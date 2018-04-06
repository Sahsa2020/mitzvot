@extends('layouts.shipper')

@section('content')
    <div class="container">
        <div class="row">
            @include('shipper.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Houses</div>
                    <div class="panel-body">

                        <a href="{{ url('/shipper/warehouses/create') }}" class="btn btn-primary pull-right btn-sm">Add New House</a>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Balance</th>
                                        <th>Manager</th>
                                        <th>Manager Email</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($houses as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->balance }}</td>
                                        <td>{{ $item->manager }}</td>
                                        <td>{{ $item->manager_email }}</td>
                                        <td>{{ $item->address }}</td>
                                        <td>{{ $item->city }}</td>
                                        <td>{{ $item->state }}</td>
                                        <td>{{ $item->country }}</td>
                                        <td>
                                            <a href="{{ url('/shipper/warehouse/' . $item->id . '/edit') }}">
                                                <button type="submit" class="btn btn-primary btn-xs">Update</button>
                                            </a> /
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/shipper/warehouses', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                            {!! Form::close() !!} /
                                            <a href="{{ url('/shipper/warehouse/' . $item->id . '/addInventory') }}">
                                                <button type="submit" class="btn btn-primary btn-xs">Add Inventory</button>
                                            </a>
                                            <a href="{{ url('/shipper/warehouse/' . $item->id . '/history') }}">
                                                <button type="submit" class="btn btn-primary btn-xs">IO History</button>
                                            </a>
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
    </div>
@endsection
