@extends('layouts.default')

<?php $whereAmI='show-media' ?>
@section('title')
  گزارش تخلف {{ $data->title }} | HOBBY9
@stop

@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/report.css')?>" type="text/css">
@stop

@section('content')
  <div class="report-form-wraper">
        <div class="verify-title">گزارش تخلف : {{$data->title}}</div>
        @if(session('status'))
          <ul>
            @if(session('status') == 'category_empty')
              <li class="alert alert-danger">دسته مورد نظر انتخاب نشده است.</li>
            @elseif(session('status') == 'success')
              <li class="alert alert-success">گزارش شما با موفقیت ثبت شد.</li>
            @endif
          </ul>
        @else
          <ul>
               @foreach ($errors->all() as $error)
                   <li class="alert alert-danger">{{ $error }}</li>
               @endforeach
           </ul>
        @endif
        <form class="form-horizontal" action="{{ url('report/'.$data->hash) }}" method="POST" id="myfrm">
          {{ csrf_field() }}
          <div class="form-content-one col-xs-12 col-sm-6">
            <fieldset>
              <div class="form-group requird-field">
                <label class="control-label col-xs-2">کلید رسانه مورد نظر</label>
                <input class="form-control input-sm" name="address" type="text" value="@if(strlen($data->hash)>0) {{url('/s/'.$data->hash.'/')}} @endif"/>
              </div>
              <div class="form-group">
                  <p class="well">توجه داشته باشید پر کردن کادر های قرمز رنگ اجباری می باشد. <br> پر کردن بقیه کادر های اختیاری است.</p>
              </div>
              <div class="form-group requird-field">
                <label class="control-label">دقیقه یا صفحه اول (۱)</label>
                  <input name="min_one" class="form-control" type="text"/>
              </div>
              <div class="form-group">
                  <p class="well">در این قسمت دقیقه یا صفحه مورد نظر را که در ان تخلف صورت گرفته است ذکر کنید.</p>
              </div>
              <div class="form-group">
                <label class="control-label">دقیقه یا صفحه دوم (۲)</label>
                  <input class="form-control" type="text" name="min_two"/>
              </div>
              <div class="form-group">
                  <p class="well">در این قسمت دقیقه یا صفحه مورد نظر را که در ان تخلف صورت گرفته است ذکر کنید.</p>
              </div>
              <div class="form-group">
                <label class="control-label">دقیقه یا صفحه سوم (۳)</label>
                  <input class="form-control" name="min_three" type="text"/>
              </div>
              <div class="form-group">
                  <p class="well">در این قسمت دقیقه یا صفحه مورد نظر را که در ان تخلف صورت گرفته است ذکر کنید.</p>
              </div>
            </fieldset>
          </div>
          <div class="form-content-two col-xs-12 col-sm-6">
            <fieldset>
              <div class="form-group requird-field">
                <label class="control-label">دسته</label>
                  <select class="form-control" name="category">
                    <option selected="selected" value="NONE"> دسته مربوط به تخلف را انتخاب کنید
                    <option value="1">مغایرت با قوانین جمهوری اسلامی</option>
                    <option value="2">مغایرت با قوانین کپی رایت</option>
                    <option value="3">شکایت نویسنده یا ناشر</option>
                    <option value="4">محتوای غیر اسلامی</option>
                  </select>
              </div>
              <div class="form-group">
                  <p class="well">دسته ای که تخلف در آن قرار می گیرد را انتخاب کنید.</p>
              </div>
              <div class="form-group">
                <label class="control-label">توضیحات</label>
                  <textarea class="form-control" rows="12" name="des"></textarea>
              </div>
              <div class="form-group">
                  <p class="well">در این قسمت توضیحات , شرح شکایت خورد را در صورت وجود بنویسید.</p>
              </div>
            </fieldset>
            <input class="btn btn-hobby btn-verify" type="submit" value="ارسال اطلاعات"/>
          </div>
        </form>
      </div>
  </div>
@stop
