<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ads;

class AdminsAdsController extends Controller
{
    public function show()
    {
      $ads = Ads::all();
      $data = array();
      foreach ($ads as $ad)
      {
          $data[$ad->identify] = $ad->ad;
      }
      return view('admin.ads.ads',['data'=>$data]);
    }
    public function save(Request $request)
    {
      foreach ($request->all() as $key => $value) {
        if($key == '_token')
        {
          continue;
        }
        Ads::updateOrCreate(['identify'=>$key],['identify'=>$key,'ad'=>$value]);
      }
      $ads = Ads::all();
      $data['status'] = 'success';
      foreach ($ads as $ad)
      {
          $data[$ad->identify] = $ad->ad;
      }
      return view('admin.ads.ads',['data'=>$data]);
    }
}
