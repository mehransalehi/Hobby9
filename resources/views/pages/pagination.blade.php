<?php
  if($whereAmI == 'search' || $whereAmI == 'channel' || $whereAmI == 'profile-search' || $whereAmI == 'tele-files' || $whereAmI == 'file-manager'|| $whereAmI == 'file-dl-manager')
    $perPage = config('co.itemSearchPage');
  elseif ($whereAmI == 'profile-filelist' || $whereAmI == 'profile-comment') {
    $perPage = config('co.profileFilelistPerPage');
  }
  elseif ($whereAmI == 'show-media') {
    $perPage = config('co.commentCount');
  }


  $currentPage = $object->currentPage();
  $from = ($currentPage * $perPage ) - $perPage +1;
  $to = $object->count() + $from -1;
  $from = \App\Http\handyHelpers::ta_persian_num($from);
  $to = \App\Http\handyHelpers::ta_persian_num($to);


?>
<div class="btn-group">
  @if($object->hasMorePages())
  <a href="{{ $object->nextPageUrl() }}" class="btn btn-info" title="صفحه بعدی "><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
  @endif
  <div class="btn btn-info">
  نمایش نتایج از
  {{ $from }}
  تا
  {{ $to }}
  </div>
  @if($currentPage != 1)
  <a href="{{ $object->previousPageUrl() }}" class="btn btn-info" title="صفحه قبلی"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
  @endif
</div>
