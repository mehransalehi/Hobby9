<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\BlogGroup;

class BlogController extends Controller
{
    public function blog($group=1,$page='none')
    {
      $groups = BlogGroup::orderBy('menu_order','asc')->get();
      $data['groups'] = $groups;
      if($page == 'none')
      {
        $data['type'] = 'groups';
        $data['group_id'] = $group;
        $data['content'] = BlogGroup::where('id',$group)->with(array('posts'=>function($query){
          $query->orderBy('mdate','desc');
        }))->get();
      }
      else
      {
        $data['type'] = 'page';
        $data['content'] = BlogPost::where('id',$page)->get();
      }
      #dd($data);
      return view('pages.blog',['data'=>$data]);
    }
}
