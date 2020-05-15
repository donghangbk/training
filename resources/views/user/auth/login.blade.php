@extends('layouts.auth')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>LOGIN</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                @if(Session::has('message'))
                <div class="text-center">
                    <strong class="text-red">{{ Session::get('message') }}</strong>
                </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="input-group {{ $errors->has('email') ? '' : 'mb-3' }}">
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email', 'user@gmail.com') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                    <div class="help-block  mb-3">
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    </div>
                    @endif
                    <div class="input-group {{ $errors->has('password') ? '' : 'mb-3' }}">
                        <input type="password" class="form-control" placeholder="Password" name="password" value="1234567">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <div class="help-block  mb-3">
                            <label class="text-danger">{{ $errors->first('password') }}</label>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
