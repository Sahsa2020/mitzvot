@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="/assets/img/list-icon.png" alt="list icon" class="img-responsive"></span> Roles</h1>
                        </div>
                    </div>
                    <div class="table-box col-md-6 box-display">
                        <div class="table-head">
                            <!-- <div class="col-md-4">
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
                            </div> -->
                            <div class=" text-right">
                                <div class="row">
                                    <div class="icons" >
                                        <ul style="padding-right: 20px;">
                                            <li><a href="{{ url('/admin/roles/create') }}"><img src="/assets/img/add-icon.png" class="img-responsive" alt="Add Icon" title="Add User"></a></li>
                                            <!-- <li><a href="#" id="delete-role"><img src="/assets/img/delete-icon.png" class="img-responsive" alt="Delete Icon" title="Delete User"></a></li> -->
                                            <li>
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/admin/roles', '19'],
                                                'style' => 'display:inline',
                                                'id' => 'delete-role'
                                            ]) !!}
                                                <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!} -->
                                                {!! Form::image('/assets/img/delete-icon.png', 'Delete', ['class' => 'img-responsive']) !!}
                                            {!! Form::close() !!}
                                            </li>
                                        </ul>
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
                                                    <input type="checkbox">
                                                    <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>
                                                </label>
                                            </div>
                                        </th> -->
                                        <th></th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Label</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $item)
                                        <tr class="row">
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input class="checkbox-input" type="checkbox">
                                                        <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <a href="{{ url('/admin/roles', $item->id) }}"><div class="name">{{ $item->name }}</div></a>
                                            </td>
                                            <td>individual</td>
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
                        <div class="pagination">{!! $roles->render() !!}</div>
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
                    <div class="col-md-6 hidden-xs hidden-sm"></div>
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
            });
            $(this).prop('checked', !is_checked);

            if($(this).is(':checked')) {
                $('table tr.row').each(function () {
                    if ($(this).find('input').is(':checked')) {
                        console.log($(this).children('td:nth-child(2)').text());
                        selected_id = $(this).children('td:nth-child(2)').text();
                        url = '/admin/roles/' + selected_id;
                        if (typeof selected_id == 'undefined') 
                            return;
                        $('#delete-role').attr('action', url);
                        $(this).children('td:nth-child(5)').find('.settings').addClass('selected');
                    }
                });
            }
        });

        $("table").delegate("tr.row .settings a", "click", function(){
            // console.log('settings clicked');
            url = '/admin/roles/' + selected_id + '/edit';
            if (typeof selected_id == 'undefined') 
                return;
            $(this).attr('href', url);
        });
    });
    
</script>

            <!-- <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Roles</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/roles/create') }}" class="btn btn-primary pull-right btn-sm">Add New Role</a>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Name</th><th>Label</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><a href="{{ url('/admin/roles', $item->id) }}">{{ $item->name }}</a></td><td>{{ $item->label }}</td>
                                        <td>
                                            <a href="{{ url('/admin/roles/' . $item->id . '/edit') }}">
                                                <button type="submit" class="btn btn-primary btn-xs">Update</button>
                                            </a> /
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['admin/roles', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $roles->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div> -->