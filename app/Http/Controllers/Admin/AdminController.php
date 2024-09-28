<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Files;
use App\Classes;
use App\Register;
use App\Feedback;

class AdminController extends Controller
{
    public function login()
    {
      return view('admin.login');
    }
    public function doLogin(Request $request)
    {
      $val = Validator::make($request->all(),[
        "username" => 'required',
        "password" => 'required'
      ]);
      if($val->fails())
      {
        return redirect('/webmaster/login')->withErrors($val)->withInput();
      }
      if(!Auth::guard('admins')->attempt(['username' => $request->username, 'password' => $request->password]))
      {
        return redirect('/webmaster/login')->with('status', 'not_access');
      }
      return redirect('/webmaster/')->with('status', 'success');
    }
    public function index()
    {
      $new = count(Files::whereDate('endate', DB::raw('CURDATE()'))->get());
      $all = count(Files::all());
      $del = count(Files::where('ispublished',4)->get());
      $video = count(Files::where('type',1)->get());
      $ebook = count(Files::where('type',2)->get());
      $music = count(Files::where('type',3)->get());
      $users = count(Classes::all());
      $reg = count(Register::all());
      $feed = count(Feedback::where('is_read','u')->get());

      $data['all'] = $all;
      $data['new'] = $new;
      $data['del'] = $del;
      $data['video'] = $video;
      $data['ebook'] = $ebook;
      $data['music'] = $music;
      $data['users'] = $users;
      $data['reg'] = $reg;
      $data['feed'] = $feed;

      return view('admin.index',['data'=>$data]);
    }
}
