<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Use config('your_key') to get the values.
    |
    */
    #Website information
    'company_name' => env('COMPANY_NAME','Hobby9'),
    'company_email' => env('COMPANY_email','info@hobby9.com'),
    #Page counts for paginators
    'itemSearchPage'=>30,
    'profileFilelistPerPage'=>16,
    'commentCount'  => 8,
    'apiCount' => 15,
    #upload options
    'convertQueueSize'=>10,
    'dlQueueSize'=>10,
    'allowedPicExtensions' => array('.jpg','.jpeg','.png','.gif'),
    'allowedPicMimes' => array('image/pjpeg','image/jpg','image/jpeg','image/gif','image/x-png','image/png'),
    'allowedExtensions' => array('.ac3','.aac','.wav','.ogg','.mts','.3gp','.mp3','.pdf','.wmv','.mov','.mpg','.mpeg','.mp4','.avi','.flv','.mkv'),
    'allowedMimes' => array('audio/mp4','audio/x-m4a','audio/ac3','audio/x-wav','video/x-ms-asf','video/x-msvideo','video/3gpp'
    ,'application/octet-stream','video/quicktime','video/x-m4v','audio/mpeg','application/pdf','video/x-ms-wmv','video/x-flv','video/mp4','video/mpeg'),
    'needConvert' => array('.ac3','.aac','.wav','.ogg','.mts','.wmv','.mov','.qt','.3gp','.3g2','.mpg','.mpeg','.m1a','.m2a'
    ,'.mp4','.m4v','.avi','.flv','.f4v','.vob','.asf','.webm','.mkv'),
    'videoExt' => array('.mts','.wmv','.mov','.qt','.3gp','.3gpp','.3g2','.3gp2','.mpg','.mpeg','.mp1','.mp2','.m1v','.m1a','.m2a','.mpa','.mpv',
    '.mpv2','.mpe','.mp4','.m4a','.m4p','.m4b','.m4r','.m4v','.avi','.flv','.f4v','.f4p','.f4a','.f4b','.vob','.lsf','.lsx','.asf','.asr','.asx',
    '.webm','.mkv'),
  	'bookExt' => array('.pdf'),
  	'audioExt' => array('.mp3','.wma','.aac','.wav','.ac3'),
    'ffmpegPath' => 'ffmpeg',
    'fileUploadLimit' => 75,#Mega bites
    'customPicSize' =>  2,#mega bites
    'tmpFileDir'  => 'tmp',
    'tmpPicDir'  => 'tmp/pics',
    'tmpUserPicDir'  => 'tmp/userpics',
    'MediaDir' => 'myfiles',
    'tmpUserPicPublicDir' => 'userpic',
    'dlPath'  =>  'dl',
    #radio
    'icecast_user_panel' => 'admin',
    'icecast_pass_panel' => 'divaneh1452',
    'ezstream' => '/home/hobby/.ezstream/',
    'radio_user' => '317b56b321080e4e65772ae0688e527b',#hash of radio user
    #telegram
    'bot_token' => '198935104:AAGyZkrOkr75K1ny7UZSzVelhfCHy2k8vf4',
    'music_channel_id' => '-1001143973779',
    #setting
    'userPicUploadLimit' => 2, #Mega Bites
    'categorys'  => array(
      'تفريحی'
      ,'متفرقه'
      ,'تبليغات'
      ,'کارتون'
      ,'طنز'
      ,'علمی'
      ,'حوادث'
      ,'حيوانات'
      ,'طبيعت'
      ,'صداوسيما'
      ,'شخصی'
      ,'سياسی'
      ,'آموزشی'
      ,'کامپيوتر'
      ,'هنری'
      ,'سلامت'
      ,'ورزشی'
      ,'مذهبی'
      ,'مهندسی'
      ),
    'hRabt'  => array('با','هم','ولی','نیز','لیکن','زیرا','خواه','نه','پس','همین که','تا','کسی','چیزی','چون','چه','چرا','پس از','همچنین','همه','همانگونه','فقط','بر','علاوه بر','طبق','روی','در','جایی','تحت','تا','بین','کجا','به کجا','به منظور','به رغم','بنابراین','برای','بدون','اگر','اما','از طریق','از','را','آنها','شما','ما','او','تو','من','و','و'),
];
