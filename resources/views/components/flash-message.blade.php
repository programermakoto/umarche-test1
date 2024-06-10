@props(['status' => "info"])

@php
    if (session('status') === "info") {
        $bgColor = "bg-indigo-500";
    }
    if (session('status') === "alert") {
        $bgColor = "bg-red-500";
    }
@endphp

@if(session("message"))
    <div class="{{$bgColor}} w-1/2 mx-auto p-2 text-white">
        {{session("message")}}
    </div>
@endif