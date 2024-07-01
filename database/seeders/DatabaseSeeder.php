<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Stock;
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

            UserSeeder::class,

            // ProductSeeder::class,

            // StockSeeder::class,
        ]);
        Product::factory(100)->create();

        Stock::factory(100)->create();
    }
}
