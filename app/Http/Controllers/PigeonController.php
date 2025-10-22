<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Driver;
use App\Models\Pigeon;
use App\Models\Restaurant;
use App\Models\OrderStatus;
use App\Mail\OrderRefunded;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\StripeClient;

class PigeonController extends Controller
{
    public function index()
    {
        $users = Cache::remember('users.count', now()->addSeconds(30), function () {
            return User::all()->count();
        });
        $sales = Cache::remember('total.sales', now()->addSeconds(30), function () {
            return Order::whereNull('error')->sum('billing_total');
        });
        $restaurants = Cache::remember('restaurants.count', now()->addSeconds(30), function () {
            return Restaurant::all()->count();
        });
        return view('dashboard.pigeon.dashboard', compact('users', 'restaurants', 'sales'));
    }

    public function users()
    {
        $users = User::whereNotNull('name')->paginate(10);
        return view('dashboard.pigeon.users.users', compact('users'));
    }

    public function drivers()
    {
        $drivers = Driver::whereNotNull('name')->paginate(10);
        return view('dashboard.pigeon.drivers.drivers', compact('drivers'));
    }

    public function restaurants()
    {
        $restaurants = Restaurant::where('active', true)->paginate(10);
        return view('dashboard.pigeon.restaurants.restaurants', compact('restaurants'));
    }

    public function applications()
    {
        $restaurants = Restaurant::where('active', false)->whereNull('password')->paginate(10);
        return view('dashboard.pigeon.restaurants.applications', compact('restaurants'));
    }

    public function orders()
    {
        $orders = Order::latest()->paginate(10);
        return view('dashboard.pigeon.orders.orders', compact('orders'));
    }

    public function settings()
    {
        $pigeon = auth()->user();
        return view('dashboard.pigeon.settings', compact('pigeon'));
    }

    public function userDetails(User $user)
    {
        $orders = Order::where('user_id', $user->id)->latest()->paginate(10);
        return view('dashboard.pigeon.users.u-details', compact('user', 'orders'));
    }

    public function orderDetails(Order $order)
    {
        $statuses = OrderStatus::where('order_id', $order->id)->get();
        return view('dashboard.pigeon.orders.o-details', compact('order', 'statuses'));
    }

    public function driverDetails(Driver $driver)
    {
        $trips = Order::getDriverCompletedOrders($driver->id)->get();
        return view('dashboard.pigeon.drivers.d-details', compact('driver', 'trips'));
    }

    public function restaurantDetails(Restaurant $restaurant)
    {
        $reviewsAvg = Cache::remember('reviews.avg.' . $restaurant->id, now()->addSeconds(30), function () use ($restaurant) {
            return number_format(Review::where('restaurant_id', $restaurant->id)->avg('rating'), 1, '.', '');
        });
        $reviewsCount = Cache::remember('reviews.count.' . $restaurant->id, now()->addSeconds(30), function () use ($restaurant) {
            return Review::where('restaurant_id', $restaurant->id)->count();
        });
        $total_orders = Cache::remember('total_orders.' . $restaurant->id, now()->addSeconds(30), function () use ($restaurant) {
            return Order::where('restaurant_id', $restaurant->id)->count();
        });
        $menu_items = Cache::remember('menu.count.' . $restaurant->id, now()->addSeconds(30), function () use ($restaurant) {
            return $restaurant->menu_items_count();
        });
        $menu_change = Cache::remember('menu.change.' . $restaurant->id, now()->addSeconds(30), function () use ($restaurant) {
            return Pigeon::getPercentatgeChange(Menu::newMenuItemsLastMonth($restaurant->id), Menu::newMenuItemsThisMonth($restaurant->id));
        });

        return view(
            'dashboard.pigeon.restaurants.r-details',
            compact('restaurant', 'menu_items', 'menu_change', 'reviewsAvg', 'reviewsCount', 'total_orders')
        );
    }

    public function cancelOrder(Order $order)
    {
        OrderStatus::create(['order_id' => $order->id, 'status' => 'cancelled']);
        return redirect()->back()->with('success', 'Order Cancelled Successfully');
    }

    public function refundOrder(Order $order)
    {
        // Stripe::refunds()->create($order->stripe_id);
        // OrderStatus::create(['order_id' => $order->id, 'status' => 'refunded']);

        // Mail::send(new OrderRefunded($order));
        // return redirect()->back()->with('success', 'Order Refunded Successfully');

        $secret = config('services.stripe.secret') ?? env('STRIPE_SECRET');
        if (!$secret) {
            return redirect()->back()->with('error', 'Stripe secret not configured.');
        }

        try {
            $stripe = new StripeClient($secret);

            // $order->stripe_id có thể là payment_intent (pi_...) hoặc charge (ch_...)
            if (str_starts_with($order->stripe_id, 'pi_')) {
                // refund by payment_intent
                $refund = $stripe->refunds->create([
                    'payment_intent' => $order->stripe_id,
                ]);
            } else {
                // fallback: try as charge id
                $refund = $stripe->refunds->create([
                    'charge' => $order->stripe_id,
                ]);
            }

            OrderStatus::create(['order_id' => $order->id, 'status' => 'refunded']);
            Mail::send(new OrderRefunded($order));

            return redirect()->back()->with('success', 'Order Refunded Successfully');
        } catch (\Exception $e) {
            \Log::error('Stripe refund error: ' . $e->getMessage(), ['order_id' => $order->id]);
            return redirect()->back()->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }

    public function activateRestaurant(Restaurant $restaurant)
    {
        $restaurant->update([
            'active' => !$restaurant->active
        ]);
        return redirect()->back();
    }

    public function delUser(User $user)
    {
        $user->delete();
        return redirect()->route('pigeon.users');
    }

    public function delRestaurant(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('pigeon.restaurants');
    }

    public function delDriver(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('pigeon.drivers');
    }

    public function setTempPassword(Restaurant $restaurant)
    {
        $data = request()->validate([
            'temp_pass' => 'required|string'
        ]);

        $restaurant->update([
            'password' => Hash::make($data['temp_pass'])
        ]);

        return redirect()->back()->with('success', 'Updated Successfully');
    }

    public function updateAccount()
    {
        $data = request()->validate([
            'name' => 'required|string|min:3',
            'username' => 'required|string|min:3|unique:pigeons,username,' . auth()->user()->id,
            'password' => 'nullable|string|min:6',
            'new_password' => 'required_if:password'
        ]);

        auth()->user()->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'new_password' => Hash::make($data['new_password']),
        ]);

        return redirect()->back()->with('success', 'Updated Successfully');
    }
}