<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class shopseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("shops")->insert([

            [

                "owner_id" => 1,

                "name" => "店名",

                "information" => "店舗情報",

                "filename" => "",

                "is_selling" => true,

            ],
            [

                "owner_id" => 2,

                "name" => "店名",

                "information" => "店舗情報",

                "filename" => "",

                "is_selling" => true,

            ]
        ]);
    }
}
