@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <diwv class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><i class="fa fa-users" aria-hidden="true"></i></span> User</h1>
                        </div>
                    </div>
                    <div class="table-box col-md-8 box-display">                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>                                        
                                        <th>ID</th>
                                        <th>Member Name</th>
                                        <th>Email</th>
                                        <!-- <th class="text-center">IP</th>
                                        <th>Organization</th>
                                        <th></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                <img src="{{ $user->image_url }}" class="img-responsive" alt="user">
                                                <div class="name">{{ $user->name }}</div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                        </tr>                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 hidden-xs hidden-sm"></div>
                    <div class="clear"></div>
                </div>
            </div>            
        </div>
    </div>
@endsection

<!-- <div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">User</div>
        <div class="panel-body">

            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>ID.</th> <th>Name</th><th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->id }}</td> <td> {{ $user->name }} </td><td> {{ $user->email }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div> -->