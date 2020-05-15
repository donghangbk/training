<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    
    protected $table = 'settings';
    protected $fillable = ['id', 'start_time', 'end_time'];
    public $timestamps = false;
}
