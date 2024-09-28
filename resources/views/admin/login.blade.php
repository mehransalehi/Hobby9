<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ورود به پنل مدیریت</title>
    <link rel="stylesheet" href="{{ URL::asset('css/admin/login.css') }}" type="text/css">
</head>
<body>
<div class="container-fluid">
  <div class="login-page">
    <div class="form">
      @if(session('status'))
        <ul>
          @if(session('status') == 'not_access')
            <li class="alert alert-danger">این اطلاعات معتبر نیست</li>
          @endif
        </ul>
      @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
      @endif
      <form method="POST" action="{{ url('webmaster/login/') }}" class="login-form">
        {{ csrf_field() }}
        <input name="username" type="text" placeholder="نام کاربری"/>
        <input name="password" type="password" placeholder="کلمه عبور"/>
        <input class="btn-submit" type="submit" value="ورود" />
      </form>
    </div>
  </div>

</div>
</body>
</html>
