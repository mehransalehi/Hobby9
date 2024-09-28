<?php
namespace App\Classes\Profile;

use App\UserBranches;
use App\Files;
use Illuminate\Support\Facades\Auth;

class BranchClass
{
  public static function returnBranches()
  {
    $class = Auth::user()->hash;
    $branches = UserBranches::where('user',$class)->with('files')->get();
    return $branches;
  }
  public static function createBranch($name)
  {
    $hash = md5($name.date("YmdHis").rand(1,10000));
    $class = Auth::user()->hash;
    UserBranches::firstOrCreate(['name'=>$name,'hash'=>$hash,'user'=>$class]);
    return true;
  }
  public static function editBranch($name,$hash)
  {
    $class = Auth::user()->hash;
    $branch = UserBranches::where('hash',$hash)->where('user',$class)->first();
    $branch->name = $name;
    $branch->save();
    return true;
  }
  public static function delBranch($hash)
  {
    $class = Auth::user()->hash;
    UserBranches::where('hash',$hash)->where('user',$class)->delete();
    Files::where('class',$class)->where('fabranch',$hash)->update(['fabranch'=>'']);
    return true;
  }
}
 ?>
