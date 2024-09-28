<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Mail\SendMail;
use Validator;
use App\Register;
use App\Classes;

class AdminEmailController extends Controller
{
    public function show()
    {
      return view('admin.email.email');
    }
    public function registerAll()
    {
      $emails = Register::all()->pluck('email')->toArray();
      $existEmails = Classes::whereIn('email',$emails)->pluck('email')->toArray();
      foreach ($emails as $email)
      {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          if(!in_array($email, $existEmails))
          {
            $hash = md5($email.date("jS F Y").rand(1,1000));
            Register::updateOrCreate(['email'=>$email],['hash'=>$hash]);
            Mail::to($email,'')->send(new RegisterMail(url('verify/'.$hash)));
          }
        }
      }
      return back()->with('status','email_success');
    }
    public function sendReg(Request $request)
    {
      $val = Validator::make($request->all(),[
        "text" =>  'required',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      $emails = explode(PHP_EOL, $request->text);
      $existEmails = Classes::whereIn('email',$emails)->pluck('email')->toArray();
      foreach ($emails as $email)
      {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          if(!in_array($email, $existEmails))
          {
            $hash = md5($email.date("jS F Y").rand(1,1000));
            Register::updateOrCreate(['email'=>$email],['hash'=>$hash]);
            Mail::to($email,'')->send(new RegisterMail(url('verify/'.$hash)));
          }
        }
      }
      return back()->with('status','email_success');
    }
    public function sendHuge(Request $request)
    {
      $val = Validator::make($request->all(),[
        "text" =>  'required',
        "emails" =>  'required',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      $emails = explode(PHP_EOL, $request->emails);
      foreach ($emails as $email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          Mail::to($email,'')->send(new SendMail($request->text,'پشتیبانی وب سایت HOBBY9.com'));
        }
      }
      return back()->with('status','email_success');
    }
    public function sendUsers(Request $request)
    {
      $val = Validator::make($request->all(),[
        "text" =>  'required',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      $emails  = Classes::all()->pluck('email')->toArray();
      foreach ($emails as $email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          Mail::to($email,'')->send(new SendMail($request->text,'پشتیبانی وب سایت HOBBY9.com'));
        }
      }
      return back()->with('status','email_success');
    }
    public function delTrash()
    {
      $regRecs = Register::all();
      foreach ($regRecs as $rec)
      {
        if(filter_var($rec->email, FILTER_VALIDATE_EMAIL))
        {
          $part = explode('@', $rec->email);
          if(count($part) != 2)
          {
            Register::where('email',$rec->email)->delete();
          }
          else {
            if(strpos($part[1], 'gmail') === false && strpos($part[1], 'yahoo') === false && strpos($part[1], 'hotmail') === false)
            {
              Register::where('email',$rec->email)->delete();
            }
          }
        }
        else
        {
          Register::where('email',$rec->email)->delete();
        }
      }
      return back()->with('status','del_success');
    }
}
