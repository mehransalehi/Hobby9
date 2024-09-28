<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Files;
use App\Classes;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Classes\SearchClass;
use Illuminate\Pagination\Paginator;
use App\Radio;
use App\Classes\Admin\SocialClass;
use App\Classes\Admin\YoutubeDl;

class AdminFileController extends Controller
{
  private $users = array(
    "USERS_KHANDE"=> array('d60054a7dc81492c1281f63d45cd1ac8','11bf013aeb5e76d10c94747f0a31d3a1','72ff51a58c6749c615303c4666d897d8','c39726242d85c9521ccbf4ce090ffc26','11260bb1fb127221fe967cf4fbad7300'),
    "USERS_HAVADES"=> array('e166f2c5e5f6b9d0aebe26533efdeb09','eff8d593e8e99d5ec807f5c31793fe76','fd7d3cdfc155afb31ca1c2f89bc833f0','5eb9d3f9304fcf6975c6310663c78aab','7f779e54185938b8990ba8485cd82b05'),
    "USERS_ANIMAL"=> array('2fc894daa45d3e989afc354c9a995462','e98b10dcff0176c7cc38154da25cb12c','e98b10dcff0176c7cc38154da25cb12c','2fc894daa45d3e989afc354c9a995462','939d12bf091dfb8fc9190121642cbcd8'),
    "USERS_TEC"=> array('1d23f80e7ca3c1929abcae60f16b5b7d','554b2d572030eaa9b8eb226a69712434','235c4b63350220247e4f7b1e742f68a7','ec423ab02fa2df5319ff88f2e0f7a5e7','ec423ab02fa2df5319ff88f2e0f7a5e7'),
    "USERS_ANIME"=> array('d796b3bfe4fca873da0e4b573bdf53d6','ba0dfa1afd01f89aa698fab7b95d9d2e','4e18b64fc91ac670d7e7aa11e91bd827','57b60afefe8029d0af758534cabac390','ba0dfa1afd01f89aa698fab7b95d9d2e'),
    "USERS_HONAR"=> array('8f1bc94038764dacb4c19748ac3b290e','ba27b0f4195439e2710cc12af9e71bc8','d91b10209e0280903b65dd9ee01d34ec','e66971e9dab9fe7f0a189038d41ac890','8f1bc94038764dacb4c19748ac3b290e'),
    "USERS_MUSIC"=> array('6e239b2c329693bcc6a9526230ceae7f','3832d487109cf725dc74b30ac7cd1db2','a2267a544499a1f3ab49a47d0cb7f3e0','cf933a27eba551ac08bcc64e12ffce9e','6f42845ac3e44e44cb7a9dc2abf8f7c6'),
    "USERS_TABIAT"=> array('939d12bf091dfb8fc9190121642cbcd8','0ee484873122a489e4c2a01bffdb0ba9','628adc958f29e8380f38380c11d2f94c','0ee484873122a489e4c2a01bffdb0ba9','628adc958f29e8380f38380c11d2f94c'),
    "USERS_AMOOZESHI"=> array('476a58c54219f2cb89ff13ec6cc5ae59','476a58c54219f2cb89ff13ec6cc5ae59','476a58c54219f2cb89ff13ec6cc5ae59','476a58c54219f2cb89ff13ec6cc5ae59','476a58c54219f2cb89ff13ec6cc5ae59'),
    "USERS_MOTE"=> array('7f779e54185938b8990ba8485cd82b05','1e5376cb7e1c0467f85e073b3fe48133','1e5376cb7e1c0467f85e073b3fe48133','7f779e54185938b8990ba8485cd82b05','1e5376cb7e1c0467f85e073b3fe48133'),
    "USERS_VARZESHI"=> array('211ead0c8959428dcfd01fd5d2cc2e2a','211ead0c8959428dcfd01fd5d2cc2e2a','211ead0c8959428dcfd01fd5d2cc2e2a','211ead0c8959428dcfd01fd5d2cc2e2a','211ead0c8959428dcfd01fd5d2cc2e2a'),
  );
  private $categorys = array('عاشقانه','تفريحی','متفرقه','تبليغات','کارتون','طنز','علمی','حوادث','حيوانات','طبيعت','صداوسيما','شخصی','سياسی','آموزشی','کامپيوتر','هنری','سلامت','ورزشی','مذهبی','مهندسی','جدیدترین موزیک ها');
  private $fetch = array('عاشقانه' => 'USERS_MOTE',
                          'تفريحی' => '',
                          'متفرقه' => 'USERS_MOTE',
                          'تبليغات' => '',
                          'کارتون' => 'USERS_ANIME',
                          'طنز' => 'USERS_KHANDE',
                          'علمی' => 'USERS_TEC',
                          'حوادث' => 'USERS_HAVADES',
                          'حيوانات' => 'USERS_ANIMAL',
                          'طبيعت' => 'USERS_TABIAT',
                          'صداوسيما' => 'USERS_MOTE',
                          'شخصی' => '',
                          'سياسی' => '',
                          'آموزشی' => 'USERS_AMOOZESHI',
                          'کامپيوتر' => 'USERS_TEC',
                          'هنری' => 'USERS_HONAR',
                          'سلامت' => '',
                          'ورزشی' => 'USERS_VARZESHI',
                          'مذهبی' => '',
                          'مهندسی' => '',
                          'جدیدترین موزیک ها' => 'USERS_MUSIC');

    public function showActions()
    {
      return view('admin.files.actions');
    }
    function returnMediaEditForm(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $file = Files::where('hash',$request->hash)->first();
      if(count($file)<=0)
      {
        $data = array(
          "status" => 'faild',
          "message" => 'شناسه نا معتبر است'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $data = array(
        "file"  => $file,
      );
      return view('ajax.adminEditFormMedia',["data"=>$data]);
    }
    function delTrash()
    {
      $files = Files::where('ispublished',4)->get();
      foreach ($files as $file) {
        $path = storage_path('app/'.config('co.MediaDir').'/'.$file->path.'/'.$file->hash.'/');
        $cmd = 'rm -fr '.$path;
        shell_exec($cmd);
        $file->delete();
      }
      return back()->with('status','del_success');
    }
    function delTele()
    {
      $files = Files::where('ispublished',21)->update(['ispublished'=>4]);
      return back()->with('status','del_success');
    }
    public function editMedia(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
        "title" =>"required",
        "tag" =>"required",
        "channel" =>"required",
        "cat" =>"required",
        "language" =>"required",
        "published" =>"required",
      ]);

      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $file = Files::where('hash',$request->hash)->first();

      if(!$file)
      {
        $data = array(
          "status" => 'faild',
          "message" => 'فایل موجود نیست',
        );
        return view('messages.msg',["data"=>$data]);
      }
      $file->title = $request->title;
      $file->creator = $request->author;
      $file->publisher = $request->publisher;
      $file->tags = $request->tag;
      $file->lang = $request->language;
      $file->branch = $request->cat;
      $soflag = 'b';
    	if($request->comment =='no' && $request->ath == 'no')
    	{
    		$soflag = 'n';
    	}
    	elseif($request->comment=='no')
    	{
    		$soflag = 's';
    	}
    	elseif($request->ath == 'no')
    	{
    		$soflag = 'c';
    	}
      $file->soflag = $soflag;
      $file->ispublished = $request->published;
      $file->likes = $request->like;
      $file->visit = $request->visit;
      $file->explenation = $request->des;
      $file->class = $request->channel;


      $data = array(
        "status" => 'success',
        "message" => 'با موفقیت ذخیره شد.',
        "hash"  => $file->hash,
        "file" => $file,
      );

      $file->save();
      return view('ajax.editedAdminMedia',["data"=>$data]);
    }
    public function search(Request $request)
    {
      $val = Validator::make($request->all(),[
        "type" => 'required',
        "hash" =>  'required',
      ]);
      if($val->fails())
      {
        return back()->withErrors($val);
      }
      $trash = false;
      if($request->trash == 'trash')
        $trash = true;
      if($request->type == 'search')
      {
        $files = SearchClass::search($request->hash);

        $perPage = config('co.itemSearchPage');
        $currentPage = $request->page  ? $request->page : 1;
        $currentPage -=1;

        $pagedData = array_slice($files, $currentPage * $perPage, $perPage);
        $files =  new Paginator($pagedData,$perPage, $currentPage+1);

        $data['title'] = 'جستجوی : '.$request->hash;
      }
      elseif($request->type == 'channel')
      {
        $files = Files::where('class',$request->hash)->where(function($query) use ($trash){
          if(!$trash)
            $query->whereNotIn('ispublished',[21,4]);
          else
            $query->where('ispublished',4);
        })->orderBy('endate', 'desc')->with('user')->simplePaginate(config('co.itemSearchPage'));


        $data['title'] = 'لیست رسانه های کانال :'.$files[0]->user->name;
      }
      else
      {
        $files = Files::where('hash',$request->hash)->orderBy('endate', 'desc')->with('user')->simplePaginate(config('co.itemSearchPage'));
        $data['title'] = 'اطلاعات رسانه : '.$files[0]->title;
      }

      if($trash != 'trash')
      {
        $files->setPath('?type='.$request->type.'&hash='.$request->hash);
      }
      else
      {
        $files->setPath('?type='.$request->type.'&hash='.$request->hash.'&trash='.$request->trash);
      }

      $data['files'] = $files;
      #dd($data);
      return view('admin.files.showfiles',['data'=>$data]);
    }
    public function showSort()
    {
      $files = Files::whereNotIn('ispublished',[21,4])->orderBy('endate', 'desc')->with('user')->simplePaginate(config('co.itemSearchPage'));
      $data['title'] = 'لیست رسانه ها - مرتب شده بر اساس تاریخ';
      $data['files'] = $files;
      #dd($data);
      return view('admin.files.showfiles',['data'=>$data]);
    }
    public function showSocial()
    {
      $files = Files::whereNotIn('ispublished',[21,4])->orderBy('endate', 'desc')->with('user')->simplePaginate(config('co.itemSearchPage'));
      $data['title'] = 'لیست رسانه ها - اشتراک گذاری';
      $data['files'] = $files;
      #dd($data);
      return view('admin.files.social',['data'=>$data]);
    }
    public function showRadio()
    {
      $files = Files::whereIn('hash',function($query){
        $query->select('media_hash')->from('tbl_radio_songs')->orderBy('date','desc');
      })->with('user')->with('radio')->simplePaginate(config('co.itemSearchPage'));
      $data['title'] = 'لیست رسانه های رادیو';
      $data['files'] = $files;
      #dd($data);
      return view('admin.files.showfiles',['data'=>$data]);
    }
    public function radioToggle(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      #$file = Files::where('hash',$request->hash)->first();
      $file = Radio::where('media_hash',$request->hash)->first();
      if(count($file)>0)
      {
        $file->delete();
        $pub = 1;
      }
      else
      {
        $file = new Radio;
        $file->media_hash = $request->hash;
        $file->date = DB::raw('NOW()');
        $file->save();
        $pub = 12;
      }
      $data = array(
        "status" => 'success',
        "message" => 'با موفقیت تغییر کرد',
        "hash"  => $file->media_hash,
        "extera" => $pub,
      );
      return view('messages.msg',["data"=>$data]);
    }
    public function teleFiles()
    {
      $files = Files::where('ispublished',7)->orderBy('endate', 'desc')->simplePaginate(config('co.itemSearchPage'));
      $data['medias'] = $files;
      $data['cat'] = $this->categorys;
      $data['users'] = $this->users;
      $data['fetch'] = $this->fetch;
      return view('admin.files.tele',['data'=>$data]);
    }
    public function singleDel(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $file = Files::where('hash',$request->hash)->first();
      if(count($file)>0)
      {
        $file->ispublished = 4;
        $file->save();
      }
      $data = array(
        "status" => 'success',
        "message" => 'با موفقیت حذف شد.',
        "hash"  => $file->hash,
      );
      return view('messages.msg',["data"=>$data]);
    }
    public function teleSave(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
        "title" =>"required",
        "tags" =>"required",
        "user" =>"required",
        "cat" =>"required",
        "isTel" =>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $file = Files::where('hash',$request->hash)->first();

      if(!$file)
      {
        $data = array(
          "status" => 'faild',
          "message" => 'فایل موجود نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }

      $file->title = $request->title;
      $file->tags = $request->tags;
      $file->explenation = $request->des;
      $file->branch = $request->cat;
      $file->endate = DB::raw('NOW()');
      if($request->isTel)
      {
        $file->ispublished = 21;
        $file->class = 'TELEGRAM';
      }
      else
      {
        $file->ispublished = 5;
        $file->class = $request->user;
      }
      $file->save();
      $data = array(
        "status" => 'success',
        "message" => 'با موفقیت ذخیره شد.',
        "hash"  => $file->hash,
      );
      return view('messages.msg',["data"=>$data]);
    }
    public function sendSocial(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
        "type" =>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $returnStat = false;
      $extera = "";
      if($request->type == 'face')
      {
        if(SocialClass::sentToFacebook($request->hash))
          $extera .='face|';
      }
      elseif($request->type == 'google')
      {
        if(SocialClass::sentToGoogle($request->hash))
          $extera .='google|';
      }
      elseif($request->type == 'twitter')
      {
        if(SocialClass::sentToTwitter($request->hash))
          $extera .='twitter|';
      }
      elseif($request->type == 'insta')
      {
        if(SocialClass::sentToInsta($request->hash))
          $extera .='insta|';
      }
      elseif($request->type == 'all')
      {
        $extera ='';
        if(SocialClass::sentToFacebook($request->hash))
          $extera .='face|';
        if(SocialClass::sentToGoogle($request->hash))
          $extera .='google|';
        if(SocialClass::sentToTwitter($request->hash))
          $extera .='twitter|';
        if(SocialClass::sentToInsta($request->hash))
          $extera .='insta|';
      }
      $data = array(
        "status" => 'success',
        "message" => 'با موفقیت فرستاده شد.',
        "hash"  => $request->hash,
        "extera" => $extera,
      );
      return view('messages.msg',["data"=>$data]);
    }
    public function dl()
    {
      $classes = Classes::select("name","hash")->get();
      $data = array(
        "classes" => $classes
      );
      return view("admin.dl.actions",["data"=>$data]);
    }
    public function dlLink(Request $request)
    {
      $val = Validator::make($request->all(),[
        "text"=>"required",
        "ext"=>"required",
        "number"=>"required"
      ]);
      if($val->fails() || ($request->user == 'NONE' && $request->special == 'NONE'))
      {
        return back()->with("status","error")->withErrors($val);
      }
      $user = $request->special;
      if($request->user != 'NONE')
      {
        $user = $request->user;
      }

      if(preg_match("/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/", $request->text))
      {
        $returnVal = YoutubeDl::getYoutubeDirect($request->text,$request->ext,$user);
      }
      elseif(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $request->text))
      {
        $returnVal = YoutubeDl::getLink($request->text,$request->ext,$user);
      }
      else
      {
        $returnVal = YoutubeDl::getYoutube($request->text,$request->ext,$user,$request->number);
      }
      return back()->with("status","success")->withInput();
    }
    public function dlManage()
    {
      $files = Files::where('ispublished',9)->orderBy('endate', 'desc')->with('user')->simplePaginate(config('co.itemSearchPage'));
      $data['medias'] = $files;
      return view('admin.dl.manage',['data'=>$data]);
    }
    public function managerSave(Request $request)
    {
      $val = Validator::make($request->all(),[
        "hash" =>"required",
        "title" =>"required",
        "tags" =>"required",
        "cat" =>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status" => 'faild',
          "message" => 'اطلاعات کافی نیست.'
        );
        return view('messages.msg',["data"=>$data]);
      }
      $file = Files::where('hash',$request->hash)->first();

      if(!$file)
      {
        $data = array(
          "status" => 'faild',
          "message" => 'فایل موجود نیست'
        );
        return view('messages.msg',["data"=>$data]);
      }

      $file->title = $request->title;
      $file->tags = $request->tags;
      $file->explenation = $request->des;
      $file->branch = $request->cat;
      $file->endate = DB::raw('NOW()');
      $file->ispublished = 1;
      
      $file->save();
      $data = array(
        "status" => 'success',
        "message" => 'با موفقیت ذخیره شد.',
        "hash"  => $file->hash,
      );
      return view('messages.msg',["data"=>$data]);
    }
}
