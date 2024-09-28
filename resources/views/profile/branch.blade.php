@extends('layouts.default-profile')

@section('title')
    ویرایش دسته ها
@endsection

<?php $whereAmI='profile-filelist' ?>
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/profile/branch.css')?>" type="text/css">
@stop
@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $(".fa-pencil-square-o").bind( "click", function() {
             var hash = $(this).closest( "td" ).attr("hash-data");
             var name = $("#branch-name-"+hash).text();
             $("#branch-name").val(name);
             $("#branch-name").css("border-color","#cccccc");
             $("#branch-name").css("border-color","green");
             $("#branch-name").focus();
             $(".branch-form h2").html('ویرایش طبقه .:. &nbsp;&nbsp;<span style="color:green">'+name+'</span>&nbsp;&nbsp; .:.');
             $("#btn-submit").val("اعمال تعغییرات");
             $("#branch-hash").val(hash);
             $("#branch-form").attr("action",THIS_URL+"/profile/branch/edit/"+hash);
             if(!$("#def-btn").length)
             $("#inputs-div").append('<div onclick="resetForm();" id="def-btn" class="btn btn-hobby">انصراف</div>');
         });
    });
    function resetForm()
    {
        $("#branch-name").val("");
        $("#branch-name").css("border-color","#cccccc");
        $(".branch-form h2").html('ساخن طبقه جدید');
        $("#btn-submit").val("ساختن");
        $("#branch-hash").val('None');
        $("#branch-form").attr("action",THIS_URL+"profile/branch/save/");
        $("#def-btn").remove();
    }
  </script>
@stop

@section('content')
  @if(@Session::get('status'))
    <ul>
      @if(@Session::get('status') == 'del_success')
        <li class="alert alert-success">با موفقیت حذف شد.</li>
      @elseif(@Session::get('status') == 'save_success')
        <li class="alert alert-success">با موفقیت ذخیره شد.</li>
      @endif
    </ul>
  @elseif (count($errors->all())>0)
    <ul>
         @foreach ($errors->all() as $error)
             <li class="alert alert-danger">{{ $error }}</li>
         @endforeach
     </ul>
  @endif
@if(count($data['branches'])<1)
  <div class="row">
    <div class="media-div col-xs-12">
      <div class="well well-sm">
        طبقه ای ساخته نشده است.
      </div>
    </div>
  </div>
@else
  <table id="branch-table" class="table table-bordred table-striped">
    <thead>
      <th>حذف</th>
      <th>ویرایش</th>
      <th>تعداد رسانه ها</th>
      <th>نام</th>
    </thead>
    <tbody>
    @foreach ($data['branches'] as $branch)
      <tr>
				<td><a href="{{url('profile/branch/del/'.$branch->hash)}}"<div><i class="fa fa-trash-o" aria-hidden="true"></i></div></a></td>
				<td hash-data="{{$branch->hash}}"><div><i id="edit-branch" class="fa fa-pencil-square-o" aria-hidden="true"></i></div></td>
				<td>{{count($branch->files)}}</td>
				<td id="branch-name-{{$branch->hash}}">{{$branch->name}}</td>
			</tr>
    @endforeach
    </tbody>
  </table>
  <hr class="gray">
@endif
<div class="branch-form">
  <h2>ساخت طبقه جدید : </h2>
  <form id="branch-form" action="{{url('profile/branch/')}}" method="POST">
    {{ csrf_field() }}
    <fieldset>
      <div class="form-group row">
          <input id="branch-name" class="form-control" name="name" placeholder="نام طبقه"/>
        </div>
        <div id="inputs-div" class="form-group row">
          <input id="btn-submit" class="btn btn-hobby" type="submit" value="ساختن">
          <input id="branch-hash" name="hash" type="hidden" value="None">
        </div>
    </fieldset>
  </form>
  </form>
</div>
@endsection
