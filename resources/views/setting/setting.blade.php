@extends('layouts.app', ['title' => __('Privacy And Policy')],['activePage' => 'setting'])
@section('content')
@include('layouts.headers.header',
array(
'class'=>'success',
'title'=>"Settings",'description'=>'',
'icon'=>'fas fa-home',
'breadcrumb'=>array([

'text'=>'Setting'
])))

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="mb-0">{{ __('Configuration Detail') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </div>
              
                <div class="p-4">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab"
                                    href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1"
                                    aria-selected="true">
                                    <i class="ni ni-email-83 mr-2"></i>{{__('General Settings')}}
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab"
                                    href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3"
                                    aria-selected="false">
                                    <i class="ni ni-credit-card mr-2"></i>{{__('Payment gateways')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel"
                                    aria-labelledby="tabs-icons-text-1-tab">
                                    <form method="post" action="{{ route('setting.generalsetting') }}"
                                        autocomplete="off" enctype="multipart/form-data" id="myform" class="myform">
                                        @csrf
                                        @method('put')
                                        <div class="pl-lg-4">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="Image" class="col-form-label"> {{__('Logo')}}</label>
                                                    <div class="avatar-upload avatar-box avatar-box-left">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="image" name="logo"
                                                                accept=".png, .jpg, .jpeg" />
                                                            <label for="image"></label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview"
                                                                style="background-image: url({{ url('upload/'.$data->logo) }});">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('logo')
                                                    <div class="custom_error">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="Image" class="col-form-label"> {{__('White
                                                        Logo')}}</label>
                                                    <div class="avatar-upload avatar-box avatar-box-left">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="image1" name="white_logo"
                                                                accept=".png, .jpg, .jpeg" />
                                                            <label for="image1"></label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview1"
                                                                style="background-image: url({{ url('upload/'.$data->white_logo) }});">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('white_logo')
                                                    <div class="custom_error">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="Image" class="col-form-label"> {{__('Favicon')}}</label>
                                                    <div class="avatar-upload avatar-box avatar-box-left">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="image2" name="favicon"
                                                                accept=".png, .jpg, .jpeg" />
                                                            <label for="image2"></label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview2"
                                                                style="background-image: url({{ url('upload/'.$data->favicon) }});">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('favicon')
                                                    <div class="custom_error">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="Image" class="col-form-label"> {{__('Background
                                                        Image')}}</label>
                                                    <div class="avatar-upload avatar-box avatar-box-left">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="image3" name="bg_img"
                                                                accept=".png, .jpg, .jpeg" />
                                                            <label for="image3"></label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview3"
                                                                style="background-image: url({{ url('upload/'.$data->bg_img) }});">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('bg_img')
                                                    <div class="custom_error">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <div
                                                        class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="name">{{ __('Name')
                                                            }}</label>
                                                        <input type="text" name="name" id="input-name"
                                                            class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                            value="{{ old('name',$data['name']) }}" autofocus required>

                                                        @if ($errors->has('name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <div
                                                        class="form-group{{ $errors->has('color') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="color">{{ __('Color')
                                                            }}</label>
                                                        <input type="color" name="color" id="input-name"
                                                            class="form-control form-control-alternative{{ $errors->has('color') ? ' is-invalid' : '' }}"
                                                            value="{{ old('color',$data['color']) }}" autofocus
                                                            required>

                                                        @if ($errors->has('color'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div
                                                        class="form-group{{ $errors->has('currency') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="name">{{ __('Currency')
                                                            }}</label>
                                                        <select name="currency_code"
                                                            class="form-control form-control-alternative select2">
                                                            @foreach ($currencies as $currency)
                                                            <option value="{{$currency->id}}" {{ $currency->id ==
                                                                $data->currency_id ? 'selected' :
                                                                ''}}>{{$currency->country}}&nbsp;&nbsp;({{$currency->currency}})&nbsp;&nbsp;({{$currency->code}})
                                                            </option>
                                                            @endforeach
                                                        </select>

                                                        @if ($errors->has('currency'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('currency') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div
                                                        class="form-group{{ $errors->has('timezone') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="name">{{ __('Timezone')
                                                            }}</label>
                                                        <select name="timezone"
                                                            class="form-control form-control-alternative select2">
                                                            @foreach ($timezones as $timezone)
                                                            <option value="{{$timezone->TimeZone}}" {{ $timezone->
                                                                TimeZone == $data->timezone ? 'selected' :
                                                                ''}}>{{$timezone->TimeZone}}&nbsp;&nbsp;({{$timezone->UTC_offset}})&nbsp;&nbsp;({{$timezone->UTC_DST_offset}})
                                                            </option>
                                                            @endforeach
                                                        </select>

                                                        @if ($errors->has('timezone'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('timezone') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <div
                                                        class="form-group{{ $errors->has('trial_days') ? ' has-danger' : '' }} col-10">
                                                        <label class="form-control-label" for="name">{{ __('Trial Days')
                                                            }}</label>
                                                        <input type="number" name="trial_days" id="input-name"
                                                            class="hide_value form-control form-control-alternative{{ $errors->has('trial_days') ? ' is-invalid' : '' }}"
                                                            value="{{ old('trial_days',$data['trial_days']) }}" autofocus min="0"
                                                            required>
                                                        @if ($errors->has('trial_days'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('trial_days') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary mt-4 rtl_btn">{{ __('Save')
                                                }}</button>
                                        </div>
                                    </form>
                                </div>
                               
                                <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" 
                                    aria-labelledby="tabs-icons-text-2-tab">
                                    <form method="post" action="{{ route('setting.payments') }}" autocomplete="off" id="myform" class="myform"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        </h6>
                                        <div class="pl-lg-4">
                                            <div class="row">
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="hide_value custom-control-input" value="1"
                                                                name="stripe_status" {{ $data['stripe_status']==1
                                                                ? 'checked' : '' }} id="customCheck1" type="checkbox">
                                                            <label class="custom-control-label" for="customCheck1">{{
                                                                __('Stripe') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div
                                                        class="form-group{{ $errors->has('STRIPE_SECRET') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="STRIPE SECRET">{{
                                                            __('Stripe
                                                            secret') }}</label>
                                                        <input type="text" name="stripe_secret" id="input-name"
                                                            class="hide_value form-control form-control-alternative{{ $errors->has('stripe_secret') ? ' is-invalid' : '' }}"
                                                            value="{{ old('stripe_secret',$data['stripe_secret']) }}">

                                                        @if ($errors->has('stripe_secret'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('stripe_secret') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div
                                                        class="form-group{{ $errors->has('stripe_public') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="STRIPE KEY">{{ __('Stripe
                                                            Public key') }}</label>
                                                        <input type="text" name="stripe_public" id="input-name"
                                                            class="hide_value form-control form-control-alternative{{ $errors->has('stripe_public') ? ' is-invalid' : '' }}"
                                                            value="{{ old('stripe_public',$data['stripe_public']) }}">

                                                        @if ($errors->has('stripe_public'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('stripe_public') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <div class="text-center">
                                                            <a type="submit"
                                                                href="https://stripe.com/docs/keys#obtain-api-keys"
                                                                target="_blank" class="btn btn-primary mt-4 rtl_btn">{{
                                                                __('Help') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="hide_value custom-control-input" value="1"
                                                                name="paypal_status" {{ $data['paypal_status']==1
                                                                ? 'checked' : '' }} id="paypal" type="checkbox">
                                                            <label class="custom-control-label" for="paypal">{{
                                                                __('Paypal')
                                                                }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div
                                                        class="form-group{{ $errors->has('paypal_sandbox') ? ' has-danger' : '' }}">
                                                        <label class="hide_value form-control-label"
                                                            for="Paypal Client Id">{{ __('Paypal Sandbox key')
                                                            }}</label>
                                                        <input type="text" name="paypal_sandbox" id="input-name"
                                                            class="form-control form-control-alternative{{ $errors->has('paypal_sandbox') ? ' is-invalid' : '' }}"
                                                            value="{{ old('paypal_sandbox',$data['paypal_sandbox']) }}">

                                                        @if ($errors->has('paypal_sandbox'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paypal_sandbox') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div
                                                        class="form-group{{ $errors->has('paypal_production') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="Paypal secret key">{{
                                                            __('Paypal Production key') }}</label>
                                                        <input type="text" name="paypal_production" id="input-name"
                                                            class="hide_value form-control form-control-alternative{{ $errors->has('paypal_production') ? ' is-invalid' : '' }}"
                                                            value="{{ old('paypal_production',$data['paypal_production']) }}">

                                                        @if ($errors->has('paypal_production'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paypal_production') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="offset-2 col-4">
                                                    <div
                                                        class="form-group{{ $errors->has('paypal_client_id') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="Paypal Client Id">{{
                                                            __('Paypal Client Id') }}</label>
                                                        <input type="text" name="paypal_client_id" id="input-name"
                                                            class="hide_value form-control form-control-alternative{{ $errors->has('paypal_client_id') ? ' is-invalid' : '' }}"
                                                            value="{{ old('paypal_client_id',$data['paypal_client_id']) }}">

                                                        @if ($errors->has('paypal_client_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paypal_client_id') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div
                                                        class="form-group{{ $errors->has('paypal_secret_key') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="Paypal secret key">{{
                                                            __('Paypal Secret key') }}</label>
                                                        <input type="text" name="paypal_secret_key" id="input-name"
                                                            class="hide_value form-control form-control-alternative{{ $errors->has('paypal_secret_key') ? ' is-invalid' : '' }}"
                                                            value="{{ old('paypal_secret_key',$data['paypal_secret_key']) }}">

                                                        @if ($errors->has('paypal_secret_key'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paypal_secret_key') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <div class="text-center">
                                                            <a type="submit"
                                                                href="https://www.appinvoice.com/en/s/documentation/how-to-get-paypal-client-id-and-secret-key-22"
                                                                target="_blank" class="btn btn-primary mt-4 rtl_btn">{{
                                                                __('Help') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary mt-4 rtl_btn">{{ __('Save')
                                                    }}</button>
                                            </div>
                                        </div>
                                    </form>
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
@endsection