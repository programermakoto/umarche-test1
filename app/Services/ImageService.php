<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{

    public static function upload($imageFile,$folderName)
    {

        $fileName = uniqid(rand() . "_"); //ランダムなファイルを作成

        $extension = $imageFile->extension(); //extensionで受け取った画像の拡張子をつけて代入

        $fileNameToStore = $fileName . "." . $extension;

        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode(); //resizeでサイズ設定

        Storage::put("public/" . $folderName . "/" . $fileNameToStore,$resizedImage);//publicの中に入れる

        return $fileNameToStore;

    }

}