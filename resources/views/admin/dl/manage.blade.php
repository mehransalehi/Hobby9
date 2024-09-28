@extends('admin.layouts.default')
<?php $whereAmI = 'file-dl-manage';?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
    مدیریت دانلود از لینک
@stop
@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/telefiles.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript">
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function(){
      $('#global-title').change(function(){
        $('.titles').val($(this).val());
      });
      $('#global-tag').change(function(){
        $('.tags').val($(this).val());
      });
      $('#global-des').change(function(){
        $('.deses').val($(this).val());
      });
      $('#global-branch').change(function(){
          var text = $('#global-branch option:selected').val();
          console.log(text);
          $(".share-cat").each(function() {
            $(this).find("option:selected").removeAttr("selected");
            $(this).find('option[value="'+text+'"]').attr('selected','selected');
          });
      });

    });
    function saveFile(hash)
    {
    	var title= $("#"+hash+"_title").val();
    	var tags= $("#"+hash+"_tags").val();
    	var des= $("#"+hash+"_exp").val();
      var cat= $("#"+hash+"_cat").val();
    	if(!title)
    	{
    		alert("عنوان خالی است");
    		return;
    	}
    	if(cat == "none")
    	{
    		alert("دسته انتخاب نشده است");
    		return;
    	}
    	else if(!tags)
    	{
    		alert("تگ خالی است");
    		return;
    	}
        var data = {"command":"save",
        			"hash":hash,
        			"title":title,
        			"tags":tags,
        			"des":des,
              "cat":cat
        		};
        console.log(data);
        sendCommand(data,"managersave");
    }
    function single_del(hash)
    {
    	if(!hash)
        {
          alert("هش یا نام خالی است.");
          return;
        }
        var data = {"command":"singledel",
        			"hash":hash
        		};
        sendCommand(data,"singledel");
    }
    function doResponse(result,command)
    {
        console.log(result);
        $("#msg-load").hide();
        var object = $('<div/>').append(result);
        var msg = $(object).find('#message').html();
        if(command == "singledel")
        {
            resDel(object,msg);
        }else if(command == "managersave")
        {
            resDel(object,msg);
        }
    }
    function resDel(object,msg)
    {
        var myStatus;
        myStatus = $(object).find('#status').html();
        if(myStatus == "faild")
        {
            notify(msg,'error');
        }
        else if(myStatus == "success")
        {
          var hash = $(object).find('#hash').html();
            notify(msg,'notice');
            $('#'+hash+'-row').remove();
        }
    }
  </script>
@stop

@section('content')
    <h1>لیست رسانه های دانلود شده از وب مستر</h1>
    <hr class="main-hr"/>
    @if(@session('status'))
      <ul>
        @if(@session('status') == 'del_success')
          <li class="alert alert-success">با موفقیت خذف شد</li>
        @elseif(@session('status') == 'email_success')
          <li class="alert alert-success">با موفقیت فرستاده شد.</li>
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
      <div class="panel-heading">رسانه های دانلود شده از یوتیوب یا لینک مستقیم</div>
      <div class="panel-body">
        <p class="well">با تغییر فیلد های کلی کل اطلاعات لیست زیر عوض می شود.</p>
        <div class="col-sm-3"><textarea class="form-control" id="global-title" placeholder="عنوان کلی"></textarea></div>
        <div class="col-sm-3"><textarea class="form-control" id="global-tag" placeholder="تگ کلی"></textarea></div>
        <div class="col-sm-3"><textarea class="form-control" id="global-des" placeholder="توضیحات کلی"></textarea></div>
        <div class="col-sm-3">
          <select id="global-branch" class="form-control">
            <option value="">دسته کلی</option>
            @foreach(config('co.categorys') as $cat)
              <option value="{{$cat}}">{{$cat}}</option>
            @endforeach
          </select>
        </div>
        <div class="pagination">@include('pages.pagination', ['object' => $data["medias"]])</div>
        <table id="branch-table" class="table table-bordred table-striped">
  				<thead>
  					<tr>
              <th>I</th>
  						<th>عنوان</th>
  						<th>تگ</th>
  						<th>توضیحات</th>
  						<th>دسته</th>
  						<th>کاربر</th>
  						<th>ذخیره</th>
  						<th>حذف</th>
  					</tr>
  				</thead>
  				<tbody>
            @if(@$data['medias'])
            @foreach($data['medias'] as $media)
              @if(!empty($media))
              <tr id="{{$media->hash}}-row">
                <td>
                  {{$media->id}}
                </td>
                <td>
                  <textarea id="{{$media->hash}}_title" class="form-control input-sm titles">{{$media->title}}</textarea>
                </td>
                <td>
                  <textarea id="{{$media->hash}}_tags" class="form-control input-sm tags">{{$media->tags}}</textarea>
                </td>
                <td>
                  <textarea id="{{$media->hash}}_exp" class="form-control input-sm deses">{{$media->explenation}}</textarea>
                </td>
                <td>
                  <select class="form-control input-sm share-cat" name="category" id="{{$media->hash}}_cat" required="required" hash="{{$media->hash}}">
                    <option value="">دسته را انتخاب کنید.</option>
                    @foreach(config('co.categorys') as $cat)
                      <option value="{{$cat}}" @if($media->branch == $cat) selected="selected" @endif>{{$cat}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <a href="{{url('class/'.$media->user->hash)}}">{{$media->user->name}}</a>
                </td>
                <td>
                  <a title="ذخیره" href="#" onclick="saveFile('{{$media->hash}}');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </td>
      					<td>
                  <a title="حذف کردن" onclick="single_del('{{$media->hash}}');return false;" href="#"><i class="fa fa-trash-o" aria-hidden="true"></i>
                </td>
      				</tr>
              @endif
            @endforeach
            @endif
  			</tbody>
  			</table>
        <div class="pagination">@include('pages.pagination', ['object' => $data["medias"]])</div>
      <!--<form action="{{ url('webmaster/feedback/send/') }}" method="POST">
        {{ csrf_field() }}
        <input name="id" type="hidden" value="none">
        <fieldset>
          <div class="form-group">
            <label for="sel1">ایمیل</label>
            <input id="email" name="email" class="form-control" type="text">
          </div>
          <div class="form-group">
            <textarea id="text" name="text" class="form-control" placeholder="متن"></textarea>
          </div>
          <div class="btn-submit form-group">
            <input id="btn-submit" class="btn btn-hobby form-control btn-ord" type="submit" value="ارسال">
          </div>
        </fieldset>
      </form>-->
      </div>
    </div>
@stop
