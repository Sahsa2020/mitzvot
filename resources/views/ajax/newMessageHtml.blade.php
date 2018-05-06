<!-- <li class="clearfix" id="message-{{$message->id}}">
    <div class="message-sender-data align-right">
        <span class="message-data-time" >{{$message->humans_time}} ago</span> &nbsp; &nbsp;
        <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>
    </div>
    <div class="message other-message float-right">{{$message->message}}</div>
</li> -->


<li id="message-{{$message->id}}">
    <div class="col-md-2">
        <div class="image">
            <img src="" class="img-responsive" alt="user-image" onerror="this.src='assets/global/img/default_avatar.jpg'">
        </div>
    </div>
    <div class="col-md-10 noleftpadding">
        <div class="chat-text">
            <div class="name">
                <span class="time">{{$message->humans_time}} ago</span>
            </div>
            <div class="text">
                <p>{{ $message->message }}</p>
            </div>
        </div>
    </div>
</li>