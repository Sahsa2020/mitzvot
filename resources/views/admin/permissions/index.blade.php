@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="/assets/images/admin/menu-icon3-white.png" alt="list icon" class="img-responsive"></span> Permissions</h1>
                        </div>
                    </div>
                    <div class="table-box col-md-6 box-display">
                        <div class="table-head">
                        <h3 style="padding-left: 0px">Current Permissions</h3>
                            <div class="top-right">
                                <div class="col-md-8">
                                    <!-- <div class="row">
                                        <select class="form-control">
                                            <option value="0">Sort By</option>
                                            <option>Price: Low to High</option>
                                        </select>
                                    </div> -->
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="icons text-right">
                                            <ul>
                                                <li><a href="{{ url('/admin/permissions/create') }}"><img src="/assets/img/add-icon.png" class="img-responsive" alt="Add Icon" title="Add User"></a></li>
                                                <!-- <li><a href="#" id="delete-permission"><img src="/assets/img/delete-icon.png" class="img-responsive" alt="Delete Icon" title="Delete User"></a></li> -->
                                                <li>
                                                {!! Form::open([
                                                    'method'=>'DELETE',
                                                    'url' => ['/admin/permissions', '19'],
                                                    'style' => 'display:inline',
                                                    'id' => 'delete-permission'
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
                                    @foreach($permissions as $item)
                                    <tr class="row">
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="checkbox-input">
                                                    <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>1</td>
                                        <td>
                                            <div class="name"><a href="{{ url('/admin/permissions', $item->id) }}">{{ $item->name }}</a></div>
                                        </td>
                                        <td>{{ $item->label }}</td>
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
                        <div class="pagination">{!! $permissions->render() !!}</div>
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
            console.log('checkbox-clicked');
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
                        url = '/admin/permissions/' + selected_id;
                        if (typeof selected_id == 'undefined') 
                            return;
                        $('#delete-permission').attr('action', url);
                        $(this).children('td:nth-child(5)').find('.settings').addClass('selected');
                    }
                });
            }
        });

        $("table").delegate("tr.row .settings a", "click", function(){
            // console.log('settings clicked');
            url = '/admin/permissions/' + selected_id + '/edit';
            if (typeof selected_id == 'undefined') 
                return;
            $(this).attr('href', url);
        });
    });
    
</script>

<!-- <div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">Permissions</div>
        <div class="panel-body">

            <a href="{{ url('/admin/permissions/create') }}" class="btn btn-primary pull-right btn-sm">Add New Permission</a>
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
                    @foreach($permissions as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><a href="{{ url('/admin/permissions', $item->id) }}">{{ $item->name }}</a></td><td>{{ $item->label }}</td>
                            <td>
                                <a href="{{ url('/admin/permissions/' . $item->id . '/edit') }}">
                                    <button type="submit" class="btn btn-primary btn-xs">Update</button>
                                </a> /
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/admin/permissions', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination"> {!! $permissions->render() !!} </div>
            </div>

        </div>
    </div>
</div> -->