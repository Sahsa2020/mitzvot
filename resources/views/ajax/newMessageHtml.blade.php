<li class="clearfix" id="message-{{$message->id}}">
    <div class="message-sender-data align-right">
        <span class="message-data-time" >{{$message->humans_time}} ago</span> &nbsp; &nbsp;
        <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>
    </div>
    <div class="message other-message float-right">{{$message->message}}</div>
</li>