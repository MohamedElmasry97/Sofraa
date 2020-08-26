<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = ['name', 'phone', 'email', 'password', 'city_id', 'neighborhood_id', 'pin_code'];
    protected $hidden = ['password', 'api_token'];

    public function neighborhoods()
    {
        return $this->belongsTo('App\Models\Neighborhood');
    }

    public function cities()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function cart()
    {
        return $this->hasOne('App\Models\Cart');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }
}
