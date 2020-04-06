<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timesheet_id');
            $table->string('task_id', 20)->nullable();
            $table->string('content', 255);
            $table->unsignedSmallInteger('time');

            $table->foreign('timesheet_id')->references('id')->on('timesheets');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_detail');
    }
}
