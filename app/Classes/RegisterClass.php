<?php
namespace App\Classes;


use App\Register;
use App\Classes;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Hash;
use App\ResetPass;

class RegisterClass
{
  public static function sendRegisterEmail($email)
  {
    $req = Classes::where('email',$email)->get();

    if(count($req)>0)
    {
      return false;
    }
    $req = Register::where('email',$email)->get();
    $returnValue=true;
    if(count($req)>0)
    {
      $returnValue='exist';
    }
    $hash = md5($email.date("jS F Y").rand(1,1000));
    Register::updateOrCreate(['email'=>$email],['hash'=>$hash]);
    Mail::to($email,'')->send(new RegisterMail(url('verify/'.$hash)));
    return $returnValue;
  }
  public static function returnEmail($hash)
  {
    $req = Register::where('hash',$hash)->get();
    if(count($req)<1)
      return false;
    return $req[0]->email;
  }
  public static function checkUsername($owner)
  {
    $req = Classes::where('owner',$owner)->get();
    if(count($req)>=1)
      return false;
    return true;
  }
  public static function checkEmail($mail)
  {
    $req = Classes::where('email',$mail)->get();
    if(count($req)<1)
      return false;
    return true;
  }
  public static function registerThisClass($data,$hash)
  {
    #dd($data);
    Register::where('hash',$hash)->delete();
    $userhash = md5($data['email'].date("jS F Y"));
    $password = Hash::make($data['pass']);
    Classes::create([
      "hash"  => $userhash,
      "owner" => $data['owner'],
      "email" => $data['email'],
      "password"  => $password,
      "name"  => $data['name'],
      "des"   => $data['des'],
      "link"  => $data['link'],
    ]);
    return true;
  }
  public static function sendRecoveryPass($email,$newPass)
  {
    $hash = md5($email.date("jS F Y").rand(1,1000));
    $newPass = Hash::make($newPass);
    Mail::to($email,'')->send(new RegisterMail(url('reset/'.$hash)));
    ResetPass::updateOrCreate(['email'=>$email],['hash'=>$hash,'password'=>$newPass]);
    return true;
  }
  public static function resetPass($hash)
  {
    $res = ResetPass::where("hash",$hash)->get();
    if(count($res)>=1)
    {
      Classes::where('email',$res[0]->email)->update(['password'=>$res[0]->password]);
      ResetPass::where("hash",$hash)->delete();
      return true;
    }
    return false;
  }
}
 ?>
