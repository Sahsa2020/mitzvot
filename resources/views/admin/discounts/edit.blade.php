@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Discount Rule</div>
                    <div class="panel-body">

                        {!! Form::model($discount, [
                            'method' => 'PATCH',
                            'url' => ['/admin/discounts', $discount->id],
                            'class' => 'form-horizontal'
                        ]) !!}

                        @include ('admin.discounts.form', ['submitButtonText' => 'Update'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection