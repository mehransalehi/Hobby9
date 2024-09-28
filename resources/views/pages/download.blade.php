@extends('layouts.default')

<?php $whereAmI='dl-media' ?>
@section('title')
  دانلود {{ $data->title }} | HOBBY9
@stop

@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/dl.css')?>" type="text/css">
@stop

@section('script')
  <script language="javascript" type="text/javascript">
  	var btnText = 'دانلود ( {{ \App\Http\handyHelpers::makeSize($data->volume) }} )';
  	var count = 8;
  	var counter = setInterval(timer, 1000);
  	function timer()
  	{
  		count=count-1;
  		if (count <= 0)
  		{
  		 clearInterval(counter);
  		 document.getElementById("btn-download").innerHTML = btnText;
  		 document.getElementById("btn-download").onclick = setup;
  		 return;
  		}
  		document.getElementById("btn-download").innerHTML = "(&nbsp;&nbsp;"+count+"&nbsp;&nbsp;) ثانیه صبر کنید";
  	}
  	function setup()
  	{
  		var hash = document.getElementById("hash");
  		var frm = document.getElementById("frm");

  		hash.value = "{{$data->hash}}";
  		frm.submit();
  	}
  	function toggle_visibility(id) {
         var e = document.getElementById(id);
         if(e.style.display == 'block')
            e.style.display = 'none';
         else
            e.style.display = 'block';
      }
  </script>

@stop

@section('content')
  <div class="container">
		<div class="col-xs-12">
			<div class="exp row">
				<div class="col-md-6">
					<a class="button_example" href="{{url('register/')}}">عضویت</a>
				</div>
				<div class="col-md-6">
					<p>به HOBBY9 خوش آومدی. این وب سایت اولین سرویس اشتراک چند رسانه ایه که کاربرا می تونن محتوای چند رسانه ای خودشونو (ویدیو ,آهنگ ,کتاب) با بقیه به اشتراک بذارن. اگه علاقه مندی که بدونی
					در این وب سایت می تونی چه کارایی بکنی منوی پایین راهنمای خوبیه. ممنون از اینکه با ما همراهی,تیم HOBBY۹ امیدواره لحظات خوشی داشته باشی.</p>
				</div>
        <div class="clear"></div>
			</div>
			<div itemscope itemtype="http://schema.org/Thing" class="content row">
				<h1 itemprop="name">دانلود فایل : {{$data->title}}</h1>
				<span class="screenreaders-only" itemprop="url">{{url('dl/'.$data->hash)}}</span>
				<form id="frm" action="{{url('dl/'.$data->hash)}}" method="POST">
          {{ csrf_field() }}
					<input type="hidden" id="hash" name="hash" value="none">
					<div id="btn-download" class="btn-download">(&nbsp;&nbsp;8&nbsp;&nbsp;) ثانیه صبر کنید</div>
				</form>
				<div class="file-info">
					<div><span class="lablel">حجم : </span>&nbsp;&nbsp;&nbsp;<span>{{ \App\Http\handyHelpers::makeSize($data->volume) }}</span></div>
					<div><span class="lablel">دانلود شده : </span>&nbsp;&nbsp;&nbsp;<span> {{$data->nundownload}} بار</span></div>
					<div><span class="lablel">آدرس : </span>&nbsp;&nbsp;&nbsp;<span><input class="form-control" type="text" value="{{url('dl/'.$data->hash)}}"/></span></div>
					<div><span class="lablel">توضیحات : </span>&nbsp;&nbsp;&nbsp;<span itemprop="description">{{$data->explenation}}</span></div>
				</div>
				<div class="share-div">
					<div title="اشتراک گذاری روی کلوب"><a href="http://www.cloob.com/share/link/add?url={{url('dl/'.$data->hash)}}&title=Download File : {{$data->title}}"><img alt="اشتراک گذاری روی کلوب" src="{{URL::asset('css/images/cloob.png')}}"></a></div>
					<div title="اشتراک گذاری روی فیسبوک"><a href="http://www.facebook.com/share.php?u={{url('dl/'.$data->hash)}}&t=Download File : {{$data->title}}"><img alt="اشتراک گذاری روی فیسبوک" src="{{URL::asset('css/images/face.png')}}"></a></div>
					<div title="اشتراک گذاری روی گوگل پلاس"><a href="https://plus.google.com/share?url={{url('dl/'.$data->hash)}}"><img alt="اشتراک گذاری روی گوگل پلاس" src="{{URL::asset('css/images/g+.png')}}"></a></div>
					<div title="اشتراک گذاری روی تویتر"><a href="http://twitter.com/home?status={{url('dl/'.$data->hash)}} : {{$data->title}}"><img alt="اشتراک گذاری روی تویتر" src="{{URL::asset('css/images/twitter.png')}}"></a></div>
					<div title="گذارش تخلف"><a href="{{url('report/'.$data->hash)}}"><img alt="گذارش تخلف" src="{{URL::asset('css/images/warn.png')}}"></a></div>
				</div>
			</div>
		</div>
	</div>
@stop
