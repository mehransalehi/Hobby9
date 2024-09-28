<?php
namespace App\Classes;

use App\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\CommentReport;
use Request;
use App\Files;
use Exception;


class CommentClass {
  public static function saveComment($inputs)
  {
    if(!Auth::check())
    {
      return true;
    }
    $class = Auth::user()->hash;
    $hash = md5($class.$inputs['hash'].date("jS F Y").mt_rand(10000,99999));
    $comment = new Comment;
    $comment->hash = $hash;
    $comment->comment = $inputs['text'];
    $comment->file = $inputs['hash'];
    $comment->user_hash = $class;
    $comment->likes = 0;
    $comment->dislike = 0;
    $comment->m_date = DB::raw('NOW()');
    $comment->is_read = 'n';
    $comment->replay = 'None';

    $comment->save();

    $comment = Comment::where('hash',$hash)->with('user')->first();
    return $comment;
  }
  public static function returnComments($hash)
  {
    $comments = Comment::where('file',$hash)->orderBy('m_date','desc')->with('user')->simplePaginate(config('co.commentCount'));
    return $comments;
  }
  public static function delete($hash)
  {
    if(Auth::check())
    {
      $class = Auth::user()->hash;
      Comment::where('hash',$hash)->where('user_hash',$class)->delete();
    }
    else{
      throw new Exception("این کامنت مال شما نیست.");
    }
    return true;
  }
  public static function report($hash)
  {
    if(Auth::check())
    {
      $class = Auth::user()->hash;
    }
    else {
      $class = Request::ip();
    }
    $report = CommentReport::firstOrNew(['hash'=>$hash,'sender'=>$class]);
    $report->date = DB::raw('NOW()');
    $report->save();
    return true;
  }
  public static function returnMyComments()
  {
    $class = Auth::user()->hash;
    $comments = Comment::whereIn('file',function($query) use ($class){
      $query->select('hash')->from('tbl_files')->where('class',$class);
    })->where('user_hash','NOT',$class)->with('media')->with('user')->orderBy('m_date','desc')->simplePaginate(config('co.profileFilelistPerPage'));
    return $comments;
  }
}
 ?>
