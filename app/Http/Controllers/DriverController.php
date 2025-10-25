<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vehicle;
use App\Models\OrderStatus;
use App\Models\DriversLicense;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\DriversLicenseRequest;

class DriverController extends Controller
{
    public function index()
    {
        // Refresh user to get latest relationships
        auth()->user()->refresh();
        auth()->user()->load(['drivers_license', 'vehicle']);
        
        $reserved = Order::getDriverReserved()->first();
        $orders = Order::getAvailableOrders()->get();
        return view('driver.driver', compact('orders', 'reserved'));
    }

    public function order(Order $order)
    {
        if ($order->driver_id !== auth()->user()->id) {
            return redirect()->back()->setStatusCode(403);
        }
        $restaurant_address = $order->status->first()->status === 'reserved'
            ? $order->restaurant->fullAddress()
            : $order->fullAddress();
        return view('driver.order', compact('order', 'restaurant_address'));
    }

    public function trips()
    {
        $trips = Order::getDriverCompletedOrders()->paginate(5);
        return view('driver.trips', compact('trips'));
    }

    public function setup()
    {
        if (auth()->user()->drivers_license) {
            return redirect()->back();
        }
        return view('driver.setup');
    }

    public function vehicle()
    {
        if (auth()->user()->vehicle) {
            $reserved = Order::getDriverReserved()->first();
            $orders = Order::getAvailableOrders()->get();
            return view('driver.driver', compact('orders', 'reserved'));
        }
        $types = ['Automobile', 'Motorcycle', 'Scooter', 'Moped'];
        return view('driver.vehicle', compact('types'));
    }

    public function profilePicture()
    {
        $data = request()->validate(['image' => 'required|image']);

        // Process image before storing
        $image = Image::read($data['image'])
            ->cover(600, 600);
        
        // Generate unique filename
        $filename = uniqid() . '.' . $data['image']->getClientOriginalExtension();
        $path = 'uploads/drivers/' . $filename;
        
        // Save processed image
        $image->save(storage_path('app/public/' . $path));

        auth()->user()->update([
            'profile_picture' => $path
        ]);

        return redirect()->back();
    }

    public function reserve(Order $order)
    {
        if (!auth()->user()->drivers_license || !auth()->user()->vehicle) {
            return redirect()->back()->withErrors('Complete your profile to reserve an order.');
        }
        
        // Check if driver already has a reserved order
        $hasReserved = Order::where('driver_id', auth()->user()->id)
            ->whereDoesntHave('status', function ($q) {
                $q->where('status', 'delivered');
            })->exists();
            
        if ($hasReserved) {
            return redirect()->back()->withErrors('You already have an active reserved order.');
        }
        $order->update(['driver_id' => auth()->user()->id]);
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'reserved',
            'created_at' => now()
        ]);
        $restaurant_address = $order->status->first()->status === 'reserved'
            ? $order->restaurant->fullAddress()
            : $order->fullAddress();
        return view('driver.order', compact('order', 'restaurant_address'));
    }

    public function foodPickupComplete(Order $order)
    {
        if ($order->driver_id !== auth()->user()->id) {
            return redirect()->back()->setStatusCode(403);
        }
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'food_picked_up',
            'created_at' => now()
        ]);
        $restaurant_address = $order->fullAddress();
        return view('driver.order', compact('order', 'restaurant_address'));
    }

    public function foodDeliveryComplete(Order $order)
    {
        if ($order->driver_id !== auth()->user()->id) {
            return redirect()->back()->setStatusCode(403);
        }
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'delivered',
            'created_at' => now()
        ]);
        
        // Redirect về driver dashboard để nhận đơn mới
        return redirect()->route('driver.index')->with('success', 'Order delivered successfully! You can now pick up new orders.');
    }

    public function storeDriversLicense(DriversLicenseRequest $request)
    {
        // Check if driver already has a license
        if (auth()->user()->drivers_license) {
            return redirect()->back()->withErrors('You have already submitted your drivers license information.');
        }

        try {
            DriversLicense::create([
                'driver_id' => auth()->user()->id,
                'license_number' => Crypt::encryptString($request['license_number']),
                'reference_number' => Crypt::encryptString($request['reference_number']),
                'dob' => $request['dob'],
                'valid_on' => $request['valid_on'],
                'expires_on' => $request['expires_on'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error saving drivers license: ' . $e->getMessage())->withInput();
        }

        // Redirect based on vehicle status
        auth()->user()->refresh();
        auth()->user()->load(['drivers_license', 'vehicle']);
        
        if (auth()->user()->vehicle) {
            $reserved = Order::getDriverReserved()->first();
            $orders = Order::getAvailableOrders()->get();
            return view('driver.driver', compact('orders', 'reserved'));
        } else {
            $types = ['Automobile', 'Motorcycle', 'Scooter', 'Moped'];
            return view('driver.vehicle', compact('types'));
        }
    }

    public function storeVehicle()
    {
        $data = request()->validate([
            'type' => 'required|string',
            'plate' => 'required|string|min:2|max:10',
            'model' => 'required|string|min:2|max:50',
            'year' => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'color' => 'required|string|min:2|max:20'
        ]);

        auth()->user()->update([
            'type' => $data['type']
        ]);

        Vehicle::create([
            'driver_id' => auth()->user()->id,
            'license_plate' => $data['plate'],
            'car_model' => $data['model'],
            'year' => $data['year'],
            'color' => $data['color']
        ]);

        // Refresh user to get latest relationships
        auth()->user()->refresh();
        auth()->user()->load(['drivers_license', 'vehicle']);

        if (auth()->user()->drivers_license) {
            $reserved = Order::getDriverReserved()->first();
            $orders = Order::getAvailableOrders()->get();
            return view('driver.driver', compact('orders', 'reserved'));
        } else {
            return view('driver.setup');
        }
    }
}