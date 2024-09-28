@extends('admin.layouts.default')
<?php $whereAmI = 'blog-branch';?>
@section('title')
    ویرایش صفحه های ویژه
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/blog-branch.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript">
    function edit(id)
    {
      $("[name=id]").val($("#"+id+"_id").val());

      $("[name=title]").val($("#"+id+"_title").val());

      $("[name=menu_title]").val($("#"+id+"_menu_title").val());

      $("[name=order]").val($("#"+id+"_order").val());
      $("#btn-submit").val('ویرایش');
      $("#btn-reset").show();
    }
    function reset()
    {
      $("[name=id]").val('none');

      $("[name=title]").val('');

      $("[name=menu_title]").val('');

      $("[name=order]").val('');

      $("#btn-submit").attr('value','ذخیره');
      $("#btn-reset").hide();
    }
  </script>
@stop

@section('content')
    <h1>ویرایش دسته های بلاگ</h1>
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
      <div class="panel-heading">افزودن یک دسته</div>
      <div class="panel-body">
        <form action="{{ url('webmaster/blog/branch/save/') }}" method="POST">
          {{ csrf_field() }}
          <input name="id" type="hidden" value="none">
          <fieldset>
            <div class="col-sm-4 form-group">
              <label>عنوان صفحه</label>
              <input name="title" class="form-control" type="text" placeholder="عنوان صفحه مورد نظر">
            </div>
            <div class="col-sm-4 form-group">
              <label>عنوان منو : </label>
              <input name="menu_title" class="form-control" type="text" placeholder="عنوان منو">
            </div>
            <div class="col-sm-4 form-group">
              <label for="sel1">ترتیب : </label>
              <input name="order" class="form-control" type="text" placeholder="ترتیب منو">
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
						<th>عنوان</th>
						<th>عنوان منو</th>
            <th>ترتیب</th>
            <th>ویرایش</th>
            <th>حذف</th>
					</tr>
				</thead>
				<tbody>
          @if(@$data['groups'])
          @foreach($data['groups'] as $value)
                <input type="hidden" value="{{ $value->id }}" id="{{ $value->id }}_id">
                <input type="hidden" value="{{ $value->page_title }}" id="{{ $value->id }}_title">
                <input type="hidden" value="{{ $value->menu_title }}" id="{{ $value->id }}_menu_title">
                <input type="hidden" value="{{ $value->menu_order }}" id="{{ $value->id }}_order">
            <tr>
              <td>
                {{ $value->page_title }}
              </td>
              <td>
                {{ $value->menu_title }}
              </td>
              <td>
                {{ $value->menu_order }}
              </td>
              <td>
                <a title="ویرایش کردن" href="#" onclick="edit('{{ $value->id }}');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </td>
    					<td>
                <a title="حذف کردن" href="{{ url('webmaster/blog/branch/del/'.$value->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>
              </td>
    				</tr>
          @endforeach
          @endif
			</tbody>
			</table>
      </div>
    </div>
@stop
