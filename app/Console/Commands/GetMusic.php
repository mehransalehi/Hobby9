<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Htmldom;
use App\Classes\Admin\DlClass;



class GetMusic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobby:music';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Music and Music Video from nex1music website.';

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
      $html = new Htmldom('http://nex1music.ir/music/');
      $i = 0;
      foreach($html->find('a.mre') as $element)
      {
        $con = new Htmldom($element->href);
        $title = $con->find('meta[name=twitter:title]')[0]->content;
        $img = $con->find('meta[name=twitter:image]')[0]->content;

        $dls = $con->find('div.linkdl a');
        $d128 = $d320 = '';
        foreach ($dls as $dl)
        {
          if(strpos($dl->href, '128') !== false)
          {
            $d128 = $dl->href;
          }
          else
          {
            $d320 = $dl->href;
          }
        }
        $title = explode(' به نام ', $title);
        if(count($title) != 2)
        {
          continue;
        }
        $creator = $title[0];
        $title = $title[1];
        $creator = str_replace('دانلود آهنگ ', '', $creator);


        $data = array(
          'url' => $d128,
          'user' => config('co.radio_user'),
          'title' => $title,
          'author' => $creator,
          'img' => $img,
          'branch' => 'جدیدترین موزیک ها',
        );
        DlClass::dlSave($data);

        echo PHP_EOL.PHP_EOL."__________________".PHP_EOL;
        echo "title : ".$title.PHP_EOL;
        echo "creator : ".$creator.PHP_EOL;
        echo "image : ".$img.PHP_EOL;
        echo "128 : ".$d128.PHP_EOL;
        echo "320 : ".$d320.PHP_EOL;

        #if($i == 3)
        #  break;
        $i++;
      }

    }
}
