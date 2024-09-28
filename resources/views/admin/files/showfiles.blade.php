@extends('admin.layouts.default')
<?php $whereAmI = 'file-manager';?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
    {{$data['title']}}
@stop
@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/filemanager.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript">
  $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
    $(document).ready(function(){
      $('body').on('click', 'a.btn-close', function() {
        var hash = $(this).attr('data-hash');
        $('#'+hash+'-edit-row').remove();
      });
      $('body').on('click', 'a.btn-save', function() {
        var hash = $(this).attr('data-hash');
        saveFile(hash);
      });
    });
    function saveFile(hash)
    {
    	var title= $("#"+hash+"_title").val();
    	var author= $("#"+hash+"_author").val();
    	var publisher= $("#"+hash+"_publisher").val();
    	var tag= $("#"+hash+"_tag").val();
      var language= $("#"+hash+"_language").val();
      var cat= $("#"+hash+"_cat").val();
      var comment= $("#"+hash+"_comment").val();
      var ath= $("#"+hash+"_ath").val();
      var published= $("#"+hash+"_published").val();
      var like= $("#"+hash+"_like").val();
      var visit= $("#"+hash+"_visit").val();
      var des= $("#"+hash+"_des").val();
      var channel= $("#"+hash+"_class").val();

    	if(!title)
    	{
    		alert("عنوان خالی است");
    		return;
    	}
    	if(!channel)
    	{
    		alert("کاربر انتخاب نشده است");
    		return;
    	}
    	if(cat == "none")
    	{
    		alert("دسته انتخاب نشده است");
    		return;
    	}
      if(!language)
    	{
    		alert("زبان انتخاب نشده");
    		return;
    	}
      if(!published)
    	{
    		alert("وضعیت مشخص نشده.");
    		return;
    	}
    	if(!tag)
    	{
    		alert("تگ خالی است");
    		return;
    	}
        var data = {"command":"save",
        			"hash":hash,
              "title":title,
              "author":author,
              "publisher":publisher,
              "tag":tag,
              "language":language,
              "cat":cat,
              "comment":comment,
              "ath":ath,
              "published":published,
              "like":like,
              "visit":visit,
              "des":des,
              "channel":channel
        		};
        console.log(data);
        sendCommand(data,"saveadminmedia");
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
    function edit(hash)
    {
      if($('#'+hash+'-edit-row').length)
      {
        return;
      }
    	if(!hash)
        {
          alert("هش یا نام خالی است.");
          return;
        }
        var data = {"command":"geteditfile",
        			"hash":hash
        		};
        sendCommand(data,"geteditfile");
    }
    function radio(hash)
    {
    	if(!hash)
        {
          alert("هش یا نام خالی است.");
          return;
        }
        var data = {"command":"radio",
        			"hash":hash
        		};
        sendCommand(data,"radio");
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
        }else if(command == "radio")
        {
            resRadio(object,msg);
        }
        else if(command == "geteditfile")
        {
            resEditForm(object,msg);
        }
        else if(command == "saveadminmedia")
        {
            resEdit(object,msg,result);
        }
    }
    function resEdit(object,msg,result)
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
          var color = $(object).find('#color').html();
          notify(msg,'notice');
          $('#'+hash+'-edit-row').remove();
          var code = $(object).find('#code');

          var row = code.find('tr').html();

          var next = $("#"+hash+"-row").next();
          console.log(color);
          if(next.prop("tagName") != 'TR')
          {
            $('tbody').append('<tr '+color+' id="'+hash+'-row">'+row+'</tr>');
          }
          else
          {
            $("#"+hash+"-row").remove();
            next.before('<tr '+color+' id="'+hash+'-row">'+row+'</tr>');
          }
        }
    }
    function resEditForm(object,msg)
    {
      var myStatus;
      myStatus = $(object).find('#status').html();
      if(myStatus == "faild")
      {
          notify(msg,'error');
      }
      else
      {
        var code = $(object).find('#code').html();
        var hash = $(object).find('#hash').html();
        var newtr = $('<tr id="'+hash+'-edit-row"></tr>');
        newtr.html('<td colspan="9">'+code+'</td>');
        $("#"+hash+"-row").after(newtr);
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
    function resRadio(object,msg)
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
          var extera = $(object).find('#extera').html();
          if(extera == 12)
          {
            $('#'+hash+'-row').css('background-color','rgba(51, 102, 0,.3)');
            $('#radio-icon-'+hash+' .fa').removeClass().addClass('fa fa-share-square-o');
            $('#radio-icon-'+hash).attr('title','از رادیو خارج کن');
          }
          else
          {
            $('#'+hash+'-row').css('background-color','rgba(128, 242, 13,.3)');
            $('#radio-icon-'+hash+' .fa').removeClass().addClass('fa fa-share-alt');
            $('#radio-icon-'+hash).attr('title','به رادیو اضافه کن');
          }
            notify(msg,'notice');
        }
    }
  </script>
@stop

@section('content')
    <h1>{{$data['title']}}</h1>
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
      <div class="panel-heading">{{$data['title']}}</div>
      <div class="panel-body">
        <div class="color-info">
          <div style="background-color:rgba(128, 242, 13,.3)" class="col-md-5">منتشر شده</div>
          <div style="background-color:rgba(255, 128, 0,.5)" class="col-md-5">فقط آپلود شده</div>
          <div style="background-color:rgba(255, 0, 0,.3)" class="col-md-5">دلیت شده</div>
          <div style="background-color:rgba(0, 172, 237,.3)" class="col-md-5">ارسالی تلگرام</div>
          <div style="background-color:rgba(51, 102, 0,.3)" class="col-md-5">رادیو</div>
        </div>
        <div class="pagination">@include('pages.pagination', ['object' => $data["files"]])</div>
        <table class="table table-bordred table-striped">
				<thead>
					<tr>
						<th>عنوان</th>
						<th>نوع</th>
            <th>زمان</th>
            <th>تاریخ</th>
            <th>لایک/بازدید/دانلود</th>
            <th>کانال</th>
            <th>رادیو</th>
            <th>ویرایش</th>
            <th>حذف</th>
					</tr>
				</thead>
				<tbody>
          @if(@$data['files'])
          @foreach($data['files'] as $file)
            <tr  id="{{$file->hash}}-row"
              @if (@$file->radio)
                style="background-color:rgba(51, 102, 0,.3)"
              @elseif ($file->ispublished == '0')
                style="background-color:rgba(255, 128, 0,.5)"
              @elseif ($file->ispublished == '4')
                style="background-color:rgba(255, 0, 0,.3)"
              @elseif ($file->ispublished == '7')
                style="background-color:rgba(0, 172, 237,.3)"
              @elseif ($file->ispublished == '1')
                style="background-color:rgba(128, 242, 13,.3)"
              @endif
            >
              <td class="title-td">
                <a title="{{ $file->title }}" href="{{url('/s/'.$file->hash)}}">{{ $file->title }}</a>
              </td>
              <td>
                @if($file->type == 1)
                  <i class="fa fa-video-camera" aria-hidden="true"></i>
                @elseif($file->type == 2)
                  <i class="fa fa-book" aria-hidden="true"></i>
                @else
                  <i class="fa fa-music" aria-hidden="true"></i>
                @endif
              </td>
              <td>
                @if($file->type == 2)
                  {{ \App\Http\handyHelpers::ta_persian_num($file->pagetime) }}
                @else
                  {{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}
                @endif
              </td>
              <td>
                {{ \App\Http\handyHelpers::MTS($file->endate) }}
              </td>
              <td>
                {{$file->numdownload}}/{{$file->visit}}/{{$file->likes}}
              </td>
              <td>
                <a href="{{url('class/'.$file->user->hash)}}">{{$file->user->name}}</a>
              </td>
              <td>
                @if($file->type == 3)
                  @if (@$file->radio)
                    <a id="radio-icon-{{$file->hash}}" onclick="radio('{{$file->hash}}');return false;" title="از رادیو خارج کن" href="#"><i class="fa fa-share-square-o" aria-hidden="true"></i></a>
                  @else
                    <a id="radio-icon-{{$file->hash}}" onclick="radio('{{$file->hash}}');return false;" title="برو برای رادیو" href="#"><i class="fa fa-share-alt" aria-hidden="true"></i></i></a>
                  @endif
                @else
                  --
                @endif
              </td>
              <td>
                <a title="ویرایش کردن" onclick="edit('{{$file->hash}}');return false;" href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </td>
    					<td>
                <a title="حذف کردن" onclick="single_del('{{$file->hash}}');return false;" href="#"><i class="fa fa-trash-o" aria-hidden="true"></i>
              </td>
    				</tr>
          @endforeach
          @endif
			</tbody>
			</table>
      <div class="pagination">@include('pages.pagination', ['object' => $data["files"]])</div>
      </div>
    </div>
@stop
