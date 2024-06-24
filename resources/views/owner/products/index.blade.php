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

                    <x-flash-message status="{{ session('status') }}" />

                    <div class="flex justify-end mb-4">

                        {{-- 変更前　route('owner.images.create') --}}

                        <button onclick="location.href='{{ route('owner.products.create') }}'"
                            class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録</button>

                    </div>

                    <div class="flex flex-wrap">

                        {{-- 変更前　@foreach ($images as $image) --}}


                        @foreach ($ownerInfo as $owner)
                            @foreach ($owner->shop->product as $product)
                                <div class="w-1/4 p-2 md:p-2">

                                    {{--変更前　route('owner.images.edit', ['image' => $image->id]) --}}

                                    <a href="{{ route('owner.products.edit', ['product' => $product->id]) }}">

                                        <div class="border rounded-md p-2 md:p-4">

                                            {{-- 変更前　<x-thumbnail :filename="$image->filename" type="products" /> --}}

                                            <x-thumbnail filename="{{ $product->imageFirst->filename ?? '' }}"
                                                type="products" />
                                            <!-- <x-thumbnail filename="{{ $product->imageFirst ? $product->imageFirst->filename : '' }}" type="products" /> -->

                                            {{-- 変更前　<div class="text-xl"> {{ $image->title }} </div> --}}

                      <div class="text-xl"> {{ $product->name }} </div>

                                        </div>

                                    </a>

                                </div>

                            @endforeach
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

</x-app-layout>