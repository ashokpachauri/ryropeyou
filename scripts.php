<?php 
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		include_once 'chat_window.php';
		include_once 'chat_bot.php';
		include_once 'change_visibility_plugin.php';
		include_once 'connect-user-js.php';
	}
?>
<script src="<?php echo base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>vendor/slick/slick.min.js"></script>
<script src="<?php echo base_url; ?>js/osahan.js"></script>
<script src="https://kit.fontawesome.com/4c37ac5d89.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url; ?>/js/sweetalert.min.js"></script>
<script src="<?php echo base_url; ?>jquery.sticky-kit.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script async src="https://guteurls.de/guteurls.js" selector=".url_meta"> </script> 
<script>
	$(window).on('load', function() {
	   $("#cover_loader").hide();
	});
	var base_url="<?php echo base_url; ?>";
	localStorage.setItem("base_url",base_url);
	function redirectToURICustom(username="",module="")
	{
		if(username!="" && module!="")
		{
			window.location.href=base_url+'u/'+username+'/'+module;
		}
		else{
			window.location.href=base_url;
		}
	}
	function playSound(filename="sounds/bing"){
		var mp3Source = '<source src="' +base_url+ filename + '.mp3" type="audio/mpeg">';
		var oggSource = '<source src="' + base_url+filename + '.ogg" type="audio/ogg">';
		var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' +base_url+ filename +'.mp3">';
		document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
	}
	function playNotificationSound(filename="sounds/bing1"){
		var mp3Source = '<source src="' +base_url+ filename + '.mp3" type="audio/mpeg">';
		var oggSource = '<source src="' + base_url+filename + '.ogg" type="audio/ogg">';
		var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' +base_url+ filename +'.mp3">';
		document.getElementById("sound1").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
	}
	function updateCheckout()
	{
		$.ajax({
			type:'POST',
			url: base_url+"update-notification-checkout",
			data:{endpoint:"unread"},
			success:function(data){
				var parsedJson=JSON.parse(data);
				if(parsedJson.status=="success")
				{
					$("#notifications_counter").html(0);
				}
			}
		});
	}
	var ajax_call = function() {
		$.ajax({
			type:'POST',
			url: base_url+"get-user-notifications",
			data:{endpoint:"unread"},
			success:function(data){
				var parsedJson=JSON.parse(data);
				if(parsedJson.status=="success")
				{
					if(parseInt(parsedJson.sound_to_play)==1)
					{
						playNotificationSound();
					}
					$("#unread_notifications_count").val(parsedJson.count);
					$("#new_notifications_data").html(parsedJson.data);
					$("#notifications_counter").html(parseInt(parsedJson.count));
				}
				else
				{
					//$("#notifications_counter").html(parseInt(parsedJson.count));
					//$("#new_notifications_data").html('<a class="dropdown-item text-center small text-gray-500" href="javascript:void(0)">Unable to fetch notifications</a>');
				}
			}
		});
		$.ajax({
			type:'POST',
			url: base_url+"messenger-api",
			data:{endpoint:"all_unread_messages"},
			success:function(data){
				var parsedJson=JSON.parse(data);
				if(parsedJson.status=="success")
				{
					//console.log(parsedJson);
					if(parseInt(parsedJson.sound_to_play)==1)
					{
						playSound();
					}
					$("#unread_messages_count").val(parsedJson.count);
					if(parseInt(parsedJson.count)>0)
					{
						var messages_data=parsedJson.data;
						var messages_html='';
						//console.log(messages_data);
						for(loopVar=0;loopVar<parseInt(parsedJson.count);loopVar++)
						{
							var messages_arr=messages_data[loopVar];
							//console.log(messages_arr);
							messages_html=messages_html+'<a class="dropdown-item d-flex align-items-center" href="'+base_url+'messenger.php?thread='+messages_arr.s_user_id_hash+'" title="'+messages_arr.s_name+'">'+
							'<div class="dropdown-list-image mr-3">'+
							'<img class="rounded-circle" src="'+messages_arr.s_user_image+'" alt="'+messages_arr.s_name+'" - "'+messages_arr.s_user_profile_title+'" style="border:1px solid #eaebec !important;">'+
							'<div class="status-indicator ';
							if(messages_arr.is_online=="1")
							{
								messages_html+='bg-success"></div>';
							}	
							else{
								messages_html+='bg-danger"></div>';
							}
							messages_html+='</div>'+
							'<div class="font-weight-bold overflow-hidden">'+
							'<div class="text-truncate">'+messages_arr.text_message+'</div>'+
							'<div class="small text-gray-500">'+messages_arr.s_name+' · '+messages_arr.datetime+'</div>'+
							'</div>'+
							'</a>';
							//console.log(messages_html);
						}
						$("#new_messages_data").html(messages_html);
					}
					else
					{
						$("#new_messages_data").html('<a class="dropdown-item text-center small text-gray-500" href="javascript:void(0)">No more new messages</a>');
					}
					$("#messages_counter").html(parseInt(parsedJson.count));
				}
				else
				{
					$("#messages_counter").html(parseInt(parsedJson.count));
					$("#new_messages_data").html('<a class="dropdown-item text-center small text-gray-500" href="javascript:void(0)">Unable to fetch new messages</a>');
				}
			}
		});
	};
	var interval = 1000 * 60 * 0.3;
	ajax_call();
	setInterval(ajax_call, interval);
</script>