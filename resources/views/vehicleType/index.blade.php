@extends('layouts.app', ['title' => __('Vehicle Type ')],['activePage' => 'vehicleType'])

@section('content')
@include('layouts.headers.header',
array(
'class'=>'info',
'title'=>"Vehicle Type",'description'=>'',
'icon'=>'fas fa-home',
'breadcrumb'=>array([
'text'=>'Vehicle Type'
])))

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-12 text-right">
                            <a href="{{ route('vehicle_type.create') }}" class="btn btn-sm btn-primary">{{ __('Add Vehicle Type') }}</a>
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

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('#') }}</th>
                                <th scope="col">{{ __('Type') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col">{{ __('Creation Date') }}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($VehicleType as $item)
                            <tr>
                                <td>{{ $loop->iteration}}</td>

                                <td>{{ $item->title }}</td>

                                <td>
                                    <span class="badge badge-pill badge-{{$item->status == 0 ? 'warning' : 'success'}}">
                                        {{$item->status == 1 ? 'Enable':'Disable'}}
                                    </span>

                                </td>
                                <td><img src="{{ asset('vehicleType')}}/{{$item->image }}" class="rounded-circle" height="50" width="50"> </td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                            <form action="{{ route('vehicle_type.destroy', $item) }}" method="post">
                                                @csrf
                                                @method('delete')


                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>

                                            <a class="dropdown-item" href="{{ route('vehicle_type.edit',$item) }}">{{ __('Edit') }}</a>

                                        </div>
                                    </div>
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