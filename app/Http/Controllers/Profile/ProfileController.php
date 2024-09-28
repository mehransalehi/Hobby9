<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Classes;
use App\Classes\Profile\FileListClass;
use App\Classes\Profile\BranchClass;
use App\Follow;
use App\Classes\Profile\FollowClass;
use App\Classes\CommentClass;
use App\Classes\Profile\SettingClass;
use App\Classes\Profile\ChannelClass;
use App\Classes\Profile\MessageClass;
use App\Classes\SearchClass;
use Illuminate\Pagination\Paginator;
use App\Classes\Profile\ProfileClass;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProfileController extends Controller
{
  public function index ()
  {
    $data['mainEntry'] = ProfileClass::returnUserStatChart();
    $res = ProfileClass::returnMediaStatChart();
    $data['video'] = $res['video'];
    $data['music'] = $res['music'];
    $data['ebook'] = $res['ebook'];
    $data['top'] = ProfileClass::returnTopMedia();
    $data['mediaEntry'] = $res['entry'];
    return view('profile.index',['data'=>$data]);
  }
  #filelist page methods
  public function filelist($type='all')
  {
    $files = FileListClass::returnUserFiles($type);
    $top = FileListClass::returnTopMedia();
    $data = array(
      "files" => $files,
      "topMedia" => $top,
      "type"  =>  $type,
    );
    return view('profile.filelist',["data"=>$data]);
  }
  public function delMedia($hash)
  {
    FileListClass::delMedia($hash);
    return back()->with('status','del_success');
  }
  public function editMedia($hash)
  {
    if(!($file = FileListClass::returnMedia($hash)))
    {
      return back()->with('status','not_exist');
    }
    $top = FileListClass::returnTopMedia();
    $branches = BranchClass::returnBranches();
    $branchesHashes = $branches->pluck('hash')->toArray();
    $isTop = false;
    if($file->hash == $top)
      $isTop = true;
    $data = array(
      "file" => $file,
      "isTop" => $isTop,
      "branches" => $branches,
      "branchesHash"  => $branchesHashes,
    );
    return view('profile.editmedia',["data"=>$data]);
  }
  public function saveMedia(request $request,$hash)
  {
    $val = Validator::make($request->all(),[
      "title" => 'required',
      "tag" =>  'required',
      "category" =>  'required',
      "language"  =>  'required',
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    $file = false;
    if ($request->hasFile('upload_img'))
    {
        $file = $request->file('upload_img');
    }
    try
    {
      FileListClass::saveDetails($request->all(),$file,$hash);
      return back()->with('status','success');
    }
    catch(Exception $e)
    {
      return back()->with('status','faild')->with('message',$e->getMessage());
    }
  }
  #branch page methods
  public function branches()
  {
    $branches = BranchClass::returnBranches();
    $data = array(
      "branches" => $branches,
    );
    return view('profile.branch',['data'=>$data]);
  }
  public function createBranch(Request $request)
  {
    $val = Validator::make($request->all(),[
      "name" => 'required|alpha_num',
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    BranchClass::createBranch($request->name);
    return redirect('/profile/branch/')->with('status', 'save_success');
  }
  public function delBranch($hash)
  {
    BranchClass::delBranch($hash);
    return redirect('/profile/branch/')->with('status', 'del_success');
  }
  public function editBranch(Request $request,$hash)
  {
    $val = Validator::make($request->all(),[
      "name" => 'required|alpha_num',
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    BranchClass::editBranch($request->name,$hash);
    return redirect('/profile/branch/')->with('status', 'save_success');
  }
  #follow methods
  public function showFollowed()
  {
    $follow = FollowClass::show();
    $data = array(
      "follow" => $follow,
    );
    return view('profile.follow',['data'=>$data]);
  }
  #comment method
  public function showComment()
  {
    $comments = CommentClass::returnMyComments();
    $data = array(
      "comments" => $comments
    );
    return view('profile.comment',['data'=>$data]);
  }
  public function saveComment(Request $request)
  {
    $val = Validator::make($request->all(),[
      'captcha' =>"required|captcha",
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    $val = Validator::make($request->all(),[
      "text"=>"required",
      "hash"=>"required",
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    try {
      $comment = CommentClass::saveComment($request->all());
      return back()->with('status', 'save_success');
    } catch (Exception $e) {
      return back()->withErrors(['msg', $e->getMessage()]);
    }
  }
  #setting method
  public function showSetting($tab='gen')
  {
    $user = SettingClass::returnSetting();

    $data = array(
      "user" => $user,
      "tab" => $tab ,
    );
    return view('profile.setting',['data'=>$data]);
  }
  public function saveSetting(Request $request ,$tab='gen')
  {
    if($tab == 'gen')
    {
      $val = Validator::make($request->all(),[
        "_name" => 'required',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      try {
        SettingClass::saveGeneral($request->all());
        return back()->with('status', 'save_success');
      } catch (Exception $e) {
        return back()->withErrors([$e->getMessage()]);
      }
    }
    if($tab == 'pic')
    {
      $file = $request->picture;
      if ($request->hasFile('picture'))
      {
        if($file->isValid())
        {
          try
          {
            $info = SettingClass::showCorp($file);
            return view('profile.corppic',["data"=>$info]);
          }
          catch(Exception $e)
          {
            return back()->withErrors([$e->getMessage()]);
          }
        }
      }
      else
      {
          return back()->withErrors(['فایل با موفقیت آپلود نشده است.']);
      }
    }
  }
  public function saveUserPic(Request $request)
  {
    $val = Validator::make($request->all(),[
      "x1" => 'required|numeric|min:0',
      "x2" => 'required|numeric|min:0',
      "y1" => 'required|numeric|min:0',
      "y2" => 'required|numeric|min:0',
      "h" => 'required|numeric|min:0',
      "w" => 'required|numeric|min:0',
      "ext" => 'required',
      "hash" => 'required',
    ]);
    if($val->fails())
    {
      return back()->withErrors($val);
    }
    try {
      SettingClass::saveCorped($request->all());
      $user = SettingClass::returnSetting();

      $data = array(
        "user" => $user,
        "tab" => 'pic' ,
      );
      session(['status' => 'save_pic']);
      return view('profile.setting',['data'=>$data]);
    } catch (Exception $e) {
      return back()->withErrors([$e->getMessage()]);
    }
  }
  #channel follow method
  public function showFollowedChannel()
  {
    $follow = ChannelClass::returnFollowedChannel();
    $followMe = ChannelClass::returnChannelFollowMe();
    $data = array(
      'follow' => $follow,
      'followed' => $followMe,
    );
    return view('profile.channels',['data'=>$data]);
  }
  #message method
  public function showMessages($hash='')
  {
    $heads = MessageClass::returnMyMessagesHead();
    $data = array(
      'heads' => $heads,
    );
    return view('profile.message',['data'=>$data]);
  }
  public function delMessages($id)
  {
    MessageClass::deleteMsg($id);
    return back()->with('status','del_success');
  }
  #search method
  public function search(Request $request)
  {
    $result = SearchClass::classSearch($request->text);
    $perPage = config('co.itemSearchPage');
    $currentPage = $request->page  ? $request->page : 1;
    $currentPage -=1;

    $pagedData = array_slice($result, $currentPage * $perPage, $perPage);
    $result =  new Paginator($pagedData,$perPage, $currentPage+1);
    $result->setPath('?text='.$request->text);
    #dd($result);
    return view('profile.search',['data'=>$result,'text'=>$request->text]);
  }
  #log method
  public function logout()
  {
    Auth::logout();
    return redirect('/');
  }
  public function login()
  {
    return view('profile.login');
  }
  public function doLogin(Request $request)
  {
    $val = Validator::make($request->all(),[
      "email" => 'required',
      "password" => 'required'
    ]);
    if($val->fails())
    {
      return redirect('/profile/login')->withErrors($val)->withInput();
    }
    $mainPass = $request->password;
    $md5 = sha1($request->password);
    $user = Classes::where(function($query) use ($request){
      $query->where('email',$request->email)->orWhere('owner',$request->email);
    })->where('password',$md5)->get();
    if(count($user)>0)
    {
      $user = $user[0];
      $newPass = Hash::make($request->password);
      Classes::where('hash',$user->hash)->update(['password'=>$newPass]);
      $mainPass = $request->password;
    }
    $remember=false;
    if($request->remember == 'remember')
    {
      $remember=true;
    }
    if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
      if(!Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password],$remember))
      {
        return redirect('/profile/login')->with('status', 'not_access');
      }
    }
    else {
      if(!Auth::attempt(['owner' => strtolower($request->email), 'password' => $request->password],$remember))
      {
        return redirect('/profile/login')->with('status', 'not_access');
      }
    }

    return redirect('/profile/')->with('status', 'success');
  }
}
