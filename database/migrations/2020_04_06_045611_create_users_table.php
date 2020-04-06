<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 31);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('address', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->unsignedTinyInteger('role_id');
            $table->integer('leader')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('description', 255)->nullable();
            $table->timestamps();
            $table->timestamp('deleted')->nullable();
            $table->rememberToken();

            $table->foreign('role_id')->references('id')->on('roles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
