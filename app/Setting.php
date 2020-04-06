<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    protected $table = 'setting';
    protected $fillable = ['id', 'start_time', 'end_time'];
}
