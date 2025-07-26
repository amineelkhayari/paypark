@extends('website.layout.app', ['activePage' => 'map-list'])
@section('content')
<div class="mt-10 mb-10 xxxxl:pl-[300px] xxxxl:pr-[300px] s:pl-[10px] s:pr-[10px] xxl:pl-[100px] xxl:pr-[100px] xxxl:pr-[150px] xxxl:pl-[150px]">
    <div class="flex lg:flex-row s:flex-col gap-5">
        <div class="map-responsive">

            <div id="mymap" style="width: 600px; height: 600px;"></div>
        </div>
        @if(count($parkingspace) == 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded-md shadow mb-6" role="alert">
    <div class="flex items-center gap-2">
        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
        </svg>
        <p class="text-lg font-medium">
            No parking spaces available at the moment. Please check back later.
        " <span class="font-bold text-black">{{ request('search') }}</span> "
        </p>
    </div>
</div>

        @endif
        <div>
            @foreach($parkingspace as $key => $item)
            <div class="bg-white xxxxl:w-[630px] rounded-[8px] shadow p-4 xl:w-[580px] xxl:w-[620px] mb-5">
                <div class="flex justify-between xxxxl:flex-row s:flex-col xl:flex-row">
                    <div class="xxxxl:w-[350px] xxxxl:mb-0 s:mb-10 xl:w-[340px] xxl:w-[340px]">
                        <h5 class="font-poppins font-medium text-[#404F65] text-xl mb-4">{{$item->title}}</h5>
                        @if(isset($item->rating))
                        <h6 class="flex gap-2 font-poppins font-normal text-[#8896AB] text-base mb-4"><img src="{{asset('website/icon/star.svg')}}" alt="">{{$item->rating}}</h6>
                        @else
                        <h6 class="flex gap-2 font-poppins font-normal text-[#8896AB] text-base mb-4">{{__('No Reviews')}}</h6>
                        @endif

                        <div class="flex gap-2">
                            <img src="{{asset('website/icon/location.svg')}}" alt="" class="w-5 h-5 mt-1">
                            <p class="font-poppins font-normal text-gray text-lg">{{$item->address}}</p>
                        </div>
                    </div>
                    <div class="xl:self-end">
                        <h4 class="font-poppins font-medium text-gray text-2xl mb-4">{{$adminsetting->currency_symbol}} {{$item->price_par_hour}} <span class="font-poppins font-normal text-[#8896AB] text-base">{{__('/hr')}}</span></h4>
                        @if(Auth::guard('appuser')->check())
                        <a href="{{url('/parking_space/'.$item->id)}}"><button class="border border-primary w-36 h-12 font-poppins font-medium text-base text-primary rounded-[6px]">{{__('Reserve')}}</button></a>
                        @else
                        <button class="border border-primary w-36 h-12 font-poppins font-medium text-base text-primary rounded-[6px]" type="button" data-modal-target="SignIn" data-modal-toggle="SignIn">{{__('Reserve')}}</button>
                        @endif
                    </div>
                </div>
            </div>
            
            @endforeach
        </div>
        
         
    </div>
</div>
@if(count($parkingspace) > 0)
<script>
    var markers = [];

    @foreach($parkingspace as $item)
    var markerData = {
        "title": '{{ $item->title }}',
        "lat": '{{ $item->lat }}',
        "lng": '{{ $item->lng }}',
        "description": '{{ $item->description }}',
        "img":'{{asset("upload/".$item->images)}}',
        "id": '{{ $item->id }}',

    };
    markers.push(markerData);
    @endforeach
    window.onload = function () {
    var map = L.map('mymap').setView([markers[0].lat, markers[0].lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(map);

    markers.forEach(function (data) {
        L.marker([data.lat, data.lng])
            .addTo(map)
            .bindPopup(`
            <div style="max-width:200px;">
                <h4>${data.title}</h4>
                <h5 class="font-poppins font-medium text-[#404F65] text-xl mb-4">{{$item->title}}</h5>

                <img src="${data.img}" alt="image" style="width:100%; height:auto; border-radius:8px; margin-top:5px;" />
                <p style="margin-top:5px;">${data.description}</p>
                <a href="{{url('/parking_space/${data.id}')}}"><button class="border border-primary w-36 h-12 font-poppins font-medium text-base text-primary rounded-[6px]">{{__('Reserve')}}</button></a>

            </div>
        `);
    });
};
</script>
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@endsection