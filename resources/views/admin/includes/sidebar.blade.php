<div class="sidebar-wraper">
  <ul class="fa-ul">
		<li><i class="fa fa-home menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/') }}"><i class="fa-li fa fa-chevron-left"></i>خانه</a></li>
    <li><i class="fa fa-files-o menu-icon" aria-hidden="true"></i><a href="#" data-toggle="collapse" data-target="file-toggle">
      <i class="fa-li fa @if($whereAmI == 'tele-files' || $whereAmI == 'file-manager' || $whereAmI == 'file-social' || $whereAmI == 'file-dl' || $whereAmI == 'file-dl-manage') fa-chevron-down @else fa-chevron-left @endif"></i>فایل ها</a>
    </li>
    <li>
      <ul @if($whereAmI == 'tele-files' || $whereAmI == 'file-manager' || $whereAmI == 'file-social' || $whereAmI == 'file-dl' || $whereAmI == 'file-dl-manage') style="display:block" @endif id="file-toggle" class="collapse fa-ul">
        <li @if($whereAmI == 'tele-files') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/files/telefiles') }}">دریافتی از تلگرام</a></li>
        <li @if($whereAmI == 'file-manager') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/files/filemanager') }}">مدیریت فایل ها</a></li>
        <li @if($whereAmI == 'file-social') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/files/social') }}">شبکه های اجتماعی</a></li>
        <li @if($whereAmI == 'file-dl') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/files/dl') }}">دانلود از لینک</a></li>
        <li @if($whereAmI == 'file-dl-manage') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/files/dlmanage') }}">مدیریت دانلود از لینک</a></li>
      </ul>
    </li>

    <li><i class="fa fa-user-circle menu-icon" aria-hidden="true"></i><a href="#"><i class="fa-li fa fa-chevron-left"></i>کاربران</a></li>
    <li><i class="fa fa-usd menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/ads') }}" data-toggle="collapse" data-target="ads-toggle">
      <i class="fa-li fa @if($whereAmI == 'ads') fa-chevron-down @else fa-chevron-left @endif"></i>تبلیغات</a>
    </li>
		<li><i class="fa fa-list menu-icon" aria-hidden="true"></i><a href="#" data-toggle="collapse" data-target="blog-toggle">
      <i class="fa-li fa @if($whereAmI == 'blog-branch' || $whereAmI == 'blog-post') fa-chevron-down @else fa-chevron-left @endif"></i>وبلاگ</a>
    </li>
    <li>
      <ul @if($whereAmI == 'blog-branch' || $whereAmI == 'blog-post') style="display:block" @endif id="blog-toggle" class="collapse fa-ul">
        <li @if($whereAmI == 'blog-branch') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/blog/branch') }}">ویرایش دسته ها</a></li>
        <li @if($whereAmI == 'blog-post') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/blog/post') }}">ویرایش پست ها</a></li>
      </ul>
    </li>
		<li><i class="fa fa-bars menu-icon" aria-hidden="true"></i><a href="#" data-toggle="collapse" data-target="branches-toggle">
      <i class="fa-li fa @if($whereAmI == 'special-branch' || $whereAmI == 'home-group') fa-chevron-down @else fa-chevron-left @endif "></i>دسته ها</a>
    </li>
    <li>
      <ul @if($whereAmI == 'special-branch' || $whereAmI == 'home-group') style="display:block" @endif id="branches-toggle" class="collapse fa-ul">
        <li @if($whereAmI == 'special-branch') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/branch/special') }}">ویرایش صفحه های برتر</a></li>
        <li @if($whereAmI == 'home-group') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/branch/group') }}">لیست رسانه های خانه</a></li>
      </ul>
    </li>
    <li><i class="fa fa-wrench menu-icon" aria-hidden="true"></i><a href="#" data-toggle="collapse" data-target="setting-toggle">
      <i class="fa-li fa @if($whereAmI == 'home-setting') fa-chevron-down @else fa-chevron-left @endif"></i>تنظیمات</a></li>
    <li>
      <ul @if($whereAmI == 'home-setting') style="display:block" @endif id="setting-toggle" class="collapse fa-ul">
        <li @if($whereAmI == 'home-setting') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/setting/home/') }}">تنظیمات Home</a></li>
      </ul>
    </li>
    <li><i class="fa fa-list menu-icon" aria-hidden="true"></i><a href="#" data-toggle="collapse" data-target="feedback-toggle">
      <i class="fa-li fa @if($whereAmI == 'feedback' || $whereAmI == 'admin-email') fa-chevron-down @else fa-chevron-left @endif"></i>ارتباط با کاربران</a>
    </li>
    <li>
      <ul @if($whereAmI == 'feedback' || $whereAmI == 'admin-email') style="display:block" @endif id="feedback-toggle" class="collapse fa-ul">
        <li @if($whereAmI == 'feedback') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/feedback') }}">پیام ها دریافتی</a></li>
        <li @if($whereAmI == 'admin-email') class="active-side" @endif><i class="fa fa-angle-left menu-icon" aria-hidden="true"></i><a href="{{ url('/webmaster/email') }}">ارسال ایمیل</a></li>
      </ul>
    </li>
	</ul>
</div>
