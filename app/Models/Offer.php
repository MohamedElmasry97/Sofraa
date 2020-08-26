<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $fillable = array('name', 'cotent', 'price_with_offer', 'from', 'to', 'resturant_id', 'food_id');

    public function food()
    {
        return $this->belongsTo('App\Models\Food');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }



}
