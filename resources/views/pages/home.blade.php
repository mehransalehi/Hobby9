@extends('layouts.default-home')

@section('metashare')
  <meta name="google-site-verification" content="Ygnh-fr_5Z-b_1tM83AX4MEJAM0OTXFxaw7pknqmz-4" />
  <meta name="samandehi" content="260758140"/>
@endsection
<?php $whereAmI='home' ?>
@section('style')
  <link rel="stylesheet" href="<?php echo URL::asset('css/home.css')?>" type="text/css">
  @if(@$data['mainmedia'][0]->type == 1)
    <link rel="stylesheet" href="<?php echo URL::asset('css/videoplayer.css')?>" type="text/css">
  @endif
  <link rel="stylesheet" href="<?php echo URL::asset('player/build/mediaelementplayer.min.css')?>" />
  <!--special style-->
  <style>
  @if(@$data['special'])
  	@foreach($data['special'] as $value)
  		.special-hr-{{ $value->id }}{
  		    border: 0;
  		    height: 1px;
  		    clear:both;
  		    margin:@margin auto;
  		   /* IE10 Consumer Preview */
  				background-image: -ms-linear-gradient(left, transparent  0%, #{{ $value->hr_color }} 50%, transparent  100%);
  				/* Mozilla Firefox */
  				background-image: -moz-linear-gradient(left, transparent  0%, #{{ $value->hr_color }} 50%, transparent  100%);
  				/* Opera */
  				background-image: -o-linear-gradient(left, transparent  0%, #{{ $value->hr_color }} 50%, transparent  100%);
  				/* Webkit (Safari/Chrome 10) */
  				background-image: -webkit-gradient(linear, left top, right top, color-stop(0, transparent ), color-stop(0.5, #{{ $value->hr_color }}), color-stop(1, transparent ));
  				/* Webkit (Chrome 11+) */
  				background-image: -webkit-linear-gradient(left, transparent  0%, #{{ $value->hr_color }} 50%, transparent  100%);
  				/* W3C Markup, IE10 Release Preview */
  				background-image: linear-gradient(to right, transparent  0%, #{{ $value->hr_color }} 50%, transparent  100%);
  		}
  		.special-div-{{ $value->id }}{
  			background-color:#{{ $value->back_color }};
  			border:1px solid #{{ $value->border_color }};
  			color:#{{ $value->text_color }};
  		}
  		.special-div-{{ $value->id }} .top-page-icon{
  			color:#{{ $value->icon_color }};
  			font-size: 30px !important;
      	padding: 0 0 18px 0 !important;
  		}
  	@endforeach
  @endif
  @if(@$data['lists'])
  	@foreach($data['lists'] as $listM)
  		<?php $list = $listM['list']; ?>
  		.home-group-{{ $list->id }}{
  			@if($list->background_image_link != 'transparent' && empty($list->background_image_link))
  				background-color:#{{ $list->back_color }};
  			@elseif($list->background_image_link != 'transparent')
  				background-image:url({{ $list->background_image_link }});
  			@endif
  			padding: 0px 0;
  		}
  		.home-group-{{ $list->id }} .group-header , .home-group-{{ $list->id }} .group-more a{
  			color:#{{ $list->title_color }};
  		}
  		.home-group-{{ $list->id }} .group-more a:hover{
  			color:#{{ $list->title_hover }};
  		}
  		.home-group-{{ $list->id }} .spacer{
  			border: 0;
  			height: 1px;
  			clear:both;
  			margin:@margin auto;
  		 /* IE10 Consumer Preview */
  			background-image: -ms-linear-gradient(left, transparent  0%, #{{ $list->hr_color }} 50%, transparent  100%);
  			/* Mozilla Firefox */
  			background-image: -moz-linear-gradient(left, transparent  0%, #{{ $list->hr_color }} 50%, transparent  100%);
  			/* Opera */
  			background-image: -o-linear-gradient(left, transparent  0%, #{{ $list->hr_color }} 50%, transparent  100%);
  			/* Webkit (Safari/Chrome 10) */
  			background-image: -webkit-gradient(linear, left top, right top, color-stop(0, transparent ), color-stop(0.5, #{{ $list->hr_color }}), color-stop(1, transparent ));
  			/* Webkit (Chrome 11+) */
  			background-image: -webkit-linear-gradient(left, transparent  0%, #{{ $list->hr_color }} 50%, transparent  100%);
  			/* W3C Markup, IE10 Release Preview */
  			background-image: linear-gradient(to right, transparent  0%, #{{ $list->hr_color }} 50%, transparent  100%);
  		}
  		.home-group-{{ $list->id }} a>h2{
  			color:#{{ $list->text_color }};
  		}
  		.home-group-{{ $list->id }} a>h2:hover{
  			color:#{{ $list->text_hover }};
  		}
  	@endforeach
  @endif
  </style>
@stop
@section('script')
  <script type="application/ld+json">
    {
       "@context": "http://schema.org",
       "@type": "WebSite",
       "url": "http://www.hobby9.com/",
       "potentialAction": {
            "@type": "SearchAction",
            "target": "http://www.hobby9.com/search/text?str={search_term_string}",
            "query-input": "required name=search_term_string"
       }
    }
  </script>
    <script type="text/javascript">
    function sticky_relocate() {
      var window_top = $(window).scrollTop();
      var div_top = $('#current-radio').offset().top;
      if (window_top > 430) {
          $('#current-radio').attr('class','current-radio-fixed');
      } else {
          $('#current-radio').attr('class','current-radio');
      }
    }

    $(function() {
      $(window).scroll(sticky_relocate);
      sticky_relocate();
    });
    </script>
	<script src="<?php echo URL::asset('js/home.js')?>"></script>
	<script src="<?php echo URL::asset('player/build/mediaelement-and-player.min.js')?>" type="text/javascript"></script>
  <script src="<?php echo URL::asset('player/jwplayer.js')?>" type="text/javascript"></script>
@stop

@section('content')
  <div class="home-content">
	@if(@$data['special'])
		<div class="top-pages">
			<div class="group-header">صفحه های برتر</div>
			<hr class="spacer"/>
		@foreach($data['special'] as $value)
			<a href="{{ $value->link }}">
			<div class="top-page-elem special-div-{{ $value->id }}">
				<div class="top-page-icon fa {{ $value->icon }}"></div>
				<hr class="special-hr-{{ $value->id }}"/>
				<div class="top-page-text">{{ $value->text }}</div>
			</div>
			</a>
		@endforeach
		</div>
	@endif
	@if(@$data['top-part'])
	<div class="container home-page-top">
    <div class="home-page-top-left col-md-6">
      <ul class="media-on-thumbs">
        @if (array_key_exists('mainmedia',$data) && count($data['mainmedia'])>1)
          <?php $z=1;?>
          @foreach ($data['mainmedia'] as $value)
              @if ($z==5)
                @break
              @endif
            <li title="{{ $value->title }}" class="media-each-thumbnail col-xs-6">
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
            <?php $z++; ?>
          @endforeach
        @elseif (array_key_exists('similar',$data))
          <?php $z=1;?>
          @foreach ($data['similar'] as $value)
            @if ($z==5)
              @break
            @endif
            <li title="{{ $value->title }}" class="media-each-thumbnail col-xs-6">
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
            <?php $z++; ?>
          @endforeach
        @endif
      </ul>
    </div>
    <div class="home-page-top-right col-md-6">
      <div class="player-div">
  			<?php $mainmedia = $data['mainmedia'][0];?>
  			<h2><a href="{{url('s/'.$mainmedia->hash.'/'.\App\Http\handyHelpers::UE($mainmedia->title))}}">{{ $mainmedia->title }}</a></h2>
  			@if($mainmedia->type == 1)
  				<video id="container" src="{{$data['path']}}" poster="{{url('includes/returnpic.php?s=1&type='.$mainmedia->type.'&picid='.$mainmedia->hash.'&p='.$mainmedia->path)}}" width="100%" height="340px" preload="none"></video>
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
  			@elseif ($mainmedia->type == 2)
  				<img src="{{url('includes/returnpic.php?s=1&type='.$mainmedia->type.'&picid='.$mainmedia->hash.'&p='.$mainmedia->path)}}" class="thumb-img ebook-thumb">
  			@elseif ($mainmedia->type == 3)
  				<img src="{{url('includes/returnpic.php?type='.$mainmedia->type.'&picid='.$mainmedia->hash.'&p='.$mainmedia->path)}}&s=@if($mainmedia->type == 2 ) 3 @else 2 @endif" class="thumb-img mp3-thumb">
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
    </div>

		<div class="clear"></div>
	</div>
	@endif
  <div class="container home-page-notify">
    <div class="col-md-6 home-page-notify-right">
      <a target="_blank" title="رادیو اینترنتی ما" href="{{url('/radio')}}" class="radio-notify"><i class="fa fa-microphone" aria-hidden="true"></i></a>
      <a title="جدیدترین موسیقی ها و ویدیو موزیک ها در کانال ما" href="https://t.me/hobby9_radio" class="radio-notify"><i class="fa fa-telegram" aria-hidden="true"></i></a>
      <a title="خرید پوشاک لوکس" href="http://shop.hobby9.com" class="radio-notify"><i class="fa fa-shopping-bag" aria-hidden="true"></i></a>
    </div>
    <div class="col-md-6 home-page-notify-left cl-effect-13">
      <div class="news-title">اخبار / </div>
      @if($data['news'])
        <?php $i=0;?>
        @foreach ($data['news'] as $news)
          @if($i == 3)
            @break
          @endif
          <a href="{{url('blog/'.$news->faid.'/'.$news->id)}}">{{$news->text_title}}</a>
          <?php $i++?>
        @endforeach
      @endif
    </div>
    <div class="clear"></div>
  </div>
	<div class="container-fluid main-lists">
	<!-- Show Media-->
	@if(@$data['lists'])
		@foreach($data['lists'] as $listM)
			<?php $list = $listM['list']; ?>
			<div class="home-group-{{ $list->id }}">
				<div class="container-hobby">
					<div class="group-header">{{ $list->title }}</div>
					<div class="group-more"><a href="{{ url('branch/'.$list->id.'/'.$list->title) }}">بیشتر <i class="fa fa-chevron-left" aria-hidden="true"></i></a></div>
					<hr class="spacer"/>
					<div class="new-media">
						<ul class="list-thumbs">
						@foreach($listM['media'] as $media)
							<li class="main-each-thumbnail @if($media->type == 2 || $media->type == 3) add-margin-bot @endif">
							  <a href="{{url('s/'.$media->hash.'/'.\App\Http\handyHelpers::UE($media->title))}}" title="{{ $media->title }}">
									<img alt="{{ $media->title }}" src="{{url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}&s=@if($media->type == 2 ) 3 @else 2 @endif" class="img-responsive">
									<h2>{{ $media->title }}</h2>
							    <span class="duration">
										@if($media->type == 2)
											{{ \App\Http\handyHelpers::ta_persian_num($media->pagetime) }} صفحه
										@else
											{{ \App\Http\handyHelpers::makeTimeString($media->pagetime) }}
										@endif
									</span>
							  </a>
							</li>
						@endforeach
						</ul>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		@endforeach
	@endif
	<!-- End Show Media-->
	</div>
</div>
<div class="popup">
  <a href="https://t.me/mr_sun69">این وب سایت به فروش می رسد در صورت تمایل با ای دی @mr_sun69 در تلگرام تماس بگیرید.</a>
</div>
<a id="current-radio" href="{{url('radio')}}" class="current-radio">
  <div><i class="fa fa-microphone" aria-hidden="true"></i> در حال پخش در رادیو... <br>
  آهنگ {{$data['currentRadio']['artist']}} از {{$data['currentRadio']['title']}}</div>
</a>
@stop
