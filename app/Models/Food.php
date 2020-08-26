<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{

    protected $table = 'foods';
    public $timestamps = true;
    protected $fillable = array('food_image', 'name', 'description', 'price', 'timeReady');

    public function offer()
    {
        return $this->hasOne('App\Models\Offer');
    }

    public function resturants()
    {
        return $this->belongsToMany('App\Models\Resturant');
    }

    public function carts()
    {
        return $this->belongsToMany('App\Models\Cart');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }

}
