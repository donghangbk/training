<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetDetail extends Model {

    protected $table = 'timesheet_detail';
    
    protected $fillable = ['id', 'timesheet_id', 'task_id', 'content', 'time'];
    
    public $timestamps = false;
}
