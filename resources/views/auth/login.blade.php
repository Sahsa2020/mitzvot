@extends('layouts.app')

@section('content')

<img src="assets/images/login-bg.png" class="img-responsive" id="bg" alt="Background">
  <div class="login-body">
    <!-- Header Start -->
    <header>
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-sm-6">
    				<div class="logo">
    					<img src="assets/images/login-logo.png" class="img-responsive" alt="Logo">
    				</div>
    			</div>
          <!-- <div class="col-sm-6 text-right">
              <a href="#" class="close-btn">X</a>
          </div> -->
    		</div>
    	</div>
    </header>
    <!-- Header End -->
    <!-- Body Content Start -->
    <section>
      	<div class="container">
    	    <div class="row">
    	      	<div class="col-sm-6">
                <div class="login-text">
                  <h1>Welcome to the Biggest <br> Charity Network in the World </h1>
                  <p>Lorem ipsum dolor sit amet, ea nam munere reprimique, consul irlorem ipsum dolor sit amet, ea nam munere reprimique, consul ir. Lorem ipsum dolor sit amet, ea nam munere reprimique, consul irlorem ipsum dolor sit amet, ea nam munere reprimique, consul ir.</p>
                </div>
    	      	</div>
    	      	<div class="col-sm-6">
                <div class="login-form">
                    <h2>log in</h2>
                    <form class="" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="from-1 form-group-border form-group {{ $errors->has('email') ? ' has-error' : '' }}" >
                          <label for="email">Email</label>
                          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                          @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                          @endif
                        </div>
                        <div class="form-group form-group-border {{ $errors->has('password') ? ' has-error' : '' }}">
                          <label for="password">Password</label>
                          <input id="password" type="password" class="form-control" name="password" required>
                          @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                          @endif
                        </div>
                        <div class="remember">
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="checkbox ">
                                <label>
                                  <input type="checkbox" value="" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                  <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span> Remember Me
                                </label>
                              </div>
                            </div>
                            <div class="col-sm-6 text-right">
                              <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                          </div>
                        </div>
                        <div class="form-group ">
                          <input type="submit" class="form-control form-group-border" value="log in">
                        </div>
                        <div class="form-group text-center">
                          <span>Don't <a href= {{ route('register') }}>register account</a>?</span>
                        </div>
                    </form>
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
