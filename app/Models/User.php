<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'username', 'email', 'password', 'address', 'birthday', 'avatar', 'role_id', 'leader', 'is_active', 'description', 'created_at', 'updated_at', 'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($model) {
            $model->is_active = 0;
            $model->save();
        });
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function notifications()
    {
        return $this->hasMany('App\Models\UserNotification', 'user_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y", strtotime($value));
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeRole($query, $type)
    {
        return $query->where('role_id', $type);
    }

}
