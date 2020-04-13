<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model {
    protected $table = 'timesheets';
    protected $fillable = ['id', 'user_id', 'issue', 'next_day', 'status', 'work_day', 'created_at', 'updated_at'];
    
    public function timesheetDetail()
    {
        return $this->hasMany('App\Models\TimesheetDetail', 'timesheet_id');
    }
}
