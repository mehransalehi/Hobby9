@extends('layouts.default')

<?php $whereAmI='channel' ?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('description')
  <meta name="description" content="{{$data['user']->name}} : {{$data['user']->des}}">
@endsection
@section('keywords')
  <meta name="keywords" content="{{$data['user']->name}}">
@endsection
@section('title')
  کانال {{$data['user']->name}}
@stop
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/channel.css')?>" type="text/css">
@if(@$data['top']->type == 1)
  <link rel="stylesheet" href="<?php echo URL::asset('css/videoplayer.css')?>" type="text/css">
@endif
<link rel="stylesheet" href="<?php echo URL::asset('player/build/mediaelementplayer.min.css')?>" />
@stop

@section('script')
  <script src="<?php echo URL::asset('player/build/mediaelement-and-player.min.js')?>" type="text/javascript"></script>
  <script src="<?php echo URL::asset('player/jwplayer.js')?>" type="text/javascript"></script>
  <script>
  $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
    });
    $(document).ready(function(){
      // Get the modal
      var modal = document.getElementById('myModal');

      // Get the button that opens the modal
      var btn = document.getElementById("myBtn");

      // Get the <span> element that closes the modal
      var span = document.getElementsByClassName("close")[0];

      // When the user clicks the button, open the modal
      btn.onclick = function() {
          modal.style.display = "block";
      }

      // When the user clicks on <span> (x), close the modal
      span.onclick = function() {
          modal.style.display = "none";
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
          if (event.target == modal) {
              modal.style.display = "none";
          }
      }
    });

    function follow_media(hash)
    {
        if($("#btn-like").length)
        {
            var data = {"media":hash,"type":"class"};
            sendCommand(data,"followmedia");
        }
        else if($("#btn-unlike").length)
        {
            var data = {"media":hash,"type":"class"};
            sendCommand(data,"unfollowmedia");
        }
    }
    function doResponse(result,command)
    {
        console.log(result);
        var object = $('<div/>').append(result);
        var msg = $(object).find('#message').html();
        if(command == "followmedia")
        {
            resFoMedia(object,msg);
        }
        else if(command == "unfollowmedia")
        {
            resUnFoMedia(object,msg);
        }
    }
    function resFoMedia(object,msg)
    {
        var myStatus;
        myStatus = $(object).find('#status').html();
        if(myStatus == "faild")
        {
            notify(msg,'error');
        }
        else if(myStatus == "success")
        {
            $(".fa-thumbs-o-up").removeClass("fa-thumbs-o-up").addClass("fa-thumbs-o-down");
            $("#btn-like span").attr("title","دنبال نکردن");
            $("#btn-like").attr("id","btn-unlike");
            notify(msg,'notice');
        }
    }
    function resUnFoMedia(object,msg)
    {
        var myStatus;
        myStatus = $(object).find('#status').html();
        if(myStatus == "faild")
        {
            notify(msg,'error');
        }
        else if(myStatus == "success")
        {
            $(".fa-thumbs-o-down").removeClass("fa-thumbs-o-down").addClass("fa-thumbs-o-up");
            $("#btn-unlike span").attr("title","دنبال کردن");
            $("#btn-unlike").attr("id","btn-like");
            notify(msg,'notice');
        }
    }
  </script>
@endsection

@section('content')
  <?php $user = $data['user'];?>
  <div class="container-fluid channel-header">
    <div class="container channel-head-content">
      <div class="channel-right-head col-sm-6">
        <img src="{{url('/includes/user_pic.php?picid='.$user->hash.'&s=1&p='.$user->pic_path)}}"/>
        <h1>{{$user->name}}</h1>
        @if(!empty($user->link))<a href="{{$user->link}}"><i class="fa fa-link fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{$user->link}}</a>@endif
      </div>
      <div class="channel-header-right col-sm-6">
        <div class="channel-header-right-top">
          <a href="{{url('rss/'.$user->hash)}}">
            <span title="خوراک" class="fa-stack fa-lg">
              <i class="rss-button fa fa-circle fa-stack-2x"></i>
              <i class="fa fa-rss fa-stack-1x fa-inverse"></i>
            </span>
          </a>
          @if(Auth::check() && Auth::user()->hash != $user->hash)
            |
            <a href="#" @if(@$data['follow']) id="btn-unlike" @else id="btn-like" @endif onclick="follow_media('{{$user->hash}}');return false;">
              <span @if(@$data['follow']) title="دنبال نکردن" @else title="دنبال کردن" @endif class="follow-btn fa-stack fa-lg">
                <i class="follow-button fa fa-circle fa-stack-2x"></i>
                <i class="fa @if(@$data['follow']) fa-thumbs-o-down @else fa-thumbs-o-up @endif  fa-stack-1x fa-inverse"></i>
              </span>
            </a>
            |
            <span id="myBtn" title="ارسال پیام" class="msg-btn fa-stack fa-lg">
              <i class="msg-button fa fa-circle fa-stack-2x"></i>
              <i class="fa fa-envelope-open-o fa-stack-1x fa-inverse"></i>
            </span>
          @endif
        </div>
        <div class="channel-header-right-bottom">
          @if(!empty($data['setting']->gp_addr))
            <a href="{{$data['setting']->gp_addr}}"><i class="fa fa-google-plus  fa-lg" aria-hidden="true"></i></a>
          @endif
          @if(!empty($data['setting']->fb_addr))
            <a href="{{$data['setting']->fb_addr}}"><i class="fa fa-facebook  fa-lg" aria-hidden="true"></i></a>
          @endif
          @if(!empty($data['setting']->tw_addr))
            <a href="{{$data['setting']->tw_addr}}"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i></a>
          @endif
          |
          <a title="فقط ویدیو ها" href="{{url('/class/'.$user->hash.'/video/')}}"><i class="media-type fa fa-video-camera" aria-hidden="true"></i></a>
          <a title="فقط موسیقی ها" href="{{url('/class/'.$user->hash.'/music/')}}"><i class="media-type fa fa-music" aria-hidden="true"></i></i></a>
          <a title="فقط کتاب ها" href="{{url('/class/'.$user->hash.'/ebook/')}}"><i class="media-type fa fa-book" aria-hidden="true"></i></a>
          <a title="همه" href="{{url('/class/'.$user->hash)}}"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="container-hobby">
    @if(@Session::get('status'))
      <ul>
        @if(@Session::get('status') == 'msg_send')
          <li class="alert alert-success">پیام با موفقیت فرستاده شد.</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif
    @if(count($data['branch'])>0)
      <div class="col-xs-12 col-sm-4 col-md-3">
        <div class="branch-div">
    			<nav id="subnav">
    				<div class="pixel-header">دسته بندی ها</div>
    				<ul class="fa-ul">
    					<li><a class="active" href="#"></a></li>
    					@foreach($data['branch'] as $branch)
    						<li><a href="{{url('/class/'.$user->hash.'/'.$branch->hash)}}"><i class="fa-li fa fa-chevron-left"></i>{{ $branch->name }} <span class="count-branch">{{\App\Http\handyHelpers::ta_persian_num(count($branch->files))}}</span></a></li>
    					@endforeach
    				</ul>
    			</nav>
    		</div>
      </div>
    @endif
		<div class="col-xs-12 @if(count($data['branch'])>0) col-sm-8 col-md-9 @endif search-content">
      @if(count($data['files'])<1)
        <div class="alert alert-hobby">رسانه ای وجود ندارد</div>
      @else
        @if($data['top'])
          <?php $top = $data['top'];?>
          <div class="@if(count($data['branch'])<0) container @endif top-media-div">
            <h2 itemprop="name"><a href="{{url('s/'.$top->hash.'/'.\App\Http\handyHelpers::UE($top->title))}}">{{ $top->title }}</a></h2>
            @if ($top->type == 1)
              <div class="tv">
              <video id="container" src="{{$data['path']}}" poster="{{url('includes/returnpic.php?s=1&type='.$top->type.'&picid='.$top->hash.'&p='.$top->path)}}" width="100%" height="490px" preload="none"></video>
              <div id="complete-div" class="monitor similar-on">
                <div class="btn btn-default btn-replay" title="پخش مجدد">پخش مجدد <span class="icon repeat-ico"></span></div>
                <ul class="media-on-thumbs">
                  @for($i=0;$i<6;$i++)
                    @if (!@$data['similar'][$i])
                      @break
                    @endif
                    <?php $value = $data['similar'][$i];?>
                    <li title="{{ $value->title }}" class="media-each-thumbnail col-xs-4">
                      <a href="{{url('s/'.$value->hash)}}/{{\App\Http\handyHelpers::UE($value->title)}}" target="_blank">
                        <img alt="{{ $value->title }}" src="{{url('includes/returnpic.php?type='.$value->type.'&picid='.$value->hash.'&p='.$value->path)}}&s=@if($value->type == 2 ) 3 @else 2 @endif" class="img-responsive"/>
                      </a>
                      @if($value->type == 1)
                        <span class="media-type"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                      @elseif($value->type == 2)
                        <span class="media-type"><i class="fa fa-book" aria-hidden="true"></i></span>
                      @else
                        <span class="media-type"><i class="fa fa-music" aria-hidden="true"></i></span>
                      @endif
                      <span class="duration">
                        @if($value->type == 2)
                          {{ \App\Http\handyHelpers::ta_persian_num($value->pagetime) }} صفحه
                        @else
                          {{ \App\Http\handyHelpers::makeTimeString($value->pagetime) }}
                        @endif
                      </span>
                      <span class="watch-later"><i class="fa fa-heart-o" aria-hidden="true"></i></span>
                    </li>
                  @endfor
                </ul>
                <div class="on-video-ad">
                </div>
              </div>
              <script>
                $('#container').mediaelementplayer(
                  {
                    enablePluginDebug: false,
                    plugins: ['flash','silverlight'],
                    // path to Flash and Silverlight plugins
                    pluginPath: '<?php echo URL::asset('player/build/')?>/',
                    flashName: 'flashmediaelement.swf',
                    silverlightName: 'silverlightmediaelement.xap',
                    success: function (mediaElement, domObject)
                    {
                      mediaElement.addEventListener('ended', function(e)
                      {
                        $("#complete-div").show();
                      }, false);
                      $(".btn-replay").bind( "click", function()
                      {
                          mediaElement.play();
                          $("#complete-div").hide();
                      });
                      $("video").each(function(index)
                      {
                          $(this).get(0).load();
                          $(this).get(0).addEventListener("canplaythrough", function()
                          {
                              this.play();
                              this.pause();
                          });
                      });
                    }
                  });
              </script>
              <img alt="tv holder" src="{{URL::asset('css/images/tv.png')}}" class="img-responsive">
            </div>
            @elseif ($top->type == 2)
              <img src="{{url('includes/returnpic.php?s=1&type='.$top->type.'&picid='.$top->hash.'&p='.$top->path)}}" class="thumb-img ebook-thumb">
            @elseif ($top->type == 3)
              <img src="{{url('includes/returnpic.php?type='.$top->type.'&picid='.$top->hash.'&p='.$top->path)}}&s=@if($top->type == 2 ) 3 @else 2 @endif" class="thumb-img mp3-thumb">
              <audio class="audioplayer" id="player2" src="{{$data['path']}}" preload="none" type="audio/mp3" controls="controls">
                <p>Your browser leaves much to be desired.</p>
              </audio>
              <script>
                // using jQuery
                $('video,audio').mediaelementplayer({
                  mode:"shim",audioWidth: "100%"
                });
              </script>
            @endif
          </div>
        @endif
        <div class="pagination">@include('pages.pagination', ['object' => $data['files']])</div>
        <ul class="media-list">
        @foreach($data['files'] as $file)
          @if($file->type == 1)
            <li class="each-media-item @if(count($data['branch'])<1) each-media-item-not @else each-media-item-be @endif" title="{{ $file->title }}">
    					<a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
    						<img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=2&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
                <h2>{{ $file->title }}</h2>
                <span class="icon sound-ico"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                <span class="duration">{{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}</span>
    					</a>
    				</li>
          @elseif($file->type == 2)
            <li class="each-media-item @if(count($data['branch'])<1) each-media-item-not @else each-media-item-be @endif" title="{{ $file->title }}">
    					<a href="{{url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title))}}">
    						<img src="{{url('/includes/returnpic.php?type='.$file->type.'&picid='.$file->hash)}}&s=3&p={{ $file->path }}" alt="{{ $file->title }}" class="img-responsive">
                <h2>{{ $file->title }}</h2>
                <span class="icon sound-ico"><i class="fa fa-book" aria-hidden="true"></i></span>
                <span class="duration"> {{ \App\Http\handyHelpers::ta_persian_num($file->pagetime) }} صفحه</span>
    					</a>
    				</li>
          @elseif($file->type == 3)
            <li class="each-media-item @if(count($data['branch'])<1) each-media-item-not @else each-media-item-be @endif" title="{{ $file->title }}">
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
        <div class="pagination">@include('pages.pagination', ['object' => $data['files']])</div>
      @endif
		</div>
	</div>
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <span class="close">&times;</span>
        <h2>ارسال پیام برای این کاربر</h2>
      </div>
      <form action="{{url('/class/'.$user->hash)}}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
          <div class="input-group">
            <textarea id="com-text" name="des" class="form-control"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input id="com-code" placeholder="کد امنیتی" required="required" class="form-control" type="text" name="captcha"/>
            <img id="captcha" src="{{captcha_src()}}" />
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input type="submit" class="btn btn-success" value="ارسال">
          </div>
        </div>
      </form>
    </div>
  </div>
@stop
