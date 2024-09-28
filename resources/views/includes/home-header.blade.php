<div class="header-btn">
  <div class="btn-wraper">
    @if(Auth::check())
      <div  class="fa fa-user-o drop-down-wraper-prof" id="btn-drop-down-prof" aria-hidden="true">
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
        <div id="drop-down-prof" class="drop-down-prof">
          <ul>
            <li><a href="{{url('profile') }}"><h4><i class="fa fa-user-o" aria-hidden="true"></i><span>داشبورد</span></h4></a></li>
            <li><a href="{{url('profile/filelist/') }}"><h4><i class="fa fa-video-camera" aria-hidden="true"></i><span>رسانه های شما</span></h4></a></li>
            <li><a href="{{url('profile/comment/')}}"><h4><i class="fa fa-comments" aria-hidden="true"></i><span>کامنت ها</span></h4></a></li>
            <li><a href="{{url('profile/setting/')}}"><h4><i class="fa fa-wrench menu-icon" aria-hidden="true"></i><span>تنظیمات</span></h4></a></li>
            <li><a href="{{url('profile/follow/')}}"><h4><i class="fa fa-heart" aria-hidden="true"></i><span>پسندیده شده ها</span></h4></a></li>
            <li><a href="{{url('profile/msg/')}}"><h4><i class="fa fa-envelope-o" aria-hidden="true"></i><span>پیام ها</span></h4></a></li>
            <li><a href="{{url('profile/logout') }}"><h4><i class="fa fa-power-off" aria-hidden="true"></i><span>خروج</span></h4></a></li>
          </ul>
        </div>
      </div>
    @else
      <a href="{{url('register') }}" data-toggle="tooltip" data-placement="bottom"  title="عضویت" class="fa fa-user-plus" aria-hidden="true"></a>
      <a href="{{url('profile/login') }}" data-toggle="tooltip" data-placement="bottom" title="ورود به ناحیه کاربری" class="fa fa-sign-in" aria-hidden="true"></a>
    @endif
    <a href="{{url('/profile/upload/')}}"><div data-toggle="tooltip" data-placement="bottom" title="آپلود رسانه" class="fa fa-upload" aria-hidden="true"></div></a>
    <div id="btn-drop-down-list" data-toggle="tooltip" data-placement="top"  title="دسته ها" class="fa fa-bars drop-down-wraper" aria-hidden="true">
      <div id="drop-down-list" class="drop-down-div">
        <ul>
          <li><a href="{{ url('/group/تبليغات/') }}"><h3>تبليغات</h3></a></li>
          <li><a href="{{ url('/group/کارتون/') }}"><h3>کارتون</h3></a></li>
          <li><a href="{{ url('/group/طنز/') }}"><h3>طنز</h3></a></li>
          <li><a href="{{ url('/group/علمی/') }}"><h3>علمی</h3></a></li>
          <li><a href="{{ url('/group/حوادث/') }}"><h3>حوادث</h3></a></li>
          <li><a href="{{ url('/group/حيوانات/') }}"><h3>حيوانات</h3></a></li>
          <li><a href="{{ url('/group/طبيعت/') }}"><h3>طبيعت</h3></a></li>
          <li><a href="{{ url('/group/صداوسيما/') }}"><h3>صداوسيما</h3></a></li>
          <li><a href="{{ url('/group/شخصی/') }}"><h3>شخصی</h3></a></li>
          <li><a href="{{ url('/group/سياسی/') }}"><h3>سياسی</h3></a></li>
          <li><a href="{{ url('/group/آموزشی/') }}"><h3>آموزشی</h3></a></li>
          <li><a href="{{ url('/group/کامپيوتر/') }}"><h3>کامپيوتر</h3></a></li>
          <li><a href="{{ url('/group/هنری/') }}"><h3>هنری</h3></a></li>
          <li><a href="{{ url('/group/سلامت/') }}"><h3>سلامت</h3></a></li>
          <li><a href="{{ url('/group/ورزشی/') }}"><h3>ورزشی</h3></a></li>
          <li><a href="{{ url('/group/مذهبی/') }}"><h3>مذهبی</h3></a></li>
          <li><a href="{{ url('/group/مهندسی/') }}"><h3>مهندسی</h3></a></li>
          <li><a href="{{ url('/group/تفريحی/') }}"><h3>تفريحی</h3></a></li>
          <li><a href="{{ url('/group/متفرقه/') }}"><h3>متفرقه</h3></a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="header-logo">
  <a href="{{ url('/') }}"><img src="{{ URL::asset('css/images/home-logo.png') }}"/ class="img-responsive" alt="سرویش اشتراک ویدیو آهنگ صوت و کتاب الکترونیکی" title="سرویش اشتراک ویدیو آهنگ صوت و کتاب الکترونیکی" ></a>
</div>
<div class="header-searchbar">
  <form action="{{ URL::asset('search/') }}" method="GET" id="search-form">
    <div class="search-box">
      <div class="search-text">دنبال چی می گردی دوست من ؟</div>
      <div class="fa fa-search search-icon"></div>
      <div><input name="text" class="search-input form-control" type="search" placeholder="Search ..."/></div>
      <div class="fa fa-arrow-right arrow-icon" onclick="document.getElementById('search-form').submit();"></div>
    </div>
  </form>
</div>
