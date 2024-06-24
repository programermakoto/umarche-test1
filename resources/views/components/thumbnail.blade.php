{{-- components/shop-thumbnailからthumbnailに変えて<x-thumbnailの要素typeがshopsならstorage
    /shops/にproductsならstorage/products/に、これを変数に置き換えている!--}} 
@php 
    if ($type === "shops") {
        $path = "storage/shops/";
    } elseif ($type === "products") {
        $path = "storage/products/";
    } else {
        $path = '';
    }
@endphp

{{-- この下のコードは空ならNO_imgの画像を、ソレ以外ならstorage/productsに入れるとなってますが上に$path ?? ''という変数に置き換えているのでassetのstorage/productsを$path ?? ''に変えてあげると画像の保存するファイルを変えれる。--}}

<div>
    @if (empty($filename))
        <img src="{{ asset('images/noimage.png') }}" class="w-12">
    @else
        <img src="{{ asset($path . $filename) }}">
    @endif
</div>