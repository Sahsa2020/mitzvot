@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="/assets/images/admin/menu-icon9-white.png" alt="list icon" class="img-responsive"></span>Policies and Rules Upload</h1>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row permission-box">
                            <div class="header">
                                <h2>The max file size is 10MB.</h2>
                                <a href="#" class="close-btn">X</a>
                            </div>
                            <div class="form">
                                <!-- <form role="form" method = "POST" action = "/admin/upload_video"> -->
                                {!! Form::open([
                                    'method'=>'POST',
                                    'url' => ['/admin/upload_policies_rules'],
                                    'class' => 'form-horizontal',
                                    'role' => 'form',
                                    'enctype' => 'multipart/form-data'
                                ]) !!}
                                <form class="form-horizontal " role="form" method = "POST" action = "/admin/upload_video">
                                    <div class="form-section">                                        
                                        <div class="form-group">
                                            <div class="choosefile">
                                                <input class="imageFile" name="rule" type="file" accept=".docx">
                                                <img src="/assets/img/choosefile-btn.png" class="img-responsive" alt="File Upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group buttons">
                                    <input type="submit" class="form-control community-btn" value="Upload">
                                    <!-- <input type="reset" class="form-control reset-btn" value="Cancel"> -->
                                    </div>
                                {!! Form::close() !!}
                                </form>
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
        <div class="panel-heading">Upload Policies and Rules</div>
        <div class="panel-body">
            {!! Form::open([
                'method'=>'POST',
                'url' => ['/admin/upload_policies_rules'],
                'class' => 'form-horizontal',
                'role' => 'form',
                'enctype' => 'multipart/form-data'
            ]) !!}
            <form class="form-horizontal " role="form" method = "POST" action = "/admin/upload_video">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <fieldset>
                        <legend>The max file size is 10MB.</legend>
                        <div class="form-group margin-top:20px">
                            <label class="col-md-3 control-label" for="name"><span class="require"></span>Rule</label>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new" data-provides="fileinput" style="float: left;">
                                    <span class="btn green btn-file">
                                        <input class="imageFile" name="rule" type="file" accept=".docx"> </span>
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
            </form>
            {!! Form::close() !!}
        </div>
    </div>
</div> -->
