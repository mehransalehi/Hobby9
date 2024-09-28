@extends('admin.layouts.default')
<?php $whereAmI = 'feedback';?>
@section('title')
    پیام های پشتیبانی
@stop

@section('style')
  <link rel="stylesheet" href="{{ URL::asset('css/admin/feedback.css') }}" type="text/css">
@stop

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){

    });
    function send(id)
    {
      str = $('#email-'+id).text().replace(/\s+/g, '');
      $('#email').val(str);
    }
  </script>
@stop

@section('content')
    <h1>پیام های کاربران</h1>
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
      <div class="panel-heading">لیست پیام های دریافتی</div>
      <div class="panel-body">
      <table id="branch-table" class="table table-bordred table-striped">
				<thead>
					<tr>
						<th>متن</th>
						<th>فرستنده</th>
            <th>IP</th>
            <th>تاریخ</th>
            <th>ایمیل</th>
            <th>پاسخ</th>
            <th>حذف</th>
					</tr>
				</thead>
				<tbody>
          @if(@$data['messages'])
          @foreach($data['messages'] as $msg)
            @if(!empty($msg))
            <tr id="row-{{$msg->id}}" @if($msg->is_read == 'u') class="warning" @endif>
              <td title="{{ $msg->feedback }}">
                ...
              </td>
              <td>
                @if(!empty($msg->sender) && strlen($msg->sender) == 32)
                  <a href="{{url('class/'.$msg->user->hash)}}">{{$msg->user->name}}</a>
                @else
                  NONE
                @endif
              </td>
              <td title="{{ $msg->ip }}">
                ...
              </td>
              <td>
                {{ \App\Http\handyHelpers::MTS($msg->date) }}
              </td>
              <td id="email-{{$msg->id}}">
                {{ $msg->email }}
              </td>
              <td>
                <a title="پاسخ" href="#" onclick="send('{{ $msg->id }}');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              </td>
    					<td>
                <a title="حذف کردن" href="{{ url('webmaster/feedback/msg/del/'.$msg->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i>
              </td>
    				</tr>
            @endif
          @endforeach
          @endif
			</tbody>
			</table>
      <form action="{{ url('webmaster/feedback/send/') }}" method="POST">
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
      </form>
      </div>
    </div>
@stop
