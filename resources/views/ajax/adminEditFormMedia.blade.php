<?php $media = $data['file']?>
<div id="hash">{{$media->hash}}</div>
<div id="code">
    <div class="form-content-1" style="width:50%">
        <fieldset>
          <div class="form-group row requird-field">
            <label class="control-label">عنوان</label>
            <input maxlength="" class="form-control input-sm" id="{{$media->hash}}_title" type="text" value="{{$media->title}}" required="required"/>
          </div>
          <div class="form-group row">
            <label class="control-label">خالق</label>
            <input maxlength="" class="form-control input-sm" type="text" id="{{$media->hash}}_author" value="{{$media->creator}}"/>
          </div>
          <div class="form-group row">
            <label class="control-label">ناشر</label>
            <input class="form-control input-sm" maxlength="" id="{{$media->hash}}_publisher" value="{{$media->publisher}}" type="text"/>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">تگ ها</label>
            <input class="form-control input-sm" id="{{$media->hash}}_tag" maxlength="" type="text" value="{{$media->tags}}" required="required"/>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">زبان</label>
            <select class="form-control input-sm" id="{{$media->hash}}_language" required="required">
              <option @if($media->lang == 'فارسی' )selected="selected"@endif>فارسی</option>
              <option @if($media->lang != 'فارسی' )selected="selected"@endif>غيرفارسی</option>
            </select>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">دسته</label>
            <select class="form-control input-sm" id="{{$media->hash}}_cat" required="required">
              <option value="">دسته را انتخاب کنید.</option>
              @foreach(config('co.categorys') as $cat)
                <option @if($media->branch == $cat) selected="selected" @endif>{{$cat}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <label class="control-label">نظر دهی</label>
            <select class="form-control input-sm" id="{{$media->hash}}_comment">
              <option @if($media->soflag == 'n' || $media->soflag == 's') selected="selected" @endif value="no">خیر وجود نداشته باشد</option>
              <option @if($media->soflag == 'b' || $media->soflag == 'c') selected="selected" @endif value="yes">بله وجود داشته باشد</option>
            </select>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">کانال</label>
            <input class="form-control input-sm" maxlength="" id="{{$media->hash}}_class" value="{{$media->user->hash}}" type="text"/>
          </div>
        </fieldset>
    </div>
    <div class="form-content-2" style="width:50%">
        <fieldset>
          <div class="form-group row">
            <label class="control-label">دسترسی خارج سایت</label>
            <select class="form-control input-sm" id="{{$media->hash}}_ath">
              <option @if($media->soflag == 'n' || $media->soflag == 'c') selected="selected" @endif value="no">خیر وجود نداشته باشد</option>
              <option @if($media->soflag == 'b' || $media->soflag == 's') selected="selected" @endif value="yes">بله وجود داشته باشد</option>
            </select>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">وضعیت</label>
            <input class="form-control input-sm" maxlength="" id="{{$media->hash}}_published" value="{{$media->ispublished}}" type="text"/>
          </div>
          <div class="form-group row">
            <label class="control-label">پسند</label>
            <input class="form-control input-sm" maxlength="" id="{{$media->hash}}_like" value="{{$media->likes}}" type="text"/>
          </div>
          <div class="form-group row">
            <label class="control-label">بازدید</label>
            <input class="form-control input-sm" maxlength="" id="{{$media->hash}}_visit" value="{{$media->visit}}" type="text"/>
          </div>
          <div class="form-group row">
            <label class="control-label">توضیحات</label>
            <textarea class="form-control" maxlength="" rows="12" id="{{$media->hash}}_des">{{$media->explenation}}</textarea>
          </div>
          <div class="form-group row">
            <a class="btn btn-hobby btn-save" data-hash="{{$media->hash}}">ذخیره اطلاعات</a>
            <a class="btn btn-hobby btn-close" data-hash="{{$media->hash}}">بستن</a>
          </div>
        </fieldset>
    </div>
</div>
