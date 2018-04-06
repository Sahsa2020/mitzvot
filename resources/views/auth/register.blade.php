@extends('layouts.app')

@section('content')
<style>
    .login-form .form-control.community-btn{
      display: inline-block;
      color: #fff !important;
      background: url(assets/global/img/send-msg-btn.png) no-repeat;
      text-decoration: none;
      text-transform: uppercase;
      padding: 14px 30px;
      border-radius: 3px;
      height: auto;
      background-size: 100% 100%;
      font-weight: bold;
      letter-spacing: 1px;
    }
    .login-form .form-control.community-btn:hover{
      background: url(assets/img/send-msg-btn-hover.png) no-repeat !important  ;
      background-size: 100% 100% !important;
      text-decoration: none;
      color: #fff;
    }
    .login-form .form-control.community-btn:focus{
      text-decoration: none;
    }
    #bg {
      position: fixed; 
      top: 0; 
      left: 0; 
      min-width: 100%;
      min-height: 100%;
    }
    .logo{
      padding: 20px;
    }
    section{
      padding: 20px 0;
    }
    .login-text{
      padding: 200px 0 0;
    }
    .login-text h1{
      color: #fff;
      font-size: 30px;
    }
    .login-text p{
      color: #fff;
      line-height: 25px;
    }
    .step-bar ul{
      list-style-type: none;
      padding: 0;
      margin:0 0 60px;
      text-align: center;
    }
    .step-bar ul li{
      display: inline-block;
      background: #fff;
      color: #333;
      font-size: 18px;
      height: 40px;
      width: 40px;
      text-align: center;
      vertical-align: top;
      line-height: 40px;
      border-radius: 5px !important;
      cursor: pointer;
      margin-right: 140px;
      position: relative;
    }
    .step-bar ul li.current{
      background: #467FFD;
      color: #fff;
    }
    .step-bar ul li::before{
      content: '';
      border-top: 4px solid #D1BFF6;
      width: 145px;
      display: inline-block;
      position: absolute;
      right: 0;
      top: 20px;
      height: 5px;
      left: 40px;
    }
    .step-bar ul li:last-child::before{
      display: none;
    }
    .step-bar ul li:last-child{
      margin: 0;
    }
    .form2, .form3{
      display: none;
    }
    .login-form{
      background: #fff;
      padding: 20px 0;
      margin: 0;
      display: inline-block;
      width: 100%;
    }
    .login-form h2{
      text-align: center;
      text-transform: capitalize;
      margin: 10px 0 20px;
      color: #2A2937;
      font-weight: bold;
      font-size: 25px;
    }
    .login-form .form-group-border{
      border: 1px solid #ccc;
      border-radius: 3px;
      padding: 5px 1px;
      position: relative;
    }
    .form-group {
        margin: 15px 20px;
    }
    .login-form label{
      margin-bottom: 0;
      color: #6d6d6d;
      font-weight: normal;
      padding: 0 10px;
      font-size: 14px;
    }
    .login-form .form3 .col-md-6:first-child .form-group-border {
        margin: 0 0 0 20px;
    }
    .login-form .form3 .col-md-6:last-child .form-group-border {
        margin: 0 20px 0 0;
    }
    .login-form .form3 .form-group-border input{
      height: 50px;
    }
    .calendar-icon {
      position: absolute;
      right: 5px;
      top: 30px;
      cursor: pointer;
      z-index: 9;
    }
    .login-form a{
      display: inline-block;
      color: #999;
      text-decoration: none;
    }
    .login-form .form-group p{
      color: #2A2937;
      font-size: 18px;
      font-weight: bold;
      margin: 0;
    }
    .login-form .form-control{
      border: none;
      box-shadow: none;
      padding: 0 10px;
      font-size: 16px;
      height: 30px;
      color: #2A2937;
    }
    .checkbox label:after{
      content: '';
      display: table;
      clear: both;
    }
    .checkbox .cr {
      position: relative;
      display: inline-block;
      border: 1px solid #6d6d6d;
      border-radius: 2px;
      width: 20px;
      height: 20px;
      float: left;
      margin-right: 15px;
    }
    .checkbox .cr .cr-icon{
      position: absolute;
      font-size: 14px;
      line-height: 18px;
      top: -1px;
      left: -1px;
      background: #fb6180;
      border: 1px solid #fb6180;
      height: 20px;
      width: 20px;
      border-radius: 3px;
      text-align: center;
    }
    .checkbox label input[type="checkbox"]{
      display: none;
    }
    .checkbox label input[type="checkbox"] + .cr > .cr-icon{
      transform: scale(3) rotateZ(-20deg);
      opacity: 0;
      transition: all .3s ease-in;
    }
    .checkbox label input[type="checkbox"]:checked + .cr > .cr-icon {
      transform: scale(1) rotateZ(0deg);
      opacity: 1;
      color: #fff;
    }
    .checkbox label input[type="checkbox"]:disabled + .cr {
      opacity: .5;
    }
    .checkbox{
      margin: 0;
    }
    .login-form .form3 label{
      padding: 0;
    }
    .security-info{
      border-bottom: 1px solid #eee;
      padding: 25px 20px;
      margin-bottom: 24px;
    }
    .security-info img{
      display: inline-block;
      vertical-align: top;
      margin-right: 12px;
    }
    .login-form .security-info label span{
      color: #467FFD;
    }
    .termsncond.form-group{
      margin-bottom: 30px;
    }
    .checkbox label a.terms{
      color: #fb6180;
    }
    .login-form span a{
      color: #467FFD;
    }
    .close-btn{
      color: #fff;
      font-size: 20px;
      padding: 10px;
      display: inline-block;
    }
    .close-btn:hover{
      text-decoration: none;
      color: #e380a7;
    }
    .noleftpadding{
      padding-left: 0 !important;
    }
    .norightpadding{
      padding-right: 0 !important;
    }
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
      margin-top: 10px;
    }
    .switch input {display:none;}
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }
    input:checked + .slider {
      background-color: #fb6180;
    }
    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }
    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }
    .slider.round:before {
      border-radius: 50%;
    }
    .file-upload{
      border: 1px solid #eee;
      border-left: 0;
      border-right: 0;
      margin-bottom: 20px;
      padding: 10px 0;
    }
    .logo-upload{
      background: #DDE1EC;
      border: 1px dashed #ADB3C8;
      color: #7E818A;
      font-weight: bold;
      font-size: 15px;
      text-align: center;
      overflow: hidden;
      position: relative;
      padding: 15px 0;
    }
    .logo-upload input{
      position: absolute;
      width: 100%;
      opacity: 0; 
      cursor: pointer;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
    }
    .close-upload{
      position: absolute;
      top: -5px;
      right: 5px;
      z-index: 9;
      cursor: pointer;
    }

    .payment-via{padding: 10px 0;}
    .payment-via img{max-width: 100%; border:3px solid #ededed; border-radius: 3px;}
    .payment-via img:hover{border:3px solid #467ffd; cursor: pointer;}
    .pay-method-block{border-bottom: 1px solid #eee;}


    .form h3 {
    font-size: 16px;
    font-weight: bold;
    margin: 24px 0;
    color: #2a2937;
    }
    .pay-method {
    border: 1px solid #eee;
    padding: 10px;
    border-radius: 3px;
    cursor: pointer;
    margin-bottom: 20px;
    text-align: center;
    }
    select.form-control {
    background: #fff url('images/select-icon.png') no-repeat 98% 5px;
    -webkit-appearance: none;
    -moz-appearance: none;
    }

    @media screen and ( min-width: 992px ) and ( max-width: 2500px ){
        .login-form h2 {
        margin: 10px 0;
        font-size: 25px;
        }
        .login-form .form-control {
        font-size: 14px;
        height: 26px;
        }
        .step-bar ul li {
        margin-right: 241px;
        position: relative;
        }
        .step-bar ul li::before {
        width: 244px;
        }
    }
    @media screen and ( min-width: 768px ) and ( max-width: 991px ){
        section{
            padding: 0;
        }
        .login-form {
            margin: 0 20px;
        }
        .login-form label {
            font-size: 12px;
        }
        .login-form a{
            font-size: 12px;
        }
    }
    @media screen and ( max-width: 991px ){
        section {
            padding: 50px 0;
        }
        .login-form {
            margin: 0;
        }
        .signup-body{
        position: relative;
        }
        .login-text {
        padding: 0;
        }
        .login-form .form3 .col-md-6:first-child .form-group-border, .login-form .form3 .col-md-6:last-child .form-group-border {
        margin: 15px 20px;
        }
        .step-bar ul {
        margin: 30px 0;
        }
    }
    @media screen and ( max-width: 767px ){
        section {
            padding: 0 0 50px;
        }
        .login-text h1 {
        font-size: 20px;
        margin: 0 0 20px;
        }
        .login-text p {
        line-height: 20px;
        font-size: 12px;
        }
        .login-form h2 {
        margin: 0 0 10px;
        font-size: 20px;
        }
        .noleftpadding{
        padding-left: 0 !important;
        padding-right: 0 !important;
        }
        .norightpadding{
        padding-left: 0 !important;
        padding-right: 0 !important;
        }
        .login-form label {
        margin-bottom: 0;
        font-size: 10px;
        }
        .login-form .form-group p, .login-form .form-group strong {
        font-size: 12px;
        }
        .login-form input[type="submit"].form-control {
        font-size: 12px;
        height: auto;
        }
        .logo-upload {
        margin-bottom: 20px;
        }
    }
    @media screen and ( max-width: 481px ){
        .step-bar ul li {
        font-size: 14px;
        height: 30px;
        width: 30px;
        line-height: 30px;
        margin-right: 70px;
        }
        .step-bar ul li::before {
        width: 80px;
        right: 0;
        top: 15px;
        left: 30px;
        }
        .login-form .form-control {
        padding: 0 10px;
        font-size: 12px;
        height: 20px;
        }

        .payment-via img{width:100%; margin-bottom: 10px;}
    }
    .login-form .form-control2{
        border: none;
        box-shadow: none;
        padding: 0 10px;
        font-size: 16px;
        height: 44px;
        color: #2A2937;
    }
</style>

<img src="assets/images/sign-up-2.jpg" class="img-responsive" id="bg"  alt="Background">
  <div class="signup-body">
    <!-- Header Start -->
    <header>
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-sm-6">
    				<div class="logo">
    					<img src="assets/images/login-logo.png" class="img-responsive" alt="Logo">
    				</div>
    			</div>
          <div class="col-sm-6 text-right">
            <!-- <a href="#" class="close-btn">X</a> -->
          </div>
    		</div>
    	</div>
    </header>
    <!-- Header End -->
    <!-- Body Content Start -->
    <section>
      	<div class="container">
    	    <div class="row">
    	      	<div class="col-md-6">
                <div class="login-text">
                  <h1>Welcome to the Biggest <br> Charity Network in the World </h1>
                  <p>Lorem ipsum dolor sit amet, ea nam munere reprimique, consul irlorem ipsum dolor sit amet, ea nam munere reprimique, consul ir. Lorem ipsum dolor sit amet, ea nam munere reprimique, consul irlorem ipsum dolor sit amet, ea nam munere reprimique, consul ir.</p>
                </div>
    	      	</div>
    	      	<div class="col-md-6">
                <div id="step-li" class="step-bar" style="visibility:hidden">
                  <ul id="step-ul">
                    <li id = "li_1" class="current">1</li>
                    <li id="li_2" class="">2</li>
                  </ul>
                </div>
                <div class="login-form">
                  <div class="">                    
                    <form class="" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                      <div class="col-sm-12" id="form1">
                      <h2>Profile Information</h2>
                        <div class="row">
                          <div class="form-group form-group-border">
                            <label>Type</label>
                            <select id="type" class="form-control placeholder-no-fix" name="type" value="{{ old('type') }}" required="true" style="">
                            <option value='INDIVIDUAL'>Individual</option>
                            <option value='INSTITUTION'>Institution</option>
                            <option value='SCHOOL'>School</option>
                            </select>
                          </div>
                          <div class="form-group form-group-border {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                          </div>
                          <div class="form-group form-group-border {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label>Email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                          </div>
                          <div class="form-group form-group-border">
                            <label>Phone</label>
                            <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                          </div>
                          <div class="form-group form-group-border">
                            <label class="">Birthday</label>
                              <div class="">
                                  <div class="input-group input-medium date date-picker" data-date="2016-09-09" data-date-format="yyyy-mm-dd" data-date-viewmode="years" style="width: 100% !important;">
                                      <input type="text" class="form-control" readonly="" name="birthday" id="input-birthday" value="{{ old('birthday') }}" style="background-color:white">
                                      <span class="input-group-btn">
                                          <button class="btn default" type="button" style="margin-right: 0px;">
                                              <i class="fa fa-calendar"></i>
                                          </button>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group form-group-border {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required minlength="6">
                            @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                          </div>
                          <div class="form-group form-group-border ">
                            <label>Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required minlength="6">
                          </div>
                          <div class="form-group">
                            <!-- <input type="submit" class="form-control community-btn" value="Next Step"> -->
                            <input id="input_next" type="button" class="form-control community-btn" value="Next Step" onclick="nextStep()">
                            <input id="input_submit_1" type="submit" class="form-control community-btn" value="Create Account" >
                          </div>
                          <div class="form-group text-center">
                            <span>Already <a href={{ route('login') }}>have an account</a>?</span>
                          </div>
                        </div>
                      </div>

                      <div class="form2" id="from2">
                        <h2>Receive Donation</h2>
                        <!-- <form> -->
                          <div class="col-sm-12" id="receive_donation_info">
                            <div class="row">
                              <div class="form-group form-group-border">
                                <label>Receive donation</label>
                                <input id="bank_account" type="text" class="form-control donation-input" name="bank_account" value="{{ old('bank_account') }}" placeholder="Bank Account"  required>
                              </div>
                              <div class="form-group form-group-border">
                                <label>Routing Number</label>
                                <input id="routing_number" type="text" class="form-control donation-input" name="routing_number" placeholder="XXXX XXXX XXXX XXXX1"  value="{{ old('routing_number') }}" required>
                              </div>
                              <div class="form-group form-group-border">
                                <label>Account Number</label>
                                <input id="account_number" type="text" class="form-control donation-input" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number" required>
                              </div>
                              <div class="form-group form-group-border">
                                <label>Name o Bank Account</label>
                                <input id="bank_account" type="text" class="form-control donation-input" name="bank_account" value="{{ old('bank_account') }}" placeholder="Eugene Tishchenko" required >
                              </div>
                              <div class="form-group form-group-border">
                                <label>Bank Name</label>
                                <input id="bank_name" type="text" class="form-control donation-input" name="bank_name" value="{{ old('bank_name') }}" placeholder="Private Bank"  required>
                              </div>
                              <div class="form-group form-group-border">
                                <label>Account Type</label>
                                <input id="account_type" type="text" class="form-control donation-input" name="account_type" value="{{ old('account_type') }}" placeholder="Account Type1"  required>
                              </div>
                          </div>
                        </div>
                              <hr>                          
                        <!-- <form> -->
                          <div class="col-sm-12">
                            <div class="row">
                              <!-- <div class="form-group form-group-border">
                                <input type="text" class="form-control form-control2" value="XXXX XXXX XXXX XXXX1">
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group form-group-border">
                                    <input type="text" class="form-control form-control2" placeholder="MM/YY">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group form-group-border">
                                    <input type="text" class="form-control form-control2" placeholder="CVV">
                                  </div>
                                </div>
                              </div> -->
                              <div class="security-info">
                                <img src="assets/img/lock.png" class="img-responsive" alt="Lock Icon">
                                <label>All your private information <span>100% Secure</span></label>
                              </div>
                              <div class="form-group termsncond">
                                <div class="checkbox">
                                    <label>
                                      <input id="term_check" type="checkbox" checked="checked">
                                      <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span> I accept the <a href="#" class="terms">Terms and Conditions</a> of the website
                                    </label>
                                </div>
                              </div>
                              <div class="form-group termsncond">
                                <div class="checkbox">
                                    <label>
                                      <input id="donation_check" type="checkbox" checked="checked">
                                      <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span> I don't want to <a href="#" class="terms"><span>Receive Donation</span></a>
                                    </label>
                                </div>
                              </div>
                              <div class="form-group">
                                <input id="input_submit_2" type="submit" class="form-control community-btn" value="Create Account" disabled>
                              </div>
                              <div class="form-group text-center">                              
                                <span>Already <a href={{ route('login') }}>have an account</a>?</span>
                              </div>
                            </div>
                          </div>
                        <!-- </form> -->
                              <!-- <div class="form-group">
                                <input type="submit" class="form-control community-btn" value="Next Step">
                              </div>
                              <div class="form-group text-center">
                                <span>Already <a href="login.html">have an account</a>?</span>
                              </div> -->
                            </div>
                          </div>
                        </form>
                      </div>

                    </form>
                  </div>                  
                </div>
    	      	</div>
    	    </div>
      	</div>
    </section>
    <!-- Body Content End -->
    <!-- Footer Start -->
    <footer></footer>
    <!-- Footer End -->
  </div>
@endsection

@section('script')
<script>

$(document).ready(function () {
  $('#input_submit_1').show();
  $('#input_next').hide();
});

$('#type').change(function () {
  var value = $(this).val();
  if (value === 'INDIVIDUAL') {
    $('#step-li').css('visibility', 'hidden');
    $('#input_next').hide();
    $('#input_submit_1').show();
  }
  if (value !== 'INDIVIDUAL') {
    $('#step-li').css('visibility', 'visible');
    $('#input_next').show();
    $('#input_submit_1').hide();
  }

});

$('#step-ul li').on('click', function() {
  $('#step-ul li').each(function(index) {
    var className = $(this).attr('class');
    if (className !== 'current') {
      $(this).addClass('current');
    }
    if (className == 'current') {
      $(this).removeClass('current');
    }
  });

  var text = $(this).text();
  if (text == '1') {
    $('#form1').css('display', 'inline');
    $('.form2').css('display', 'none');
  }
  if (text == '2') {
    $('#form1').css('display', 'none');
      $('.form2').css('display', 'inline');
  }

});
  
$('#term_check').on('click', function() {
  if (!$(this).prop('checked')) {
    console.log('term_check');
    $('#input_submit_2').attr('disabled', true)
  }
  if ($(this).prop('checked')) {
    console.log('term_check_yes');
    $('#input_submit_2').prop('disabled', false);
  }
});

$('#donation_check').on('click', function() {
  if ($(this).prop('checked')) {
    console.log('donation_check_yes');
    $('.donation-input').attr('disabled', false);
  }
  if (!$(this).prop('checked')) {
    console.log('donation_check_no');
    $('.donation-input').attr('disabled', true);
  }
});

function nextStep() {
      // if ($('#name').val() == '' ) {
      //   alert('Please enter name');
      //   return
      // }
      // if ($('#email').val() == '' ) {
      //   alert('Please enter email');
      //   return
      // }
      // if ($('#phone').val() == '' ) {
      //   alert('Please enter phone number');
      //   return
      // }
      // if ($('#input-birthday').val() == '' ) {
      //   alert('Please enter birthday');
      //   return
      // }
      // if ($('#password').val() == '' ) {
      //   alert('Please enter password');
      //   return
      // }
      // if ($('#password_confirmation').val() == '' ) {
      //   alert('Please reenter password');
      //   return
      // }

      $('#form1').css('display', 'none');
      $('.form2').css('display', 'inline');
      $('#li_2').addClass('current');
      $('#li_1').removeClass('current');
    }

  
</script>
@endsection


