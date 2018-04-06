@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Discounts</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/discounts/create') }}" class="btn btn-primary pull-right btn-sm">Add New Discount Rule</a>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>Discounting Percent</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($discounts as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->percent }}%</td>
                                        <td>
                                            <a href="{{ url('/admin/discounts/' . $item->id . '/edit') }}">
                                                <button type="submit" class="btn btn-primary btn-xs">Update</button>
                                            </a> /
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/admin/discounts', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $discounts->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
