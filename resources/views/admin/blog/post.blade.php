@extends('admin.layouts.default')
<?php $whereAmI = 'blog-post';?>
@section('title')
    ویرایش صفحه های ویژه
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/jquery-te-1.4.0.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ URL::asset('css/admin/blog-post.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript" src="{{ URL::asset('js/admin/jquery-te-1.4.0.min.js') }}" charset="utf-8"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#edit").jqte();
    });
    function edit(id)
    {
      $("[name=id]").val($("#"+id+"_id").val());
      $("[name=title]").val($("#"+id+"_title").val());
      $("[name=fabranch]").val($("#"+id+"_faid").val());
      $("#edit").jqteVal($("#"+id+"_content").val());
      $("[name=writer]").val($("#"+id+"_writer").val());

      if($("#"+id+"_is_news").val()==1)
      {
        $("[name=news]").prop('checked', true);
      }
      $("#btn-submit").val('ویرایش');
      $("#btn-reset").show();
    }
    function reset()
    {
      $("[name=id]").val('none');
      $("[name=title]").val('');
      $("[name=fabranch]").val('');
      $("#edit").jqteVal('');
      $("[name=writer]").val('');
      $("[name=news]").prop('checked', false);

      $("#btn-submit").attr('value','ذخیره');
      $("#btn-reset").hide();
    }
  </script>
@stop

@section('content')
    <h1>ویرایش پست های بلاگ</h1>
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
      <div class="panel-heading">افزودن یک پست</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/blog/post/save/') }}" method="POST">
          {{ csrf_field() }}
          <input name="id" type="hidden" value="none">
          <fieldset>
            <div class="col-sm-3 form-group">
              <label>عنوان مطلب : </label>
              <input name="title" class="form-control" type="text" placeholder="عنوان مطلب">
            </div>
            <div class="col-sm-3 form-group">
              <label>دسته :‌ </label>
              <select class="form-control" name="fabranch">
                <option value="none">یک دسته را انتخاب کنید</option>
                @foreach($data['groups'] as $value)
                  <option value="{{ $value->id }}">{{ $value->menu_title }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-3 form-group">
              <label>نویسنده :‌</label>
              <input name="writer" class="form-control" type="text" placeholder="نویسنده">
            </div>
            <div class="col-sm-3 form-group checkbox">
              <label>خبر ؟‌
              <input name="news" class="form-control" type="checkbox" value="checked">
              </label>
            </div>
            <div class="form-group">
              <textarea id="edit" name="text" class="form-control"></textarea>
            </div>
            <div class="btn-submit form-group">
              <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ذخیره">
              <div id="btn-reset" onclick="reset();" class="btn btn-hobby form-control btn-ord btn-reset">ریست فرم</div>
            </div>
          </fieldset>
        </form>
        <p class="well">برای قرار دادن دکمه ادامه از رشته [!con!] استفاده کنید.</p>
        <table id="branch-table" class="table table-bordred table-striped">
				<thead>
					<tr>
						<th>عنوان</th>
						<th>نویسنده</th>
            <th>تاریخ</th>
            <th>دسته</th>
            <th>خبر</th>
            <th>ویرایش</th>
            <th>حذف</th>
					</tr>
				</thead>
				<tbody>
          @if(@$data['posts'])
          @foreach($data['posts'] as $value)
                <input type="hidden" value="{{ $value->id }}" id="{{ $value->id }}_id">
                <input type="hidden" value="{{ $value->text_title }}" id="{{ $value->id }}_title">
                <input type="hidden" value="{{ $value->writer }}" id="{{ $value->id }}_writer">
                <input type="hidden" value="{{ $value->content }}" id="{{ $value->id }}_content">
                <input type="hidden" value="{{ $value->faid }}" id="{{ $value->id }}_faid">
                <input type="hidden" value="{{ $value->is_news }}" id="{{ $value->id }}_is_news">
            <tr>
              <td>
                {{ $value->text_title }}
              </td>
              <td>
                {{ $value->writer }}
              </td>
              <td>
                {{ $value->mdate }}
              </td>
              <td>
                {{ $value->group->menu_title }}
              </td>
              <td>
                @if($value->is_news == 1)
                  <i style="color:green" class="fa fa-check" aria-hidden="true"></i>
                @else
                  <i style="color:red" class="fa fa-times" aria-hidden="true"></i>
                @endif
              </td>
              <td>
                <a title="ویرایش کردن" href="#" onclick="edit('{{ $value->id }}');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </td>
    					<td>
                <a title="حذف کردن" href="{{ url('webmaster/blog/post/del/'.$value->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>
              </td>
    				</tr>
          @endforeach
          @endif
			</tbody>
			</table>
      </div>
    </div>
@stop
