@extends('admin.layouts.default')
<?php $whereAmI = 'special-branch';?>
@section('title')
    ویرایش صفحه های ویژه
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/special.css') }}" type="text/css">
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
    function edit(id)
    {
      $("[name=id]").val($("#"+id+"_id").val());

      $("[name=text]").val($("#"+id+"_text").val());
      $('.top-page-text').text($("#"+id+"_text").val());

      $("[name=icon]").val($("#"+id+"_icon").val());
      $('#icon-change').removeClass();
      $('#icon-change').addClass("top-page-icon fa "+$("#"+id+"_icon").val());

      $("[name='text_color']").val($("#"+id+"_text_color").val());
      $('.top-page-text').css("color","#"+$("#"+id+"_text_color").val());

      $("[name='icon_color']").val($("#"+id+"_icon_color").val());
      $('.top-page-icon').css("color","#"+$("#"+id+"_icon_color").val());

      $("[name='back_color']").val($("#"+id+"_back_color").val());
      $('.top-page-elem').css("background-color","#"+$("#"+id+"_back_color").val());

      $("[name='border_color']").val($("#"+id+"_border_color").val());
      $('.top-page-elem').css("border-color","#"+$("#"+id+"_border_color").val());

      $("[name='hr_color']").val($("#"+id+"_hr_color").val());
      $('.spacer').css("border-color","#"+$("#"+id+"_hr_color").val());

      $("[name='link']").val($("#"+id+"_link").val());
      $("#btn-submit").val('ویرایش');
      $("#btn-reset").show();
    }
    function reset()
    {
      $("[name=id]").val('none');

      $("[name=text]").val('');

      $("[name=icon]").val('');

      $("[name='text_color']").val('');

      $("[name='icon_color']").val('');

      $("[name='back_color']").val('');

      $("[name='border_color']").val('');

      $("[name='hr_color']").val('');

      $("[name='link']").val('');
      $("#btn-submit").attr('value','ذخیره');
      $("#btn-reset").hide();
    }
  </script>
@stop

@section('content')
    <h1>ویرایش صفخه های برتر</h1>
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
      <div class="panel-heading">افزودن یک صفحه ویژه</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/branch/special/') }}" method="POST">
          {{ csrf_field() }}
          <input name="id" type="hidden" value="none">
          <fieldset>
            <div class="col-sm-2 form-group">
              <label for="sel1">رنگ نوشته :</label>
              <input name="text_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">رنگ آیکن : </label>
              <input name="icon_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">رنگ حاشیه : </label>
              <input name="border_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">رنگ خط : </label>
              <input name="hr_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">رنگ زمینه : </label>
              <input name="back_color" class="form-control jscolor" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">لینک : </label>
              <input name="link" class="form-control" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">نوشته : </label>
              <input id="text" name="text" class="form-control" type="text">
            </div>
            <div class="col-sm-2 form-group">
              <label for="sel1">آیکن :‌ </label>
              <input id="icon" name="icon" class="form-control" type="text">
            </div>

            <div class="col-sm-2 form-group">
              <div class="top-page-elem">
          			<div id="icon-change" class="top-page-icon fa fa-balance-scale"></div>
          			<hr class="spacer">
          			<div class="top-page-text">پشتیبانی</div>
          		</div>
            </div>

            <div class="btn-submit form-group">
              <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ذخیره">
              <div id="btn-reset" onclick="reset();" class="btn btn-hobby form-control btn-ord btn-reset">ریست فرم</div>
            </div>
          </fieldset>
        </form>
        <table id="branch-table" class="table table-bordred table-striped">
				<thead>
					<tr>
						<th>متن</th>
						<th>لینک</th>
            <th>ایکن</th>
            <th>ویرایش</th>
            <th>حذف</th>
					</tr>
				</thead>
				<tbody>
          @if(@$special)
          @foreach($special as $value)
            @if(!empty($value))

                <input type="hidden" value="{{ $value->id }}" id="{{ $value->id }}_id">
                <input type="hidden" value="{{ $value->text }}" id="{{ $value->id }}_text">
                <input type="hidden" value="{{ $value->icon }}" id="{{ $value->id }}_icon">
                <input type="hidden" value="{{ $value->link }}" id="{{ $value->id }}_link">
                <input type="hidden" value="{{ $value->icon_color }}" id="{{ $value->id }}_icon_color">
                <input type="hidden" value="{{ $value->text_color }}" id="{{ $value->id }}_text_color">
                <input type="hidden" value="{{ $value->border_color }}" id="{{ $value->id }}_border_color">
                <input type="hidden" value="{{ $value->back_color }}" id="{{ $value->id }}_back_color">
                <input type="hidden" value="{{ $value->hr_color }}" id="{{ $value->id }}_hr_color">
            <tr>
              <td>
                {{ $value->text }}
              </td>
              <td>
                {{ $value->link }}
              </td>
              <td>
                <i class="fa {{ $value->icon }}" aria-hidden="true"></i>
              </td>
              <td>
                <a title="ویرایش کردن" href="#" onclick="edit('{{ $value->id }}');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </td>
    					<td>
                <a title="حذف کردن" href="{{ url('webmaster/branch/special/'.$value->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>
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
