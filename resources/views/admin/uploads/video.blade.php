@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload Video</div>
                    <div class="panel-body">
                        {!! Form::open([
                            'method'=>'POST',
                            'url' => ['/admin/upload_video'],
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'enctype' => 'multipart/form-data'
                        ]) !!}
                        <!--<form class="form-horizontal " role="form" method = "POST" action = "/admin/upload_video">-->
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <fieldset>
                                    <legend>The max file size is 10MB.</legend>
                                    <div class="form-group margin-top:20px">
                                        <label class="col-md-3 control-label" for="name"><span class="require"></span>Video</label>
                                        <div class="col-md-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput" style="float: left;">
                                                <span class="btn green btn-file">
                                                    <input class="imageFile" name="video" type="file" accept="video/mp4"> </span>
                                                <span class="fileinput-filename"> </span> &nbsp;
                                                <a class="close fileinput-exists" data-dismiss="fileinput" href="javascript:;"> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div    style="text-align:center;">
                                        <button class="btn btn-primary green" type="submit">Upload</button>
                                        </div>
                                    </div>
                                    </fieldset>
                                </div>
                            </div>
                        <!--</form>-->
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
