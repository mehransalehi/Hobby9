$(document).ready(function(){
  var wrap = $("#header");
  var top = 150;
  $(window).scroll(function() {
      var windowpos = $(window).scrollTop();
      //$("#test").html("offset : "+ $("#sticker").offset().top+"<br>Distance from top:" + top + "<br />Scroll position: " + windowpos);
      if (windowpos > top) {
        wrap.addClass("header-fix-pos");
        $(".search-text").addClass("search-text-fixed");
        $(".btn-wraper").addClass("btn-wraper-fixed");
        $(".header-logo>img").addClass("home-logo-fixed");
        $(".header-replacer").show();
        wrap.children("div").css('height',"80px");
      } else {
        wrap.removeClass("header-fix-pos");
        $(".header-replacer").hide();
        $(".search-text").removeClass("search-text-fixed");
        $(".header-logo>img").removeClass("home-logo-fixed");
        $(".btn-wraper").removeClass("btn-wraper-fixed");
        wrap.children("div").css('height',"150px");
      }
  });
  $("#expand-btn").bind( "click", function() {

  });
  $('#back-to-top').click(function () {
      $('body,html').animate({
          scrollTop: 0
      }, 400);
      return false;
  });
  $("[data-toggle=collapse]").bind( "click", function() {
      var target = $(this).attr("data-target");
      $(this).children('.fa-li').toggleClass("fa-chevron-left fa-chevron-down");
      $("#"+target).slideToggle("fast");
  });
  $('[data-toggle="tooltip"]').tooltip();
  $(".dismiss").click(function(){
         $("#notification").fadeOut("slow");
  });
  $("#notification").click(function(){
         $("#notification").fadeOut("slow");
  });
});
function sendCommand(data,command)
{
	var result;
	$.post(THIS_URL+"/ajax/"+command,data,
	function(rdata,status){
		doResponse(rdata,command);
});
}
function notify(msg,type)
{
  $("#notification").fadeOut("slow");
  $("#notification").removeClass();
  $("#notification").addClass("noty-"+type);
  $("#notification").fadeIn("slow").children('p').html(msg);
}
