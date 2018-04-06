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
</head>
<body>
    <div id="app">
        <div class="container">
            <div class="row">
                <h3 class="text-center">MillionMitzvot support</h3>
                <h5 class="text-center margin-top-20" style="padding: 10px;">Thank you for contacting us. We will response in a few minutes. Please ask anything.</h5>
                <h5 class="text-center"></h5>
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
                                            <textarea class="form-control" name="message" type="text" placeholder="Type your initial message here" style="height: 80px; resize: none;" onkeydown = "checkEnter(event);"></textarea>
                                        </div>
                                        <div class="col-xs-4" style="width: 70px;padding-left: 0px;">
                                            <button class="btn btn-sm btn-primary" type="submit" style="width: 100%; height: 80px;">Send</button>
                                        </div>
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
