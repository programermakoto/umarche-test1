<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([

            "name" => "test",

            "email" => "test@test.com",

            "password" => Hash::make("Password123"),

            "created_at" => "2023/12/05 11:11:11",

            "updated_at" => "2023/12/06 11:11:11",

             ]);
    }
}
