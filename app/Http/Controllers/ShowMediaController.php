<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ShowMediaClass;
use Validator;
use App\VioReport;
use Illuminate\Support\Facades\DB;
use App\Classes\Profile\FollowClass;
use App\Classes\CommentClass;
use App\Classes\SymlinkClass;
use App\Classes\MakeSchema;
use App\Classes\HomeClass;
use App\Files;


use Exception;

class ShowMediaController extends Controller
{
    public function showjson($hash)
    {

      $mainMedia = Files::select('title','pagetime','volume','explenation','likes','visit','hash','type','filetype','path')->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->limit(20)->first();
      #dd($mainMedia);
      $data['mainmedia'] = $mainMedia->toArray();
      $data['tumb'] = url('includes/returnpic.php?type='.$mainMedia->type.'&picid='.$mainMedia->hash.'&p='.$mainMedia->path);
      $data['videolink'] = SymlinkClass::create($mainMedia->hash,$mainMedia->path,$mainMedia->filetype);
      $data['dllink'] = url('dl/'.$value->hash);

      return json_encode($data);
    }
    public function show($hash)
    {

      if(!($mainMedia = ShowMediaClass::returnMainMedia($hash)))
      {
        abort(404);
      }
      $data['mainmedia'] = $mainMedia;
      $data['similar'] = ShowMediaClass::returnSimilar($mainMedia->title,$mainMedia->tags,15);
      $data['tags'] = explode('-', $data['mainmedia']->tags);
      $data['ads'] = ShowMediaClass::returnAds();
      $data['isFollowed'] = ShowMediaClass::isFollowed($hash);
      $data['comments'] = ShowMediaClass::returnComments($hash);
      $data['path'] = SymlinkClass::create($mainMedia->hash,$mainMedia->path,$mainMedia->filetype);
      $data['schema'] = MakeSchema::retunShowMedia($mainMedia,$data['path']);
      $data['currentRadio'] = HomeClass::returnCurrentRadio();
      $meta = MakeSchema::returnMetaTags($mainMedia,$data['path']);
      return view('pages.show',['data'=>$data,'meta'=>$meta]);
    }
    public function embed($filetype,$hash,$type,$width='frame')
    {
      if(!($mainMedia = ShowMediaClass::returnMainMedia($hash)))
      {
        return redirect('notfound')->with('status','media');
      }
      $data['mainmedia'] = $mainMedia;
      $data['similar'] = ShowMediaClass::returnSimilar($mainMedia->title,$mainMedia->tags,7);
      $data['path'] = SymlinkClass::create($mainMedia->hash,$mainMedia->path,$mainMedia->filetype);

      $height = 48;
      if(!is_numeric($width))
      {
         $width = 640;
         $height = 360;
      }
      else
      {
         if($filetype == 'video')
         {
           if( $width <=80)
           {
             $height = $$width - 2;
           }
           else
           {
             $height = 640 - $width;
             $height = 360 - (integer)($height / 2);
           }
         }
      }
      $data['height'] = $height;
      $data['width'] = $width;
      $data['type'] = $type;
      return view('pages.embed',['data'=>$data]);
    }
    public function dl($hash)
    {
      if(!($mainMedia = ShowMediaClass::returnMainMedia($hash)))
      {
        return redirect('notfound')->with('status','media');
      }
      $path = SymlinkClass::create($mainMedia->hash,$mainMedia->path,$mainMedia->filetype);
      $currentRadio = HomeClass::returnCurrentRadio();
      return view('pages.download',['data'=>$mainMedia,'path'=>$path,'currentRadio'=>$currentRadio]);
    }
    public function dlReturn($hash)
    {
      if(!($mainMedia = ShowMediaClass::returnMainMedia($hash)))
      {
        return redirect('notfound')->with('status','media');
      }
      $path = SymlinkClass::create($mainMedia->hash,$mainMedia->path,$mainMedia->filetype);
      return redirect($path);
    }
    public function report($hash)
    {
      if(!($mainMedia = ShowMediaClass::returnMainMedia($hash)))
      {
        return redirect('notfound')->with('status','media');
      }
      return view('pages.report',['data'=>$mainMedia]);
    }
    public function saveReport(Request $request,$hash)
    {
      $val = Validator::make($request->all(),[
        "address"=>"required",
        "min_one"=>"required"
      ]);
      if($val->fails())
      {
        return redirect('/report/'.$hash)->withErrors($val)->withInput();
      }
      if(empty($request->category) || $request->category == "NONE")
      {
        return redirect('/report/'.$hash)->with('status', 'category_empty');
      }
      VioReport::create(["hash"=>$hash,"min_one"=>$request->min_one,"min_two"=>$request->min_two,
      "min_three"=>$request->min_three,"report"=>$request->des,"m_date"=>DB::raw('NOW()'),"seen"=>0,"category"=>$request->category]);
      return redirect('/report/'.$hash)->with('status', 'success');
    }
    public function getClassMedia(Request $request)
    {

      $val = Validator::make($request->all(),[
        "hash"=>"required",
      ]);
      if($val->fails())
      {
        return 'validator error';
      }

      return ShowMediaClass::returnClassMedia($request->hash,14);
    }
    public function followMedia(Request $request)
    {
      #validate type (media , class) and hash required
      $val = Validator::make($request->all(),[
        "media"=>"required",
        "type"=>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }
      try {
        $hash = FollowClass::follow($request->media,$request->type);
        $data = array(
          "status" => 'success',
          "message" => 'با موفقیت ثبت شد.',
          "hash"  => $hash,
        );
        return view('messages.msg',["data"=>$data]);
      } catch (Exception $e) {
        $data = array(
          "status" => 'faild',
          "message" => $e->getMessage()
        );
        return view('messages.msg',["data"=>$data]);
      }
    }
    public function unFollowMedia(Request $request)
    {
      #validate type (media , class) and hash required
      $val = Validator::make($request->all(),[
        "media"=>"required",
        "type"=>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }
      try {
        $hash = FollowClass::unFollow($request->media,$request->type);
        $data = array(
          "status" => 'success',
          "message" => 'با موفقیت حذف شد.',
          "hash"  => $hash,
        );
        return view('messages.msg',["data"=>$data]);
      } catch (Exception $e) {
        $data = array(
          "status" => 'faild',
          "message" => $e->getMessage(),
        );
        return view('messages.msg',["data"=>$data]);
      }
    }
    public function saveComment(Request $request)
    {
      $val = Validator::make($request->all(),[
        'captcha' =>"required|captcha",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'کد امنیتی اشتباه است'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $val = Validator::make($request->all(),[
        "text"=>"required",
        "hash"=>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }
      try {
        $comment = CommentClass::saveComment($request->all());
        $data = array(
          "status" => 'success',
          "message" => 'با موفقیت ذخیره شد.',
          "comment"  => $comment,
          "captcha" => captcha_src(),
        );
        return view('ajax.showComment',["data"=>$data]);
      } catch (Exception $e) {
        $data = array(
          "status" => 'faild',
          "message" => $e->getMessage(),
        );
        return view('ajax.showComment',["data"=>$data]);
      }
    }
    public function reportComment(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash"=>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }
      try {
        $comment = CommentClass::report($request->hash);
        $data = array(
          "status" => 'success',
          "message" => 'با موفقیت ذخیره شد.',
        );
        return view('ajax.showComment',["data"=>$data]);
      } catch (Exception $e) {
        $data = array(
          "status" => 'faild',
          "message" => $e->getMessage(),
        );
        return view('ajax.showComment',["data"=>$data]);
      }
    }
    public function delComment(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash"=>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }
      try {
        $comment = CommentClass::delete($request->hash);
        $data = array(
          "status" => 'success',
          "message" => 'با موفقیت حذف شد.',
        );
        return view('ajax.showComment',["data"=>$data]);
      } catch (Exception $e) {
        $data = array(
          "status" => 'faild',
          "message" => $e->getMessage(),
        );
        return view('ajax.showComment',["data"=>$data]);
      }
    }
}
