<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Resturant extends Authenticatable
{
    protected $table = 'resturants';
    public $timestamps = true;
    protected $fillable = ['name', 'email', 'phone', 'password', 'minmum_order', 'delivery_fee', 'communication_phone', 'whats_up', 'resturant_image', 'status', 'city_id', 'neighborhood_id', 'pin_code'];
    protected $hidden = ['password', 'api_token'];

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function neighborhood()
    {
        return $this->belongsTo('App\Models\Neighborhood');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function foods()
    {
        return $this->belongsToMany('App\Models\Food');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function commission()
    {
        return $this->hasOne('App\Models\Commission');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }


    public function getTotalCommissionsAttribute($value)
    {
        $commissions = $this->orders()->where('status','delivered')->sum('comision');

        return $commissions;
    }

    public function getTotalOrdersAmountAttribute($value)
    {
        $commissions = $this->orders()->where('status','delivered')->sum('total_price');

        return $commissions;
    }

    public function getTotalPaymentsAttribute($value)
    {
        $payments = $this->transactions()->sum('amount');

        return $payments;
    }
}
