@extends('admin.layouts.default')
<?php $whereAmI = 'ads';?>
@section('title')
  ویرایش تبلیغات
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/ads.css') }}" type="text/css">
@stop

@section('script')
  <script src="{{ URL::asset('js/jscolor.min.js') }}"></script>
@stop

@section('content')
    <h1>ویرایش تبلیغات</h1>
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
      <div class="panel-heading">ویرایش تبلیغات</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/ads/') }}" method="POST">
          {{ csrf_field() }}
          <fieldset>
            <div class="col-sm-4 form-group">
              <label>تبلیغ سمت چپ </label>
              <textarea name="showmedia_left_ad" class="form-control" placeholder="تبلیغ سمت چپ">@if($data['showmedia_left_ad']) {{ $data['showmedia_left_ad'] }} @endif</textarea>
            </div>
            <div class="col-sm-4 form-group">
              <label>تبلیغ وسط چپ </label>
              <textarea name="showmedia_mid_ad" class="form-control" placeholder="تبلیغ وسط">@if($data['showmedia_mid_ad']) {{ $data['showmedia_mid_ad'] }} @endif</textarea>
            </div>
            <div class="col-sm-4 form-group">
              <label>تبلیغ سمت چپ </label>
              <textarea name="showmedia_right_ad" class="form-control" placeholder="تبلیغ سمت راست">@if($data['showmedia_right_ad']) {{ $data['showmedia_right_ad'] }} @endif</textarea>
            </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ذخیره">
          </div>
          </fieldset>
        </form>
      </div>
    </div>
@stop
