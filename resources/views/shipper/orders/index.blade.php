@extends('layouts.shipper')

@section('content')
    <div class="container">
        <div class="row">
            @include('shipper.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Orders</div>
                    <div class="panel-body">
                        {!! Form::open([
                                                'method'=>'GET',
                                                'url' => ['/shipper/orders'],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                <select name="state" class="form-control" style= " width: 200px; display: inline;" >
                                                    <option value="" {{$state==''? 'selected': ''}}>All</option>
                                                    <option value="APPOINTED" {{$state=='APPOINTED'? 'selected': ''}}>Booked</option>
                                                    <option value="DONE" {{$state=='DONE'? 'selected': ''}}>Shipped</option>
                                                </select>
                                                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                                            {!! Form::close() !!}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Control Number</th>
                                        <th>Amount</th>
                                        <th>Shipped</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $item)
                                    <tr>
                                        <td>{{ date('m/d', strtotime($item->created_at))}}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->shipped_amount }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->ip_address }}</td>
                                        <td>{{ $item->city }}</td>
                                        <td>{{ $item->state }}</td>
                                        <td>{{ $item->country }}</td>
                                        <td>
                                            @if($item->del_flg == 0)
                                            <a href="{{ url('/shipper/order/' . $item->id ) }}">
                                                <button type="submit" class="btn btn-primary btn-xs">Order</button>
                                            </a>
                                            @else
                                            <a href="{{ url('/shipper/order/' . $item->id ) }}">
                                                <button type="submit" class="btn btn-primary btn-xs">Detail</button>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $orders->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
