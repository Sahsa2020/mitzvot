<div class="people-list" id="people-list">
    <ul class="list">
        @foreach($threads as $inbox)
            @if(!is_null($inbox->thread))
        <li class="clearfix">
            <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
            <img src="{{$inbox->withUser->image_url}}" onerror="this.src='assets/global/img/default_avatar.jpg'" class="avatar img-responsive"/>
            <div class="about">
                <div class="name">{{$inbox->withUser->name}}</div>
                <div class="status">
                    @if(auth()->user()->id == $inbox->thread->sender->id)
                        <span class="fa fa-reply"></span>
                    @endif
                    <span>{{substr($inbox->thread->message, 0, 20)}}</span>
                </div>
            </div>
            </a>
        </li>
            @endif
        @endforeach

    </ul>
</div>
