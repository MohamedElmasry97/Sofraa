<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model 
{

    protected $table = 'commissions';
    public $timestamps = true;
    protected $fillable = array('total_food_price', 'commission_application', 'commission_received', 'commission_left', 'resturant_id');

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

}