<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderMenu;
use App\Models\OrderStatus;
use App\Models\Restaurant;
use App\Models\RestaurantHours;
use App\Models\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
use Darryldecode\Cart\CartCondition;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        if (\Cart::session($restaurant->id)->isEmpty() || !RestaurantHours::isOpen($restaurant->id)) {
            return redirect()->back();
        }
        $delivery_condition = new CartCondition(array(
            'name' => 'Delivery Fee',
            'type' => 'delivery',
            'target' => 'total',
            'value' => $restaurant->delivery_fee,
            'order' => 1
        ));
        $tax_condition = new CartCondition(array(
            'name' => 'VAT 10%',
            'type' => 'tax',
            'target' => 'total',
            'value' => '10%',
            'order' => 2
        ));
        $tip = new CartCondition(array(
            'name' => 'Tip',
            'type' => 'tip',
            'target' => 'total',
            'value' => '2.00',
            'order' => 3
        ));
        \Cart::session($restaurant->id)->condition([
            $delivery_condition,
            $tax_condition,
            \Cart::getCondition('Tip') ?: $tip
        ]);

        return view('checkout', ['restaurant' => $restaurant]);
    }

    public function tip(Restaurant $restaurant)
    {
        $data = request()->validate([
            'tip' => 'required|numeric|min:0|max:500'
        ]);

        $tip = new CartCondition(array(
            'name' => 'Tip',
            'type' => 'tip',
            'target' => 'total',
            'value' => $data['tip'],
            'order' => 3
        ));

        \Cart::session($restaurant->id)->removeCartCondition('Tip');
        \Cart::condition($tip);
        return redirect()->back();
    }

    public function store(Restaurant $restaurant)
    {
        $user = auth()->user();
        $sessionAddress = \Session::get('address');

        // Log thông tin để debug
        \Log::info('Checkout Process:', [
            'user_id' => $user->id,
            'session_address' => $sessionAddress,
            'has_session_address' => !empty($sessionAddress)
        ]);

        // Kiểm tra địa chỉ đã lưu
        $storedAddress = Address::where('account_id', $user->id)
            ->where('description', 'delivery')
            ->first();

        \Log::info('Stored Address:', [
            'stored_address' => $storedAddress
        ]);

        if (empty($sessionAddress) && !$storedAddress) {
            \Log::error('No address found for checkout');
            return redirect()->back()->withErrors('Please enter your delivery address.');
        }

        // Nếu có địa chỉ trong session, lưu nó cho user
        if (!empty($sessionAddress)) {
            try {
                $this->saveAddressFromSession($user, $sessionAddress);
                \Log::info('New address saved from session');
            } catch (\Exception $e) {
                \Log::error('Error saving address: ' . $e->getMessage());
            }
        }

        try {
            $cart = \Cart::session($restaurant->id);
            $order = $this->addToOrdersTables($restaurant->id, null, null);

            return view('checkout.payment', [
                'order' => $order,
                'restaurant' => $restaurant
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in checkout process: ' . $e->getMessage());
            return redirect()->back()->withErrors('An error occurred during checkout. Please try again.');
        }
    }

    protected function saveAddressFromSession($user, $sessionAddress)
    {
        \Log::info('Saving address from session:', [
            'session_data' => $sessionAddress
        ]);

        try {
            // Kiểm tra xem địa chỉ đã tồn tại chưa
            $existingAddress = Address::where('account_id', $user->id)
                ->where('description', 'delivery')
                ->first();

            if (!$existingAddress) {
                $addressData = [
                    'account_id' => $user->id,
                    'description' => 'delivery'
                ];

                // Xử lý dựa trên loại địa chỉ
                if (isset($sessionAddress['place_type']) && $sessionAddress['place_type'] == 'poi') {
                    $addressData += [
                        'street_address' => $sessionAddress['short'] ?? '',
                        'city' => $sessionAddress['context']['city'] ?? '',
                        'province' => $sessionAddress['context']['province'] ?? '',
                        'postal_code' => isset($sessionAddress['place_name']) ?
                            ltrim(strstr(ltrim(explode(',', $sessionAddress['place_name'])[3]), ' ')) : '',
                        'country' => $sessionAddress['context']['country'] ?? '',
                        'longitude' => $sessionAddress['coordinates'][0] ?? null,
                        'latitude' => $sessionAddress['coordinates'][1] ?? null,
                        'added_on' => now(),
                    ];
                } else {
                    $addressData += [
                        'street_address' => $sessionAddress['place_name'] ?? '',
                        'city' => $sessionAddress['context']['city'] ?? '',
                        'province' => $sessionAddress['context']['province'] ?? '',
                        'postal_code' => isset($sessionAddress['place_name']) ?
                            ltrim(strstr(ltrim(explode(',', $sessionAddress['place_name'])[2]), ' ')) : '',
                        'country' => $sessionAddress['context']['country'] ?? '',
                        'longitude' => $sessionAddress['coordinates'][0] ?? null,
                        'latitude' => $sessionAddress['coordinates'][1] ?? null,
                        'added_on' => now(),
                    ];
                }

                \Log::info('Creating new address:', $addressData);
                Address::create($addressData);
            } else {
                \Log::info('Address already exists:', [
                    'existing_address' => $existingAddress
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in saveAddressFromSession: ' . $e->getMessage());
            throw $e;
        }
    }
    protected function addToOrdersTables($rest_id, $charge_id = null, $error = null)
    {
        $cart = \Cart::session($rest_id);

        $orderData = [
            'user_id' => auth()->user()->id,
            'restaurant_id' => $rest_id,
            'total_items_qty' => $cart->getTotalQuantity(),
            'billing_subtotal' => $cart->getSubtotal(),
            'billing_delivery' => $cart->getCondition('Delivery Fee')->getValue(),
            'billing_tax' => number_format($cart->getCondition('VAT 10%')->getCalculatedValue($cart->getSubTotal()), 2, '.', ','),
            'driver_tip' => number_format($cart->getCondition('Tip')->getValue(), 2, '.', ','),
            'billing_total' => $cart->getTotal(),
            'payment_status' => 'pending'
        ];

        if ($charge_id) {
            $orderData['stripe_id'] = $charge_id;
        }

        if ($error) {
            $orderData['error'] = $error;
        }

        $order = Order::create($orderData);
        if ($error) {
            OrderStatus::create(['order_id' => $order->id, 'status' => 'failed', 'created_at' => now()]);
        } else {
            OrderStatus::create(['order_id' => $order->id, 'status' => 'new', 'created_at' => now()]);
        }
        foreach (\Cart::getContent() as $item) {
            OrderMenu::create([
                'order_id' => $order->id,
                'menu_id' => $item->id,
                'quantity' => $item->quantity,
                'special' => $item->attributes['instructions']
            ]);
        }
        if (\Session::get('address.place_type') == 'address') {
            Address::create([
                'account_id' => $order->id,
                'description' => 'delivery',
                'street_address' => strtok(\Session::get('address.place_name'), ','),
                'city' => \Session::get('address.context.city'),
                'province' => \Session::get('address.context.province'),
                'postal_code' => ltrim(strstr(ltrim(explode(',', \Session::get('address.place_name'))[2]), ' ')),
                'country' => \Session::get('address.context.country'),
                'longitude' => \Session::get('address.coordinates.0'),
                'latitude' => \Session::get('address.coordinates.1'),
                'added_on' => now()
            ]);
        } elseif (\Session::get('address.place_type') == 'poi') {
            Address::create([
                'account_id' => $order->id,
                'description' => 'delivery',
                'street_address' => \Session::get('address.short'),
                'city' => \Session::get('address.context.city'),
                'province' => \Session::get('address.context.province'),
                'postal_code' => ltrim(strstr(ltrim(explode(',', \Session::get('address.place_name'))[3]), ' ')),
                'country' => \Session::get('address.context.country'),
                'longitude' => \Session::get('address.coordinates.0'),
                'latitude' => \Session::get('address.coordinates.1'),
                'added_on' => now()
            ]);
        }

        return $order;
    }
}