<input type="hidden" value="1" name="post_content_type" id="post_content_type">
<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$post_count=0;
	$posts_query="SELECT * FROM users_posts WHERE status=1 ORDER BY added DESC"; 
	//(user_id='".$_COOKIE['uid']."' OR r_user_id='".$_COOKIE['uid']."')
	$posts_result=mysqli_query($conn,$posts_query);
	//echo $posts_query;
	if(mysqli_num_rows($posts_result)>0)
	{
		while($posts_row=mysqli_fetch_array($posts_result))
		{
			$post_count=$post_count+1;
			if($post_count==2)
			{
				$jobs_query="SELECT * FROM jobs WHERE status=1";
				$jobs_result=mysqli_query($conn,$jobs_query);
				if(mysqli_num_rows($jobs_result)>0)
				{
					?>
						<div class="mb-3 shadow-sm rounded box bg-white osahan-slider-main">
							<div class="osahan-slider">
								<?php
									while($jobs_row=mysqli_fetch_array($jobs_result))
									{
										$og_title=base_url."job/".trim(strtolower($jobs_row['job_title']))." ".trim(strtolower($jobs_row['job_company']));
										$og_title=str_replace(" ","-",$og_title);
										$og_url=$og_title."-".$jobs_row['id'].".html";
										
										$og_image=base_url."alphas/".strtolower(substr($jobs_row['job_company'],0,1)).".png";
										?>
										<div class="osahan-slider-item">
											<a href="<?php echo $og_url; ?>">
												<div class="shadow-sm border rounded bg-white job-item job-item mr-2 mt-3 mb-3">
													<div class="d-flex align-items-center p-3 job-item-header">
														<div class="overflow-hidden mr-2">
														   <h6 class="font-weight-bold text-dark mb-0 text-truncate"><?php echo $jobs_row['job_title']; ?></h6>
														   <div class="text-truncate text-primary"><?php echo $jobs_row['job_company']; ?></div>
														   <div class="small text-gray-500"><i class="feather-map-pin"></i> <?php echo $jobs_row['job_location']; ?></div>
														</div>
														<img class="img-fluid ml-auto" src="<?php echo $og_image; ?>" alt="" style="border:1px solid #eaebec !important;padding: 2px;border-radius: 7px;">
													</div>
													<?php getCommonPersonsOnJob($jobs_row['id'],$_COOKIE['uid']); ?>
													<div class="p-3 job-item-footer">
														<small class="text-gray-500"><i class="feather-clock"></i> Posted <?php echo date("d-m-Y",strtotime($jobs_row['added'])); ?></small>
													</div>
												</div>
											</a>
										</div>
										<?php
									}
								?>
							</div>
						</div>
					<?php
				}
			}
			$who_can_see_broadcast_post_option_post=$posts_row['is_public'].",".$posts_row['is_private'].",".$posts_row['is_protected'].",".$posts_row['is_friendly_protected'].",".$posts_row['is_magic'];
			$can_see_this_post=canSeeThisPost($_COOKIE['uid'],$posts_row['id']);	
			if($can_see_this_post)
			{
				$p_user_id=$posts_row['user_id'];
				$p_user_profile=getUserProfileImage($p_user_id);
				
				$p_user_query="SELECT * FROM users WHERE id='$p_user_id'";
				$p_user_result=mysqli_query($conn,$p_user_query);
				$p_user_row=mysqli_fetch_array($p_user_result);
				
				//$occu_query="SELECT * FROM users_"; 
				
				$time="";

				$t=strtotime('now')- strtotime($posts_row['added']);

				$days=floor($t/(60*60*24));

				$hours=floor(($t/(60*60))-($days*24));

				$minutes=floor(($t/(60))-($days*24)-($hours*60));

				$seconds=floor(($t)-($days*24)-($hours*60)-($minutes*60));

				if($days!=0)

				{

					$time="about ".$days." days ago.";

				}

				else if($hours!=0){

					$time="about ".$hours." hours ago.";

				}

				else if($minutes!=0){

					$time="about ".$minutes." minutes ago.";

				}

				else if($seconds!=0){

					$time="about ".$seconds." seconds ago.";

				}
				
				$r_user_query="SELECT * FROM users WHERE id='".$posts_row['r_user_id']."'";

				$r_user_result=mysqli_query($conn,$r_user_query);

				$r_user_row=mysqli_fetch_array($r_user_result);
				
				$post_title=$posts_row['post_title'];

				$post_title=str_replace("{first_name}",$first_name,$post_title);

				$post_title=str_replace("{last_name}",$last_name,$post_title);

				$post_id=$posts_row['id'];

				$post_media_query="SELECT * FROM users_posts_media WHERE post_id='$post_id'";

				$post_media_result=mysqli_query($conn,$post_media_query);
				
				$num_rows=mysqli_num_rows($post_media_result);
				$ex_query="SELECT * FROM posts_reports WHERE user_id='".$_COOKIE['uid']."' AND post_id='".$posts_row['id']."'";
				$ex_result=mysqli_query($conn,$ex_query);
				if(mysqli_num_rows($ex_result)<=0)
				{
					
					$_active_query="SELECT * FROM users_logs WHERE user_id='".$posts_row['user_id']."'";
					$_active_res=mysqli_query($conn,$_active_query);
					$_active_row=mysqli_fetch_array($_active_res);
					$_active_status="bg-success";
					if($_active_row['is_active']=="0")
					{
						$_active_status="bg-danger";
					}
			?>
				<div class="box shadow-sm border rounded bg-white mb-3 osahan-post" id="user_posts_container_number_<?php echo $post_id; ?>">
					<div class="p-3 d-flex align-items-center border-bottom osahan-post-header">
						<div class="dropdown-list-image mr-3">
						   <img class="rounded-circle" style="border:1px solid #eaebec !important;" src="<?php echo $p_user_profile; ?>" alt="<?php echo $p_user_row['first_name']." ".$p_user_row['last_name']; ?>">
						   <div class="status-indicator <?php echo $_active_status; ?>"></div>
						</div>
						<div class="font-weight-bold">
						   <div class="text-truncate">
								<a href="<?php echo base_url; ?>u/<?php echo $p_user_row['username']; ?>" style="text-decoration:none;">
									<?php echo $p_user_row['first_name']." ".$p_user_row['last_name']; ?>
								</a><?php
									$already_tagged=array();
									$tagged_query="SELECT * FROM users_posts_tags WHERE post_id='".$posts_row['id']."'";
									$tagged_result=mysqli_query($conn,$tagged_query);
									$tagged_num_rows=mysqli_num_rows($tagged_result);
									if($tagged_num_rows>0)
									{
										$tagged_row=mysqli_fetch_array($tagged_result);
										$tagged_user_query="SELECT * FROM users WHERE id='".$tagged_row['r_user_id']."'";
										$tagged_user_result=mysqli_query($conn,$tagged_user_query);
										if(mysqli_num_rows($tagged_user_result)>0)
										{
											$already_tagged[]=$tagged_row['r_user_id'];
											$tagged_user=mysqli_fetch_array($tagged_user_result);
											?>
											&nbsp;is with&nbsp; <a href="<?php echo base_url; ?>u/<?php echo $tagged_user['username']; ?>"><?php echo ucwords(strtolower($tagged_user['first_name'].' '.$tagged_user['last_name'])); ?></a>
											<?php
										}
										if($tagged_num_rows==2)
										{
											$tagged_row=mysqli_fetch_array($tagged_result);
											$tagged_user_query="SELECT * FROM users WHERE id='".$tagged_row['r_user_id']."'";
											$tagged_user_result=mysqli_query($conn,$tagged_user_query);
											if(mysqli_num_rows($tagged_user_result)>0)
											{
												$already_tagged[]=$tagged_row['r_user_id'];
												$tagged_user=mysqli_fetch_array($tagged_user_result);
												?>
												&nbsp;and&nbsp; <a href="<?php echo base_url; ?>u/<?php echo $tagged_user['username']; ?>"><?php echo ucwords(strtolower($tagged_user['first_name'].' '.$tagged_user['last_name'])); ?></a>
												<?php
											}
										}
										else if($tagged_num_rows>2){
											?>
											&nbsp;and&nbsp;<a href="javascript:void(0);"><?php echo ($tagged_num_rows-1); ?> others</a>
											<?php
											while($tagged_row=mysqli_fetch_array($tagged_result))
											{
												$already_tagged[]=$tagged_row['r_user_id'];
											}
										}
									}
								?>
							</div>
							<?php
								$who_can_see_broadcast_post_option_post=$posts_row['is_public'].",".$posts_row['is_private'].",".$posts_row['is_protected'].",".$posts_row['is_friendly_protected'].",".$posts_row['is_magic'];
							?>
						   <div class="small text-gray-500"><?php echo substr($p_user_row['profile_title'],0,50).'...'; ?><br/><?php //echo $post_title.'&nbsp;'; ?><?php echo $time; ?></div>
							<a href="javascript:void(0);" <?php if($_COOKIE['uid']==$posts_row['user_id']){ ?> data-ua="<?php echo $posts_row['users_allowed']; ?>" data-ub="<?php echo $posts_row['users_blocked']; ?>" data-token="<?php echo md5($posts_row['id']); ?>" id="change_post_visibility_<?php echo $posts_row['id']; ?>" onclick="changeContentVisibility('<?php echo $posts_row['id']; ?>','post');" data-type="post" data-setting="<?php echo $who_can_see_broadcast_post_option_post; ?>" <?php } ?>><i class="fa <?php 
								if($who_can_see_broadcast_post_option_post=="1,0,0,0,0"){ echo "fa-globe"; }
								if($who_can_see_broadcast_post_option_post=="0,1,0,0,0"){ echo "fa-user"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,0,0"){ echo "fa-users"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,1,0"){ echo "fa-users"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,0,1"){ echo "fa-users"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,0,2"){ echo "fa-users"; }
							?>"></i>&nbsp;&nbsp;<?php 
								if($who_can_see_broadcast_post_option_post=="1,0,0,0,0"){ echo "Anyone"; }
								if($who_can_see_broadcast_post_option_post=="0,1,0,0,0"){ echo "Only Me"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,0,0"){ echo "Only Connections"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,1,0"){ echo "Connections of Connections"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,0,1"){ echo "+Allowed Specific Connections"; }
								if($who_can_see_broadcast_post_option_post=="0,0,1,0,2"){ echo "-Blocked Specific Connections"; }
							?></a>&nbsp;&nbsp;&nbsp;
						</div>
						<div class="dropdown ml-auto" style="max-width:50px;min-width:50px;">
							<a class="dropdown-toggle" href="javascript:void(0);" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
							<ul class="dropdown-menu" style="max-width:100px;min-width:80px;margin-left:-20px;">
								<?php
									if($posts_row['user_id']==$_COOKIE['uid'])
									{
								?>
								<li style="max-width:100px;min-width:80px;text-align:center;padding-bottom: 4px;margin-bottom: 5px;" data-toggle="modal" data-target="#do_post_modal_<?php echo $post_id; ?>"><a href="javascript:void(0);">Edit</a></li>
								<li style="max-width:100px;min-width:80px;text-align:center;border-top: 2px solid gray;padding-bottom: 4px;margin-bottom: 5px;" onclick="deletePost('<?php echo $posts_row['id']; ?>');"><a href="javascript:void(0);">Delete</a></li>
								<?php
									}
									else
									{
										?>
										<li style="max-width:100px;min-width:80px;text-align:center;" onclick="reportPost('<?php echo $post_id; ?>','hide');"><a href="javascript:void(0);">Hide Post</a></li>
										<li style="max-width:100px;min-width:80px;text-align:center;border-top: 2px solid gray;" onclick="reportPost('<?php echo $post_id; ?>');"><a href="javascript:void(0);">Report Post</a></li>
										<?php
									}
								?>
							</ul>
						</div>
					</div>
					<div class="p-3 border-bottom osahan-post-body">
						<p class="mb-2 url_meta">
							<?php echo $posts_row['post_text']; ?>
						</p>
						<div class="row" style="padding:0px;margin:0px;">
							<?php 
								if($num_rows>0)
								{
									while($post_media_row=mysqli_fetch_array($post_media_result))
									{
										$media_id=$post_media_row['media_id'];
										$gallery_query="SELECT * FROM gallery WHERE id='$media_id'";
										$gallery_result=mysqli_query($conn,$gallery_query);
										$num_rows=mysqli_num_rows($gallery_result);
										if($num_rows>0)
										{
											$gallery_row=mysqli_fetch_array($gallery_result);
											$type=$gallery_row['type'];
											$type_arr=explode("/",$type);
											if($type_arr[0]=="image")
											{
												?>
												<div class="<?php if($num_rows=="1"){ ?> col-md-12 <?php }else { ?>col-md-4 <?php } ?>" style="padding:5px;border:1px solid gray;cursor:pointer;margin-bottom:10px;">
													<image src="<?php echo base_url.$gallery_row['file']; ?>" class="img-responsive" style="width:100%;display:inline;">
												</div>
											<?php
											}
											else if($type_arr[0]=="video")
											{
												?>
												<div class="<?php if($num_rows=="1"){ ?> col-md-12 <?php }else { ?>col-md-4 <?php } ?>" style="padding:5px;border:1px solid gray;cursor:pointer;margin-bottom:10px;">
													<video controls controlsList="nodownload"  style="width:100%;" autoplay="false">
														<source src="<?php echo base_url.$gallery_row['file']; ?>" type="video/<?php echo $type_arr[1]; ?>">
													</video>
												</div>
												<?php
											}
										}
									}
								}
							?>
						</div>
					</div>
					<?php
						/*feelings assessment*/
								$data_feeling="Like";
								$dquery="SELECT COUNT(id) as total FROM users_posts_activity WHERE post_id='$post_id' AND activity_id='Reacted' AND user_id!='".$_COOKIE['uid']."'";
								$dresult=mysqli_query($conn,$dquery);
								$drow=mysqli_fetch_array($dresult);
								$_other_reactions=$drow['total'];
								if(!isset($_other_reactions) || $_other_reactions<0)
								{
									$_other_reactions=0;
								}
								$liked=false;
								$dquery="SELECT id,data_feeling FROM users_posts_activity WHERE post_id='$post_id' AND activity_id='Reacted' AND user_id='".$_COOKIE['uid']."'";
								$dresult=mysqli_query($conn,$dquery);
								if(mysqli_num_rows($dresult)>0)
								{
									$liked=true;
									$drow=mysqli_fetch_array($dresult);
									$data_feeling=strtolower($drow['data_feeling']);
								}
							/*feelings assessment*/
					?>
					<div class="p-4 border-bottom osahan-post-footer">
						<div class="ru-reaction-box" data-post="<?php echo $post_id; ?>" style="margin-top: -10px !important;">
							<span class="like-btn" data-post="<?php echo $post_id; ?>">
								<span class="like-btn-emo <?php if($liked){ echo 'like-btn-'.strtolower($data_feeling); } else{ echo 'like-btn-default'; } ?>" data-post="<?php echo $post_id; ?>"></span> 
								<span class="like-btn-text <?php if($liked) { echo ' like-btn-text-thoughtful active';}  ?>"  data-post="<?php echo $post_id; ?>"><?php echo $data_feeling; ?></span> 
								<ul class="feelings-box" data-post="<?php echo $post_id; ?>"> 
									<li class="feeling feeling-like" data-post="<?php echo $post_id; ?>" data-feeling="Like"></li>
									<li class="feeling feeling-superlike" data-post="<?php echo $post_id; ?>" data-feeling="Superlike"></li>
									<li class="feeling feeling-thoughtful" data-post="<?php echo $post_id; ?>" data-feeling="Thoughtful"></li>
									<li class="feeling feeling-loved-it" data-post="<?php echo $post_id; ?>" data-feeling="Loved-It"></li>
									<li class="feeling feeling-describe-plz" data-post="<?php echo $post_id; ?>" data-feeling="Describe-Plz"></li>
									<li class="feeling feeling-bang-on" data-post="<?php echo $post_id; ?>" data-feeling="Bang-On"></li>
									<li class="feeling feeling-boring" data-post="<?php echo $post_id; ?>" data-feeling="Boring"></li>
								</ul>
							</span>
							<?php
								$comments_query="SELECT * FROM users_posts_comments WHERE post_id='$post_id' AND status=1 ORDER BY added ASC LIMIT 5";
								$comments_result=mysqli_query($conn,$comments_query);
								$comments_count=mysqli_num_rows($comments_result);
							
							?>
							<span class="like-stat"> 
								<span class="like-emo" data-post="<?php echo $post_id; ?>"> 
									<?php
										if($liked)
										{
											?>
											<span class="like-btn-<?php echo $data_feeling; ?>"></span>
											<?php
										}
									?>
								</span>
								
								<span class="like-details" data-post="<?php echo $post_id; ?>"><?php if($liked){ echo "You and "; } echo $_other_reactions." others reacted"; ?></span>
								<span class="like-details1" data-post="<?php echo $post_id; ?>">
									<a href="#" class="mr-3 text-secondary"><i class="feather-message-square"></i> <span id="data_post_comments_<?php echo $post_id; ?>"><?php echo $comments_count; ?></span></a>
									<a href="#" class="mr-3 text-secondary"><i class="feather-share-2"></i> 0</a>
								</span>
							</span>
						</div>
						<!--<a href="#" class="mr-3 text-secondary"><i class="feather-heart text-danger"></i> 16</a>-->
					</div>
					<div class="commentssec" id="post_comments_data_<?php echo $post_id; ?>">
						<?php
							if($comments_count>0)
							{
								while($comments_row=mysqli_fetch_array($comments_result))
								{
									$_active_query="SELECT * FROM users_logs WHERE user_id='".$comments_row['user_id']."'";
									$_active_res=mysqli_query($conn,$_active_query);
									$_active_row=mysqli_fetch_array($_active_res);
									$_active_status="bg-success";
									if($_active_row['is_active']=="0")
									{
										$_active_status="bg-danger";
									}
									
									$u_query="SELECT first_name,last_name,username FROM users WHERE id='".$comments_row['user_id']."'";
									$u_result=mysqli_query($conn,$u_query);
									$u_row=mysqli_fetch_array($u_result);
									$ex_query="SELECT * FROM comments_reports WHERE user_id='".$_COOKIE['uid']."' AND comment_id='".$comments_row['id']."'";
									$ex_result=mysqli_query($conn,$ex_query);
									if(mysqli_num_rows($ex_result)<=0)
									{
										?>
										<div class="p-3 d-flex align-items-top border-bottom osahan-post-comment" id="user_comments_container_number_<?php echo $comments_row['id']; ?>">
											<div class="dropdown-list-image mr-3">
												<a href="<?php echo base_url; ?>u/<?php echo $u_row['username']; ?>" style="text-decoration:none;">
													<img class="rounded-circle img-nf" style="border:1px solid #eaebec; !important;" src="<?php echo getUserProfileImage($comments_row['user_id']); ?>" alt="<?php echo $u_row['first_name']." ".$u_row['last_name']; ?>">
													<div class="status-indicator <?php echo $_active_status; ?>"></div>
												</a>
											</div>
											<div class="font-weight-bold" style="width:100% !important">
												<div class="text-truncate"><a href="<?php echo base_url; ?>u/<?php echo $u_row['username']; ?>" style="text-decoration:none;"> <?php echo $u_row['first_name']." ".$u_row['last_name']; ?></a> <span class="float-right small"><?php  echo date("d-m-Y",strtotime($comments_row['added'])); echo " - "; echo date("h:i a",strtotime($comments_row['added'])); ?></span></div>
												<div class="row">
													<div class="col-md-11">
														<div class="row">
															<div class="col-md-12">
																<div class="small text-gray-500" id="comment_text_section_<?php echo $comments_row['id']; ?>"><?php echo $comments_row['comment_text']; ?></div>
																<div class="row" style="padding:10px;">
																	<div class="col-md-12">
																		<?php
																			
																			$comment_id=$comments_row['id'];
																			/*feelings assessment*/
																				$data_feeling="Like";
																				$dquery="SELECT COUNT(id) as total FROM users_comments_activity WHERE comment_id='$comment_id' AND activity_id='Reacted' AND user_id!='".$_COOKIE['uid']."'";
																				$dresult=mysqli_query($conn,$dquery);
																				$drow=mysqli_fetch_array($dresult);
																				$_other_reactions=$drow['total'];
																				if(!isset($_other_reactions) || $_other_reactions<0)
																				{
																					$_other_reactions=0;
																				}
																				$liked=false;
																				$dquery="SELECT id,data_feeling FROM users_comments_activity WHERE comment_id='$comment_id' AND activity_id='Reacted' AND user_id='".$_COOKIE['uid']."'";
																				$dresult=mysqli_query($conn,$dquery);
																				if(mysqli_num_rows($dresult)>0)
																				{
																					$liked=true;
																					$drow=mysqli_fetch_array($dresult);
																					$data_feeling=strtolower($drow['data_feeling']);
																				}
																			/*feelings assessment*/
																		?>
																		<div class="ru-reaction-box-comment" data-comment="<?php echo $comment_id; ?>" style="margin-top: -10px !important;">
																			<span class="like-btn" data-comment="<?php echo $comment_id; ?>">
																				<span class="like-btn-emo <?php if($liked){ echo 'like-btn-'.strtolower($data_feeling); } else{ echo 'like-btn-default'; } ?>" data-comment="<?php echo $comment_id; ?>"></span> 
																				<span class="like-btn-text <?php if($liked) { echo ' like-btn-text-thoughtful active';}  ?>"  data-comment="<?php echo $comment_id; ?>"><?php echo $data_feeling; ?></span> 
																				<ul class="feelings-box" data-comment="<?php echo $comment_id; ?>"> 
																					<li class="feeling feeling-like" data-comment="<?php echo $comment_id; ?>" data-feeling="Like"></li>
																					<li class="feeling feeling-superlike" data-comment="<?php echo $comment_id; ?>" data-feeling="Superlike"></li>
																					<li class="feeling feeling-thoughtful" data-comment="<?php echo $comment_id; ?>" data-feeling="Thoughtful"></li>
																					<li class="feeling feeling-loved-it" data-comment="<?php echo $comment_id; ?>" data-feeling="Loved-It"></li>
																					<li class="feeling feeling-describe-plz" data-comment="<?php echo $comment_id; ?>" data-feeling="Describe-Plz"></li>
																					<li class="feeling feeling-bang-on" data-comment="<?php echo $comment_id; ?>" data-feeling="Bang-On"></li>
																					<li class="feeling feeling-boring" data-comment="<?php echo $comment_id; ?>" data-feeling="Boring"></li>
																				</ul>
																			</span>
																			<span class="like-stat"> 
																				<span class="like-emo" data-comment="<?php echo $comment_id; ?>"> 
																					<?php
																						if($liked)
																						{
																							?>
																							<span class="like-btn-<?php echo $data_feeling; ?>"></span>
																							<?php
																						}
																					?>
																				</span>
																				
																				<span class="like-details" data-comment="<?php echo $comment_id; ?>"><?php if($liked){ echo "You and "; } echo $_other_reactions." others reacted"; ?></span>
																				
																			</span>
																		</div>
																	</div>
																</div>
																
															</div>
															<?php
																if($comments_row['user_id']==$_COOKIE['uid'])
																{
																	
																	?>
																	<div class="col-md-12" id="edit_comment_text_<?php echo $comments_row['id']; ?>" style="display:none;margin-top:10px;margin-bottom:10px;">
																		<div class="row">
																			<div class="col-md-10">
																				<textarea rows="3" name="edit_text_input_area<?php echo $comments_row['id']; ?>" id="edit_text_input_area<?php echo $comments_row['id']; ?>" class="form-control" style="resize:none;"><?php echo $comments_row['comment_text']; ?></textarea>
																			</div>	
																			<div class="col-md-2">
																				<button type="button" class="btn btn-success pull-right" onclick="updateEditedComment('<?php echo $comments_row['id']; ?>');">Update</button>
																				<button type="button" class="btn btn-danger pull-right" onclick="$('#edit_comment_text_<?php echo $comments_row['id']; ?>').hide();" style="margin-top:15px;">Close</button>
																			</div>
																		</div>
																	</div>
																	<?php
																}
															?>
														</div>
													</div>
													<div class="col-md-1">
														<div class="dropdown ml-auto" style="max-width:50px;min-width:50px;">
															<a class="dropdown-toggle" href="javascript:void(0);" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
															<ul class="dropdown-menu" style="max-width:100px;min-width:80px;margin-left:-20px;">
																<?php
																	if($comments_row['user_id']==$_COOKIE['uid'])
																	{
																?>
																<li style="max-width:100px;min-width:80px;text-align:center;padding-bottom: 4px;margin-bottom: 5px;" onclick="editComment('<?php echo $comments_row['id']; ?>');"><a href="javascript:void(0);">Edit</a></li>
																<li style="max-width:100px;min-width:80px;text-align:center;border-top: 2px solid gray;padding-bottom: 4px;margin-bottom: 5px;" onclick="deleteComment('<?php echo $comments_row['id']; ?>');"><a href="javascript:void(0);">Delete</a></li>
																<?php
																	}
																	else
																	{
																		?>
																		<li style="max-width:100px;min-width:80px;text-align:center;" onclick="reportComment('<?php echo $comments_row['id']; ?>','hide','comment');"><a href="javascript:void(0);">Hide</a></li>
																		<li style="max-width:100px;min-width:80px;text-align:center;border-top: 2px solid gray;" onclick="reportComment('<?php echo $comments_row['id']; ?>');"><a href="javascript:void(0);">Report</a></li>
																		<?php
																	}
																?>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
								}
							}
						?>
						
					</div>
					
					<div class="p-3 d-flex align-items-center w-100">
						<div class="w-100">
							<textarea placeholder="Post a comment" data-img="<?php echo getUserProfileImage($_COOKIE['uid']); ?>" data-uid="<?php echo $_COOKIE['uid']; ?>" data-name="<?php echo $user_row['first_name']." ".$user_row['last_name']; ?>" id="comment_text_<?php echo $post_id; ?>" class="form-control border-0 p-0 shadow-none" rows="1" style="resize:none;"></textarea>
						</div>
						<div class="ml-3">
							<button class="btn btn-primary btn-sm" type="button" onclick="addComment('<?php echo $post_id; ?>');">Send</button>
						</div>
					</div>
					<div class="p-3 border-top osahan-slider-main auto-w">
						<div class="quote_slider" id="osahan-slider">
							<div class="osahan-slider-item" style="width:100px !important;max-width:227px !important;">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Love that broadcast!!');" class="btn btn-light btn-sm mr-1">Love that broadcast!!</button>
							</div>
							<div class="osahan-slider-item">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Congratulations!!');" class="btn btn-light btn-sm mr-1">Congratulations!!</button>
							</div>
							<div class="osahan-slider-item">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Excited!!');" class="btn btn-light btn-sm mr-1">Excited!!</button>
							</div>
							<div class="osahan-slider-item">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Thanks a milion for helping out');" class="btn btn-light btn-sm mr-1">Thanks a milion for helping out</button>
							</div>
							<div class="osahan-slider-item">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Whats it about?');" class="btn btn-light btn-sm mr-1">Whats it about?</button>
							</div>
							<div class="osahan-slider-item">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Oooo Great Wow');" class="btn btn-light btn-sm mr-1">Oooo Great Wow</button>
							</div>
							<div class="osahan-slider-item">
								<button type="button" onclick="addComment('<?php echo $post_id; ?>','Curious');" class="btn btn-light btn-sm mr-1">Curious</button>
							</div>
						</div>
					</div>
					<?php
						$is_public=$posts_row['is_public'];
						$is_private=$posts_row['is_private'];
						$is_protected=$posts_row['is_protected'];
						$is_friendly_protected=$posts_row['is_friendly_protected'];
						$is_magic=$posts_row['is_magic'];
						$who_can_see_selected=$is_public.','.$is_private.','.$is_protected.','.$is_friendly_protected.','.$is_magic;
					?>
					<div class="modal fade do_post_modal_<?php echo $post_id; ?>" id="do_post_modal_<?php echo $post_id; ?>" style="z-index:99999 !important;" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="do_post_modal_static_<?php echo $post_id; ?>" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="" method="post" id="media_form">
									<input type="hidden" name="edit_post_id" value="<?php echo $post_id ?>" id="edit_post_id_<?php echo $post_id; ?>">
									<input type="file" data-idslug="_<?php echo $post_id; ?>" class="image_selector_<?php echo $post_id; ?>" id="media_image_selector_<?php echo $post_id; ?>" name="image_selector[]" multiple="multiple" accept="image/*" style="display:none;">
									<input type="file" data-idslug="_<?php echo $post_id; ?>" class="video_selector_<?php echo $post_id; ?>" id="media_video_selector_<?php echo $post_id; ?>" name="video_selector[]" multiple="multiple" accept="video/*" style="display:none;">
									<input type="file" data-idslug="_<?php echo $post_id; ?>" class="image_selector_<?php echo $post_id; ?>" id="image_selector_<?php echo $post_id; ?>" name="image_selector[]" multiple="multiple" accept="image/*" style="display:none;">
									<input type="file" data-idslug="_<?php echo $post_id; ?>" class="video_selector_<?php echo $post_id; ?>" id="video_selector_<?php echo $post_id; ?>" name="video_selector[]" multiple="multiple" accept="video/*" style="display:none;">
								</form>
								
								<div class="row post_modal_section_<?php echo $post_id; ?>" style="padding:0px;margin:0px;" id="create_post_modal_section_<?php echo $post_id; ?>">
									<div class="col-md-12" style="padding:0px;margin:0px;">
										<div class="modal-header w-100">
											<div class="row w-100">
												<div class="col-md-8">
													<h5 class="modal-title" id="do_post_modal_static_<?php echo $post_id; ?>">Create Post </h5>
												</div>
												<div class="col-md-4">
													<button onclick="resetPost('_<?php echo $post_id; ?>');" data-dismiss="modal" style="border-color:none !important;float:right !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-times"></i></button>
												</div>
											</div>
										</div>
										<div class="modal-body" style="padding:0px;">
											<?php
												$user_profile_query="SELECT * FROM users WHERE id='".$_COOKIE['uid']."'";
												$user_profile_result=mysqli_query($conn,$user_profile_query);
												$user_profile_data=mysqli_fetch_array($user_profile_result);
											?>
											<div class="w-100">
												<div class="row w-100" style="padding:0px;margin:0px;">
													<div class="col-md-2" style="padding:10px;">
														<div class="dropdown-list-image mr-3" style="width:50px;height:50px;">
															<img class="rounded-circle" src="<?php echo getUserProfileImage($_COOKIE['uid']); ?>" alt="User Profile Picture"  style="border:1px solid #eaebec !important;height:50px;width:50px;">
															<div class="status-indicator bg-success"></div>
														</div>
													</div>
													<div class="col-md-10" style="padding:10px;">
														<h6><?php
															echo ucwords(strtolower($user_profile_data['first_name'].' '.$user_profile_data['last_name']));
														?></h6>
														<button type="button" class="btn btn-default" style="padding:0px;" onclick="$('#mode_modal_section_<?php echo $post_id; ?>').show();$('#create_post_modal_section_<?php echo $post_id; ?>').hide();" id="mode_<?php echo $post_id; ?>" data-val="<?php echo $who_can_see_selected; ?>"><?php echo conn_selec_text_arr($who_can_see_selected); ?></button>
													</div>
													<div class="col-md-12" id="textContent_<?php echo $post_id; ?>" style="min-height:180px !important;max-height:350px !important;overflow-y:auto;">
														<div class="row">
															<div class="col-md-12" style="padding:10px;max-height:170px;overflow-y:auto;">
																<textarea placeholder="Write your thoughts..." autofocus onkeyup="keyupFunction('_<?php echo $post_id; ?>');" style="resize:none;font-size:20px;" name="status_text" id="status_text_<?php echo $post_id; ?>" class="form-control border-0 p-0 shadow-none status_text" rows="5"><?php echo $posts_row['post_text']; ?></textarea>
															</div>
															<div class="col-md-12" style="padding:10px;max-height:170px;overflow-y:auto;" id="post_preview_section_<?php echo $post_id; ?>">
																<?php
																	$post_media_query="SELECT * FROM users_posts_media WHERE post_id='$post_id'";

																	$post_media_result=mysqli_query($conn,$post_media_query);
																	
																	$num_rows=mysqli_num_rows($post_media_result);
																	if($num_rows>0)
																	{
																		?>
																		<div class="row">
																		<?php
																		while($post_media_row=mysqli_fetch_array($post_media_result))
																		{
																			$media_id=$post_media_row['media_id'];
																			$gallery_query="SELECT * FROM gallery WHERE id='$media_id'";
																			$gallery_result=mysqli_query($conn,$gallery_query);
																			$num_rows=mysqli_num_rows($gallery_result);
																			if($num_rows>0)
																			{
																				$gallery_row=mysqli_fetch_array($gallery_result);
																				$type=$gallery_row['type'];
																				$type_arr=explode("/",$type);
																				if($type_arr[0]=="image")
																				{
																					?>
																					<div class="col-md-4" style="padding:5px;border:1px solid gray;cursor:pointer;min-height:200px;max-height:201px;margin-bottom:10px;">
																						<image src="<?php echo base_url.$gallery_row['file']; ?>" class="img-responsive" style="width:100%;min-height:190px;max-height:191px;display:inline;">
																					</div>
																				<?php
																				}
																				else if($type_arr[0]=="video")
																				{
																					?>
																					<div class="col-md-4" style="padding:5px;border:1px solid gray;cursor:pointer;margin-bottom:10px;">
																						<video controls nodownload  style="width:100%;min-height:190px;max-height:191px;" autoplay="false">
																							<source src="<?php echo base_url.$gallery_row['file']; ?>" type="video/<?php echo $type_arr[1]; ?>">
																						</video>
																					</div>
																					<?php
																				}
																			}
																		}
																		?>
																		</div>
																		<?php
																	}
																?>
															</div>
														</div>
													</div>
													<div class="col-md-12" style="padding:10px;border-top:1px solid #dee2e6">
														<div class="row">
															<div class="col-md-12">
																<button type="button" class="btn btn-secondary" onclick="$('#add_tags_modal_section_<?php echo $post_id; ?>').show();$('#create_post_modal_section_<?php echo $post_id; ?>').hide();"  title="Tag your friends" style="font-size:16px;border:2px solid gray;border-radius:50%;"><i class="fa fa-tag"></i></button>&nbsp;&nbsp;&nbsp;
																<button type="button" class="btn btn-primary" onclick="$('#image_selector_<?php echo $post_id; ?>').click();" title="Add photos" style="font-size:16px;border:2px solid gray;border-radius:50%;"><i class="fa fa-picture-o"></i></button>&nbsp;&nbsp;&nbsp;
																<button type="button" class="btn btn-warning" onclick="$('#video_selector_<?php echo $post_id; ?>').click();" title="Add videos" style="font-size:16px;border:2px solid gray;border-radius:50%;"><i class="fa fa-video-camera"></i></button>
																<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Words Count : <span id="word_count"><?php $words=explode(" ",$posts_row['post_text']);echo count($words); ?></span></span>
																<button type="button" class="btn btn-success" id="save_brodcast_post_<?php echo $post_id; ?>" onclick="statusTextKeyup('_<?php echo $post_id; ?>');" style="float:right;font-size:16px;;">Post</button>
															</div>
														</div>
													</div>
												</div>											
											</div>
										</div>
									</div>
								</div>
								<div class="row post_modal_section_<?php echo $post_id; ?>" style="padding:0px;margin:0px;display:none;" id="mode_modal_section_<?php echo $post_id; ?>">
									<div class="col-md-12" style="padding:0px;margin:0px;">
										<div class="modal-header w-100">
											<div class="row w-100">
												<div class="col-md-4">
													<button title="Back" onclick="$('#create_post_modal_section_<?php echo $post_id; ?>').show();$('#mode_modal_section_<?php echo $post_id; ?>').hide();" style="border-color:none !important;float:left !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-arrow-left"></i></button>
												</div>
												<div class="col-md-8">
													<h5 class="modal-title" id="do_post_modal_static">Who can see this?</h5>
												</div>
											</div>
										</div>
										<div class="modal-body" style="padding:0px;">
											<div class="w-100">
												<div class="row w-100" style="padding:0px;margin:0px;">
													<div class="col-md-12" id="tagContent" style="min-height:250px !important;max-height:400px !important;overflow-y:auto;">
														<div class="row">
															<div class="col-md-12" style="padding:10px;">
																<div class="row">
																	<div class="col-md-12" style="margin-bottom:20px;">
																		<select id="mode_seection_dropdown_<?php echo $post_id; ?>" onchange="connections_selection_div('_<?php echo $post_id; ?>');" name="mode_seection_dropdown" class="form-control">
																			<option value="1,0,0,0,0" <?php if($who_can_see_selected=="" || $who_can_see_selected=="1,0,0,0,0"){ echo 'selected'; } ?>>Anyone</option>
																			<option value="0,1,0,0,0" <?php if($who_can_see_selected=="0,1,0,0,0"){ echo 'selected'; } ?>>Only Me</option>
																			<option value="0,0,1,0,0" <?php if($who_can_see_selected=="0,0,1,0,0"){ echo 'selected'; } ?>>Only Connections</option>
																			<option value="0,0,1,1,0" <?php if($who_can_see_selected=="0,0,1,1,0"){ echo 'selected'; } ?>>Connections of Connections</option>
																			<option value="0,0,1,0,1" <?php if($who_can_see_selected=="0,0,1,0,1"){ echo 'selected'; } ?>>Allow Specific Connections</option>
																			<option value="0,0,1,0,2" <?php if($who_can_see_selected=="0,0,1,0,2"){ echo 'selected'; } ?>>Block Specific Connections</option>
																		</select>
																	</div>
																</div>
																<?php
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
																		<div class="row" id="connections_selection_div" style="<?php if($is_magic=='0') {  ?>display:none;<?php } ?>">
																		<?php
																		$allowed=false;
																		$allowed_arr=array();
																		if($is_magic==1)
																		{
																			$allowed=$posts_row['users_allowed'];
																		}
																		else if($is_magic==2)
																		{
																			$allowed=$posts_row['users_blocked'];
																		}
																		if($allowed!=false)
																		{
																			$allowed_arr=explode(",",$allowed);
																		}
																		while($bridge_row=mysqli_fetch_array($bridge_result))
																		{
																			$connect_user_id=$bridge_row['id'];
																			$tag_profile=getUserProfileImage($connect_user_id);
																			?>
																			<div class="col-md-12" style="margin-bottom:5px;font-size:16px;">
																				<input type="checkbox" <?php if(in_array($connect_user_id,$allowed_arr)){ echo 'checked'; } ?> name="mode_friends_<?php echo $post_id; ?>[]" class="mode_friends_<?php echo $post_id; ?>" value="<?php echo $connect_user_id; ?>" style="width:20px;height:20px;vertical-align:-6px;">&nbsp;&nbsp;
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
																		<h6 style="text-align:center;">There is no connections.</h6>
																		<?php
																	}
																?>
															</div>
														</div>
													</div>
												</div>											
											</div>
										</div>
									</div>
								</div>
								<div class="row post_modal_section_<?php echo $post_id; ?>" style="padding:0px;margin:0px;display:none;" id="add_tags_modal_section_<?php echo $post_id; ?>">
									<div class="col-md-12" style="padding:0px;margin:0px;">
										<div class="modal-header" style="width:100%;">
											<div class="row" style="width:100%;">
												<div class="col-md-4">
													<button title="Back" onclick="$('#create_post_modal_section_<?php echo $post_id; ?>').show();$('#add_tags_modal_section_<?php echo $post_id; ?>').hide();" style="border-color:none !important;float:left !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-arrow-left"></i></button>
												</div>
												<div class="col-md-8">
													<h5 class="modal-title" id="do_post_modal_static">Tag Connections </h5>
												</div>
											</div>
										</div>
										<div class="modal-body" style="padding:0px;">
											<div class="w-100">
												<div class="row" style="width:100%;padding:0px;margin:0px;">
													<div class="col-md-12" id="tagContent_<?php echo $post_id; ?>" style="min-height:250px !important;max-height:400px !important;overflow-y:auto;">
														<div class="row">
															<div class="col-md-12" style="padding:10px;">
																<?php
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
																		<div class="row">
																		<?php
																		while($bridge_row=mysqli_fetch_array($bridge_result))
																		{
																			$connect_user_id=$bridge_row['id'];
																			$tag_profile=getUserProfileImage($connect_user_id);
																			?>
																			<div class="col-md-12" style="margin-bottom:5px;font-size:16px;">
																				<input type="checkbox" name="tagged_friends_<?php echo $post_id; ?>[]" <?php if(in_array($connect_user_id,$already_tagged)){ echo 'checked'; } ?> class="tagged_friends_<?php echo $post_id; ?>" value="<?php echo $connect_user_id; ?>" style="width:20px;height:20px;vertical-align:-6px;">&nbsp;&nbsp;
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
																		<h6 style="text-align:center;">There is no connections to tag.</h6>
																		<?php
																	}
																?>
															</div>
														</div>
													</div>
												</div>											
											</div>
										</div>
									</div>
								</div>
								<div class="row post_modal_section_<?php echo $post_id; ?>" style="padding:0px;margin:0px;display:none;" id="offered_posting_as_blog_<?php echo $post_id; ?>">
									<div class="col-md-12" style="padding:0px;margin:0px;">
										<div class="modal-header" style="width:100%;">
											<div class="row" style="width:100%;">
												<div class="col-md-4">
													<button title="Back" onclick="$('#create_post_modal_section_<?php echo $post_id; ?>').show();$('#offered_posting_as_blog_<?php echo $post_id; ?>').hide();" style="border-color:none !important;float:left !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-arrow-left"></i></button>
												</div>
												<div class="col-md-8">
													<h6 class="modal-title">Whether you wish to post it as blog too? </h6>
												</div>
											</div>
										</div>
										<div class="modal-body" style="padding:0px;">
											<div class="w-100">
												<div class="row" style="width:100%;padding:0px;margin:0px;">
													<div class="col-md-12" id="offerBlogContent_<?php echo $post_id; ?>" style="min-height:150px !important;max-height:400px !important;overflow-y:auto;">
														<div class="row">
															<div class="col-md-12" style="padding:10px;">
																<div class="form-group">
																	<input type="radio" name="save_as_blog_too" onclick="$('#blog_title_section_<?php echo $post_id; ?>').show();" checked id="save_as_blog_too_yes_<?php echo $post_id; ?>" value="1">&nbsp;&nbsp;Yes&nbsp;&nbsp;
																	<input type="radio" name="save_as_blog_too" onclick="$('#blog_title_section_<?php echo $post_id; ?>').hide();" id="save_as_blog_too_no_<?php echo $post_id; ?>" value="0">&nbsp;&nbsp;No
																</div>
																<div class="form-group" id="blog_title_section">
																	<input type="text" name="blog_title" id="blog_title_<?php echo $post_id; ?>" placeholder="Write down the suitable blog post title" class="form-control">
																</div>
															</div>
														</div>
													</div>
												</div>											
											</div>
										</div>
									</div>
								</div>
							
							</div>
						</div>
					</div>
				</div>
				
				<?php
				}
			}
		}
	}
?>
<input type="hidden" name="post_count" value="<?php echo $post_count; ?>" id="post_count">							
<script type="text/javascript" src="<?php echo base_url; ?>js/feeling.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>js/feelingcomments.js"></script>
<script>
	(function($) {
		  "use strict"; // Start of use strict

		// Tooltip
		$('[data-toggle="tooltip"]').tooltip();

		// Osahan Slider
		$('.quote_slider').slick({
		  centerMode: true,
		  centerPadding: '30px',
		  slidesToShow: 3,
		  responsive: [
			{
			  breakpoint: 768,
			  settings: {
				arrows: false,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 1
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				arrows: false,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 1
			  }
			}
		  ]
		});

	})(jQuery);
</script>
