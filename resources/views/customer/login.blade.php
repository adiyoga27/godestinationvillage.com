@extends('customer/layout')

@section('content')
    <!-- start authentication area -->
    <div class="authentication-section">
        <div class="container">
            <div class="main-form ptb-100">
                <form method="POST" class="register-form" id="login-form" action="{{ route('login') }}">
                    @csrf
                    <div class="content">
                        <h3>Welcome Back</h3>
                        <p>Login please if you already have an account</p>
                    </div>
                    <div class="form-group">
                        <div class="input-icon"><i class='bx bx-at'></i></div>
                        <input type="text" id="your_name" placeholder="Your email" name="email" class="form-control"
                            placeholder="Email" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="input-icon"><i class='bx bx-key'></i></div>
                        <input type="password" name="password" id="your_pass" class="form-control"
                            placeholder="Your password" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row align-items-center mb-30">
                        <div class="col-lg-6 col-sm-6 col-6">
                            <div class="checkbox">
                                <input name="remember-me" type="checkbox" id="remember">
                                <label for="remember">Remember me</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6">
                            <div class="link">
                                <a href="forget-password.html">Forget password?</a>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="signin" id="signin" class="btn-primary">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- end authentication section -->
@endsection()
