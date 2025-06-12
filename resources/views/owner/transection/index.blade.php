@extends('owner.app', ['title' => __('Transaction')],['activePage' => 'transection'])

@section('content')
  @include('owner.layouts.headers.header',
      array(
          'class'=>'info',
          'title'=>"Transaction",'description'=>'',
          'icon'=>'fas fa-home',
          'breadcrumb'=>array([
            'text'=>'Transaction'
])))

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Transaction') }}</h3>
                            </div>
                            <div class="col-3 text-center">
                                <a href="" class="btn btn-sm btn-primary">{{__('GrandTotal')}}:- ${{$tempData['grandTotal']}}</a>
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
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('UserPhoto') }}</th>
                                    <th scope="col">{{ __('Total Amount') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tempData['data'] as $sgurad)
                                <tr>
                                <td>{{ $loop->iteration}}</td>
                                 <td>{{ $sgurad['user']['name'] }}</td>
                                 <td><img src="{{ asset('upload')}}/{{$sgurad['user']['image'] }}" class="rounded-circle" height="50" width="50"> </td>
                                 <td>${{ $sgurad['total'] }}</td>
                                 <td class="text-right"></td>
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