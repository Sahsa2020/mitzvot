@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="main-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h1><span><img src="images/menu-icon11-white.png" alt="list icon" class="img-responsive"></span> Messsage from Customer</h1>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="chatbox">
                                <div class="row-eq-height">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="chat-left">
                                                <div class="searchbox">
                                                    <input type="text" class="form-control" placeholder="Search">
                                                    <span class="search-btn">
                                                    <i class="fa fa-search" aria-hidden="true"></i></span>
                                                </div>
                                                <div class="user-list">
                                                    <ul>
                                                        @foreach($messages as $item)
                                                        <li>
                                                            <div class="name">
                                                                {{ $item->name }}
                                                                <span class="time">4 hours</span>
                                                            </div>
                                                            <div class="text">
                                                                <p>{{ $item->subject}}</p>
                                                            </div>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="chat-right">
                                                <ul>
                                                    @foreach($messages as $item)
                                                    <li>
                                                        <div class="col-md-2">
                                                            <div class="image">
                                                                <img src="../images/user3.png" class="img-responsive" alt="user-image">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 noleftpadding">
                                                            <div class="chat-text">
                                                                <div class="name">
                                                                    {{ $item->name }}
                                                                    <span class="time">4 hours </span>
                                                                </div>
                                                                <div class="text">
                                                                    <p>{{ $item->message }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach                              
                                                </ul>
                                                <div class="chatsent">
                                                    {!! Form::model($messages[0], [
                                                            'method' => 'POST',
                                                            'url' => ['/admin/message_history/reply', 17],
                                                            'class' => 'form-horizontal'
                                                    ]) !!}
                                                    <textarea class="form-control" name="message" rows="8" required="true" cols="40" placeholder="Write here..."></textarea>
                                                    <span class="attachment-icon">
                                                        <input type="file">
                                                        <img src="/assets/img/attachment-icon.png" class="img-responsive" alt="chat-sent-icon">
                                                    </span>
                                                    <span class="chat-sent-btn">
                                                        <input type="submit" hidden><img src="/assets/img/chat-sent-icon.png" class="img-responsive" alt="chat-sent-icon">
                                                    </span>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 hidden-xs hidden-sm"></div>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
    </div>
@endsection

<!-- <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Messages from Customers</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($messages as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->subject }}</td>
                                        <td>{{ $item->message }}</td>
                                        <td>
                                            <a href="/admin/message_history/reply/{{$item->id}}">
                                                <button type="submit" class="btn btn-primary btn-xs">Reply</button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $messages->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div> -->
