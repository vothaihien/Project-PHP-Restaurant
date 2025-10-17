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
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;

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
            'name' => 'GST/QST 14.975%',
            'type' => 'tax',
            'target' => 'total',
            'value' => '14.975%',
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
        // Debug: Log session data
        \Log::info('Session data:', \Session::all());
        \Log::info('Address in session:', \Session::get('address'));
        
        if (!\Session::has('address')) {
            return redirect()->back()->withErrors('Please enter your delivery address.');
        }
        $cart = \Cart::session($restaurant->id);
        $contents = $cart->getContent()->map(function ($item) {
            return $item->name . ', ' . $item->quantity;
        })->values()->toJson();

        try {
            $charge = Stripe::charges()->create([
                'amount' => $cart->getTotal(),
                'currency' => 'CAD',
                'source' => request()->stripeToken,
                'description' => 'Order',
                'receipt_email' => auth()->user()->email,
                'metadata' => [
                    'contents' => $contents,
                    'quantity' => $cart->getTotalQuantity()
                ],
            ]);

            $order = $this->addToOrdersTables($restaurant->id, $charge['id'], null);

            //SEND ORDER PLACED EMAIL TO USER
            Mail::send(new OrderPlaced($order));

            //SUCCESSFUL
            $cart->clear();

            return view('order-complete')->with('success', 'Order Completed Successfully');
        } catch (CardErrorException $e) {
            $this->addToOrdersTables($restaurant->id, null, $e->getMessage());
            return redirect()->back()->withErrors('Error! ' . $e->getMessage());
        }
    }

    protected function addToOrdersTables($rest_id, $charge_id, $error)
    {
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'restaurant_id' => $rest_id,
            'total_items_qty' => \Cart::getTotalQuantity(),
            'billing_subtotal' => \Cart::getSubtotal(),
            'billing_delivery' => \Cart::getCondition('Delivery Fee')->getValue(),
            'billing_tax' => number_format(\Cart::getCondition('GST/QST 14.975%')->getCalculatedValue(\Cart::getSubTotal()), 2, '.', ','),
            'driver_tip' => number_format(\Cart::getCondition('Tip')->getValue(), 2, '.', ','),
            'billing_total' => \Cart::getTotal(),
            'stripe_id' => $charge_id,
            'error' => $error
        ]);
        if ($error) {
            OrderStatus::create(['order_id' => $order->id, 'status' => 'failed']);
        } else {
            OrderStatus::create(['order_id' => $order->id, 'status' => 'new']);
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
            ]);
        }

        return $order;
    }
}