<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head_without_session.php'; ?>
		<?php
			$file_key=false;
			if(isset($_REQUEST['file_key']) && $_REQUEST['file_key']!="")
			{
				$file_key=$_REQUEST['file_key'];
			}
			$crystal_clear_row=false;
			$query="SELECT * FROM videos WHERE md5(id)='".$file_key."'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$crystal_clear_row=mysqli_fetch_array($result);
			}
			else{
				include_once '404.php';
				die();
			}
		?>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="<?php echo $crystal_clear_row['title']; ?> | RopeYou">
		<meta name="title" content="<?php echo $crystal_clear_row['title']; ?>">
		<meta name="keywords" content="<?php echo $crystal_clear_row['title']; ?>">
		<meta name="description" content="<?php echo substr($crystal_clear_row['description'],0,160); ?>">
		
		<!----Facebook---------------------->
		<meta property="og:title" content="<?php echo $crystal_clear_row['title']; ?>">
		<meta property="og:description" content="<?php echo substr($crystal_clear_row['description'],0,160); ?>">
		<meta property="og:image" content="<?php echo base_url.'uploads/'.$crystal_clear_row['cover_image']; ?>">
		<meta property="og:url" content="<?php echo base_url.'streaming.php?file_key='.md5($crystal_clear_row['id']); ?>">
		
		<!----------Twitter-------------------------->
		<meta property="twitter:title" content="<?php echo $crystal_clear_rowcrystal_clear_row['title']; ?>">
		<meta property="twitter:description" content="<?php echo substr($crystal_clear_row['description'],0,160); ?>">
		<meta property="twitter:image" content="<?php echo base_url.'uploads/'.$crystal_clear_row['cover_image']; ?>">
		<meta property="twitter:card" content="<?php echo base_url.'uploads/'.$crystal_clear_row['cover_image']; ?>">
		<meta property="twitter:url" content="<?php echo base_url.'streaming.php?file_key='.md5($crystal_clear_row['id']); ?>">
		
		<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
		<title>Videos | RopeYou Connects</title>
	</head>
	<link href="<?php echo base_url; ?>/logo-demo.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>/logo-style.css" rel="stylesheet">
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-8">
						<div class="blog-card padding-card box shadow-sm rounded bg-white mb-3 border-0">
							<?php
								if($file_key!=false)
								{
									$crystal_clear_row=false;
									$query="SELECT * FROM videos WHERE md5(id)='".$file_key."' AND in_appropriate=0";
									$result=mysqli_query($conn,$query);
									if(mysqli_num_rows($result)>0)
									{
										include_once 'blog_space_functions.php';
										$bsf=new BlogSpaceFunctions();
										$crystal_url=base_url.'streaming.php?file_key='.$file_key;
										$crystal_clear_row=mysqli_fetch_array($result);
										$user_id=$crystal_clear_row['user_id'];
										$user_data=getUsersData($user_id);
										$crystal_clear_id=$crystal_clear_row['id'];
										?>
										<div class="video-wrapper card-img-top">
											<video class="card-img-top" poster="<?php echo base_url.'img/logo.png' ?>" id="video_streamor" alt="Loading Stream..." nodownload style="background-color:#ddd;padding:5px;">
												<source src="uploads/blank.mp4" type="video/mp4">
											</video>
											<div class="video-description" style="left:83%;top:-85%;">
												<a href="javascript:void(0);">
													<img class="img-responsive" style="width:90px;" src="<?php echo base_url; ?>img/logo.png" alt="Logo" scale="0">
												</a>
											</div>
										</div>
										<div class="row" style="padding:10px;">
											<div class="col-md-3">
												<button id="video_streamor_triger" data-init="0" data-file="<?php echo base64_encode($crystal_clear_row['file']); ?>" title="Play" class="btn btn-danger"><i class="fa fa-play-circle" style="font-size:16px;"></i></button>&nbsp;&nbsp;
												<button id="video_streamor_triger_pause" class="btn btn-danger"><i class="fa fa-pause-circle" title="Pause" style="font-size:16px;"></i></button>
											</div>
											<div class="col-md-9">
												<div class="row">
													<div class="col-md-10">
														<div id="custom-seekbar" style="margin-top:12px;">
															<span></span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="row">
															<div class="col-md-6">
																<a class="font-weight-bold d-block text-center social_icon_temp" href="javascript:void(0);" onclick="shareUrlOnFB('<?php echo $crystal_url ?>');" style="font-size:20px;"><i class="fa fa-facebook"></i></a>
															</div>
															<div class="col-md-6">
																<a class="font-weight-bold d-block text-center social_icon_temp" href="https://twitter.com/intent/tweet?text=<?php echo urlencode($crystal_clear_row['title']) ?>&url=<?php echo urlencode($crystal_url); ?>&hashtags=<?php echo urlencode($crystal_clear_row['video_tags']) ?>" target="_blank" style="font-size:20px;background-color:#00b7d6 !important;"><i class="fa fa-twitter"></i></a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
											$comments_data_query="SELECT * FROM users_videos_comments WHERE video_id='$crystal_clear_id' AND status=1 AND type='main' ORDER BY id DESC";
											$comments_data_result=mysqli_query($conn,$comments_data_query);
											$__POST_COMMENTS_COUNT__=mysqli_num_rows($comments_data_result);
										?>
										<div class="card-body">
											<span class="badge badge-success">Video CV</span>
											<h3 style="width:100%;"><?php echo ucwords(strtolower($user_data['first_name'].' '.$user_data['last_name'])); ?> <?php if($crystal_clear_row['user_id']!=$_COOKIE['uid']){ ?><a href="javscript:void(0);" onclick="reportVideo('<?php echo $crystal_clear_id; ?>');" class="btn btn-danger pull-right" style="float:right;">Report Video</a> <?php } ?></h3>
											<h6 class="mb-3"><i class="feather-calendar"></i> <?php echo date('M d, Y',strtotime($crystal_clear_row['added'])); ?> / <?php echo $__POST_COMMENTS_COUNT__; ?> Comments</h6>
											<p>
												<?php echo $crystal_clear_row['description']; ?>
											</p>
										</div>
										<div class="modal fade report_video_modal" id="report_video_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="report_video_modalBackdrop" aria-hidden="true">
											<div class="modal-dialog modal-md" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h6 class="modal-title" id="report_video_modalBackdrop">Report Video</h6>
													</div>
													<div class="modal-body" style="padding:0px;margin:0px;">											
														<div class="row" style="padding:0px;margin:0px;">
															<div class="col-md-12" style="box-shadow: 0 0 0 1px rgba(0,0,0,.15), 0 2px 3px rgba(0,0,0,.2);transition: box-shadow 83ms;background:#fff;padding:10px;margin: 0px;border-radius:2px;font-size:15px;line-height:30px;">
																<form action="" method="post">
																	<input type="hidden" required name="reporting_video_id" id="reporting_video_id" value="<?php echo $crystal_clear_id; ?>">
																	<input type="radio" required name="issue_type" value="fake" checked>&nbsp;&nbsp;I think this is fake.<br/>
																	<input type="radio" required name="issue_type" value="spam">&nbsp;&nbsp;I think this is spam.<br/>
																	<input type="radio" required name="issue_type" value="scam">&nbsp;&nbsp;I think this is malware, scam or phishing.<br/>
																	<input type="radio" required name="issue_type" value="misinformation">&nbsp;&nbsp;I think this is false information.<br/>
																	<input type="radio" required name="issue_type" value="offensive">&nbsp;&nbsp;I think topic or language is offensive.<br/>
																	<input type="radio" required name="issue_type" value="nudity">&nbsp;&nbsp;Nudity, sexual scenes or language, prostitution or sex trafficking.<br/>
																	<input type="radio" required name="issue_type" value="violence">&nbsp;&nbsp;Torture, rape or abuse, terrorist act or recruitment for terrorism.<br/>
																	<input type="radio" required name="issue_type" value="threat">&nbsp;&nbsp;Personal attack or threatening language.<br/>
																	<input type="radio" required name="issue_type" value="hatespeech">&nbsp;&nbsp;Racit, sexist, hateful language.<br/>
																	<input type="radio" required name="issue_type" value="copyright">&nbsp;&nbsp;Defamation, Trademark or copyright violation.<br>
																	<div class="row" style="padding:0px;margin:0px;">
																		<div class="col-md-12"  style="padding:0px;margin:0px;padding-top:20px;">
																			<button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
																			<button type="button" onclick="saveVideoReport();" class="btn btn-success pull-right">Submit</button>
																		</div>
																	</div>													
																</form>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal fade edit_comment" id="edit_comment_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit_comment_modalBackdrop" aria-hidden="true">
											<div class="modal-dialog modal-md" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h6 class="modal-title" id="edit_comment_modalBackdrop" style="width:100%;">Edit Comment<button class="pull-right btn btn-danger" data-dismiss="modal" style="float:right !important;"><i class="fa fa-times"></i></button></h6>
													</div>
													<div class="modal-body">											
														<div class="row">
															<div class="col-md-12">
																<textarea id="edit_comment_text" style="resize:none;" rows="3" name="comment_text" class="form-control"></textarea>
															</div>
															<div class="col-md-12" style="margin-top:20px;">
																<button class="btn btn-success pull-right" data-bspid="" data-cid="" data-ct="" onclick="UpdateVideoComment();" id="update_comment" type="button" name="" id="">Update</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
									else
									{
										$file_key=false;
									}
								}
							?>
						</div>
						<?php
						if($file_key!=false)
						{
							$recieved_html= "";
							$recieved_html.='<div class="row"><div class="col-md-12" id="comment_data">';
							if($__POST_COMMENTS_COUNT__>0)
							{
								$comments_count=mysqli_num_rows($comments_data_result);
								$recieved_html.='<div class="padding-card reviews-card box shadow-sm rounded bg-white mb-3 border-0">
								<div class="card-body">
									<h5 class="card-title mb-4">'.$comments_count.' comments</h5>';
									if(isLogin())
									{
										$c_user_data=$bsf->fetchUser($_COOKIE['uid']);
										$user_image=$bsf->userImage($_COOKIE['uid']);
										$c_user_profile=$bsf->base_url.'u/'.$c_user_data['username'];
										$comment_type="main";
										$recieved_html.='
												<div class="row">
													<div class="col-md-1">
														<p class="mb-0">
															<a href="'.$c_user_profile.'" title="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"><img class="rounded-circle" src="'.$user_image.'" style="height:25px;width:25px;" alt="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"></a>
														</p>
													</div>
													<div class="col-md-9">
														<div class="control-group form-group">
															<div class="controls">
																<input type="hidden" name="comment_type" value="'.$comment_type.'">
																<input type="hidden" name="user_id" value="'.$_COOKIE['uid'].'">
																<input type="hidden" name="comment_id" value="">
																<input type="hidden" name="video_id" value="'.$crystal_clear_id.'">
																<input type="text" name="comment_text" id="comment_text_0" placeholder="Write a comment..." class="form-control" required="">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="control-group form-group">
															<div class="controls">
																<button type="button" data-bspid="'.$crystal_clear_id.'" data-cid="" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_0" onclick="doVideoComment(0);" class="btn btn-success">Send</button>
															</div>
														</div>
													</div>
												</div>
											';
									}
								while($comments_data_row=mysqli_fetch_array($comments_data_result))
								{
									$comm_user_id=$comments_data_row['user_id'];
									$comm_user=$bsf->fetchUser($comm_user_id);
									$comm_user_image=$bsf->userImage($comm_user_id);
									$comment_type="'main'";
									$recieved_html.='<div class="media mb-4" id="comment_main_'.$comments_data_row['id'].'">
										<img class="d-flex mr-3 rounded" style="width:25px;height:25px;" src="'.$comm_user_image.'" alt="'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).'">
										<div class="media-body">
											<div class="row">
												<div class="col-md-10">
													<h5 class="mt-0">'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).' <small>'.date('M d Y',strtotime($comments_data_row['added'])).'</small></h5>
													<p>'.$comments_data_row['comment_text'].'</p>
												</div>';
												if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$comments_data_row['id']))
												{
													$recieved_html.='<div class="col-md-2">
														<a href="javascript:void(0);" class="pull-right text-danger" style="float:right;" title="Delete" onclick="deleteVideoComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
														<a href="javascript:void(0);" class="pull-right text-primary" style="float:right;margin-right:10px;" title="Edit" onclick="editVideoComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
													</div>
												';
												}
											$recieved_html.='</div>';
											$comment_id=$comments_data_row['id'];
											$sub_comments_query="SELECT * FROM users_videos_comments WHERE video_id='$crystal_clear_id' AND comment_id='$comment_id' AND status=1 ORDER BY id ASC";
											$sub_comments_result=mysqli_query($conn,$sub_comments_query);
											$num_rows=mysqli_num_rows($sub_comments_result);
											if($num_rows>0)
											{
												while($sub_comments_row=mysqli_fetch_array($sub_comments_result))
												{
													$sub_comment=$sub_comments_row['id'];
													$comm_user_id=$sub_comments_row['user_id'];
													$comm_user=$bsf->fetchUser($comm_user_id);
													$comm_user_image=$bsf->userImage($comm_user_id);
													$comment_type="'child'";
													$recieved_html.='<div class="media mt-4" id="comment_child_'.$sub_comments_row['id'].'">
														<img class="d-flex mr-3 rounded" style="width:25px;height:25px;" title="'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).'" src="'.$comm_user_image.'" alt="'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).'">
														<div class="media-body">
															<div class="row">
																<div class="col-md-10">
																	<h5 class="mt-0">'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).' <small>'.date('M d Y',strtotime($comments_data_row['added'])).'</small></h5>
																	<p>'.$sub_comments_row['comment_text'].'</p>
																</div>';
																if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$sub_comments_row['id']))
																{
																	$recieved_html.='<div class="col-md-2">
																		<a href="javascript:void(0);" title="Delete" class="pull-right text-danger"  style="float:right;" onclick="deleteVideoComment('.$sub_comments_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
																		<a href="javascript:void(0);" title="Edit" class="pull-right text-primary"  style="float:right;margin-right:10px;" onclick="editVideoComment('.$sub_comments_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
																	</div>';
																}
															$recieved_html.='</div>
														</div>
													</div>';
												}
												if(isLogin())
												{
													$c_user_data=$bsf->fetchUser($_COOKIE['uid']);
													$user_image=$bsf->userImage($_COOKIE['uid']);
													$c_user_profile=$bsf->base_url.'u/'.$c_user_data['username'];
													$comment_type="";
													$recieved_html.='
															<div class="row">
																<div class="col-md-1">
																	<p class="mb-0">
																		<a href="'.$c_user_profile.'" title="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"><img class="rounded-circle" src="'.$user_image.'" style="height:25px;width:25px;" alt="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"></a>
																	</p>
																</div>
																<div class="col-md-9">
																	<div class="control-group form-group">
																		<div class="controls">
																			<input type="hidden" name="comment_type" value="'.$comment_type.'">
																			<input type="hidden" name="user_id" value="'.$_COOKIE['uid'].'">
																			<input type="hidden" name="comment_id" value="'.$comment_id.'">
																			<input type="hidden" name="video_id" value="'.$crystal_clear_id.'">
																			
																			<input type="text" name="comment_text" id="comment_text_'.$sub_comment.'" placeholder="Reply..." class="form-control" required="">
																		</div>
																	</div>
																</div>
																<div class="col-md-2">
																	<div class="control-group form-group">
																		<div class="controls">
																			<button type="button" data-bspid="'.$crystal_clear_id.'" data-cid="'.$comment_id.'" data-ct="'.$comment_type.'" onclick="doVideoComment('.$sub_comment.');" name="do_comment" id="do_comment_'.$sub_comment.'" class="btn btn-success">Send</button>
																		</div>
																	</div>
																</div>
															</div>
														';
												}
											}
											else
											{
												if(isLogin())
												{
													$c_user_data=$bsf->fetchUser($_COOKIE['uid']);
													$user_image=$bsf->userImage($_COOKIE['uid']);
													$c_user_profile=$bsf->base_url.'u/'.$c_user_data['username'];
													$comment_type="";
													$recieved_html.='<div class="media mt-4">
															<div class="media-body">
															
															<div class="row">
																<div class="col-md-1">
																	<p class="mb-0">
																		<a href="'.$c_user_profile.'" title="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"><img class="rounded-circle" src="'.$user_image.'" style="height:25px;width:25px;" alt="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"></a>
																	</p>
																</div>
																<div class="col-md-9">
																	<div class="control-group form-group">
																		<div class="controls">
																			<input type="hidden" name="comment_type" value="'.$comment_type.'">
																			<input type="hidden" name="user_id" value="'.$_COOKIE['uid'].'">
																			<input type="hidden" name="comment_id" value="'.$comment_id.'">
																			<input type="hidden" name="video_id" value="'.$crystal_clear_id.'">
																			
																			<input type="text" name="comment_text" id="comment_text_'.$comment_id.'" placeholder="Reply..." class="form-control" required="">
																		</div>
																	</div>
																</div>
																<div class="col-md-2">
																	<div class="control-group form-group">
																		<div class="controls">
																			<button type="button" data-bspid="'.$crystal_clear_id.'" data-cid="'.$comment_id.'" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_'.$comment_id.'" onclick="doVideoComment('.$comment_id.');" class="btn btn-success">Send</button>
																		</div>
																	</div>
																</div>
															</div>
														</div></div>';
												}
											}
										$recieved_html.='</div>
									</div>';
								}
								$recieved_html.='</div></div>';
							}
							else
							{
								if(isLogin())
								{
										$c_user_data=$bsf->fetchUser($_COOKIE['uid']);
										$user_image=$bsf->userImage($_COOKIE['uid']);
										$c_user_profile=$bsf->base_url.'u/'.$c_user_data['username'];
										$comment_type="main";
										$recieved_html.='<div class="padding-card reviews-card box shadow-sm rounded bg-white mb-3 border-0">
										<div class="card-body">
												<div class="row">
													<div class="col-md-1">
														<p class="mb-0">
															<a href="'.$c_user_profile.'" title="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"><img class="rounded-circle" src="'.$user_image.'" style="height:25px;width:25px;" alt="'.ucwords(strtolower($c_user_data['first_name'].' '.$c_user_data['last_name'])).'"></a>
														</p>
													</div>
													<div class="col-md-9">
														<div class="control-group form-group">
															<div class="controls">
																<input type="hidden" name="comment_type" id="comment_type_y" value="'.$comment_type.'">
																<input type="hidden" name="user_id" value="'.$_COOKIE['uid'].'">
																<input type="hidden" name="comment_id" value="">
																<input type="hidden" name="video_id" value="'.$crystal_clear_id.'">
																<input type="text" name="comment_text" id="comment_text_y" placeholder="Write a comment..." class="form-control" required="">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="control-group form-group">
															<div class="controls">
																<button type="button" data-bspid="'.$crystal_clear_id.'" data-cid="" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_y" onclick="doVideoComment();" class="btn btn-success">Send</button>
															</div>
														</div>
													</div>
												</div>
											</div></div>';
									}
							}
							$recieved_html.="</div></div>";
							echo $recieved_html; 
						}
						?>
					</div>
					<div class="col-lg-4 col-md-4">
						<div class="sidebar-card box shadow-sm rounded bg-white mb-3 border-0">
							<div class="card-body">
								<h5 class="card-title mb-4">Search</h5>
								<div class="input-group">
									<input class="form-control" placeholder="Type and hit enter" aria-label="Recipient's username" aria-describedby="basic-addon2" type="text">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="button"><i class="feather-arrow-right"></i></button>
									</div>
								</div>
							</div>
						</div>
						<div class="sidebar-card box shadow-sm rounded bg-white mb-3 border-0">
							<div class="card-body">
								<h5 class="card-title mb-4">Similar Videos</h5>
								<?php
									$reported_videos=array();
									$reported_video_query="SELECT * FROM video_reports WHERE user_id='".$_COOKIE['uid']."'";
									$reported_video_result=mysqli_query($conn,$reported_video_query);
									if(mysqli_num_rows($reported_video_result)>0)
									{
										while($r_v_row=mysqli_fetch_array($reported_video_result))
										{
											$reported_videos[]=$r_v_row['video_id'];
										}
									}
									$video_query="SELECT * FROM videos WHERE md5(id)!='".$file_key."' AND id NOT IN ('".implode("','",$reported_videos)."') AND status=1 AND in_appropriate=0 LIMIT 0,9";
									if($crystal_clear_row!=false)
									{
										$video_query="SELECT * FROM videos WHERE md5(id)!='".$file_key."' AND category='".$crystal_clear_row['category']."' AND status=1 AND in_appropriate=0 LIMIT 9";
									}
									$counter=0;
									$video_result=mysqli_query($conn,$video_query);
									if(mysqli_num_rows($video_result)>0)
									{
										while($video_row=mysqli_fetch_array($video_result))
										{
											$video_users_data=getUsersData($video_row['user_id']);
											?>
											<div class="sidebar-card box shadow-sm rounded bg-white mb-1 border-0">
												<div class="card-body" style="padding:0px;">
													<div class="row">
														<div class="col-md-4" style="padding:0px;">
															<a href="<?php echo base_url.'streaming.php?file_key='.md5($video_row['id']); ?>">
																<img src="<?php echo base_url.$video_row['cover_image']; ?>" class="card-img-top" style="height:115px;">
															</a>
														</div>
														<div class="col-md-8" style="padding:10px;">
															<h5 style="font-size:12px;"><a href="<?php echo base_url.'streaming.php?file_key='.md5($video_row['id']); ?>"><?php echo $video_row['title']; ?></a></h5>
															<h6 style="font-size:10px;"><?php echo getUsersLink($video_users_data['username'],$video_users_data['first_name'],$video_users_data['last_name']); ?></h6>
															<h6  class="mb-1" style="font-size:12px;" ><i class="feather-calendar"></i> <?php echo date('M d, Y',strtotime($video_row['added'])); ?></h6>
														</div>
													</div>
												</div>
											</div>
											<?php
										}
									}
									else
									{
										?>
										<div class="sidebar-card box shadow-sm rounded bg-white mb-3 border-0">
											<p style="font-size:14px;text-align:center;">There are no videos.</p>
										</div>
										<?php
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
   </body>
</html>
<!--<script src="//vjs.zencdn.net/7.8.2/video.min.js"></script>-->
<script>
	var base_url="<?php echo base_url; ?>";
	function reportVideo(video_id="",issue_type="")
	{
		if(issue_type=="")
		{
			$("#reporting_video_id").val(video_id);
			$("#report_video_modal").modal("show");
		}
		else
		{
			saveVideoReport(video_id,issue_type);
		}
	}
	function editVideoComment(comment_id="",comment_type="")
	{
		if(comment_id!="" && comment_type!="")
		{
			$.ajax({
				url:base_url+'get-video-comment-data',
				type:'post',
				data:{comment_id:comment_id,comment_type:comment_type},
				success:function(response)
				{
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						var ct="main";
						if(comment_type=="child" || comment_type=="")
						{
							ct="";
						}
						$("#edit_comment_text").val(parsedJson.comment_text);
						
						$("#update_comment").data("bspid",parsedJson.video_id);
						$("#update_comment").data("cid",comment_id);
						$("#update_comment").data("ct",ct);
						$("#edit_comment_modal").modal('show');
					}
					else
					{
						alert(parsedJson.message);
					}
				}
			});
		}
		else
		{
			alert('There is some issue please try reloading.');
		}
	}
	function deleteVideoComment(comment_id="",comment_type="")
	{
		if(comment_id!="" && comment_type!="")
		{
			$.ajax({
				url:base_url+'delete-video-comment',
				type:'post',
				data:{comment_id:comment_id,comment_type:comment_type},
				success:function(response){
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						var c_id=parsedJson.c_id;
						var c_type=parsedJson.c_type;
						$("#comment_"+c_type+"_"+c_id).remove();
					}
					else
					{
						alert(parsedJson.message);
					}
				}
			});
		}
		else
		{
			alert('something is not righ.Please try reloading the page.');
		}
	}
	function doVideoComment(cid=false)
	{
		$(".form-control").css({"outline":"none"});
		if(cid===false)
		{
			cid="y";
		}
		if($("#comment_text_"+cid).val()!="")
		{
			var video_id=$("#do_comment_"+cid).data('bspid');
			var comment_id=$("#do_comment_"+cid).data('cid');
			var comment_type=$("#do_comment_"+cid).data('ct');
			var comment_text=$("#comment_text_"+cid).val();
			$.ajax({
				url:base_url+'do-video-comment',
				type:'post',
				data:{do_comment:1,video_id:video_id,comment_id:comment_id,comment_type:comment_type,comment_text:comment_text},
				success:function(response){
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						$("#comment_data").html(parsedJson.recieved_html);
					}
					else
					{
						alert(parsedJson.message);
					}
				}
			});
		}
		else
		{
			$("#comment_text_"+cid).css({"outline":"1px solid red"});
		}
	}
	function UpdateVideoComment()
	{
		$("#edit_comment_text").css({"outline":"none"});
		if($("#edit_comment_text").val()!="")
		{
			var video_id=$("#update_comment").data('bspid');
			var comment_id=$("#update_comment").data('cid');
			var comment_type=$("#update_comment").data('ct');
			var comment_text=$("#edit_comment_text").val();
			$.ajax({
				url:base_url+'do-video-comment',
				type:'post',
				data:{update_comment:1,video_id:video_id,self_id:comment_id,comment_id:comment_id,comment_type:comment_type,comment_text:comment_text},
				success:function(response){
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						$("#comment_data").html(parsedJson.recieved_html);
						$("#edit_comment_modal").modal('hide');
					}
					else
					{
						$("#edit_comment_modal").modal('hide');
						alert(parsedJson.message);
					}
				}
			});
		}
		else
		{
			$("#edit_comment_text").css({"outline":"1px solid red"});
		}
	}
	function saveVideoReport(video_id="",issue_type="")
	{
		if(video_id=="" && issue_type=="")
		{
			video_id=$("#reporting_video_id").val();
			if (!$("input[name='issue_type']:checked").val()) {
			  return false;
			}
			else
			{
				var issue_type=$("input[name='issue_type']:checked").val();
				$.ajax({
					url:base_url+'report-a-video',
					type:'post',
					data:{issue_type:issue_type,video_id:video_id},
					success:function(response){
						var parsedJson=JSON.parse(response)
						if(parsedJson.status=="success")
						{
							alert('This video has been reported & will no longer visible to you in feeds.');
						}
					}
				});
			}
		}
		else
		{
			$.ajax({
				url:base_url+'report-a-video',
				type:'post',
				data:{issue_type:issue_type,video_id:video_id},
				success:function(response){
					var parsedJson=JSON.parse(response)
					if(parsedJson.status=="success")
					{
						alert('This video has been reported & will no longer visible to you in feeds.');
					}
				}
			});
		}
	}
	var initialized=0;
	var percentage = 0;
	const seekable_video = document.querySelector('#video_streamor');
	seekable_video.addEventListener('timeupdate', (event) => {
	  percentage = ( seekable_video.currentTime / seekable_video.duration ) * 100;
	  $( '#custom-seekbar span' ).css( 'width', percentage + '%' )
	});
	/*seekable_video.ontimeupdate = function(){
	  
	}*/

	$('#custom-seekbar').on( 'click', function( e ){
	  var offset = $( this ).offset(),
		  left = ( e.pageX - offset.left ),
		  totalWidth = $( '#custom-seekbar' ).width(),
		  percentage = ( left / totalWidth );

		seekable_video.currentTime = seekable_video.duration * percentage;
	});
	function initialiazePlay(file_path,action)
	{
		$("#video_streamor").attr("src","ajax.videostream.php?file_key="+file_path+"&action="+action);
		$("#video_streamor_triger").data("init",1);
	}
	function doPlay()
	{
		//$("#video_streamor").trigger('pause');
		$("#video_streamor").trigger('play');
	}
	$("#video_streamor_triger").on("click",function(){
		initialized=$("#video_streamor_triger").data("init");
		var file_path=$("#video_streamor_triger").data("file");
		if(initialized=="1")
		{
			doPlay();
		}
		else
		{
			initialiazePlay(file_path,"stream");
			doPlay();
		}
	});
	$("#video_streamor_triger_pause").on("click",function(){
		//$("#video_streamor").attr("src","");
		$("#video_streamor").trigger('pause');
	});
	/*----SHARE URL ON SOCIALPLATFORMS----------------*/
	function shareUrlOnFB(url){
		FB.ui({
		  method: 'share',
		  href: url
		}, function(response){});
	}
	window.fbAsyncInit = function() {
		FB.init({
		  appId      : '<?php echo fb_app_id; ?>',
		  cookie     : true,   
		  xfbml      : true,  
		  version    : 'v5.0' 
		});
		
	};
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	  }(document, 'script', 'facebook-jssdk'));
</script>

<!--<script src='<?php echo base_url; ?>videojs/videojs.watermark.js'></script>-->
<script src="<?php echo base_url; ?>js/list.min.js"></script>
<script src="<?php echo base_url; ?>handlebars.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>capture/webcamjs/webcam.min.js"></script>