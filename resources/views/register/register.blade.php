@extends('layouts.default')
<?php $whereAmI='profile-login'; ?>
@section('description')
  <meta name="description" content="عضویت در وب سایت www.HOBBY9.com">
@stop

@section('title')
  عضویت | HOBBY9
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/register.css') }}" type="text/css">
@stop

@section('script')
@stop

@section('content')
  <div class="login-form-wraper">
    <img src="{{ URL::asset('css/images/home-logo.png') }}"/ class="img-responsive" >
    <div class="reg-title">عضویت</div>
    @if(session('status'))
      <ul>
        @if(session('status') == 'exist')
          <li class="alert alert-danger">کاربری با این ایمیل قبلا در وب سایت عضو شده است. با کلیک بر روی کلید ورود پایین می توانید به ناحیه کاربری وارد شوید.</li>
        @elseif(session('status') == 'rereg')
          <li class="alert alert-success">درخواست عضویت دوباره برای شما ارسال شد. لطفا به ایمیل خود رفته و روی لینک ارسالی از طرف ما در داخل ایمیل خود کلیک کنید تا عملیات ثبت نام خود را کامل کنید.<br>در صورتی که ایمیل در قسمت Inbox نبود قسمت Spam را نیز چک کنید</li>
        @elseif(session('status') == 'success')
          <li class="alert alert-success">درخواست عضویت برای شما ارسال شد. لطفا به ایمیل خود رفته و روی لینک ارسالی از طرف ما در داخل ایمیل خود کلیک کنید تا عملیات ثبت نام خود را کامل کنید.<br>در صورتی که ایمیل در قسمت Inbox نبود قسمت Spam را نیز چک کنید</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif

    <form action="{{ url('register') }}" method="POST">
      {{ csrf_field() }}
      <fieldset>
        <div class="form-group">
          <div class="input-group">
            <input name="email" class="form-control margin-bottom-sm" type="text" placeholder="ایمیل">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input name="captcha" class="form-control margin-bottom-sm" type="text" placeholder="کد امنیتی">
            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
          </div>
        </div>
        <div class="form-group">
          <p><?php echo captcha_img(); ?></p>
        </div>
        <div class="form-group">
          <input class="btn btn-hobby form-control" type="submit" value="عضویت">
        </div>
      </fieldset>
    </form>
    <hr class="spacer"/>
    <div class="reg-title">قبلا عضو شده اید؟</div>
    <div class="form-group">
      <a href="{{url('profile/login') }}" class="btn btn-hobby btn-lg btn-reg">ورود</a>
    </div>
  </div>
@stop
