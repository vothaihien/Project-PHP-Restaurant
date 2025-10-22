<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_id', 'description', 'street_address', 'city', 'province', 'postal_code', 'country', 'longitude', 'latitude', 'added_on'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'account_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'account_id', 'id')
            ->where('description', 'delivery');
    }

    public static function getCity($user)
    {
        return Order::where('user_id', $user)->latest()->first()->address->city ?? 'N/A';
    }
}