<div class="row">
  <div class="navbar-profile">
    <div class="user-name-navbar">
    <a title="مشاهده نمای خارجی کانال شما" href="{{url('/class/'.Auth::user()->hash)}}">
      <img src="{{url('/includes/user_pic.php?picid='.Auth::user()->hash.'&s=2&p='.Auth::user()->pic_path)}}">
      <p>{{Auth::user()->name}}/{{Auth::user()->owner}}</p>
    </a>
    </div>
    <div class="search-profile-box">
      <div class="container-search">
        <form id="prof-search-form" action="{{url('/profile/search/')}}">
          <span onclick="document.getElementById('prof-search-form').submit();" class="icon-search"><i class="fa fa-search" aria-hidden="true"></i></span>
          <input type="search" id="search" name="text" placeholder="جستجو ...">
        </form>
      </div>
    </div>
    <div class="recive-msg">
      <a title="صندوق پیام" href="{{url('/profile/msg/')}}">
      <i @if(\App\Http\handyHelpers::countNewMsg()>0) style="color:orange" @endif class="fa fa-envelope-open-o" aria-hidden="true"></i>
      <span class="num-new-msg">{{\App\Http\handyHelpers::countNewMsg()}}</span>
      </a>
    </div>
  </div>
</div>
