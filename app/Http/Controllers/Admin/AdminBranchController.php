<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Classes\Admin\SpecialBranchClass;
class AdminBranchController extends Controller
{
    public function special()
    {
      $result = SpecialBranchClass::returnSpecial();
      return view('admin.branch.special',['special'=>$result]);
    }
    public function group()
    {
      $result = SpecialBranchClass::returnGroup();
      return view('admin.branch.group',['group'=>$result]);
    }
    public function delGroup($id)
    {
      SpecialBranchClass::delGroup($id);
      $result = SpecialBranchClass::returnGroup();
      $data['status']='deleted';
      return view('admin.branch.group',['data'=>$data,'group'=>$result]);
    }
    public function delSpecial($id)
    {
      SpecialBranchClass::delSpecial($id);
      $result = SpecialBranchClass::returnSpecial();
      $data['status']='deleted';
      return view('admin.branch.special',['data'=>$data,'special'=>$result]);
    }
    public function saveGroup(Request $request)
    {
      $val = Validator::make($request->all(),[
        "id" => 'required',
        "title" => 'required',
        "order" => 'required|digits:1',
        "title_hover" => 'required',
        "title_color" => 'required',
        "text_color" => 'required',
        "text_hover" => 'required',
        "back_color" => 'required',
        "hr_color" => 'required',
        "branch" => 'required'
      ]);
      if($val->fails())
      {
        return redirect('/webmaster/branch/group')->withErrors($val);
      }
      $val = Validator::make($request->all(),[
        "branch_hash" => 'required'
      ]);
      if($val->fails() && ($request->branch == 'hashed' || $request->branch == 'taged' || $request->branch == 'searched' || $request->branch == 'channel'))
      {
        return redirect('/webmaster/branch/group')->withErrors($val);
      }
      $value = array(
        "id" => $request->id,
        "title" => $request->title,
        "order" => $request->order,
        "title_hover" => $request->title_hover,
        "title_color" => $request->title_color,
        "text_color" => $request->text_color,
        "text_hover" => $request->text_hover,
        "back_color" => $request->back_color,
        "hr_color" => $request->hr_color,
        "link" => $request->link,
        "branch" => $request->branch,
        "branch_hash" => $request->branch_hash);
      SpecialBranchClass::saveGroup($request->id,$value);

      $data['status']='success';
      $result = SpecialBranchClass::returnGroup();
      return view('admin.branch.group',['data'=>$data,'group'=>$result]);
    }
    public function saveSpecial(Request $request)
    {
      $val = Validator::make($request->all(),[
        "id" => 'required',
        "text_color" => 'required',
        "icon_color" => 'required',
        "border_color" => 'required',
        "back_color" => 'required',
        "hr_color" => 'required',
        "link" => 'required',
        "text" => 'required',
        "icon" => 'required',
      ]);
      if($val->fails())
      {
        return redirect('/webmaster/branch/special')->withErrors($val);
      }
      $value = array(
        "id" => $request->id,
        "text_color" => $request->text_color,
        "icon_color" => $request->icon_color,
        "border_color" => $request->border_color,
        "back_color" => $request->back_color,
        "hr_color" => $request->hr_color,
        "link" => $request->link,
        "text" => $request->text,
        "icon" => $request->icon);
      SpecialBranchClass::saveSpecial($request->id,$value);

      $data['status']='success';
      $result = SpecialBranchClass::returnSpecial();
      return view('admin.branch.special',['data'=>$data,'special'=>$result]);
    }
}
