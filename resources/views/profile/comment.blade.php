@extends('layouts.default-profile')

@section('title')
  کامنت های ارسالی برای شما
@endsection

<?php $whereAmI='profile-comment' ?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/comment.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">
  $.ajaxSetup({
  headers: {
  	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });
  $(document).ready(function(){
    $(".span-expand").bind( "click",function(){
          var hash = $(this).attr("data-hash");
          $("#unexpand-"+hash).hide();
          $("#expand-"+hash).show();
    });
    $(".media-staff a:first-child").bind( "click", function() {
        $("#reply-form").show();
        var hash = $(this).closest( "li" ).attr("hash-data");
        var file = $(this).closest( "li" ).attr("hash-file");
        $(".media-border").removeClass('media-border-green');
        $(this).closest( ".media-border" ).addClass('media-border-green');
        $("#msg-reply-hash").val(hash);
        $("#msg-reply-file").val(file);
        $("#reply-form").show();
        $("#reply-msg").focus();
    });
  });
  function rep_comment(id)
  {
      $('#btn-rep-'+id).attr("onclick","return false;");
      $('#btn-rep-'+id).prop( "disabled", true );
      var data = {"hash":id};
      sendCommand(data,"repcomment");
  }
  function doResponse(result,command)
  {
      console.log(result);
      var object = $('<div/>').append(result);
      var msg = $(object).find('#message').html();
      if(command == "repcomment")
      {
          resRepCommand(object,msg);
      }
  }
  function resRepCommand(object,msg)
  {
      var myStatus;
      myStatus = $(object).find('#status').html();
      if(myStatus == "faild")
      {
          notify(msg,'error');
      }
      else if(myStatus == "success")
      {
          notify(msg,'notice');
      }
  }
  </script>
@stop

@section('content')
  @if(count($data["comments"])<=0)
    <div class="row">
      <div class="media-div col-xs-12">
        <div class="well well-sm">
          هیچ کامنتی برای شما ارسال نشده است.
        </div>
      </div>
    </div>
  @else
    <div class="pagination">@include('pages.pagination', ['object' => $data["comments"]])</div>
    <div class="row">
			<div class="media-div col-xs-12">
        @if(@Session::get('status'))
          <ul>
            @if(@Session::get('status') == 'save_success')
              <li class="alert alert-success">با موفقیت ثبت شد</li>
            @elseif(@Session::get('status') == 'not_exsit')
              <li class="alert alert-danger">این فایل وجود ندارد</li>
            @endif
          </ul>
        @else
          <ul>
               @foreach ($errors->all() as $error)
                   <li class="alert alert-danger">{{ $error }}</li>
               @endforeach
           </ul>
        @endif
				<ul class="media-lists">
          @foreach ($data["comments"] as $comment)
            <li hash-data="{{$comment->hash}}" hash-file="{{$comment->media->hash}}">
              <div class="media-border">
      					<div class="media-exp">
      						<a href="{{url('s/'.$comment->media->hash.'/'.\App\Http\handyHelpers::UE($comment->media->title))}}" title="{{$comment->media->title}}">
      							<img src="{{url('/includes/returnpic.php?type='.$comment->media->type.'&picid='.$comment->media->hash)}}&s=@if($comment->media->type == 2 ) 3 @else 2 @endif&p={{ $comment->media->path }}" alt="{{ $comment->media->title }}" class="img-thumbnail" height="130px">
                    @if($comment->media->type == 1)
                      <span class="media-type"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                    @elseif($comment->media->type == 2)
                      <span class="media-type"><i class="fa fa-book" aria-hidden="true"></i></span>
                    @else
                      <span class="media-type"><i class="fa fa-music" aria-hidden="true"></i></span>
                    @endif
      							<span class="duration">
                      @if($comment->media->type == 2)
                        {{ \App\Http\handyHelpers::ta_persian_num($comment->media->pagetime) }} صفحه
                      @else
                        {{ \App\Http\handyHelpers::makeTimeString($comment->media->pagetime) }}
                      @endif
                    </span>
      						</a>
      					</div>
      					<div class="media-staff">
                  <a title="پاسخ دادن به این کامنت" onclick="return false;" href="#" style="margin-right:10px"><i class="fa fa-reply" aria-hidden="true" style="margin-left:40px"></i></a>
      						<a id="btn-rep-{{$comment->hash}}" onclick="rep_comment('{{$comment->hash}}');return false;" title="گزارش تخلف برای این کامنت" href=""><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                  <hr class="gray"/>
                  <div>
                  	<p title="تاریخ ایجاد کامنت"> <i class="fa fa-calendar" aria-hidden="true"></i></span>&nbsp;&nbsp; {{ \App\Http\handyHelpers::MTS($comment->m_date) }}</p>
                  </div>
      					</div>
      					<div class="media-exp-mid">
      						<h2>{{$comment->user->owner}} گفته : </h2>
  						      @if(strlen($comment->comment) > 250)
                      <p id="unexpand-{{$comment->hash}}"><?php echo substr($comment->comment,0,251);?>...
                      <span data-hash="{{$comment->hash}}" class="span-expand"> بیشتر </span></p>
                      <p id="expand-{{$comment->hash}}" class="expand-p">{{$comment->comment}}</p>
                    @else
                      <p>{{$comment->comment}}</p>
                    @endif
      					</div>
    					<div style="clear:both"></div>
              </div>
    				</li>
          @endforeach
        </ul>
        <div id="reply-form" class="row well msg-form" style="display:none">
          <form action="{{url('/profile/comment')}}" method="POST">
            {{ csrf_field() }}
            <fieldset>
              <div class="form-group">
                <textarea id="reply-msg" name="text" placeholder="متن پاسخ" class="form-control main-msg"></textarea>
              </div>
              <div class="form-group">
                <input class="form-control captcha" placeholder="کد امنیتی" name="captcha" type="text" value=""/>
                <img src="{{captcha_src()}}"/>
              </div>
              <input class="btn btn-lg btn-success btn-block" type="submit" value="ارسال"/>
              <input id="msg-reply-hash" name="comment_hash" type="hidden" value="#"/>
              <input id="msg-reply-file" name="hash" type="hidden" value="#"/>
            </fieldset>
          </form>
        </div>
    </div>
  </div>
    <div class="pagination">@include('pages.pagination', ['object' => $data["comments"]])</div>
  @endif
@stop
