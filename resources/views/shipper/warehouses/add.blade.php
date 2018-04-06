@extends('layouts.shipper')

@section('content')
    <div class="container">
        <div class="row">
            @include('shipper.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Add or Remove Inventory for House</div>
                    <div class="panel-body">
                        {!! Form::model($house, [
                            'method' => 'POST',
                            'url' => ['/shipper/warehouses/inventory', $house->id],
                            'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                {!! Form::label('name', 'Name: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('manager', 'Manager Name: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('manager', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('manager_email', 'Manager Email: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::email('manager_email', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address', 'Address: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('address', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('city', 'City: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('city', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('state', 'State: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('state', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('country', 'Country: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('country', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('amount', 'Inventory Count: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::number('amount', null, ['class' => 'form-control', 'required' => 'true']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('comment', 'Comment: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('comment', null, ['class' => 'form-control', 'required' => 'true']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">
                                    {!! Form::submit('Add Inventory', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection