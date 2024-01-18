<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 300px;
  width: 280px;
}

/* The popup chat - hidden by default */
.chat-popup {
  display: none;
  max-height:410px;
  min-width:300px;
  position: fixed; /*absolute*/
  bottom: 0;
  right: 350px;
  border: 1px solid #3e416d;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width textarea */
.form-container input {
  width: 100%;
  padding: 15px;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 30px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/send button */


/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
.online{
	color:green;
}
.offline{
	color:red;
}

.chat-bot-dropbtn {
  padding: 16px;
  font-size: 10px;
  border: none;
}

.chat-bot-dropdown {
  position: relative;
  display: inline-block;
}

.chat-bot-dropdown-content {
  display: none;
  background-color:#ddd;
  position: absolute;
  min-width: 50px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  right:10px;
}
.chat-bot-dropdown-content-left {
  display: none;
  background-color:#ddd;
  position: absolute;
  min-width:50px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  left:10px;
}
.chat-bot-dropdown-content a {
  color: black;
  padding: 5px 5px;
  text-decoration: none;
  display: block;
}
.chat-bot-dropdown-content-left a {
  color: black;
  padding: 5px 5px;
  text-decoration: none;
  display: block;
}
.chat-bot-dropdown-content a:hover {background-color: #ddd;}
.chat-bot-dropdown-content-left a:hover {background-color: #ddd;}

.chat-bot-dropdown:hover .chat-bot-dropdown-content  {display: block;}
.chat-bot-dropdown:hover .chat-bot-dropdown-content-left  {display: block;}
</style>
<div class="chat-popup" id="myForm">
	<div class="form-container">
		<div class="row">
			<div class="col-md-2">
				<a href="" class="__user_profile_url__" title=""><img src="" class="__USER_IMAGE__ rounded-circle" alt=""  style="height:25px;width:25px;"></a>
			</div>
			<div class="col-md-10">
				<h6 style="font-size:12px;margin:0px;"><a href="" class="__user_profile_url__"><span class="__USER_NAME__"></span></a> 
				<button type="button" class="btn btn-default pull-right" onclick="$('#myForm').hide()" style="float:right;"><i class="fa fa-times"></i></button>
				<button type="button" class="btn btn-default pull-right" onclick="$('#minimize_chat_window').toggle('display');" style="float:right;margin-right:10px;"><i class="fa fa-minus text-danger"></i></button></h6>
				<span class="online" id="online_status_window">online</span>
			</div>
		</div>
		<div class="row" id="minimize_chat_window">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12" style="min-height:275px;max-height:276px;border-top:1px solid gray;border-bottom:1px solid gray;margin-bottom:20px;overflow-y:auto;" id="chat_window_message_content">
						<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
							<div class="mr-auto ml-auto">
								<?php
									echo loading();
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-12" id="chatbotbutton" data-msnm="" data-ruid="" data-suid="" data-suimg="">
								<input type="text" data-friend="" class="form-control chat_text_editor" id="chat_text_editor" placeholder="Type message.." name="msg" required style="min-height:45px !important;margin-bottom:10px;margin-top:-15px;">
							</div>
							<div class="col-md-12" id="hoverable_menu_anchor_list" style="min-height:45px !important;position:absolute;display:none;background-color:#ddd;margin-bottom:10px;margin-top:-15px;text-align:center;padding:10px;">
								<button type="button" onclick="openFileChooser('chatbot');" class="btn btn-light btn-sm rounded message_box">
									<i class="feather-image"></i>
								</button>
								<button type="button" onclick="openAttachmentChooser('chatbot');" class="btn btn-light btn-sm rounded message_box">
									<i class="feather-paperclip"></i>
								</button>
								<button type="button" onclick="openCamRecorder('chatbot');" class="btn btn-light btn-sm rounded message_box">
									<i class="feather-camera"></i>
								</button>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<a href="javascript:void(0);" id="hoverable_menu_anchor" onclick='$("#hoverable_menu_anchor_list").toggle("display");'><i class="fa fa-ellipsis-v"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade report_message_modal" id="report_message_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="amazing_report_message_modalBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="amazing_report_message_modalBackdrop">Report Message</h6>
			</div>
			<div class="modal-body" style="padding:0px;margin:0px;">											
				<div class="row" style="padding:0px;margin:0px;">
					<div class="col-md-12" style="box-shadow: 0 0 0 1px rgba(0,0,0,.15), 0 2px 3px rgba(0,0,0,.2);transition: box-shadow 83ms;background:#fff;padding:10px;margin: 0px;border-radius:2px;font-size:15px;line-height:30px;">
						<input type="hidden" required name="reporting_message_id" id="reporting_message_id" value="">
						<input type="radio" required name="message_issue_type" value="fake">&nbsp;&nbsp;I think this is fake.<br/>
						<input type="radio" required name="message_issue_type" value="spam">&nbsp;&nbsp;I think this is spam.<br/>
						<input type="radio" required name="message_issue_type" value="scam">&nbsp;&nbsp;I think this is malware, scam or phishing.<br/>
						<input type="radio" required name="message_issue_type" value="misinformation">&nbsp;&nbsp;I think this is false information.<br/>
						<input type="radio" required name="message_issue_type" value="offensive">&nbsp;&nbsp;I think topic or language is offensive.<br/>
						<input type="radio" required name="message_issue_type" value="nudity">&nbsp;&nbsp;Nudity, sexual scenes or language, prostitution or sex trafficking.<br/>
						<input type="radio" required name="message_issue_type" value="violence">&nbsp;&nbsp;Torture, rape or abuse, terrorist act or recruitment for terrorism.<br/>
						<input type="radio" required name="message_issue_type" value="threat">&nbsp;&nbsp;Personal attack or threatening language.<br/>
						<input type="radio" required name="message_issue_type" value="hatespeech">&nbsp;&nbsp;Racit, sexist, hateful language.<br/>
						<input type="radio" required name="message_issue_type" value="copyright">&nbsp;&nbsp;Defamation, Trademark or copyright violation.<br>
						<div class="row" style="padding:0px;margin:0px;">
							<div class="col-md-12"  style="padding:0px;margin:0px;padding-top:20px;">
								<button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
								<button type="button" onclick="saveMessageReport();" class="btn btn-success pull-right">Submit</button>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade report_user_modal" id="report_user_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="amazing_report_user_modalBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="amazing_report_user_modalBackdrop">Report & Block User</h6>
			</div>
			<div class="modal-body" style="padding:0px;margin:0px;">											
				<div class="row" style="padding:0px;margin:0px;">
					<div class="col-md-12" style="box-shadow: 0 0 0 1px rgba(0,0,0,.15), 0 2px 3px rgba(0,0,0,.2);transition: box-shadow 83ms;background:#fff;padding:10px;margin: 0px;border-radius:2px;font-size:15px;line-height:30px;">
						<input type="hidden" required name="reporting_user_id" id="reporting_user_id" value="">
						<div class="row">
							<div class="col-md-12" style="padding:10px;">
								<div class="row">
									<div class="col-md-3">
										<h6>Action:</h6>
									</div>
									<div class="col-md-4">
										<select id="report_type" name="report_type" class="form-control">
											<option value="report" selected>Report</option>
											<option value="block">Block</option>
											<option value="report,block">Report & Block</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-12" style="padding:10px;">
								<p id="error_user_issue_type" style="display:none;color:red;">Please Select a Reason to Report</p>
								<input type="radio" required name="user_issue_type" value="fake">&nbsp;&nbsp;I think this is fake.<br/>
								<input type="radio" required name="user_issue_type" value="spam">&nbsp;&nbsp;I think this is spam.<br/>
								<input type="radio" required name="user_issue_type" value="scam">&nbsp;&nbsp;I think this is malware, scam or phishing.<br/>
								<input type="radio" required name="user_issue_type" value="misinformation">&nbsp;&nbsp;I think this is false information.<br/>
								<input type="radio" required name="user_issue_type" value="offensive">&nbsp;&nbsp;I think topic or language is offensive.<br/>
								<input type="radio" required name="user_issue_type" value="nudity">&nbsp;&nbsp;Nudity, sexual scenes or language, prostitution or sex trafficking.<br/>
								<input type="radio" required name="user_issue_type" value="violence">&nbsp;&nbsp;Torture, rape or abuse, terrorist act or recruitment for terrorism.<br/>
								<input type="radio" required name="user_issue_type" value="threat">&nbsp;&nbsp;Personal attack or threatening language.<br/>
								<input type="radio" required name="user_issue_type" value="hatespeech">&nbsp;&nbsp;Racit, sexist, hateful language.<br/>
								<input type="radio" required name="user_issue_type" value="copyright">&nbsp;&nbsp;Defamation, Trademark or copyright violation.<br>
							</div>
						</div>
						<div class="row" style="padding:0px;margin:0px;">
							<div class="col-md-12"  style="padding:0px;margin:0px;padding-top:20px;">
								<button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
								<button type="button" onclick="saveUserReport();" class="btn btn-success pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade openCamRecorder" id="openCamRecorder" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Capture & Send</h5>
			</div>
			<div class="modal-body">
				<div class="p-2 d-flex">
					<div id="my_camera"></div>
					<br/>
					<div id="results"></div>												
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" onClick="configure();">Reload</button>
				<button type="button" class="btn btn-primary" onClick="take_snapshot();">Capture</button>
				<button type="button" class="btn btn-success" id="saveSnap" data-mode="messenger" onClick="saveSnap();">Send</button>
				<button type="button" class="btn btn-secondary" onClick="closeModal();">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade openFileChooser" id="openFileChooser" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Upload Image And Send</h5>
			</div>
			<div class="modal-body">
				<div class="p-2 d-flex">
					<form id="image_input_form" method="post">
						<input type='file'	name="image_input" id="image_input" accept="image/*">	
					</form>
				</div>
				<div class="p-2 d-flex" id="image_result">
														
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="changeImage();">Change Image</button>
				<button type="button" class="btn btn-success" id="sendImage" data-mode="messenger" onClick="sendImage();">Send</button>
				<button type="button" class="btn btn-secondary" onclick="closeModalUpload();">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade openAttachmentChooser" id="openAttachmentChooser" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="openAttachmentChooserLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="openAttachmentChooserLabel">Attach files to messages</h5>
			</div>
			<div class="modal-body">
				<div class="p-2 row">
					<form id="attachment_input_form" method="post">
						<input type='file'	name="attachment_input[]" multiple="multiple" id="attachment_input" accept="image/*,video/*,audio/*,application/pdf,application/vnd.ms-excel">	
					</form>
				</div>
				<div class="p-2 row" id="attachment_result">
														
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onClick="clearAttachments();">Clear</button>
				<button type="button" class="btn btn-success" id="sendFileAttachment" data-mode="messenger" onClick="sendFileAttachment();">Send</button>
				<button type="button" class="btn btn-secondary" onClick="clearAttachments();closeModalAttachment();">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	var base_url="<?php echo base_url; ?>";
	var last_message_id="<?php echo $last_message_id ?>";
	localStorage.setItem("last_message_id",last_message_id);
	localStorage.setItem("base_url",base_url);
	$("#chat_text_editor").on("keyup",function(e){
		if(e.keyCode === 13){
            var text=$("#chat_text_editor").val();
			if(text!="")
			{
				var ruid=$("#chatbotbutton").data("ruid");
				var suid=$("#chatbotbutton").data("suid");
				var messageSenderImage = $("#chatbotbutton").data("suimg").trim();
				$.ajax({
					url:base_url+'send-message',
					type:"post",
					data:{img_mesg:"",r_user_id:ruid,s_user_id:suid,s_user_img:messageSenderImage,page_refer:"chatbot",text_message:text},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							$("#chat_text_editor").val("")
							updateBotChatContent(ruid);
						}
						else
						{
							alert(parsedJson.message);
						}
					}
				});
			}
        }
	});
	function fixChatScrollBarToBottom(){
		var msgPane = document.getElementById("chat_history");
		msgPane.scrollTop = msgPane.scrollHeight;
	}
	function updateUser(user,chat_list_dynamic="chat_list_dynamic",whole_div="whole_div")
	{
		if(user!="")
		{
			localStorage.setItem('r_user_id',user);
			$('#'+chat_list_dynamic).load(base_url+"test-chat-list?user_id="+user);
			$('#'+whole_div).load(base_url+"test-chat?user_id="+user);
			setTimeout(function(){ fixChatScrollBarToBottom(); },1000);
		}
		
	}
	function readURLFromFile(input,image_result="image_result",image_to_send="image_to_send") {
	  if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function(e) {
		  $('#'+image_result).html("<image id='"+image_to_send+"' src='"+e.target.result+"' style='width:100%;border-radius:10px;'>");
		}
		reader.readAsDataURL(input.files[0]); 
	  }
	}
	function changeImage(form="image_input_form",image_result="image_result",image_input="image_input")
	{
		var mode=$("#sendImage").data("mode");
		if(mode=="messenger")
		{
			$("#"+form)[0].reset();
			$('#'+image_result).html("");
			$("#"+image_input).click();
		}
	}
	function sendImage()
	{
		var mode=$("#sendImage").data("mode");
		if(mode=="messenger")
		{
			var base64image=$("#image_to_send").attr("src");
			$("#image_input_form")[0].reset();
			$('#image_result').html("");
			$("#openFileChooser").modal("hide");
			var messageSenderName = $("#message_box_button").attr("data-msnm").trim();
			var messageSenderImage = $("#message_box_button").attr("data-suimg").trim();
			var r_user_id=$("#message_box_button").attr("data-ruid").trim();
			var s_user_id=$("#message_box_button").attr("data-suid").trim();
			if(base64image!="" && base64image!=null && base64image!="undefined")
			{
				var template = Handlebars.compile( $("#image-message-template").html());
				var context = { 
				  messageOutput: base64image,
				  time: getTime(),
				  messageSenderImage:messageSenderImage,
				  r_user_id:r_user_id,
				  s_user_id:s_user_id,
				  messageSenderName:messageSenderName,
				  messageStatus:'Sent'
				};
				var message_transaction_data='<i class="feather-check text-primary"></i><img src="'+base64image+'" width="20" height="20"> &nbsp;Photo';
				$("#chat_history").append(template(context));
				$("#message_transaction_data_"+r_user_id).html(message_transaction_data);
				$.ajax({
					type:'POST',
					url: base_url+"send-message",
					data:{img_mesg:1,r_user_id:r_user_id,s_user_id:s_user_id,s_user_img:messageSenderImage,page_refer:"messenger",text_message:base64image},
					success:function(data){
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							
						}
					}
				});
			}
		}
		else if(mode=="chatbot")
		{
			var base64image=$("#image_to_send").attr("src");
			$("#image_input_form")[0].reset();
			$('#image_result').html("");
			$("#openFileChooser").modal("hide");
			var messageSenderName = $("#chatbotbutton").data("msnm").trim();
			var messageSenderImage = $("#chatbotbutton").data("suimg").trim();
			var r_user_id=$("#chatbotbutton").data("ruid").trim();
			var s_user_id=$("#chatbotbutton").data("suid").trim();
			if(base64image!="" && base64image!=null && base64image!="undefined")
			{
				$.ajax({
					type:'POST',
					url: base_url+"send-message",
					data:{img_mesg:1,r_user_id:r_user_id,s_user_id:s_user_id,s_user_img:messageSenderImage,page_refer:"chatbot",text_message:base64image},
					success:function(data){
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							$("#hoverable_menu_anchor_list").hide();
							updateBotChatContent(r_user_id);
						}
					}
				});
			}
		}
	}
	$("#image_input").change(function() {
	  readURLFromFile(this);
	});
	function openAttachmentChooser(mode="messenger")
	{
		$("#sendFileAttachment").data("mode",mode);
		$("#openAttachmentChooser").modal("show");
		$("#attachment_input").click();
	}
	function clearAttachments()
	{
		$('#attachment_result').html("");
		$("#attachment_input_form")[0].reset();
	}
	function closeModalAttachment()
	{
		clearAttachments();
		$("#openAttachmentChooser").modal("hide");
	}
	function openFileChooser(mode="messenger")
	{
		$("#sendImage").data("mode",mode);
		$("#openFileChooser").modal("show");
		$("#image_input").click();
	}
	function closeModalUpload()
	{
		$('#image_result').html("");
		$("#image_input_form")[0].reset();
		$("#openFileChooser").modal("hide");
	}
	/*-------------- Capture ----------------------*/
	function openCamRecorder(mode="messenger")
	{
		$("#saveSnap").data("mode",mode);
		$("#openCamRecorder").modal("show");
		configure(mode);
	}
	function configure(mode){
		$('#my_camera').show();
		Webcam.set({
			width: 448,
			height: 322,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach('#my_camera');
		$("#results").hide();
	}
	function closeModal()
	{
		Webcam.reset();
		$("#openCamRecorder").modal("hide");
	}
	
	var shutter = new Audio();
	shutter.autoplay = false;
	shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';

	function take_snapshot() {
		// play sound effect
		shutter.play();

		// take snapshot and get image data
		Webcam.snap( function(data_uri) {
			$("#results").show();
			document.getElementById('results').innerHTML = '<img id="imageprev" src="'+data_uri+'" style="width:100%;"/>';
		});

		Webcam.reset();
		$('#my_camera').hide();
	}
	function getTime() {
		var date=new Date();
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var ampm = hours >= 12 ? 'PM' : 'AM';
		hours = hours % 12;
		hours = hours ? hours : 12; // the hour '0' should be '12'
		minutes = minutes < 10 ? '0'+minutes : minutes;
		var strTime = hours + ':' + minutes + ' ' + ampm;
		return strTime;
	}
	function saveSnap(){
		var mode=$("#saveSnap").data("mode");
		if(mode=="messenger")
		{
			var base64image =  document.getElementById("imageprev").src;
			var messageSenderName = $("#message_box_button").attr("data-msnm").trim();
			var messageSenderImage = $("#message_box_button").attr("data-suimg").trim();
			var r_user_id=$("#message_box_button").attr("data-ruid").trim();
			var s_user_id=$("#message_box_button").attr("data-suid").trim();
			$("#results").hide();
			if(base64image!="" && base64image!=null && base64image!="undefined")
			{
				var template = Handlebars.compile( $("#image-message-template").html());
				var context = { 
				  messageOutput: base64image,
				  time: getTime(),
				  messageSenderImage:messageSenderImage,
				  r_user_id:r_user_id,
				  s_user_id:s_user_id,
				  messageSenderName:messageSenderName,
				  messageStatus:'Sent'
				};
				var message_transaction_data='<i class="feather-check text-primary"></i><img src="'+base64image+'" width="20" height="20"> &nbsp;Photo';
				$("#chat_history").append(template(context));
				$("#message_transaction_data_"+r_user_id).html(message_transaction_data);
				document.getElementById('results').innerHTML = '';
				Webcam.reset();
				$("#openCamRecorder").modal("hide");
				$.ajax({
					type:'POST',
					url: base_url+"send-message",
					data:{img_mesg:1,r_user_id:r_user_id,s_user_id:s_user_id,s_user_img:messageSenderImage,page_refer:"messenger",text_message:base64image},
					success:function(data){
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							
						}
					}
				});
			}
		}
		else if(mode=="chatbot")
		{
			var base64image =  document.getElementById("imageprev").src;
			var messageSenderName = $("#chatbotbutton").data("msnm").trim();
			var messageSenderImage = $("#chatbotbutton").data("suimg").trim();
			var r_user_id=$("#chatbotbutton").data("ruid").trim();
			var s_user_id=$("#chatbotbutton").data("suid").trim();
			$("#results").hide();
			if(base64image!="" && base64image!=null && base64image!="undefined")
			{
				
				document.getElementById('results').innerHTML = '';
				Webcam.reset();
				$("#openCamRecorder").modal("hide");
				$.ajax({
					type:'POST',
					url: base_url+"send-message",
					data:{img_mesg:1,r_user_id:r_user_id,s_user_id:s_user_id,s_user_img:messageSenderImage,page_refer:"chatbot",text_message:base64image},
					success:function(data){
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							$("#hoverable_menu_anchor_list").hide();
							updateBotChatContent(r_user_id);
						}
					}
				});
			}
		}
	}
	
	/*--------------------------------------------------------*/
	$("#report_type").change(function(){
		var report_type=$("#report_type").val();
		if(report_type=="report" || report_type=="report,block")
		{
			$("input[name='user_issue_type']").attr("required",true);
		}
		else{
			$("input[name='user_issue_type']").attr("required",false);
		}
	});
	
	function confirmDialogue(confirm_message)
	{
		return confirm(confirm_message);
	}
	function alertDialogue(message)
	{
		return alert(message);
	}
	function ReportAndBlockUser(window_user_id,window_user,window_user_image)
	{
		$("#reporting_user_id").val(window_user_id);
		$("#report_user_modal").modal("show");
	}
	function DeleteWholeThread(window_user_id)
	{
		var confirm_message="All the messages and data will be permanently deleted! do you want to continue?";
		if(confirmDialogue(confirm_message))
		{
			$.ajax({
				url:base_url+'delete-bulk-messages',
				type:'post',
				data:{window_user_id:window_user_id},
				success:function(response)
				{
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						$(".user_thread_"+window_user_id).remove();
					}
					else
					{
						alertDialogue(parsedJson.message);
					}
				}
			});
		}
		else
		{
			return;
		}
	}
	function reportThread(thread_id)
	{
		$("#reporting_message_id").val(thread_id);
		$("#report_message_modal").modal("show");
	}
	function saveMessageReport(message_id="",issue_type="")
	{
		if(message_id=="" && issue_type=="")
		{
			message_id=$("#reporting_message_id").val();
			if (!$("input[name='message_issue_type']:checked").val()) {
			  return false;
			}
			else
			{
				var issue_type=$("input[name='message_issue_type']:checked").val();
				$.ajax({
					url:base_url+'report-a-message',
					type:'post',
					data:{issue_type:issue_type,message_id:message_id},
					success:function(response){
						var parsedJson=JSON.parse(response)
						if(parsedJson.status=="success")
						{
							$(".thread_"+message_id).remove();
						}
						else{
							alertDialogue(parsedJson.message);
						}
					}
				});
			}
		}
		else
		{
			$.ajax({
				url:base_url+'report-a-message',
				type:'post',
				data:{issue_type:issue_type,message_id:message_id},
				success:function(response){
					var parsedJson=JSON.parse(response)
					if(parsedJson.status=="success")
					{
						$(".thread_"+message_id).remove();
					}
					else{
						alertDialogue(parsedJson.message);
					}
				}
			});
		}
	}
	function saveUserReport(window_user_id="",issue_type="",report_type="")
	{
		$("#error_user_issue_type").hide();
		if(window_user_id=="" && issue_type=="" && report_type=="")
		{
			var report_type=$("#report_type").val();
			window_user_id=$("#reporting_user_id").val();
			if (!$("input[name='user_issue_type']:checked").val() && report_type!="block") {
			  $("#error_user_issue_type").show();
			  return false;
			}
			else
			{
				$("#error_user_issue_type").hide();
				var issue_type=$("input[name='user_issue_type']:checked").val();
				$.ajax({
					url:base_url+'user-report-block',
					type:'post',
					data:{issue_type:issue_type,window_user_id:window_user_id,report_type:report_type},
					success:function(response){
						var parsedJson=JSON.parse(response)
						if(parsedJson.status=="success")
						{
							manageSendMessageInput(window_user_id,parsedJson.blocked);
							$("#report_user_modal").modal("hide");
						}
						else
						{
							alertDialogue(parsedJson.message);
						}
					}
				});
			}
		}
		else
		{
			$.ajax({
				url:base_url+'user-report-block',
				type:'post',
				data:{issue_type:issue_type,window_user_id:window_user_id,report_type:report_type},
				success:function(response){
					var parsedJson=JSON.parse(response)
					if(parsedJson.status=="success")
					{
						manageSendMessageInput(window_user_id,parsedJson.blocked);
						$("#report_user_modal").modal("hide");
					}
					else
					{
						alertDialogue(parsedJson.message);
					}
				}
			});
		}
	}
	
	function manageSendMessageInput(window_user_id,blocked)
	{
		if($("#chat_text_editor").length>0)
		{
			if($("#chat_text_editor").data('friend')==window_user_id && blocked=="blocked")
			{
				$("#chat_text_editor").val();
				$(".chat_text_editor").attr("disabled",false);
				$(".chat_text_editor").attr("disabled",true);
				$(".chat_text_editor").attr("title","Due to blocking you are not allowed to send messages to this user for now.");
				
			}
			else if($("#chat_text_editor").data('friend')==window_user_id && blocked=="unblocked")
			{
				$("#chat_text_editor").val();
				$(".chat_text_editor").attr("disabled",false);
				$(".chat_text_editor").attr("title","");
				
			}
		}
		if($("#message_box").length>0){
			if($("#message_box").data('friend')==window_user_id && blocked=="blocked")
			{
				$("#message_box").val();
				$(".message_box").attr("disabled",false);
				$(".message_box").attr("disabled",true);
				$(".message_box").attr("title","Due to blocking you are not allowed to send messages to this user for now.");
				
			}
			else if($("#message_box").data('friend')==window_user_id && blocked=="unblocked")
			{
				$("#message_box").val();
				$(".message_box").attr("disabled",false);
				$(".message_box").attr("title","");
			}
			$('#whole_div').load(base_url+"test-chat?user_id="+window_user_id);
		}
	}
</script>	
				   