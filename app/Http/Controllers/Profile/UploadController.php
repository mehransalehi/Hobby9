<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use App\Classes\Profile\UploadClass;
use App\Classes\Profile\BranchClass;
use App\Classes\Admin\YoutubeDl;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Classes\Admin\DlClass;
use Exception;

class UploadController extends Controller
{
  private $gid;
    public function index()
    {
      $data['tab'] = 'local';
      return view('profile.upload',['data'=>$data]);
    }
    public function returnUploadMenu()
    {
      $branches = BranchClass::returnBranches();
      $data = array(
        "branches" => $branches,
      );
      return view('profile.uploadmenu',['data'=>$data]);
    }
    public function upload(Request $request)
    {
      $file = $request->userfile;
      if ($request->hasFile('userfile'))
      {
        if($file->isValid())
        {
          try
          {
            $upload = new UploadClass($file);
            $hash = $upload->upload();
            $data = array(
              "hash"=>$hash,
              "status"=>"success",
              "message"=>"رسانه با موفقیت آپلود شد.",
            );
            return view('profile.uploadmsg',['data'=>$data]);
          }
          catch(Exception $e)
          {
            $data = array(
              "status"=>"failed",
              "message"=>$e->getMessage(),
            );
            return view('profile.uploadmsg',['data'=>$data]);
          }
        }
      }
      else
      {
          $data = array(
            "status"=>"failed",
            "message"=>$file->getErrorMessage(),
          );
          return view('profile.uploadmsg',['data'=>$data]);
      }
    }
    public function saveDetail(Request $request)
    {
      $val = Validator::make($request->all(),[
        "title" => 'required',
        "tag" =>  'required',
        "cat" =>  'required',
        "lang"  =>  'required',
        "hash"  =>  'required',
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
        UploadClass::saveDetails($request->all(),$file);
        $data = array(
          "status"=>"success",
          "message"=>"با موفقیت ثبت شد",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
      catch(Exception $e)
      {
        $data = array(
          "status"=>"failed",
          "message"=>$e->getMessage(),
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
    }
    public function dlLink(Request $request)
    {
      $val = Validator::make($request->all(),[
        "text"=>"required",
      ]);
      if($val->fails())
      {
        $data = array(
          "status"=>"failed",
          "message"=>"اطلاعات کافی نیست",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
      $user = Auth::user()->hash;
      $mime = false;
      if(!($remoteSize = $this->remoteFileSize($request->text,$mime)))
      {
        $data = array(
          "status"=>"failed",
          "message"=>"حجم فایل قابل تشخیص نیست.",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
      elseif ($remoteSize > config('co.fileUploadLimit'))
      {
        $data = array(
          "status"=>"failed",
          "message"=>"حجم فایل بیشتر از  ". config('co.fileUploadLimit') ." مگابایت است.",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
      if(!in_array($mime, config('co.allowedMimes')))
      {
        $data = array(
          "status"=>"failed",
          "message"=>"این فایل معتبر نیست",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
      /*if(preg_match("/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/", $request->text))
      {
        $title = '';
        $url = YoutubeDl::getDlAndTitle($request->text, $title);
        $data = array(
          'url' => $url,
          'user' => $user,
          'title' => $title
        );
        return DlClass::userDlSave($data);
      }
      else*/if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $request->text))
      {
        $url = $request->text;
        $data = array(
          'url' => $url,
          'user' => $user
        );
        $hash = DlClass::userDlSave($data);
        $data = array(
          "status"=>"success",
          "hash"=>$hash,
          "message"=>"با موفقیت در صف دانلود قرار گرفت",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
      else {
        $data = array(
          "status"=>"failed",
          "message"=>"این آدرس معتبر نیست",
        );
        return view('profile.uploadmsg',['data'=>$data]);
      }
    }
    public function getProgress(Request $request)
    {
      $val = Validator::make($request->all(),[
        "gid"=>"required",
      ]);
      if($val->fails())
      {
        return back()->with("status","error")->withErrors($val);
      }
      $this->gid = $request->gid;
      \Ratchet\Client\connect('ws://127.0.0.1:5588/jsonrpc')->then(function($conn) {
      $conn->on('message', function($msg) use ($conn)
      {
        $res = json_decode($msg,true);
        if(array_key_exists("error", $rec))
        {
          $res = array(
            "result"=>array(
              "status"=>"error",
              "message"=>$msg["error"]["message"],
            ),
          );

          echo $res;
          $conn->close();
          exit;
        }
        else
        {
          echo $msg;
          $conn->close();
          exit;
        }
      });
      $message = array(
        'jsonrpc'=>'2.0',
        'id'=>'qwer',
        'method'=>'aria2.tellStatus',
        'params'=>array($this->gid,
          array("status","totalLength","completedLength","downloadSpeed")
        )
      );
      $conn->send(json_encode($message));
      }, function ($e)
      {
          echo "Could not connect: {$e->getMessage()}\n";
      });
    }
    public function remoteFileSize($url,&$mime)
    {
      // Assume failure.
      $result = -1;

      $curl = curl_init( $url );

      // Issue a HEAD request and follow any redirects.
      curl_setopt( $curl, CURLOPT_NOBODY, true );
      curl_setopt( $curl, CURLOPT_HEADER, true );
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
      curl_setopt( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/21.0" );

      $data = curl_exec( $curl );
      curl_close( $curl );
      if( $data ) {
        $content_length = "unknown";
        $status = "unknown";
        $mime = "unknown";

        if( preg_match( "/Content-Type: (.+)/", $data, $matches ) ) {
          $mime = (string)$matches[1];
          $mime = explode(";", $mime);
          $mime = trim($mime[0]);
        }

        if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
          $status = (int)$matches[1];
        }

        if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
          $content_length = (int)$matches[1];
        }


        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if( $status == 200 || ($status > 300 && $status <= 308) ) {
          $result = $content_length;
        }
      }

      return $result/1024/1024;
    }
}
