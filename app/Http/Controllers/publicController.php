<?php

namespace App\Http\Controllers;

use App\Files;
use Illuminate\Http\Request;
use App\Classes\SearchClass;
use App\Http\Requests;
use Illuminate\Pagination\Paginator;
use App\Classes\HomeClass;
use App\Classes\ChannelClass;
use Illuminate\Support\Facades\App;
use App\Classes;
use App\Messages;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\SymlinkClass;
use App\Classes\ShowMediaClass;
use App\HomeGroup;
use App\Feedback;
use Illuminate\Http\Response;

class publicController extends Controller
{
    public function latest()
    {
      $media = Files::select('title','pagetime','volume','explenation','likes','visit','hash')->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->limit(20)->get();
      return $media->toJson();
    }
    public function index(Request $request)
    {
      $data = HomeClass::returnHomeStaff();
    	return view("pages.home",['data'=>$data]);
    }
    public function searchRadio(Request $request)
    {
      $result = SearchClass::externalClassSearch($request->text,'',config('co.radio_user'));
      $text = $request->text;

      $perPage = config('co.itemSearchPage');
      $currentPage = $request->page  ? $request->page : 1;
      $currentPage -=1;

      $pagedData = array_slice($result, $currentPage * $perPage, $perPage);
      $result =  new Paginator($pagedData,$perPage, $currentPage+1);
      $result->setPath('?text='.$text);
      $user = Classes::where('hash',config('co.radio_user'))->first();
      return view('pages.search',['data'=>$result,'group'=>'','text'=>$text,'user'=>$user]);
    }
    public function search(Request $request)
    {
      if ($request->has('str'))
      {
        $result = SearchClass::search($request->str);
        $text = $request->str;
      }
      else
      {
        $result = SearchClass::search($request->text);
        $text = $request->text;
      }

      $perPage = config('co.itemSearchPage');
      $currentPage = $request->page  ? $request->page : 1;
      $currentPage -=1;

      $pagedData = array_slice($result, $currentPage * $perPage, $perPage);
      $result =  new Paginator($pagedData,$perPage, $currentPage+1);
      $result->setPath('?text='.$text);
      #dd($result);
      return view('pages.search',['data'=>$result,'group'=>'','text'=>$text]);
    }
    public function group(Request $request,$name)
    {
      $result = SearchClass::group($name);
      #dd($result);
      return view('pages.search',['data'=>$result,'group'=>$name]);
    }
    public function returnPics(Request $request)
    {
      $path = '';
    	$size = $request->s;
    	$path = false;
    	if($request->s)
    	{
    		if($size == 1)
    		{
    			$path  = storage_path('app/public/'.config('co.MediaDir')).'/'.$request->p."/$request->picid/".$request->picid."_larg.jpg";
    		}
    		elseif($size == 3)
    		{
    			$path  = storage_path('app/public/'.config('co.MediaDir')).'/'.$request->p."/$request->picid/".$request->picid."_small2.jpg";
    		}
    		else
    		{
    			$path  = storage_path('app/public/'.config('co.MediaDir')).'/'.$request->p."/$request->picid/".$request->picid."_small.jpg";
    		}
    		//echo $path;
    	}
    	else
    	{
    		$path = url('css/images/media_notfound_small.png');
    	}
      if(!file_exists($path))
    	{
        if($size == 1)
    		{
          $path = url('css/images/media_notfound_larg.png');
    		}
    		elseif($size == 2)
    		{
          $path = url('css/images/media_notfound_small.png');
    		}
        elseif($size == 3)
    		{
          $path = url('css/images/media_notfound_small.png');
    		}
      }
      return response(file_get_contents($path))->header('Content-type','image/jpeg');
    }
    public function returnUserPic(Request $request)
    {
      $path = '';
    	$size = $request->s;
    	$path = false;
    	if($request->s)
    	{
    		if($size == 1)
    		{
    			$path  = storage_path('app/public/'.config('co.tmpUserPicPublicDir')).'/'.$request->p."/$request->picid/".$request->picid."_larg.jpg";
    		}
    		elseif($size == 2)
    		{
    			$path  = storage_path('app/public/'.config('co.tmpUserPicPublicDir')).'/'.$request->p."/$request->picid/".$request->picid."_small.jpg";
    		}
    		//echo $path;
    	}
    	else
    	{
        $path = url('css/images/user_notfound_larg.png');
    	}
      if(!file_exists($path))
    	{
        if($size == 1)
    		{
          $path = url('css/images/user_notfound_larg.png');
    		}
    		elseif($size == 2)
    		{
          $path = url('css/images/user_notfound_small.png');
    		}
      }
      return response(file_get_contents($path))->header('Content-type','image/jpeg');
    }
    public function channel(Request $request,$hash)
    {
      $user = ChannelClass::returnUser($hash);
      $files = ChannelClass::returnUserFiles($hash);
      $branches = ChannelClass::returnUserBranches($hash);
      $setting = ChannelClass::returnUserSetting($hash);
      $isFollowed = ChannelClass::isFollowed($hash);
      $top = ChannelClass::returnTopMedia($hash);

      $sim = false;
      $path = false;
      if($top)
      {
        $path = SymlinkClass::create($top->hash,$top->path,$top->filetype);
        $sim = ShowMediaClass::returnSimilar($top->title,$top->tags,6);
      }
      if(!$user)
      {
        return redirect('notfound');
      }
      $data = array(
        'user' => $user,
        'files' => $files,
        'branch' => $branches,
        'setting' => $setting,
        'follow' => $isFollowed,
        'top' => $top,
        'path' => $path,
        'similar' => $sim
      );
      return view('pages.channel',["data"=>$data]);
    }
    public function BranchChannel(Request $request,$hash,$branch)
    {
      $user = ChannelClass::returnUser($hash);
      $files = ChannelClass::returnBranchFiles($hash,$branch);
      $branches = ChannelClass::returnUserBranches($hash);
      $setting = ChannelClass::returnUserSetting($hash);
      $isFollowed = ChannelClass::isFollowed($hash);
      if(!$user)
      {
        return redirect('notfound');
      }
      $data = array(
        'user' => $user,
        'files' => $files,
        'branch' => $branches,
        'setting' => $setting,
        'follow' => $isFollowed,
        'top' => false,
      );
      return view('pages.channel',["data"=>$data]);
    }
    public function feed($hash)
    {
      $feedNews = App::make('feed');
      // cache the feed for 60 minutes with custom cache key "feedNewsKey"
      #$feedNews->setCache(60, $hash);

      // check if there is cached feed and build new only if is not
      #if (!$feedNews->isCached())
      #{
         // creating rss feed with our most recent 20 records in news table
         $class = Classes::where('hash',$hash)->first();

         $files = Files::where('class',$hash)->where(function($query){
           $query->where('ispublished',1)->orWhere('ispublished',5);
         })->orderBy('endate', 'desc')->limit(config('co.itemSearchPage'))->get();

         // set your feed's title, description, link, pubdate and language
         $feedNews->title = 'آخرین رسانه های کانال '.$class->name;
         $feedNews->description = $class->des;
         $feedNews->link = $class->link;
         $feedNews->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
         $feedNews->lang = 'en';
         $feedNews->setShortening(true); // true or false
         $feedNews->setTextLimit(100); // maximum length of description text
         #dd($files);
         foreach ($files as $file)
         {
             // set item's title, author, url, pubdate, description and content
             $feedNews->add($file->title, $file->creator, url('/s/'.$file->hash), $file->endate, $file->explenation);
         }

      #}

      // return your feed ('atom' or 'rss' format)
      return $feedNews->render('atom');
    }
    public function sendMsgTo(Request $request,$hash)
    {
      if(!Auth::check())
      {
        return back();
      }
      $val = Validator::make($request->all(),[
        'captcha' =>"required|captcha",
        'des' => "required"
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      $sender = Auth::user()->hash;

      Messages::create(['sender'=>$sender, 'reciver'=>$hash, 'date'=>DB::raw('NOW()'), 'text'=>$request->des, 'is_read'=>'u']);
      return back()->with('status','msg_send');
    }
    public function specialBranch(Request $request)
    {
      $list = HomeGroup::where('id',$request->id)->first();
      $result = HomeClass::returnMediaListPage($list->branch,@$request->page,$list->hash);
      #dd($result);
      return view('pages.search',['data'=>$result,'group'=>'','text'=>$list->title,'special'=>true]);
    }
    public function feedback()
    {
      return view('pages.feedback');
    }
    public function saveFeedback(Request $request)
    {
      $val = Validator::make($request->all(),[
        "email" => 'required',
        "text" =>  'required',
        "captcha" =>  'required|captcha',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      if(Auth::check())
      {
        $sender = Auth::user()->hash;
      }
      else
      {
        $sender = $request->ip();
      }
      $ip = $request->ip();
      Feedback::create(['feedback'=>$request->text,'sender'=>$sender,'date'=>DB::raw('NOW()'),'email'=>$request->email,'is_read'=>'u']);
      return back()->with('status','success');
    }
    public function notfound()
    {
      abort(404);
    }
    public function returnCurrentRadio()
    {
      $infos = HomeClass::returnCurrentRadio();
      return $infos;
    }
}
