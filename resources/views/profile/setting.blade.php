@extends('layouts.default-profile')

@section('title')
  تنظیمات کانال شما
@endsection

<?php $whereAmI='profile-setting' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/setting.css')?>" type="text/css">
@stop
@section('script')
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
<?php $user = $data['user']; ?>
@section('content')

      <!-- MESSAGE HERE -->
      @if(@Session::get('status'))
        <ul>
          @if(@Session::get('status') == 'save_success')
            <li class="alert alert-success">با موفقیت ویرایش شد</li>
          @elseif(@Session::get('status') == 'save_pic')
            <li class="alert alert-success">عکس با موفقیت آپلود شد.</li>
          @elseif(@Session::get('status') == 'faild')
            <li class="alert alert-danger">{{Session::get('message')}}</li>
          @endif
        </ul>
      @else
        <ul>
             @foreach ($errors->all() as $error)
                 <li class="alert alert-danger">{{ $error }}</li>
             @endforeach
         </ul>
      @endif
      <div class="col-xs-12">
        <?php $user = $data['user'];?>
        <ul class="nav nav-tabs">
          <li id="gen-set-tab" tab-content="gen-set" class="@if($data['tab'] == 'gen') active @endif tab-btn"><a onclick="return false;" href="#">تنظیمات عمومی</a></li>
          <li id="pic-set-tab" tab-content="pic-set" class="@if($data['tab'] == 'pic') active @endif tab-btn"><a onclick="return false;" href="#">تغییر آواتار</a></li>
          <!--<li id="tel-set-tab" tab-content="tel-set" class="@if($data['tab'] == 'tel') active @endif tab-btn"><a onclick="return false;" href="#">دریافت کد تلگرام</a></li>
          -->
        </ul>
        <div id="gen-set" class="tab-content col-xs-12" @if($data['tab'] == 'gen') style="display:block" @endif>
          <div class="well well-sm" style="color:red">کاربران عزیز دقت داشته باشید پر کردن کادر های قرمز رنگ اجباری است.</div>
            <form class="form-horizontal" action="{{url('/profile/setting/gen/')}}" method="POST" id="myfrm">
              {{ csrf_field() }}
              <div class="form-content-1 col-md-6">
  			  			<fieldset>
  			  				<div class="form-group row">
  			  					<label class="control-label">نام کاربری</label>
  							    <input class="form-control input-sm" name="address" type="text" value="{{$user->owner}}" />
  							    <p class="well">نام کاربری قابل تغییر نیست.با گروه پشتیبانی تماس بگیردید</p>
  			  				</div>
  			  				<div class="form-group row requird-field">
  			  					<label class="control-label">نام کانال</label>
  							    <input class="form-control input-sm" type="text" name="_name" value="{{$user->name}}" required="required"/>
  							    <p class="well">در این قسمت نام کانال خود را وارد کنید . نام کانال باید حداقل داردای ۵ کاراکتر باشد . نام کانال نامی است که بقیه کاربران شما را با این نام خواهند شناخت.</p>
  			  				</div>
  			  				<div class="form-group row">
  			  					<label class="control-label">آدرس وب سایت</label>
  							    <input class="form-control input-sm" name="link" value="{{$user->link}}" placeholder="http://www.website.com"  type="url"/>
  							    <p class="well">در صورت وجود آدرس وب سایت یا وبلاگی که این کانال در راستای حمایت از آن و یا در زیرمجموعه آن قرار دارد را وارد کنید.</p>
  			  				</div>
  			  				<div class="form-group row">
  			  					<label class="control-label">آدرس صفحه فیسبوک</label>
  							    <input class="form-control input-sm" name="fb_addr"  type="url" value="{{@$user->setting->fb_addr}}"/>
  							    <p class="well">در صورتی که این کانال صفحه ای در شبکه اجتماعی فیسبوک دارد آن را در این قسمت وارد کنید</p>
  			  				</div>
  			  				<div class="form-group row">
  			  					<label class="control-label">آدرس صفحه تویتر</label>
  							    <input class="form-control input-sm" name="tw_addr"  type="url" value="{{@$user->setting->tw_addr}}"/>
  							    <p class="well">در صورتی که این کانال صفحه ای در شبکه اجتماعی تویتر دارد آن را در این قسمت وارد کنید</p>
  			  				</div>
  			  				<div class="form-group row">
  			  					<label class="control-label">آدرس صفحه گوگول پلاس</label>
  							    <input class="form-control input-sm" name="gp_addr"  type="url" value="{{@$user->setting->gp_addr}}"/>
  							    <p class="well">در صورتی که این کانال صفحه ای در شبکه اجتماعی گوگل پلاس دارد آن را در این قسمت وارد کنید</p>
  			  				</div>
  			  			</fieldset>
              </div>
              <div class="form-content-2 col-md-6">
                <fieldset>
                  <div class="form-group row">
                    <label class="control-label">کلمه عبور</label>
                    <input class="form-control input-sm"  type="password" name="pass"/>
                    <p class="well">تعدا کاراکتر های کلمه عبور باید بیشتر از ۶ کاراکتر باشد. درصورتی که تمایلی به تغییر کلمه عبور ندارید این فیلد و فیلد بعدی را خالی رها کنید.</p>
                  </div>
                  <div class="form-group row">
                    <label class="control-label">تکرار کلمه عبور</label>
                    <input class="form-control input-sm" type="password" name="confpass"/>
                    <p class="well">تکرار کلمه عبور برای اطمینان از درست وارد شدن آن</p>
                  </div>
                  <div class="form-group row">
                    <label class="control-label">توضیحات</label>
                    <textarea class="form-control" rows="12" name="des">{{$user->des}}</textarea>
                    <p class="well">در این قسمت توضیحاتی در مورد کانال و اهداف سازنده آن را بیان کنید.</p>
                  </div>
                  <div class="form-group row">
                    <input class="btn btn-success" type="submit" value="ذخیره اطلاعات"/>
                  </div>
                </fieldset>
              </div>
			  		</form>
        </div>
        <div id="pic-set" class="tab-content col-xs-12" @if($data['tab'] == 'pic') style="display:block" @endif>
          <form action="{{url('/profile/setting/pic/')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
						<div class="col-md-6 pic-upload-left">
							<h2>قرار دادن آواتار جدید</h2>
							<p class="well well-sm"><b>حداکثر حجم عکس ۲ مگابایت</b></p>
							<fieldset>
				  				<div class="form-group row">
									<input type="file" name="picture" class="upload-pic">
								</div>
								<div class="form-group row">
									<input class="btn btn-success" type="submit" value="آپلود آواتار">
								</div>
							</fieldset>
						</div>
						<div class="col-md-6 pic-upload-right">
							<h2>آواتار حال حاضر</h2>
							<img class="img-thumbnail" src="{{url('/includes/user_pic.php?picid='.Auth::user()->hash.'&s=1&p='.Auth::user()->pic_path)}}" />
						</div>
					</form>
        </div>
        <!--<div id="tel-set" class="tab-content col-xs-12" @if($data['tab'] == 'tel') style="display:block" @endif>
          tele
        </div>-->
      </div>
@endsection
