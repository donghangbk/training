<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model {
    protected $table = 'user_notification';
    protected $fillable = ['id', 'user_id', 'user_receive_id'];
    public $timestamps = false;

    public function info() {
        return $this->belongsTo('App\Models\User', 'user_receive_id', 'id');
    }
}
