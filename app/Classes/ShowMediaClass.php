<?php
namespace App\Classes;

use App\Files;
use App\Classes;
use App\Classes\SearchClass;
use App\Ads;
use App\Follow;
use Illuminate\Support\Facades\Auth;
use App\Classes\CommentClass;
use Request;

class ShowMediaClass{

  public static function returnMainMedia($hash)
  {
    $mainMedia = Files::where('hash',$hash)->where(function($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
    })->with('user')->get();
    if(count($mainMedia)<1)
    {
      return false;
    }
    return $mainMedia[0];
  }
  public static function returnSimilar($title,$tags,$count)
  {
    $similar = SearchClass::search($tags,$count);
    if(count($similar)<1)
    {
      $similar = SearchClass::search($title,$count);
      if(count($similar)<1)
      {
        $similar = Files::orderBy('id', 'desc')->limit($count)->with('user')->get();
      }
    }
    foreach ($similar as $value) {
      $value->user = Classes::where('hash',$value->class)->first();
    }
    return $similar;
  }
  public static function returnAds()
  {
    $data = array();
    $ads = Ads::where('identify','like','showmedia%')->get();
    foreach ($ads as $ad)
    {
        $data[$ad->identify] = $ad->ad;
    }
    return $data;
  }
  public static function returnClassMedia($hash,$count)
  {
    $files = Files::select('title','type','pagetime','hash','filetype','visit','class','path')->where('class',$hash)
    ->where(function($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
    })->with(array('user' => function($query){
        $query->select('id','hash','name');
    }))->orderBy('endate', 'desc')->limit($count)->get();

    $resultCode = '<div class="left-media-list">
                    <ul>';
    $i=1;
    foreach ($files as $file) {
      $icon = $picSize = $duration = '';
      if($file->type==1)
      {
        $icon='<span class="media-type"><i class="fa fa-video-camera" aria-hidden="true"></i></span>';
        $picSize = '2';
        $duration = \App\Http\handyHelpers::makeTimeString($file->pagetime);
      }
      elseif($file->type==2)
      {
        $icon='<span class="media-type"><i class="fa fa-book" aria-hidden="true"></i></span>';
        $picSize = '3';
        $duration = \App\Http\handyHelpers::ta_persian_num($file->pagetime). ' صفحه';
      }
      elseif($file->type==3)
      {
        $icon='<span class="media-type"><i class="fa fa-music" aria-hidden="true"></i></span>';
        $picSize = '2';
        $duration = \App\Http\handyHelpers::makeTimeString($file->pagetime);
      }
      $resultCode .= '
            <li title="'. $file->title .'">
              <a href="http://www.hobby9.com/s/'. $file->hash .'/'. $file->title .'">
                <div class="media-left-img">
                  <img alt="'. $file->title .'" src="http://www.hobby9.com/includes/returnpic.php?type='. $file->type .'&picid='. $file->hash .'&s='.$picSize.'&p='. $file->path .'" class="img-responsive">
                  '.$icon.'
                  <span class="duration">
                    '.$duration.'
                  </span>
                  <span class="watch-later"><i class="fa fa-heart-o" aria-hidden="true"></i></span>
                </div>
              </a>
              <div class="media-left-info">
                <h2 title="'. $file->title .'"><a href="http://www.hobby9.com/s/'. $file->hash .'/'. $file->title .'">'. $file->title .'</a></h2>
                <div>از <a href="http://www.hobby9.com/class/'. $file->user->hash .'">'. $file->user->name .'</a></div>
                <div>بازدید : '. \App\Http\handyHelpers::ta_persian_num($file->visit) .'</div>
              </div>
              <div style="clear:both"></div>
            </li>
        ';
      $i++;
    }
    $resultCode .='
        </ul>
      </div>';



    $resultCode = '<div id="code">'.$resultCode.'</div>
                   <div id="status">SUCCESS</div>';
    return $resultCode;
  }
  public static function isFollowed($hash)
  {
    if(Auth::check())
    {
      $class = Auth::user()->hash;
    }
    else
    {
      $class = Request::ip();
    }
    $follow = Follow::where("target",$hash)->where("user",$class)->where("type",'m')->first();
    if(count($follow)>0)
    {
      return true;
    }
    return false;
  }
  public static function returnComments($hash)
  {
    $comments = CommentClass::returnComments($hash);
    return $comments;
  }
}
 ?>
