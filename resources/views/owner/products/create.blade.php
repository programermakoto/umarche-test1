<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 bg-white border-b border-gray-200">

                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="post" action="{{ route('owner.products.store') }}">

                        @csrf

                        <div class="-m-2">

                            <div class="p-2 w-1/2 mx-auto">

                                <div class="relative">

                                    <select name="category">

                                        @foreach ($categories as $category)

                                            {{-- 下のコード、この段階ではプライマリーカテゴリーのnameを表示 --}}

                                            <optgroup label="{{ $category->name }}">

                                                {{-- プライマリーカテゴリーからセカンダリーカテゴリーにすることで表示させれる --}}

                                                @foreach ($category->secondary as $secondary)

                                                    <option value="{{ $secondary->id }}">{{ $secondary->name}}</option>

                                                @endforeach

                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <x-select-image :images="$images" name="image1" />
                        <x-select-image :images="$images" name="image2" />
                        <x-select-image :images="$images" name="image3" />
                        <x-select-image :images="$images" name="image4" />
                        <x-select-image :images="$images" name="image5" />
                        <div class="p-2 w-full flex justify-around mt-4">

                            <button type="button" onclick="location.href='{{ route('owner.products.index') }}'"
                                class="mx-auto text-black bg-gray-500 border-1 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">戻る</button>

                            <button type="submit"
                                class=" mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>

                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script>

        "use strict"

        const images = document.querySelectorAll(".image") //全てのimageタグを取得

        images.forEach(image => { // それぞれのimageタグに対して

            image.addEventListener('click', function (e) { // クリックしたら

                const imageName = e.target.dataset.id.substr(0, 6) //data-idの6文字

                const imageId = e.target.dataset.id.replace(imageName + '_') // 6文字カット

                const imageFile = e.target.dataset.file

                const imagePath = e.target.dataset.path

                const modal = e.target.dataset.modal

                // サムネイルとinput type=hiddenのvalueを設定

                document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile

                document.getElementById(imageName + '_hidden').value = imageId

                MicroModal.close(modal);//モーダルを閉じる

            },)

        })

    </script>
</x-app-layout>