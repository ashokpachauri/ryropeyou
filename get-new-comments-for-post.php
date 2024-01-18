<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_REQUEST['post_id']) && $_REQUEST['post_id']!="")
	{
		$post_id=$_REQUEST['post_id'];
		$user_id=$_COOKIE['uid'];
		$html="";
		$comments_query="SELECT * FROM users_posts_comments WHERE post_id='$post_id' AND status=1 ORDER BY added ASC";
		$comments_result=mysqli_query($conn,$comments_query);
		$comments_count=mysqli_num_rows($comments_result);
		$response['comments_count']=$comments_count;
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
					$html.='<div class="p-3 d-flex align-items-top border-bottom osahan-post-comment" id="user_comments_container_number_'.$comments_row['id'].'">
						<div class="dropdown-list-image mr-3">
							<a href="'.base_url.'u/'.strtolower($u_row['username']).'" style="text-decoration:none;">
								<img class="rounded-circle img-nf" style="border:1px solid #eaebec; !important;" src="'.getUserProfileImage($comments_row['user_id']).'" alt="'.ucwords(strtolower($u_row['first_name']." ".$u_row['last_name'])).'">
								<div class="status-indicator '.$_active_status.'"></div>
							</a>
						</div>
						<div class="font-weight-bold" style="width:100% !important">
							<div class="text-truncate">
								<a href="'.base_url.'u/'.strtolower($u_row['username']).'" style="text-decoration:none;"> 
									'.ucwords(strtolower($u_row['first_name']." ".$u_row['last_name'])).'
								</a> 
								<span class="float-right small">
									'.date("M d Y",strtotime($comments_row['added'])).'
								</span>
							</div>
							<div class="row">
								<div class="col-md-11">
									<div class="row">
										<div class="col-md-12">
											<div class="small text-gray-500" id="comment_text_section_'.$comments_row['id'].'">'.$comments_row['comment_text'].'</div>
											<div class="row" style="padding:10px;">
												<div class="col-md-12">';
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
													$html.='<div class="row">
																<div class="col-md-12 col ru-reaction-box-parent">
																	<div class="ru-reaction-box-comment" data-comment="'.$comment_id.'">
																<span class="like-btn" data-comment="'.$comment_id.'">
																	<span class="like-btn-emo ';
																	if($liked){ $html.= 'like-btn-'.strtolower($data_feeling); } else{ $html.='like-btn-default'; } 
																	$html.='" data-comment="'.$comment_id.'"></span> 
																	<span class="like-btn-text ';
																	if($liked) { $html.= ' like-btn-text-thoughtful active';}  $html.='"  data-comment="'.$comment_id.'">'.$data_feeling.'</span> 
																	<ul class="feelings-box" data-comment="'.$comment_id.'"> 
																		<li class="feeling feeling-like" data-comment="'.$comment_id.'" data-feeling="Like"></li>
																		<li class="feeling feeling-superlike" data-comment="'.$comment_id.'>" data-feeling="Superlike"></li>
																		<li class="feeling feeling-thoughtful" data-comment="'.$comment_id.'" data-feeling="Thoughtful"></li>
																		<li class="feeling feeling-loved-it" data-comment="'.$comment_id.'" data-feeling="Loved-It"></li>
																		<li class="feeling feeling-describe-plz" data-comment="'.$comment_id.'" data-feeling="Describe-Plz"></li>
																		<li class="feeling feeling-bang-on" data-comment="'.$comment_id.'" data-feeling="Bang-On"></li>
																		<li class="feeling feeling-boring" data-comment="'.$comment_id.'" data-feeling="Boring"></li>
																	</ul>
																</span>
																<span class="like-stat"> 
																	<span class="like-emo" data-comment="'.$comment_id.'"> ';
																		if($liked)
																			{
																				
																				$html.='<span class="like-btn-'.$data_feeling.'"></span>';
																				
																			}
																	$html.='</span>
																	
																	<span class="like-details" data-comment="'.$comment_id.'">';
																	if($liked){ $html.= "You and "; } $html.= $_other_reactions." others reacted"; $html.='</span>
																	
																</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>';
										if($comments_row['user_id']==$_COOKIE['uid'])
										{
											
											$html.='<div class="col-md-12" id="edit_comment_text_'.$comments_row['id'].'" style="display:none;margin-top:10px;margin-bottom:10px;">
												<div class="row">
													<div class="col-md-10">
														<textarea rows="3" name="edit_text_input_area'.$comments_row['id'].'" id="edit_text_input_area'.$comments_row['id'].'" class="form-control" style="resize:none;">'.$comments_row['comment_text'].'</textarea>
													</div>	
													<div class="col-md-2">
														<button type="button" class="btn btn-success pull-right" onclick="updateEditedComment('.$comments_row['id'].');">Update</button>
														<button type="button" class="btn btn-danger pull-right" onclick="$(\'#edit_comment_text_'.$comments_row['id'].'\').hide();" style="margin-top:15px;">Close</button>
													</div>
												</div>
											</div>';
										}
									$html.='</div>
								</div>
								<div class="col-md-1">';
								$html.='<div class="btn-group ml-auto">
									<a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v"></i>														
									</a>
									<div class="dropdown-menu dropdown-menu-right" style="">';
										if($comments_row['user_id']==$_COOKIE['uid'])
											{
												$html.='<button class="dropdown-item" onclick="editComment('.$comments_row['id'].');" type="button"><i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit</button>
												<button class="dropdown-item" type="button" onclick="deleteComment('.$comments_row['id'].');"><i class="fa fa-times text-danger"></i>&nbsp;&nbsp;Delete</button>';
											
											}
											else
											{
												$html.='<button class="dropdown-item" onclick="reportComment(\"'.$comments_row['id'].'\",\"hide\",\"comment\");" type="button"><i class="fa fa-eye-slash"></i>&nbsp;&nbsp;Hide</button>
													<button class="dropdown-item" type="button" onclick="reportComment('.$comments_row['id'].');"><i class="fa fa-file text-danger"></i>&nbsp;&nbsp;Report</button>
												';
											}
									$html.='</div>
								</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}
			
		}
		$response['status']="success";
		$response['post_id']=$post_id;
		$response['html']=$html;
	}
	else
	{
		$response['status']="error";
		$response['message']="Parameter Post ID is Missing.";
	}
	echo json_encode($response);
?>