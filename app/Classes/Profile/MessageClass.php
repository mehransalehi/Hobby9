<?php
namespace App\Classes\Profile;

use App\Classes;
use App\Messages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageClass{
  public static function returnMyMessagesHead()
  {
    $class = Auth::user()->hash;
    $messages = Messages::where('reciver',$class)->orderBy('date','desc')->with('senderUser')->get();
    Messages::where('reciver',$class)->update(['is_read'=>'r']);
    return $messages;
  }
  public static function deleteMsg($id)
  {
    Messages::where('id',$id)->delete();
    return true;
  }
}
 ?>
