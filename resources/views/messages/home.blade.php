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
                                <img src="{{$user->image_url}}" class="img-responsive" alt="Image">
                                <h1>{{ $user->name }}</h1>
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
                                                        <a href="#" class="add-chats">+</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="chat-right">
                                                    <ul>
                                                        <li>
                                                            <div class="col-md-2">
                                                                <div class="image">
                                                                    <img src="/assets/img/user3.png" class="img-responsive" alt="user-image">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 noleftpadding">
                                                                <div class="chat-text">
                                                                    <div class="name">
                                                                        Margarita Brinker
                                                                        <span class="time">4 hours ago</span>
                                                                    </div>
                                                                    <div class="text">
                                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="col-md-2">
                                                                <div class="image">
                                                                    <img src="/assets/img/user1.png" class="img-responsive" alt="user-image">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 noleftpadding">
                                                                <div class="chat-text">
                                                                    <div class="name">
                                                                        Margarita Brinker
                                                                        <span class="time">4 hours ago</span>
                                                                    </div>
                                                                    <div class="text">
                                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                                                                        <p>Lorem Ipsum has been the industry's standard dummy text ever since.</p>
                                                                        <p>Lorem Ipsum is simply dummy text.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="col-md-2">
                                                                <div class="image">
                                                                    <img src="/assets/img/user3.png" class="img-responsive" alt="user-image">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 noleftpadding">
                                                                <div class="chat-text">
                                                                    <div class="name">
                                                                        Margarita Brinker
                                                                        <span class="time">4 hours ago</span>
                                                                    </div>
                                                                    <div class="text">
                                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since. Lorem Ipsum has been the industry's standard dummy text ever since.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="col-md-2">
                                                                <div class="image">
                                                                    <img src="/assets/img/user3.png" class="img-responsive" alt="user-image">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 noleftpadding">
                                                                <div class="chat-text">
                                                                    <div class="name">
                                                                        Margarita Brinker
                                                                        <span class="time">4 hours ago</span>
                                                                    </div>
                                                                    <div class="text">
                                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since. Lorem Ipsum has been the industry's standard dummy text ever since.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="chatsent">
                                                        <textarea class="form-control" placeholder="Write here..."></textarea>
                                                        <span class="attachment-icon">
                                                            <input type="file">
                                                            <img src="/assets/img/attachment-icon.png" class="img-responsive" alt="chat-sent-icon">
                                                        </span>
                                                        <span class="chat-sent-btn">
                                                            <img src="/assets/images/chat-sent-icon.png" class="img-responsive" alt="chat-sent-icon">
                                                        </span>
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
  <div class="clear50"></div>
</body>
@endsection
