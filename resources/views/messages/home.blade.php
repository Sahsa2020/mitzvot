@extends('layouts.app')

@section('content')
<!-- <div class="container">
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
</div> -->

<body>
  <div class="login-body">
    <!-- Body Content Start -->
        <div class="container">
            <div class="row">
                <div class="col-md-3 noleftpadding">
                    <div class="sidebar">
                        <div class="sidebar-inner">
                            <div class="myprofile">
                                <img src="{{ auth()->user()->image_url }}" class="img-responsive" alt="Image">
                                <h1>{{ auth()->user()->name }}</h1>
                                <span class="org-name"><i class="fa fa-users" aria-hidden="true"></i> Organization Name</span>
                            </div>
                            <div class="side-nav">
                                <ul>
                                    <li>
                                        <a href="/#/profile">
                                            <img src="/assets/personal-account/img/menu-icon1-dark.png" class="img-responsive" alt="menu-icon"> 
                                            <img src="/assets/personal-account/img/menu-icon1.png" class="img-responsive onhovershow" alt="icon">
                                            My account
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="#"><img src="images/menu-icon2.png" class="img-responsive" alt="menu-icon"> Good Deeds</a>
                                    </li> -->
                                    <li>
                                        <a href="#">
                                            <img src="/assets/personal-account/img/menu-icon3-dark.png" class="img-responsive" alt="menu-icon"> 
                                            <img src="/assets/personal-account/img/menu-icon3.png" class="img-responsive onhovershow" alt="menu-icon"> 
                                            Gallery
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/#/profile/friends">
                                            <img src="/assets/personal-account/img/menu-icon4-dark.png" class="img-responsive" alt="menu-icon"> 
                                            <img src="/assets/personal-account/img/menu-icon4.png" class="img-responsive onhovershow" alt="menu-icon">
                                            My friends
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="/messages/">
                                            <img src="/assets/personal-account/img/menu-icon9-dark.png" class="img-responsive" alt="menu-icon"> 
                                            <img src="/assets/personal-account/img/menu-icon9.png" class="img-responsive onhovershow" alt="menu-icon"> 
                                            Messages
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="notifications.html"><img src="images/menu-icon10.png" class="img-responsive" alt="menu-icon"> Donate</a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="images/menu-icon6.png" class="img-responsive" alt="menu-icon"> My boxies</a>
                                    </li> -->
                                </ul>
                                <ul>
                                    <li>
                                        <a href="/#/profile/edit-profile">
                                            <img src="/assets/personal-account/img/menu-icon7-dark.png" class="img-responsive" alt="menu-icon"> 
                                            <img src="/assets/personal-account/img/menu-icon7.png" class="img-responsive onhovershow" alt="menu-icon">
                                            Edit Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/logout">
                                            <img src="/assets/personal-account/img/menu-icon8-dark.png" class="img-responsive" alt="menu-icon">
                                            <img src="/assets/personal-account/img/menu-icon8.png" class="img-responsive onhovershow" alt="menu-icon"> 
                                            Log Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="main-body">
                        <div class="">
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
                                                            @foreach($users as $user)
                                                            <li>
                                                                <div class="name">
                                                                    {{ $user->name }}
                                                                    <span class="time">4 hours</span>
                                                                </div>
                                                                <!-- <div class="text">
                                                                    <p>Subject title</p>
                                                                </div> -->
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        <!-- <a href="#" class="add-chats">+</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="chat-history chat-right">
                                                    <ul id="talkMessages">
                                                        @foreach($messages as $message)
                                                            @if($message->sender->id == auth()->user()->id)
                                                                <li >
                                                                    <div class="col-md-2">
                                                                        <div class="image">
                                                                            <!-- <img src="{{ auth()->user()->image_url }}" class="img-responsive" alt="user-image"> -->
                                                                            <img src="" class="img-responsive" alt="user-image" onerror="this.src='assets/global/img/default_avatar.jpg'">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-10 noleftpadding">
                                                                        <div class="chat-text">
                                                                            <div class="name">
                                                                                {{ auth()->user()->name }}
                                                                                <span class="time">{{ $message->humans_time }}</span>
                                                                            </div>
                                                                            <div class="text">
                                                                                <p>{{ $message->message }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @else
                                                                <li id="message-{{$message->id}}">
                                                                    <div class="col-md-2">
                                                                        <div class="image">
                                                                            <img src="$users[0]->image_url" class="img-responsive" alt="user-image" onerror="this.src='assets/global/img/default_avatar.jpg'">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-10 noleftpadding">
                                                                        <div class="chat-text">
                                                                            <div class="name">
                                                                                {{ $users[0]->name }}
                                                                                <span class="time">{{ $message->humans_time }}</span>
                                                                            </div>
                                                                            <div class="text">
                                                                                <p>{{ $message->message }}</p>                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                        @endforeach                                                       
                                                    </ul>
                                                    <div class="chatsent">
                                                        <form action="" method="post" id="talkSendMessage">
                                                            <textarea class="form-control" name="message-data" placeholder="Write here..." onkeydown = "checkEnter(event);"></textarea>
                                                            <span class="attachment-icon">
                                                                <input type="file">
                                                                <img src="/assets/img/attachment-icon.png" class="img-responsive" alt="chat-sent-icon">
                                                            </span>
                                                            <span class="chat-sent-btn">
                                                                <button style="background:none; padding:0; border:none;" type="submit">
                                                                    <img src="/assets/images/chat-sent-icon.png" class="img-responsive" alt="chat-sent-icon">
                                                                </button>
                                                            </span>
                                                            <!-- <input type="hidden" name="_id" value="{{@request()->route('id')}}"/> -->
                                                            <input type="hidden" name="_id" value="{{ $users[0]->id }}"/>
                                                        </form>                                                        
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
    <!-- Body Content End -->
    <!-- Footer Start -->
    <!-- <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <h2>MillionMitzvots</h2>
                    <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <div class="bottom-block">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="block">
                                    <h3>Address</h3>
                                    <p>65 Leonard Street, London EC2A </p>
                                </div>
                                <div class="block">
                                    <h3>Email</h3>
                                    <a href="mailto:millionmitzvot@gmail.com">millionmitzvot@gmail.com</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="block">
                                    <h3>Phone</h3>
                                    <p>123-456-7890</p>
                                </div>
                                <div class="block social">
                                    <h3>Social</h3>
                                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 hidden-sm hidden-xs"><div class="row text-center"><div class="divider">&nbsp;</div></div></div>
                <div class="col-sm-6">
                    <h2>Navigations</h2>
                    <ul>
                        <li><a href="#"><i class="fa fa-circle" aria-hidden="true"></i> Home</a></li>
                        <li><a href="#"><i class="fa fa-circle" aria-hidden="true"></i> About Us</a></li>
                        <li><a href="#"><i class="fa fa-circle" aria-hidden="true"></i> Score Board</a></li>
                        <li><a href="#"><i class="fa fa-circle" aria-hidden="true"></i> Purchase</a></li>
                        <li><a href="#"><i class="fa fa-circle" aria-hidden="true"></i> Contacts</a></li>
                    </ul>
                    <a href="#" class="community-btn">join the community</a>
                </div>
            </div>
        </div>
        <div class="bottom-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p>© 2017 All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer> -->
    <!-- Footer End -->
  </div>
  <!-- <div class="clear50"></div> -->
</body>

<script>
          var __baseUrl = "{{url('/')}}"
      </script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js'></script>



        <script src="{{asset('chat/js/talk.js')}}"></script>

    <script>
        var show = function(data) {
            alert(data.sender.name + " - '" + data.message + "'");
        }

        var msgshow = function(data) {
            // var html = '<li id="message-' + data.id + '">' +
            // '<div class="message-data">' +
            // '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>' + data.sender.name + '</span>' +
            // '<span class="message-data-time">1 Second ago</span>' +
            // '</div>' +
            // '<div class="message my-message">' + data.message +
            // '</div>' +
            // '</li>';

            var html = 
            '<li id="message-' + data.id + '">' +
                '<div class="col-md-2">' +
                    '<div class="image">' +
                        '<img src="' + {{ auth()->user()->image_url }} + '"' + 'class="img-responsive" alt="user-image">' +
                    '</div>' +
                '</div>' +
                '<div class="col-md-10 noleftpadding">' + 
                    '<div class="chat-text">' + 
                        '<div class="name">' +
                            data.sender.name +
                            '<span class="time">1 Second ago</span>' +
                        '</div>' +
                        '<div class="text">' +
                            '<p>' + data.message + '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</li>';

            $('#talkMessages').append(html);
            setTimeout(function(){
                $('.chat-history').scrollTop($('.chat-history')[0].scrollHeight);
            }, 100);
        }
        
        function checkEnter(event){
            if( (event.keyCode == 10 || event.keyCode == 13) && !event.shiftKey ){
                $('form#talkSendMessage').submit();
                event.preventDefault();
            }
        }

        //
        {{ $id = $users[0]->id }}
        function readMessages(){
            $.ajax({
                method: "POST",
                url: "/message/{{$id}}",
                data: {}
            })
            .done(function( msg ) {
                console.log("Done");
                setTimeout(readMessages, 5000);
            });
        }
        document.addEventListener( 'DOMContentLoaded', function () {
            setTimeout(function(){
                $('.chat-history').scrollTop($('.chat-history')[0].scrollHeight);
            }, 100);
            readMessages();
        }, false );
    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}

@endsection
