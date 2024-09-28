@extends('layouts.default-radio')

@section('title')
  رادیو اینترنتی | HOBBY9
@endsection
@section('description')
  <meta name="description" content="جدیدترین موزیک ها و ویدیو موزیک ها به همراه پخش زنده جدیدترین موسیقی ها | HOBBY9 Radio">
@endsection
<?php $whereAmI='radio' ?>
@section('style')
  <link rel="stylesheet" href="<?php echo URL::asset('css/radio.css')?>" type="text/css">
@stop

@section('script')
  <script src="<?php echo URL::asset('js/amplitude.min.js')?>" ></script>

  <script type="text/javascript">
    $(document).ready(function(){
      Amplitude.init({
    "songs": [
      {
        "call_sign": "WYMSE",
        "station_name": "88.9 Radio Milwaukee",
        "location": "Milwaukee, WI",
        "frequency": "88.9 MHz",
        "url": "{{url('/')}}:9000/stream",
        "live": true,
        "cover_art_url": "images/radiomilwaukee.jpg"
      }
    ],
    "default_album_art": "images/no-cover-large.png",
    "autoplay": true
    });

      var clicked = true;

      $(".bar-c").click( function() {
        if (clicked) {
          $(".bar").addClass("noAnim");
          clicked = false;
        } else {
          $(".bar").removeClass("noAnim");
          clicked = true;
        }
      });
    });

  </script>
@stop

@section('content')
  <sidebar class="col-md-4">
    <div class="online-radio">
      <img class="microphone" src="{{URL::asset('css/images/micro.png')}}" />
      <div class="bar-c amplitude-play-pause amplitude-paused" amplitude-song-index="0">
        <div id="bar-1" class="bar"></div>
        <div id="bar-2" class="bar"></div>
        <div id="bar-3" class="bar"></div>
        <div id="bar-4" class="bar"></div>
        <div id="bar-5" class="bar"></div>
        <div id="bar-6" class="bar"></div>
      </div>
      <h1>Radio Hobby9</h1>
    </div>


    <div class="current-music">
      <h2>آهنگ های در حال پخش</h2>
      @if(count($data['medias']))
        <ul class="current-music-list">
          <?php $i=0; ?>
          @foreach ($data['medias'] as $media)
            @if($i == 7)
              @break
            @endif
            <li title="{{ $media->title }}">
              <img alt="{{ $media->title }}" class="microphone" src="{{url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}&s=1" />
              <div class="cu-music-info">
                <h3>{{ $media->title }}</h3>
                <a target="_blank" title="دانلود" href="{{url('dl/'.$media->hash)}}"><i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                <a target="_blank" title="پخش" href="{{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}"><i class="fa fa-play" aria-hidden="true"></i></a>
              </div>
            </li>
            <?php $i++; ?>
          @endforeach
        </ul>
      @endif
    </div>
    <div class="learning">
      <h2>آموزش پخش رادیو در تمامی دستگاه ها</h2>
      <a href="http://www.hobby9.com/blog/1/21"><i class="fa fa-android" aria-hidden="true"></i></a>
      <a href="http://www.hobby9.com/blog/1/22"><i class="fa fa-linux" aria-hidden="true"></i></a>
      <a href="http://www.hobby9.com/blog/1/22"><i class="fa fa-windows" aria-hidden="true"></i></a>
      <a href="http://www.hobby9.com/blog/1/21"><i class="fa fa-apple" aria-hidden="true"></i></a>
    </div>
    <div class="other">
      <a href="{{url('/feedback')}}">ارتباط با ما</a>
      <a target="_blank" href="https://t.me/hobby9_radio">کانال تلگرام ما</a>
      <a href="http://www.hobby9.com//blog/1/20">درباره رادیو</a>
    </div>
    <div class="tags">
      <a rel="tag" class="tag" href="?share=رادیو"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;رادیو</a>
      <a rel="tag" class="tag" href="?share=رادیو اینترنتی"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;رادیو اینترنتی</a>
      <a rel="tag" class="tag" href="?share=موزیک"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;موزیک</a>
      <a rel="tag" class="tag" href="?share=جدیدترین موزیک ها"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;جدیدترین موزیک ها</a>
      <a rel="tag" class="tag" href="?share=موسیقی"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;موسیقی</a>
      <a rel="tag" class="tag" href="?share=جدیدترین موسیقی ها"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;جدیدترین موسیقی ها</a>
      <a rel="tag" class="tag" href="?share=موزیک ویدیو"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;موزیک ویدیو</a>
      <a rel="tag" class="tag" href="?share=به روز ترین موزیک ها"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;به روز ترین موزیک ها</a>
      <a rel="tag" class="tag" href="?share=بهترین موزیک ها"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;بهترین موزیک ها</a>
      <a rel="tag" class="tag" href="?share=موزیک های ایرانی"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;موزیک های ایرانی</a>
      <a rel="tag" class="tag" href="?share=موزیک های خارجی"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;موزیک های خارجی</a>
      <a rel="tag" class="tag" href="?share=دانلود موزیک"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;دانلود موزیک</a>
      <a rel="tag" class="tag" href="?share=دانلود موسیقی"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;دانلود موسیقی</a>
      <a rel="tag" class="tag" href="?share=دانلود آهنگ"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;دانلود آهنگ</a>
      <a rel="tag" class="tag" href="?share=آهنگ ایرانی"><i class="fa fa-tag" aria-hidden="true"></i> &nbsp;&nbsp;آهنگ ایرانی</a>
    </div>
  </sidebar>
  <div class="col-md-8 content-music">
    <div class="content-head">
      جدیدترین موزیک ها
    </div>
    <div class="new-music">
      @if(count($data['medias']))
        <ul>
          @foreach ($data['medias'] as $media)
            <li class="each-media-item">
              <a alt="{{ $media->title }}" href="{{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}"><img class="each-media-thumb img-responsive" src="{{url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}&s=1" /></a>
              <div class="media-each-info">
                <div class="text">{{ $media->title }}</div>
                <div class="btn-group btn-group-xs">
                  <a target="_blank" class="btn btn-info" href="{{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}"> پخش <i class="fa fa-play" aria-hidden="true"></i></a>
                  <a target="_blank" class="btn btn-info" href="{{url('dl/'.$media->hash)}}"> دانلود <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
@stop
