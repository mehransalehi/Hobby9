var idDel;
var grpType;
var isFreshLoad = true;
$(document).ready(function(){
    $("#expand-btn").bind( "click", function() {
        $(".media-div").toggleClass("media-div-expand col-sm-12 container");
        if($(".media-div").hasClass( "media-div-expand" ))
        {
        	$(".monitor").each(function(){
        		$(this).removeClass("monitor").addClass("monitor-expand");
        	});
            $("video").css("position","relative");
            $("#container").addClass("monitor-expand");
        	$(".similar-on").removeClass("similar-on").addClass("similar-on-expand");
        	$(".watch-later").removeClass("watch-later").addClass("watch-later-expand");
            $("#container_wrapper").addClass("monitor-expand");
            $("#container_wrapper").attr("id","container_wrapper_a");
        }
        else
        {
        	$(".monitor-expand").each(function(){
        		$(this).removeClass("monitor-expand").addClass("monitor");
        	});
        	$(".similar-on-expand").removeClass("similar-on-expand").addClass("similar-on");
        	$(".watch-later-expand").removeClass("watch-later-expand").addClass("watch-later");
            $("#container_wrapper_a").removeClass("monitor-expand");
            $("#container_wrapper_a").attr("id","container_wrapper");
        }
    });
    $(".media-staff-div").ready(function($){
          $("#btn-exp").click(function(){
                $("#hide-code").hide();
                $("#hide-exp").slideToggle("fast");
            });
            $("#btn-code").click(function(){
                $("#hide-exp").hide();
                $("#hide-code").slideToggle("fast");
            });

        $("#iframe-code").bind( "click",function(){
            $("#script-code").removeClass('active');
            $(this).addClass('active');
            $(".iframe-content").show();
            $(".script-content").hide();
            $("#media-embed-code").text($(this).attr("iframeurl"));
        });
        $("#script-code").bind( "click",function(){
            $("#iframe-code").removeClass('active');
            $(this).addClass('active');
            $(".iframe-content").hide();
            $(".script-content").show();
            $("#media-embed-code").text($(this).attr("scripturl"));
        });
    });
    $('#code-width').keyup(function () {
        var width = $('#code-width').val();
        var height = 640 - width;
        height = 360 - Math.floor(height / 2);
        var fileHash = $(this).attr("hash");
        var fileType = $(this).attr("media");

        if(fileType == 1)
        {
          var fUrl = '<iframe src="'+THIS_URL+'/video/embed/hash/'+fileHash+'/mt/frame" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="'+width+'" height="'+height+'" ></iframe>';
          var sUrl = '<div id="'+fileHash+'"><script type="text/JavaScript" src="'+THIS_URL+'/video/embed/hash/'+fileHash+'/mt/script/'+width+'/"></script></div>';
        }
        else if(fileType == 3)
        {
          var fUrl = '<iframe style="border:none;" src="'+THIS_URL+'/music/embed/hash/'+fileHash+'/mt/frame" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="'+width+'" height="48" ></iframe>';
          var sUrl = '<div id="'+fileHash+'"><script type="text/JavaScript" src="'+THIS_URL+'/music/embed/hash/'+fileHash+'/mt/script/'+width+'/"></script></div>';
        }
        $("#media-embed-code-iframe").text(fUrl);
        $("#media-embed-code-script").text(sUrl);
        $('#code-height').val(height);
    });
    $("#lamp-btn").click(function(){
        $(".hobby-container").css("z-index","100");
        $(".dark-div").toggle();
    });
});


function return_similar(hash)
{
    var data = {"command":"similar","media":hash};
    sendCommand(data,"similar");
}
function follow_media(hash)
{
    if($("#btn-like").length)
    {
        var data = {"media":hash,"type":"media"};
        sendCommand(data,"followmedia");
    }
    else if($("#btn-unlike").length)
    {
        var data = {"media":hash,"type":"media"};
        sendCommand(data,"unfollowmedia");
    }
}
function send_comment()
{
    var text = $("#com-text").val();
    var code = $("#com-code").val();
    var hash = $("#com-file").val();
    if(text.length == 0 || code.length == 0 )
    {
        notify('متن یا کد امنیتی وارد نشده است','warning');
        return;
    }
    var data = {"text":text,"captcha":code,"hash":hash};
    sendCommand(data,"comment");
}
function rep_comment(id)
{
    idDel = id;
    $('#btn-rep-'+id+"> i").removeClass("fa-exclamation").addClass("fa-spinner fa-spin");
    $('#btn-rep-'+id).attr("onclick","");
    $('#btn-rep-'+id).prop( "disabled", true );
    var data = {"hash":id};
    sendCommand(data,"repcomment");
}
function send_vote(elem , type , hash)
{
    idDel = hash;
    var data = {"do":"vote","hash":hash,"type":type};
    sendCommand(data,"send-vote");
}
function del_comment(id)
{
    idDel = id;
    $('#btn-del-'+id+"> i").removeClass("fa-exclamation").addClass("fa-spinner fa-spin");
    $('#btn-del-'+id).attr("onclick","");
    $('#btn-del-'+id).prop( "disabled", true );
    var data = {"hash":id};
    sendCommand(data,"delcomment");
}
function load_group(Class)
{
    if(!isFreshLoad)
      return;
    $(".loading-wrapper").show();
    data = {"hash":Class};
    sendCommand(data,"classmedia");
}
function doResponse(result,command)
{
    console.log(result);
    var object = $('<div/>').append(result);
    var msg = $(object).find('#message').html();
    $("#msg-load").hide();
    if(command == "classmedia")
    {
        resLoadMedia(object,msg);
    }
    else if(command == "comment")
    {
        resSendCommand(object,msg);
    }
    else if(command == "delcomment")
    {
        resDelCommand(object,msg);
    }
    else if(command == "repcomment")
    {
        resRepCommand(object,msg);
    }
    else if(command == "send-vote")
    {
        resSendVote(object,msg);
    }
    else if(command == "follow-user")
    {
        resFoUser(object,msg);
    }
    else if(command == "unfollow-user")
    {
        resUnFoUser(object,msg);
    }
    else if(command == "followmedia")
    {
        resFoMedia(object,msg);
    }
    else if(command == "unfollowmedia")
    {
        resUnFoMedia(object,msg);
    }
    else if(command == "followed-class")
    {
        resFollowed(object,msg);
    }
    else if(command == "follower-class")
    {
        resFollower(object,msg);
    }
}
function resFoMedia(object,msg)
{
    var myStatus;
    myStatus = $(object).find('#status').html();
    if(myStatus == "faild")
    {
        notify(msg,'error');
    }
    else if(myStatus == "success")
    {
        $(".fa-heart-o").removeClass("fa-heart-o").addClass("fa-heart").css("color","red");
        $("#btn-like .btns-text-staff").text("نمی پسندم");
        $("#btn-like").attr("id","btn-unlike");
        notify(msg,'notice');
    }
}
function resUnFoMedia(object,msg)
{
    var myStatus;
    myStatus = $(object).find('#status').html();
    if(myStatus == "faild")
    {
        notify(msg,'error');
    }
    else if(myStatus == "success")
    {
        $(".fa-heart").removeClass("fa-heart").addClass("fa-heart-o").css("color","black");
        $("#btn-unlike .btns-text-staff").text("پسندیدن");
        $("#btn-unlike").attr("id","btn-like");
        notify(msg,'notice');
    }
}
function resLoadMedia(object,msg)
{
  var myStatus = $(object).find('#status').html();
  var code = $(object).find('#code').html();
  if(myStatus == 'SUCCESS')
  {
    isFreshLoad = false;
    $(".loading-wrapper").hide();
    $("#class-media").append(code);
  }
}
function resDelCommand(object,msg)
{
    var myStatus;
    myStatus = $(object).find('#status').html();
    if(myStatus == "faild")
    {
        notify(msg,'error');
    }
    else if(myStatus == "success")
    {
        notify(msg,'notice');
        $('#item-list-'+idDel).remove();
    }
}
function resSendVote(object,msg)
{
    var myStatus;
    myStatus = $(object).find('#status').html();
    if(myStatus == "ERROR")
    {
        showMessage(msg,true);
    }
    else if(myStatus == "OK")
    {
        var code = $(object).find('#code').html();
        var msg = 'با موفقیت ثبت شد';
        if(code.indexOf("WRONG") > -1)
        {
            if(code.indexOf("WRONG HASH") > -1)
            {
                msg = 'اطلاعات ورودی اشتباه است';
            }
            else if(code.indexOf("WRONG SAME CLASS") > -1)
            {
                msg = 'امکان رای دادن به نظر خود وجود ندارد.';
            }
            else if(code.indexOf("WRONG CLASS") > -1)
            {
                msg = 'برای رای دادن وارد پروفایل کاربری خود شوید.';
            }
            else if(code.indexOf("WRONG SCRIPT") > -1)
            {
                msg = 'مشکلی در اجرای برنامه های سمت سرور به وجود آمده است.';
            }
            else if(code.indexOf("WRONG VOTE") > -1)
            {
                msg = 'قبلا به این پست رای داده اید.';
            }
            showMessage(msg,true);
            return;
        }
        showMessage(msg,false);
        $("#vote-number-"+idDel).html(code);
    }
}
function resRepCommand(object,msg)
{
    var myStatus;
    myStatus = $(object).find('#status').html();
    if(myStatus == "faild")
    {
        notify(msg,'error');
    }
    else if(myStatus == "success")
    {
        notify(msg,'notice');
        $('#btn-rep-'+idDel).remove();
    }
}
function resSendCommand(object,msg)
{
    var myStatus;
    myStatus = $(object).find('#status').html();
    if(myStatus == "faild")
    {
        notify(msg,"error");
    }
    else if(myStatus == "success")
    {
        var code = $(object).find('#code').html();
        var captcha = $(object).find('#captcha').html();
        notify(msg,'notice');
        $("#code-pic").attr("src",captcha);
        $("#comments-list").prepend(code);
    }
    $("#msg-no-comment").hide();
    $("#com-text").val('');
    $("#com-code").val('');
}
function change_tab(this_tab)
{
    if(!$(this_tab).hasClass("active"))
    {
        $(".left-media-tab li").each(function(){
            if($(this).hasClass("active"))
            {
                $(this).removeClass("active");
                $("#"+$(this).attr("c-data")).hide();
            }
            else if(!$(this).hasClass("active"))
            {
                $(this).addClass("active");
                $("#"+$(this).attr("c-data")).show();
            }
        });
        return true;
    }
    return false;
}
function expand_showfile()
{
    $("#exp-this").hide();
    $("#exp-expand-this").show();
}
