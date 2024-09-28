@extends('layouts.default-profile')

@section('title')
  صندوق پیام شما
@endsection

<?php $whereAmI='profile-message' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/message.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">

  </script>
@stop

@section('content')
  @if(@Session::get('status'))
    <ul>
      @if(@Session::get('status') == 'del_success')
        <li class="alert alert-success">با موفقیت حذف شد.</li>
      @endif
    </ul>
  @endif
  @if(count($data['heads'])<1)
    <div class="alert alert-hobby">
            صندوق پیام شما خالی است.
    </div>
  @else
    <div class="msg-title">
      <ul>
        @foreach ($data['heads'] as $msg)
          <li class="col-sm-4 " @if($msg->is_read == 'u') style="border-top:1px solid red;" @endif>
						<div class="msg-det">
							<img src="{{url('/includes/user_pic.php?picid='.$msg->senderUser->hash.'&s=2&p='.$msg->senderUser->pic_path)}}"/>
							<p><a href="{{url('/class/'.$msg->senderUser->hash)}}" title="{{$msg->senderUser->name}}">{{$msg->senderUser->name}}</a> گفته : </p>
							<p style="line-height: 20px;">{{$msg->text}}</p>
						</div>
						<div>
							<div class="msg-date" title="زمان ارسال">{{\App\Http\handyHelpers::createIntervalString($msg->date)}}</div>
							<div class="msg-opt">
								<a href="{{url('/profile/msg/del/'.$msg->id)}}" title="حذف"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
							</div>
						</div>
					</li>
        @endforeach
      </ul>
    </div>
    <!--<div class="col-sm-8 msg-content">
      <ul>
        <li>
          <div class="msg-main">
            <img src="pic/profile.png"/>
            <p class="msg-sender"><a href="#">Hobby9</a></p>
            <p>این یک پیام آزمایشی برای تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.این یک پیام آزمایشی برای این یک پیام آزمایشی برای تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.این یک پیام آزمایشی برای تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.</p>
          </div>
          <div class="msg-content-date">
            <p>
              <span class="icon time-ico"></span>&nbsp;&nbsp;<span>۱ ساعت پیش</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="#"><span class="icon reply-ico"></span></a>
              <a href="#"><span class="icon trash-ico"></span></a>
              <input type="checkbox">
            </p>
          </div>
        </li>
        <li>
          <div class="msg-main">
            <img src="pic/profile.png"/>
            <p class="msg-sender"><a href="#">Hobby9</a></p>
            <p>گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار .</p>
          </div>
          <div class="msg-content-date">
            <p>
              <span class="icon time-ico"></span>&nbsp;&nbsp;<span>۱ ساعت پیش</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="#"><span class="icon reply-ico"></span></a>
              <a href="#"><span class="icon trash-ico"></span></a>
              <input type="checkbox">
            </p>
          </div>
        </li>
        <li>
          <div class="msg-main">
            <img src="pic/profile.png"/>
            <p class="msg-sender"><a href="#">Hobby9</a></p>
            <p>بنست شود و نحوی قرفتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.این یک پیام آزمایشی برای تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.</p>
          </div>
          <div class="msg-content-date">
            <p>
              <span class="icon time-ico"></span>&nbsp;&nbsp;<span>۱ ساعت پیش</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="#"><span class="icon reply-ico"></span></a>
              <a href="#"><span class="icon trash-ico"></span></a>
              <input type="checkbox">
            </p>
          </div>
        </li>
        <li>
          <div class="msg-main">
            <img src="pic/profile.png"/>
            <p class="msg-sender"><a href="#">Hobby9</a></p>
            <p>اینپروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.این یک پیام آزمایشی برای تسا نحوی قرار گیری پیام های دریافتی در پروفایل کاربری است که باید نست شود و نحوی قرار گیری آن در تمامی حالت صفحه نمایش کوجک و بزرگ و خیلی کوچم مورد برسی قرارا گیرد.</p>
          </div>
          <div class="msg-content-date">
            <p>
              <span class="icon time-ico"></span>&nbsp;&nbsp;<span>۱ ساعت پیش</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="#"><span class="icon reply-ico"></span></a>
              <a href="#"><span class="icon trash-ico"></span></a>
              <input type="checkbox">
            </p>
          </div>
        </li>
      </ul>
      <div class="well msg-form">
              <form action="#" method="POST">
                  <textarea class="form-control" id="new_message" name="new_message" placeholder="متن پیام" rows="5"></textarea>
                  <input class="btn btn-info" type="submit" value="ارسال پیام">
              </form>
          </div>
    </div>-->
  @endif
@stop
