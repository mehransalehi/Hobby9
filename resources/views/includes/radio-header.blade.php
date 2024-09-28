<div class="header-left">
  <form action="{{ URL::asset('searchradio/') }}" method="GET" id="search-form" >
    <input name="text" placeholder="search music" value="جستجوی آهنگ, هنرمند"/>
    <div class="fa fa-search search-icon arrow-icon" onclick="document.getElementById('search-form').submit();"></div>
  </form>
  <!--<i class="fa fa-user-plus" aria-hidden="true"></i>
  <i class="fa fa-sign-in" aria-hidden="true"></i>-->
</div>
<div class="header-right">
  <img src="{{ URL::asset('css/images/radio.png') }}" alt="رادیو Hobby9"/>
</div>
