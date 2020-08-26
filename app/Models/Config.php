<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    protected $table = 'config';
    public $timestamps = true;
    protected $fillable = array('account_bank', 'comission' , 'account_bank2', 'text');

}
