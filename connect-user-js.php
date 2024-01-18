<script>
	var base_url="<?php echo base_url; ?>";
	localStorage.setItem("base_url",base_url);
	function ConnectUser(connection_user_id,action_str="connect")
	{
		if(connection_user_id!='')
		{
			if(action_str=="")
			{
				return alert("Invalid action.");
			}
			$.ajax({
				url:base_url+'connection-action',
				type:"post",
				data:{connection_user_id:connection_user_id,method:action_str},
				success:function(response)
				{
					var parsed_json=JSON.parse(response);
					if(parsed_json.status=="success")
					{
						var textStr = parsed_json.function_param;
						textStr = textStr.toLowerCase().replace(/\b[a-z]/g, function(letter) {
							return letter.toUpperCase();
						});
						var class_param=parsed_json.class_param;
						if(class_param=="connect")
						{
							var on_click_function_call_connect="ConnectUser('"+connection_user_id+"','"+parsed_json.function_param+"');";
							var buttonHtml='<button type="button" onclick="'+on_click_function_call_connect+'" class="btn btn-primary btn-sm btn-block"> '+textStr+' </button>';
							$(".connect_user_"+connection_user_id).html(buttonHtml);
						}
						else if(class_param=="follow"){
							var on_click_function_call_connect="ConnectUser('"+connection_user_id+"','"+parsed_json.function_param+"');";
							var buttonHtml='<button type="button" onclick="'+on_click_function_call_connect+'" class="btn btn-outline-primary btn-sm btn-block"> <i class="feather-user-plus"></i> '+textStr+' </button>';
							$(".follow_user_"+connection_user_id).html(buttonHtml);
						}
					}
					else
					{
						alert(parsed_json.message);
					}
				}
			});
		}
		else
		{
			alert('invalid action.');
		}
	}
</script>