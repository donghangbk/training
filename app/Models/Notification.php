<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    
    protected $fillable = ['id', 'user_id', 'user_receive_id', 'status', 'message', 'created_at'];
}
