<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{

    public static function upload($imageFile, $folderName)
    {
        //ここで配列の時の処理塗装でない時の処理を書く

        if (is_array($imageFile)) {

            $file = $imageFile["image"];

        } else {

            $file = $imageFile;

        }
        $fileName = uniqid(rand() . "_"); //ランダムなファイルを作成

        $extension = $file->extension(); //extensionで受け取った画像の拡張子をつけて代入

        $fileNameToStore = $fileName . "." . $extension;

        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode(); //resizeでサイズ設定

        Storage::put("public/" . $folderName . "/" . $fileNameToStore, $resizedImage);//publicの中に入れる

        return $fileNameToStore;

    }


}