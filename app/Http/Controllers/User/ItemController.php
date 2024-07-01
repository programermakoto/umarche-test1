<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()

    {//ユーザーかどうかの確認

    $this->middleware("auth:users");}

    public function index()
    {

        //table('t_stocks')でテーブル名を取得する

        // DB::rawでSQLをそのままファイルに記述できる. (クエリービルダー）

        // ->groupBy('product_id')->having('quantity','>',1);でproduct_id毎の合計在庫が１個以上あるかを指定

        $stocks = DB::table('t_stocks')

            ->select(
                'product_id',

                DB::raw('sum(quantity)as quantity')
            )

            ->groupBy('product_id')

            ->having('quantity', '>', 1);
        //->joinSub($stocks,'stock', function ($join) {で　$stocksをstockに置き換えて、置き換えたproduct_idとproductsテーブルのproducts.idを合体！これでproductsのテーブルとstockのテーブルが紐付いた。

        //さらに->join ('shops', 'products.shop_id', '=', 'shops.id')で　productsのテーブルとshopsのテーブルを紐付かせる

        // ->where('shops.is_selling', true)->where('products.is_selling', true)で　両方販売中の物を取得

        $products = DB::table('products')

            ->joinSub($stocks, 'stock', function ($join) {

                $join->on('products.id', '=', 'stock.product_id');

            })

            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')

            ->join('images as image1', 'products.image1', '=', 'image1.id')

            ->join('images as image2', 'products.image2', '=', 'image2.id')

            ->join('images as image3', 'products.image3', '=', 'image3.id')

            ->join('images as image4', 'products.image4', '=', 'image4.id')
            ->where('shops.is_selling', true)

            ->where('products.is_selling', true)
            ->select('products.id as id', 'products.name as name', 'products.price', 'products.sort_order as sort_order', 'products.information', 'secondary_categories.name as category', 'image1.filename as filename')
            ->get();
        return view('user.index', compact('products'));

    }
    public function show($id)
    {

        $product = Product::findOrFail($id);

        return view('user.show', compact('product'));
    }


}
