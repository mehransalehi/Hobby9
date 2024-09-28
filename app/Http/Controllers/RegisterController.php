<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\VerifyRequest;
use App\Classes\RegisterClass;
use Validator;

class RegisterController extends Controller
{
  public function register()
  {
    return view('register.register');
  }
  public function doRegister(RegisterUser $request)
  {
    if(!($res= RegisterClass::sendRegisterEmail($request->email)))
    {
      return redirect('register')->with('status', 'exist');
    }
    elseif($res==='exist')
    {
      return redirect('register')->with('status', 'rereg');
    }
    return redirect('register')->with('status', 'success');
  }
  public function verify($hash)
  {
    $email = RegisterClass::returnEmail($hash);
    if(!$email)
    {
      return redirect('/');
    }
    return view('register.verify',['email'=>$email,'hash'=>$hash]);
  }
  public function doVerify(VerifyRequest $request,$hash)
  {
    $email = RegisterClass::returnEmail($hash);
    if(!$email)
    {
      return redirect('verify/'.$hash)->with('status', 'email_not_exist');
    }
    if(!preg_match ('/^[a-zA-Z0-9_]*$/',$request->alias))
    {
      return redirect('verify/'.$hash)->with('status', 'bad_username')->withInput();
    }
    if(!RegisterClass::checkUsername(strtolower($request->alias)))
    {
      return redirect('verify/'.$hash)->with('status', 'exist_username')->withInput();
    }
    $data = array(
      "email"=>$email,
      "owner"=>strtolower($request->alias),
      "name"=>$request->_name,
      "link"=>$request->link,
      "des"=>$request->des,
      "pass"=>$request->pass
    );
    $email = RegisterClass::registerThisClass($data,$hash);
    return redirect('profile/')->with('status', 'new_register');
  }
  public function forget()
  {
    return view('register.forget');
  }
  public function saveForget(Request $request)
  {
    $val = Validator::make($request->all(),[
      "email" => 'required|email',
      "pass" =>  'required',
      "conf" =>  'required',
      "captcha" =>  'required|captcha',
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    if($request->pass != $request->conf)
    {
      return back()->with('status','pass_wrong');
    }
    if(!RegisterClass::checkEmail(strtolower($request->email)))
    {
      return back()->with('status','email_exist');
    }
    RegisterClass::sendRecoveryPass($request->email,$request->pass);
    return back()->with('status','success');
  }
  public function resetPass(Request $request)
  {
    if(RegisterClass::resetPass($request->hash))
    {
      return view('pages.reset',['data'=>'success']);
    }
    else
    {
      return view('pages.reset',['data'=>'failed']);
    }
  }
}
