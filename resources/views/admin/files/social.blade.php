@extends('admin.layouts.default')
<?php $whereAmI = 'file-social';?>
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
    function social(hash,type)
    {
    	if(!hash)
        {
          alert("هش یا نام خالی است.");
          return;
        }
        var data = {"command":"socialsend",
        			"hash":hash,
              "type":type
        		};
        $("#"+hash+"-"+type).find('a').attr("click-b",$("#"+hash+"-"+type).find('a').attr("onclick")).attr("onclick","");
        $("#"+hash+"-"+type).find('i').attr("class","fa fa-spinner fa-pulse");
        if(type == 'all')
        {
          $("#"+hash+"-face").find('a').attr("click-b",$("#"+hash+"-face").find('a').attr("onclick")).attr("onclick","").find('i').attr("class","fa fa-spinner fa-pulse");
          $("#"+hash+"-twitter").find('a').attr("click-b",$("#"+hash+"-twitter").find('a').attr("onclick")).attr("onclick","").find('i').attr("class","fa fa-spinner fa-pulse");
          $("#"+hash+"-google").find('a').attr("click-b",$("#"+hash+"-google").find('a').attr("onclick")).attr("onclick","").find('i').attr("class","fa fa-spinner fa-pulse");
          $("#"+hash+"-insta").find('a').attr("click-b",$("#"+hash+"-insta").find('a').attr("onclick")).attr("onclick","").find('i').attr("class","fa fa-spinner fa-pulse");
        }
        sendCommand(data,"socialsend");
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
        }else if(command == "socialsend")
        {
            resSocial(object,msg);
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
    function resSocial(object,msg)
    {
      var btnClasses = {"face":"fa fa-facebook",
                        "google":"fa fa-google-plus",
                        "twitter":"fa fa-twitter",
                        "insta":"fa fa-instagram",
                        "all":"fa fa-reply-all"};
        var myStatus;
        myStatus = $(object).find('#status').html();

        var hash = $(object).find('#hash').html();
        var extera = $(object).find('#extera').html();
        $("#"+hash+"-face").find('a').attr("onclick",$("#"+hash+"-face").find('a').attr("click-b")).find('i').attr("class","fa fa-facebook");
        $("#"+hash+"-twitter").find('a').attr("onclick",$("#"+hash+"-twitter").find('a').attr("click-b")).find('i').attr("class","fa fa-twitter");
        $("#"+hash+"-google").find('a').attr("onclick",$("#"+hash+"-google").find('a').attr("click-b")).find('i').attr("class","fa fa-google-plus");
        $("#"+hash+"-insta").find('a').attr("onclick",$("#"+hash+"-insta").find('a').attr("click-b")).find('i').attr("class","fa fa-instagram");
        $("#"+hash+"-all").find('a').attr("onclick",$("#"+hash+"-all").find('a').attr("click-b")).find('i').attr("class","fa fa-reply-all");
        console.log(extera);
        if(extera !== undefined && extera !== null)
        {
          var btns = extera.split("|");
          btns.forEach(function(item,index){
            $("#"+hash+"-"+item).find('i').attr("class","fa fa-check-circle").css("color","green");
            $("#"+hash+"-"+item).find('a').attr("onclick","").attr("click-b","");
            $("#"+hash+"-"+item).attr("id","");
          });
        }

        if(myStatus == "faild")
        {
            notify(msg,'error');
        }
        else if(myStatus == "success")
        {
          notify(msg+" "+extera,'notice');
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
            <th>کانال</th>
            <th>FB</th>
            <th>Twitter</th>
            <th>G+</th>
            <th>Insta</th>
            <th>ارسال به همه</th>
            <th>زمان</th>
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
                <a href="{{url('class/'.$file->user->hash)}}">{{$file->user->name}}</a>
              </td>
              <td id="{{$file->hash}}-face"><a onclick="social('{{$file->hash}}','face');return false;" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></td>
              <td id="{{$file->hash}}-twitter"><a onclick="social('{{$file->hash}}','twitter');return false;" href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></td>
              <td id="{{$file->hash}}-google"><a onclick="social('{{$file->hash}}','google');return false;" href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></td>
              <td id="{{$file->hash}}-insta"><a onclick="social('{{$file->hash}}','insta');return false;" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></td>
              <td id="{{$file->hash}}-all"><a onclick="social('{{$file->hash}}','all');return false;" href="#"><i class="fa fa-reply-all" aria-hidden="true"></i></a></td>
              <td>
                @if($file->type == 2)
                  {{ \App\Http\handyHelpers::ta_persian_num($file->pagetime) }}
                @else
                  {{ \App\Http\handyHelpers::makeTimeString($file->pagetime) }}
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
