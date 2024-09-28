<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Files;
use App\Classes\HomeClass;

class MusicController extends Controller
{
    public function show()
    {
      $files = Files::whereIn('hash',function($query){
        $query->select('media_hash')->from('tbl_radio_songs')->orderBy('date','desc');
      })->with('user')->orderBy('endate','desc')->limit(32)->get();

      $data = array(
        'medias' => $files,
      );
      $data['currentRadio']= HomeClass::returnCurrentRadio();
      return view('radio.index',['data'=>$data]);
    }
}
