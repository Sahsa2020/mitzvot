<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="{{asset('chat/css/reset.css')}}">
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{asset('chat/css/style.css')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="assets/global/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/bootstrap/css/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/bootstrap/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/global/bootstrap/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" /> -->
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
    <link href="assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <link href="assets/one-page/css/settings.css" rel="stylesheet">
    <link href="assets/one-page/css/style.css" rel="stylesheet" type="text/css" />
    <link href="assets/one-page/css/red.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="assets/global/css/layout.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="assets/global/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" /> -->
    <link href="assets/global/css/login.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
  </head>

  <body>
    <div class="header">
        <div class="container header-brand">
            <h3 class="text-center">MillionMitzvot support</h3>
        </div>
    </div>
    <div class="clearfix body">
        @include('partials.peoplelist')
        
        <div class="chat">
            <div class="chat-header clearfix">
                @if(isset($user))
                    <img src="{{@$user->image_url}}" onerror="this.src='assets/global/img/default_avatar.jpg'" class="avatar img-responsive" />
                @endif
                <div class="chat-about">
                    @if(isset($user))
                        <div class="chat-with">{{'To ' . @$user->name}}</div>
                    @else
                        <div class="chat-with">No Thread Selected</div>
                    @endif
                </div>
                <i class="fa fa-star"></i>
            </div> <!-- end chat-header -->
                
            @yield('content')
            
            <div class="footer" style="background: white; width: 100%; padding: 10px; top: auto; bottom: 0px;">
                <form action="" method="post" id="talkSendMessage">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <fieldset>
                                <div class="form-group margin-top-0 margin-bottom-0">
                                    <div class="col-xs-8" style="width: calc(100% - 95px); padding-right: 0px;">
                                        <textarea class="form-control" name="message-data" id="message-data" placeholder ="Type your message here" style="height: 80px; resize: none;" onkeydown = "checkEnter(event);"></textarea>
                                    </div>
                                    <div class="col-xs-4" style="width: 80px;padding: 0px; margin-left: 10px;">
                                        <button class="btn btn-sm btn-primary" type="submit" style="width: 100%; height: 80px;">Send</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <input type="hidden" name="_id" value="{{@request()->route('id')}}"/>
                </form>
            </div>
        
        </div> <!-- end chat -->
        
    </div> <!-- end container -->


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
            var html = '<li id="message-' + data.id + '">' +
            '<div class="message-data">' +
            '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>' + data.sender.name + '</span>' +
            '<span class="message-data-time">1 Second ago</span>' +
            '</div>' +
            '<div class="message my-message">' + data.message +
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
    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}

  </body>
</html>
