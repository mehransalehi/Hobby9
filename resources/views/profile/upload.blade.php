@extends('layouts.default-profile')

@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
  آپلود رسانه
@endsection

<?php $whereAmI='profile-upload' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/upload.css')?>" type="text/css">
@stop
@section('script')
  <script src="{{URL::asset('js/profile/hobby_uploader.js')}}" type="text/javascript"></script>
  <script src="{{URL::asset('js/profile/jquery-asPieProgress.js')}}" type="text/javascript"></script>
  <script src="{{URL::asset('js/profile/jquery.playSound.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function(){
      $(".tab-btn").bind( "click", function() {
          var id = $(this).attr("tab-content");
          $(".tab-btn").removeClass("active");
          $(".tab-content").css("display","none");
          $(this).addClass("active");
          $("#"+id).css("display","block");
      });
    });
  </script>
@stop
@section('content')
  <ul class="nav nav-tabs">
    <li id="local-set-tab" tab-content="local-set" class="@if($data['tab'] == 'local') active @endif tab-btn"><a onclick="return false;" href="#">آپلود معمولی / دستگاه محلی</a></li>
    <li id="link-set-tab" tab-content="link-set" class="@if($data['tab'] == 'link') active @endif tab-btn"><a onclick="return false;" href="#">آپلود از لینک</a></li>
    <!--<li id="you-set-tab" tab-content="you-set" class="@if($data['tab'] == 'you') active @endif tab-btn"><a onclick="return false;" href="#">آپلود از یوتیوب</a></li>-->
  </ul>
  <div id="local-set" class="tab-content col-xs-12" @if($data['tab'] == 'local') style="display:block" @endif>
    <div class="upload-form" id="uuuppp">
      <div id="detail" class="detail"></div>
      <div id="maininput"></div>
      <div id="progressbox" class="upload-progressbox">
      <div id="upload-progress-bar" class="upload-progress-bar pie_progress" role="progressbar" data-goal="100">
        <div class="pie_progress__number">0%</div>
        <div class="pie_progress__label">درحال آپلود</div>
      </div>
      <div id="upload-file-name" class="upload-file-name"></div>
      <div id="formDiv" class="formDiv">
        <div id="form-div-btns">
          <div id="btnLater" class="btn-later btn btn-warning">مشخصات فایل را بعدا وارد می کنم</div>
          <!--<div id="btnShowForm" class="btn-show-form btn btn-success">مشخصات فایل را وارد می کنم</div>-->
        </div>
        <div id="later-message">
          <div class="well well-sm" style="color:green;margin-top:-16px">این فایل با کادر قرمز رنگ در قسمت "رسانه های شما" مشخص شده است <br><br> با زدن کلید ویرایش مقابل آن اطلاعات مربوط به این فایل را وارد کنید.</div>
          <div class="well well-sm" style="color:red;margin-top:-16px">نکته : تا زمانی که اطلاعات فایل وارد نشده باشد این رسانه در سایت قابل مشاهده نخواهد بود.</div>
        </div>
      </div>
      <div id="info-upload-file-form"></div>
    </div>
    </div>
    <div class="extentions">
      <h2 style="text-align:center">پــسونــد هــای مــجاز آپــلــود</h2>
      <table>
            <tr>
              <td>ویــدئــو</td>
                <td>wmv, mov , mpg , mpeg , mp4 , avi , flv , mkv, 3gp</td>
            </tr>
            <tr>
                <td>کــتــاب الــکتــرونــیکــی</td>
                <td>PDF</td>
            </tr>
            <tr>
                <td>صــوتــی</td>
                <td>MP3 , AAC , WAV , OGG , AC3</td>
            </tr>
            <tr>
                <td>محدودیـــت حــجمی</td>
                <td>۷۵ M</td>
            </tr>
      </table>
    </div>
  </div>
  <div id="link-set" class="tab-content col-xs-12" @if($data['tab'] == 'link') style="display:block" @endif>
    <div id="linkdetail" class="detail"></div>
    <div id="sendlinkprogressbox" class="upload-progressbox">
      <div id="link-progress-bar" class="upload-progress-bar pie_progress" role="progressbar" data-goal="100">
        <div class="pie_progress__number">0%</div>
        <div class="pie_progress__label">درحال دانلود</div>
      </div>
      <div id="sendlink-file-name" class="upload-file-name"></div>

      <div id="formDivSend" class="formDiv">
        <div id="form-div-btns-send">
          <div id="btnLaterSend" class="btn-later btn btn-warning">مشخصات فایل را بعدا وارد می کنم</div>
          <!--<div id="btnShowForm" class="btn-show-form btn btn-success">مشخصات فایل را وارد می کنم</div>-->
        </div>
        <div id="later-message-send">
          <div class="well well-sm" style="color:green;margin-top:-16px">این فایل با کادر قرمز رنگ در قسمت "رسانه های شما" مشخص شده است <br><br> با زدن کلید ویرایش مقابل آن اطلاعات مربوط به این فایل را وارد کنید.</div>
          <div class="well well-sm" style="color:red;margin-top:-16px">نکته : تا زمانی که اطلاعات فایل وارد نشده باشد این رسانه در سایت قابل مشاهده نخواهد بود.</div>
        </div>
      </div>

      <div id="info-send-link-file-form"></div>
    </div>
    <form class="form-horizontal" id="sendlinkform">
      <fieldset>
        <div class="form-group row single-feild">
          <label class="control-label">لینک</label>
          <input id="dlLinkInput" placeholder="لینک" class="form-control input-sm" name="link" type="text" required="required"/>
          <p class="well">لینک مستقیم فایل را وارد کنید.</p>
        </div>
      </fieldset>
      <fieldset>
        <div>
          <button onclick="return false;" id="btnDlLink" class="btn btn-success btn-dl-link">ارسال</button>
        </div>
      </fieldset>
    </form>
    <div class="extentions">
      <h2 style="text-align:center">پــسونــد هــای مــجاز آپــلــود</h2>
      <table>
            <tr>
              <td>ویــدئــو</td>
                <td>wmv, mov , mpg , mpeg , mp4 , avi , flv , mkv, 3gp</td>
            </tr>
            <tr>
                <td>کــتــاب الــکتــرونــیکــی</td>
                <td>PDF</td>
            </tr>
            <tr>
                <td>صــوتــی</td>
                <td>MP3 , AAC , WAV , OGG , AC3</td>
            </tr>
            <tr>
                <td>محدودیـــت حــجمی</td>
                <td>۷۵ M</td>
            </tr>
      </table>
    </div>
  </div>
  <!--<div id="you-set" class="tab-content col-xs-12" @if($data['tab'] == 'you') style="display:block" @endif>
    <form class="form-horizontal" id="myfrm">
      <fieldset>
        <div class="form-group row single-feild">
          <label class="control-label">لینک</label>
          <input placeholder="لینک" class="form-control input-sm" name="link" type="text" required="required"/>
          <p class="well">لینک رسانه یوتیوب مانند : https://www.youtube.com/watch?v=sCbDfOa8Gc8</p>
        </div>
        <div class="form-group row single-feild">
          <label class="control-label">نوع رسانه</label>
          <span>MP4</span><input id="mp4-radio" name="type" type="radio" class="form-control input-sm" value="MP4" checked/>
          <span>MP3</span><input id="mp3-radio" name="type" type="radio" class="form-control input-sm" value="MP3"/>
          <p class="well">اگر فقط صوت رسانه لازم است گزینه MP3 را انتخاب کنید چرا که در صورت انتخاب گزینه MP4 هم صوت و هم تصویر رسانه مورد نظر دانلود شده و ممکن است کیفیت صدای رسانه کمی پایین بیایید.</p>
        </div>
      </fieldset>
    </form>
  </div>-->





@endsection
