<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    #protected $connection = 'mysql1';
    protected $fillable = ['title', 'creator', 'publisher', 'type', 'pagetime', 'explenation', 'ispublished', 'endate', 'lang', 'hash', 'numdownload', 'volume', 'filetype', 'price', 'tags', 'visit', 'likes', 'branch', 'fabranch', 'servernum', 'class', 'path', 'ip',
    'd1', 'd2', 'd3', 'd4', 'd5', 'd6', 'd7', 'soflag'];
    protected $table = 'tbl_files';
    public $timestamps = false;

    public function user()
    {
      return $this->belongsTo('App\Classes','class','hash');
    }
    public function branch()
    {
      return $this->belongsTo('App\UserBranch','fabranch','hash');
    }
    public function radio()
    {
      return $this->belongsTo('App\Radio','hash','media_hash');
    }
    public function follow()
    {
        return $this->hasMany('App\Follow','target','hash');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment','file','hash');
    }
    public function coQueue()
    {
      return $this->hasOne('App\CoQueue','file_hash','hash');
    }
}
