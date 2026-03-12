@extends('layouts.auth')

@section('content')
<div class="row w-100">
  <div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
      <div class="" align="center">
        <img src="{{ asset('dist/images/logo.png') }}" width="50%">
      </div>
      <br />
      <br />

      
      <h4>Hello! let's get started</h4>
      <h6 class="font-weight-light">Sign in to continue.</h6>
      <form class="pt-3" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input id="email" type="email" class="form-control form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control form-control-lg {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-block btn-gradient-danger btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
        </div>
        <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <label class="form-check-label text-muted">
                  <input class="form-check-input" type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  Keep me signed in
                </label>
            </div>
            <div style="align: right">
            @if (Route::has('password.request'))
                <a class="auth-link text-black" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
            </div>
        </div>
         <div class="mb-2">
         <a href="{{ url('/auth/facebook') }}"> <button type="button" class="btn btn-block btn-facebook auth-form-btn">
            <i class="mdi mdi-facebook mr-2"></i>Connect using Facebook
          </button></a>
        </div>
        <div class="mb-2">
          <a href="{{ url('/auth/google') }}"><button type="button" class="btn btn-block btn-google auth-form-btn">
            <i class="mdi mdi-google mr-2"></i>Connect using Google
          </button></a>
        </div>
        
        <div class="text-center mt-4 font-weight-light">
          Don't have an account? <a href="{{url('user/register')}}" class="text-primary">Create</a>
        </div> 
      </form>
    </div>
  </div>
</div>
@endsection
