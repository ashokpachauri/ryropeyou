<?php
	include_once 'connection.php';
	include_once 'blog_space_functions.php';
	$response=array();
	$bsf=new BlogSpaceFunctions();
	if(isset($_POST['do_comment']))
	{
		$user_id=$_COOKIE['uid'];
		$blog_space_post_id=$_POST['blog_space_post_id'];
		$comment_text=trim($_POST['comment_text']);
		$comment_id=trim($_POST['comment_id']);
		if($comment_text!="")
		{
			$comment_type=$_POST['comment_type'];
			if($comment_type!='main' && $comment_type!='')
			{
				$comment_type='main';
				$comment_id='';
			}
			$insert_query="INSERT INTO users_blog_post_comments SET user_id='$user_id',blog_space_post_id='$blog_space_post_id',comment_text='$comment_text',comment_id='$comment_id',type='$comment_type',added=NOW()";
			if(mysqli_query($conn,$insert_query))
			{
				$insert_id=mysqli_insert_id($conn);
				$response['status']="success";
				$comments_data_query="SELECT * FROM users_blog_post_comments WHERE blog_space_post_id='$blog_space_post_id' AND status=1 AND type='main' ORDER BY id DESC";
				$comments_data_result=mysqli_query($bsf->dbconn,$comments_data_query);
				$__POST_COMMENTS_COUNT__=mysqli_num_rows($comments_data_result);
				$recieved_html="";
				if($__POST_COMMENTS_COUNT__>0)
				{
					$comments_count=mysqli_num_rows($comments_data_result);
					$recieved_html='<div class="padding-card reviews-card box shadow-sm rounded bg-white mb-3 border-0">
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
													<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
													<input type="text" name="comment_text" id="comment_text_0" placeholder="Write a comment..." class="form-control" required="">
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="control-group form-group">
												<div class="controls">
													<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_0" onclick="doComment(0);" class="btn btn-success">Send</button>
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
									<div class="col-md-11">
										<h5 class="mt-0">'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).' <small>'.date('M d Y',strtotime($comments_data_row['added'])).'</small></h5>
										<p>'.$comments_data_row['comment_text'].'</p>
									</div>';
									if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$comments_data_row['id']))
									{
										$recieved_html.='<div class="col-md-1">
											<a href="javascript:void(0);" class="pull-right text-danger" style="float:right;" title="Delete" onclick="deleteBlogPostComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
											<a href="javascript:void(0);" class="pull-right text-primary" style="float:right;" title="Edit" onclick="editBlogPostComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
										</div>
									';
									}
								$recieved_html.='</div>';
								$comment_id=$comments_data_row['id'];
								$sub_comments_query="SELECT * FROM users_blog_post_comments WHERE blog_space_post_id='$blog_space_post_id' AND comment_id='$comment_id' AND status=1 ORDER BY id ASC";
								$sub_comments_result=mysqli_query($bsf->dbconn,$sub_comments_query);
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
													<div class="col-md-11">
														<h5 class="mt-0">'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).' <small>'.date('M d Y',strtotime($comments_data_row['added'])).'</small></h5>
														<p>'.$sub_comments_row['comment_text'].'</p>
													</div>';
													if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$sub_comments_row['id']))
													{
														$recieved_html.='<div class="col-md-1">
															<a href="javascript:void(0);" title="Delete" class="pull-right text-danger"  style="float:right;" onclick="deleteBlogPostComment('.$sub_comments_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
															<a href="javascript:void(0);" title="Edit" class="pull-right text-primary"  style="float:right;margin-right:10px;" onclick="editBlogPostComment('.$sub_comments_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
														
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
																<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
																
																<input type="text" name="comment_text" id="comment_text_'.$sub_comment.'" placeholder="Reply..." class="form-control" required="">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="control-group form-group">
															<div class="controls">
																<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="'.$comment_id.'" data-ct="'.$comment_type.'" onclick="doComment('.$sub_comment.');" name="do_comment" id="do_comment_'.$sub_comment.'" class="btn btn-success">Send</button>
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
																<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
																
																<input type="text" name="comment_text" id="comment_text_'.$comment_id.'" placeholder="Reply..." class="form-control" required="">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="control-group form-group">
															<div class="controls">
																<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="'.$comment_id.'" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_'.$comment_id.'" onclick="doComment('.$comment_id.');" class="btn btn-success">Send</button>
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
													<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
													<input type="text" name="comment_text" id="comment_text_y" placeholder="Write a comment..." class="form-control" required="">
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="control-group form-group">
												<div class="controls">
													<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_y" onclick="doComment();" class="btn btn-success">Send</button>
												</div>
											</div>
										</div>
									</div>
								</div></div>';
						}
				}
				$response['recieved_html']=$recieved_html;
			}
			else
			{
				$response['status']="error";
				$response['message']="There is some issue please contact developer.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="Comment can not be blank";
		}
	}
	else if(isset($_POST['update_comment']))
	{
		$user_id=$_COOKIE['uid'];
		$blog_space_post_id=$_POST['blog_space_post_id'];
		$comment_text=trim($_POST['comment_text']);
		$comment_id=trim($_POST['comment_id']);
		$self_id=trim($_POST['self_id']);
		if($comment_text!="")
		{
			$comment_type=$_POST['comment_type'];
			if($comment_type!='main' && $comment_type!='')
			{
				$comment_type='main';
				$comment_id='';
			}
			$insert_query="UPDATE users_blog_post_comments SET comment_text='$comment_text' WHERE id='$self_id'";
			if(mysqli_query($conn,$insert_query))
			{
				$response['status']="success";
				$comments_data_query="SELECT * FROM users_blog_post_comments WHERE blog_space_post_id='$blog_space_post_id' AND status=1 AND type='main' ORDER BY id DESC";
				$comments_data_result=mysqli_query($bsf->dbconn,$comments_data_query);
				$__POST_COMMENTS_COUNT__=mysqli_num_rows($comments_data_result);
				$recieved_html="";
				if($__POST_COMMENTS_COUNT__>0)
				{
					$comments_count=mysqli_num_rows($comments_data_result);
					$recieved_html='<div class="padding-card reviews-card box shadow-sm rounded bg-white mb-3 border-0">
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
													<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
													<input type="text" name="comment_text" id="comment_text_0" placeholder="Write a comment..." class="form-control" required="">
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="control-group form-group">
												<div class="controls">
													<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_0" onclick="doComment(0);" class="btn btn-success">Send</button>
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
									<div class="col-md-11">
										<h5 class="mt-0">'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).' <small>'.date('M d Y',strtotime($comments_data_row['added'])).'</small></h5>
										<p>'.$comments_data_row['comment_text'].'</p>
									</div>';
									if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$comments_data_row['id']))
									{
										$recieved_html.='<div class="col-md-1">
											<a href="javascript:void(0);" class="pull-right text-danger" style="float:right;" title="Delete" onclick="deleteBlogPostComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
											<a href="javascript:void(0);" title="Edit" class="pull-right text-primary"  style="float:right;margin-right:10px;" onclick="editBlogPostComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
										</div>
									';
									}
								$recieved_html.='</div>';
								$comment_id=$comments_data_row['id'];
								$sub_comments_query="SELECT * FROM users_blog_post_comments WHERE blog_space_post_id='$blog_space_post_id' AND comment_id='$comment_id' AND status=1 ORDER BY id ASC";
								$sub_comments_result=mysqli_query($bsf->dbconn,$sub_comments_query);
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
													<div class="col-md-11">
														<h5 class="mt-0">'.ucwords(strtolower($comm_user['first_name'].' '.$comm_user['last_name'])).' <small>'.date('M d Y',strtotime($comments_data_row['added'])).'</small></h5>
														<p>'.$sub_comments_row['comment_text'].'</p>
													</div>';
													if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$sub_comments_row['id']))
													{
														$recieved_html.='<div class="col-md-1">
															<a href="javascript:void(0);" title="Delete" class="pull-right text-danger"  style="float:right;" onclick="deleteBlogPostComment('.$sub_comments_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
															<a href="javascript:void(0);" title="Edit" class="pull-right text-primary"  style="float:right;margin-right:10px;" onclick="editBlogPostComment('.$sub_comments_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
										
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
																<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
																
																<input type="text" name="comment_text" id="comment_text_'.$sub_comment.'" placeholder="Reply..." class="form-control" required="">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="control-group form-group">
															<div class="controls">
																<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="'.$comment_id.'" data-ct="'.$comment_type.'" onclick="doComment('.$sub_comment.');" name="do_comment" id="do_comment_'.$sub_comment.'" class="btn btn-success">Send</button>
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
																<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
																
																<input type="text" name="comment_text" id="comment_text_'.$comment_id.'" placeholder="Reply..." class="form-control" required="">
															</div>
														</div>
													</div>
													<div class="col-md-2">
														<div class="control-group form-group">
															<div class="controls">
																<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="'.$comment_id.'" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_'.$comment_id.'" onclick="doComment('.$comment_id.');" class="btn btn-success">Send</button>
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
													<input type="hidden" name="blog_space_post_id" value="'.$blog_space_post_id.'">
													<input type="text" name="comment_text" id="comment_text_y" placeholder="Write a comment..." class="form-control" required="">
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="control-group form-group">
												<div class="controls">
													<button type="button" data-bspid="'.$blog_space_post_id.'" data-cid="" data-ct="'.$comment_type.'" name="do_comment" id="do_comment_y" onclick="doComment();" class="btn btn-success">Send</button>
												</div>
											</div>
										</div>
									</div>
								</div></div>';
						}
				}
				$response['recieved_html']=$recieved_html;
			}
			else
			{
				$response['status']="error";
				$response['message']="There is some issue please contact developer.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="Comment can not be blank";
		}
	}
	echo json_encode($response);
?>