<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<title>Messenger | RopeYou Connects</title>
	</head>
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <!-- Main Content -->
				   <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12" style="min-height:650px;">
					  <div class="box shadow-sm rounded bg-white mb-0 osahan-chat">
						 <div class="row m-0">
							<div class="border-right col-lg-5 col-xl-4 px-0 d-none d-lg-inline" id="chat_list_dynamic">
							   <div class="osahan-chat-left people-list" id="people-list">
								  <div class="position-relative icon-form-control p-3 border-bottom search">
									 <i class="feather-search position-absolute"></i>
									 <input placeholder="Search messages" type="text" class="form-control">
								  </div>
									<?php
										$thread="";
										if(isset($_REQUEST['thread']) && $_REQUEST['thread']!="")
										{
											$thread=$_REQUEST['thread'];
										}
										if(isset($_REQUEST['nthread']) && $_REQUEST['nthread']!="")
										{
											$nthread=$_REQUEST['nthread'];
											mysqli_query($conn,"UPDATE threats_to_user SET flag=1,checkout=1,seen=1 WHERE md5(id)='$nthread' AND user_id='".$_COOKIE['uid']."'");
										}
										
										$me=$_COOKIE['uid'];
										$friend_id="";
										$friends=array();
										$user_id=$_COOKIE['uid'];
										$chat_list_query="SELECT * FROM user_joins_user WHERE user_id='".$_COOKIE['uid']."' OR r_user_id='".$_COOKIE['uid']."' AND status=1 ORDER BY id DESC";
										$chat_list_result=mysqli_query($conn,$chat_list_query);
										$chat_list_num_row=mysqli_num_rows($chat_list_result);
										if($chat_list_num_row>0)
										{
											while($chat_list_row=mysqli_fetch_array($chat_list_result))
											{
												if($chat_list_row['user_id']==$_COOKIE['uid'])
												{
													$friend=$chat_list_row['r_user_id'];
													
													if(!(in_array($friend,$friends)))
													{
														$friends[]=$friend;
													}
												}
												else
												{
													$friend=$chat_list_row['user_id'];
													if(!(in_array($friend,$friends)))
													{
														$friends[]=$friend;
													}
												}
											}
										}
										//echo $chat_list_query;
										//print_r($friends);
									?>
								  <div class="osahan-chat-list list" id="oshan_chat_list">|<!-- style="max-height:550px !important;overflow-y:auto;"-->
									<?php
										$selected_user=$friends[0];
										$printed=0;
										for($loopVar=0;$loopVar<count($friends);$loopVar++)
										{
											$friend_id=$friends[$loopVar];
											$friends_query="SELECT * FROM users WHERE id='$friend_id'";
											$friends_result=mysqli_query($conn,$friends_query);
											if(mysqli_num_rows($friends_result)>0)
											{
												$printed=$printed+1;
												$friends_row=mysqli_fetch_array($friends_result);
												
												$message_query="SELECT * FROM users_chat WHERE ((r_user_id='$friend_id' AND user_id='$user_id' AND s_status=1 AND status=1) OR (user_id='$friend_id' AND r_user_id='$user_id' AND status=1 AND r_status=1)) ORDER BY added DESC LIMIT 1";
												$message_result=mysqli_query($conn,$message_query);
												$message_row=mysqli_fetch_array($message_result);
												?>
												<div class="p-3 d-flex align-items-center border-bottom osahan-post-header overflow-hidden" onclick="updateUser(<?php echo $friend_id; ?>);" style="cursor:pointer;">
													<div class="dropdown-list-image mr-3">
														<img class="rounded-circle" style="border:1px solid #eaebec !important;paddding:5px;" src="<?php echo getUserProfileImage($friend_id); ?>" title="<?php echo $friends_row['first_name']." ".$friends_row['last_name']; ?>" alt="<?php echo $friends_row['first_name']." ".$friends_row['last_name']; ?>">
														<div class="status-indicator <?php if(userLoggedIn($friend_id)){ echo 'bg-success';} else{ echo 'bg-danger'; } ?>"></div>
													</div>
													<div class="font-weight-bold mr-1 overflow-hidden">
														<div class="text-truncate name"><?php echo ucwords(strtolower($friends_row['first_name']." ".$friends_row['last_name'])); ?></div>
														<div class="small text-truncate overflow-hidden text-black-50" id="message_transaction_data_<?php echo $selected_user; ?>">
															<?php
																if($me==$message_row['r_user_id'])
																{
																	?>
																		<i class="feather-corner-down-right text-primary"></i>
																	<?php
																}
																else if($message_row['flag']==0)
																{
																	?>
																		<i class="feather-check text-primary"></i>
																	<?php
																}
																else if($message_row['flag']==1)
																{
																	?>
																		<i class="feather-check text-primary" style="font-size:bold"></i>
																	<?php
																}
																else if($message_row['flag']==2)
																{
																	?>
																		<i class="feather-check text-primary" style="color:#fff !important;background: url(<?php echo base_url; ?>images/superlike.png) !important;background-size: contain !important;"></i>
																	<?php
																}
															?>
															<?php 
																$text_message=$message_row['text_message'];
																$img_mesg=$message_row['img_mesg'];
																if($text_message=="**RUCONNECTED**")
																{
																	$text_message="Bridge Constructed.Start Knowing Eachother.";
																}
																else if($text_message=="**RUDISCONNECTED**")
																{
																	$text_message="You are no longer connected.";
																}
																else if($img_mesg=="1" && $text_message!="")
																{
																	$text_message='<img src="'.base_url.$text_message.'" style="width:20px;"> &nbsp;Photo';
																}
																echo $text_message;
															?>
														</div>
													</div>
													<span class="ml-auto mb-auto">
													   <div class="text-right text-muted pt-1 small"><?php echo date("D",strtotime($message_row['added'])); ?></div>
													</span>
												</div>
												<?php
											}
											
										}
									?>
								  </div>
							   </div>
							</div>
							<div class="col-lg-7 col-xl-8 px-0" id="whole_div">
								<?php
									if($thread=="")
									{
										if(isset($friends[0]) && $friends[0]!="")
										{
											$selected_user=$friends[0];
											$thread=md5($selected_user);
										}
									}
									else
									{
										$mu_query="SELECT * FROM users WHERE md5(id)='$thread'";
										$mu_result=mysqli_query($conn,$mu_query);
										$mu_row=mysqli_fetch_array($mu_result);
										$selected_user=$mu_row['id'];
									}
									if($thread!="")
									{
										//$selected_user=$friends[0];
										$mu_query="SELECT * FROM users WHERE id='$selected_user'";
										$mu_result=mysqli_query($conn,$mu_query);
										$mu_row=mysqli_fetch_array($mu_result);
										$selected_user_profile_image=getUserProfileImage($selected_user);
										$selected_user_name=$mu_row['first_name']." ".$mu_row['last_name'];
										
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
													<div class="dropdown-menu dropdown-menu-right" id="chat_menu_action_dropdown">
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
														$message_query="SELECT * FROM users_chat WHERE ((user_id='$me' AND r_user_id='$selected_user' AND s_status=1 AND status=1) OR (r_user_id='$me' AND user_id='$selected_user' AND r_status=1 AND status=1)) ORDER BY added ASC";
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
																else if($message_row['text_message']=="**RUDISCONNECTED**")
																{
																	?>
																	<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
																		<div class="mr-auto ml-auto">
																			<p style="text-align:center;" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo date("h:i a",strtotime($message_row['added'])); ?>">You are no longer connected.</p>
																		</div>
																	</div>
																	<?php
																}
																else if($me==$message_row['user_id'])
																{
																	?>
																	<div class="d-flex align-items-center osahan-post-header thread_<?php echo $message_row['id']; ?>" style="margin-top:10px;margin-bottom:10px;">
																		<span class="mr-auto mb-auto">
																		</span>
																		<div class="mr-2 ml-1" style="max-width:60% !important;">
																			<?php
																				if($img_mesg=="1" && $message_row['text_message']!="")
																				{
																					echo '<img src="'.base_url.$message_row['text_message'].'" width="320" height="240" style="width:100%;border-radius:10px;" data-toggle="tooltip" data-placement="top" data-original-title="'.date("h:i a",strtotime($message_row['added'])).'">';
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
																	<div class="d-flex align-items-center osahan-post-header thread_<?php echo $message_row['id']; ?>" style="margin-top:10px;margin-bottom:10px;">
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
											<textarea placeholder="Write a message…" <?php if(userBlocked($selected_user)){ echo 'disabled="true" title="Due to blocking you are not allowed to send messages to this user for now."'; } ?> data-friend="<?php echo $selected_user; ?>" id="message_box" class="form-control border-0 p-3 shadow-none message_box" style="resize:none;" rows="2"></textarea>
										</div>
										<div class="p-3 d-flex align-items-center">
											<div class="overflow-hidden">
												 <button type="button" onclick="openFileChooser();" class="btn btn-light btn-sm rounded message_box">
													<i class="feather-image"></i>
												 </button>
												 <button type="button" onclick="openAttachmentChooser();" class="btn btn-light btn-sm rounded message_box">
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
								<?php
									}
									else if($printed==0)
									{
										?>
										<div class="p-3 w-100 align-items-center border-bottom osahan-post-header overflow-hidden" style="margin-top:150px;">
											<p class="text-center" style="text-align:center !important;">You don't have any connections, <a href="<?php echo base_url; ?>/bridge">click here</a> to add connections.</p>
										</div>
										<?php
									}
								?>
							</div>
						 </div>
					  </div>
					</main>
					<aside class="col col-xl-3 order-xl-2 col-lg-12 order-lg-2 col-12 d-none d-lg-inline">
						
					</aside>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
   </body>
</html>
<script id="message-template" type="text/x-handlebars-template">
	<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
		<span class="mr-auto mb-auto">
		</span>
		<div class="mr-2 ml-1" style="max-width:60% !important;">
			<p data-toggle="tooltip" data-placement="top" data-original-title="{{time}}">{{messageOutput}}</p>
		</div>
		<div class="dropdown-list-image ml-3 mb-auto">
			<img class="rounded-circle" style="border:1px solid #eaebec !important;padding:5px;cursor:pointer;height:2rem;width:2rem;" src="{{messageSenderImage}}"  data-toggle="tooltip" data-placement="top" data-original-title="{{messageSenderName}}" alt="{{messageSenderName}}">
			<p style="margin-top:5px;font-size:9px;">
				{{messageStatus}}
			</p>
		</div>
	</div>
</script>
<script id="image-message-template" type="text/x-handlebars-template">
	<div class="d-flex align-items-center osahan-post-header" style="margin-top:10px;margin-bottom:10px;">
		<span class="mr-auto mb-auto">
		</span>
		<div class="mr-2 ml-1" style="max-width:60% !important;">
			<img src="{{messageOutput}}" width="320" height="240" style="width:100%;border-radius:10px;"  data-toggle="tooltip" data-placement="top" data-original-title="{{time}}">
		</div>
		<div class="dropdown-list-image ml-3 mb-auto">
			<img class="rounded-circle" style="border:1px solid #eaebec !important;padding:5px;cursor:pointer;height:2rem;width:2rem;" src="{{messageSenderImage}}"  data-toggle="tooltip" data-placement="top" data-original-title="{{messageSenderName}}" alt="{{messageSenderName}}">
			<p style="margin-top:5px;font-size:9px;">
				{{messageStatus}}
			</p>
		</div>
	</div>
</script>
<script id="message-response-template" type="text/x-handlebars-template">
  <li>
	<div class="message-data">
	  <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
	  <span class="message-data-time">{{time}}, Today</span>
	</div>
	<div class="message my-message">
	  {{response}}
	</div>
  </li>
</script>
<script id="image-message-response-template" type="text/x-handlebars-template">
  <li>
	<div class="message-data">
	  <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
	  <span class="message-data-time">{{time}}, Today</span>
	</div>
	<div class="message my-message">
		<img src="{{response}}" width="320" height="240" style="width:100%;border-radius:10px;"  data-toggle="tooltip" data-placement="top" data-original-title="{{time}}">
	</div>
  </li>
</script>
<script src="<?php echo base_url; ?>js/list.min.js"></script>
<script src="<?php echo base_url; ?>handlebars.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>capture/webcamjs/webcam.min.js"></script>
<script src="<?php echo base_url; ?>chat.js"></script>