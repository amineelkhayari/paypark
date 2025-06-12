@extends('owner.app',['activePage' => 'dashboard'])

@section('content')
<style>
    .active {
        color: gold;
    }
</style>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row pb-5">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{__('USERS')}} </h5>
                                    <span class="h2 font-weight-bold mb-0">{{$Tuser }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="far fa-user"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{__('BOOKINGS')}}
                                    </h5>
                                    <span class="h2 font-weight-bold mb-0">{{$Tbooking}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                        <i class="fas fa-cart-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{__('GUARDS')}}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$Tguard}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"> {{ __('PLAN PURCHASED')}}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$Tsubscription}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{__('PARKING SPACES')}}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$Tspace}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class=" col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">
                                        {{__('VERIFIED SPACES')}}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$Tverify}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-pink text-white rounded-circle shadow">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{__('THIS MONTH BOOKINGS')}}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$totalmonthbooking}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--7" style="padding-bottom: 25px">

    <div class="row mt-5">
        <div class="col-xl-8 mb-5 mb-xl-0" >
            <div class="card shadow" style="height: 100%">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Verified Space')}}</h3>
                        </div>
                        {{-- <div class="col text-right">
                            <a href="" class="btn btn-sm btn-primary">See all</a>
                        </div> --}}
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Total Booking')}}</th>
                                <th scope="col">{{__('Total Earning')}}</th>
                                <th scope="col">{{__('Rating')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($space_data as $item)
                        <tr>
                            <th scope="row">
                                {{$item['title']}}
                            </th>
                            <td>
                                {{$item['total_booking']}}
                            </td>
                            <td>
                                ${{$item['total_earning']}}
                            </td>
                            <td>

                                <div class="product-rating mb-1">
                                    @for ($i = 1; $i <= 5; $i++) <i
                                        class="fas fa-star {{$i<=$item['avg_rating'] ? 'active' : ''}}"></i>
                                    @endfor
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('User`s')}}</h3>
                        </div>
                        <div class="col text-right">
                            {{-- <a href="{{ url('parkingUser') }}" class="btn btn-sm btn-primary">See all</a> --}}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('User')}}</th>
                                <th scope="col">{{__('Booking')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Udata as $item)

                            <tr>
                                <th scope="row">{{$item['name']}}</th>
                                <td>{{$item['total_booking']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
        <div class="col-xl-12 mt-5">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{__('Parking Facilities')}}</h3>
                        </div>
                        <div class="col text-right">
                            {{-- <a href="{{ url('facilities') }}" class="btn btn-sm btn-primary">See all</a> --}}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Image')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facilities_data as $item)

                            <tr>
                                <th scope="row">
                                    {{$item['title']}}
                                </th>
                                <td>
                                    <img src="{{ asset('upload')}}/{{$item->image }}" class="rounded-circle" height="50"  width="50">

                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
</div>
@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush