@extends('admin.layouts.default')
<?php $whereAmI = 'home';?>
@section('title')
  خانه | پنل مدیریت
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/index.css') }}" type="text/css">
@stop

@section('content')
    <h1>داشبورد</h1>
    <hr class="main-hr"/>
    <div class="panel panel-default">
      <div class="panel-heading">اطلاعات</div>
      <div class="panel-body">
        <div class="info-panel col-md-5">
          <div class="user-count">
            <div class="top-info-panel">
              <i class="fa fa-users" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['users']}}</div>
                <div class="info-text">تعداد کاربران</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش کاربران</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="reg-count">
            <div class="top-info-panel">
              <i class="fa fa-registered" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['reg']}}</div>
                <div class="info-text">درخواست عضویت</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="feed-count">
            <div class="top-info-panel">
              <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['feed']}}</div>
                <div class="info-text">پیام های پشتیبانی</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="file-count">
            <div class="top-info-panel">
              <i class="fa fa-file" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['all']}}</div>
                <div class="info-text">تعداد فایل ها</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="del-count">
            <div class="top-info-panel">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['del']}}</div>
                <div class="info-text">فایل های پاک شده</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="new-file-count">
            <div class="top-info-panel">
              <i class="fa fa-eye" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['new']}}</div>
                <div class="info-text">آپلود شده امروز</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="video-count">
            <div class="top-info-panel">
              <i class="fa fa-video-camera" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['video']}}</div>
                <div class="info-text">تعداد ویدیوها</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="music-count">
            <div class="top-info-panel">
              <i class="fa fa-music" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['music']}}</div>
                <div class="info-text">تعداد موسیقی ها</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="info-panel col-md-5">
          <div class="book-count">
            <div class="top-info-panel">
              <i class="fa fa-book" aria-hidden="true"></i>
              <div class="info-on-top">
                <div class="num-in-top">{{$data['ebook']}}</div>
                <div class="info-text">تعداد کتاب ها</div>
              </div>
            </div>
            <div class="bot-info-panel">
              <a href="#">
                <span>نمایش</span>
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
@stop
