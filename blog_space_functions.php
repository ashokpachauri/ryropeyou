<?php
	include_once 'dbconnection.php';
	class BlogSpaceFunctions extends DBConnection{
		public $database;
		public $dbconn;
		public function __construct()
		{
			$this->database=new DBConnection();
			$this->dbconn=$this->database->DBConnect();
		}
		public function changeConstantsToPHP($constants_array,$html_string)
		{
			foreach($constants_array as $key=>$val)
			{
				$html_string=str_replace($key, $val, $html_string);
			}
			return $html_string;
		}
		public function doUcWords($string="")
		{
			return ucwords(strtolower($string));
		}
		public function fetchUser($user_id)
		{
			$_user_query="SELECT * FROM users WHERE id='$user_id'";
			$_user_result=mysqli_query($this->dbconn,$_user_query);
			if(mysqli_num_rows($_user_result)>0)
			{
				$user_row=mysqli_fetch_array($_user_result);
				return $user_row;
			}
			else
			{
				return false;
			}
		}
		public function userImage($user_id)
		{
			$_query="SELECT media_id FROM users_profile_pics WHERE user_id='$user_id' AND status=1";
			$_result=mysqli_query($this->dbconn,$_query);
			$_profile="";
			if(mysqli_num_rows($_result)>0)
			{
				$_row=mysqli_fetch_array($_result);
				$media_id=$_row['media_id'];
				$media_query="SELECT * FROM gallery WHERE id='$media_id'";
				$media_result=mysqli_query($this->dbconn,$media_query);
				if(mysqli_num_rows($media_result)>0)
				{
					$media_row=mysqli_fetch_array($media_result);
					$_profile=$media_row['file'];
				}
			}
			if (strpos($_profile, 'http') !== false) {
				
			}
			else
			{
				if($_profile=="")
				{
					$_user_query="SELECT first_name FROM users WHERE id='$user_id'";
					$_user_result=mysqli_query($this->dbconn,$_user_query);
					if(mysqli_num_rows($_user_result)>0)
					{
						$user_row=mysqli_fetch_array($_user_result);
						$_profile=base_url."alphas/".strtolower(substr($user_row['first_name'],0,1)).".png";
					}
					else
					{
						$_profile="default.png";
						$_profile=base_url."uploads/".$_profile;
					}
				}
				else
				{
					$_profile=base_url.$_profile;
				}
			}
			return $_profile;
		}
		public function blogPostImage($image="")
		{
			if($image=="")
			{
				return blog_post_image;
			}
			else
			{
				return base_url.$image;
			}
		}
		public function blogImage($image="")
		{
			if($image=="")
			{
				return blog_image;
			}
			else
			{
				return base_url.$image;
			}
		}
		public function fetchBlogSpace($blog_space_id)
		{
			$blog_space_query="SELECT * FROM blog_spaces WHERE id='$blog_space_id'";
			$blog_space_result=mysqli_query($this->dbconn,$blog_space_query);
			if(mysqli_num_rows($blog_space_result)>0)
			{
				return mysqli_fetch_array($blog_space_result);
			}
			else
			{
				return false;
			}
		}
		public function fetchBlogSpacePost($blog_space_post_id)
		{
			$blog_space_post_query="SELECT * FROM blog_space_posts WHERE id='$blog_space_post_id'";
			$blog_space_post_result=mysqli_query($this->dbconn,$blog_space_post_query);
			if(mysqli_num_rows($blog_space_post_result)>0)
			{
				return mysqli_fetch_array($blog_space_post_result);
			}
			else
			{
				return false;
			}
		}
		public function fetchBlogSpaceCategory($blog_space_category_id)
		{
			$blog_space_category_query="SELECT * FROM blog_space_categories WHERE id='$blog_space_category_id'";
			$blog_space_category_result=mysqli_query($this->dbconn,$blog_space_category_query);
			if(mysqli_num_rows($blog_space_category_result)>0)
			{
				return mysqli_fetch_array($blog_space_category_result);
			}
			else{
				return false;
			}
		}
		public function blogSpacePostMaxHTML($blog_space_post_id)
		{
			$html_type="'max'";
			$html_remove="'reload'";
			$html='<div class="blog-card padding-card box shadow-sm rounded bg-white mb-3 border-0" id="blog_post_'.$blog_space_post_id.'">
						<img class="card-img-top" src="__POST_IMAGE_SRC__" alt="__POST_IMAGE_ALT__">
						<div class="card-body">
							<h2>__POST_TITLE__</h2>
							<span class="badge badge-success" style="padding:10px;">__CATEGORY__</span>
							<h6 class="mb-3 mt-1"><a href="__AUTHOR_URL__" title="__AUTHOR_NAME__"><img class="rounded-circle" src="__AUTHOR_SRC__" style="height:25px;width:25px;" alt="__AUTHOR_ALT__"></a>
								<strong>&nbsp;<a href="__AUTHOR_URL__" title="__AUTHOR_NAME__">__AUTHOR_NAME__</a></strong>&nbsp;<i class="feather-calendar"></i> __POST_DATE__ / __POST_COMMENTS_COUNT__ Comments</h6>
							<p>
								__MAX_POST_CONTENT__
							</p>
						</div>
						<div class="card-footer border-0">
							<div class="footer-social text-right" style="font-size:14px;"><span>Share : </span>
								<a href="javascript:void(0);" title="Share with facebook" onclick="shareUrlOnFB(__POST_URL_ENCODED__);"><i class="feather-facebook text-primary"></i></a>&nbsp;&nbsp;
								<a href="__TWITTER_SHARE_URL__" target="_blank" title="Share with twitter"><i class="feather-twitter text-secondary"></i></a>&nbsp;&nbsp;
								<!--<a href="__INSTAGRAM_SHARE_URL__" target="_blank" rel="noopener" title="Share with instagram"><i class="feather-instagram text-warning"></i></a>&nbsp;&nbsp;-->';
								if(isLogin() && isDeserving($_COOKIE['uid'],$blog_space_post_id))
								{
									$html.='<a href="__EDIT_POST_URL__" title="Edit"><i class="feather-edit text-primary"></i></a>&nbsp;&nbsp;
									<a href="javascript:void(0);" data-url="__BLOG_SPACE_URL__" id="blog_delete_'.$blog_space_post_id.'" onclick="deleteBlogPost('.$blog_space_post_id.','.$html_type.','.$html_remove.');" title="Delete"><i class="feather-trash text-danger"></i></a>&nbsp;&nbsp;';
							
								}
								$html.='
							</div>
						</div>
					</div>';
					return $html;
		}
		public function blogSpacePostMiniHTML($blog_space_post_id)
		{
			$html_type="'mini'";
			$html_remove="'remove'";
			$html='<div class="box shadow-sm rounded bg-white mb-3 blog-card border-0" id="blog_post_'.$blog_space_post_id.'">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<a href="__POST_URL__"><img class="card-img-top" src="__POST_IMAGE_SRC__" style="height:120px;" alt="__POST_IMAGE_ALT__"></a>
						</div>
						<div class="col-md-8">
							<h6 class="text-dark"><a href="__POST_URL__">__POST_TITLE__</a></h6>
							<span class="badge badge-success" style="padding:10px;margin-bottom:10px;">__CATEGORY__</span>
							<p class="mb-0">__MINI_POST_CONTENT__ <a href="__POST_URL__" style="margin-top:20px;">...Read More</a></p>
						</div>
					</div>
				</div>
				<div class="card-footer border-0">
					<div class="row">
						<div class="col-md-6">
							<p class="mb-0">
								<a href="__AUTHOR_URL__" title="__AUTHOR_NAME__"><img class="rounded-circle" src="__AUTHOR_SRC__" style="height:25px;width:25px;" alt="__AUTHOR_ALT__"></a>
								<strong>&nbsp;<a href="__AUTHOR_URL__" title="__AUTHOR_NAME__">__AUTHOR_NAME__</a></strong> On __POST_DATE__
							</p>
						</div>
						<div class="col-md-6">
							<div class="footer-social text-right" style="font-size:14px;">
								<a href="__POST_URL__" class="text-danger">__POST_READ_COUNT__ Read</a>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="javascript:void(0);" title="Share with facebook" onclick="shareUrlOnFB(__POST_URL_ENCODED__);"><i class="feather-facebook text-primary"></i></a>&nbsp;&nbsp;
								<a href="__TWITTER_SHARE_URL__" target="_blank" title="Share with twitter"><i class="feather-twitter text-secondary"></i></a>&nbsp;&nbsp;
								<!--<a href="__INSTAGRAM_SHARE_URL__" target="_blank" rel="noopener" title="Share with instagram"><i class="feather-instagram text-warning"></i></a>&nbsp;&nbsp;-->';
								if(isLogin() && isDeserving($_COOKIE['uid'],$blog_space_post_id))
								{
									$html.='<a href="__EDIT_POST_URL__" title="Edit"><i class="feather-edit text-primary"></i></a>&nbsp;&nbsp;
									<a href="javascript:void(0);" data-url="__BLOG_SPACE_URL__" id="blog_delete_'.$blog_space_post_id.'" onclick="deleteBlogPost('.$blog_space_post_id.','.$html_type.','.$html_remove.');" title="Delete"><i class="feather-trash text-danger"></i></a>&nbsp;&nbsp;';
							
								}
							$html.='</div>
						</div>
					</div>
				</div>
			</div>';
			return $html;
		}
		public function blogPostNothingFound()
		{
			return '<div class="box shadow-sm rounded bg-white mb-3 blog-card border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<h6 style="text-align:center;margin-top:75px;margin-bottom:75px;">There is nothing to show</h6>
						</div>
					</div>
				</div>
			</div>';
		}
		public function blogSpacePostMini($blog_space_post_id="")
		{
			if($blog_space_post_id=="")
			{
				return "";
			}
			else
			{
				$blog_post_row=$this->fetchBlogSpacePost($blog_space_post_id);
				if($blog_post_row)
				{
					$blog_space_id=$blog_post_row['blog_space_id'];
					/*---------------------Get Blog Space Stuff---------------*/
						$blog_space_row=$this->fetchBlogSpace($blog_space_id);
						$blog_space_category_id=$blog_space_row['category'];
						$user_id=$blog_space_row['user_id'];
						$blog_space_category_row=$this->fetchBlogSpaceCategory($blog_space_category_id);
						$user_row=$this->fetchUser($user_id);
					/*---------------------Get Blog Space Stuff---------------*/
					$blog_space_post_mini_html=$this->blogSpacePostMiniHTML($blog_space_post_id);
					$space_url=$blog_space_row['space_url'];
					$post_url=$blog_post_row['post_url'];
					$__POST_URL__=blog_space_url.$space_url.'/'.$post_url;
					$__POST_IMAGE_SRC__=$this->blogPostImage($blog_post_row['post_image']);
					$__POST_IMAGE_ALT__=$blog_post_row['title'];
					$__POST_TITLE__=$blog_post_row['title'];
					$__CATEGORY__=$blog_space_category_row['title'].' / '.$blog_space_row['title'];
					$__MINI_POST_CONTENT__=substr($blog_post_row['description'],0,100);
					$__AUTHOR_SRC__=$this->userImage($blog_space_row['user_id']);
					$__AUTHOR_ALT__=$this->doUcWords($user_row['first_name'].' '.$user_row['last_name']);
					$__AUTHOR_NAME__=$this->doUcWords($user_row['first_name'].' '.$user_row['last_name']);
					$__AUTHOR_URL__=strtolower(base_url.'u/'.$user_row['username']);
					$__POST_DATE__=date("M d,Y",strtotime($blog_post_row['created']));
					$str_len=strlen($blog_post_row['description']);
					$__POST_READ_COUNT__=ceil($str_len/1000);
					$__POST_URL_ENCODED__=$__POST_URL__;
					$__TWITTER_SHARE_URL__=$this->database->twitter_share."text=".urlencode($__POST_TITLE__)."&url=".urlencode($__POST_URL__)."&hashtags=".$blog_post_row['seo_tags'];
					$__INSTAGRAM_SHARE_URL__=$this->database->instagram_share.$__POST_URL__;
					$__EDIT_POST_URL__=$__POST_URL__.'/edit';
					$__POST_ID__=$blog_post_row['id'];
					$__BLOG_SPACE_URL__=blog_space_url.$space_url;
					$constants_array=array(
						"__POST_URL__"=>$__POST_URL__,
						"__POST_ID__"=>$__POST_ID__,
						"__POST_IMAGE_SRC__"=>$__POST_IMAGE_SRC__,
						"__POST_IMAGE_ALT__"=>$__POST_IMAGE_ALT__,
						"__POST_TITLE__"=>$__POST_TITLE__,
						"__CATEGORY__"=>$__CATEGORY__,
						"__MINI_POST_CONTENT__"=>$__MINI_POST_CONTENT__,
						"__AUTHOR_SRC__"=>$__AUTHOR_SRC__,
						"__AUTHOR_ALT__"=>$__AUTHOR_ALT__,
						"__AUTHOR_NAME__"=>$__AUTHOR_NAME__,
						"__AUTHOR_URL__"=>$__AUTHOR_URL__,
						"__POST_DATE__"=>$__POST_DATE__,
						"__POST_READ_COUNT__"=>$__POST_READ_COUNT__." minute(s)",
						"__POST_URL_ENCODED__"=>"'".$__POST_URL_ENCODED__."'",
						"__TWITTER_SHARE_URL__"=>$__TWITTER_SHARE_URL__,
						"__INSTAGRAM_SHARE_URL__"=>$__INSTAGRAM_SHARE_URL__,
						"__EDIT_POST_URL__"=>$__EDIT_POST_URL__,
						"__BLOG_SPACE_URL__"=>$__BLOG_SPACE_URL__
					);
					//return $blog_space_post_mini_html;
					return $this->changeConstantsToPHP($constants_array,$blog_space_post_mini_html);
				}
				else
				{
					return "";
				}
			}
		}
		public function blogSpacePostMax($blog_space_post_id="")
		{
			if($blog_space_post_id=="")
			{
				return "";
			}
			else
			{
				$blog_post_row=$this->fetchBlogSpacePost($blog_space_post_id);
				if($blog_post_row)
				{
					$blog_space_id=$blog_post_row['blog_space_id'];
					/*---------------------Get Blog Space Stuff---------------*/
						$blog_space_row=$this->fetchBlogSpace($blog_space_id);
						$blog_space_category_id=$blog_space_row['category'];
						$user_id=$blog_space_row['user_id'];
						$blog_space_category_row=$this->fetchBlogSpaceCategory($blog_space_category_id);
						$user_row=$this->fetchUser($user_id);
					/*---------------------Get Blog Space Stuff---------------*/
					$blog_space_post_max_html=$this->blogSpacePostMaxHTML($blog_space_post_id);
					$space_url=$blog_space_row['space_url'];
					$post_url=$blog_post_row['post_url'];
					$__POST_URL__=blog_space_url.$space_url.'/'.$post_url;
					$__POST_IMAGE_SRC__=$this->blogPostImage($blog_post_row['post_image']);
					$__POST_IMAGE_ALT__=$blog_post_row['title'];
					$__POST_TITLE__=$blog_post_row['title'];
					$__CATEGORY__=$blog_space_category_row['title'].' / '.$blog_space_row['title'];
					$__MAX_POST_CONTENT__=$blog_post_row['description'];
					$__AUTHOR_SRC__=$this->userImage($blog_space_row['user_id']);
					$__AUTHOR_ALT__=$this->doUcWords($user_row['first_name'].' '.$user_row['last_name']);
					$__AUTHOR_NAME__=$this->doUcWords($user_row['first_name'].' '.$user_row['last_name']);
					$__AUTHOR_URL__=strtolower(base_url.'u/'.$user_row['username']);
					$__POST_DATE__=date("M d,Y",strtotime($blog_post_row['created']));
					$__POST_URL_ENCODED__=$__POST_URL__;
					$__TWITTER_SHARE_URL__=$this->database->twitter_share."text=".urlencode($__POST_TITLE__)."&url=".urlencode($__POST_URL__)."&hashtags=".$blog_post_row['seo_tags'];
					$__INSTAGRAM_SHARE_URL__=$this->database->instagram_share.$__POST_URL__;
					$__POST_ID__=$blog_post_row['id'];
					$__EDIT_POST_URL__=$__POST_URL__.'/edit';
					$__BLOG_SPACE_URL__=blog_space_url.$space_url;
					
					$comments_data_query="SELECT * FROM users_blog_post_comments WHERE blog_space_post_id='$blog_space_post_id' AND status=1 AND type='main' ORDER BY id DESC";
					$comments_data_result=mysqli_query($this->dbconn,$comments_data_query);
					$__POST_COMMENTS_COUNT__=mysqli_num_rows($comments_data_result);
					$constants_array=array(
						"__POST_URL__"=>$__POST_URL__,
						"__EDIT_POST_URL__"=>$__EDIT_POST_URL__,
						"__POST_ID__"=>$__POST_ID__,
						"__POST_IMAGE_SRC__"=>$__POST_IMAGE_SRC__,
						"__POST_IMAGE_ALT__"=>$__POST_IMAGE_ALT__,
						"__POST_TITLE__"=>$__POST_TITLE__,
						"__CATEGORY__"=>$__CATEGORY__,
						"__MAX_POST_CONTENT__"=>$__MAX_POST_CONTENT__,
						"__AUTHOR_SRC__"=>$__AUTHOR_SRC__,
						"__AUTHOR_ALT__"=>$__AUTHOR_ALT__,
						"__AUTHOR_NAME__"=>$__AUTHOR_NAME__,
						"__AUTHOR_URL__"=>$__AUTHOR_URL__,
						"__POST_DATE__"=>$__POST_DATE__,
						"__POST_COMMENTS_COUNT__"=>$__POST_COMMENTS_COUNT__,
						"__POST_URL_ENCODED__"=>"'".$__POST_URL_ENCODED__."'",
						"__TWITTER_SHARE_URL__"=>$__TWITTER_SHARE_URL__,
						"__INSTAGRAM_SHARE_URL__"=>$__INSTAGRAM_SHARE_URL__,
						"__BLOG_SPACE_URL__"=>$__BLOG_SPACE_URL__
					);
					$recieved_html= $this->changeConstantsToPHP($constants_array,$blog_space_post_max_html);
					$recieved_html.='<div class="row"><div class="col-md-12" id="comment_data">';
					if($__POST_COMMENTS_COUNT__>0)
					{
						$comments_count=mysqli_num_rows($comments_data_result);
						$recieved_html.='<div class="padding-card reviews-card box shadow-sm rounded bg-white mb-3 border-0">
						<div class="card-body">
							<h5 class="card-title mb-4">'.$comments_count.' comments</h5>';
							if(isLogin())
							{
								$c_user_data=$this->fetchUser($_COOKIE['uid']);
								$user_image=$this->userImage($_COOKIE['uid']);
								$c_user_profile=$this->base_url.'u/'.$c_user_data['username'];
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
							$comm_user=$this->fetchUser($comm_user_id);
							$comm_user_image=$this->userImage($comm_user_id);
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
												<a href="javascript:void(0);" class="pull-right text-danger" style="float:right;" title="Delete" onclick="deleteBlogPostComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-trash"></i></a>
												<a href="javascript:void(0);" class="pull-right text-primary" style="float:right;margin-right:10px;" title="Edit" onclick="editBlogPostComment('.$comments_data_row['id'].','.$comment_type.');"><i class="fa fa-pencil"></i></a>
											</div>
										';
										}
									$recieved_html.='</div>';
									$comment_id=$comments_data_row['id'];
									$sub_comments_query="SELECT * FROM users_blog_post_comments WHERE blog_space_post_id='$blog_space_post_id' AND comment_id='$comment_id' AND status=1 ORDER BY id ASC";
									$sub_comments_result=mysqli_query($this->dbconn,$sub_comments_query);
									$num_rows=mysqli_num_rows($sub_comments_result);
									if($num_rows>0)
									{
										while($sub_comments_row=mysqli_fetch_array($sub_comments_result))
										{
											$sub_comment=$sub_comments_row['id'];
											$comm_user_id=$sub_comments_row['user_id'];
											$comm_user=$this->fetchUser($comm_user_id);
											$comm_user_image=$this->userImage($comm_user_id);
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
											$c_user_data=$this->fetchUser($_COOKIE['uid']);
											$user_image=$this->userImage($_COOKIE['uid']);
											$c_user_profile=$this->base_url.'u/'.$c_user_data['username'];
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
											$c_user_data=$this->fetchUser($_COOKIE['uid']);
											$user_image=$this->userImage($_COOKIE['uid']);
											$c_user_profile=$this->base_url.'u/'.$c_user_data['username'];
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
								$c_user_data=$this->fetchUser($_COOKIE['uid']);
								$user_image=$this->userImage($_COOKIE['uid']);
								$c_user_profile=$this->base_url.'u/'.$c_user_data['username'];
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
					$recieved_html.="</div></div>";
					return $recieved_html;
				}
				else
				{
					return "";
				}
			}
		}
	}
?>