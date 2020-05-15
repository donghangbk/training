<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model {
    const TIMESHEET_WAITING = 0;

    protected $fillable = ['id', 'user_id', 'issue', 'next_day', 'status', 'work_day', 'created_at', 'updated_at'];

    public function tasks()
    {
        return $this->hasMany('App\Models\TimesheetDetail', 'timesheet_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y", strtotime($value));
    }
}
