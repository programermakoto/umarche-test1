@php

if($name==="image1"){//$name==="image1"でnameがimage1の時

$modal="modal-1";//modal-1とはidのことです

}

if($name==="image2"){//$name==="image1"でnameがimage2の時

$modal="modal-2";//modal-2とはidのことです

}

if($name==="image3"){//$name==="image1"でnameがimage3の時

$modal="modal-3";//modal-3とはidのことです

}

if($name==="image4"){//$name==="image1"でnameがimage4の時

$modal="modal-4";//modal-4とはidのことです

}

if($name==="image5"){//$name==="image1"でnameがimage4の時

$modal="modal-5";//modal-5とはidのことです

}

//id="modal-1"をid="{{$modal}}"にすればifで作った変数名で置き換えれる

@endphp


<div class="modal micromodal-slide" id="{{$modal}}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{$modal}}-title">
        <header class="modal__header">
          <h2 class="modal__title" id="{{$modal}}-title">
           ファイル選択してください
          </h2>
          <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="{{$modal}}-content">
        <div class="flex flex-wrap">

@foreach ($images as $image)

<div class="w-1/4 p-2 md:p-2">

<div class="border rounded-md p-2 md:p-4">

<img class="image" data-id="{{ $name }}_{{ $image->id }}"

data-file="{{$image->filename}}"

data-path="{{asset("storage/products/")}}"

data-modal="{{$modal}}"

src="{{ asset("storage/products/" . $image->filename)}}">

{{-- <x-thumbnail :filename="$image->filename" type="products" /> --}}

<div class="text-gray-700"> {{ $image->title }} </div>

</div>

</div>

@endforeach

</div>
        </main>
        <footer class="modal__footer">
          <button type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>

        </footer>
      </div>
    </div>
  </div>

  <div class="flex justify-around items-center mb-4">{{-- ボタンと画像を中央揃え --}}

  <a data-micromodal-trigger="{{$modal}}" href='javascript:;'>ファイルを選択</a>

<div class="w-1/4">

<img id="{{ $name }}_thumbnail" src="">{{--id="{{ $name }}がimage1や2が入ってくる場所です--}}

</div>

</div>

<input id="{{ $name }}_hidden" type="hidden" name="{{ $name }}" value="">

{{--これをJSで！--}}