<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = true;
    protected $fillable = ['amount', 'resturant_id', 'note'];

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant','resturant_id');
    }
}
