@extends('layouts.default-profile')
@section('metashare')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
  جستجوی عبارت {{$data['text']}}
@endsection

<?php $whereAmI='profile-search' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/search.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">
  function del(hash)
  {
      if(confirmSubmit())
      {
          document.location.href=THIS_URL+"/profile/filelist/del/"+hash;
      }
  }
  function confirmSubmit()
  {
      var agree=confirm("آیا از پاک شدن فایل مطمئن هستید؟");
      if (agree)
          return true ;
      else
          return false ;
  }

  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });
  function follow_media(hash)
  {
    var data = {"media":hash,"type":"media"};
    sendCommand(data,"unfollowmedia");
  }
  function doResponse(result,command)
  {
      console.log(result);
      var object = $('<div/>').append(result);
      var msg = $(object).find('#message').html();
      $("#msg-load").hide();
      if(command == "unfollowmedia")
      {
          resUnFoMedia(object,msg);
      }
  }
  function resUnFoMedia(object,msg)
  {
      var myStatus;
      myStatus = $(object).find('#status').html();
      hash = $(object).find('#hash').html();
      if(myStatus == "faild")
      {
          notify(msg,'error');
      }
      else if(myStatus == "success")
      {
          $("#row-"+hash).remove();
          notify(msg,'notice');
      }
  }
  </script>
@stop
<?php $user = $data['user']; ?>
@section('content')
  @if(count($data)<1)
    <div class="alert alert-hobby">
      اطلاعاتی درباره مطلب مورد نظر یافت نشد.
    </div>
  @else
    <div class="upper-staff">
			<div class="info-icons">
				<div><i class="fa fa-user-o" aria-hidden="true"></i> رسانه های شما</div>
				<div><i class="fa fa-thumbs-o-up"></i> رسانه های پسندیده شده</div>
			</div>
			<div class="order-icons"></div>
		</div>
		<hr class="gray"/>
    @if(@Session::get('status'))
      <ul>
        @if(@Session::get('status') == 'del_success')
          <li class="alert alert-success">با موفقیت حذف شد.</li>
        @elseif(@Session::get('status') == 'not_exsit')
          <li class="alert alert-danger">این فایل وجود ندارد</li>
        @endif
      </ul>
    @else
      <ul>
           @foreach ($errors->all() as $error)
               <li class="alert alert-danger">{{ $error }}</li>
           @endforeach
       </ul>
    @endif
    <div class="pagination">@include('pages.pagination', ['object' => $data])</div>
    <table id="branch-table" class="table table-bordred table-striped">
      <thead>
        <tr>
          <th>امکانات</th>
          <th><div>نوع / نام</div></th>
        </tr>
      </thead>
      @foreach ($data as $media)
        <tr id="row-{{$media->hash}}">
					<td>
            @if($media->class == Auth::user()->hash)
              <a title="ویرایش کردن" href="{{url('profile/filelist/edit/'.$media->hash)}}" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
              &nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;
              <a title="حذف کردن" href="#" onclick="del('{{$media->hash}}');return false;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            @else
              <a title="نمی پسندم" href="#" onclick="follow_media('{{$media->hash}}');return false;"><i class="fa fa-thumbs-o-down"></i></a>
            @endif
          </td>
					<td>
            <div>
              @if($media->class == Auth::user()->hash)
                <i class="fa fa-user-o" aria-hidden="true"></i>
              @else
                <i class="fa fa-thumbs-o-up"></i>
              @endif
              &nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;
            </div>
            <div> &nbsp;&nbsp;&nbsp;
              @if($media->type == 1)
                <i class="fa fa-video-camera" aria-hidden="true"></i>
              @elseif($media->type == 2)
                <i class="fa fa-book" aria-hidden="true"></i>
              @elseif($media->type == 3)
                <i class="fa fa-music" aria-hidden="true"></i>
              @endif
              / &nbsp;&nbsp;&nbsp;
            </div>
            <a title="{{$media->title}}" href="{{url('s/'.$media->hash.'/'.\App\Http\handyHelpers::UE($media->title))}}">{{$media->title}}</a>
          </td>
				</tr>
      @endforeach
      </tbody>
    </table>
    <div class="pagination">@include('pages.pagination', ['object' => $data])</div>
  @endif
@endsection
