@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Reply Message to Customer {{$message->name}}</div>
                    {!! Form::model($message, [
                            'method' => 'POST',
                            'url' => ['/admin/message_history/reply', $message->id],
                            'class' => 'form-horizontal'
                    ]) !!}
                        <div class="row">
                            <div class="col-md-12 col-sm-12" style="padding: 30px;">
                                <fieldset>
                                <div class="form-group margin-top:20px">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" id="subject" name="subject"  required="true" placeholder='Subject' value="RE: {{$message->subject}}">
                                    </div>
                                </div>
                                <div class="form-group margin-top:20px">
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="message" rows="8" required="true" cols="40" placeholder='Message'>{{$message->message}}
----------------------------------------------------------
</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div  style="text-align:center;">
                                    <button type="submit" class="btn btn-primary green">Reply</button>
                                    </div>
                                </div>
                                </fieldset>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
