<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style>
        .banner-txt{
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            text-align: center;
        }
        .banner img{
            width: 100%;
            max-height: 350px;  
        }
        .banner-txt img{
            margin: auto;
            width: auto;
            cursor: pointer;
        }
        .banner-txt h1{
            color: #fff;
            font-weight: bold;
            font-size: 45px;
        }
        .banner-txt p{
            color: #fff;
            font-size: 20px;
        }
        .community-btn{
            display: inline-block;
            color: #fff;
            background: #477AEA;
            text-decoration: none;
            text-transform: uppercase;
            padding: 14px 20px;
            border-radius: 3px;
            margin: 20px 0;
            font-size: 13px;
            border: 1px solid #477AEA;
        }
        .community-btn:hover{
            text-decoration: none;
            color: #fff;
        }
        .row-eq-height {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display:         flex;
        }
        .clear{
            display: inline-block;
            width: 100%;
            clear: both;
            height: 100px;
        }
        .clear20{
            display: inline-block;
            width: 100%;
            clear: both;
            height: 20px;
        }

        .faq{
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .faq h2{
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .faq h2 span{
            color: #FB6180;
            font-weight: normal;
            font-size: 24px;
            display: inline-block;
            margin-left: 10px;
            cursor: pointer;
        }
        .faq h2 span.minus{
            display: none;
        }
        .faq .answer{
            display: none;
        }
        .faq.active .answer{
            display: block;
        }
        .faq.active span.plus{
            display: none;
        }
        .faq.active h2 span.minus{
            display: inline-block;
        }
        .faq.active h2{  
            color: #FB6180;
        } 

        /* CHAT Start */
        .chat-window{
            bottom: 100px;
            position: fixed;
            right: 0;
            z-index: 9;
        }
        .chat-window > div > .panel{
            border-radius: 5px 5px 0 0;
        }
        .icon_minim{
            padding:2px 10px;
        }
        .msg_container_base{
            background: #F7F8FA;
            margin: 0;
            padding: 0 10px 10px;
            max-height: 350px;
            overflow-x: hidden;
            height: 350px;
        }
        .top-bar {
            background: #467FFD;
            color: #fff;
            padding: 15px 0;
            position: relative;
            overflow: hidden;
        }
        .msg_receive{
            background: #eef5f9;
            color: #333;
        }
        .msg_sent{
            margin-right:0;
            background: #467FFD;
            color: #fff;
        }
        .messages {
            padding: 14px;
            border-radius: 2px;
            max-width: 100%;
            margin: 0 10px;
            width: auto;
        }
        .messages > p {
            margin: 0;
        }
        .messages > time {
            font-size: 11px;
            color: #ccc;
        }
        .msg_container {
            padding: 10px;
            overflow: hidden;
            display: flex;
        }
        .base_sent {
          justify-content: flex-end;
          align-items: flex-end;
        }
        .msg_sent > time{
            float: right;
        }
        .msg_container_base::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        .msg_container_base::-webkit-scrollbar
        {
            width: 12px;
            background-color: #F5F5F5;
        }

        .msg_container_base::-webkit-scrollbar-thumb
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }

        .btn-group.dropup{
            position:fixed;
            left:0px;
            bottom:0;
        }
        .detail{
            font-weight: bold;
        }
        .detail p{
            margin: 0;
            font-weight: normal;
            font-size: 12px;
        }
        .user-icon img{
            height: 40px;
            width: 40px;
            border-radius: 50%;
        }
        .panel-footer{
            background: #fff;
            padding: 0;
            border: none;
        }
        .panel-footer .form-control{
            height: 60px;
            border: none;
            box-shadow: none;
            font-size: 14px;
            color: #333;
        }
        /* .panel-footer .input-group-btn button{
            background: url('/assets/img/chat-sent-icon.png') no-repeat center;
            border: none;
            outline: none;
            height: 50px;
            width: 50px;
            font-size: 0;
        } */
        .input-group-btn button{
            background: url('/assets/images/chat-sent-icon.png') no-repeat center;
            border: none;
            outline: none;
            height: 50px;
            width: 50px;
            font-size: 0;
        }
        .chat-close a{
            display: block;
            text-align: right;
        }
        .chat-close a img{
            display: inline-block;
            text-align: right;
        }
        /* CHAT End */
        
        @media screen and (min-width: 992px) and (max-width: 1199px){
            .navbar-nav{
                float: left;
            }
            .navbar ul li a {
                padding: 12px 10px;
                font-size: 12px;
            }
            .dropdown {
                width: 210px;
                top: 70px;
            }
            .community-btn {
                padding: 14px 15px;
            }
        }
        @media screen and (min-width: 768px) and (max-width: 991px){
            .navbar ul li:last-child{
                float: right;
                vertical-align: top;
            }
            .navbar ul li a {
                padding: 5px 8px;
                font-size: 10px;
            }
            .navbar ul li:last-child a{
                padding: 0;
            }
            .navbar .dropdown ul li a{
                padding: 5px 10px;
                font-size: 12px;
            }
            .navbar .dropdown ul li:last-child{
                float: none;
            }
            .dropdown .drop-head {
                padding: 10px;
                margin-bottom: 0;
            }
            .dropdown {
                width: 220px;
                top: 60px;
            }
            .banner-txt h1 {
                font-size: 25px;
            }
        }
        @media screen and ( max-width: 991px ){
            .row-eq-height{
                display: block;
            }
        }
        @media screen and ( max-width: 767px ){
            .logo img{
                margin: auto;
            }
            .navbar ul {
                text-align: center;
                width: 100%;
            }
            .navbar ul li a {
                padding: 5px;
                font-size: 12px;
            }
            .dropdown {
                left: 0;
                width: 100%;
                top: 65px;
            }
            .community-btn {
                padding: 5px 20px;
                margin: 0;
                font-size: 10px;
            }
            .chat-window{position: absolute; top: 15px;}
        }
        @media screen and ( max-width: 490px ){

            .banner-txt h1{font-size: 18px;}
        }

        body {
            /* width: 100px;
            height: 100px; */
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="container">
            <div class="row">
                <!-- <h3 class="text-center">MillionMitzvot support</h3>
                <h5 class="text-center margin-top-20" style="padding: 10px;">Thank you for contacting us. We will response in a few minutes. Please ask anything.</h5>
                <h5 class="text-center"></h5> -->
                <div class="top-bar">
                    <div class="col-md-3 col-xs-3">
                        <div class="user-icon">
                            <img src="$admin[0]->image_url"  class="img-responsive" onerror="this.src='assets/global/img/default_avatar.jpg'" alt="user-img">
                        </div>
                    </div>
                    <div class="col-md-9 col-xs-9">
                        <div class="row">
                            <div class="detail">
                                Ann stockward
                                <p>Support Assistance</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer" style="background: white; position: fixed; width: 100%; padding: 10px; top: auto; bottom: 0px;">
                    {!! Form::open([
                        'method'=>'POST',
                        'url' => ['/customer_support'],
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <div class="form-group margin-top-0 margin-bottom-0">
                                        <div class="col-xs-8" style="width: calc(100% - 75px); padding-right: 0px;">
                                            <textarea class="form-control" name="message" type="text" placeholder="Type your initial message here" style="height: 80px; resize: none;" onkeydown = "checkEnter(event);" style="height:100px;"></textarea>
                                        </div>
                                        <!-- <div class="col-xs-4" style="width: 70px;padding-left: 0px;">
                                            <button class="btn btn-sm btn-primary" type="submit" style="width: 100%; height: 80px;">Send</button>
                                        </div> -->
                                        <span class="input-group-btn">
                                        <button class="" type="submit">Send</button>
                                        </span>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @if ($ticket != null)
            <div class="modal fade in" role="basic" style="display:block;z-index:9999999999999;">
                <div class="modal-backdrop fade in" style="height: 100%;"></div>
                <div class="modal-dialog" style="text-align: center; top: calc(50% - 60px);">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p style="padding: 0px 23px; margin-bottom: 0px;" id = "ticket-connecting-message">
                                Tryping to connect you to an agent......
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="assets/global/js/jquery.min.js"></script>
    <script src="assets/global/js/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="assets/global/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/global/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="assets/global/js/owl.carousel.min.js" type="text/javascript"></script>
    <script src="assets/global/js/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="/js/app.js"></script>
    <script>
        var ticketId = 0;
        function isTicketClosed(){
            var url = "/isTicketClosed/" + ticketId;
            $.ajax({
                url: url,
                context: document.body
            }).done(function(res) {
                if(res.success){
                    window.location.replace('/message/' + res.accepter);
                }
                else
                {
                    setTimeout(function(){
                        isTicketClosed();
                    }, 3000);
                }
            });
        }
        @if ($ticket != null)
            ticketId = <?php echo $ticket->id; ?>;
            isTicketClosed();
            setTimeout(function(){
                $('#ticket-connecting-message').html('We are sorry that no one is available at this time. We will send you message in 24 hours.');
            }, 90000);
        @endif
        function checkEnter(event){
            if( (event.keyCode == 10 || event.keyCode == 13) && !event.shiftKey ){
                $('#app form').submit();
                event.preventDefault();
            }
        }
    </script>
</body>
</html>
