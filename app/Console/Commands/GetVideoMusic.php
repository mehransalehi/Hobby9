<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Htmldom;
use App\Classes\Admin\DlClass;



class GetVideoMusic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobby:musicvideo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Music Video from nex1music website.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $html = new Htmldom('http://nex1music.ir/music-video/');
      $i = 0;
      foreach($html->find('a.mre') as $element)
      {
        $con = new Htmldom($element->href);
        $title = $con->find('meta[name=twitter:title]')[0]->content;
        $img = $con->find('meta[name=twitter:image]')[0]->content;

        $dls = $con->find('div.linkdl a');
        $d480 = $d720 = '';
        foreach ($dls as $dl)
        {
          if(strpos($dl->href, '480') !== false)
          {
            $d480 = $dl->href;
          }
          else
          {
            $d720 = $dl->href;
          }
        }
        $title = explode(' به نام ', $title);
        if(count($title) != 2)
        {
          continue;
        }
        $creator = $title[0];
        $title = $title[1];
        $creator = str_replace('دانلود موزیک ویدئو جدید ', '', $creator);
        $creator = str_replace('دانلود موزیک ویدئو ', '', $creator);


        $data = array(
          'url' => $d480,
          'user' => config('co.radio_user'),
          'title' => $title,
          'author' => $creator,
          'img' => $img,
          'branch' => 'جدیدترین موزیک ها',
        );

        DlClass::dlSave($data,true);

        echo PHP_EOL.PHP_EOL."__________________".PHP_EOL;
        echo "title : ".$title.PHP_EOL;
        echo "creator : ".$creator.PHP_EOL;
        echo "image : ".$img.PHP_EOL;
        echo "480 : ".$d480.PHP_EOL;
        echo "720 : ".$d720.PHP_EOL;

        #if($i == 10)
        #  break;
        $i++;
      }

    }
}
