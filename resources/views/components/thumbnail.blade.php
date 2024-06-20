{{--components/shop-thumbnailからthumbnailに変えて<x-thumbnailの要素typeがshopsならstorage
    /shops/にproductsならstorage/products/に、これを変数に置き換えている!--}} 
    @php if($type==="shops" ){ $path="storage/shops/" ; }
    if($type==="products" ){ $path="storage/products/" ; } @endphp
    {{--この下のコードは空ならNO_imgの画像を、ソレ以外ならstorage/productsに入れるとなってますが上に$pathという変数に置き換えているのでassetのstorage/productsを$pathに変えてあげると画像の保存するファイルを変えれる。--}}
    <div>

    @if (empty($filename))

        <img src="{{asset("images/noimage.png")}}" class="w-12">

    @else

        <img src="{{asset("$path" . $filename)}}">

    @endif

    </div>