
<?php $media = $data['mainmedia'] ?>
@if($data['type'] == 'frame')
  <!doctype html>
  <html>
  <head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="<?php echo URL::asset('player/build/mediaelementplayer.min.css')?>" />
  <script src="<?php echo URL::asset('js/jquery-3.1.1.min.js')?>"></script>
  <script src="<?php echo URL::asset('player/jwplayer.js')?>" type="text/javascript"></script>
  <script src="<?php echo URL::asset('player/build/mediaelement-and-player.min.js')?>" type="text/javascript"></script>
  </head>
  @if ($media->type == 1)
    <body style="height: {{$data['height']}}; overflow: hidden; margin: 0px; padding: 0px; position: absolute; width: {{$data['width']}}px;">
  @else
    <body style="height: {{$data['height']}}; overflow: hidden; margin: 0px; padding: 0px; position: absolute; width: 100%;">
  @endif
    @if ($media->type == 1)
      <video id="container" src="{{$data['path']}}" poster="{{url('includes/returnpic.php?s=1&type='.$media->type.'&picid='.$media->hash.'&p='.$media->path)}}" width="100%" height="100%" preload="none"></video>
      <script>
        $('#container').mediaelementplayer(
          {
            enablePluginDebug: false,
            plugins: ['flash','silverlight'],
            // path to Flash and Silverlight plugins
            pluginPath: '<?php echo URL::asset('player/build/')?>/',
            flashName: 'flashmediaelement.swf',
            silverlightName: 'silverlightmediaelement.xap',
            success: function (mediaElement, domObject)
            {
              mediaElement.addEventListener('ended', function(e)
              {
                $("#complete-div").show();
              }, false);
              $(".btn-replay").bind( "click", function()
              {
                  mediaElement.play();
                  $("#complete-div").hide();
              });
              $("video").each(function(index)
              {
                  $(this).get(0).load();
                  $(this).get(0).addEventListener("canplaythrough", function()
                  {
                      this.play();
                      this.pause();
                  });
              });
            }
          });
      </script>
    @elseif ($media->type == 3)
      <audio class="audioplayer" id="player2" src="{{$data['path']}}" preload="none" type="audio/mp3" controls="controls">
        <p>Your browser leaves much to be desired.</p>
      </audio>
      <script>
        // using jQuery
        $('video,audio').mediaelementplayer({
          mode:"shim",audioWidth: "100%"
        });
      </script>
    @endif
  </body>
  </html>
@else
  (function() {
        var newiframe = document.createElement('iframe');
        newiframe.setAttribute('width','{{$data['width']}}');
        @if($media->type == 1)
          newiframe.setAttribute('height','{{$data['height']}}');
        @elseif($media->type == 3)
          newiframe.setAttribute('height','48');
        @endif
        newiframe.setAttribute('allowFullScreen','true');
        newiframe.setAttribute('webkitallowfullscreen','true');
        newiframe.setAttribute('mozallowfullscreen','true');
        newiframe.setAttribute('src','{{url('/video/embed/hash/'.$media->hash.'/mt/frame')}}');
        var timeoutNum = Math.floor((Math.random() * 1000) + 1);
        setTimeout(function(){
        document.getElementById('{{$media->hash}}').appendChild(newiframe);
         }, timeoutNum);
  })();
@endif
