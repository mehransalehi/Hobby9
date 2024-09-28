@extends('layouts.default')
@section('title')
  بازیابی کلمه عبور
@endsection

<?php $whereAmI='reset'; ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/reset.css')?>" type="text/css">
@stop
@section('content')
@if ($data == 'success')
  <h1>پسورد جدید با موفقیت جایگزین شد</h1>
@elseif ($data == 'failed')
  <h1>اشکالی رخ داده است</h1>
@endif
@endsection
