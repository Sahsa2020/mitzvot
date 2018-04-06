@extends('layouts.shipper')

@section('content')
    <div class="container">
        <div class="row">
            @include('shipper.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Make Order</div>
                    <div class="panel-body">
                        {!! Form::model($order, [
                            'method' => 'POST',
                            'url' => ['/shipper/order', $order->id],
                            'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                {!! Form::label('id', 'Control Number: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('id', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('date', 'Date: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('date', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                {!! Form::label('name', 'Name: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('email', 'Email: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'disabled']) !!}
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
                                {!! Form::label('amount', 'Amount: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('amount', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                {!! Form::label('house', 'WareHouse: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::select('house', $houses, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('amount', 'Amount: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::number('amount', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('comment', 'Comment: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('comment', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                            </div>
                            @if($order->del_flg == 0)
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">
                                    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Ship', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            @else
                            <div class="form-group">
                                {!! Form::label('shipped', 'Shipped: ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('shipped', null, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                            @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection