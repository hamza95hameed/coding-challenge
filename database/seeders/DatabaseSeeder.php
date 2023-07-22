<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        foreach(range(1,20) as $item)
        {
            DB::table('users')->insert([
                'name' => 'User'. $item,
                'email' => 'user'. $item. '@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
