<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class ImageController extends Controller
{
    public function __construct()
    {

        $this->middleware("auth:owners");

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter("image"); //Imageのidを取得

            if (!is_null($id)) { //imageのidが存在するなら↓

                $imagesOwnerId = Image::findOrFail($id)->owner->id;

                $imageId = (int) $imagesOwnerId; //文字列=>数値に

                //認証用のid↓

                if ($imageId !== Auth::id()) { //同じではなかったら

                    abort(404); //404の画面表示

                }

            }

            return $next($request);

        });

    }

    public function index()
    {
        $images = Image::where("owner_id", Auth::id())->orderBy("updated_at", "desc")->paginate(20);

        return view("owner.images.index", compact("images"));
    }

    public function create()
    {
        return view("owner.images.create");
    }


    public function store(UploadImageRequest $request)
    {
        // dd($request);
        $imageFiles = $request->file("files"); //filesとすることで複数画像を取得できる

        //一応IF分で書いておきます！

        if (!is_null($imageFiles)) {

            foreach ($imageFiles as $imageFile) {

                $fileNameToStore = ImageService::upload($imageFile, "products"); //第二引数はフォルダー名

                Image::create([

                    "owner_id" => Auth::id(),

                    "filename" => $fileNameToStore

                ]); //ファイルが帰ってきたら保存処理をする。

            }

        }

        //レダイレクションでindex画面に戻しフラッシュメッセージを表示させる。

        return redirect()

            ->route("owner.images.index")

            ->with([

                "message" => "画像登録を実施しました",

                "status" => "info"

            ]);
    }
    public function edit($id)
    {
        $image = Image::findOrFail($id);

        return view("owner.images.edit", compact("image"));
    }
    public function update(Request $request, $id)
    {
        //
        $request->validate([

            'title' => ['string', 'max:50'],

        ]);

        $image = Image::findOrFail($id);

        $image->title = $request->title;

        $image->save();

        return redirect()

            ->route("owner.images.index")

            ->with([

                "message" => "画像情報を更新しました",

                "status" => "info"

            ]);
    }

    public function destroy($id)
    {
        // storageフォルダーの中の選択されたIDの画像を消さないといけないので

        $image = Image::findOrFail($id);
        $imageInProducts = Product::where("image1", $image->id)//image1で$image->idを使っているか

            ->orWhere("image2", $image->id)//orWhereでそのまま複数取得できる

            ->orWhere("image3", $image->id)

            ->orWhere("image4", $image->id)

            ->get();//最後はgetで取得する

        // $imageInProductsの変数に値が入っていればの処理↓

        if ($imageInProducts) {

            $imageInProducts->each(function ($product) use ($image) {

                if ($product->image1 === $image->id) {//一致していれば

                    $product->image1 = null;//image1をnullにする

                    $product->save();//保存

                }if ($product->image2 === $image->id) {

                    $product->image2 = null;

                    $product->save();

                }

                if ($product->image3 === $image->id) {//一致していれば

                    $product->image3 = null;//image1をnullにする

                    $product->save();//保存

                }

                if ($product->image4 === $image->id) {//一致していれば

                    $product->image4 = null;//image1をnullにする

                    $product->save();//保存

                }

            });

        }

        // eachと書くことでコレクションの中身まで指定できる

        // use($image)書くことで使うことができる

        //ストレージフォルダのありかを示さないといけない

        $filePath = "public/prodcuts/" . $image->filename;

        if (Storage::exists($filePath)) {

            storage::delete($filePath);
        }

        Image::findOrFail($id)->delete();

        return redirect()

            ->route("owner.images.index")

            ->with([

                "message" => "画像を削除しました",

                "status" => "alert"

            ]);
    }
}
