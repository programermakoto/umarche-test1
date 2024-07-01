<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth; //ユーザーidを取得するために

class CartController extends Controller
{
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
        dd("テスト");

    }
}
