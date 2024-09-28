<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Files;
use App\Classes\SymlinkClass;

class ApiController extends Controller
{
    public function branches()
    {
      $branches = array(
          array(
            "title" => "آخرین ها",
            "type"  => "latest",
            "url" => ""
          ),
          array(
            "title" => "جدیدترین آهنگ ها",
            "type"  => "music",
            "url" => ""
          ),
          array(
            "title" => "جدیدترین موزیک ویدیو ها",
            "type"  => "videomusic",
            "url" => ""
          ),
          array(
            "title" => "تازه های تکنولوژی",
            "type"  => "tec",
            "url" => ""
          ),
          array(
            "title" => "کارتون",
            "type"  => "toons",
            "url" => ""
          ),
          array(
            "title" => "تازه های تلفن همراه",
            "type"  => "mobile",
            "url" => ""
          ),
          array(
            "title" => "تازه های کامپیوتر",
            "type"  => "computer",
            "url" => ""
          ),
          array(
            "title" => "طنز",
            "type"  => "fun",
            "url" => ""
          )
      );
      return json_encode($branches);
    }
    public function returnBranch($type)
    {
      switch ($type) {
        case 'latest':

          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));


        break;
        case 'music':

          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->where('class',config('co.radio_user'))->where('type',3)->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));


        break;
        case 'videomusic':

          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->where('class',config('co.radio_user'))->where('type',1)->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));


        break;
        case 'tec':

          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->whereIn('branch',['آموزشی','علمی'])->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));


        break;
        case 'toons':

          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->whereIn('branch',['کارتون'])->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));


        break;
        case 'mobile':

          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->whereIn('branch',['کامپيوتر'])->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->where(function($query){
            $query->where('tags','like','%موبایل%')->orWhere('tags','like','%mobile%')->orWhere('tags','like','%گوشی%');
          })->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));


        break;
        case 'computer':
          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->whereIn('branch',['کامپيوتر'])->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));

          return $files->toJson();
        break;
        case 'fun':
          $files = Files::select('numdownload','publisher','creator','title','pagetime','volume','explenation','likes','visit','hash','path','type','filetype')->where(function ($query){
            $query->where('ispublished',1)->orWhere('ispublished',5);
          })->whereIn('branch',['طنز','تفريحی'])->with(array('user'=>function($query){
              $query->select('owner','hash');
          }))->orderBy('endate','desc')->simplePaginate(config('co.apiCount'));

        break;
      }
      foreach ($files as $file) {
        $tempData = $file->toArray();
        $tempData['thumb'] = url('includes/returnpic.php?type='.$file->type.'&picid='.$file->hash.'&p='.$file->path.'&s=1');
        $tempData['formatedDate'] = \App\Http\handyHelpers::MTS($file->endate);
        $tempData['link'] = SymlinkClass::create($file->hash,$file->path,$file->filetype);
        $data['data'][] = $tempData;
      }
      #$files = $files->toArray();
      #dd($files);
      $data['currentPage'] = $files->currentPage();
      $data['nextPageUrl'] = $files->nextPageUrl();
      $data['prePageUrl'] = $files->previousPageUrl();
      $data['morePage'] = $files->hasMorePages();
      return json_encode($data);
    }
}
