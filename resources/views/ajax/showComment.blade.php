<div id="status">{{$data['status']}}</div>
@if(@$data['message'])
  <div id="message">{{$data['message']}}</div>
@endif
@if(@$data['hash'])
  <div id="hash">{{$data['hash']}}</div>
@endif
@if(@$data['captcha'])
  <div id="captcha">{{$data['captcha']}}</div>
@endif
@if (@$data['comment'])
  <div id="code">
    <?php $comment = $data['comment'];?>
    <div class="comment" id="item-list-{{$comment->hash}}">
      <small class="comment-date">{{ \App\Http\handyHelpers::MTST($comment->m_date) }}</small>
      <a class="media-right" href="{{url('class/'.$comment->user_hash)}}">
        <img src="{{URL::asset('css/images/profile.png')}}" /><!-- user pic -->
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
  </div>
@endif
