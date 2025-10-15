<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Restaurant extends Authenticatable
{
    //Restaurant model
    use Notifiable;

    protected $guard = 'restaurant';

    protected $fillable = ['name', 'slug', 'email', 'phone', 'password', 'category_id', 'image', 'delivery_fee', 'active'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getStatus()
    {
        return $this->active === 1 ? 'Active' : 'Not Active';
    }

    public function delete()
    {
        $this->address()->delete();
        $this->menu_items()->delete();
        $this->categories()->detach();
        return parent::delete();
    }

    public function menu_items()
    {
        return $this->hasMany(Menu::class);
    }

    public function menu_items_count()
    {
        return $this->menu_items()->count();
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'account_id')->where('description', 'restaurant');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->orderBy('name');
    }

    public function hours()
    {
        return $this->hasMany(RestaurantHours::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function fullAddress()
    {
        return $this->address->street_address . ', ' . $this->address->city . ' ' .
            $this->address->province . ', ' . $this->address->postal_code;
    }

    public function scopeOrderByAvgRating($query)
    {
        return $query->where('active', true)->withCount([
            'reviews as average_review' => function ($query) {
                $query->select(\DB::raw('coalesce(avg(rating),0)'));
            }
        ])->orderByDesc('average_review')->paginate(12);
    }

    public function scopeOrderByPrice($query)
    {
        return $query->where('active', true)->withCount([
            'categories as category_price' => function ($query) {
                $query->select(\DB::raw('category_id'))->whereIn('category_id', ['1', '2', '3']);
            }
        ])->orderBy('category_price')->paginate(12);
    }

    public function scopeOrderByDeliveryFee($query)
    {
        return $query->where('active', true)->orderBy('delivery_fee')->paginate(12);
    }

    public function scopeSearchRestaurants($query, $search)
    {
        return $query->where('active', true)->where('name', 'like', '%' . $search . '%')->paginate(12);
    }
}
