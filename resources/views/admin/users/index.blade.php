@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

                <div class="col-md-10">
                    <div class="main-body">
                        <div class="col-md-12">
                            <div class="row">
                                <h1><span><i class="fa fa-users" aria-hidden="true"></i></span> Users</h1>
                            </div>
                        </div>
                        <div class="table-box col-md-8 box-display">
                            <div class="table-head">
                                <h3>Platform users</h3>
                                <div class="top-right users">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <select class="form-control">
                                                <option value="0">Sort By</option>
                                                <option>Price: Low to High</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="searchbox">
                                            <input type="text" class="form-control" placeholder="Search">
                                            <span class="search-btn">
                                            <i class="fa fa-search" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="icons">
                                                <ul>
                                                    <li><a href="{{ url('/admin/users/create') }}"><img src="/assets/img/add-icon.png" class="img-responsive" alt="Add Icon" title="Add User"></a></li>                                                    
                                                    <li>
                                                        {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/admin/users', '19'],
                                                            'style' => 'display:inline',
                                                            'id' => 'delete-user'
                                                        ]) !!}
                                                            <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!} -->
                                                            {!! Form::image('/assets/img/delete-icon.png', 'Delete', ['class' => 'img-responsive']) !!}
                                                        {!! Form::close() !!}
                                                        <!-- <a type="" href="#" id="delete-user" method="delete"><img src="/assets/img/delete-icon.png" class="img-responsive" alt="Delete Icon" title="Delete User"></a> -->
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <!-- <th>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" checked="checked">
                                                        <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>
                                                    </label>
                                                </div>
                                            </th> -->
                                            <th></th>
                                            <th>ID</th>
                                            <th>Member Name</th>
                                            <th>Box ids</th>
                                            <th class="text-center">IP</th>
                                            <th>Organization</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $item)
                                            <tr class="row">
                                                <td>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="checkbox-input">
                                                            <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    <a href="{{ url('/admin/users', $item->id) }}"><img src="{{ $item->image_url }}" class="img-responsive" alt="user"></a>
                                                    <div class="name">{{ $item->name }}</div>
                                                </td>
                                                <td>#135</td>
                                                <td class="text-center">104.237.90.126</td>
                                                <td>Organization Name</td>
                                                <td>
                                                    <div class="settings">
                                                        <a href="#"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div>{!! $users->render() !!}</div>
                            <!-- <div class="pagination-block text-right">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                    <li class="page-item"><a class="page-link" href="#">&lt; Prev</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next ></a></li>
                                    </ul>
                                </nav>
                            </div> -->
                        </div>
                        <div class="col-md-4 hidden-xs hidden-sm"></div>
                        <div class="clear"></div>
                    </div>
                </div>

        </div>
    </div>
@endsection

<!-- jquery -->
<script src="/assets/global/js/jquery.min.js"></script>
<script src="/assets/global/js/jquery-migrate.min.js" type="text/javascript"></script>

<script>
    var is_checked = false;
    var selected_id;
    $(document).ready(function(){
        $("table").delegate("tr.row .checkbox-input", "click", function(){

            $('table tr.row .checkbox-input').each(function(index) {
                if($(this).is(':checked')) {
                    // console.log('click', $(this).is(':checked'));
                    $(this).prop('checked', false);
                }
            });
            $('table tr.row .settings').each(function(index) {
                $(this).removeClass('selected');
                $(this).prop('disabled', true);
            });
            $(this).prop('checked', !is_checked);

            if($(this).is(':checked')) {
                $('table tr.row').each(function () {
                    if ($(this).find('input').is(':checked')) {
                        // console.log($(this).children('td:nth-child(2)').text());
                        selected_id = $(this).children('td:nth-child(2)').text();
                        url = '/admin/users/' + selected_id;
                        if (typeof selected_id == 'undefined') 
                            return;
                        $('#delete-user').attr('action', url);
                        $(this).children('td:nth-child(7)').find('.settings').addClass('selected');
                        $(this).children('td:nth-child(7)').find('.settings').prop('disabled', false);
                    }
                });
            }
        });

        $("table").delegate("tr.row .settings a", "click", function(){
            // console.log('settings clicked');
            url = '/admin/users/' + selected_id + '/edit';
            if (typeof selected_id == 'undefined') 
                return;
            $(this).attr('href', url);
        });

        // $('#delete-user').on('click', function() {
        //     console.log('clicked');
        //     selected_id = 23;
        //     var url = '/admin/users/' + selected_id;
        //     $.ajax({
        //         url: url,
        //         type: 'DELETE',
        //         success: function(result) {
        //             location.reload(true);
        //             // Do something with the result
        //         }
        //     });
        // });
    });
</script>

<!-- <div class="col-md-9"> -->
    <!-- <div class="panel panel-default">
        <div class="panel-heading">Users</div>
        <div class="panel-body">

            <a href="{{ url('/admin/users/create') }}" class="btn btn-primary pull-right btn-sm">Add New User</a>
            <br/>
            <br/>

            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><a href="{{ url('/admin/users', $item->id) }}">{{ $item->name }}</a></td><td>{{ $item->email }}</td>
                            <td>{{ $item->ip_address }}</td>
                            <td>
                                <a href="{{ url('/admin/users/' . $item->id . '/edit') }}">
                                    <button type="submit" class="btn btn-primary btn-xs">Update</button>
                                </a> /
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/admin/users', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination"> {!! $users->render() !!} </div>
            </div>

        </div>
    </div> -->
<!-- </div> -->