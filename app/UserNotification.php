<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model {
    protected $table = 'user_notification';
    protected $fillable = ['id', 'user_id', 'user_receive_id'];
}
