@extends('admin.layouts.default')
<?php $whereAmI = 'home-setting';?>
@section('title')
    تنظیمات خانه
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/setting.css') }}" type="text/css">
@stop

@section('content')
    <h1>تنظیمات صفحه خانگی</h1>
    <hr class="main-hr"/>
    @if(@$data['status'])
      <ul>
        @if(@$data['status'] == 'success')
          <li class="alert alert-success">با موفقیت ذخیره شد.</li>
        @elseif(@$data['status'] == 'deleted')
          <li class="alert alert-success">با موفقیت حذف شد.</li>
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
      <div class="panel-heading">آپلود لوگو</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/setting/') }}" method="POST">
          {{ csrf_field() }}
          <fieldset>
            <div class="form-group">
              <div class="input-group">
                <input name="image" class="form-control" type="file" placeholder="انتخاب کنید">
              </div>
            </div>
            <div class="form-group">
              <input class="btn btn-hobby form-control btn-ord" type="submit" value="آپلود">
            </div>
          </fieldset>
        </form>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">نمایش قسمت های مختلف</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/setting/home/showhide/') }}" method="POST">
          {{ csrf_field() }}
          <fieldset>
            <div class="form-group">
              <div class="form-group check-box-group">
                  <input @if(@$data['home_special_page'] == 'show') checked @endif name="special_page" class="checkbox" type="checkbox" value="show">صفحه های ویژه نمایش داده شود؟
              </div>
            </div>
            <div class="form-group">
              <div class="form-group check-box-group">
                  <input @if(@$data['home_top_part'] == 'show') checked @endif name="top_part" class="checkbox" type="checkbox" value="show">رسانه اصلی و اخبار نشان داده شود؟
              </div>
            </div>
            <div class="form-group">
              <label for="sel1">رسانه اصلی : </label>
              <select name="top_media" class="form-control" id="sel1">
                <option @if(strpos(@$data['home_top_media'], 'none') !== false) selected @endif value="none">هیچکدام</option>
                <option @if(strpos(@$data['home_top_media'], 'newest') !== false) selected @endif value="newest">جدیدترین</option>
                <option @if(strpos(@$data['home_top_media'], 'view') !== false) selected @endif value="view">پربازدیدترین</option>
                <option @if(strpos(@$data['home_top_media'], 'download') !== false) selected @endif value="download">پردانلود ترین</option>
                <option @if(strpos(@$data['home_top_media'], 'hashed') !== false) selected @endif value="hashed">رسانه مشخص</option>
                <option @if(strpos(@$data['home_top_media'], 'taged') !== false) selected @endif value="taged">رسانه هایی با تگ مشخص</option>
                <option @if(strpos(@$data['home_top_media'], 'searched') !== false) selected @endif value="searched">رسانه هایی با اسم مشخص جستجو شده</option>
                <option @if(strpos(@$data['home_top_media'], 'channel') !== false) selected @endif value="channel">رسانه های یک کانال خاص</option>
              </select>
            </div>
            <div class="form-group">
              <label for="sel1">کلید رسانه : </label>
              <input name="hash" class="form-control" type="text" placeholder="کلید رسانه مورد نظر" value="{{ @$data['home_top_media-info'] }}">
            </div>
            <div class="form-group">
              <input class="btn btn-hobby form-control btn-ord" type="submit" value="ذخیره">
            </div>
          </fieldset>
        </form>
      </div>
    </div>
@stop
