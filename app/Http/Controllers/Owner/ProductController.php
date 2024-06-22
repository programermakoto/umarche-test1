<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Product;
use App\Models\SecondaryCategory;

use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{


    public function __construct()
    {

        //オーナーかどうかの確認

        $this->middleware("auth:owners");

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter("product"); //productのidを取得

            if (!is_null($id)) { //productのidが存在するなら↓

                $productOwnerId = Product::findOrFail($id)->shop->owner->id;//Productの中にownerがないのでshop->owner->idにする。

                $productId = (int) $productOwnerId; //文字列=>数値に

                //認証用のid↓

                if ($productId !== Auth::id()) { //同じではなかったら

                    abort(404); //404の画面表示

                }
            }return $next($request);
        });
    }

    public function index()
    {
        //Owner::findOrFail(Auth::$id())でログインしているオーナーの情報を取得している。

        //->shop->product更にこのコードで取得した情報からリレーションで繋がっているshopからproductを取得して変数で置き換える。

        // $products = Owner::findOrFail(Auth::id())->shop->product;
        $ownerInfo = Owner::with("shop.product.imageFirst")->where("id", Auth::id())->get();

        // dd($ownerInfo);

        //return view("owner.products.index"に$products（変数)に置き換えたものを使いたいのでcompact("products")で使用可能に！

        return view("owner.products.index", compact("ownerInfo"));

        //このview("owner.products.index"を制作する


    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
