@extends('layouts.default-profile')

@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
  کانال های دنبال شده
@endsection

<?php $whereAmI='profile-channels' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/channels.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">
  $(document).ready(function(){
      $(".tab-btn").bind( "click", function() {
          var id = $(this).attr("tab-content");
          $(".tab-btn").removeClass("active");
          $(".tab-content").css("display","none");
          $(this).addClass("active");
          $("#"+id).css("display","block");
      });
    });
    $.ajaxSetup({
    headers: {
    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    function follow_media(hash)
    {
      var data = {"media":hash,"type":"class"};
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
@stop
@section('content')
      <!-- MESSAGE HERE -->
      @if(@Session::get('status'))
        <ul>
          @if(@Session::get('status') == 'save_success')
            <li class="alert alert-success">با موفقیت ویرایش شد</li>
          @elseif(@Session::get('status') == 'save_pic')
            <li class="alert alert-success">عکس با موفقیت آپلود شد.</li>
          @elseif(@Session::get('status') == 'faild')
            <li class="alert alert-danger">{{Session::get('message')}}</li>
          @endif
        </ul>
      @else
        <ul>
             @foreach ($errors->all() as $error)
                 <li class="alert alert-danger">{{ $error }}</li>
             @endforeach
         </ul>
      @endif
      <div class="col-xs-12">
        <ul class="nav nav-tabs">
          <li id="you-set-tab" tab-content="you-set" class="active tab-btn"><a onclick="return false;" href="#">کانال هایی که دنبال کرده اید</a></li>
          <li id="them-set-tab" tab-content="them-set" class="tab-btn"><a onclick="return false;" href="#">کانال هایی که شما را دنبال کرده اند</a></li>
        </ul>
        <div id="you-set" class="tab-content col-xs-12" style="display:block">
          @if(count($data['follow'])<1)
            <div class="alert alert-hobby">کانالی را دنبال نکرده اید.</div>
          @else
            <ul class="media-list">
            @foreach($data['follow'] as $follow)
              <?php $channel = $follow->targetChannel;?>
              <li class="each-media-item" title="{{ $channel->name }}" id="row-{{$channel->hash}}">
                <a href="{{url('class/'.$channel->hash)}}">
                  <img class="img-responsive" src="{{url('/includes/user_pic.php?picid='.$channel->hash.'&s=1&p='.$channel->pic_path)}}" alt="{{ $channel->name }}"/>
                  <h2>{{ $channel->name }}</h2>
                </a>
                <div class="col-xs-12 btn-unlike btn btn-hobby" onclick="follow_media('{{$channel->hash}}')">دنبال نمی کنم</div>
              </li>
            @endforeach
          </ul>
          @endif
        </div>
        <div id="them-set" class="tab-content col-xs-12">
          @if(count($data['followed'])<1)
            <div class="alert alert-hobby">کسی کانال شما را دنبال نکرده است.</div>
          @else
            <ul class="media-list">
            @foreach($data['followed'] as $follow)
              <?php $channel = $follow->userChannel;?>
              <li class="each-media-item" title="{{ $channel->name }}" id="row-{{$channel->hash}}">
                <a href="{{url('class/'.$channel->hash)}}">
                  <img class="img-responsive" src="{{url('/includes/user_pic.php?picid='.$channel->hash.'&s=1&p='.$channel->pic_path)}}" alt="{{ $channel->name }}"/>
                  <h2>{{ $channel->name }}</h2>
                </a>
              </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>
@endsection
