@extends('layouts.default-profile')

@section('title')
  اصلاح مشخصات رسانه {{$data['file']->title}}
@endsection

<?php $whereAmI='profile-filelist' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/editmedia.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">
  </script>
@stop
<?php $media = $data['file']; ?>
@section('content')
  <div class="well well-sm">فرم اصلاح مشخصات رسانه "{{$media->title}}"</div>
  <div class="well well-sm" style="color:red;margin-top:-16px">کاربران عزیز دقت داشته باشید پر کردن کادر های قرمز رنگ اجباری است.</div>
  @if(@Session::get('status'))
    <ul>
      @if(@Session::get('status') == 'success')
        <li class="alert alert-success">با موفقیت ویرایش شد</li>
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
      <form class="form-horizontal" action="{{url('profile/filelist/edit/'.$media->hash.'/')}}" method="POST" id="myfrm" enctype="multipart/form-data">
      {{csrf_field()}}
      <div class="form-content-1 col-md-6">
          <fieldset>
            <div class="form-group row requird-field">
              <label class="control-label">عنوان</label>
              <input maxlength="" class="form-control input-sm" name="title" type="text" value="{{$media->title}}" required="required"/>
              <p class="well">در این قسمت عنوان اصلی رسانه وارد شود.</p>
            </div>
            <div class="form-group row">
              <label class="control-label">خالق</label>
              <input maxlength="" class="form-control input-sm" type="text" name="author" value="{{$media->creator}}"/>
              <p class="well">در این قسمت نام خالق اثر در صورت وجود ذکر شود.</p>
            </div>
            <div class="form-group row">
              <label class="control-label">ناشر</label>
              <input class="form-control input-sm" maxlength="" name="publisher" value="{{$media->publisher}}" type="text"/>
              <p class="well">در این قسمت نام شرکت,سازمان و یا فردی که این اثر را منتشر کرده است را وارد کنید.</p>
            </div>
            <div class="form-group row requird-field">
              <label class="control-label">تگ ها</label>
              <input class="form-control input-sm" name="tag" maxlength="" type="text" value="{{$media->tags}}" required="required"/>
              <p class="well">در این قسمت باید کلمات کلیدی مربوط به این رسانه وارد شود. با این کار رسانه شما به سهولت در دسترس است. با انتخاب کلمات کلیدی مناسب رسانه شما در متور های جستجو و جستوجی سایت به راحتی توسط دیگر کاربران پیدا می شود.برای وارد کردن تگ ها کلمات را با خط تیره (-) از هم جدا کنید به عنوان مثال برای رسانه ای با موضوع آموزش رایانه تگها می توانند به صورت زیر باشند :<br>آموزش رایانه-آموزشی-کامپیوتر</p>
            </div>
            <div class="form-group row requird-field">
              <label class="control-label">زبان</label>
              <select class="form-control input-sm" name="language" required="required">
                <option @if($media->lang == 'فارسی' )selected="selected"@endif>فارسی</option>
          			<option @if($media->lang != 'فارسی' )selected="selected"@endif>غيرفارسی</option>
              </select>
              <p class="well">زبان این رسانه را انتخاب کنید.</p>
            </div>
            <div class="form-group row requird-field">
              <label class="control-label">دسته</label>
              <select class="form-control input-sm" name="category" required="required">
              <option value="">دسته را انتخاب کنید.</option>
              @foreach(config('co.categorys') as $cat)
            		<option @if($media->branch == $cat) selected="selected" @endif>{{$cat}}</option>
            	@endforeach
            </select>
              <p class="well">دسته ای که رسانه در آن قرار دارد.</p>
            </div>
            <div class="form-group row">
              <label class="control-label">نظر دهی</label>
              <select class="form-control input-sm" name="comment">
                <option @if($media->soflag == 'n' || $media->soflag == 's') selected="selected" @endif value="no">خیر وجود نداشته باشد</option>
                <option @if($media->soflag == 'b' || $media->soflag == 'c') selected="selected" @endif value="yes">بله وجود داشته باشد</option>
              </select>
              <p class="well">آیا قابلیت گذاشتن نظر برای کاربران وجود داشته باشد؟</p>
            </div>
          </fieldset>
      </div>
      <div class="form-content-2 col-md-6">
          <fieldset>
            <div class="form-group row">
              <label class="control-label">دسترسی خارج سایت</label>
              <select class="form-control input-sm" name="ath">
                <option @if($media->soflag == 'n' || $media->soflag == 'c') selected="selected" @endif value="no">خیر وجود نداشته باشد</option>
                <option @if($media->soflag == 'b' || $media->soflag == 's') selected="selected" @endif value="yes">بله وجود داشته باشد</option>
              </select>
              <p class="well">آیا قابلت نمایش این رسانه در خارج از سایت وجود داشته باشد؟</p>
            </div>
            @if($media->type == 3)
              <div class="form-group row">
		  					<label class="control-label">عکس سفارشی</label>
						    <input class="form-control input-sm" type="file" name="upload_img"/>
						    <p class="well">در این قسمت عکس سفارشی خود را قرار دهید.</p>
		  				</div>
            @endif
            <div class="form-group row">
              <label class="control-label">طبقه ها</label>
              <select class="form-control input-sm" name="fabranch">
              <option @if(!in_array($media->fabranch, $data['branchesHash'])) selected="selected" @endif>بدون طبقه</option>
              @foreach ($data['branches'] as $branch)
                <option value="{{$branch->hash}}" @if($media->fabranch == $branch->hash) selected="selected" @endif>{{$branch->name}}</option>
              @endforeach
            </select>
              <p class="well">طبقه ای که تمایل دارید این رسانه در آن قرار بگیرد.</p>
            </div>
            <div class="form-group row">
              <label class="control-label">رسانه شاخص</label>
              <input @if ($data["isTop"]) checked="checked" @endif class="form-control input-sm" name="top_media" value="set" type="checkbox"/>
              <p class="well">در صورت انتخاب این رسانه در هنگام مشاهده لیست رسانه های شما توسط کاربران به صورت شاخص و در بالای لیست به نمایش در خواهد آمد.</p>
            </div>
            <div class="form-group row">
              <label class="control-label">توضیحات</label>
              <textarea class="form-control" maxlength="" rows="12" name="des">{{$media->explenation}}</textarea>
              <p class="well">در این قسمت توضیحات مربوط به این رسانه را وارد کنید.</p>
            </div>
            <div class="form-group row">
              <input class="btn btn-success" type="submit" value="ذخیره اطلاعات"/>
            </div>
          </fieldset>
        </div>
      </form>
    </div>
@endsection
