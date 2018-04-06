@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Donations</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($donates as $donate)
                                    <tr>
                                        <td><img class="donate-image" src="{{$donate->picture}}" alt="" ></td>
                                        <td>{{ $donate->name }}</td>
                                        <td>{{ $donate->description }}</td>
                                        <td>
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/donates/approve', $donate->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('Approve', ['class' => 'btn btn-success btn-xs']) !!}
                                            {!! Form::close() !!}
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/donates/unapprove', $donate->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('UnApprove', ['class' => 'btn btn-danger btn-xs']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $donates->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
