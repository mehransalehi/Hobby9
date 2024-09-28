@extends('admin.layouts.default')
<?php $whereAmI = 'file-manager';?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
    عملیات روی فایل ها
@stop
@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/filemanager.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript">
  $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
    $(document).ready(function(){

    });
    function single_del(hash)
    {
    	if(!hash)
        {
          alert("هش یا نام خالی است.");
          return;
        }
        var data = {"command":"singledel",
        			"hash":hash
        		};
        sendCommand(data,"singledel");
    }

  </script>
@stop

@section('content')
    <h1>اکشن ها</h1>
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
      <div class="panel-heading">عملیات قابل اجرا روی فایل ها</div>
      <div class="panel-body">
        <a href="{{url('/webmaster/files/showsorted')}}" class="btn btn-hobby form-control col-md-5 btn-ord">نمایش بر اساس جدیدترین</a>
        <a href="{{url('/webmaster/files/showradio')}}" class="btn btn-hobby form-control col-md-5 btn-ord">نمایش موسیقی های رادیو</a>
        <a href="{{url('/webmaster/files/deltrash')}}" class="btn btn-hobby form-control col-md-5 btn-ord">پاک کردن فایهای سطل زباله</a>
        <a href="{{url('/webmaster/files/deltele')}}" class="btn btn-hobby form-control col-md-5 btn-ord">تمیز کردن رسانه های مخصوص تلگرام</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">جستجو , رسانه خاص , رسانه های یک کانال خاص</div>
      <div class="panel-body">
      <form action="{{ url('webmaster/files/search') }}" method="GET">
        <fieldset>
          <div class="radio">
            جستجو<input checked="checked" type="radio" name="type" value="search">
          </div>
          <div class="radio">
            یک کانال خاص<input type="radio" name="type" value="channel">
          </div>
          <div class="radio">
            رسانه خاص<input type="radio" name="type" value="file">
          </div>
          <div class="radio">
            در سطل زباله؟<input type="checkbox" name="trash" value="trash">
          </div>
        </fieldset>
        <fieldset>
          <div class="form-group">
            <input type="text" name="hash" class="form-control" placeholder="هش رسانه , هش کانال , رشته برای جستجو"></textarea>
          </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="جستجو">
          </div>
        </fieldset>
      </form>
      </div>
    </div>
@stop
