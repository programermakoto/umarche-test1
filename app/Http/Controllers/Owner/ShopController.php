<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:owners");
        $this->middleware(function ($request, $next) {

            // dd($request->route()->parameter("shop"));

            // dd(Auth::id());

            $id = $request->route()->parameter("shop");//shopのidを取得

            if (!is_null($id)) {//shopのidが存在するなら↓

                $shopsOwnerId = shop::findOrFail($id)->owner->id;

                $shopId = (int) $shopsOwnerId;//文字列=>数値に

                $ownerId = Auth::id();//認証用のid

                if ($shopId !== $ownerId) {//同じではなかったら

                    abort(404);//404の画面表示

                }

            }

            return $next($request);

        });
    }
    public function index()
    {
        // http://127.0.0.1:8000/owner/shops/index
        $ownerId = Auth::id();
        $shops = shop::where("owner_id", $ownerId)->get();
        return view("owner.shops.index", compact("shops"));
    }
    public function edit($id)
    {
        // dd(shop::findOrFail($id));
        $shop = shop::findOrFail($id);
        //http://127.0.0.1:8000/owner/shops/edit/1
        return view("owner.shops.edit", compact("shop"));
    }
    public function update(UploadImageRequest $request,$id )
    {
        $request->validate([

            'name' => ['required', 'string', 'max:50'],

            'information' => ['required', 'string', 'max:1000'],

            'is_selling' => ['required'],

        ]);
        $imageFile = $request->image;//imgを受け取り変数へ

        if (!is_null($imageFile) && $imageFile->isValid()) {//nullではないかアップロードできてるか確認

            $fileNameToStore = ImageService::upload($imageFile, "shops");
            // Storage::putFile("public/shops",$imageFile);//保存先と保存したいファイル

            // $fileName = uniqid(rand() . "_");//ランダムなファイルを作成

            // $extension = $imageFile->extension();//extensionで受け取った画像の拡張子をつけて代入

            // $fileNameToStore = $fileName . "." . $extension;

            // $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();//resizeでサイズ設定,encodeで画像として扱える

            // Storage::put("public/shops/" . $fileNameToStore, $resizedImage);//ファイルからのファイル名,リサイズした画像

        }
        $shop = Shop::findOrFail($id);

        $shop->name = $request->name;

        $shop->information = $request->information;

        $shop->is_selling = $request->is_selling;
        if (!is_null($imageFile) && $imageFile->isValid()) {

            $shop->filename = $fileNameToStore;

        }

        $shop->save();

        return redirect()

            ->route("owner.shops.index")

            ->with([

                "message" => "店舗情報を追加しました",

                "status" => "info"

            ]);
    }
}
