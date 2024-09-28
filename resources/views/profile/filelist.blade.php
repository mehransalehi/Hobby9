@extends('layouts.default-profile')

@section('title')
  لیست رسانه های شما
@endsection

<?php $whereAmI='profile-filelist' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/filelist.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $(".span-expand").bind( "click",function(){
            var hash = $(this).attr("data-hash");
            $("#unexpand-"+hash).hide();
            $("#expand-"+hash).show();
      });
    });
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
  </script>
@stop

@section('content')
  <div class="upper-staff">
    <div class="select-icons">
      <a @if($data['type'] == 'all')class="active-key"@endif title="نمایش همه" href="{{url('profile/filelist/all/')}}"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i></a>
      <a @if($data['type'] == 'video')class="active-key"@endif title="فقط ویدیو ها" href="{{url('profile/filelist/video/')}}"><i class="fa fa-video-camera" aria-hidden="true"></i></a>
      <a @if($data['type'] == 'sound')class="active-key"@endif title="فقط آهنگ ها" href="{{url('profile/filelist/sound/')}}"><i class="fa fa-music" aria-hidden="true"></i></a>
      <a @if($data['type'] == 'ebook')class="active-key"@endif title="فقط کتاب ها" href="{{url('profile/filelist/ebook/')}}"><i class="fa fa-book" aria-hidden="true"></i></span></a>
    </div>
  </div>
  <hr class="gray"/>
  @if(count($data["files"])<=0)
    <div class="row">
      <div class="media-div col-xs-12">
        <div class="well well-sm">
          هیچ رسانه ای آپلود نشده است. برای آپلود رسانه <a href="{{url('profile/upload/')}}">اینجا</a> کلیک کنید.
        </div>
      </div>
    </div>
  @else
    <div class="pagination">@include('pages.pagination', ['object' => $data["files"]])</div>
    <div class="row">
			<div class="media-div col-xs-12">
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
				<ul class="media-lists">
          @foreach ($data["files"] as $media)
            <li>
              <div class="media-border">
      					<div class="media-exp">
      						<a href="{{url('s/'.$media->hash.'/'.\App\Http\handyHelpers::UE($media->title))}}" title="@if($media->ispublished == 2) اطلاعات این فایل وارد نشده است @else {{$media->title}} @endif">
      							<img @if($media->ispublished == 2) style=" border:1px solid red; " @endif src="{{url('/includes/returnpic.php?type='.$media->type.'&picid='.$media->hash)}}&s=@if($media->type == 2 ) 3 @else 2 @endif&p={{ $media->path }}" alt="{{ $media->title }}" class="img-thumbnail" height="130px">
                    @if($media->type == 1)
                      <span class="media-type"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                    @elseif($media->type == 2)
                      <span class="media-type"><i class="fa fa-book" aria-hidden="true"></i></span>
                    @else
                      <span class="media-type"><i class="fa fa-music" aria-hidden="true"></i></span>
                    @endif
      							<span class="duration">
                      @if($media->type == 2)
                        {{ \App\Http\handyHelpers::ta_persian_num($media->pagetime) }} صفحه
                      @else
                        {{ \App\Http\handyHelpers::makeTimeString($media->pagetime) }}
                      @endif
                    </span>
      						</a>
      					</div>
      					<div class="media-staff">
      						<a title="ویرايش مشخصات این فايل" href="{{url('profile/filelist/edit/'.$media->hash)}}" target="_blank" style="margin-left:40px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                  <a title="حذف اين فايل" href="#" onclick="del('{{$media->hash}}');" style="margin-left:10px"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  <hr class="gray"/>
                  <div>
                  	<p title="تاریخ ایجاد فایل"> <i class="fa fa-calendar" aria-hidden="true"></i></span>&nbsp;&nbsp; {{ \App\Http\handyHelpers::MTS($media->endate) }}</p>
                  	<p title="تعداد بازدید این فایل"> <i class="fa fa-eye" aria-hidden="true"></i></span>&nbsp;&nbsp; {{$media->visit}} بازدید</p>
                  	<p title="تعداد دفعات دانلود شده"> <i class="fa fa-cloud-download" aria-hidden="true"></i></span>&nbsp;&nbsp; {{$media->numdownload}} دانلود</p>
                  </div>
      					</div>
      					<div class="media-exp-mid">
      						<a href="{{url('s/'.$media->hash.'/'.\App\Http\handyHelpers::UE($media->title))}}">
                    <h2
                      @if($media->ispublished == 2)
                        style="color:red"
                      @endif>
                      @if($media->ispublished == 2)
                         اطلاعات این فایل وارد نشده است
                      @else
                        {{$media->title}}
                        @if($media->coQueue)
                           <span style="color:orange">| در صف کانورت قرار دارد</span> 
                        @endif
                        @if($data['topMedia'] == $media->hash)
                           <span style="color:green">(رسانه شاخص)</span>
                        @endif
                      @endif
                      </h2>
                    </a>
  						      @if(strlen($media->explenation) > 250)
                      <p id="unexpand-{{$media->hash}}"><?php echo substr($media->explenation,0,251);?>...
                      <span data-hash="{{$media->hash}}" class="span-expand"> بیشتر </span></p>
                      <p id="expand-{{$media->hash}}" class="expand-p">{{$media->explenation}}</p>
                    @else
                      <p>{{$media->explenation}}</p>
                    @endif
      					</div>
    					<div style="clear:both"></div>
    				</li>
          @endforeach
        </ul>
      </div>
    </div>
    <div class="pagination">@include('pages.pagination', ['object' => $data["files"]])</div>
  @endif
@stop
