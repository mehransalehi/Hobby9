@extends('layouts.default')

<?php $whereAmI='dl-media' ?>
@section('title')
  دانلود {{ $data->title }} | HOBBY9
@stop

@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/notfound.css')?>" type="text/css">
@stop

@section('script')
@stop

@section('content')
  <div class="container">
		<div class="col-xs-12">
			<p class="p-head">۴۰۴ - صفحه پیدا نشد</p>
      <p class="p-des">این صفحه موجود نمی باشد لطفا در صورت نیاز از طریق لینک های پایین سایت با ما در ارتباط باشید.</p>
		</div>
	</div>
@stop
