<!-- Leaflet & Geocoder CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
@extends('owner.app', ['title' => __('Space')],['activePage' => 'spaces'])
@section('content')
   @include('owner.layouts.headers.header',
      array(
          'class'=>'info',
          'title'=>"SpaceZone",'description'=>'',
          'icon'=>'fas fa-home',
          'breadcrumb'=>array([
            'text'=>'Space'
],['text'=>'Edit Detail'])))
  

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-12 text-right">
                                <a href="{{ route('spaces.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('spaces.update', $parkingSpace) }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id" value="{{$parkingSpace->id}}">

                            <div class="pl-lg-4">                               
                                <div class="form-group{{ $errors->has('spacename') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __(' SpaceName') }}</label>
                                    <input type="text" name="spacename" id="input-name" class="form-control form-control-alternative{{ $errors->has('spacename') ? ' is-invalid' : '' }}" placeholder="{{ __('Space Name') }}" value="{{ old('spacename',$parkingSpace->title) }}"  autofocus required>

                                    @if ($errors->has('spacename'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('spacename') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Description') }}</label>
                                    <input type="text" name="description" id="input-name" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description',$parkingSpace->description) }}"  autofocus required>

                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone" id="input-name" class="form-control form-control-alternative{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __('Phone Number') }}" value="{{ old('phone',$parkingSpace->phone_number) }}"  autofocus required>

                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Price (Per Hour)') }}</label>
                                    <input type="text" name="price" id="input-name" class="form-control form-control-alternative{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price Per Hour') }}" value="{{ old('price',$parkingSpace->price_par_hour) }}"  autofocus required>

                                    @if ($errors->has('price'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>

                              
                                <div class="form-group">
                                    <label class="form-control-label" for="input-status">{{ __('Facilities') }}</label>
                                    <select multiple class="form-control form-control-alternative" id="exampleFormControlSelect2" name="facilites[]">
                                        @foreach ( $facilites  as $facility)
                                            @if(is_null($parkingSpace->facilities))
                                                <option value="{{$facility->id}}" >{{$facility->title}}</option>  
                                            @else
                                                <option value="{{$facility->id}}"  {{in_array($facility->id,$parkingSpace->facilities) ? 'selected' : '' }} >{{$facility->title}}</option>  
                                            @endif
                                        @endforeach
                                    </select>
                                  </div>

                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Address') }}</label>
                                    <input type="text" name="address" id="address" class="form-control form-control-alternative{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address',$parkingSpace->address) }}"  readonly autofocus required>

                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                    <div id="dvMap" style="height:350px; width: 100%;"  class="mt-2"></div>
                                </div>
                               
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('Lat') }}</label>
                                        <input type="text" class="form-control form-control-alternative" name="lat" id="lat" value="{{ old('lat',$parkingSpace->lat) }}">
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('Long') }}</label>
                                        <input type="text" class="form-control form-control-alternative" name="lng" id="lng" value="{{ old('lng',$parkingSpace->lng) }}">
                                    </div>


                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"  name="available" value = "1" {{$parkingSpace->available_all_day == 1 ? 'checked':'' }} role="switch" id="ss"  />
                                        <label class="form-check-label" for="flexSwitchCheckChecked">{{ __('Available 24 Hour')}}</label>
                                        <div class="d-flex" id="time">
                                            <div  class="col-4">
                                                <div class="form-group">
                                                    <label for="example-time-input" class="form-control-label">{{__('Open Time')}}</label>
                                                    <input class="form-control" type="time"  id="example-time-input" name="open_time" value="{{$parkingSpace->open_time}}" >
                                                </div>
                                                 
                                            </div>
                                            <div></div>
                                            <div  class="col-4">
                                                <div class="form-group">
                                                    <label for="example-time-input" class="form-control-label">{{__('Close Time')}}</label>
                                                    <input class="form-control" type="time"  id="example-time-input" name="close_time"  value="{{$parkingSpace->close_time}}">
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-status">{{ __('Offline Paymemt') }}</label>
                                    <select class="form-control form-control-alternative" name="offlinepayment">
                                        <option  class="form-control form-control-alternative" value="1"{{$parkingSpace->offline_payment==1?'selected':''}} >{{__('Yes')}}</option>
                                        <option  class="form-control form-control-alternative" value="0" {{$parkingSpace->offline_payment==0?'selected':''}}>{{__('No')}}</option>
                                    </select>   
                                </div>   
                                <div class="form-group">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select class="form-control form-control-alternative" name="status">
                                        <option  class="form-control form-control-alternative" value="1" {{$parkingSpace->status==1?'selected':''}}>{{ __('Enable')}}</option>
                                        <option  class="form-control form-control-alternative" value="0" {{$parkingSpace->status==0?'selected':''}}>{{ __('Disable')}}</option>
                                    </select>   
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4 rtl_btn">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var markers = [
        {
            "title": '{{$parkingSpace->title}}',
            "lat": '{{$parkingSpace->lat}}',
            "lng": '{{$parkingSpace->lng}}',
            "description": '{{$parkingSpace->description}}'
        }
    ];
    initLeafletMap();
   function initLeafletMap() {
        const map = L.map('dvMap').setView([markers[0].lat, markers[0].lng], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
        }).addTo(map);

        const bounds = [];

        markers.forEach(function (data) {
            const marker = L.marker([data.lat, data.lng], {
                draggable: true,
                title: data.title
            }).addTo(map);

            //marker.bindPopup();

            marker.on('dragend', function () {
                const latlng = marker.getLatLng();
                const lat = latlng.lat.toFixed(7);
                const lng = latlng.lng.toFixed(7);
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            });

            bounds.push([data.lat, data.lng]);
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds);
        } else {
            map.setView(bounds[0], 13); // Zoom closer if only one marker
        }
    }
    </script>  
@endsection
<script language="JavaScript"  src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>    
<script type="text/javascript">
    $(document).ready(function(){
            $(document).on('change','#ss',function(){
                if($(this).is(':checked')) 
                {     
                    $('#time').addClass('d-none');
                    $('#time').removeClass('d-flex');    
                }    
                else
                {
                    $('#time').removeClass('d-none');
                    $('#time').addClass('d-flex'); 
                }  
            });
    });
</script>  