@extends('layouts.shipper')

@section('content')
    <div class="container">
        <div class="row">
            @include('shipper.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">House IO History</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>Control Number</th>
                                        <th>Amount</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($ios as $item)
                                    <tr>
                                        <td>{{ date('m/d', strtotime($item->created_at))}}</td>
                                        <td>{{ $item->order_id }}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->comment }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
