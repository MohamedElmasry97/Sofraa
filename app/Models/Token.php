<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';
    public $timestamps = true;

    protected $fillable = ['token', 'type', 'toeknable_id', 'tokenable_type'];

    public function tokenable()
    {
        return $this->morphTo();
    }
}
