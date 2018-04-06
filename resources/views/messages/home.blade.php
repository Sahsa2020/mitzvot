@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Contact Users</div>

                <div class="panel-body">
                    @foreach($users as $user)
                        <table class="table">
                            <tr>
                                <td style = "position: relative;">
                                    <img src="{{$user->image_url}}" onerror="this.src='assets/global/img/default_avatar.jpg'" style = "width: 100px; height: 100px;border-radius: 50%;">
                                    {{$user->name}}
                                    <span class="badge badge-danger" style="position: absolute; left: 80px;bottom: 10px;">{{$user->unread_messages}}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <a href="{{route('message.read', ['id'=>$user->id])}}" class="btn btn-success pull-right">Message</a>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
