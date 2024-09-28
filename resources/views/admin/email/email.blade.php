@extends('admin.layouts.default')
<?php $whereAmI = 'admin-email';?>
@section('title')
  ارسال ایمیل
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/email.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){

    });
  </script>
@stop

@section('content')
    <h1>ارسال ایمیل</h1>
    <hr class="main-hr"/>
    @if(@session('status'))
      <ul>
        @if(@session('status') == 'del_success')
          <li class="alert alert-success">با موفقیت خذف شد</li>
        @elseif(@session('status') == 'email_success')
          <li class="alert alert-success">با موفقیت فرستاده شد.</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">عملیات مربوط به ایمیل</div>
      <div class="panel-body">
        <a href="{{url('/webmaster/email/regall')}}" class="btn btn-hobby form-control col-md-5 btn-ord">ارسال دوباره ایمیل رجیستر به همه</a>
        <a href="{{url('/webmaster/email/deltrash')}}" class="btn btn-hobby form-control col-md-5 btn-ord">پاک کردن ایمیل های نامربوط</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">ارسال دوباره ایمیل رجیستری</div>
      <div class="panel-body">
      <form action="{{ url('webmaster/email/reg/') }}" method="POST">
        {{ csrf_field() }}
        <fieldset>
          <div class="form-group">
            <textarea id="text" name="text" class="form-control" placeholder="لیست ایمیل ها | در هر خط یک ایمیل"></textarea>
          </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ارسال">
          </div>
        </fieldset>
      </form>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">ارسال ایمیل به کاربران</div>
      <div class="panel-body">
      <form action="{{ url('webmaster/email/users/') }}" method="POST">
        {{ csrf_field() }}
        <fieldset>
          <div class="form-group">
            <textarea id="text" name="text" class="form-control" placeholder="متن"></textarea>
          </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ارسال">
          </div>
        </fieldset>
      </form>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">ارسال ایمیل انبوه</div>
      <div class="panel-body">
      <form action="{{ url('webmaster/email/huge/') }}" method="POST">
        {{ csrf_field() }}
        <fieldset>
          <div class="form-group">
            <textarea id="emails" name="emails" class="form-control" placeholder="لیست ایمیل ها | در هر خط یک ایمیل"></textarea>
          </div>
          <div class="form-group">
            <textarea id="text" name="text" class="form-control" placeholder="متن"></textarea>
          </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ارسال">
          </div>
        </fieldset>
      </form>
      </div>
    </div>
@stop
