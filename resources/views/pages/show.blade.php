@extends('layouts.default')

<?php $whereAmI='show-media' ?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
  {{ $data['mainmedia']->title }} | HOBBY9
@stop

@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/show.css')?>" type="text/css">
@if($data['mainmedia']->type == 1)
  <link rel="stylesheet" href="<?php echo URL::asset('css/videoplayer.css')?>" type="text/css">
@endif
<link rel="stylesheet" href="<?php echo URL::asset('player/build/mediaelementplayer.min.css')?>" />
<link rel="stylesheet" href="<?php echo URL::asset('player/plugins/dist/ads/ads.min.css')?>" />
@stop

@section('script')
  {!!$data['schema']!!}
  <script src="<?php echo URL::asset('js/showmedia.js')?>" ></script>
  <script src="<?php echo URL::asset('player/build/mediaelement-and-player.min.js')?>" type="text/javascript"></script>
  <script src="<?php echo URL::asset('player/plugins/dist/ads/ads-i18n.js')?>" type="text/javascript"></script>
  <script src="<?php echo URL::asset('player/plugins/dist/ads/ads.min.js')?>" type="text/javascript"></script>
  <script src="<?php echo URL::asset('player/plugins/dist/ads-vast-vpaid/ads-vast-vpaid.min.js')?>" type="text/javascript"></script>
  <script type="text/javascript">
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
@stop

@section('content')
  <div class="container-hobby main-div">
  		<div class="row">
  			<div>
  					<!--START MEDIA DIV-->
  					<div class="media-div col-xs-12 col-sm-7">
              <?php $media = $data['mainmedia'] ?>
  						<div>
  							<h1>{{ $media->title }}</h1>
  							<!--<div class="btn btn-default" id="expand-btn" title="نمایش بزرگ"><i class="fa fa-expand" aria-hidden="true"></i></div>-->
                @if ($media->type == 1)
                 <div class="btn btn-default" id="lamp-btn" title="تاریک کردن"><i class="fa fa-lightbulb-o" aria-hidden="true"></i></div>
                @endif
                <div class="row sender-div">ارسال شده توسط <a href="{{url('class/'.$media->user->hash)}}">{{ $media->user->name }}</a> {{ \App\Http\handyHelpers::MTS($media->endate) }} </div>
  						</div>
              @if ($media->type == 1)
				        <div class="tv">
                <video id="container" src="{{$data['path']}}" poster="{{url('includes/returnpic.php?s=1&type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}" width="100%" height="550px" preload="none"></video>
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
          				    pluginPath: '<?php echo URL::asset('player/build/')?>/',
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
    						            });
        						    });
          			    	}
            				});
        				</script>
  							<img alt="tv holder" src="{{URL::asset('css/images/tv.png')}}" class="img-responsive">
  						</div>
              @elseif ($media->type == 2)
                <img src="{{url('includes/returnpic.php?s=1&type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}" class="thumb-img ebook-thumb">
              @elseif ($media->type == 3)
                <img src="{{url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}&s=@if($media->type == 2 ) 3 @else 2 @endif" class="thumb-img mp3-thumb">
                  <a id="current-radio" href="{{url('radio')}}" class="current-radio">
                    <div><i class="fa fa-microphone" aria-hidden="true"></i> در حال پخش در رادیو... <br>
                    آهنگ {{$data['currentRadio']['artist']}} از {{$data['currentRadio']['title']}}</div>
                  </a>
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
  					<!--END MEDIA DIV-->
  					<!--START LEFT COLUMN-->

  					<div class="right-div col-sm-5">
  						<div class="ads-div">
  							<div class="showfile-ads">@if(@$data['ads']['showmedia_left_ad']){!! $data['ads']['showmedia_left_ad'] !!} @endif</div>
  							<div class="showfile-ads">@if(@$data['ads']['showmedia_mid_ad']){!! $data['ads']['showmedia_mid_ad'] !!} @endif</div>
                <div class="showfile-ads">@if(@$data['ads']['showmedia_right_ad']){!! $data['ads']['showmedia_right_ad'] !!} @endif</div>
              </div>
  						<div>
  							<ul class="nav nav-tabs left-media-tab">
  							  <li c-data="class-media" onclick="if(change_tab(this)){load_group('{{$media->class}}');}"><a href="#">دیگر رسانه های کانال</a></li>
  							  <li c-data="similar-media" onclick="change_tab(this);" class="active"><a href="#">رسانه های مشابه</a></li>
  							</ul>
  						</div>
              <div id="similar-media">
                  <div class="left-media-list">
      							<ul>
                      <?php $i = 0;?>
                      @foreach($data['similar'] as $value)
                        @if ($value->hash == $media->hash)
                          @continue;
                        @endif
                        @if($i == 15)
                          @break;
                        @endif
                        <li title="{{ $value->title }}">
            							<a href="{{url('s/'.$value->hash)}}/{{\App\Http\handyHelpers::UE($value->title)}}">
            								<div class="media-left-img">
            									<img height="110" alt="{{ $value->title }}" src="{{url('includes/returnpic.php?type='.$value->type.'&picid='.$value->hash.'&p='.$value->path)}}&s=@if($value->type == 2 ) 3 @else 2 @endif" class="img-responsive">
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
            								</div>
            							</a>
            							<div class="media-left-info">
            								<h2 title="{{ $value->title }}"><a href="{{url('/s/'.$value->hash)}}/{{\App\Http\handyHelpers::UE($value->title)}}">{{ $value->title }}</a></h2>
            								<div>از <a href="{{url('/class/'.$value->user->hash)}}">{{ $value->user->name }}</a></div>
            								<div>بازدید : {{ \App\Http\handyHelpers::ta_persian_num($value->visit) }}</div>
            							</div>
            							<div style="clear:both"></div>
            						</li>
                        <?php $i++ ;?>
                      @endforeach
    				        </ul>
                  </div>
              </div>
              <div id="class-media">
                <div class="loading-wrapper">
                  <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                  <span class="sr-only">Loading...</span>
                  <p>در حال بارگذاری ...</p>
                </div>
              </div>
  				</div>
  					<!--END LEFT COLUMN-->
  					<!--START MEDIA STAFF-->
			<div class="media-staff-div col-xs-12 col-sm-7">
          <div>
						<div class="btns-media-option">
	            <a href="{{url('dl/'.$media->hash)}}" title="دانلود این رسانه" class="btn btn-default"><span class="btns-text-staff">دانلود</span>&nbsp;<i style="color:green" class="fa fa-download" aria-hidden="true"></i></a>
	            <a href="{{url('report/'.$media->hash)}}" title="گزارش تخلف" class="btn btn-default"><span class="btns-text-staff">گذارش</span>&nbsp;<i style="color:#d9534f" class="fa fa-exclamation" aria-hidden="true"></i></a>
	            <div id="btn-exp" title="مشخصات این رسانه" class="btn btn-default"><span class="btns-text-staff">مشخصات</span>&nbsp;<i style="color:#5bc0de" class="fa fa-info-circle" aria-hidden="true"></i></div>
              @if ($media->type !== 2)
                <div id="btn-code" title="دریافت کد برای قرار دادن در سایت یا وبلاگ" class="btn btn-default"><span class="btns-text-staff">کد رسانه</span>&nbsp;<span>&lt;&gt;</span></div>
              @endif
            </div>
		        <div class="btns-navbar view-num-div">
		            <div title="تعداد بازدید" class="btn btn-default">{{ \App\Http\handyHelpers::ta_persian_num($media->visit) }} &nbsp;<span class="btns-text-staff">بازدید</span> &nbsp;&nbsp;<i class="fa fa-eye" aria-hidden="true"></i></div>
		            <div @if($data['isFollowed'])id="btn-unlike" @else id="btn-like" @endif @if($data['isFollowed'])  title="نمی پسندم" @else title="پسندیدن" @endif  class="btn btn-default" onclick="follow_media('{{$media->hash}}');"><span class="btns-text-staff">@if($data['isFollowed']) نمی پسندم @else پسندیدن @endif</span>&nbsp;<i @if($data['isFollowed']) style="color:red" @endif class="fa @if($data['isFollowed']) fa-heart  @else fa-heart-o @endif" aria-hidden="true"></i></div>
		        </div>
            <div class="clear"></div>
          </div>

          @if ($media->type !== 2)
            <div id="hide-code" class="hide-content">
              <div>
                <ul class="nav nav-tabs code-tabs">
                  <li id="iframe-code"><a onclick="return false" href="#">کد iframe</a></li>
                  <li id="script-code" class="active"><a onclick="return false" href="#">کد script</a></li>
                </ul>
                <div class="code-tab-content">
                  <div class="iframe-content col-xs-12">
                    <textarea id="media-embed-code-iframe" onclick="select();" spellcheck="false" class="form-control">
                      @if($media->type == 1)
                        <iframe src="{{url('/video/embed/hash/'.$media->hash.'/mt/frame')}}" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="640" height="360" ></iframe>
                      @elseif ($media->type == 3)
                        <iframe style="border:none;" src="{{url('/music/embed/hash/'.$media->hash.'/mt/frame')}}" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="640" height="30" ></iframe>
                      @endif
                    </textarea>
                  </div>
                  <div class="script-content col-xs-12">
                    <textarea id="media-embed-code-script" onclick="select();" spellcheck="false" class="form-control">
                      @if($media->type == 1)
                        <div id="{{$media->hash}}"><script type="text/JavaScript" src="{{url('/video/embed/hash/'.$media->hash.'/mt/script/640/')}}"></script></div>
                      @elseif ($media->type == 3)
                        <div id="{{$media->hash}}"><script type="text/JavaScript" src="{{url('/music/embed/hash/'.$media->hash.'/mt/script/640/')}}"></script></div>
                      @endif
                    </textarea>
                  </div>
                  <div class="code-size">
                    <div class="inner-title">اندازه</div>
                    @if($media->type == 1)
      	        			<label>طول : px </label><input hash="{{$media->hash}}" media="{{$media->type}}" id="code-width" class="form-control input-sm" type="text" value="640" />
      	        			<label>ارتفاع : px </label><input id="code-height" class="form-control input-sm" type="text" value="360" />
                    @elseif ($media->type == 3)
                      <label>طول : px </label><input hash="{{$media->hash}}" media="{{$media->type}}" id="code-width" class="form-control input-sm" type="text" value="640" />
                    @endif
    	        		</div>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          @endif
          <div id="hide-code" class="hide-content">
            <div>
            	<input type="hidden" id="file-hash" value="0ce9543f041fb5df9ea84a86a00dd0c9">
            	<div class="hide-code-link"><a onclick="return false;" id="iframe-code" href="#" iframeurl="<iframe src=&quot;http://www.hobby9.com/video/embed/hash/0ce9543f041fb5df9ea84a86a00dd0c9/mt/frame&quot; allowFullScreen=&quot;true&quot; webkitallowfullscreen=&quot;true&quot; mozallowfullscreen=&quot;true&quot; width=&quot;640&quot; height=&quot;360&quot; ></iframe>">دریافت کد iframe</a> | <a onclick="return false;" href="#" id="script-code" scripturl="<div id=&quot;0ce9543f041fb5df9ea84a86a00dd0c9&quot;><script type=&quot;text/JavaScript&quot; src=&quot;http://www.hobby9.com/video/embed/hash/0ce9543f041fb5df9ea84a86a00dd0c9/mt/script/640/&quot;></script></div>">دریافت کد script</a></div>
            	<textarea id="media-embed-code" onclick="select();" spellcheck="false" class="form-control">&lt;iframe src="http://www.hobby9.com/video/embed/hash/0ce9543f041fb5df9ea84a86a00dd0c9/mt/frame" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="640" height="360" &gt;&lt;/iframe&gt;</textarea>
            	<div class="code-size">
            		<div class="inner-title">اندازه</div>
            		<label>طول : px </label><input id="code-width" class="form-control input-sm" type="text" value="640">
            		<label>ارتفاع : px </label><input id="code-height" class="form-control input-sm" type="text" value="360">
            	</div>
            	<a href="#">توضیحات در مورد کد مورد نظر و نحوی استفاده</a><i style="color:#5bc0de" class="fa fa-info-circle" aria-hidden="true"></i>
           </div>
          </div>



          <div id="hide-exp" class="hide-content">
          	<div class="btns-navbar">
          		<ul class="list-group">
          			<li><i class="fa fa-book" aria-hidden="true"></i> ناشر : {{$media->publisher}}</li>
          			<li><i class="fa fa-bookmark" aria-hidden="true"></i> خالق : {{$media->creator}}</li>
          			<li><i class="fa fa-hdd-o" aria-hidden="true"></i> حجم :
                  {{ \App\Http\handyHelpers::makeSize($media->volume) }}
                </li>
          			<li><i class="fa fa-clock-o" aria-hidden="true"></i>
                @if($media->type == 2)
                  {{ \App\Http\handyHelpers::ta_persian_num($media->pagetime) }} صفحه
                @else
                  زمان :
                  {{ \App\Http\handyHelpers::makeTimeString($media->pagetime) }}
                @endif
                </li>
          			<li><i class="fa fa-flag-o" aria-hidden="true"></i> زبان :
                  @if($media->lang == 'فارسی')
                    فارسی
                  @else
                    غیر فارسی
                  @endif
                </li>
          		</ul>
          	</div>
          </div>

			        <div class="tag-div">
				        <a href="{{ url('/group/'.$media->branch) }}" class="btn btn-info">{{ $media->branch }} &nbsp;&nbsp;<i class="fa fa-flag-o" aria-hidden="true"></i></a>
                @foreach($data['tags'] as $tag)
                  <a href="{{ url('search?text='.$tag) }} " class="btn btn-default"> {{ $tag }} &nbsp;&nbsp;<i class="fa fa-tag" aria-hidden="true"></i></a>
                @endforeach
              </div>
			        <div class="share-div">
  							<div><a href="http://www.cloob.com/share/link/add?url={{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}&title={{$media->title}}"><img alt="اشتراک گذاری روی شبکه اجتماعی cloob" src="{{ URL::asset('css/images/cloob.png')}}"></a></div>
  							<div><a href="http://www.facebook.com/share.php?u={{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}&t={{$media->title}}"><img alt="اشتراک گذاری روی شبکه اجتماعی facebook" src="{{ URL::asset('css/images/face.png')}}"></a></div>
  							<div><a href="https://plus.google.com/share?url={{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}"><img alt="اشتراک گذاری روی شبکه اجتماعی google plus" src="{{ URL::asset('css/images/g+.png')}}"></a></div>
  							<div><a href="http://twitter.com/home?status={{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}:{{$media->title}}"><img alt="اشتراک گذاری روی شبکه اجتماعی twitter" src="{{ URL::asset('css/images/twitter.png')}}"></a></div>
                <div><a href="https://t.me/share/url?url={{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}"><img alt="اشتراک گذاری روی تلگرام" src="{{ URL::asset('css/images/telegram.png')}}"></a></div>
              </div>

  						<div class="row exp-div">
  							<div class="inner-title">توضیحات</div>
                  @if(empty($media->explenation))
                  	<div class="well well-sm">
                      توضیحاتی برای رسانه {{ $media->title }} درج نشده است.
                    </div>
                  @else
                    @if(strlen($media->explenation) > 800)
                      <p id="exp-this">{!!mb_substr($media->explenation,0,800)!!}...&nbsp;&nbsp;&nbsp;&nbsp;<span class="expand-exp" onclick="expand_showfile();" id="show-expand-this">نمایش ادامه توضیحات</span></p>
    		        			<p style="display:none;" id="exp-expand-this">{{$media->explenation}}</p>
                    @else
                      <p>{{$media->explenation}}</p>
                    @endif
                  @endif
  						</div>
            	<hr class="style-two row">
            	<div class="gplus btns-navbar">
                  <div class="btn btn-default">
                         <div class="g-plusone" data-size="small" data-annotation="none" data-href="{{url('s/'.$media->hash)}}/{{\App\Http\handyHelpers::UE($media->title)}}"></div>
                         <p>حمایت از این مطلب</p>
                   </div>

                  <div class="btn btn-default">
                          <div class="g-plusone" data-size="small" data-annotation="none" data-href="{{url('/')}}"></div>
                          <p>حمایت از سایت</p>
                  </div>
              </div>
              <div class="inner-title">نظرات</div>
              @if (Auth::check())
                @if ($media->soflag == 'b' || $media->soflag =='c')
                  <div class="comment-form">
      							<form>
      								<div class="comment-text">
      									<div>ارسال نظر</div>
      									<textarea id="com-text" name="des" class="form-control"></textarea>
      									<input id="com-code" placeholder="کد امنیتی" required="required" class="form-control" type="text" name="code"/>
      									<input type="hidden" id="com-file" value="{{$media->hash}}">
      									<img id="code-pic" src="{{captcha_src()}}" />
      									<input type="submit" onclick="send_comment();return false;" class="btn btn-success" value="ارسال">
      								</div>
      							</form>
      						</div>
      						<hr class="style-two row" />
                @else
                  <div class="well well-sm"><p>امکان ارسال نظر برای این رسانه توسط مالک آن مسدود شده است.</p></div>
                @endif
              @else
                <div class="well well-sm"><p>برای ارسال نظر ابتدا از <a href="{{url('register')}}">اینجا</a> عضو شوید و در صورت عضو بودن از <a href="{{url('profile/login/')}}">اینجا</a> وارد شوید.</p></div>
              @endif

              @if ($data['comments'] && count($data['comments'])>0)
                <div id="comments-list" class="comments-list">
                  @foreach ($data['comments'] as $comment)
                    <div class="comment" id="item-list-{{$comment->hash}}">
          						<small class="comment-date">{{ \App\Http\handyHelpers::MTST($comment->m_date) }}</small>
          						<a class="media-right" href="{{url('class/'.$comment->user_hash)}}">
          							<img src="{{url('/includes/user_pic.php?picid='.$comment->user->hash.'&s=2&p='.$comment->user->pic_path)}}" /><!-- user pic -->
          							<h4 class="media-heading user_name">{{$comment->user->name}}</h4>
          						</a>
          						<div class="media-body">
          							<p>{{$comment->comment}}</p>
          						</div>
                      <div class="comment-btns btns-navbar">
                        <div title="گزارش تخلف" class="btn btn-default" id="btn-rep-{{$comment->hash}}" onclick="rep_comment('{{$comment->hash}}')"><i style="color:#d9534f" class="fa fa-exclamation" aria-hidden="true"></i></div>
                        @if (Auth::check())
                          @if (Auth::user()->hash == $comment->user_hash)
                            <div title="حذف این کامنت" onclick="del_comment('{{$comment->hash}}')"  id="btn-del-{{$comment->hash}}" class="btn btn-default"><i class="fa fa-trash-o" aria-hidden="true"></i></div>
                          @endif
                        @endif
                      </div>
                      <div class="clear"></div>
          					</div>
                  @endforeach
                </div>
                <div class="pagination">@include('pages.pagination', ['object' => $data["comments"]])</div>
              @else
                <div id="msg-no-comment" class="well well-sm"><p>نظری ارسال نشده است.</p></div>
              @endif
  					<!--END MEDIA STAFF-->
  		</div>
    </div>
  </div>
  <div class="dark-div"></div>
  <div class="popup">
		<a href="https://t.me/mr_sun69">این وب سایت به فروش می رسد در صورت تمایل با ای دی @mr_sun69 در تلگرام تماس بگیرید.</a>
	</div>
@stop
