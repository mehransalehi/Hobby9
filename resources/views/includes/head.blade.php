<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta charset="utf8">

<meta name="author" content="Mehran.S">
<meta http-equiv="content-language" content="fa" />
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1.0">
@if(@$meta)
	{!! $meta!!}
@else
@yield('description','<meta name="description" content="سرویس اشتراک ویدیو آهنگ صوت و کتاب الکترونيکی | HOBBY9">')

@yield('keywords','<meta name="keywords" content="اشتراک فایل,اشتراک ودیو,اشتراک آهنگ,اشتراک کتاب الکترونیکی,دانلود آهنگ,دانلود کتاب الکترونیکی,دانلود ویدیو,ویدیو خنده دار,ویدیو آموزشی,ویدیو جالب,آهنگ زیبا,مطالب آموزشی,مطلاب سرگرم کننده,مطالب تفریحی,مطالب مذهبی,اوقات فراغت,video,music,ebook,اشتراک,share,sharingاپلود اهنگ,اپلود فیلم,آپلود آهنگ,موزیک ویدیو,سایت فیلم,سرویس ویدیو,سایت کلیپ,سایت اشتراک, اشتراک گذاری,اشتراک فیلم,آهنگ آنلاین,آپلود موزیک,دانلود رایگان کتاب,آپلود رایگان,سرویس رایگان اشتراک,دانلود رایگان,صوتی,سایت کتاب,فایل صوتی,پی دی اف,صوتی دانلود,تماشای فیلم,پخش آنلاین">')
@endif
@yield('metashare')

<title>@yield('title','سرویس اشتراک ویدیو آهنگ صوت و کتاب الکترونيکی | HOBBY9')</title>

@yield('style')
<!--<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
<link rel="stylesheet" href="<?php echo URL::asset('css/font-awesome.min.css')?>">

<script type="text/javascript">
	var THIS_URL = "{{ url('/') }}";
</script>
<script src="<?php echo URL::asset('js/jquery-3.1.1.min.js')?>"></script>
<script src="<?php echo URL::asset('js/tooltip.min.js')?>"></script>
<script src="<?php echo URL::asset('js/main.js')?>"></script>
@yield('script')
