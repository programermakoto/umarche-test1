<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

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
