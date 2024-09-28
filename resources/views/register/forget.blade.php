@extends('layouts.default')
<?php $whereAmI='profile-login'; ?>
@section('description')
  <meta name="description" content="عضویت در وب سایت www.HOBBY9.com">
@stop

@section('title')
  بازیابی کلمه عبور
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/forget.css') }}" type="text/css">
@stop

@section('script')
@stop

@section('content')
  <div class="login-form-wraper">
    <img src="{{ URL::asset('css/images/home-logo.png') }}"/ class="img-responsive" >
    <div class="reg-title">بازیابی کلمه عبور</div>
    @if(session('status'))
      <ul>
        @if(session('status') == 'email_exist')
          <li class="alert alert-danger">کاربری با این ایمیل ثبت نشده است</li>
        @elseif(session('status') == 'pass_wrong')
          <li class="alert alert-success">درخواست عضویت دوباره برای شما ارسال شد. لطفا به ایمیل خود رفته و روی لینک ارسالی از طرف ما در داخل ایمیل خود کلیک کنید تا عملیات ثبت نام خود را کامل کنید.<br>در صورتی که ایمیل در قسمت Inbox نبود قسمت Spam را نیز چک کنید</li>
        @elseif(session('status') == 'success')
          <li class="alert alert-success">در خواست تغییر پسورد به ایمیل شما فرستاده شد لینک داخل ایمیل ارسالی را باز کنید تا پسورد جدید شما ثبت شود.<br>در صورتی که ایمیل در قسمت Inbox نبود قسمت Spam را نیز چک کنید</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif

    <form action="{{ url('forget') }}" method="POST">
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
            <input name="pass" class="form-control margin-bottom-sm" type="password" placeholder="کلمه عبور جدید">
            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input name="conf" class="form-control margin-bottom-sm" type="password" placeholder="تکرار کلمه عبور جدید">
            <span class="input-group-addon"><i class="fa fa-repeat fa-fw"></i></span>
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
          <input class="btn btn-hobby form-control" type="submit" value="ارسال">
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
