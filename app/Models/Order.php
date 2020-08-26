<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = ['payment', 'total_price', 'delivery_address', 'comision', 'net', 'delivery_fees', 'price', 'note', 'status', 'resturant_id', 'client_id'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function foods()
    {
        return $this->belongsToMany('App\Models\Food')->withPivot('price', 'note', 'quantity');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }
}
