<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Classes\Admin\BlogClass;

class AdminBlogController extends Controller
{
    public function branch()
    {
      $groups = BlogClass::returnGroups();
      $data = array('groups'=>$groups);
      return view('admin.blog.branch',['data'=>$data]);
    }
    public function post()
    {
      $groups = BlogClass::returnGroups();
      $posts = BlogClass::returnPosts();
      $data = array('groups'=>$groups,'posts'=>$posts);
      #dd($data);
      return view('admin.blog.post',['data'=>$data]);
    }
    public function delBranch($id)
    {
      BlogClass::delBranch($id);
      $groups = BlogClass::returnGroups();

      $data = array('status'=>'deleted','groups'=>$groups);
      return view('admin.blog.branch',['data'=>$data]);
    }
    public function delPost($id)
    {
      BlogClass::delPost($id);
      $groups = BlogClass::returnGroups();
      $posts = BlogClass::returnPosts();

      $data = array('status'=>'deleted','groups'=>$groups,'posts'=>$posts);
      return view('admin.blog.post',['data'=>$data]);
    }
    public function savePost(Request $request)
    {
      $val = Validator::make($request->all(),[
        "title"=>'required',
        "fabranch"=>'required',
        "writer"=>'required',
        "text"=>'required'
      ]);

      if($val->fails())
      {
        return redirect('/webmaster/blog/post/')->withErrors($val);
      }
      $news = 0;
      if($request->news == 'checked')
        $news = 1;

      BlogClass::savePost($request->title,$request->writer,$request->text,$request->fabranch,$news,$request->id);
      $groups = BlogClass::returnGroups();
      $posts = BlogClass::returnPosts();
      $data = array('status'=>'success','groups'=>$groups,'posts'=>$posts);
      return view('admin.blog.post',['data'=>$data]);
    }
    public function saveBranch(Request $request)
    {
      $val = Validator::make($request->all(),[
        "title"=>'required',
        "order"=>'required',
        "menu_title"=>'required'
      ]);

      if($val->fails())
      {
        return redirect('/webmaster/blog/branch/')->withErrors($val);
      }

      BlogClass::saveGroup($request->title,$request->menu_title,$request->order,$request->id);
      $groups = BlogClass::returnGroups();

      $data = array('status'=>'success','groups'=>$groups);
      return view('admin.blog.branch',['data'=>$data]);
    }
}
