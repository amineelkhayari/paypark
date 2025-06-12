@extends('owner.app', ['title' => __('Booked User')],['activePage' => 'bookuser'])

@section('content')
  @include('owner.layouts.headers.header',
      array(
          'class'=>'info',
          'title'=>"Booked User",'description'=>'',
          'icon'=>'fas fa-home',
          'breadcrumb'=>array([
            'text'=>'Booked User'
])))

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
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

                    <div class="table-responsive" style="padding: 20px;">
                        <table class="table datatable align-items-center table-flush p-3">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col">{{ __('OrederNo') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Spacename') }}</th>
                                    <th scope="col">{{ __('Paymenttype') }}</th>
                                    <th scope="col">{{ __('TotalAmount') }}</th>
                                    <th scope="col">{{ __('Arrivingtime') }}</th>
                                    <th scope="col">{{ __('Leavingtime') }}</th>
                                    <th scope="col">{{ __('Payment status') }}</th>
                                    <th scope="col"></th>
                                     <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parkingBooking as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $booking->order_no }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->space->title }}</td>
                                        <td>{{ $booking->payment_type }}</td>
                                        <td>{{ $booking->total_amount }}</td>
                                        <td>{{ $booking->arriving_time}}</td>

                                        <td>{{ $booking->leaving_time}}</td>
                                            <td>
                                                @if($booking->payment_status == 0)   
                                                    <span class="badge badge-pill badge-warning">{{__('Remaning')}}</span>
                                                @elseif($booking->payment_status == 1)
                                                    <span class="badge badge-pill badge-success">{{__('Complete')}}</span>
                                                @elseif($booking->payment_status == 2)
                                                    <span class="badge badge-pill badge-danger">{{__('Cancel')}}</span>
                                                @endif   
                                            </td>
                                            <td class="text-right" >
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <form action="{{ url('owner/bookingPaymentStatus', $booking->id) }}" method="post">
                                                            @csrf
                                                        @if($booking->payment_status ==1)
                                                         <button type="submit" class="dropdown-item" value ="1" name="changepaymentstatus"> {{ __('Complete') }}  </button>
                                                        @else
                                                            @if($booking->payment_status != 0 ) 
                                                                <button type="submit" class="dropdown-item" value ="0" name="changepaymentstatus"> {{ __('Remaning') }}  </button>
                                                            @endif
                                                            @if($booking->payment_status != 1 )
                                                                <button type="submit" class="dropdown-item" value ="1" name="changepaymentstatus"> {{ __('Complete') }}  </button>
                                                            @endif 
                                                            @if($booking->payment_status != 2 )
                                                                <button type="submit" class="dropdown-item" value ="2" name="changepaymentstatus"> {{ __('Cancel') }}  </button>
                                                            @endif    
                                                        @endif
                                                        </form> 
                                                    </div>  
                                                </div>
                                            </td>
                                           
                                            <td>
                                                @if($booking->status == 0)   
                                                    <span class="badge badge-pill badge-warning">{{ __('Waiting')}}</span>
                                                @elseif($booking->status == 1)
                                                    <span class="badge badge-pill badge-info">{{ __('Approved')}}</span>
                                                @elseif($booking->status == 2)
                                                    <span class="badge badge-pill badge-success">{{ __('Complete')}}</span>
                                                @elseif($booking->status == 3)  
                                                    <span class="badge badge-pill badge-danger">{{ __('Cancel')}}</span>  
                                                @endif
                                            </td>
                                        
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ url('owner/bookingstatus', $booking->id) }}" method="post">
                                                        @csrf
                                                    @if($booking->status ==2)
                                                     <button type="submit" class="dropdown-item" value ="2" name="changestatus"> {{ __('Complete') }}  </button>
                                                    @else
                                                        @if($booking->status != 0 ) 
                                                            <button type="submit" class="dropdown-item" value ="0" name="changestatus"> {{ __('Waiting') }}  </button>
                                                        @endif
                                                        @if($booking->status != 1 )
                                                            <button type="submit" class="dropdown-item" value ="1" name="changestatus"> {{ __('Approved') }}  </button>
                                                        @endif 
                                                        @if($booking->status != 2 )
                                                            <button type="submit" class="dropdown-item" value ="2" name="changestatus"> {{ __('Complete') }}  </button>
                                                        @endif
                                                        @if($booking->status != 3 )
                                                            <button type="submit" class="dropdown-item" value ="3" name="changestatus"> {{ __('Cancel') }}  </button>
                                                        @endif  
                                                    @endif
                                                    </form> 
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