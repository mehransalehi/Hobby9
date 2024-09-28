@extends('layouts.default')

<?php $whereAmI='blog' ?>
@section('title')
  وبلاگ | HOBBY9
@stop
@section('style')
<link rel="stylesheet" href="<?php echo URL::asset('css/blog.css')?>" type="text/css">
@stop


@section('content')
  <div class="container-hobby row main-search-content">
		<div class="col-sm-3 col-md-2 col-xs-12 search-menu">
				<ul>
          @if(@$data['groups'])
            @foreach($data['groups'] as $group)
              <li><a href="{{ url('/blog/'.$group->id.'/') }}"><h3>{{ $group->menu_title }}</h3></a></li>
            @endforeach
          @endif
        </ul>
		</div>
		<div class="col-sm-9 col-md-10 col-xs-12 blog-content">
      @if($data['type'] == 'groups')
        <ul>
        @foreach($data['content'][0]->posts as $post)
          <li>
            <h2 class="post-title">{{ $post->text_title }}</h2>
            <div class="post-date">({{ \App\Http\handyHelpers::MTS($post->mdate) }})</div>
            <?php
              $content = explode('[!con!]',$post->content);
             ?>
             <p>
               {!! $content[0] !!}
             </p>
             <div class="post-writer">{{ $post->writer }}</div>
             @if(count($content) > 1)
               <a href="{{ url('blog/'.$data['group_id'].'/'.$post->id) }}" class="btn btn-hobby btn-con">ادامه مطلب</a>
             @endif
          <hr class="hr-blog">
        </li>
        @endforeach
      </ul>
      @else
        <ul>
        @foreach($data['content'] as $post)
          <li>
            <h2 class="post-title">{{ $post->text_title }}</h2>
            <div class="post-date">({{ \App\Http\handyHelpers::MTS($post->mdate) }})</div>
            <?php
              $content = explode('[!con!]',$post->content);
             ?>
             <p>
               @if(count($content) > 1)
                 {!! $content[1] !!}
               @else
                 {!! $content[0] !!}
               @endif
             </p>
             <div class="post-writer">{{ $post->writer }}</div>
        </li>
        @endforeach
      </ul>
      @endif
		</div>
	</div>


@stop
