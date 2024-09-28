@extends('layouts.default-profile')

@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
  رسانه های دنبال شده
@endsection
@section('script')
  <script type="text/javascript">
    $.ajaxSetup({
    headers: {
    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    function follow_media(hash)
    {
      var data = {"media":hash,"type":"media"};
      sendCommand(data,"unfollowmedia");
    }
    function doResponse(result,command)
    {
        console.log(result);
        var object = $('<div/>').append(result);
        var msg = $(object).find('#message').html();
        $("#msg-load").hide();
        if(command == "unfollowmedia")
        {
            resUnFoMedia(object,msg);
        }
    }
    function resUnFoMedia(object,msg)
    {
        var myStatus;
        myStatus = $(object).find('#status').html();
        hash = $(object).find('#hash').html();
        if(myStatus == "faild")
        {
            notify(msg,'error');
        }
        else if(myStatus == "success")
        {
            $("#row-"+hash).remove();
            notify(msg,'notice');
        }
    }
  </script>
@endsection
<?php $whereAmI='profile-follow' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/follow.css')?>" type="text/css">
@stop
@section('content')
  @if(count($data['follow'])<1)
    <div class="alert alert-hobby">رسانه ای را نپسندیده اید</div>
  @else
    <ul class="media-list">
    @foreach($data['follow'] as $follow)
      <?php $file = $follow->file;?>
      @if($file->type == 1)
        <li class="each-media-item" title="{{ $file->title }}" id="row-{{$file->hash}}">
          <a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
            <img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=2&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
            <h2>{{ $file->title }}</h2>
            <span class="icon sound-ico"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
            <span class="duration">{{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}</span>
          </a>
          <div class="col-xs-12 btn-unlike btn btn-hobby" onclick="follow_media('{{$file->hash}}')">نمی پسندم</div>
        </li>
      @elseif($file->type == 2)
        <li class="each-media-item" title="{{ $file->title }}" id="row-{{$file->hash}}">
          <a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
            <img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=3&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
            <h2>{{ $file->title }}</h2>
            <span class="icon sound-ico"><i class="fa fa-book" aria-hidden="true"></i></span>
            <span class="duration"> {{ \App\Http\handyHelpers::ta_persian_num($file->pagetime) }} صفحه</span>
          </a>
          <div class="col-xs-12 btn-unlike btn btn-hobby" onclick="follow_media('{{$file->hash}}')">نمی پسندم</div>
        </li>
      @elseif($file->type == 3)
        <li class="each-media-item" title="{{ $file->title }}" id="row-{{$file->hash}}">
          <a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
            <img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=2&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
            <h2>{{ $file->title }}</h2>
            <span class="icon sound-ico"><i class="fa fa-music" aria-hidden="true"></i></span>
            <span class="duration">{{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}</span>
          </a>
          <div class="col-xs-12 btn-unlike btn btn-hobby" onclick="follow_media('{{$file->hash}}')">نمی پسندم</div>
        </li>
      @endif
    @endforeach
    </ul>
  @endif
@endsection
