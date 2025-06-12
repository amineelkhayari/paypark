@extends('layouts.app', ['title' => __('Parking Owner')],['activePage' => 'parkingOwner'])

@section('content')
     @include('layouts.headers.header',
      array(
          'class'=>'danger',
          'title'=>"Parking Owner",'description'=>'',
          'icon'=>'fas fa-home',
          'breadcrumb'=>array([
            'text'=>'Owner List'
])))

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-12 text-right">
                                {{-- <a href="{{ route('vehicleType.create') }}" class="btn btn-sm btn-primary">{{ __('Add Parking Owner') }}</a> --}}
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

                    <div class="table-responsive" style="padding: 20px">
                        <table class="table datatable align-items-center table-flush p-3">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Phone No') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parkingOwner as $item)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone_no }}</td>
                                        <td>
                                            <span class="badge badge-pill badge-{{$item->status == 0 ? 'warning' : 'success'}}">
                                                {{$item->status == 1 ? 'Enable':'Disable'}}
                                            </span>
                                        </td>
                                        <td><img src="{{ asset('upload')}}/{{$item->image }}" class="rounded-circle" height="50" width="50"> </td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ url('/block_parkingOwner', $item) }}" method="post">
                                                        @csrf
                                                        @if ($item->status == 1)
                                                            <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __('Are you sure you want to block this user?') }}') ? this.parentElement.submit() : ''">
                                                                {{ __('Block') }}
                                                            </button>
                                                        @else
                                                            <button type="button" class="dropdown-item"
                                                                onclick="confirm('{{ __('Are you sure you want to unblock this user?') }}') ? this.parentElement.submit() : ''">
                                                                {{ __('Unblock') }}
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <a class="dropdown-item rtl_edit" href="{{ url('parkingOwner/'.$item->id.'/space', []) }}">{{ __('View') }}</a>
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