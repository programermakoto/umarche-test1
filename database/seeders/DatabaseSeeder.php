<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 追加シーダーの呼び出し
        $this->call([

            AdminSeeder::class,
            
            OwnerSeeder::class,

            shopSeeder::class,

            ImageSeeder::class,

            CategorySeeder::class,
            
            ProductSeeder::class,
            ]);
    }
}
