@extends('layouts.chat')

@section('content')
    <div class="chat-history">
        <ul id="talkMessages">

            @foreach($messages as $message)
                @if($message->sender->id == auth()->user()->id)
                    <li class="clearfix" id="message-{{$message->id}}">
                        <div class="message-sender-data align-right">
                            <span class="message-data-time" >{{$message->humans_time}} ago</span> &nbsp; &nbsp;
                            <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>
                        </div>
                        <div class="message other-message float-right">{{$message->message}}</div>
                    </li>
                @else

                    <li id="message-{{$message->id}}">
                        <div class="message-data">
                            <span class="message-user-image">
                                <img src="{{@$user->image_url}}" onerror="this.src='assets/global/img/default_avatar.jpg'" class="avatar img-responsive" />
                            </span>
                            <span class="message-data-time">{{$message->humans_time}} ago</span>
                        </div>
                        <div class="message my-message">{{$message->message}}</div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div> <!-- end chat-history -->
<script>
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
@endsection