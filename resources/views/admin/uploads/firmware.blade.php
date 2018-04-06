@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload Firmware</div>
                    <div class="panel-body">
                        {!! Form::open([
                            'method'=>'POST',
                            'url' => ['/admin/upload_firmware'],
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'enctype' => 'multipart/form-data'
                        ]) !!}
                        <!--<form class="form-horizontal " role="form" method = "POST" action = "/admin/upload_video">-->
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <fieldset>
                                    <div class="form-group margin-top:20px">
                                        <label class="col-md-3 control-label" for="name"><span class="require"></span>Major Version</label>
                                        <div class='form-group col-md-9'>
                                            <input type="number" name="major_version" class="form-control" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group margin-top:20px">
                                        <label class="col-md-3 control-label" for="name"><span class="require"></span>Minor Version</label>
                                        <div class='form-group col-md-9'>
                                            <input type="number" name="minor_version" class="form-control" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group margin-top:20px">
                                        <label class="col-md-3 control-label" for="name"><span class="require"></span>Firmware</label>
                                        <div class="col-md-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput" style="float: left;">
                                                <span class="btn green btn-file">
                                                    <input class="imageFile" name="firmware" type="file" required = "true"> </span>
                                                <span class="fileinput-filename"> </span> &nbsp;
                                                <a class="close fileinput-exists" data-dismiss="fileinput" href="javascript:;"> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div    style="text-align:center;">
                                        <button class="btn btn-primary green" type="submit">Update Firmware</button>
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
