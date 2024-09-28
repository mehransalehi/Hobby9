@extends('admin.layouts.default')
<?php $whereAmI = 'home-group';?>
@section('title')
  ویرایش گروه های نمایش در خانه
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/group.css') }}" type="text/css">
@stop

@section('script')
  <script src="{{ URL::asset('js/jscolor.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $(".jscolor").on('change',function(){
        var name = $(this).attr('name');
        var color = $(this).val();
        if(name=='text_color')
        {
          $('.top-page-text').css("color","#"+color);
        }
        else if(name=='icon_color')
        {
          $('.top-page-icon').css("color","#"+color);
        }
        else if(name=='border_color')
        {
          $('.top-page-elem').css("border-color","#"+color);
        }
        else if(name=='hr_color')
        {
          $('.spacer').css("border-color","#"+color);
        }
        else if(name=='back_color')
        {
          $('.top-page-elem').css("background-color","#"+color);
        }
      });
      $("#text").on('change',function(){
          $('.top-page-text').text($(this).val());
      });
      $("#icon").on('change',function(){
          $('#icon-change').removeClass();
          $('#icon-change').addClass("top-page-icon fa "+$(this).val());
      });
    });
    function reset()
    {
      $("[name=id]").val('none');

      $("[name=title]").val('');

      $("[name=order]").val('');

      $("[name='title_color']").val('');

      $("[name='title_hover']").val('');

      $("[name='text_color']").val('');

      $("[name='text_hover']").val('');

      $("[name='hr_color']").val('');

      $("[name='back_color']").val('');

      $("[name='branch']").val('');



      $("[name='link']").val('');
      $("#btn-submit").attr('value','ذخیره');
      $("#btn-reset").hide();
    }
    function edit(id)
    {
      $("[name=id]").val($("#"+id+"_id").val());

      $("[name=title]").val($("#"+id+"_title").val());

      $("[name=order]").val($("#"+id+"_order").val());

      $("[name='title_color']").val($("#"+id+"_title_color").val());

      $("[name='title_hover']").val($("#"+id+"_title_hover").val());

      $("[name='text_color']").val($("#"+id+"_text_color").val());

      $("[name='text_hover']").val($("#"+id+"_text_hover").val());

      $("[name='hr_color']").val($("#"+id+"_hr_color").val());

      $("[name='back_color']").val($("#"+id+"_back_color").val());

      $("[name='branch']").val($("#"+id+"_branch").val());

      $("[name='branch_hash']").val($("#"+id+"_hash").val());

      $("[name='link']").val($("#"+id+"_link").val());
      $("#btn-submit").val('ویرایش');
      $("#btn-reset").show();
    }

  </script>
@stop

@section('content')
    <h1>ویرایش گروه های نمایش داده شده در صفحه خانه</h1>
    <hr class="main-hr"/>
    @if(@$data['status'])
      <ul>
        @if(@$data['status'] == 'success')
          <li class="alert alert-success">با موفقیت ذخیره شد.</li>
        @elseif(@$data['status'] == 'deleted')
          <li class="alert alert-success">با موفقیت حذف شد.</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">افزودن گروه</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/branch/group/') }}" method="POST">
          {{ csrf_field() }}
          <input name="id" type="hidden" value="none">
          <fieldset>
            <div class="col-sm-2 form-group">
              <label>عنوان : </label>
              <input name="title" class="form-control" type="text" placeholder="عنوان گروه">
            </div>
            <div class="col-sm-2 form-group">
              <label>ترتیب : </label>
              <input name="order" class="form-control" type="text" placeholder="ترتیب">
            </div>
            <div class="col-sm-2 form-group">
              <label>رنگ عنوان : </label>
              <input name="title_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>رنگ هاور عنوان : </label>
              <input name="title_hover" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>رنگ متن رسانه ها : </label>
              <input name="text_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>رنگ متن هاور رسانه ها :</label>
              <input name="text_hover" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>رنگ خط : </label>
              <input name="hr_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>رنگ زمینه : </label>
              <input name="back_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>لینک عکس زمینه : </label>
              <input name="link" class="form-control" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label>دسته های اصلی : </label>
              <select name="branch" class="form-control">
                <option value="تبليغات">تبليغات</option>
                <option value="کارتون">کارتون</option>
                <option value="طنز">طنز</option>
                <option value="علمی">علمی</option>
                <option value="حوادث">حوادث</option>
                <option value="حيوانات">حيوانات</option>
                <option value="طبيعت">طبيعت</option>
                <option value="صداوسيما">صداوسيما</option>
                <option value="شخصی">شخصی</option>
                <option value="سياسی">سياسی</option>
                <option value="آموزشی">آموزشی</option>
                <option value="کامپيوتر">کامپيوتر</option>
                <option value="هنری">هنری</option>
                <option value="سلامت">سلامت</option>
                <option value="ورزشی">ورزشی</option>
                <option value="مذهبی">مذهبی</option>
                <option value="مهندسی">مهندسی</option>
                <option value="تفريحی">تفريحی</option>
                <option value="متفرقه">متفرقه</option>
                <option value="taged">دسته با تگ مشخص</option>
                <option value="searched">دسته با جستجوی مشخص</option>
                <option value="newest">جدیدترین ها</option>
                <option value="mostest">پر بازدیدترین ها</option>
                <option value="channel">یک کانال خاص</option>
                <option value="music">آهنگ ها</option>
                <option value="book">کتاب ها</option>
                <option value="video">ویدیو ها</option>
              </select>
            </div>
            <div class="col-sm-2 form-group">
              <label>کلید رسانه : </label>
              <input name="branch_hash" class="form-control" type="text" placeholder="کلید رسانه مورد نظر">
            </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ذخیره">
            <div id="btn-reset" onclick="reset();" class="btn btn-hobby form-control btn-ord btn-reset">ریست فرم</div>
          </div>
          </fieldset>
        </form>
        <p class="well">برای بی رنگ بودن زمینه از کلمه transparent در داخل لینک عکس زمینه استفاده کنید.</p>
        <p class="well">در صورتی که می خواهید دسته ای به چند تگ را مشخص کنید تگ ها را با - از هم جدا کنید . در ابتدا و انتها - نگذارید فقط در بین تگ ها - قرار دهید</p>
        <table id="branch-table" class="table table-bordred table-striped">
				<thead>
					<tr>
						<th>عنوان</th>
						<th>شاخه</th>
            <th>کلید</th>
            <th>ترتیب</th>
            <th>عکس زمینه</th>
            <th>ویرایش</th>
            <th>حذف</th>
					</tr>
				</thead>
				<tbody>
          @if(@$group)
          @foreach($group as $value)
            @if(!empty($value))
                <input type="hidden" value="{{ $value->id }}" id="{{ $value->id }}_id">
                <input type="hidden" value="{{ $value->title }}" id="{{ $value->id }}_title">
                <input type="hidden" value="{{ $value->branch }}" id="{{ $value->id }}_branch">
                <input type="hidden" value="{{ $value->hash }}" id="{{ $value->id }}_hash">
                <input type="hidden" value="{{ $value->back_color }}" id="{{ $value->id }}_back_color">
                <input type="hidden" value="{{ $value->title_color }}" id="{{ $value->id }}_title_color">
                <input type="hidden" value="{{ $value->text_color }}" id="{{ $value->id }}_text_color">
                <input type="hidden" value="{{ $value->title_hover }}" id="{{ $value->id }}_title_hover">
                <input type="hidden" value="{{ $value->hr_color }}" id="{{ $value->id }}_hr_color">
                <input type="hidden" value="{{ $value->text_hover }}" id="{{ $value->id }}_text_hover">
                <input type="hidden" value="{{ $value->background_image_link }}" id="{{ $value->id }}_link">
                <input type="hidden" value="{{ $value->order }}" id="{{ $value->id }}_order">
            <tr>
              <td>
                {{ $value->title }}
              </td>
              <td>
                {{ $value->branch }}
              </td>
              <td>
                {{ $value->hash }}
              </td>
              <td>
                {{ $value->order }}
              </td>
              <td>
                {{ $value->background_image_link }}
              </td>
              <td>
                <a title="ویرایش کردن" href="#" onclick="edit('{{ $value->id }}');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </td>
    					<td>
                <a title="حذف کردن" href="{{ url('webmaster/branch/group/'.$value->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>
              </td>
    				</tr>
            @endif
          @endforeach
          @endif
			</tbody>
			</table>
      </div>
    </div>
@stop
