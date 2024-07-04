<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth; //ユーザーidを取得するために
use App\Models\User;
use App\Constants\Common;
use App\Services\CartService;
use App\Jobs\SendThanksMail;
use App\Jobs\SendOrderedMail;

class CartController extends Controller
{
    public function index()
    {

        $user = User::findOrFail(Auth::id());//ログインしてるユーザー情報を取得

        $products = $user->products;

        $totalPrice = 0;

        foreach ($products as $product) {

            $totalPrice += $product->price * $product->pivot->quantity;//金額と数量を掛けたものをtotalPriceに

        }

        // dd($products,$totalPrice);

        return view("user.cart", compact("products", "totalPrice"));

    }
    public function add(Request $request)
    {

        // dd($request);
        $itemInCart = Cart::where('product_id', $request->product_id)//渡ってくるproductを取得

            ->where('user_id', Auth::id())->first();//違うユーザーかもしれないのでuserでログインしてるユーザーとする

        if ($itemInCart) {

            $itemInCart->quantity += $request->quantity; //カートに1入ってるとして更に追加されると合計される

            $itemInCart->save();//保存

        } else {

            Cart::create([

                "user_id" => Auth::id(),

                "product_id" => $request->product_id,

                "quantity" => $request->quantity

            ]);
        }
        return redirect()->route("user.cart.index");

    }
    public function delete($id)
    {

        Cart::where("product_id", $id)->where("user_id", Auth::id())->delete();//ログインしているユーザーidを削除

        return redirect()->route("user.cart.index");

    }
    public function checkout()
    {



        $user = User::findOrFail(Auth::id()); //1ログインしているユーザー情報を取る

        $products = $user->products; //2ユーザーから商品を取得する

        $lineItems = []; //3stripeではカートに入っている情報をlineItemsと呼び配列を作り中に入れていく

        foreach ($products as $product) {
            //4foreachで全てのカートに入っている情報をとる

            // $quantity = '';//在庫情報の処理****

            // $quantity = Stock::where('product_id', $product->id)->sum('quantity'); //商品の現在の在庫数を調べる****

            // if ($product->pivot->quantity > $quantity) {

            //カート内の数量より現在の在庫数が多かったら買えない処理に

            // return redirect()->route('user.cart.index'); //user.cart.indexに戻す

            // } else {

            // 買える時の処理****

            $price_data = ([

                'unit_amount' => $product->price, //商品価格

                'currency' => 'jpy', //通貨

                'product_data' => $product_data = ([

                    'name' => $product->name, //商品名

                    'description' => $product->information, //商品情報

                ]),

            ]);

            $lineItem = [

                'price_data' => $price_data, //$price_dataの事

                'quantity' => $product->pivot->quantity, //在庫情報

            ];

            array_push($lineItems, $lineItem);

            // }

        } //$lineItemsの中に商品名と商品情報と商品価格と通貨と在庫情報を入れていく

        // dd($lineItems);
        // もし買える状態でstripeに渡す前に在庫情報を減らすので

        foreach ($products as $product) {

            //         Stock::create([

            //             'product_id' => $product->id, //その商品に対して選択

            //             'type' => \Constant::PRODUCT_LIST['reduce'],

            //             //商品を減らす以前使った定数(app/Http/Controller/Owner/ProductController)

            //             'quantity' => $product->pivot->quantity * -1 //カートの在庫数を減らす

            //         ]);

            //     }

            // dd("test");

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); //シークレットキー(envファイルに書いていたからenv('STRIPE_SECRET_KEY')このようになります)

            $checkout_session = \Stripe\Checkout\Session::create([

                'payment_method_types' => ['card'],

                'line_items' => [$lineItems], //22行目の配列が入ってくる

                'mode' => 'payment', //一回払い(モード)

                'success_url' => route('user.cart.success'), //支払い成功したらuser.items.indexに戻す

                'cancel_url' => route('user.cart.cancel'), //支払い失敗したらuser.cart.indexに戻す

            ]);

            $publicKey = env('STRIPE_PUBLIC_KEY'); //公開キー

            return view(
                'user.checkout',

                compact('checkout_session', 'publicKey')
            ); //checkout_sessionに情報が全て入って、publicKeyと渡す！

        }

    }
    public function success()
    {
        $items = Cart::where('user_id', Auth::id())->get(); //カートの中でログインしているユーザーの商品情報が設定されている

        $products = CartService::getItemsInCart($items);

        $user = User::findOrFail(Auth::id());

        //複数メールを送るのでそれぞれの商品とユーザーを処理する

        foreach ($products as $product) {

            SendOrderedMail::dispatch($product, $user);

        }

        SendthanksMail::dispatch($products, $user);

        // dd("メール送信test");



        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.items.index');
    }
    public function cancel()
    {

        $user = User::findOrFail(Auth::id());

        foreach ($user->products as $product) {

            Stock::create([

                'product_id' => $product->id,//その商品に対して選択

                'type' => \Constant::PRODUCT_LIST['add'],//商品を増やす。以前使った定数(app/Http/Controller/Owner/ProductController)

                'quantity' => $product->pivot->quantity //カートの在庫数を減らす

            ]);
        }

        return redirect()->route('user.cart.index');

    }
}
