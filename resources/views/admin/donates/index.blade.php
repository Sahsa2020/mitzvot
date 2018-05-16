@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')
            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="/assets/images/admin/menu-icon6-white.png" class="img-responsive" alt="Icon"></span>
                            Approve Donates</h1>
                        </div>
                    </div>
                    <div class="col-md-12 row">
                        <div class="row row-eq-height" style="display:inline-block;">
                            @foreach($donates as $donate)
                            <div class="col-md-3">
                                <div class="organization">
                                    <div class="image">
                                        <img src="{{$donate->picture}}" class="img-responsive" alt="image" width="250px">
                                        <!-- <div class="small-img">
                                            <ul>
                                                <li class="donate-img">
                                                    <a href="#">
                                                        <img src="/assets/img/donate-logo.png" class="img-responsive" alt="donate-logo">
                                                    </a>
                                                </li>
                                                <li class="envelope-img">
                                                    <a href="#">
                                                        <img src="/assets/img/envelope.png" class="img-responsive" alt="envelope-logo">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div> -->
                                    </div>
                                    <div class="org-details">
                                        <h2>{{ $donate->name }}</h2>
                                        <div class="donate-description">
                                            <p>{{ $donate->description }}</p>
                                        </div>
                                        <!-- <div class="download-btns">
                                            <ul>
                                                <li><a href="#"><img src="/assets/images/admin/doc-icon.png" class="img-responsive" alt="image"></a></li>
                                                <li><a href="#"><img src="/assets/images/admin/pdf-icon.png" class="img-responsive" alt="image"></a></li>
                                            </ul>
                                        </div> -->
                                    </div>
                                    <div class="buttons">
                                        <!-- <div class="row">
                                        </div> -->
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/donates/approve', $donate->id],
                                                'style' => 'display:inline; text-align: center'
                                            ]) !!}
                                                {!! Form::submit('Approve', ['class' => 'community-btn donates']) !!}
                                            {!! Form::close() !!}
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/donates/unapprove', $donate->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('Decline', ['class' => 'community-btn decline-btn']) !!}
                                            {!! Form::close() !!}
                                        <!-- <a href="#" class="community-btn donates">approve</a>
                                        <a href="#" class="decline-btn">decline</a> -->
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pagination"> {!! $donates->render() !!} </div>
                    <div class="col-md-4 hidden-xs hidden-sm"></div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
@endsection



<!-- <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Donations</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($donates as $donate)
                                    <tr>
                                        <td><img class="donate-image" src="{{$donate->picture}}" alt="" ></td>
                                        <td>{{ $donate->name }}</td>
                                        <td>{{ $donate->description }}</td>
                                        <td>
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/donates/approve', $donate->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('Approve', ['class' => 'btn btn-success btn-xs']) !!}
                                            {!! Form::close() !!}
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/donates/unapprove', $donate->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::submit('UnApprove', ['class' => 'btn btn-danger btn-xs']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $donates->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div> -->
