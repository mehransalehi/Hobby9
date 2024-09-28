@extends('layouts.default')
<?php $whereAmI='profile-login'; ?>
@section('description')
  <meta name="description" content="ورود به پروفایل کاربری وب سایت www.HOBBY9.com">
@stop

@section('title')
  ورود به ناحیه کاربری
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}" type="text/css">
@stop

@section('script')
@stop

@section('content')
  <div class="login-form-wraper">
    <img src="{{ URL::asset('css/images/home-logo.png') }}"/ class="img-responsive" >
    <div class="login-title">ورود به ناحیه کاربری</div>
    <form action="{{ url('profile/login/') }}" method="POST">
      {{ csrf_field() }}
      <fieldset>
        <div class="form-group">
          <div class="input-group">
            <input name="email" class="form-control margin-bottom-sm" type="text" placeholder="نام کاربری یا ایمیل">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input name="password" class="form-control margin-bottom-sm" type="password" placeholder="کلمه عبور">
            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
          </div>
        </div>
        <div class="form-group check-box-group">
            <input name="remember" class="checkbox" type="checkbox" value="remember">مرا به خاطر بسپار
        </div>
        <div class="form-group">
          <input class="btn btn-hobby form-control" type="submit" value="ورود">
        </div>
      </fieldset>
    </form>
    <a class="forget-link" href="{{url('forget')}}">کلمه عبور را فراموش کرده اید؟</a>
    <hr class="spacer"/>
    <div class="reg-title">عضویت در HOBBY9</div>
    <div class="form-group">
      <a href="{{url('register') }}" class="btn btn-hobby btn-lg btn-reg">عضویت</a>
    </div>
  </div>
@stop
