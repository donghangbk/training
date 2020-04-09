<?php

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
        // $this->call(UsersTableSeeder::class);
        DB::table('roles')->insert([
            ['name' => 'admin'],
        ]);

        DB::table('roles')->insert([
            ['name' => 'user'],
        ]);

        DB::table('users')->insert([
            ['username' => 'admin'],
            ['email' => 'admin@gmail.com'],
            ['password' => '$2y$10$meq8xtxH38GbuzFd6PoRW.dysV2Bg0Xm0KmAO0zWTdaR1KeEv40HG'],
            ['avatar' => '/img/avatar.png'],
            ['role_id'] => 1
        ]);

        DB::table('setting')->insert([
            ['start_time' => '1700'],
            ['end_time' => '1900']
        ]);
    }
}
