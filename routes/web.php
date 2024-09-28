<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use App\Http\Middleware\CheckAdmins;
use App\Http\Middleware\CheckUsers;

#redirect
Route::get('/rules', function(){
  return redirect('blog/2');
});
Route::get('/ads', function(){
  return redirect('blog/4');
});

#test
Route::get('/json/latest/', 'publicController@latest');
Route::get('/json/{hash}/', 'ShowMediaController@showjson');
#API
Route::get('/api/branches', 'ApiController@branches');
Route::get('/api/branch/{type}','ApiController@returnBranch');

Route::get('/', 'publicController@index');
Route::get('/search', 'publicController@search');
Route::get('/searchradio', 'publicController@searchRadio');
Route::get('/notfound', 'publicController@notfound');
Route::get('/search/text', 'publicController@search');
Route::get('/group/{name}/', 'publicController@group');
Route::get('/branch/{id}/{title}', 'publicController@specialBranch');
Route::get('/feedback', 'publicController@feedback');
Route::post('/feedback', 'publicController@saveFeedback');



#music
Route::get('/radio/','MusicController@show');
Route::get('/currentradio/','publicController@returnCurrentRadio');
#admin
Route::get('/webmaster/login/','Admin\AdminController@login');
Route::post('/webmaster/login/','Admin\AdminController@doLogin');

Route::group(['middleware'=>CheckAdmins::class],function(){
  Route::get('/webmaster/','Admin\AdminController@index');
  #Admin blog
  Route::get('/webmaster/blog/branch/del/{id}','Admin\AdminBlogController@delBranch');
  Route::get('/webmaster/blog/branch/','Admin\AdminBlogController@branch');
  Route::post('/webmaster/blog/branch/save/','Admin\AdminBlogController@saveBranch');
  Route::get('/webmaster/blog/post/','Admin\AdminBlogController@post');
  Route::get('/webmaster/blog/post/del/{id}','Admin\AdminBlogController@delPost');
  Route::post('/webmaster/blog/post/save/','Admin\AdminBlogController@savePost');
  #Admin show group
  Route::get('/webmaster/branch/group/{id}','Admin\AdminBranchController@delGroup');
  Route::get('/webmaster/branch/group/','Admin\AdminBranchController@group');
  Route::post('/webmaster/branch/group/','Admin\AdminBranchController@saveGroup');
  #Admin Branch
  Route::get('/webmaster/branch/special/{id}','Admin\AdminBranchController@delSpecial');
  Route::get('/webmaster/branch/special/','Admin\AdminBranchController@special');
  Route::post('/webmaster/branch/special/','Admin\AdminBranchController@saveSpecial');
  #Admin Setting
  Route::get('/webmaster/setting/home/branch/{id}','Admin\AdminSettingController@delHomeShowBranch');
  Route::post('/webmaster/setting/{page}/{type}','Admin\AdminSettingController@saveSetting');
  Route::get('/webmaster/setting/{page}','Admin\AdminSettingController@setting');
  #Admin Ads
  Route::get('/webmaster/ads','Admin\AdminsAdsController@show');
  Route::post('/webmaster/ads','Admin\AdminsAdsController@save');
  #Contact with users
  Route::get('/webmaster/feedback/','Admin\AdminsFeedbackController@feedback');
  Route::get('/webmaster/feedback/msg/del/{id}','Admin\AdminsFeedbackController@feedbackDel');
  Route::post('webmaster/feedback/send/','Admin\AdminsFeedbackController@sendMail');
  #---Send emails
  Route::get('/webmaster/email/','Admin\AdminEmailController@show');
  Route::get('/webmaster/email/regall','Admin\AdminEmailController@registerAll');
  Route::get('/webmaster/email/deltrash','Admin\AdminEmailController@delTrash');
  Route::post('/webmaster/email/reg','Admin\AdminEmailController@sendReg');
  Route::post('/webmaster/email/huge','Admin\AdminEmailController@sendHuge');
  Route::post('/webmaster/email/users','Admin\AdminEmailController@sendUsers');
  #FILES
  #---social files
  Route::get('/webmaster/files/social','Admin\AdminFileController@showSocial');
  #---tele files
  Route::get('/webmaster/files/telefiles','Admin\AdminFileController@teleFiles');
  #---file manager
  Route::get('/webmaster/files/filemanager','Admin\AdminFileController@showActions');
  Route::get('/webmaster/files/showsorted','Admin\AdminFileController@showSort');
  Route::get('/webmaster/files/showradio','Admin\AdminFileController@showRadio');
  Route::get('/webmaster/files/deltrash','Admin\AdminFileController@delTrash');
  Route::get('/webmaster/files/deltele','Admin\AdminFileController@delTele');
  Route::get('/webmaster/files/search','Admin\AdminFileController@search');
  Route::get('/webmaster/files/dl','Admin\AdminFileController@dl');
  Route::post('/webmaster/files/dl','Admin\AdminFileController@dlLink');
  Route::get('/webmaster/files/dlmanage','Admin\AdminFileController@dlManage');
  #ajax
  Route::post('/ajax/telesave', 'Admin\AdminFileController@teleSave');
  Route::post('/ajax/managersave', 'Admin\AdminFileController@managerSave');
  Route::post('/ajax/singledel', 'Admin\AdminFileController@singleDel');
  Route::post('/ajax/radio', 'Admin\AdminFileController@radioToggle');
  Route::post('/ajax/geteditfile', 'Admin\AdminFileController@returnMediaEditForm');
  Route::post('/ajax/saveadminmedia', 'Admin\AdminFileController@editMedia');
  Route::post('/ajax/socialsend', 'Admin\AdminFileController@sendSocial');

});
#Register
Route::get('/register', 'RegisterController@register');
Route::post('/register', 'RegisterController@doRegister');
Route::get('/verify/{hash}', 'RegisterController@verify');
Route::get('/forget', 'RegisterController@forget');
Route::post('/forget', 'RegisterController@saveForget');
Route::get('/reset/{hash}', 'RegisterController@resetPass');
Route::post('/verify/{hash}', 'RegisterController@doVerify');

#blog
Route::get('/blog', 'BlogController@blog');
Route::get('/blog/{group}', 'BlogController@blog');
Route::get('/blog/{group}/{page}', 'BlogController@blog');
#show media
Route::get('/s/{hash}/{title?}', 'ShowMediaController@show')->where('title', '(.*)');
#embed
Route::get('/{filetype}/embed/hash/{hash}/mt/{type}/{width}', 'ShowMediaController@embed');
Route::get('/{filetype}/embed/hash/{hash}/mt/{type}', 'ShowMediaController@embed');

#DOWNLOAD
Route::get('/dl/{hash}', 'ShowMediaController@dl');
Route::post('/dl/{hash}', 'ShowMediaController@dlReturn');
#report
Route::get('/report/{hash}', 'ShowMediaController@report');
Route::post('/report/{hash}', 'ShowMediaController@saveReport');
#ajax
Route::post('/ajax/classmedia', 'ShowMediaController@getClassMedia');
Route::post('/ajax/followmedia', 'ShowMediaController@followMedia');
Route::post('/ajax/unfollowmedia', 'ShowMediaController@unFollowMedia');
Route::post('/ajax/comment', 'ShowMediaController@saveComment');
Route::post('/ajax/repcomment', 'ShowMediaController@reportComment');
Route::post('/ajax/delcomment', 'ShowMediaController@delComment');


#pics
Route::get('/includes/returnpic.php', 'publicController@returnPics');
Route::get('/returnpic.php', 'publicController@returnPics');
Route::get('/includes/user_pic.php', 'publicController@returnUserPic');
Route::get('/user_pic.php', 'publicController@returnUserPic');
#channel view
Route::get('/class/{hash}', 'publicController@channel');
Route::post('/class/{hash}', 'publicController@sendMsgTo');
Route::get('/class/{hash}/{type}', 'publicController@branchChannel');
Route::get('/class/{hash}/type/{type}', 'publicController@branchChannel');
Route::get('/rss/{hash}', 'publicController@feed');
#Profile
Route::get('/profile/login', 'Profile\ProfileController@login');
Route::post('/profile/login', 'Profile\ProfileController@doLogin');
Route::group(['middleware'=>CheckUsers::class],function(){
  Route::get('/profile', 'Profile\ProfileController@index');
  #filelist
  Route::get('/profile/filelist/del/{hash}', 'Profile\ProfileController@delMedia');
  Route::get('/profile/filelist/{type}', 'Profile\ProfileController@filelist');
  Route::get('/profile/filelist/', 'Profile\ProfileController@filelist');
  Route::get('/profile/filelist/edit/{hash}', 'Profile\ProfileController@editMedia');
  Route::post('/profile/filelist/edit/{hash}', 'Profile\ProfileController@saveMedia');
  #branches
  Route::get('/profile/branch/', 'Profile\ProfileController@branches');
  Route::get('/profile/branch/del/{hash}', 'Profile\ProfileController@delBranch');
  Route::post('/profile/branch/', 'Profile\ProfileController@createBranch');
  Route::post('/profile/branch/edit/{hash}', 'Profile\ProfileController@editBranch');
  #upload
  Route::get('/profile/upload/', 'Profile\UploadController@index');
  Route::post('/profile/upload/', 'Profile\UploadController@upload');
  Route::post('/profile/uploadaslink/', 'Profile\UploadController@dlLink');
  Route::post('/profile/upload/file/detail', 'Profile\UploadController@saveDetail');
  #upload ajax
  Route::post('/ajax/uploadMenu', 'Profile\UploadController@returnUploadMenu');
  Route::post('/profile/getprogress', 'Profile\UploadController@getProgress');

  Route::get('/profile/logout/', 'Profile\ProfileController@logout');
  #follow
  Route::get('/profile/follow/', 'Profile\ProfileController@showFollowed');
  #comment
  Route::get('/profile/comment/', 'Profile\ProfileController@showComment');
  Route::post('/profile/comment/', 'Profile\ProfileController@saveComment');
  #setting
  Route::get('/profile/setting/', 'Profile\ProfileController@showSetting');
  Route::get('/profile/setting/{tab}', 'Profile\ProfileController@showSetting');
  Route::post('/profile/setting/{tab}', 'Profile\ProfileController@saveSetting');
  Route::post('/profile/setting/pic/corp/', 'Profile\ProfileController@saveUserPic');
  #channel follow
  Route::get('/profile/followed/', 'Profile\ProfileController@showFollowedChannel');
  #message
  Route::get('/profile/msg/', 'Profile\ProfileController@showMessages');
  Route::get('/profile/msg/del/{id}', 'Profile\ProfileController@delMessages');
  #search
  Route::get('/profile/search/', 'Profile\ProfileController@search');
});


#Route::get('/', function () {
#    return view('welcome');
#});
