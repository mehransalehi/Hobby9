@extends('admin.layouts.default')
<?php $whereAmI = 'file-dl';?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
   دانلود از لینک
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
    <h1>دانلود از لینک - یوتیوب</h1>
    <hr class="main-hr"/>
    @if(@session('status'))
      <ul>
        @if(@session('status') == 'success')
          <li class="alert alert-success">با موفقیت فرستاده شد</li>
        @elseif(@session('status') == 'error')
          <li class="alert alert-danger">مشکلی رخ داده است</li>
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
      <div class="panel-heading"></div>
      <div class="panel-body">
      <form action="{{ url('webmaster/files/dl') }}" method="POST">
        {{ csrf_field() }}
        <fieldset>
          <div class="form-group col-sm-5">
            <lable>لینک / کلمه :</lable>
            <input type="text" name="text" class="form-control" placeholder="لینک یا کلمه کلیدی" value="{{old('text')}}">
          </div>
          <div class="form-group col-sm-5">
            <lable>کاربران عادی</lable>
            <select class="form-control" name="user">
              @if (empty(old('user')))
                <option value="NONE">انتخاب کنید</option>
              @else
                <option value="{{old('user')}}">{{old('user')}}</option>
              @endif
              @foreach ($data['classes'] as $class)
                <option value="{{$class->hash}}">{{$class->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-sm-5">
            <lable>کاربران ویژه :</lable>
            <select class="form-control" name="special">
              @if (empty(old('special')))
                <option value="NONE">انتخاب کنید</option>
              @else
                <option value="{{old('special')}}">{{old('special')}}</option>
              @endif
            </select>
          </div>
          <div class="form-group col-sm-5">
            <lable>پسوند : </lable>
            <select class="form-control" name="ext">
              @if (empty(old('ext')))
                <option selected value=".mp4">Mp4</option>
              @else
                <option selected value="{{old('ext')}}">{{old('ext')}}</option>
              @endif
              <option value=".mp3">mp3</option>
              <option value=".pdf">pdf</option>
              <option value=".3gp">3gp</option>
              <option value=".mkv">mkv</option>
              <option value=".flv">flv</option>
              <option value=".avi">avi</option>
              <option value=".mpeg">mpeg</option>
              <option value=".mpg">mpg</option>
              <option value=".mov">mov</option>
              <option value=".wmv">wmv</option>
              <option value=".aac">wmv</option>
              <option value=".ogg">mpg</option>
              <option value=".wav">mov</option>
              <option value=".ac3">wmv</option>
            </select>
          </div>
          <div class="form-group col-sm-5">
            <lable>چند تا : </lable>
            <input type="text" name="number" class="form-control" placeholder="چنتا" value="10">
          </div>

          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="دانلود">
          </div>
          <p class="well">در قسمت لینک / کلید آدرس لینک مستقیم فایل, یا آدرس صفحه نمایش ویدیو از سایت یوتیوب و یا کلمه کلیدی مورد نظر برای جستجو در یوتیوب را وارد کنید</p>
          <p class="well">کاربران عادی نشان دهنده ی کاربران عادی عضو شده در سایت هستند</p>
          <p class="well">کاربران ویژه نشان دهنده صفحات ویژه و کانال های ویژه هستند</p>
          <p class="well">در صورت انتخاب هم کاربر عادی و هم کاربر ویژه , کاربر عادی اولویت دارد.</p>
          <p class="well">در قست پسوند , پسوند فایلی که باید دانلود شود یا پسوند مورد نظری که مایلید از سایت یوتیوب دانلود شود قرار بگیرد</p>
          <p class="well">در قسمت چند تا تعداد رسانه هایی را که باید بعد از جستجو از یوتیوب دانلود کند را ذکر کنید. سعی کنید تعداد بالا نباشد.</p>
        </fieldset>
      </form>
      </div>
    </div>
@stop
