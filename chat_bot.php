<?php
	include_once 'connection.php';
?>
<link rel="stylesheet" href="<?php echo base_url.'chat_bot/'; ?>css/style.css">
<link rel="stylesheet" href="<?php echo base_url.'chat_bot/'; ?>css/icons.css">
<link rel="stylesheet" href="<?php echo base_url.'chat_bot/'; ?>css/framework.css">
<!--<body>
<a class="chat-plus-btn" href="#sidebar-chat" uk-toggle> 
	<i class="fa fa-comments-o" style="font-size:26px;"></i>
</a>
	<div id="sidebar-chat" class="sidebar-chat px-3" uk-offcanvas="flip: true; overlay: true" style="max-width:230px !important;">
		<div class="uk-offcanvas-bar" style="max-width:330px !important;border-left:1px solid gray;">

			<div class="sidebar-chat-head mb-2">

				<div class="btn-actions">
					<a href="#" uk-tooltip="title: Search ;offset:7" uk-toggle="target: .sidebar-chat-search; animation: uk-animation-slide-top-small"> <i class="icon-feather-search"></i> </a>
					<a href="#" uk-tooltip="title: settings ;offset:7"> <i class="icon-feather-settings"></i> </a>
					<a href="#"> <i class="uil-ellipsis-v"></i> </a>
					<a href="#sidebar-chat" uk-toggle> <button class="btn btn-default" type="button"> <i class="fa fa-times"></i></button> </a>
				</div>

				<h2> Chats </h2>
			</div>

			<div class="sidebar-chat-search" hidden>
				<input type="text" class="uk-input" placeholder="Search in Messages">
				<span class="btn-close" uk-toggle="target: .sidebar-chat-search; animation: uk-animation-slide-top-small"> <i class="icon-feather-x"></i> </span>
			</div>

			<ul class="uk-child-width-expand sidebar-chat-tabs" uk-tab>
				<li class="uk-active"><a href="javascript:void(0);" style="text-align:left;">Connections</a></li>
			</ul>
			<div class="row">
				<div class="col-md-12" id="chat_contact_list">
					<?php
						include_once 'class.chat.php';
						$chat=new Chat();
						$chat_contact_list=$chat->chatContactList();
						if($chat_contact_list)
						{
							echo $chat_contact_list;
						}
					?>
				</div>
			</div>
		</div>
	</div>
</body>-->
<script src="<?php echo base_url.'chat_bot/'; ?>js/framework.js"></script>
<script src="<?php echo base_url.'chat_bot/'; ?>js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url.'chat_bot/'; ?>js/simplebar.js"></script>
<script src="<?php echo base_url.'chat_bot/'; ?>js/main.js"></script>
<script>
	var base_url="<?php echo base_url; ?>";
	var sender__user_image="<?php getUserProfileImage($_COOKIE['uid']); ?>";
	var s__uid="<?php echo $_COOKIE['uid']; ?>";
	var interval_bot_chat=setInterval(function(){ },30000);
	function deleteThread(thread_id="")
	{
		if(thread_id!="")
		{
			$.ajax({
				url:base_url+'delete-thread',
				type:'post',
				data:{thread_id:thread_id},
				success:function(response)
				{
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						$(".thread_"+thread_id).remove();
					}
				}
			});
		}
	}
	function openChatWindow(friend_id="")
	{
		$("#chat_text_editor").data('friend',friend_id);
		clearInterval(interval_bot_chat);
		if(friend_id!="")
		{
			jsonStr=false;
			var id_exists=''+friend_id in dataJson;
			if(id_exists)
			{
				var blocked=$("#friend_open_"+friend_id).data("blocked");
				$("#chat_text_editor").val();
				$("#chat_text_editor").attr("title","");
				$("#chat_text_editor").attr("disabled",false);
				if(blocked=="blocked")
				{
					$("#chat_text_editor").attr("disabled",true);
					$("#chat_text_editor").attr("title","Due to blocking you are not allowed to send messages to this user for now.");
				}
				jsonStr=dataJson[friend_id];
				//console.log(jsonStr);
				$(".__user_profile_url__").attr("href",jsonStr['chat_window_user_url']);
				$(".__user_profile_url__").attr("title",jsonStr['chat_window_user_name']);
				$(".__USER_NAME__").text(jsonStr['chat_window_user_name']);
				$(".__USER_IMAGE__").attr("src",jsonStr['chat_window_image']);
				$(".__USER_IMAGE__").attr("alt",jsonStr['chat_window_image_alt']);
				$("#online_status_window").text(jsonStr['chat_window_user_online']);
				$("#online_status_window").removeClass("online");
				$("#online_status_window").removeClass("offline");
				$("#online_status_window").addClass(jsonStr['chat_window_user_online']);
				var window_user_id=jsonStr['window_user_id'];
				var chat_window_user_online=jsonStr['chat_window_user_online'];
				$("#chatbotbutton").data("msnm",jsonStr['chat_window_user_name']);
				$("#chatbotbutton").data("suimg",sender__user_image);
				$("#chatbotbutton").data("ruid",friend_id);
				$("#chatbotbutton").data("suid",s__uid);
				$('#myForm').show();
				updateBotChatContent(window_user_id);
				interval_bot_chat=setInterval(function(){
					updateBotChatContent(window_user_id);
				},30000);
				//$("#chat_window_message_content").html(jsonStr['chat_window_user_name']);
			}
		}
	}
	function updateBotChatContent(window_user_id=""){
		if(window_user_id!="")
		{
			$.ajax({
				url:base_url+"chatajax",
				type:"post",
				data:{action:"chat_bot_window_content",__acrf:window_user_id},
				success:function(response){
					//console.log(response);
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						if($("#chat_window_message_content").html()!=parsedJson.response_html)
						{
							$("#chat_window_message_content").html(parsedJson.response_html);
						}
						manageSendMessageInput(window_user_id,parsedJson.blocked)
					}
				}
			});
		}
	}
	var si=setInterval(function(){ fetch_user_contact_lists(); }, 60000);
	function fetch_user_contact_lists() 
	{
	  $.ajax({
		  url:base_url+'chatajax',
		  type:'post',
		  data:{action:'chat_bot_list'},
		  success:function(response)
		  {
			  var parsedJson=JSON.parse(response);
			  if(parsedJson.status=="success")
			  {
				  $("#chat_contact_list").html(parsedJson.data);
			  }
		  }
	  });
	}
	fetch_user_contact_lists();
</script>	