<?php
namespace App\Classes;

use App\AdminSetting;
use App\SpecialBranch;
use App\BlogPost;
use App\Files;
use App\HomeGroup;
use App\Classes\SearchClass;
use App\Classes\ShowMediaClass;
use Illuminate\Pagination\Paginator;
class HomeClass{

  public static function returnHomeStaff()
  {
    $settings = AdminSetting::where('identify','like','home%')->get();
    $data = array();
    #setting
    foreach ($settings as $setting) {
        if($setting->identify == 'home_special_page' && $setting->value == 'show')
        {
          $data['special'] = SpecialBranch::orderBy('id','desc')->limit(6)->get();
        }
        elseif($setting->identify == 'home_top_part' && $setting->value == 'show')
        {
          $data['news'] = BlogPost::where('is_news',1)->orderBy('id','desc')->limit(6)->with('group')->get();
          $data['top-part'] = true;
        }
        elseif($setting->identify == 'home_top_media')
        {
          $keys = explode('|', $setting->value);
          $data['key'] = $keys[0];
          if($keys[0] == 'newest')
          {
            $data['mainmedia'] = Files::where(function ($query){
              $query->where('ispublished',1)->orWhere('ispublished',5);
            })->orderBy('endate','desc')->limit(7)->get();
          }
          elseif($keys[0] == 'view')
          {
            $data['mainmedia'] = Files::where(function ($query){
              $query->where('ispublished',1)->orWhere('ispublished',5);
            })->orderBy('visit','desc')->limit(7)->get();
          }
          elseif($keys[0] == 'download')
          {
            $data['mainmedia'] = Files::where(function ($query){
              $query->where('ispublished',1)->orWhere('ispublished',5);
            })->orderBy('numdownload','desc')->limit(7)->get();
          }
          elseif($keys[0] == 'hashed')
          {
            $data['mainmedia'] = Files::where('hash',$keys[1])->get();
            $data['similar'] = ShowMediaClass::returnSimilar($data['mainmedia'][0]->title,$data['mainmedia'][0]->tags,4);
          }
          elseif($keys[0] == 'searched')
          {
            $data['mainmedia'] = SearchClass::search($keys[1],7);
          }
          elseif($keys[0] == 'channel')
          {
            $data['mainmedia'] = Files::where('class',$keys[1])->where(function ($query){
              $query->where('ispublished',1)->orWhere('ispublished',5);
            })->inRandomOrder()->limit(7)->get();
          }
          elseif($keys[0] == 'taged')
          {
            $key = trim($keys[1],'-');
            $tags = explode('-', $key);

            $data['mainmedia'] = Files::where(function ($query){
              $query->where('ispublished',1)->orWhere('ispublished',5);
            })->where(function ($query) use ($tags){
              $query->where('tags','like','%'.$tags[0].'%');
              for ($i=1; $i < count($tags) ; $i++) {
                $query->orWhere('tags','like','%'.$tags[$i].'%');
              }
            })->inRandomOrder()->limit(6)->get();
          }
          if(@$data['mainmedia'][0])
          {
            $data['path'] = SymlinkClass::create($data['mainmedia'][0]->hash,$data['mainmedia'][0]->path,$data['mainmedia'][0]->filetype);
          }
          #dd($data);
        }
        elseif($setting->identify == 'home_branch')
        {

        }
    }
    #media list
    $lists = HomeGroup::orderBy('order','asc')->get();

    $i = 0;

    foreach ($lists as $list) {
      $data['lists'][$i]['list'] = $list;
      $data['lists'][$i]['media'] = self::returnMediaList($list->branch,$list->hash);
      $i++;
    }
    $data['currentRadio'] = self::returnCurrentRadio();
    return $data;
  }
  public static function returnMediaList($type,$key='')
  {
    $media ='';
    if($type == 'newest')
    {
      $media = Files::where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->limit(6)->get();
    }
    elseif($type == 'taged')
    {
      $key = trim($key,'-');
      $keys = explode('-', $key);

      $media = Files::where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->where(function ($query) use ($keys){
        $query->where('tags','like','%'.$keys[0].'%');
        for ($i=1; $i < count($keys) ; $i++) {
          $query->orWhere('tags','like','%'.$keys[$i].'%');
        }
      })->inRandomOrder()->limit(6)->get();
    }
    elseif($type == 'searched')
    {
      $media = SearchClass::search($key,6);
    }
    elseif($type == 'mostest')
    {
      $media = Files::where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('numdownload','desc')->limit(6)->get();
    }
    elseif($type == 'channel')
    {
      $media = Files::where('class',$key)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->limit(6)->get();
    }
    elseif($type == 'music')
    {
      $media = Files::where('type',3)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->inRandomOrder()->limit(6)->get();
    }
    elseif($type == 'book')
    {
      $media = Files::where('type',2)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->inRandomOrder()->limit(6)->get();
    }
    elseif($type == 'video')
    {
      $media = Files::where('type',1)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->inRandomOrder()->limit(6)->get();
    }
    else
    {
      $media = Files::where('branch',$type)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->inRandomOrder()->limit(6)->get();
      #dd($media);
    }
    return $media;
  }
  public static function returnMediaListPage($type,$page=0,$key='')
  {
    $media ='';
    if($type == 'newest')
    {
      $media = Files::where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    elseif($type == 'taged')
    {
      $key = trim($key,'-');
      $keys = explode('-', $key);

      $media = Files::where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->where(function ($query) use ($keys){
        $query->where('tags','like','%'.$keys[0].'%');
        for ($i=1; $i < count($keys) ; $i++) {
          $query->orWhere('tags','like','%'.$keys[$i].'%');
        }
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    elseif($type == 'searched')
    {
      $media = SearchClass::search($key);
      $perPage = config('co.itemSearchPage');
      $currentPage = $page  ? $page : 1;
      $currentPage -=1;

      $pagedData = array_slice($media, $currentPage * $perPage, $perPage);
      $media =  new Paginator($pagedData,$perPage, $currentPage+1);
      $media->setPath('?text='.$key);
    }
    elseif($type == 'mostest')
    {
      $media = Files::where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('numdownload','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    elseif($type == 'channel')
    {
      $media = Files::where('class',$key)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    elseif($type == 'music')
    {
      $media = Files::where('type',3)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    elseif($type == 'book')
    {
      $media = Files::where('type',2)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    elseif($type == 'video')
    {
      $media = Files::where('type',1)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }else
    {
      $media = Files::where('branch',$type)->where(function ($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
      #dd($media);
    }
    return $media;
  }
  public static function returnCurrentRadio()
  {
    $infos = file_get_contents("http://hobby9.com:9000/status-json.xsl");
    $infos = json_decode($infos,true);
    $title = $infos['icestats']['source']['title'];
    $title = trim(substr($title, 0,strpos($title, "[Hob")));
    $title = explode("-", $title);
    $arti = trim($title[1]);
    $title = trim($title[0]);
    $infos = array(
      "title" => $title,
      "artist" => $arti
    );
    return $infos;
  }
}
 ?>
