<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Shop;
use App\Models\Product;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;

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
        // shops処理

        // whereで条件指定　→　"owner_id",Auth::id()オーナーでログインしたID

        $shops = Shop::where("owner_id", Auth::id())

            ->select("id", "name")//idとnameを表示

            ->get();//id,nameを取得

        // images処理

        $images = Image::where("owner_id", Auth::id())

            ->select("id", "title", "filename")

            ->orderBy("updated_at", "desc")//新しい順番

            ->get();

        // category処理

        // リレーションで取得する際n+1問題が起こるのでwithで！

        $categories = PrimaryCategory::with("secondary")

            ->get();

        return view(
            "owner.products.create",

            compact("shops", "images", "categories")
        );
    }


    public function store(ProductRequest $request)
    
    {
        
        $request->validate([

            'name' => ['required', 'string', 'max:50'],

            'information' => ['required', 'string', 'max:1000'],//max1000文字

            'price' => ['required', 'integer'],//必須にしてintegerで数字

            'sort_order' => ['nullable', 'integer'],//nullableでnullでもOK!

            'quantity' => ['required', 'integer'],

            'shop_id' => ['required', 'exists:shops,id'],//存在しているかどうかを確認するためにexists:shops,idと書く

            'category' => ['required', 'exists:secondary_categories,id'],//exists:secondary_categories,idでsecondary_categoriesテーブルの外部キーidと結ぶ

            'image1' => ['nullable', 'exists:images,id'],//画像1~4まであり,空でもOK!

            'image2' => ['nullable', 'exists:images,id'],

            'image3' => ['nullable', 'exists:images,id'],

            'image4' => ['nullable', 'exists:images,id'],

            "is_selling" => "required"

        ]);

        //これらを保存するための処理をしたできてるがProductとstockを同時に保存できるようにトランザクション処理を行います！

        try {

            DB::transaction(function () use ($request) {

                $product = Product::create([

                    'name' => $request->name,

                    'information' => $request->information,

                    'price' => $request->price,

                    'sort_order' => $request->sort_order,

                    'shop_id' => $request->shop_id,

                    'secondary_category_id' => $request->category,

                    'image1' => $request->image1,

                    'image2' => $request->image2,

                    'image3' => $request->image3,

                    'image4' => $request->image4,

                    'is_selling' => $request->is_selling

                ]);

                // $productと上で変数名で書いていたのでStockテーブルでも使っていく。

                Stock::create([

                    "product_id" => $product->id,

                    "type" => 1,

                    "quantity" => $request->quantity,
                ]);

            }, 2);

        } catch (Throwable $e) {

            Log::error($e);

            throw $e;

        }

        // リダイレクション処理

        return redirect()

            ->route('owner.products.index')

            ->with([

                "message" => "商品登録をしました。",

                "status" => "info"

            ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);//パラメーターでidが入ってくるのでfindOrFail($id)で指定すれば一つの商品を選ぶことができます

        $quantity = Stock::where("product_id", $product->id)

            ->sum("quantity");//一つの商品の在庫一つの商品の在庫情報を取るために

        // whereで条件指定　→　"owner_id",Auth::id()オーナーでログインしたID

        $shops = Shop::where("owner_id", Auth::id())

            ->select("id", "name")

            ->get();

        $images = Image::where("owner_id", Auth::id())

            ->select("id", "title", "filename")

            ->orderBy("updated_at", "desc")

            ->get();

        // リレーションで取得する際n+1問題が起こるのでwithで！

        $categories = PrimaryCategory::with("secondary")

            ->get();

        return view("owner.products.edit", compact("product", "quantity", "shops", "images", "categories"));
    }
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);//1つのプロダクトを指定できます
        $quantity = Stock::where("product_id", $product->id)

            ->sum("quantity");//一つの商品の在庫一つの商品の在庫情報を取るために
//$request->current_quantityでedit画面で表示してる数量と$quantityでアップデートで読み込んだ数量が同じでなかったら

        if ($request->current_quantity !== $quantity) {

            $id = $request->route()->parameter("product"); //productのidを取得

            return redirect()->route("owner.products.edit", ['product' => $id])//['product'=> $id]にすることでルートパラメーターを持ったままeditに渡せる

                ->with(["message" => "在庫数が変更されました！！再度変更お願いします", "status" => "alert"]);
        } else {
            try {

                // $product = Product::findOrFail($id)となり今回は新規ではなく元々あるものを更新するので$productという変数を入れてあげる

                DB::transaction(function () use ($request, $product) {

                    $product->name = $request->name;

                    $product->information = $request->information;

                    $product->price = $request->price;

                    $product->sort_order = $request->sort_order;

                    $product->shop_id = $request->shop_id;

                    $product->secondary_category_id = $request->category;

                    $product->image1 = $request->image1;

                    $product->image2 = $request->image2;

                    $product->image3 = $request->image3;

                    $product->image4 = $request->image4;

                    $product->is_selling = $request->is_selling;

                    $product->save();

                    //追加の時

                    if ($request->type === '1') {

                        $newQuantity = $request->quantity;

                    }

                    //削減の時* -1を入れることでマイナスの値を入れることが可能

                    if ($request->type === '2') {

                        $newQuantity = $request->quantity * -1;

                    }Stock::create([

                        "product_id" => $product->id,

                        "type" => $request->type,

                        "quantity" => $newQuantity

                    ]);

                }, 2);

            } catch (Throwable $e) {
                Log::error($e);

                throw $e;

            }

            // リダイレクション処理

            return redirect()

                ->route('owner.products.index')

                ->with([

                    "message" => "商品情報を更新しました。",

                    "status" => "info"

                ]);
        }

    }


    public function destroy($id)
    {
        //
    }
}
