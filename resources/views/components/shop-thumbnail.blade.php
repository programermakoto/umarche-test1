<div>

@if (empty($filename))

    <img src="{{asset("images/noimage.png")}}" class="w-12">

@else

    <img src="{{asset("storage/shops/" . $filename)}}">

@endif

</div>