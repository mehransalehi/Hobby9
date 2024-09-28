@extends('layouts.default')

<?php $whereAmI = 'verify'; ?>

@section('description')
  <meta name="description" content="عضویت در وب سایت www.HOBBY9.com">
@stop

@section('title')
  ادامه عملیات ثبت نام | {{ $email }}
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/verify.css') }}" type="text/css">
@stop

@section('script')
@stop

@section('content')
		<div class="verify-form-wraper">
          <div class="verify-title">ادامه عملیات عضویت</div>
          @if(session('status'))
            <ul>
              @if(session('status') == 'email_not_exist')
                <li class="alert alert-danger">این ایمیل در خواست ثبت نام نداده است.</li>
              @elseif(session('status') == 'bad_username')
                <li class="alert alert-danger">نام کاربری فقط باید از حروف انگلیسی و اعداد و ـ (underline) تشکیل شده باشد.</li>
              @elseif(session('status') == 'exist_username')
                <li class="alert alert-danger">این نام کاربری قبلا توسط شخصی دیگر انتخاب شده است.</li>
              @endif
            </ul>
          @else
            <ul>
                 @foreach ($errors->all() as $error)
                     <li class="alert alert-danger">{{ $error }}</li>
                 @endforeach
             </ul>
          @endif
		  		<form class="form-horizontal" action="{{ url('verify/'.$hash) }}" method="POST" id="myfrm">
            {{ csrf_field() }}
            <div class="form-content-one col-xs-12 col-sm-6">
            <fieldset>
		    	  	<div class="form-group">
		    	  		<label class="control-label col-xs-2">ایمیل</label>
		    		    <input class="form-control input-sm" disabled="disabled" name="email" type="text" value="{{ $email }}"/>
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">توجه داشته باشید پر کردن کادر های قرمز رنگ اجباری می باشد. <br> پر کردن بقیه کادر های اختیاری است.</p>
			    		</div>
			    		<div class="form-group requird-field">
			    			<label class="control-label">نام کاربری</label>
		    		    <input data-validate-length="5,30" name="alias" class="form-control" value="{{ old('alias') }}" type="text" />
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">در کادر بالا یک نام کاربری برای خود انتخاب کنید . از این نام کاربری بعدا برای ورود به قسمت کاربری استفاده می شود آن را بخاطر بسپارید.نام کاربری باید تعداد حروفی بین ۵ تا ۳۰ کاراکتر را شامل شود و تنها استفاده از اعداد و حروف انگلیسی و زیر خط (underline) مجاز می باشد .</p>
			    		</div>
			    		<div class="form-group requird-field">
			    			<label class="control-label">نام کانال</label>
		    		    <input class="form-control" data-validate-length="5,200"  type="text" name="_name" value="{{ old('_name') }}"/>
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">در کادر بالا نام کانال خود را وارد کنید این نام باید حداقل شامل ۵ کاراکتر باشد.</p>
			    		</div>
			    		<div class="form-group">
			    			<label class="control-label">آدرس</label>
			    		    <input class="form-control" name="link"  placeholder="http://www.website.com"  value="{{ old('link') }}" type="url"/>
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">در صورت وجود آدرس سایت یا وبلاگی که این کلاس برای حمایت یا در زیرمجموعه آن راه اندازی شده است را وارد کنید.</p>
			    		</div>
			    	</fieldset>
		  		</div>
		  		<div class="form-content-two col-xs-12 col-sm-6">
		  			<fieldset>
		  				<div class="form-group requird-field">
			    			<label class="control-label">کلمه عبور</label>
		    		    <input class="form-control" type="password" name="pass" data-validate-length="6,20"/>
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">کلمه عبور مورد نظر خود را وارد کنید. تعداد حروف کلمه عبور باید بین ۶ تا ۲۰ حرف باشد. دقت داشته باشید صفحه کلید در حالت فارسی نباشد.</p>
			    		</div>
			    		<div class="form-group requird-field">
			    			<label class="control-label">تکرار</label>
			    		    <input class="form-control"  type="password" name="pass_confirmation" data-validate-linked="password"/>
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">کلمه عبوری که در قسکن قبلی وارد کرده اید را دوباره در کادر بالا وارد کنید.</p>
			    		</div>
			    		<div class="form-group">
			    			<label class="control-label">متن</label>
			    		    <textarea class="form-control"  maxlength="" rows="5" name="des">{{ old('des') }}</textarea>
			    		</div>
			    		<div class="form-group">
			    	  		<p class="well">در این قسمت توضیحاتی در مورد کانال و اهداف مورد نظر سازنده کانال را وارد کنید.</p>
			    		</div>
			    	</fieldset>
			    	<input class="btn btn-hobby btn-verify" type="submit" value="ارسال اطلاعات"/>
  		  		</div>
			    </form>
		  	</div>
		</div>
@stop
