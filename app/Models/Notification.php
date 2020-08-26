<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['content' , 'title' , 'is-read' ,'notifiable_id','notifiable_type', 'date'];
    protected $table = 'notifications';
    public $timestamps = true;


    public function notifiable()
    {
        return $this->morphTo();
    }
}
