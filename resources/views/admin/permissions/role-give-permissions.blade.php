@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="/assets/images/admin/menu-icon4-white.png" alt="list icon" class="img-responsive"></span> Give Role Permission</h1>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row permission-box">
                            <div class="header">
                                <h2>Give Permission to Role</h2>
                                <a href="#" class="close-btn">X</a>
                            </div>
                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="form">
                                <form role="form" method="POST" action="/admin/give-role-permissions">
                                    <div class="form-section">
                                        <div class="form-group form-group-border {{ $errors->has('name') ? ' has-error' : ''}}">
                                            <label>Role</label>
                                            <select class="roles form-control" id="role" name="role">
                                                @foreach($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->label }}</option>
                                                @endforeach()
                                            </select>
                                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="form-group form-group-border {{ $errors->has('name') ? ' has-error' : ''}}">
                                            <label>Permission</label>
                                            <select class="permissions form-control" id="permissions" name="permissions[]" multiple="multiple">
                                                @foreach($permissions as $permission)
                                                <option value="{{ $permission->name }}">{{ $permission->label }}</option>
                                                @endforeach()
                                            </select>
                                            {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group buttons">
                                      <input type="submit" class="form-control community-btn" value="Grant" >
                                      <input type="reset" class="form-control reset-btn" value="Cancel">
                                    </div>
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
                    <div class="panel-heading">Give Permission to Role</div>
                    <div class="panel-body">

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::open(['method' => 'POST', 'url' => ['/admin/give-role-permissions'], 'class' => 'form-horizontal']) !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
                            {!! Form::label('name', 'Role: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <select class="roles form-control" id="role" name="role">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->label }}</option>
                                    @endforeach()
                                </select>
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
                            {!! Form::label('label', 'Permissions: ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <select class="permissions form-control" id="permissions" name="permissions[]" multiple="multiple">
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->label }}</option>
                                    @endforeach()
                                </select>
                                {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-4">
                                {!! Form::submit('Grant', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div> -->