<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Files;
use App\Classes\SymlinkClass;
use App\Classes;
use Illuminate\Support\Facades\DB;
use App\BlogPost;
class GenerateSitemap extends Command
{
    private $urlXml = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    </urlset>';


       #<url>
       #  <loc>http://www.example.com/</loc>
       #  <lastmod>2005-01-01</lastmod>
       #  <changefreq>monthly</changefreq>
       #  <priority>0.8</priority>
       #</url>

    private $indexXml = '<?xml version="1.0" encoding="UTF-8"?>
    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    </sitemapindex>';
       #<sitemap>
        #<loc>http://www.example.com/sitemap1.xml.gz</loc>
        #<lastmod>2004-10-01T18:23:17+00:00</lastmod>
       #</sitemap>
       #<sitemap>
        #<loc>http://www.example.com/sitemap2.xml.gz</loc>
        #<lastmod>2005-01-01</lastmod>
       #</sitemap>
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $recNum = 40000;
    protected $signature = 'hobby:sitemap {isnew=no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append new media and blog post to sitemap and submit it to webmaster tools.';

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
      $arguments = $this->arguments();
      $new = false;
      if($arguments && $arguments['isnew'] == 'new')
      {
          $new = true;
      }
      #$indexsitemap = simplexml_load_file(public_path().'/sitemap.xml');
      #echo basename($indexsitemap->sitemap->loc);exit;
      #var_dump($indexsitemap);
      #echo "YES";exit;
      if($new)
      {
        #--if main sitemap not exist
        #---- make main sitemap
        #-- check to static link sitemap file exist
        #---- if not return false and say static site sitemap file shloud create manualy and exit
        #---- if exist add static link sitemap file to main sitemap
        #-- make public/sitemaps folder if not exist
        #-- make a sitemap for medias in public/sitemap folder and add a record for each published media
        #---- 1- link to media show page
        #---- 2- link to media download page
        #---- 3- link to direct media file
        #-- add medias sitemap to main sitemap
        #-- make a sitemap for blog posts in public/sitemap folder and add a record for each post
        #---- 1- link to post
        #-- add blogmap sitemap to main sitemap
        #-- make a sitemap for user channels in public/sitemap folder and add a record for each user
        #---- 1- link to channel
        #-- add channelmap sitemap to main sitemap


        if(!file_exists(public_path().'/sitemap.xml'))
        {
          $sitemap = simplexml_load_string($this->indexXml);
          $sitemap->asXML(public_path().'/sitemap.xml');
        }
        else
        {
          echo PHP_EOL."There is main sitemap now if you want to recreate sitemap delete public/sitemap.xml".PHP_EOL;exit;
        }

        $indexsitemap = simplexml_load_file(public_path().'/sitemap.xml');

        if(!file_exists(public_path().'/sitemap/staticmap.xml'))
        {
          echo PHP_EOL."Static sitemap must create manualy and placed in public/sitemap/staticmap.xml".PHP_EOL;exit;
        }

        $sitemap = $indexsitemap->addChild('sitemap');
        $sitemap->addChild('loc', url('/sitemap/staticmap.xml'));
        $sitemap->addChild('lastmod', date('Y-m-d'));

        $indexsitemap->asXML(public_path().'/sitemap.xml');

        #Storage::makeDirectory(public_path().'/sitemap/mediamaps/');
        shell_exec('mkdir -p '.public_path().'/sitemap/mediamaps/');
        shell_exec('mkdir -p '.public_path().'/sitemap/videomaps/');
        shell_exec('mkdir -p '.public_path().'/sitemap/dlmaps/');

        $files = Files::whereIn('ispublished',[1,5,12])->with('user')->get();
        echo PHP_EOL."Count : ".count($files).PHP_EOL;

        $mediamap = simplexml_load_string($this->urlXml);
        $dlmap = simplexml_load_string($this->urlXml);
        $videomap = simplexml_load_string($this->urlXml);
        $videomap->addAttribute("xmlns:xmlns:video",'http://www.google.com/schemas/sitemap-video/1.1');

        $i=$i1=$i2=0;
        $j=$j1=$j2=1;
        foreach ($files as $file)
        {
          echo PHP_EOL.$i.PHP_EOL;
          if($i>$this->recNum)
          {
            file_put_contents(public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-'.$j.'.xml', $mediamap->asXML());
            $indexmapurl = $indexsitemap->addChild('sitemap');
            $indexmapurl->addChild('loc', url('/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-'.$j.'.xml'));
            $indexmapurl->addChild('lastmod', date('Y-m-d'));

            $mediamap = simplexml_load_string($this->urlXml);
            $j++;
            $i=1;
          }
          if($i2>$this->recNum)
          {
            file_put_contents(public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-'.$j2.'.xml', $dlmap->asXML());
            $indexmapurl = $indexsitemap->addChild('sitemap');
            $indexmapurl->addChild('loc', url('/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-'.$j.'.xml'));
            $indexmapurl->addChild('lastmod', date('Y-m-d'));

            $dlmap = simplexml_load_string($this->urlXml);
            $j2++;
            $i2=1;
          }
          if($i1>$this->recNum)
          {
            file_put_contents(public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-'.$j1.'.xml', $videomap->asXML());
            $indexmapurl = $indexsitemap->addChild('sitemap');
            $indexmapurl->addChild('loc', url('/sitemap/videomaps/videomap-'.date('Y-m-d').'-'.$j1.'.xml'));
            $indexmapurl->addChild('lastmod', date('Y-m-d'));

            $videomap = simplexml_load_string($this->urlXml);
            $videomap->addAttribute("xmlns:xmlns:video",'http://www.google.com/schemas/sitemap-video/1.1');
            $j1++;
            $i1=1;
          }

          $dlurl = $dlmap->addChild('url');
          $dlurl->addChild('loc', url('dl/'.$file->hash));

          $path = SymlinkClass::create($file->hash,$file->path,$file->filetype);

          if($file->type == 1)
          {
            $i1++;
            $url = $videomap->addChild('url');
            $url->addChild('loc', url('s/'.$file->hash).'/'.\App\Http\handyHelpers::UE($file->title));

            $video = $url->addChild('video:video:video');
            $thumb = url('includes/returnpic.php?s=1&amp;type='.$file->type.'&amp;picid='.$file->hash.'&amp;p='.$file->path);
            $video->addChild('video:video:thumbnail_loc', $thumb);
            $video->addChild('video:video:title', '<![CDATA['.str_replace('&', '', mb_substr($file->title,0,100)).']]>');
            if(empty($file->explenation))
            {
              $exp = 'توضیحاتی برای رسانه'.$file->title.'درج نشده است.';
            }
            else
            {
              $exp = $file->explenation;
            }
            $video->addChild('video:video:description', '<![CDATA['.str_replace('&', '', mb_substr($exp,0,2048)).']]>');
            $video->addChild('video:video:content_loc', $path);
            $dur = $this->timeString($file->pagetime);
            $video->addChild('video:video:duration', $dur);
            $video->addChild('video:video:category', $file->branch);
            $video->addChild('video:video:family_friendly', 'yes');
            $video->addChild('video:video:uploader', $file->user->name)->addAttribute("info",url('class/'.$file->user->hash));
          }
          elseif($file->type == 2)
          {
            $i++;
            $url = $mediamap->addChild('url');
            $url->addChild('loc', url('s/'.$file->hash).'/'.\App\Http\handyHelpers::UE($file->title));
            $contenturl = $mediamap->addChild('url');
            $contenturl->addChild('loc', $path);
          }
          elseif($file->type == 3)
          {
            $i++;
            $url = $mediamap->addChild('url');
            $url->addChild('loc', url('s/'.$file->hash).'/'.\App\Http\handyHelpers::UE($file->title));
            $contenturl = $mediamap->addChild('url');
            $contenturl->addChild('loc', $path);
          }

        }

        file_put_contents(public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-'.$j.'.xml', $dlmap->asXML());
        $indexmapurl = $indexsitemap->addChild('sitemap');
        $indexmapurl->addChild('loc', url('/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-'.$j.'.xml'));
        $indexmapurl->addChild('lastmod', date('Y-m-d'));

        file_put_contents(public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-'.$j.'.xml', $mediamap->asXML());
        $indexmapurl = $indexsitemap->addChild('sitemap');
        $indexmapurl->addChild('loc', url('/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-'.$j.'.xml'));
        $indexmapurl->addChild('lastmod', date('Y-m-d'));

        file_put_contents(public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-'.$j1.'.xml', $videomap->asXML());
        $indexmapurl = $indexsitemap->addChild('sitemap');
        $indexmapurl->addChild('loc', url('/sitemap/videomaps/videomap-'.date('Y-m-d').'-'.$j1.'.xml'));
        $indexmapurl->addChild('lastmod', date('Y-m-d'));


        $users = Classes::all();

        $channelmap = simplexml_load_string($this->urlXml);
        foreach ($users as $user)
        {
          $url = $channelmap->addChild('url');
          $url->addChild('loc', url('class/'.$user->hash));
        }
        file_put_contents(public_path().'/sitemap/channelmap.xml', $channelmap->asXML());
        $indexmapurl = $indexsitemap->addChild('sitemap');
        $indexmapurl->addChild('loc', url('/sitemap/channelmap.xml'));
        $indexmapurl->addChild('lastmod', date('Y-m-d'));


        $posts = BlogPost::orderBy('id','desc')->with('group')->get();
        $blogmap = simplexml_load_string($this->urlXml);
        foreach ($posts as $post)
        {
          $url = $blogmap->addChild('url');
          $url->addChild('loc', url('/blog/'.$post->group->id.'/'.$post->id.'/'));
        }


        file_put_contents(public_path().'/sitemap/blogmap.xml', $blogmap->asXML());
        $indexmapurl = $indexsitemap->addChild('sitemap');
        $indexmapurl->addChild('loc', url('/sitemap/blogmap.xml'));
        $indexmapurl->addChild('lastmod', date('Y-m-d'));


        file_put_contents(public_path().'/sitemap.xml', $indexsitemap->asXML());

      }
      else
      {
        #if main sitemap not exist
        #-- return an error to run command with new arguman
        #else
        #-- load sitemap for medias in public/sitemap folder and check url record numbers and size
        #-- if check return false
        #---- make new sitemap
        #---- append new sitemap to main sitemap file
        #-- else
        #---- append new medias urls
        #-- load sitemap for blog posts in public/sitemap folder and check url record numbers and size
        #-- if check return false
        #---- make new sitemap
        #---- append new sitemap to main sitemap file
        #-- else
        #---- append new medias urls
        #-- make a sitemap for user channels in public/sitemap folder and check url record numbers and size
        #-- if check return false
        #---- make new sitemap
        #---- append new sitemap to main sitemap file
        #-- else
        #---- append new medias urls
        if(!file_exists(public_path().'/sitemap.xml'))
        {
          echo PHP_EOL."Main Sitemap not exist you need to run this command with new parameter".PHP_EOL;exit;
        }
        $indexsitemap = simplexml_load_file(public_path().'/sitemap.xml');

        $videomaps = glob(public_path().'/sitemap/videomaps/*.xml');
        $mapVideoExist = false;
        if(count($videomaps)<1)
        {
          $latest_video_map = simplexml_load_string($this->urlXml);
          $latest_video_map->addAttribute("xmlns:xmlns:video",'http://www.google.com/schemas/sitemap-video/1.1');
          $latest_video_map->asXML(public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-1.xml');
          $latest_video_map = public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-1.xml';
        }
        else
        {
          $mapVideoExist = true;
          #$videomaps = array_combine($videomaps, array_map("filemtime", $videomaps));
          #var_dump($videomaps);exit;
          #arsort($videomaps);
          #$latest_video_map = key($videomaps);
          $latest_video_map = end($videomaps);
        }

        $videomapSize = filesize($latest_video_map)/1024/1024;
        if($videomapSize >= 40)
        {
          $videomapSize = 0;
          $latest_video_map = simplexml_load_string($this->urlXml);
          $latest_video_map->addAttribute("xmlns:xmlns:video",'http://www.google.com/schemas/sitemap-video/1.1');
          $latest_video_map->asXML(public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-1.xml');
          $latest_video_map = public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-1.xml';
        }


        #check other map
        $mediamaps = glob(public_path().'/sitemap/mediamaps/*.xml');
        $mapExist = false;
        if(count($mediamaps)<1)
        {
          $latest_map = simplexml_load_string($this->urlXml);
          $latest_map->asXML(public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-1.xml');
          $latest_map = public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-1.xml';
        }
        else
        {
          $mapExist = true;
          /*$mediamaps = array_combine($mediamaps, array_map("filemtime", $mediamaps));
          arsort($mediamaps);
          $latest_map = key($mediamaps);*/
          $latest_map = end($mediamaps);
        }

        $mediamapSize = filesize($latest_map)/1024/1024;
        if($mediamapSize >= 40)
        {
          $mediamapSize = 0;
          $latest_map = simplexml_load_string($this->urlXml);
          $latest_map->asXML(public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-1.xml');
          $latest_map = public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-1.xml';
        }

        #check dl map
        $dlmaps = glob(public_path().'/sitemap/dlmaps/*.xml');
        $mapDlExist = false;
        if(count($dlmaps)<1)
        {
          $latest_dl_map = simplexml_load_string($this->urlXml);
          $latest_dl_map->asXML(public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-1.xml');
          $latest_dl_map = public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-1.xml';
        }
        else
        {
          $mapDlExist = true;
          /*$mediamaps = array_combine($mediamaps, array_map("filemtime", $mediamaps));
          arsort($mediamaps);
          $latest_map = key($mediamaps);*/
          $latest_dl_map = end($dlmaps);
        }

        $dlmapSize = filesize($latest_dl_map)/1024/1024;
        if($dlmapSize >= 40)
        {
          $dlmapSize = 0;
          $latest_dl_map = simplexml_load_string($this->urlXml);
          $latest_dl_map->asXML(public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-1.xml');
          $latest_dl_map = public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-1.xml';
        }

        #######################################################################################################

        $newFiles = Files::where('endate','>=',DB::raw('DATE_SUB(CURDATE(),INTERVAL 2 DAY)'))
        ->where('type',1)->whereIn('ispublished',[1,5,12])->with('user')->get();
        echo count($newFiles);
        #echo $newFiles[0]->title;exit;
        $mediamap = simplexml_load_file($latest_map);
        $mediamapCount = count($mediamap->url);
        $diff = $this->recNum - $mediamapCount;

        $videomap = simplexml_load_file($latest_video_map);
        $videomapCount = count($videomap->url);
        $diff1 = $this->recNum - $videomapCount;

        $dlmap = simplexml_load_file($latest_dl_map);
        $dlmapCount = count($dlmap->url);
        $diff2 = $this->recNum - $dlmapCount;

        $i=$i1=$i2=0;
        $j=$j1=$j2=1;
        foreach ($newFiles as $file)
        {
          echo PHP_EOL.$i.PHP_EOL;
          if($i1>=$diff1)
          {
            $videomap->asXML($latest_map);
            if($mapVideoExist)
            {
              foreach ($indexsitemap->sitemap as $s)
              {
                if(basename($s->loc) == basename($latest_video_map))
                {
                  $s->lastmod = date('Y-m-d');
                  break;
                }
              }
            }
            else
            {
              $indexmapurl = $indexsitemap->addChild('sitemap');
              $indexmapurl->addChild('loc', url('/sitemap/videomaps/'.basename($latest_video_map)));
              $indexmapurl->addChild('lastmod', date('Y-m-d'));
            }
            $mapVideoExist = false;
            $videomap = simplexml_load_string($this->urlXml);
            $videomap->addAttribute("xmlns:xmlns:video",'http://www.google.com/schemas/sitemap-video/1.1');
            $videomap->asXML(public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-'.$j1.'.xml');
            $latest_video_map = public_path().'/sitemap/videomaps/videomap-'.date('Y-m-d').'-'.$j1.'.xml';
            $videomap = simplexml_load_file($latest_video_map);
            $j1++;
            $diff1 = $this->recNum;
            $i1=1;
          }

          if($i2>=$diff2)
          {
            $dlmap->asXML($latest_dl_map);
            if($mapDlExist)
            {
              foreach ($indexsitemap->sitemap as $s)
              {
                if(basename($s->loc) == basename($latest_dl_map))
                {
                  $s->lastmod = date('Y-m-d');
                  break;
                }
              }
            }
            else
            {
              $indexmapurl = $indexsitemap->addChild('sitemap');
              $indexmapurl->addChild('loc', url('/sitemap/dlmaps/'.basename($latest_dl_map)));
              $indexmapurl->addChild('lastmod', date('Y-m-d'));
            }
            $mapDlExist = false;
            $dlmap = simplexml_load_string($this->urlXml);
            $dlmap->asXML(public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-'.$j2.'.xml');
            $latest_dl_map = public_path().'/sitemap/dlmaps/dlmap-'.date('Y-m-d').'-'.$j2.'.xml';
            $dlmap = simplexml_load_file($latest_dl_map);
            $j2++;
            $diff2 = $this->recNum;
            $i2=1;
          }

          if($i>=$diff)
          {
            $mediamap->asXML($latest_map);
            if($mapExist)
            {
              foreach ($indexsitemap->sitemap as $s)
              {
                if(basename($s->loc) == basename($latest_map))
                {
                  $s->lastmod = date('Y-m-d');
                  break;
                }
              }
            }
            else
            {
              $indexmapurl = $indexsitemap->addChild('sitemap');
              $indexmapurl->addChild('loc', url('/sitemap/mediamaps/'.basename($latest_map)));
              $indexmapurl->addChild('lastmod', date('Y-m-d'));
            }
            $mapExist = false;
            $mediamap = simplexml_load_string($this->urlXml);
            $mediamap->asXML(public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-'.$j.'.xml');
            $latest_map = public_path().'/sitemap/mediamaps/mediamap-'.date('Y-m-d').'-'.$j.'.xml';
            $mediamap = simplexml_load_file($latest_map);
            $j++;
            $diff = $this->recNum;
            $i=1;
          }

          $dlurl = $dlmap->addChild('url');
          $dlurl->addChild('loc', url('dl/'.$file->hash));

          $path = SymlinkClass::create($file->hash,$file->path,$file->filetype);

          if($file->type == 1)
          {
            $i1++;
            $url = $videomap->addChild('url');
            $url->addChild('loc', url('s/'.$file->hash).'/'.\App\Http\handyHelpers::UE($file->title));

            $video = $url->addChild('video:video:video');
            $thumb = url('includes/returnpic.php?s=1&amp;type='.$file->type.'&amp;picid='.$file->hash.'&amp;p='.$file->path);
            $video->addChild('video:video:thumbnail_loc', $thumb);
            $video->addChild('video:video:title', '<![CDATA['.str_replace('&', '', mb_substr($file->title,0,100)).']]>');
            if(empty($file->explenation))
            {
              $exp = 'توضیحاتی برای رسانه'.$file->title.'درج نشده است.';
            }
            else
            {
              $exp = $file->explenation;
            }
            $video->addChild('video:video:description', '<![CDATA['.str_replace('&', '', mb_substr($exp,0,2048)).']]>');
            $video->addChild('video:video:content_loc', $path);
            $dur = $this->timeString($file->pagetime);
            $video->addChild('video:video:duration', $dur);
            $video->addChild('video:video:category', $file->branch);
            $video->addChild('video:video:family_friendly', 'yes');
            $video->addChild('video:video:uploader', $file->user->name)->addAttribute("info",url('class/'.$file->user->hash));
          }
          elseif($file->type == 2)
          {
            $i++;
            $url = $mediamap->addChild('url');
            $url->addChild('loc', url('s/'.$file->hash).'/'.\App\Http\handyHelpers::UE($file->title));
            $contenturl = $mediamap->addChild('url');
            $contenturl->addChild('loc', $path);
          }
          elseif($file->type == 3)
          {
            $i++;
            $url = $mediamap->addChild('url');
            $url->addChild('loc', url('s/'.$file->hash).'/'.\App\Http\handyHelpers::UE($file->title));
            $contenturl = $mediamap->addChild('url');
            $contenturl->addChild('loc', $path);
          }

        }
        file_put_contents($latest_dl_map, $dlmap->asXML());
        if($mapDlExist)
        {
          foreach ($indexsitemap->sitemap as $s)
          {
            if(basename($s->loc) == basename($latest_dl_map))
            {
              $s->lastmod = date('Y-m-d');
              break;
            }
          }
        }
        else
        {
          $indexmapurl = $indexsitemap->addChild('sitemap');
          $indexmapurl->addChild('loc', url('/sitemap/dlmaps/'.basename($latest_dl_map)));
          $indexmapurl->addChild('lastmod', date('Y-m-d'));
        }

        file_put_contents($latest_map, $mediamap->asXML());
        if($mapExist)
        {
          foreach ($indexsitemap->sitemap as $s)
          {
            if(basename($s->loc) == basename($latest_map))
            {
              $s->lastmod = date('Y-m-d');
              break;
            }
          }
        }
        else
        {
          $indexmapurl = $indexsitemap->addChild('sitemap');
          $indexmapurl->addChild('loc', url('/sitemap/mediamaps/'.basename($latest_map)));
          $indexmapurl->addChild('lastmod', date('Y-m-d'));
        }


        file_put_contents($latest_video_map, $videomap->asXML());
        if($mapVideoExist)
        {
          foreach ($indexsitemap->sitemap as $s)
          {
            echo basename($s->loc) ." == ". basename($latest_video_map).PHP_EOL;
            if(basename($s->loc) == basename($latest_video_map))
            {
              $s->lastmod = date('Y-m-d');
              break;
            }
          }
        }
        else
        {
          $indexmapurl = $indexsitemap->addChild('sitemap');
          $indexmapurl->addChild('loc', url('/sitemap/videomaps/'.basename($latest_video_map)));
          $indexmapurl->addChild('lastmod', date('Y-m-d'));
        }


          ########################################################################

        $users = Classes::where('reg_date','>=',DB::raw('DATE_SUB(NOW(),INTERVAL 24 HOUR)'))->get();

        if(!$channelmap = simplexml_load_file(public_path().'/sitemap/channelmap.xml'))
        {
          echo PHP_EOL."Channel sitemap not exist you need to run this command with new parameter".PHP_EOL;exit;
        }
        foreach ($classes as $class)
        {
          $url = $channelmap->addChild('url');
          $url->addChild('loc', url('class/'.$user->hash));
        }
        file_put_contents(public_path().'/sitemap/channelmap.xml', $channelmap->asXML());

        foreach ($indexsitemap->sitemap as $s)
        {
          if(basename($s->loc) == 'channelmap.xml')
          {
            $s->lastmod = date('Y-m-d');
            break;
          }
        }


        $posts = BlogPost::where('mdate','>=',DB::raw('DATE_SUB(NOW(),INTERVAL 24 HOUR)'))->with('group')->get();
        if(!$blogmap = simplexml_load_file(public_path().'/sitemap/blogmap.xml'))
        {
          echo PHP_EOL."Blog sitemap not exist you need to run this command with new parameter".PHP_EOL;exit;
        }
        foreach ($posts as $post)
        {
          $url = $blogmap->addChild('url');
          $url->addChild('loc', url('/blog/'.$post->group->id.'/'.$post->id.'/'));
        }

        file_put_contents(public_path().'/sitemap/blogmap.xml', $blogmap->asXML());
        foreach ($indexsitemap->sitemap as $s)
        {
          if(basename($s->loc) == 'blogmap.xml')
          {
            $s->lastmod = date('Y-m-d');
            break;
          }
        }


        file_put_contents(public_path().'/sitemap.xml', $indexsitemap->asXML());

      }
        /*$xml = simplexml_load_string($this->urlXml);

        $url = $xml->addChild('url');
        $url->addChild('loc', 'http://www.example.com/');
        $url->addChild('priority', '0.8');

        echo $xml->asXML();*/

        $sitemapUrl = htmlentities(url('sitemap.xml'));
        //Google
        $url = "http://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapUrl;
        file_get_contents($url);

        //Bing / MSN
        $url = "http://www.bing.com/webmaster/ping.aspx?siteMap=".$sitemapUrl;
        file_get_contents($url);
        #SubmitSiteMap($url);

        // Live
        #$url = "http://webmaster.live.com/ping.aspx?siteMap=".$sitemapUrl;
        #SubmitSiteMap($url);

        // moreover
        #$url = "http://api.moreover.com/ping?sitemap=".$sitemapUrl;
        #SubmitSiteMap($url);

        echo PHP_EOL."DONE".PHP_EOL;
    }
    public function timeString($pgTime)
  	{
  		$showtime = $structTime = '';
  		$times = explode(":",$pgTime);
  		if(count($times) == 2 )
  		{
  			@$hour = 0;
  			@$min = (int)$times[0];
  			@$sec = (int)$times[1];
  		}
  		else
  		{
  			@$hour = (int)$times[0];
  			@$min = (int)$times[1];
  			@$sec = (int)$times[2];
  		}
      $structTime = ($hour*60*60)+($min*60)+($sec);
  		return $structTime;
  	}
}
