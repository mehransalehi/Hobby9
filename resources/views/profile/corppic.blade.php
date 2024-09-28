@extends('layouts.default-profile')

@section('title')
  بریدن عکس کانال
@endsection

<?php $whereAmI='profile-corppic' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/setting.css')?>" type="text/css">
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/Jcrop.css')?>" type="text/css">
<style>
  header{
    z-index:1000 !important;
  }
</style>
@stop
@section('script')
  <script src="<?php echo URL::asset('js/profile/Jcrop.min.js')?>"></script>';
  <script type="text/javascript">
  $(document).ready(function(){
     $('.cropper > img').Jcrop({
              onSelect: setCoords,
              onChange: setCoords,
              aspectRatio: 10 / 9,
              setSelect:   [ 50, 50, 210, 200 ],
          });
  });
  function setCoords(c)
  {
        // variables can be accessed here as
        // c.x, c.y, c.x2, c.y2, c.w, c.h
        $("#x1").val(c.x);
        $("#x2").val(c.x2);
        $("#y1").val(c.y);
        $("#y2").val(c.y2);
        $("#w").val(c.w);
        $("#h").val(c.h);

  };

  </script>
@stop

@section('content')
  @if(@Session::get('status'))
    <ul>
      @if(@Session::get('status') == 'save_success')
        <li class="alert alert-success">با موفقیت ویرایش شد</li>
      @elseif(@Session::get('status') == 'faild')
        <li class="alert alert-danger">{{Session::get('message')}}</li>
      @endif
    </ul>
  @else
    <ul>
         @foreach ($errors->all() as $error)
             <li class="alert alert-danger">{{ $error }}</li>
         @endforeach
     </ul>
  @endif
  <form action="{{url('/profile/setting/pic/corp/')}}" method="POST">
    {{ csrf_field() }}
    <div class="cropper">
      <img src="{{$data['url']}}" alt="Picture" style="width:{{$data['width']}}px !important;height:{{$data['height']}}px !important">
    </div>
    <fieldset>
      <p class="well well-sm">تکه ای از عکس که داخل کادر با رنگ روشن قرار گرفته است به عنوان آواتار شما انتخاب می شود.</p>
      <div class="form-group row">
        <input class="btn btn-success" type="submit" value="تایید">
      </div>
    </fieldset>
    <input type="hidden" id="x1" name="x1">
    <input type="hidden" id="x2" name="x2">
    <input type="hidden" id="y1" name="y1">
    <input type="hidden" id="y2" name="y2">
    <input type="hidden" id="h" name="h">
    <input type="hidden" id="w" name="w">
    <input type="hidden" name="ext" value="{{$data['ext']}}">
    <input type="hidden" name="hash" value="{{$data['hash']}}">
  </form>
@endsection
