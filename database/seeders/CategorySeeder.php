<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
  
    public function run()
    {
        DB::table("primary_categories")->insert([

            [

                "name" => "ライト",

                "sort_order" => 1,

            ],

            [

                "name" => "イス",

                "sort_order" => 2,

            ],

            [

                "name" => "テーブル",

                "sort_order" => 3,

            ],

        ]);

        //secondary_categoriesの方は外部キーがあるのでprimary_category_idが必要

        DB::table("secondary_categories")->insert([

            [

                "name" => "天井照明",

                "sort_order" => 1,

                "primary_category_id" => 1

            ],

            [

                "name" => "蛍光灯",

                "sort_order" => 2,

                "primary_category_id" => 1

            ],

            [

                "name" => "壁掛け証明",

                "sort_order" => 3,

                "primary_category_id" => 1

            ],

            [

                "name" => "ゲーミングチェア",

                "sort_order" => 4,

                "primary_category_id" => 2

            ],

            [

                "name" => "座椅子",

                "sort_order" => 5,

                "primary_category_id" => 2

            ],

            [

                "name" => "ダイニングチェア",

                "sort_order" => 6,

                "primary_category_id" => 2

            ],

        ]);
    }
}
