<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Feedback;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Mail\SendMail;
use Validator;

class AdminsFeedbackController extends Controller
{
    public function feedback()
    {
      $msgs = Feedback::with('user')->orderBy('date','desc')->get();
      $data['messages'] = $msgs;
      Feedback::where('id','>','-1')->update(['is_read'=>'p']);
      return view('admin.feedback.feedback',['data'=>$data]);
    }
    public function feedbackDel($id)
    {
      Feedback::where('id',$id)->delete();
      return back()->with('status','del_success');
    }
    public function sendMail(Request $request)
    {
      $val = Validator::make($request->all(),[
        "email" => 'required',
        "text" =>  'required',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      Mail::to($request->email,'')->send(new SendMail($request->text,'پشتیبانی وب سایت HOBBY9.com'));
      return back()->with('status','email_success');
    }
}
