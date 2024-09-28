@extends('layouts.default')
<?php $whereAmI='search' ?>
@section('title')
  @if (@$special)
    {{ $text }}
  @elseif(empty($group))
  نتایج جستجوی "{{ $text }}" | HOBBY9
  @else
    گروه {{ $group }} | HOBBY9
  @endif
@stop
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/search.css')?>" type="text/css">
@stop


@section('content')
  <div class="container-hobby row main-search-content">
		<div class="col-sm-3 col-md-2 col-xs-12 search-menu">
				<ul>
          <h2>دسته ها</h2>
          <li @if($group == 'تبليغات') class="active-branch" @endif><a href="{{ url('/group/تبليغات/') }}"><h3>تبليغات</h3></a></li>
          <li @if($group == 'کارتون') class="active-branch" @endif><a href="{{ url('/group/کارتون/') }}"><h3>کارتون</h3></a></li>
          <li @if($group == 'طنز') class="active-branch" @endif><a href="{{ url('/group/طنز/') }}"><h3>طنز</h3></a></li>
          <li @if($group == 'علمی') class="active-branch" @endif><a href="{{ url('/group/علمی/') }}"><h3>علمی</h3></a></li>
          <li @if($group == 'حوادث') class="active-branch" @endif><a href="{{ url('/group/حوادث/') }}"><h3>حوادث</h3></a></li>
          <li @if($group == 'حيوانات') class="active-branch" @endif><a href="{{ url('/group/حيوانات/') }}"><h3>حيوانات</h3></a></li>
          <li @if($group == 'طبيعت') class="active-branch" @endif><a href="{{ url('/group/طبيعت/') }}"><h3>طبيعت</h3></a></li>
          <li @if($group == 'صداوسيما') class="active-branch" @endif><a href="{{ url('/group/صداوسيما/') }}"><h3>صداوسيما</h3></a></li>
          <li @if($group == 'شخصی') class="active-branch" @endif><a href="{{ url('/group/شخصی/') }}"><h3>شخصی</h3></a></li>
          <li @if($group == 'سياسی') class="active-branch" @endif><a href="{{ url('/group/سياسی/') }}"><h3>سياسی</h3></a></li>
          <li @if($group == 'آموزشی') class="active-branch" @endif><a href="{{ url('/group/آموزشی/') }}"><h3>آموزشی</h3></a></li>
          <li @if($group == 'کامپيوتر') class="active-branch" @endif><a href="{{ url('/group/کامپيوتر/') }}"><h3>کامپيوتر</h3></a></li>
          <li @if($group == 'هنری') class="active-branch" @endif><a href="{{ url('/group/هنری/') }}"><h3>هنری</h3></a></li>
          <li @if($group == 'سلامت') class="active-branch" @endif><a href="{{ url('/group/سلامت/') }}"><h3>سلامت</h3></a></li>
          <li @if($group == 'ورزشی') class="active-branch" @endif><a href="{{ url('/group/ورزشی/') }}"><h3>ورزشی</h3></a></li>
          <li @if($group == 'ورزشی') class="active-branch" @endif><a href="{{ url('/group/مذهبی/') }}"><h3>مذهبی</h3></a></li>
          <li @if($group == 'مهندسی') class="active-branch" @endif><a href="{{ url('/group/مهندسی/') }}"><h3>مهندسی</h3></a></li>
          <li @if($group == 'تفريحی') class="active-branch" @endif><a href="{{ url('/group/تفريحی/') }}"><h3>تفريحی</h3></a></li>
          <li @if($group == 'متفرقه') class="active-branch" @endif><a href="{{ url('/group/متفرقه/') }}"><h3>متفرقه</h3></a></li>
        </ul>
		</div>
		<div class="col-sm-9 col-md-10 col-xs-12 search-content">
      @if(count($data)<1)
        <div class="alert alert-hobby">نتیجه ای برای جستجوی شما یافت نشد</div>
      @else
        @if($user)
          <h1>نتایج جستجوی صفحه {{$user->name}}</h1>
        @else
          <h1>نتایج جستجوی : {{$text}}</h1>
        @endif
        <div class="pagination">@include('pages.pagination', ['object' => $data])</div>
        <ul class="media-list">
        @foreach($data as $file)
          @if($file->type == 1)
            <li class="each-media-item" title="{{ $file->title }}">
    					<a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
    						<img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=2&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
                <h2>{{ $file->title }}</h2>
                <span class="icon sound-ico"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                <span class="duration">{{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}</span>
    					</a>
    				</li>
          @elseif($file->type == 2)
            <li class="each-media-item" title="{{ $file->title }}">
    					<a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
    						<img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=3&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
                <h2>{{ $file->title }}</h2>
                <span class="icon sound-ico"><i class="fa fa-book" aria-hidden="true"></i></span>
                <span class="duration"> {{ \App\Http\handyHelpers::ta_persian_num($file->pagetime) }} صفحه</span>
    					</a>
    				</li>
          @elseif($file->type == 3)
            <li class="each-media-item" title="{{ $file->title }}">
    					<a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
    						<img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=2&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
                <h2>{{ $file->title }}</h2>
                <span class="icon sound-ico"><i class="fa fa-music" aria-hidden="true"></i></span>
                <span class="duration">{{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}</span>
    					</a>
    				</li>
          @endif
        @endforeach
        </ul>
      @endif
		</div>
    <div class="pagination">@include('pages.pagination', ['object' => $data])</div>
	</div>


@stop
