@extends('admin.layouts.default')
<?php $whereAmI = 'tele-files';?>
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
    دریافتی از تلگرام
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
      $('.user-group').change(function(){
  			var hash = $(this).attr('hash');
  			console.log(hash);
        var text =  $(this).val()
  			var item ='';
  			console.log(text);
  			if(text == 'khande')
  			{
  				 item = USERS_KHANDE[Math.floor(Math.random()*USERS_KHANDE.length)];
  			}
  			else if (text == 'havades') {
  				item = USERS_HAVADES[Math.floor(Math.random()*USERS_HAVADES.length)];
  			}
  			else if (text == 'animal') {
  				 item = USERS_ANIMAL[Math.floor(Math.random()*USERS_ANIMAL.length)];
  			}
  			else if (text == 'tec') {
  				 item = USERS_TEC[Math.floor(Math.random()*USERS_TEC.length)];
  			}
  			else if (text == 'anime') {
  				 item = USERS_ANIME[Math.floor(Math.random()*USERS_ANIME.length)];
  			}
  			else if (text == 'honar') {
  				 item = USERS_HONAR[Math.floor(Math.random()*USERS_HONAR.length)];
  			}
  			else if (text == 'music') {
  				 item = USERS_MUSIC[Math.floor(Math.random()*USERS_MUSIC.length)];
  			}
  			else if (text == 'tabiat') {
  				 item = USERS_TABIAT[Math.floor(Math.random()*USERS_TABIAT.length)];
  			}
  			else if (text == 'amoozesh') {
  				 item = USERS_AMOOZESHI[Math.floor(Math.random()*USERS_AMOOZESHI.length)];
  			}
  			else if (text == 'mote') {
  				 item = USERS_MOTE[Math.floor(Math.random()*USERS_MOTE.length)];
  			}
  			else if (text == 'varzeshi') {
  				 item = USERS_VARZESHI[Math.floor(Math.random()*USERS_VARZESHI.length)];
  			}
  			console.log(item);
  			$("#"+hash+"_userhash").val(item);
      });
      $('.share-cat').change(function(){
        var hash = $(this).attr('hash');
        console.log(hash);
        var text =  $(this).val()
        var item ='';
        console.log(text);
        if(text == 'طنز' || text == 'تفريحی')
        {
           item = USERS_KHANDE[Math.floor(Math.random()*USERS_KHANDE.length)];
        }
        else if (text == 'حوادث') {
          item = USERS_HAVADES[Math.floor(Math.random()*USERS_HAVADES.length)];
        }
        else if (text == 'حيوانات') {
           item = USERS_ANIMAL[Math.floor(Math.random()*USERS_ANIMAL.length)];
        }
        else if (text == 'کامپيوتر' || text == 'علمی' || text == 'مهندسی') {
           item = USERS_TEC[Math.floor(Math.random()*USERS_TEC.length)];
        }
        else if (text == 'کارتون') {
           item = USERS_ANIME[Math.floor(Math.random()*USERS_ANIME.length)];
        }
        else if (text == 'هنری') {
           item = USERS_HONAR[Math.floor(Math.random()*USERS_HONAR.length)];
        }
        else if (text == 'جدیدترین موزیک ها') {
           item = USERS_MUSIC[Math.floor(Math.random()*USERS_MUSIC.length)];
        }
        else if (text == 'طبيعت') {
           item = USERS_TABIAT[Math.floor(Math.random()*USERS_TABIAT.length)];
        }
        else if (text == 'آموزشی') {
           item = USERS_AMOOZESHI[Math.floor(Math.random()*USERS_AMOOZESHI.length)];
        }
        else if (text == 'ورزشی') {
           item = USERS_VARZESHI[Math.floor(Math.random()*USERS_VARZESHI.length)];
        }
        else
        {
           item = USERS_MOTE[Math.floor(Math.random()*USERS_MOTE.length)];
        }
        console.log(item);
        $("#"+hash+"_userhash").val(item);
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
    function saveFile(hash,isTel)
    {
    	var title= $("#"+hash+"_title").val();
    	var tags= $("#"+hash+"_tags").val();
    	var user= $("#"+hash+"_userhash").val();
    	var des= $("#"+hash+"_exp").val();
      var cat= $("#"+hash+"_cat").val();
    	if(!title)
    	{
    		alert("عنوان خالی است");
    		return;
    	}
    	if(user == "none")
    	{
    		alert("کاربر انتخاب نشده است");
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
        			"user":user,
        			"des":des,
              "cat":cat,
    					"isTel":isTel
        		};
        console.log(data);
        sendCommand(data,"telesave");
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
        }else if(command == "telesave")
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
    <h1>لیست رسانه های آپلود شده از تلگرام</h1>
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
      <div class="panel-heading">رسانه های دریافتی از تلگرام</div>
      <div class="panel-body">
        <script type='text/javascript'>
          @foreach ($data['users'] as $key => $value)
            var {{$key}} = {!! json_encode($value) !!};
          @endforeach
    		</script>
        <div class="pagination">@include('pages.pagination', ['object' => $data["medias"]])</div>
        <table id="branch-table" class="table table-bordred table-striped">
  				<thead>
  					<tr>
              <th>I</th>
  						<th>عنوان</th>
  						<th>تگ</th>
  						<th>توضیحات</th>
  						<th>دسته</th>
  						<th>نوع کاربر</th>
  						<th>ذخیره</th>
  						<th>حذف</th>
  					</tr>
  				</thead>
  				<tbody>
            @if(@$data['medias'])
            @foreach($data['medias'] as $media)
              @if(!empty($media))
              <tr id="{{$media->hash}}-row"
                @if ($media->type == 1 || $media->type == 3)
                  @if(!\App\Http\handyHelpers::isTele($media->pagetime))
                    class="danger"
                  @else
                    @if($media->type == 1)
                      class="Info"
                    @elseif ($media->type == 3)
                      class="success"
                    @else
                      class="warning"
                    @endif
                  @endif
                @endif
                >
                <?php
                  $userCode ='none';
                  if($media->branch != 'none' && $media->branch)
                  {
                    $index = $data['fetch'][$media->branch];
                    $rand_keys = array_rand($data['users'][$index], 1);
              			$userCode = $data['users'][$index][$rand_keys];
                  }
                  elseif ($media->type == 3) {
                    $rand_keys = array_rand($data['users']['USERS_MUSIC'], 1);
              			$userCode = $data['users']['USERS_MUSIC'][$rand_keys];
                  }
                ?>
                <input id="{{$media->hash}}_userhash" type="hidden" value="{{$userCode}}">
                <td>
                  {{$media->id}}
                </td>
                <td>
                  <textarea id="{{$media->hash}}_title" class="form-control input-sm">{{$media->title}}</textarea>
                </td>
                <td>
                  <textarea id="{{$media->hash}}_tags" class="form-control input-sm">{{$media->tags}}</textarea>
                </td>
                <td>
                  <textarea id="{{$media->hash}}_exp" class="form-control input-sm">{{$media->explenation}}</textarea>
                </td>
                <td>
                  <select class="form-control input-sm share-cat" name="category" id="{{$media->hash}}_cat" required="required" hash="{{$media->hash}}">
                    <option value="">دسته را انتخاب کنید.</option>
                    @foreach($data['cat'] as $cat)
                  		<option @if($media->branch == $cat || ($cat == 'جدیدترین موزیک ها' && $media->type==3)) selected="selected" @endif>{{$cat}}</option>
                  	@endforeach
                  </select>
                </td>
                <td>
                  <select class="form-control input-sm user-group" id="{{$media->hash}}_user-group" hash="{{$media->hash}}">
          					<option value="none">
                      @if ($userCode == 'none')
                        NONE
                      @else
                        انتخاب شده
                      @endif
                    </option>
          					<option value="khande">خنده</option>
          					<option value="havades">حوادث</option>
          					<option value="animal">حیوانات</option>
          					<option value="tec">تکنولوژی</option>
          					<option value="anime">انیمیشن</option>
          					<option value="honar">هنری</option>
          					<option value="music">موزیک</option>
          					<option value="tabiat">طبیعت</option>
          					<option value="amoozesh">آموزشی</option>
          					<option value="mote">متفرقه</option>
          					<option value="varzeshi">ورزشی</option>
          				</select>
                </td>
                <td>
                  <a title="ذخیره" href="#" onclick="saveFile('{{$media->hash}}',@if(!\App\Http\handyHelpers::isTele($media->pagetime)) '1' @else '0' @endif);return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
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
