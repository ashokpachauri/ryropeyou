<?php
	include_once 'connection.php';
	$selected_user=$_REQUEST['user_id'];
	
	$me=$_COOKIE['uid'];
?>
<?php
	
		//$selected_user=$friends[0];
		$mu_query="SELECT * FROM users WHERE id='$selected_user'";
		$mu_result=mysqli_query($conn,$mu_query);
		$mu_row=mysqli_fetch_array($mu_result);
		$selected_user_profile_image=getUserProfileImage($selected_user);
		$selected_user_name=ucwords(strtolower($mu_row['first_name']." ".$mu_row['last_name']));
		
		$me_query="SELECT * FROM users WHERE id='$me'";
		$me_result=mysqli_query($conn,$me_query);
		$me_row=mysqli_fetch_array($me_result);
		$me_name=$me_row['first_name']." ".$me_row['last_name'];
		$me_image=getUserProfileImage($me);
?>
<div class="p-3 d-flex align-items-center  border-bottom osahan-post-header">
	<div class="dropdown-list-image mr-3 mb-auto"><img class="rounded-circle" id="active_user_image" style="cursor:pointer;border:1px solid #eaebec !important;padding:5px;" title="<?php echo $selected_user_name; ?>" src="<?php echo $selected_user_profile_image; ?>" alt="<?php echo $selected_user_name; ?>"></div>
	<div class="font-weight-bold mr-1 overflow-hidden">
		<div class="text-truncate" id="active_user_name"><?php echo $selected_user_name; ?>
		</div>
		<div class="small text-truncate overflow-hidden text-black-50" id="active_user_profile_title"><?php echo $mu_row['profile_title']; ?></div>
	</div>
	<span class="ml-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-light btn-sm rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="feather-more-vertical"></i>
			</button>
			<div class="dropdown-menu dropdown-menu-right">
				<button class="dropdown-item" type="button"><i class="feather-phone"></i> Voice Call</button>
				<button class="dropdown-item" type="button"><i class="feather-video"></i> Video Call</button>	
				<button class="dropdown-item" type="button" onclick="DeleteWholeThread('<?php echo $selected_user; ?>');"><i class="feather-trash"></i> Delete</button>
				<?php
					if(userBlocked($selected_user))
					{
						?>
						<button class="dropdown-item" type="button" onclick="saveUserReport('<?php echo $selected_user; ?>','','unblock');"><i class="feather-x-circle"></i> Unblock</button>
						<?php
					}
					else
					{
						?>
						<button class="dropdown-item" type="button" onclick="ReportAndBlockUser('<?php echo $selected_user; ?>','<?php echo $selected_user_name; ?>','<?php echo $selected_user_profile_image; ?>');"><i class="feather-x-circle"></i> Report & Block</button>
						<?php
					}
				?>
			</div>
		</div>
	</span>
</div>										
<div class="row">
	<div class="col-lg-12 col-xl-12 col-md-12 chat-history" id="messages_stack">
		<div class="osahan-chat-box p-3 border-top border-bottom bg-light" id="chat_history" style="overflow-y:scroll !important;height:420px;">
			<?php
				$current_date="";
				$message_query="SELECT * FROM users_chat WHERE ((user_id='$me' AND r_user_id='$selected_user' AND s_status=1) OR (r_user_id='$me' AND user_id='$selected_user' AND r_status=1)) AND status=1 ORDER BY added ASC";
				$message_result=mysqli_query($conn,$message_query);
				if(mysqli_num_rows($message_result)>0)
				{
					mysqli_query($conn,"UPDATE users_chat SET flag=2,fetched=1 WHERE user_id='$selected_user' AND r_user_id='$me' AND (flag=0 OR flag=1)");
					while($message_row=mysqli_fetch_array($message_result))
					{
						
						$img_mesg=$message_row['img_mesg'];
						if(date("M d,Y",strtotime($message_row['added']))!=$current_date)
						{
							$current_date=date("M d,Y",strtotime($message_row['added']));
							?>
							<div class="text-center my-3">
								<span class="px-3 py-2 small bg-white shadow-sm  rounded"><?php echo $current_date; ?></span>
							</div>
							<?php
						}
						if($message_row['text_message']=="**RUCONNECTED**")
						{
							?>
							<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
								<div class="mr-auto ml-auto">
									<p style="text-align:center;" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo date("h:i a",strtotime($message_row['added'])); ?>">Bridge Constructed.Start Knowing Eachother.</p>
								</div>
							</div>
							<?php
						}
						else if($me==$message_row['user_id'])
						{
							?>
							<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
								<span class="mr-auto mb-auto">
								</span>
								<div class="mr-2 ml-1" style="max-width:60% !important;">
									<?php
										if($img_mesg=="1" && $message_row['text_message']!="")
										{
											echo '<img src="'.$message_row['text_message'].'" width="320" height="240" style="width:100%;border-radius:10px;" data-toggle="tooltip" data-placement="top" data-original-title="'.date("h:i a",strtotime($message_row['added'])).'">';
										}
										else
										{
									?>
											<p data-toggle="tooltip" data-placement="top" data-original-title="<?php echo date("h:i a",strtotime($message_row['added'])); ?>"><?php echo filter_var($message_row['text_message'],FILTER_SANITIZE_STRING); ?></p>
									<?php
										}
									?>
								</div>
								<div class="dropdown-list-image ml-3 mb-auto">
									<img class="rounded-circle" style="border:1px solid #eaebec !important;padding:5px;cursor:pointer;height:2rem;width:2rem;" src="<?php echo $me_image; ?>"  data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $me_name; ?>" alt="<?php echo $me_name; ?>">
									<p style="margin-top:5px;font-size:9px;">
										<?php 
											if($message_row['flag']=="0")
											{
												echo "Sent";
											}
											else if($message_row['flag']=="1")
											{
												echo "Delivered";
											}
											else if($message_row['flag']=="2")
											{
												echo "Seen";
											}
										?>
									</p>
								</div>
							</div>
							<?php
						}
						else if($me==$message_row['r_user_id'])
						{
							?>
							<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
								<div class="dropdown-list-image mr-1 mb-auto">
									<img class="rounded-circle" style="cursor:pointer;border:1px solid #eaebec !important;padding:5px;height:2rem;width:2rem;" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $selected_user_name; ?>" src="<?php echo $selected_user_profile_image; ?>" alt="<?php echo $selected_user_name; ?>">
								</div>
								<div class="mr-1" style="max-width:60% !important;">
									<?php
										if($img_mesg==1 && $message_row['text_message']!="")
										{
											echo '<img src="'.$message_row['text_message'].'" width="320" height="240" style="width:100%;border-radius:10px;" data-toggle="tooltip" data-placement="top" data-original-title="'.date("h:i a",strtotime($message_row['added'])).'">';
										}
										else
										{
									?>
											<p data-toggle="tooltip" data-placement="top" data-original-title="<?php echo date("h:i a",strtotime($message_row['added'])); ?>"><?php echo filter_var($message_row['text_message'],FILTER_SANITIZE_STRING); ?></p>
									<?php
										}
									?>
								</div>
							</div>
							<?php
						}
					}
				}
			?>
		</div>
	</div>
</div>
<div class="w-100 border-top border-bottom">
	<textarea placeholder="Write a message…" <?php if(userBlocked($selected_user)){ echo 'disabled="true" title="Due to blocking you are not allowed to send messages to this user for now."'; } ?> id="message_box" data-friend="<?php echo $selected_user; ?>" class="form-control border-0 p-3 shadow-none message_box" style="resize:none;" rows="2"></textarea>
</div>
<div class="p-3 d-flex align-items-center">
	<div class="overflow-hidden">
		 <button type="button" onclick="openFileChooser();" class="btn btn-light btn-sm rounded message_box">
			<i class="feather-image"></i>
		 </button>
		 <button type="button" class="btn btn-light btn-sm rounded message_box">
			<i class="feather-paperclip"></i>
		 </button>	
		 <button type="button" onclick="openCamRecorder();" class="btn btn-light btn-sm rounded message_box">
			<i class="feather-camera"></i>
		 </button>
	</div>
	<span class="ml-auto">
		<button type="button" class="btn btn-primary btn-sm rounded message_box" id="message_box_button" data-msnm="<?php echo $me_name; ?>" data-ruid="<?php echo $selected_user; ?>" data-suid="<?php echo $me; ?>" data-suimg="<?php echo $me_image; ?>">
			<i class="feather-send"></i> Send
		</button>
	</span>
</div>					