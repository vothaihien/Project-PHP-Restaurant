<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\OrderStatus;
use App\Models\RestaurantHours;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\SetOperatingHoursRequest;

class RestaurantController extends Controller
{
    public function index()
    {
        $reviewsCount = Cache::remember('reviews.count', now()->addSeconds(30), function () {
            return Review::where('restaurant_id', auth()->user()->id)->count();
        });
        $menusCount = Cache::remember('menus.count', now()->addSeconds(30), function () {
            return Menu::where('restaurant_id', auth()->user()->id)->count();
        });
        
        // Calculate sales data for charts
        $restaurantId = auth()->user()->id;
        
        // Monthly sales (last 9 months)
        $monthlySales = [];
        for ($i = 8; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $sales = Order::where('restaurant_id', $restaurantId)
                ->whereNull('error')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('billing_total');
            $monthlySales[] = round($sales, 2); // Keep actual dollar amount
        }
        
        // Weekly sales (last 9 weeks)
        $weeklySales = [];
        for ($i = 8; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $sales = Order::where('restaurant_id', $restaurantId)
                ->whereNull('error')
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('billing_total');
            $weeklySales[] = round($sales, 2); // Keep actual dollar amount
        }
        
        // Total orders count
        $totalOrders = Order::where('restaurant_id', $restaurantId)->count();
        
        // Monthly orders count (last 6 months for orders chart)
        $monthlyOrders = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Order::where('restaurant_id', $restaurantId)
                ->whereNull('error')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyOrders[] = $count;
        }
        
        return view('dashboard.restaurant.dashboard', compact(
            'reviewsCount', 
            'menusCount', 
            'monthlySales', 
            'weeklySales',
            'totalOrders',
            'monthlyOrders'
        ));
    }

    public function management()
    {
        $categories = Category::pluck('id', 'name');

        return view('dashboard.restaurant.management', compact('categories'));
    }

    public function menu()
    {
        $menu_items = Menu::where('restaurant_id', auth()->id())->paginate(10);
        return view('dashboard.restaurant.menu', compact('menu_items'));
    }

    public function orders()
    {
        $orders = Order::where('restaurant_id', auth()->id())->paginate(10);
        return view('dashboard.restaurant.orders', compact('orders'));
    }

    public function reviews()
    {
        $avgRating = Cache::remember('reviews.avg', now()->addSeconds(30), function () {
            return number_format(Review::where('restaurant_id', auth()->user()->id)->avg('rating'), 1, '.', '');
        });
        $reviews = Review::where('restaurant_id', auth()->id())->latest()->paginate(10);
        return view('dashboard.restaurant.reviews', compact('reviews', 'avgRating'));
    }

    public function newMenuItem()
    {
        $categories = Category::noPriceRange();
        return view('dashboard.restaurant.newmenuitem', compact('categories'));
    }

    public function editMenuItem(Menu $menu)
    {
        $categories = Category::noPriceRange();
        return view('dashboard.restaurant.editmenuitem', compact('menu', 'categories'));
    }

    public function orderDetails(Order $order)
    {
        return view('dashboard.restaurant.orderdetails', compact('order'));
    }

    public function createMenuItem()
    {
        $data = request()->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'category_id' => 'nullable|numeric',
            'price' => 'required|numeric|between:0,200'
        ]);

        if (isset($data['image'])) {
            // Process image before storing
            $image = Image::read($data['image'])
                ->cover(1200, 1200);
            
            // Generate unique filename
            $filename = uniqid() . '.' . $data['image']->getClientOriginalExtension();
            $imagePath = 'uploads/menu/' . auth()->user()->id . '/' . $filename;
            
            // Ensure directory exists
            $directory = storage_path('app/public/uploads/menu/' . auth()->user()->id);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save processed image
            $image->save(storage_path('app/public/' . $imagePath));
        } else {
            $imagePath = null;
        }

        Menu::create([
            'restaurant_id' => auth()->user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $imagePath,
            'category_id' => $data['category_id'],
            'price' => $data['price']
        ]);

        return redirect()->route('restaurant.menu');
    }

    public function updateMenuItem(Menu $menu)
    {
        $data = request()->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'category_id' => 'nullable|numeric',
            'price' => 'required|numeric|between:0,200'
        ]);

        if (isset($data['image'])) {
            // Delete old image if exists
            if (File::exists(public_path('storage/' . $menu->image))) {
                File::delete(public_path('storage/' . $menu->image));
            }
            
            // Process image before storing
            $image = Image::read($data['image'])
                ->cover(1200, 1200);
            
            // Generate unique filename
            $filename = uniqid() . '.' . $data['image']->getClientOriginalExtension();
            $imagePath = 'uploads/menu/' . auth()->user()->id . '/' . $filename;
            
            // Ensure directory exists
            $directory = storage_path('app/public/uploads/menu/' . auth()->user()->id);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save processed image
            $image->save(storage_path('app/public/' . $imagePath));
        } else {
            if (isset($menu->image)) {
                $imagePath = $menu->image;
            } else {
                $imagePath = null;
            }
        }
        Menu::findOrFail($menu->id)->update([
            'restaurant_id' => auth()->user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $imagePath,
            'category_id' => $data['category_id'],
            'price' => $data['price']
        ]);

        return redirect()->route('restaurant.menu');
    }

    public function deleteMenuItem(Menu $menu)
    {
        if (File::exists(public_path('storage/' . $menu->image))) {
            File::delete(public_path('storage/' . $menu->image));
        }
        Menu::destroy($menu->id);
        return redirect()->back()->with('success', 'Menu Item Deleted Successfully.');
    }

    public function addCategory()
    {
        $data = request()->validate([
            'category_id' => 'required|numeric'
        ]);

        if (!auth()->user()->categories->contains($data['category_id'])) {
            switch ($data['category_id']) {
                case 1:
                    auth()->user()->categories()->detach(['2', '3']);
                    auth()->user()->categories()->attach('1');
                    break;
                case 2:
                    auth()->user()->categories()->detach(['1', '3']);
                    auth()->user()->categories()->attach('2');
                    break;
                case 3:
                    auth()->user()->categories()->detach(['1', '2']);
                    auth()->user()->categories()->attach('3');
                    break;
                default:
                    auth()->user()->categories()->attach($data['category_id']);
            }
        } else {
            return redirect()->back()->with('error', 'That category already exists for this restaurant');
        }

        return redirect()->back();
    }

    public function setImage()
    {
        $currentUser = auth()->user();
        $data = request()->validate([
            'image' => 'required|image'
        ]);

        if (
            File::exists(public_path('storage/' . $currentUser->image))
            && $currentUser->image !== 'uploads/default.jpeg'
        ) {
            File::delete(public_path('storage/' . $currentUser->image));
        }

        // Process image before storing
        $image = Image::read($data['image'])
            ->cover(1200, 1200);
        
        // Generate unique filename
        $filename = uniqid() . '.' . $data['image']->getClientOriginalExtension();
        $imagePath = 'uploads/' . $filename;
        
        // Save processed image
        $image->save(storage_path('app/public/' . $imagePath));

        Restaurant::findOrFail($currentUser->id)->update([
            'image' => $imagePath
        ]);

        return redirect()->back();
    }

    public function setOperatingHours(SetOperatingHoursRequest $request)
    {
        $array = $request->validated();

        for ($i = 1; $i < 8; $i++) {
            RestaurantHours::create([
                'restaurant_id' => auth()->user()->id,
                'day' => $i,
                'open_time' => $array[$i . '-open'],
                'close_time' => $array[$i . '-close'],
            ]);
        }

        return redirect()->back();
    }

    public function updateOperatingHours(SetOperatingHoursRequest $request)
    {
        $array = $request->validated();

        for ($i = 1; $i < 8; $i++) {
            RestaurantHours::where('restaurant_id', auth()->user()->id)->where('day', $i)->update([
                'open_time' => $array[$i . '-open'],
                'close_time' => $array[$i . '-close'],
            ]);
        }

        return redirect()->back();
    }

    public function completeOrder(Order $order)
    {
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'food_ready_for_pickup',
            'created_at' => now()
        ]);
        return redirect()->back();
    }

    public function cancelOrder(Order $order)
    {
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'created_at' => now()
        ]);
        return redirect()->back();
    }
}