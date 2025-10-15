<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PigeonController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');
Auth::routes();

Route::get('/login/pigeon', [LoginController::class, 'showPigeonLoginForm'])->name('login.pigeon');
Route::get('/login/driver', [LoginController::class, 'showDriverLoginForm'])->name('login.driver');
Route::get('/login/restaurant', [LoginController::class, 'showRestaurantLoginForm'])->name('login.restaurant');
;
Route::get('/register/pigeon', [RegisterController::class, 'showPigeonRegisterForm'])->name('register.pigeon');
Route::get('/register/driver', [RegisterController::class, 'showDriverRegisterForm'])->name('register.driver');
Route::get('/register/restaurant', [RegisterController::class, 'showRestaurantRegisterForm'])->name('register.restaurant');

Route::post('/login/pigeon', [LoginController::class, 'pigeonLogin']);
Route::post('/login/driver', [LoginController::class, 'driverLogin']);
Route::post('/login/restaurant', [LoginController::class, 'restaurantLogin']);
Route::post('/register/pigeon', [RegisterController::class, 'createPigeon'])->name('register.pigeon');
Route::post('/register/driver', [RegisterController::class, 'createDriver'])->name('register.driver');
Route::post('/register/restaurant', [RegisterController::class, 'createRestaurant'])->name('register.restaurant');
Route::view('/get-back-to-you', 'get-back-to-you');

Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::post('/home', [HomeController::class, 'address']);
Route::get('/account/settings', [HomeController::class, 'setting'])->middleware('auth');
Route::get('/r/{restaurant}', [HomeController::class, 'show'])->name('home.show');

Route::post('/cart/{menu}', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{menu}', [CartController::class, 'remove'])->name('cart.remove');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/r/{restaurant}/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/u/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::view('/order-complete', 'order-complete');

    Route::post('/u/orders/{order}/review', [ReviewController::class, 'store']);
    Route::post('/r/{restaurant}/favorite', [HomeController::class, 'favorite'])->name('home.favorite');
    Route::post('/r/{restaurant}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/r/{restaurant}/checkout/tip', [CheckoutController::class, 'tip'])->name('checkout.tip');
});

Route::group(['middleware' => 'auth:driver'], function () {
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');
    Route::get('/d/order/{order}', [DriverController::class, 'order'])->name('driver.order');
    Route::get('/driver/setup', [DriverController::class, 'setup'])->name('driver.setup');
    Route::get('/driver/setup/2', [DriverController::class, 'vehicle'])->name('driver.vehicle');
    Route::get('/trips', [DriverController::class, 'trips'])->name('driver.trips');

    Route::post('/driver', [DriverController::class, 'profilePicture'])->name('driver.image');
    Route::post('/driver/{order}', [DriverController::class, 'reserve'])->name('driver.reserve');
    Route::post('/driver/setup/1', [DriverController::class, 'storeDriversLicense'])->name('driver.storeDriversLicense');
    Route::post('/driver/setup/2', [DriverController::class, 'storeVehicle'])->name('driver.storeVehicle');
    Route::post('/d/order/{order}', [DriverController::class, 'foodPickupComplete'])->name('driver.foodPickupComplete');
    Route::post('/d/order/{order}/delivered', [DriverController::class, 'foodDeliveryComplete'])->name('driver.foodDeliveryComplete');
});

Route::group(['middleware' => 'auth:restaurant'], function () {
    Route::get('/restaurant', [RestaurantController::class, 'index'])->name('restaurant.index');
    Route::get('/menu', [RestaurantController::class, 'menu'])->name('restaurant.menu');
    Route::get('/menu/new', [RestaurantController::class, 'newMenuItem'])->name('restaurant.newMenuItem');
    Route::get('/menu/{menu}/edit', [RestaurantController::class, 'editMenuItem'])->name('restaurant.editMenuItem');
    Route::get('/management', [RestaurantController::class, 'management'])->name('restaurant.manage');
    Route::get('/orders', [RestaurantController::class, 'orders'])->name('restaurant.orders');
    Route::get('/orders/{order}', [RestaurantController::class, 'orderDetails'])->name('restaurant.orderDetails');
    Route::get('/reviews', [RestaurantController::class, 'reviews'])->name('restaurant.reviews');

    Route::post('/menu/new', [RestaurantController::class, 'createMenuItem'])->name('restaurant.createMenuItem');
    Route::patch('/menu/{menu}/edit', [RestaurantController::class, 'updateMenuItem'])->name('restaurant.updateMenuItem');
    Route::delete('/menu/{menu}/edit', [RestaurantController::class, 'deleteMenuItem'])->name('restaurant.deleteMenuItem');
    Route::post('/set-image', [RestaurantController::class, 'setImage'])->name('setImage');
    Route::post('/set-hours', [RestaurantController::class, 'setOperatingHours'])->name('setOperatingHours');
    Route::patch('/update-hours', [RestaurantController::class, 'updateOperatingHours'])->name('updateOperatingHours');
    Route::post('/add-category', [RestaurantController::class, 'addCategory'])->name('addCategory');
    Route::post('/orders/{order}', [RestaurantController::class, 'completeOrder'])->name('restaurant.completeOrder');
    Route::patch('/orders/{order}', [RestaurantController::class, 'cancelOrder'])->name('restaurant.cancelOrder');
});

Route::group(['middleware' => 'auth:pigeon'], function () {
    Route::get('/pigeon', [PigeonController::class, 'index'])->name('pigeon.index');
    Route::get('/users', [PigeonController::class, 'users'])->name('pigeon.users');
    Route::get('/users/{user}/details', [PigeonController::class, 'userDetails'])->name('pigeon.userDetails');
    Route::get('/drivers', [PigeonController::class, 'drivers'])->name('pigeon.drivers');
    Route::get('/drivers/{driver}/details', [PigeonController::class, 'driverDetails'])->name('pigeon.driverDetails');
    Route::get('/restaurants', [PigeonController::class, 'restaurants'])->name('pigeon.restaurants');
    Route::get('/restaurants/applications', [PigeonController::class, 'applications'])->name('pigeon.applications');
    Route::get('/restaurants/{restaurant}/details', [PigeonController::class, 'restaurantDetails'])->name('pigeon.restaurantDetails');
    Route::get('/all-orders', [PigeonController::class, 'orders'])->name('pigeon.orders');
    Route::get('/orders/{order}/details', [PigeonController::class, 'orderDetails'])->name('pigeon.orderDetails');
    Route::get('/account/settings', [PigeonController::class, 'settings'])->name('pigeon.settings');

    Route::post('/users/order/{order}', [PigeonController::class, 'refundOrder'])->name('pigeon.refundOrder');
    Route::patch('/users/order/{order}', [PigeonController::class, 'cancelOrder'])->name('pigeon.cancelOrder');
    Route::post('/restaurants/{restaurant}/details', [PigeonController::class, 'setTempPassword'])->name('pigeon.setTempPass');
    Route::patch('/restaurants/{restaurant}/details', [PigeonController::class, 'activateRestaurant'])->name('pigeon.activateRestaurant');
    Route::delete('/users/{user}/details', [PigeonController::class, 'delUser'])->name('pigeon.delUser');
    Route::delete('/restaurants/{restaurant}/details', [PigeonController::class, 'delRestaurant'])->name('pigeon.delRestaurant');
    Route::delete('/drivers/{driver}/details', [PigeonController::class, 'delDriver'])->name('pigeon.delDriver');
    Route::patch('/account/settings', [PigeonController::class, 'updateAccount'])->name('pigeon.updateAccount');
});

Route::view('/privacy', 'privacy')->name('privacy');

Route::fallback(function () {
    return view('fallback');
});