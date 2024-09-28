@extends('layouts.default')
<?php $whereAmI='profile-login'; ?>
@section('description')
  <meta name="description" content="عضویت در وب سایت www.HOBBY9.com">
@stop

@section('title')
 ارتباط با ما
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/feedback.css') }}" type="text/css">
@stop

@section('script')
@stop

@section('content')
  <div class="login-form-wraper">
    <img src="{{ URL::asset('css/images/home-logo.png') }}"/ class="img-responsive" >
    <div class="reg-title">ارتباط با ما</div>
    @if(session('status'))
      <ul>
        @if(session('status') == 'success')
          <li class="alert alert-success">با موفقیت ذخیره شد</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif

    <form action="{{ url('feedback') }}" method="POST">
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
            <textarea name="text" class="form-control margin-bottom-sm" placeholder="متن"></textarea>
            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
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
  </div>
@stop
