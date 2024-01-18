<div class="modal fade change_visibility" id="change_visibility" style="z-index:99999 !important;" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="change_visibility_static" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="row change_visibility_section" style="padding:0px;margin:0px;" id="change_visibility_section">
				<div class="col-md-12" style="padding:0px;margin:0px;">
					<div class="modal-header" style="width:100%;">
						<div class="row" style="width:100%;">
							<div class="col-md-8">
								<h5 class="modal-title" id="change_visibility_static">Visibility </h5>
							</div>
							<div class="col-md-4">
								<button  data-dismiss="modal" style="border-color:none !important;float:right !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-times"></i></button>
							</div>
						</div>
					</div>
					<div class="modal-body" style="padding:0px;">
						<div class="w-100">
							<div class="row" style="width:100%;padding:0px;margin:0px;">
								<div class="col-md-12" style="padding:10px;border-top:1px solid #dee2e6;margin-bottom:20px;">
									<input type="hidden" name="hidden_visibility_token" id="hidden_visibility_token" value="">
									<input type="hidden" name="hidden_visibility_type" id="hidden_visibility_type" value="">
									<select class="form-control" id="change_content_visibility_option" name="change_content_visibility_option">
										<option value="1,0,0,0,0">Anyone</option>
										<option value="0,1,0,0,0">Only Me</option>
										<option value="0,0,1,0,0">Only Connections</option>
										<option value="0,0,1,1,0">Connections of Connections</option>
										<option value="0,0,1,0,1">Allow Specific Connection</option>
										<option value="0,0,1,0,2">Block Specific Connection</option>
									</select>
								</div>
								<div class="col-md-12 change_content_visibility_users" id="change_content_visibility_users_allowed" style="min-height:250px;max-height:251px;overflow-y:auto;display:none;">
									<?php
										$users_allowed=array();
										$allowed_uquery="SELECT * FROM users_privacy_settings WHERE setting_term='who_can_see_broadcast_post_option' AND user_id='".$_COOKIE['uid']."'";
										$allowed_uresult=mysqli_query($conn,$allowed_uquery);
										if(mysqli_num_rows($allowed_uresult)>0)
										{
											$allowed_urow=mysqli_fetch_array($allowed_uresult);
											$users_allowed_str=$allowed_urow['users_allowed'];
										}
										$users_allowed=explode(",",$users_allowed_str);
										
										$__user_id=$_COOKIE['uid'];
										$friends=array();
										$friends_query="SELECT * FROM user_joins_user WHERE user_id='".$__user_id."' AND status=1";
										$friends_result=mysqli_query($conn,$friends_query);
										if(mysqli_num_rows($friends_result)>0)
										{
											while($friends_row=mysqli_fetch_array($friends_result))
											{
												$friends[]=$friends_row['r_user_id'];
											}
										}
										$friends_query="SELECT * FROM user_joins_user WHERE r_user_id='".$__user_id."' AND status=1";
										$friends_result=mysqli_query($conn,$friends_query);
										if(mysqli_num_rows($friends_result)>0)
										{
											while($friends_row=mysqli_fetch_array($friends_result))
											{
												$friends[]=$friends_row['user_id'];
											}
										}
										$bridge_query="SELECT * FROM users WHERE id IN ('".implode("','",$friends)."')";
										$bridge_result=mysqli_query($conn,$bridge_query);
										$bridge_num_rows=mysqli_num_rows($bridge_result);
										if($bridge_num_rows>0)
										{
											?>
											<div class="row" id="visibility_allowed_user_div">
											<?php
											while($bridge_row=mysqli_fetch_array($bridge_result))
											{
												$connect_user_id=$bridge_row['id'];
												$tag_profile=getUserProfileImage($connect_user_id);
												?>
												<div class="col-md-12" style="margin-bottom:5px;font-size:16px;">
													<input type="checkbox" name="allowed_visibility_friends[]" class="allowed_visibility_friends" value="<?php echo $connect_user_id; ?>" <?php if(in_array($connect_user_id,$users_allowed)){ echo "checked"; } ?> style="width:20px;height:20px;vertical-align:-6px;">&nbsp;&nbsp;
													<a href="<?php echo base_url."u/".$bridge_row['username']; ?>" onclick="return confirm('This may cause to loss your unsaved data.Still want to continue?');" style="text-decoration:none;"><img src="<?php echo $tag_profile; ?>" style="height:40px;width:40px;border-radius:50%;">&nbsp;&nbsp;<?php echo ucwords(strtolower($bridge_row['first_name'].' '.$bridge_row['last_name'])); ?></a>
												</div>
												<?php
											}
											?>
											</div>
											<?php
										}
										else
										{
											?>
											<h6 style="text-align:center;">there is no connections</h6>
											<?php
										}
									?>	
								</div>
								<div class="col-md-12 change_content_visibility_users" id="change_content_visibility_users_blocked" style="min-height:250px;max-height:251px;overflow-y:auto;display:none;">
									<?php
										$users_blocked=array();
										$blocked_uquery="SELECT * FROM users_privacy_settings WHERE setting_term='who_can_see_broadcast_post_option' AND user_id='".$_COOKIE['uid']."'";
										$blocked_uresult=mysqli_query($conn,$blocked_uquery);
										if(mysqli_num_rows($blocked_uresult)>0)
										{
											$blocked_urow=mysqli_fetch_array($blocked_uresult);
											$users_blocked_str=$blocked_urow['users_blocked'];
										}
										$users_blocked=explode(",",$users_blocked_str);
										
										
										$__user_id=$_COOKIE['uid'];
										$friends=array();
										$friends_query="SELECT * FROM user_joins_user WHERE user_id='".$__user_id."' AND status=1";
										$friends_result=mysqli_query($conn,$friends_query);
										if(mysqli_num_rows($friends_result)>0)
										{
											while($friends_row=mysqli_fetch_array($friends_result))
											{
												$friends[]=$friends_row['r_user_id'];
											}
										}
										$friends_query="SELECT * FROM user_joins_user WHERE r_user_id='".$__user_id."' AND status=1";
										$friends_result=mysqli_query($conn,$friends_query);
										if(mysqli_num_rows($friends_result)>0)
										{
											while($friends_row=mysqli_fetch_array($friends_result))
											{
												$friends[]=$friends_row['user_id'];
											}
										}
										$bridge_query="SELECT * FROM users WHERE id IN ('".implode("','",$friends)."')";
										$bridge_result=mysqli_query($conn,$bridge_query);
										$bridge_num_rows=mysqli_num_rows($bridge_result);
										if($bridge_num_rows>0)
										{
											?>
											<div class="row" id="visibility_blocked_user_div">
											<?php
											while($bridge_row=mysqli_fetch_array($bridge_result))
											{
												$connect_user_id=$bridge_row['id'];
												$tag_profile=getUserProfileImage($connect_user_id);
												?>
												<div class="col-md-12" style="margin-bottom:5px;font-size:16px;">
													<input type="checkbox" name="blocked_visibility_friends[]" class="blocked_visibility_friends" value="<?php echo $connect_user_id; ?>" <?php if(in_array($connect_user_id,$users_blocked)){ echo "checked"; } ?> style="width:20px;height:20px;vertical-align:-6px;">&nbsp;&nbsp;
													<a href="<?php echo base_url."u/".$bridge_row['username']; ?>" onclick="return confirm('This may cause to loss your unsaved data.Still want to continue?');" style="text-decoration:none;"><img src="<?php echo $tag_profile; ?>" style="height:40px;width:40px;border-radius:50%;">&nbsp;&nbsp;<?php echo ucwords(strtolower($bridge_row['first_name'].' '.$bridge_row['last_name'])); ?></a>
												</div>
												<?php
											}
											?>
											</div>
											<?php
										}
										else
										{
											?>
											<h6 style="text-align:center;">there is no connections</h6>
											<?php
										}
									?>	
								</div>
								<div class="col-md-12" style="margin-top:20px;margin-bottom:20px;">
									<button class="btn btn-info pull-right" type="button" onclick="saveContentVisibility();">Update</button>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var base_url="<?php echo base_url; ?>";
	function conn_selec_text_arr(mode_seection_dropdown){
		switch(mode_seection_dropdown)
		{
			case "1,0,0,0,0": return "<i class='fa fa-globe'></i>&nbsp;&nbsp;Any One";break;
			case "0,1,0,0,0":return "<i class='fa fa-user'></i>&nbsp;&nbsp;Only Me";break;
			case "0,0,1,0,0":return "<i class='fa fa-users'></i>&nbsp;&nbsp;Only Connections";break;
			case "0,0,1,1,0":return "<i class='fa fa-users'></i>&nbsp;&nbsp;Connections of Connections";break;
			case "0,0,1,0,1":return "<i class='fa fa-users'></i>&nbsp;+&nbsp;Allowed Specific";break;
			case "0,0,1,0,2":return "<i class='fa fa-users'></i>&nbsp;-&nbsp;Blocked Specific";break;
		}
	}
	function saveContentVisibility()
	{
		var random_number=$("#hidden_visibility_token").val();
		var type=$("#hidden_visibility_type").val();
		var settings=$("#change_content_visibility_option").val();
		var users_allowed="";
		var users_blocked="";
		if(settings=="0,0,1,0,1")
		{
			var ua=[];
			$("input:checkbox[name='allowed_visibility_friends[]']:checked").each(function(){
				ua.push($(this).val());
			});
			users_allowed=ua.toString();
		}
		else if(settings=="0,0,1,0,2")
		{
			var ub=[];
			$("input:checkbox[name='blocked_visibility_friends[]']:checked").each(function(){
				ub.push($(this).val());
			});
			users_blocked=ub.toString();
		}
		$.ajax({
			url:base_url+"save-post-broadcast-setting",
			type:"post",
			data:{random_number:random_number,type:type,settings:settings,users_allowed:users_allowed,users_blocked:users_blocked},
			success:function(response)
			{
				var parsedJson=JSON.parse(response);
				if(parsedJson.status=="success")
				{
					$("#change_post_visibility_"+random_number).html(conn_selec_text_arr(settings));
					$("#change_"+type+"_visibility_"+random_number).data("setting",settings);
					$("#change_"+type+"_visibility_"+random_number).data("ua",users_allowed);
					$("#change_"+type+"_visibility_"+random_number).data("ub",users_blocked);
					$("#change_visibility").modal("hide");
				}
				else
				{
					alert(parsedJson.message);
				}
			}
		});
	}
	$("#change_content_visibility_option").change(function(){
		var random_number=$("#hidden_visibility_token").val();
		var type=$("#hidden_visibility_type").val();
		/*-----------------------------------------------------*/
		var setting=$("#change_"+type+"_visibility_"+random_number).data("setting");
		var users_allowed=$("#change_"+type+"_visibility_"+random_number).data("ua")+",";
		var users_blocked=$("#change_"+type+"_visibility_"+random_number).data("ub")+",";
		//$("#change_content_visibility_option").val(setting);
		//console.log(users_allowed);
		//console.log(users_blocked);
		console.log(users_blocked);
		var users_allowed_arr=users_allowed.split(",");
		if(users_allowed_arr.length>0)
		{
			$("input:checkbox[name='allowed_visibility_friends[]']").attr("checked",false);
			for(loop=0;loop<users_allowed_arr.length;loop++)
			{
				$("input:checkbox[name='allowed_visibility_friends[]'][value='"+users_allowed_arr[loop]+"']").attr("checked",true);
			}
		}
		var users_blocked_arr=users_blocked.split(",");
		if(users_blocked_arr.length>0)
		{
			$("input:checkbox[name='blocked_visibility_friends[]']").attr("checked",false);
			for(loop=0;loop<users_blocked_arr.length;loop++)
			{
				$("input:checkbox[name='blocked_visibility_friends[]'][value='"+users_blocked_arr[loop]+"']").attr("checked",true);
			}
		}
		/*-----------------------------------------------------*/
		var settings=$("#change_content_visibility_option").val();
		$(".change_content_visibility_users").hide();
		if(settings=="0,0,1,0,1")
		{
			$("#change_content_visibility_users_allowed").show();
		}
		else if(settings=="0,0,1,0,2")
		{
			$("#change_content_visibility_users_blocked").show();
		}
	});
	function changeContentVisibility(random_number,type="post")
	{
		$("#hidden_visibility_token").val(random_number);
		$("#hidden_visibility_type").val(type);
		$(".change_content_visibility_users").hide();
		$(".blocked_visibility_friends").attr("checked",false);
		$(".allowed_visibility_friends").attr("checked",false);
		var hash=$("#change_"+type+"_visibility_"+random_number).data("token");
		var setting=$("#change_"+type+"_visibility_"+random_number).data("setting");
		var users_allowed=$("#change_"+type+"_visibility_"+random_number).data("ua")+",";
		var users_blocked=$("#change_"+type+"_visibility_"+random_number).data("ub")+",";
		$("#change_content_visibility_option").val(setting);
		//console.log(users_allowed);
		//console.log(users_blocked);
		var users_allowed_arr=users_allowed.split(",");
		if(users_allowed_arr.length>0)
		{
			$("input:checkbox[name='allowed_visibility_friends[]']").attr("checked",false);
			for(loop=0;loop<users_allowed_arr.length;loop++)
			{
				$("input:checkbox[name='allowed_visibility_friends[]'][value='"+users_allowed_arr[loop]+"']").attr("checked",true);
			}
		}
		var users_blocked_arr=users_blocked.split(",");
		if(users_blocked_arr.length>0)
		{
			$("input:checkbox[name='blocked_visibility_friends[]']").attr("checked",false);
			for(loop=0;loop<users_blocked_arr.length;loop++)
			{
				$("input:checkbox[name='blocked_visibility_friends[]'][value='"+users_blocked_arr[loop]+"']").attr("checked",true);
			}
		}
		if(setting=="0,0,1,0,1")
		{
			$("#change_content_visibility_users_allowed").show();
		}
		else if(setting=="0,0,1,0,2")
		{
			$("#change_content_visibility_users_blocked").show();
		}
		
		$("#change_visibility").modal("show");
	}						
</script>