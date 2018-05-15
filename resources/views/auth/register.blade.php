@extends('layouts.app')

@section('content')
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
                    <div id="step_page" class="step-bar" style="visibility: hidden">
                      <ul id="step-ul">
                        <li id = "li_1" class="current">1</li>
                        <li id="li_2" class="">2</li>
                      </ul>
                    </div>

                    <div class="login-form">
                      <div class="">
                        <form class="" role="form" method="POST" action="{{ route('register') }}">
                              {{ csrf_field() }}
                              <div class="col-sm-12" id="profile_information">
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
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                                  </div>
                                  <div class="form-group form-group-border">
                                    <label>Address</label>
                                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                                  </div>
                                  <div class="form-group form-group-border">
                                    <label>City</label>
                                    <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                                  </div>
                                  <div class="form-group form-group-border">
                                    <label>Country</label>
                                    <input id="country" type="text" class="form-control" name="country" value="{{ old('country') }}" required>
                                  </div>
                                  <div class="form-group form-group-border">
                                    <label class="">Birthday</label>
                                    <input type="text" class="form-control date-picker" data-date="2016-09-09" data-date-format="yyyy-mm-dd" data-date-viewmode="years" readonly="" name="birthday" id="input-birthday" value="{{ old('birthday') }}" style="background-color:white">
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
                                  
                                </div>
                              </div>

                              <div id="next_step">
                                <div class="form-group">
                                  <input id="" type="button" class="form-control community-btn" value="Next Step" onclick="nextStep()">
                                </div>
                                <div class="form-group text-center">
                                  <span>Already <a href={{ route('login') }}>have an account</a>?</span>
                                </div>
                              </div>

                              <div id="receive_donation">
                                <h2>Receive Donation</h2>
                                <div class="col-sm-12">
                                  <div class="row">
                                    <div class="form-group form-group-border">
                                      <label>Receive donation</label>
                                      <input id="bank_account" type="text" class="form-control receive-donation" name="bank_account" value="{{ old('bank_account') }}" placeholder="Bank Account" required>
                                    </div>
                                    <div class="form-group form-group-border">
                                      <label>Routing Number</label>
                                      <input id="routing_number" type="text" class="form-control receive-donation" name="routing_number" placeholder="XXXX XXXX XXXX XXXX1"  value="{{ old('routing_number') }}" required>
                                    </div>
                                    <div class="form-group form-group-border">
                                      <label>Account Number</label>
                                      <input id="account_number" type="text" class="form-control receive-donation" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number" required>
                                    </div>
                                    <div class="form-group form-group-border">
                                      <label>Name of Bank Account</label>
                                      <input id="name_of_bank_account" type="text" class="form-control receive-donation" name="name_of_bank_account" value="{{ old('bank_account') }}" placeholder="Eugene Tishchenko" required >
                                    </div>
                                    <div class="form-group form-group-border">
                                      <label>Bank Name</label>
                                      <input id="bank_name" type="text" class="form-control receive-donation" name="bank_name" value="{{ old('bank_name') }}" placeholder="Private Bank"  required>
                                    </div>
                                    <div class="form-group form-group-border">
                                      <label>Account Type</label>
                                      <input id="account_type" type="text" class="form-control receive-donation" name="account_type" value="{{ old('account_type') }}" placeholder="Account Type1"  required>
                                    </div>
                                  </div>
                                </div>

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
                              </div>                              

                              <div id="create_account">
                                <div class="form-group">
                                  <input id="input_submit_2" type="submit" class="form-control community-btn" value="Create Account">
                                </div>
                                <div class="form-group text-center">
                                  <span>Already <a href={{ route('login') }}>have an account</a>?</span>
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
  $('#profile_information').show();
  $('#next_step').hide();
  $('#receive_donation').hide();
  $('#create_account').show();

  $('.receive-donation').each(function(index) {
      $(this).prop('required', false);
      });
});

$('#type').change(function () {
  var value = $(this).val();
  if (value === 'INDIVIDUAL') {
    $('#step_page').css('visibility', 'hidden');    
    $('#create_account').show();
    $('#profile_information').show();
    $('#next_step').hide();
    $('#receive_donation').hide();

    $('.receive-donation').each(function(index) {
      $(this).prop('required', false);
      });
  }
  if (value !== 'INDIVIDUAL') {
    console.log('not individual');
    $('#step_page').css('visibility', 'visible');
    $('#create_account').hide();
    $('#profile_information').show();
    $('#next_step').show();
    $('#receive_donation').hide();

    $('.receive-donation').each(function(index) {
      $(this).prop('required', true);
      });
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
    $('#create_account').hide();
    $('#profile_information').show();
    $('#next_step').show();
    $('#receive_donation').hide();
  }
  if (text == '2') {
    $('#create_account').show();
    $('#profile_information').hide();
    $('#next_step').hide();
    $('#receive_donation').show();
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
    $('.receive-donation').attr('disabled', false);
  }
  if (!$(this).prop('checked')) {
    console.log('donation_check_no');
    $('.receive-donation').attr('disabled', true);
  }
});

function nextStep() {
    $('#li_2').addClass('current');
    $('#li_1').removeClass('current');
    $('#create_account').show();
    $('#profile_information').hide();
    $('#next_step').hide();
    $('#receive_donation').show();
}

$('#input_submit_2').on('click', function() {
    // $('.receive-donation').each(function(index) {
    //   $(this).prop('required', false);
    //   });

});

$('#menu-btn').click(function() {    
    $('#mobile-side-menu').show();
});

$('#back-btn').click(function() {
    $('#mobile-side-menu').hide();
});

var is_clicked_account = false;
$('a.dropdown-toggle').click( function(){
    is_clicked_account = !is_clicked_account;
    if (is_clicked_account) {
        $('li.admin-top-menu').addClass('open');
    } else {
        $('li.admin-top-menu').removeClass('open');
    }
});
</script>
@endsection

@section('additional_styles')
<link href="/css/register-style.css" rel="stylesheet">
@endsection