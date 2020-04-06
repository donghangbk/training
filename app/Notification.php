<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    protected $table = 'notifications';
    protected $fillable = ['id', 'user_id', 'user_receive_id', 'status', 'message', 'created_at'];
}
