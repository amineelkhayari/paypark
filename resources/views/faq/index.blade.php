@extends('layouts.app', ['title' => __('FAQ ')],['activePage' => 'faq'])

@section('content')
@include('layouts.headers.header',
array(
'class'=>'info',
'title'=>"FAQ",'description'=>'',
'icon'=>'fas fa-home',
'breadcrumb'=>array([
'text'=>'FAQ'
])))

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-12 text-right">
                            <a href="{{route('faq.create')}}" class="btn btn-sm btn-primary">{{ __('Add FAQ') }}</a>
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
                                <th scope="col">{{ __('Question') }}</th>
                                <th scope="col">{{ __('Answer') }}</th>                                
                                <th scope="col">{{ __('Creation Date') }}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($faq as $item)
                            <tr>
                                <td>{{ $loop->iteration}}</td>
                                <td>{{ $item->question }}</td>
                                <td>{{ $item->answer }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <form action="{{ route('faq.destroy', $item) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __(" Are you sure you want to delete this user? ") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            <a class="dropdown-item rtl_edit" href="{{ route('faq.edit',$item) }}">{{ __('Edit') }}</a>
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