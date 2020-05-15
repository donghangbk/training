<?php

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => \Hash::make('123456789'),
            'avatar' => '/img/avatar.png',
            'role_id' => 1
        ]);

        Setting::create([
            'start_time' => '1700', 'end_time' => '1900'
        ]);
    }
}
