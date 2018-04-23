@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="/assets/images/admin/menu-icon9-white.png" alt="list icon" class="img-responsive"></span> Firmware</h1>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row permission-box">
                            <div class="header">
                                <h2>Upload Firmware</h2>
                                <a href="#" class="close-btn">X</a>
                            </div>
                            <div class="form">
                                <!-- <form role="form" method = "POST" action = "/admin/upload_video"> -->
                                {!! Form::open([
                                    'method'=>'POST',
                                    'url' => ['/admin/upload_firmware'],
                                    'class' => '',
                                    'role' => 'form',
                                    'enctype' => 'multipart/form-data'
                                ]) !!}
                                    <div class="form-section">
                                        <div class="form-group form-group-border">
                                            <label>Major Version</label>
                                            <input type="number" name="major_version" class="form-control" required="true">
                                            <!-- <select class="form-control">
                                                <option>1.02</option>
                                            </select> -->
                                        </div>
                                        <div class="form-group form-group-border">
                                            <label>Major Version</label>
                                            <input type="number" name="minor_version" class="form-control" required="true">
                                            <!-- <select class="form-control">
                                                <option>1.00</option>
                                            </select> -->
                                        </div>
                                        <div class="form-group">
                                            <div class="choosefile">
                                            <input class="imageFile" name="firmware" type="file" required = "true">
                                                <img src="/assets/img/choosefile-btn.png" class="img-responsive" alt="File Upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group buttons">
                                      <input type="submit" class="form-control community-btn" value="Save">
                                      <input type="reset" class="form-control reset-btn" value="Cancel">
                                    </div>
                                {!! Form::close() !!}
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 hidden-xs hidden-sm"></div>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
    </div>
@endsection

<!-- <div class="col-md-9">
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
            <!<form class="form-horizontal " role="form" method = "POST" action = "/admin/upload_video">
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
            {!! Form::close() !!}
        </div>
    </div>
</div> -->
