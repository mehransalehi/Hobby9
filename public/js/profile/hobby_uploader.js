var UPLOAD_MENU_TEXT = 'none';
var uploadDone = false;
var sendLinkDlDone = false;
var btnLaterClicked = false;
var btnLaterSendClicked = false;
$.ajaxSetup({
headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$(document).ready(function() {
	var isSupport = false;
	var MYACTION = "/profile/upload";
	var DLLINKACTION = "/profile/uploadaslink";
	var GETSTATUSLINK = '/profile/getprogress';
	var input ;
	var sendlinkInput;
	var allowedExtension = 'ogg|aac|ac3|wav|mkv|mp4|mp3|pdf|wmv|flv|avi|mpg|3gp|mov|mts';
	var maxSize = 75;
	var btn;
	var isThat = false;
	if(window.FormData !== undefined)
	{
		getUploadMenu();
		isSupport = true;
		var progressBar = $('<div class="btn-file-upload" onclick="document.getElementById(\'uploadBtn\').click()">آپلود فایل <span class="icon upload-ico"></span><br><div>کلیک کنید</div></div>');
		$("#maininput").append(progressBar);
		progressBar = $("<input>",
			{
				"type" : "file",
				"id":"uploadBtn",
				"style":"float:right;visibility:hidden"
			}
		);
		$("#maininput").append(progressBar);
	}
	else
	{
		var myDiv = $("<div>",
			{
				"style" : "color:red;font-size:12px;",
			}
		);
		myDiv.html("کاربر گرامی به دلیل قدیمی بودن مرورگر شما امکان چک کردن سایز فایل شما ممکن نیست لذا دقت داشته باشید که حجم فایل شما باید کمتر از ۵۰ مگا بایت باشد.درغیر این صورت پس از آپلود کامل فایل سایت پذیرای فایل شما نخواهد بود و وقت شما هدر خواهد رفت<br> مرورگر خود را به روز کنید");
		$("#maininput").append(myDiv);
		var myInput = $("<div>",
			{
				"style" : "clear:both;"
			}
		);
		$("#maininput").append(myInput);
		var formCode = $("<form>",
			{
				"action": MYACTION +"?oldBrowser=1",
				"method":"post",
				"enctype":"multipart/form-data",
				"id":"MyUploadForm",
				"name":"MyUploadForm",
				"target":"upload_res"
			}
		);
		var myInput = $("<input>",
			{
				"type" : "hidden",
				"name":"APC_UPLOAD_PROGRESS",
				"id":"progress_key",
				"value":upload_id
			}
		);
		formCode.append(myInput);
		var myInput = $("<input>",
			{
				"type" : "file",
				"name":"userfile",
				"id":"uploadBtn"
			}
		);
		formCode.append(myInput);
		var myInput = $("<iframe>",
			{
				"id":"upload_frame",
				"name":"upload_frame",
				"frameborder":"0",
				"border":"0",
				"src":"",
				"scrolling":"no",
				"scrollbar":"no",
				"style":"margin-top:50px;"
			}
		);
		formCode.append(myInput);
		var myInput = $("<div>",
			{
				"style" : "clear:both;"
			}
		);
		formCode.append(myInput);
		var myInput = $("<iframe>",
			{
				"id":"upload_res",
				"name":"upload_res",
				"frameborder":"0",
				"border":"0",
				"src":"",
				"height":"300px",
				"scrolling":"no",
				"scrollbar":"no",
				"style":"margin-right:50px;"
			}
		);
		formCode.append(myInput);
		$("#maininput").append(formCode);
		$("#maininput").append('<a style="font-size:15px;font-family:droid-bol" href="'+THIS_URL+'profile/upload/index1.php">آپلود یک فایل دیگر</a>')
		$("#MyUploadForm").submit(function()
		 {
		 	$('#upload_frame').show();
			function set () {
				$('#upload_frame').attr('src','upload_frame.php?up_id='+upload_id);
			}
			setTimeout(set);
	    	});
	}
	//local donwload btn
	$('#uploadBtn').on('change', function(){
		resetPage();
		input = $('#uploadBtn');
		if(isSupport)
		{
			var fd = new FormData();
			fd.append('userfile', input.prop("files")[0]);
			console.log(input.prop("files")[0]);
			progress_bar(fd);
		}
		else
		{
			if(checkExtention(input.val().split('\\').pop()) == -1)
			{
				alert("فایل با این پسوند پشتیبانی نمی شود.");
				return false;
			}
			 $("#uploadBtn").hide();
			 $("#MyUploadForm").submit();
		}
	});
	//download from link btn
	$('#btnDlLink').on('click', function(){
		sendResetPage();
		sendlinkInput = $("#dlLinkInput");
		if(checkExtention(sendlinkInput.val().split('\\').pop()) == -1)
		{
			alert("فایل با این پسوند پشتیبانی نمی شود.");
			return false;
		}
		var fd = new FormData();
		fd.append('text', sendlinkInput.val());
		sendDlLink(fd);
	});
	function sendDlLink(fd)
	{
		$.ajax({
			       url: DLLINKACTION,
			       type: "POST",
			       data: fd,
			       processData: false,
			       contentType: false,
			       xhr: function() {
							 	var req = $.ajaxSettings.xhr();
								return req;
				    },
			       success: function(response) {
							console.log(response);
			      		afterDlLinkSend(response);
			       },
			       error: function(jqXHR, textStatus, errorMessage) {
				   console.log(errorMessage);
			       }
			    });
	}
	function progress_bar(fd)
	{
		$.ajax({
			       url: MYACTION,
			       type: "POST",
			       data: fd,
			       processData: false,
			       contentType: false,
			       beforeSend : beforeSend,
			       xhr: function() {
			        $('#upload-progress-bar').asPieProgress('reset');
					var req = $.ajaxSettings.xhr();
					if (req) {
					    req.upload.addEventListener('progress',function(ev){
						progress = Math.round(ev.loaded * 100 / ev.total);
						$('#upload-progress-bar').asPieProgress('go',progress+'%');
						/*$('#upload-progress-bar').progressCircle({
						nPercent        : progress,
						showPercentText : true,
						circleSize      : 75,
						thickness       : 2
						});*/

					    }, false);
					}
					return req;
				    },
			       success: function(response) {
							console.log(response);
			      		afterUpload(response);
			       },
			       error: function(jqXHR, textStatus, errorMessage) {
				   console.log(errorMessage);
			       }
			    });
	}
	function beforeSendLink()
	{
		$("#formDiv").show();
		$("#sendlinkform").hide();
		$("#linkdetail").hide();
		$("#linkdetail").html("");
		$("#sendlinkprogressbox").show();
		$("#sendlink-file-name").text(sendlinkInput.val().split('\\').pop());
		//Change id of from for send link and btn submit sended form name.
		var htmlcode = $($.parseHTML( UPLOAD_MENU_TEXT ));
		htmlcode.find("form").attr("id","sendlinkfrm");
		htmlcode.find("#hash").attr("id","sendhash");
		htmlcode.find("#btnsubmit").attr("onclick","detail_submition('sendlinkfrm','link');");
		var SEND_MENU_TEXT = $('<div>').append(htmlcode.clone()).html()

		$("#info-send-link-file-form").html(SEND_MENU_TEXT);

		$("#formDivSend").show();
		$("#form-div-btns-send").show();
		$('#link-progress-bar').asPieProgress('reset');
		$("#later-message-send").hide();
	}
	function beforeSend()
	{
		if(checkExtention(input.val().split('\\').pop()) == -1)
		{
			alert("فایل با این پسوند پشتیبانی نمی شود.");
			return false;
		}
		if(checkSize(input.prop("files")[0]) > maxSize)
		{
			alert("حجم فایل بیشتر از ۷۵ مگابایت است.فعلا حداکثر حجم ۷۵ مگابایت است");
			return false;
		}
		$('#maininput').hide();
		$("#formDiv").show();
		$("#progressbox").show();
		$("#form-div-btns").show();
		$("#upload-file-name").text(input.val().split('\\').pop());

		//$("#info-upload-file-form").hide();
		//alert(UPLOAD_MENU_TEXT);
		$("#info-upload-file-form").html(UPLOAD_MENU_TEXT);
		$("#detail").html("");
		$("#detail").hide();
	}
	function afterDlLinkSend(data)
	{
		data = $('<div>').append(data);
		var stat = $(data).find('#status').html();
		if(stat == 'failed')
		{
			notify($(data).find('#message').html(),'error');
			return;
		}
		else if (stat !== 'success')
		{
			notify("اشکالی به وجود آمده است با مدیریت تماس بگیرید.",'error');
			return;
		}
		beforeSendLink();
		var hash = $(data).find('#hash').html();
		var fd = new FormData();
		var gid = hash.substring(0,hash.length/2);
		console.log("gid : "+gid);
		fd.append('gid', gid);

		var loop = setInterval(function(){
			$.ajax({
				       url: GETSTATUSLINK,
				       type: "POST",
				       data: fd,
				       processData: false,
				       contentType: false,
				       xhr: function() {
								 	var req = $.ajaxSettings.xhr();
									return req;
					    },
				       success: function(response)
							 {
								 var obj = JSON.parse(response);
								 progress = Math.round(obj.result.completedLength * 100 / obj.result.totalLength);
								 $('#link-progress-bar').asPieProgress('go',progress+'%');
								 console.log("PROGRESS : "+progress+"%");
								 console.log("DOWNLOADED : "+obj.result.completedLength);
								 console.log("TOTAL : "+obj.result.totalLength);
								 console.log("STATUS : "+obj.result.status);
								 console.log("\n\n");
								 if(obj.result.status == 'complete')
								 {
									$.playSound('/bell');
									 clearInterval(loop);
									 sendLinkDlDone = true;
								 		$("#sendhash").val(hash);
									 if(btnLaterSendClicked)
									 {
										 $('#sendlinkform').show();
									 }
								  $('#link-progress-bar div').hide();
									$("#dlLinkInput").val("");
						 			$('#link-progress-bar').append('<i class="fa fa-check check-icon" aria-hidden="true"></i>');
						 			$("#sendlink-file-name").html($("#sendlink-file-name").text()+'<br><br><div style="color:green">با موفقیت دانلود شد</div>');
						 			$("#sendlink-file-name").css("padding","15px 20px");
								 }
								 else if(obj.result.status == 'error')
								 {
									 clearInterval(loop);
									  $('#link-progress-bar div').hide();
							 			$('#link-progress-bar').append('<i class="fa fa-times close-icon" aria-hidden="true"></i>');
							 			$("#sendlink-file-name").html($("#sendlink-file-name").text()+'<br><div style="color:red">'+obj.result.message+'</div>');
							 			$("#sendlink-file-name").css("padding","15px 20px");
							 			$("#info-send-link-file-form").empty();
							 			$("#formDivSend").hide();
							 			$("#sendlinkform").show();
								 }
				       },
				       error: function(jqXHR, textStatus, errorMessage) {
					   		console.log(errorMessage);
				       }
				    });
		},2000);
	}
	function afterUpload(data)
	{
		$.playSound('/bell');
		$('#uploadBtn').val("");
		$('#prog-circle').hide();
		if(btnLaterClicked)
			$("#maininput").show();
		uploadDone = true;
		if (data)
		{
			data = $('<div>').append(data);
			status = $(data).find('#status').html();
		}
		if(status == "success")
		{
			$('#upload-progress-bar div').hide();
			$("#hash").val(($(data).find('#hash').text()));
			$('#upload-progress-bar').append('<i class="fa fa-check check-icon" aria-hidden="true"></i>');
			$("#upload-file-name").html($("#upload-file-name").text()+'<br><br><div style="color:green">با موفقیت آپلود شد</div>');
			$("#upload-file-name").css("padding","15px 20px");
		}
		else if(status == "failed")
		{
			$('#upload-progress-bar div').hide();
			$('#upload-progress-bar').append('<i class="fa fa-times close-icon" aria-hidden="true"></i>');
			$("#upload-file-name").html($("#upload-file-name").text()+'<br><div style="color:red">'+$(data).find('#message').text()+'</div>');
			$("#upload-file-name").css("padding","15px 20px");
			$("#info-upload-file-form").empty();
			$("#formDiv").hide();
			$("#maininput").show();
		}
	}
	function checkExtention(filename)
	{
		var extensions = new RegExp(allowedExtension + '$', 'i');
		var extension = filename.replace(/^.*\./, '');
		if (extension == filename) {
		    extension = '';
		} else {
		    extension = extension.toLowerCase();
		}

		var htmlcode = $($.parseHTML( UPLOAD_MENU_TEXT ));

		if(extension == 'mp3' || extension == 'ogg' || extension == 'aac' || extension == 'ac3' || extension == 'wav')
		{
			htmlcode.find("#img-upload-input").html('<label class="control-label">عکس سفارشی</label><input id="img-input-main" class="form-control input-sm img-input-main" name="upload_img" type="file"/><p class="well">در این قسمت عکس سفارشی مربوط به صوت مورد نظر خود را می توانید وارد کنید.</p> ');
			UPLOAD_MENU_TEXT = $('<div>').append(htmlcode.clone()).html();
		}
		else
		{
			htmlcode.find("#img-upload-input").html('');
			UPLOAD_MENU_TEXT = $('<div>').append(htmlcode.clone()).html();
		}
		if (extensions.test(extension)){
			return filename;
		} else {
			return -1;
		}
	}
	function checkSize(file)
	{
		var fileSize = 0;
		fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100);
		return fileSize;
	}

	 $("#btnShowForm").click(function(){
   	$("#info-upload-file-form").slideToggle("fast",function(){});
   });

	 $("#btnLaterSend").click(function(){
 		$("#formDivSend").css("clear","both");
 		$("#formDivSend").css("float","none");
 		$("#info-send-file-form").empty();
 		$("#form-div-btns-send").hide();
 		$("#later-message-send").show();
		$("#info-send-link-file-form").empty();

 		if(sendLinkDlDone)
			$("#sendlinkform").show();
		else
			btnLaterSendClicked = true;
    });
	$("#btnLater").click(function(){
		$("#formDiv").css("clear","both");
		$("#formDiv").css("float","none");
		$("#info-upload-file-form").empty();
		$("#form-div-btns").hide();
		$("#later-message").show();
		$("#info-upload-file-form").empty();
		btnLaterClicked = true;
		if(uploadDone)
			$("#maininput").show();
   });


 	$('#upload-progress-bar').asPieProgress({
      'namespace': 'pie_progress',
      'barcolor': '#88cc00',
			'trackcolor': '#d5ff80',
			'fillcolor': 'none'
  });
	$('#link-progress-bar').asPieProgress({
      'namespace': 'pie_progress',
      'barcolor': '#88cc00',
			'trackcolor': '#d5ff80',
			'fillcolor': 'none'
  });
});
function sendResetPage()
{
	$("#sendlinkform").show();
		$("#sendlinkprogressbox").hide();
		$("#info-send-file-form").empty();
		$('#link-progress-bar').find(".check-icon").remove();
		$('#link-progress-bar').find(".close-icon").remove();
		$("#formDivSend").hide();
		$("#formDivSend").css("clear","none");
		$("#formDivSend").css("float","left");
		$("#send-file-name").css("padding","30px 20px");
		//$('#prog-circle').show();
		$("#form-div-btns-send").hide();
		$("#later-message-send").hide();
		$('#link-progress-bar div').show();
		sendLinkDlDone = false;
		btnLaterSendClicked = false;
}
function resetPage()
{
		$("#maininput").show();
		$("#progressbox").hide();
		$("#info-upload-file-form").empty();
		$('#upload-progress-bar').find(".check-icon").remove();
		$('#upload-progress-bar').find(".close-icon").remove();
		$("#formDiv").hide();
		$("#formDiv").css("clear","none");
		$("#formDiv").css("float","left");
		$("#upload-file-name").css("padding","30px 20px");
		$('#prog-circle').show();
		$("#form-div-btns").hide();
		$("#later-message").hide();
		$('#upload-progress-bar div').show();
		uploadDone = false;
		btnLaterClicked = false;
}
function detail_submition(formId,type)
{
	var frm = document.getElementById(formId);
	var jfrm = $("#"+formId)
	btn = jfrm.find(".btnsub");
	var title = frm.title.value;
	var author = frm.author.value;
	var publisher = frm.publisher.value;
	var tag = frm.tag.value;
	var descrip = frm.des.value;
	var language = frm.language.value;
	var cat = frm.category.value;
	var hash = frm.hash.value;
	var fabranch = frm.fabranch.value;
	var comment = frm.comment.value;
	var ath = frm.ath.value;
	var top = frm.top_media.value;
	var valid = true;
	var msg = "لطفا مشکلات زیر را تصحیح فرمایید";
	if(title == '')
    	{
    		valid = false;
    		msg +='\n\n' + 'عنوان باید وارد شود';
    	}
    	if(tag == '')
    	{
    		valid = false;
    		msg +='\n\n' + 'تگ ها باید به صورت توضیح داده شده وارد شوند';
    	}
    	if(cat == '')
    	{
    		valid = false;
    		msg +='\n\n' + 'یک دسته باید برای فایل آپلود شده انتخاب شود.';
    	}
    	if(language == '')
    	{
    		valid = false;
    		msg +='\n\n' + 'زبان فایل آپلود شده باید انتخاب شود.';
    	}
    	if(hash == 'null')
    	{
    		valid = false;
    		msg +='\n\n' + 'تا تمام شدن آپلود صبور باشید';
    	}
    	if (!valid)
    	{
    		alert(msg);
    		return false;
    	}
	btn.disabled = true;
	var inputimg = jfrm.find(".img-input-main");
	var fd = new FormData();
	if(inputimg.val())
		fd.append('upload_img', inputimg.prop("files")[0]);
	if(top == 'set')
		fd.append('top', 'set');
	fd.append('title', title);
	fd.append('author', author);
	fd.append('publisher', publisher);
	fd.append('tag', tag);
	fd.append('des', descrip);
	fd.append('lang', language);
	fd.append('cat', cat);
	fd.append('hash', hash);
	fd.append('fabranch', fabranch);
	fd.append('comment', comment);
	fd.append('ath', ath);

	btn.val('در حال ارسال اطلاعات');
	$.ajax({
	    url: THIS_URL+"/profile/upload/file/detail",
	    type:"post",
	    data: fd,
	    cache:false,
        contentType: false,
        processData: false,
	}).done(function(data, statusText, xhr){
		setResultPage(data, statusText, xhr,type,btn);
	});
}
function setResultPage(data, statusText, xhr,type,btn)
{
	var detailId = "";
	if(type == "upload")
	{
		detailId = $("#detail");
	}
	else if(type == "link")
	{
		detailId = $("#linkdetail");
	}
	var status = xhr.status;
	console.log(data);
	if(status==200)
	{
		var data = $('<div/>').append(data);
		var stat = $(data).find('#status').html();
		console.log("stat" + stat);
		if(stat == 'failed')
		{
				detailId.css("color","red");
				detailId.html($(data).find('#message').text());
				if(type == "upload")
				{
					resetPage();
				}
				else if(type == "link")
				{
					sendResetPage();
				}
		}
		else if(stat == 'success')
		{

			detailId.css("color","green");
			detailId.html($(data).find('#message').text());
			if(type == "upload")
			{
				resetPage();
			}
			else if(type == "link")
			{
				sendResetPage();
			}
			btn.val('ارسال');
		}
	}
	else
	{
		detailId.html('<p>صفحه مورد نظر موجود نیست</p>');
	}
	detailId.show();
}
function getUploadMenu()
{
	var senddata = {"command":"uploadMenu"};
	sendCommand(senddata,"uploadMenu");
}
function doResponse(result,command)
{
	//alert(result);
	//console.log(result);
	UPLOAD_MENU_TEXT = result;
}
