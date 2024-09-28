<div id="status">{{$data['status']}}</div>
@if(@$data['message'])
  <div id="message">{{$data['message']}}</div>
@endif
@if(@$data['hash'])
  <div id="hash">{{$data['hash']}}</div>
@endif
@if(@$data['file'])
  <?php $file = $data['file']; ?>
  <div id="color">
  @if ($file->ispublished == '1')
    style="background-color:rgba(128, 242, 13,.3)"
  @elseif ($file->ispublished == '0')
    style="background-color:rgba(255, 128, 0,.5)"
  @elseif ($file->ispublished == '4')
    style="background-color:rgba(255, 0, 0,.3)"
  @elseif ($file->ispublished == '7')
    style="background-color:rgba(0, 172, 237,.3)"
  @elseif ($file->ispublished == '12')
    style="background-color:rgba(51, 102, 0,.3)"
  @endif
  </div>
    <table id="code">
  <tr id="{{$file->hash}}-row">
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
        @if ($file->ispublished == 12)
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
</table>
@endif
