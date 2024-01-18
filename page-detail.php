<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head_without_session.php'; ?>
		<?php
			if(isset($_POST['update']))
			{
				$email_verified=true;
				$do_register=filter_var($_POST['do_register'], FILTER_SANITIZE_STRING);
				$contact_email=filter_var($_POST['contact_email'], FILTER_SANITIZE_EMAIL);
				if($do_register=="1")
				{
					if(filter_var($contact_email, FILTER_VALIDATE_EMAIL))
					{
						$email_verified=true;
					}
					else
					{
						$email_verified=false;
					}
				}
				if($email_verified==true)
				{
					$status=1;
					$creator_user_id=false;
					$validation_required=false;
					$page_title=filter_var($_POST['page_title'], FILTER_SANITIZE_STRING);
					$category=filter_var($_POST['category'], FILTER_SANITIZE_STRING);
					$contact_mobile=filter_var($_POST['contact_mobile'], FILTER_SANITIZE_STRING);
					$website=filter_var($_POST['website'], FILTER_SANITIZE_STRING);
					$page_logo="";
					$target_dir = "uploads/";
					$check = getimagesize($_FILES["logo"]["tmp_name"]);
					if($check!==false)
					{
						$page_logo=time().'-'.str_replace(" ","-",strtolower(basename($_FILES["logo"]["name"])));
						$target_file = $target_dir.$page_logo;
						if(move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file))
						{
							$page_logo=$target_file;
						}
					}
					
					$first_name=filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
					$last_name=filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
					if($do_register=="1")
					{
						$user_check_query="SELECT * FROM users WHERE email='$contact_email'";
						$user_check_result=mysqli_query($user_check_query);
						if(mysqli_num_rows($user_check_result)>0)
						{
							?>
							<script>
								alert('You have an existing account with this email.Please login to your account and start creating page.');
							</script>
							<?php
						}
						else
						{
							$username=generateUniqueUserName($contact_email);
							$password=time();
							$password_hash=md5($password);
							$code=mt_rand(1000,9999);
							$insert_query="INSERT INTO users SET email='$contact_email',first_name='$first_name',last_name='$last_name',username='$username',password='$password_hash',status=0,validated=0,code='$code'";
							if(mysqli_query($conn,$insert_query))
							{
								$creator_user_id=mysqli_insert_id($conn);
								$validation_required=true;
								$status=0;
							}
						}
					}
					else
					{
						$creator_user_id=$_COOKIE['uid'];
					}
					if($creator_user_id)
					{
						$page_username=generateUniquePageUserName($page_title,$contact_email);
						$page_insert_query="INSERT INTO pages SET page_logo='$page_logo',added=NOW(),status='$status',contact_email='$contact_email',title='$page_title',category='$category',contact_mobile='$contact_mobile',website='$website',username='$page_username',user_id='$creator_user_id'";
						if(mysqli_query($conn,$page_insert_query))
						{
							if($validation_required)
							{
								$contact_type="email";
								$email_content=email_html;
								$email_content=str_replace("RY-CODE",$code,$email_content);
								$email_content=str_replace("RY-USR",$creator_user_id,$email_content);
								$email_content=str_replace("RY-BOOL","1",$email_content);
								$email_content=str_replace("RY-PAGE",$page_username,$email_content);
								$headers = "MIME-Version: 1.0" . "\r\n";
								$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
								$headers .= 'From: <no-reply@ropeyou.com>' . "\r\n";
								
								mail($contact_email,"RopeYou Confirmation Email",$email_content,$headers);
								if(mysqli_query($conn,"INSERT INTO users_contact SET user_id='".$creator_user_id."',contact_name='Self',contact_type='$contact_type',contact='$contact_email'"))
								{
									session_destroy();
									session_start();
									$_SESSION['atv']='email';
									$_SESSION['mobile']=trim($contact_email);
									$_SESSION['ccode']="";
									
									$_SESSION['page']=$page_username;
									$_SESSION['is_page']='1';
									?>
									<script>
										location.href='<?php echo base_url."verify-otp"; ?>';
									</script>
									<?php
									die();
								}
							}
							else
							{
								?>
								<script>
									location.href='<?php echo base_url."p/".$page_username; ?>';
								</script>
								<?php
								die();
							}
						}
						else
						{
							if($validation_required)
							{
								mysqli_query($conn,"DELETE FROM users WHERE id='$creator_user_id'");
							}
							?>
							<script>
								alert('Something went wrong please try again later.');
							</script>
							<?php
						}
					}
					else
					{
						?>
						<script>
							alert('Unauthorised access. Please try again.');
						</script>
						<?php
					}	
				}
				else
				{
					?>
					<script>
						alert('Please enter a valid email.');
					</script>
					<?php
				}
				
			}
			$page_username=$_REQUEST['__username'];
			if($page_username=="")
			{
				include_once '404.php';
				die();
			}
			else
			{
				$page_query="SELECT * FROM pages WHERE username='$page_username' AND status=1";
				$page_result=mysqli_query($conn,$page_query);
				if(mysqli_num_rows($page_result)>0)
				{
					$page_detail_row=mysqli_fetch_array($page_result);
				}
				else
				{
					include_once '404.php';
					die();
				}
			}
			$og_title=$page_detail_row['title'];
			$og_url="p/".$page_username;//
			$og_description=$page_detail_row['title'];
		?>
		
		<title><?php echo $og_title; ?> | RopeYou Connects</title>
		<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5fe339f07c936200185ee881&product=inline-share-buttons" async="async"></script>
		<meta property="og:url" content="<?php echo base_url.$og_url; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $og_title; ?>" />
		<meta property="og:description" content="<?php echo substr(strip_tags($og_description),0,100); ?>" />
		<meta property="og:image" content="<?php echo base_url; ?>img/logo.png" />
		<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
		
		
		<meta property="twitter:title" content="<?php echo $og_title; ?>">
		<meta property="twitter:description" content="<?php echo substr(strip_tags($og_description),0,100); ?>">
		<meta property="twitter:image" content="<?php echo base_url; ?>img/logo.png">
		<meta property="twitter:card" content="<?php echo base_url; ?>img/logo.png">
		<meta property="twitter:url" content="<?php echo base_url.$og_url; ?>">
		
	</head>
	<style>
		h6{
			font-size: 0.7rem !important;
		}
		::-webkit-input-placeholder { /* Chrome/Opera/Safari */
		  color: #d8dfe3 !important;
		}
		::-moz-placeholder { /* Firefox 19+ */
		  color: #d8dfe3 !important;
		}
		:-ms-input-placeholder { /* IE 10+ */
		  color: #d8dfe3 !important;
		}
		:-moz-placeholder { /* Firefox 18- */
		  color: #d8dfe3 !important;
		}
		#page_post_textarea{
			color:#000;
			font-size:18px;
		}
	</style>
	<body style="min-height:calc(100vh);">
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container" style="bottom:0px;position:relative;">
				<div class="row">
					<?php
						$page_id=$page_detail_row['id'];
						if(isLogin())
						{
							$user_id=$_COOKIE['uid'];
							if($user_id!=$page_detail_row['user_id'])
							{
								$pvquery="SELECT * FROM page_views WHERE user_id='$user_id' AND page_id='$page_id'";
								$pvresult=mysqli_query($conn,$pvquery);
								if(mysqli_num_rows($pvresult)>0)
								{
									$pvrow=mysqli_fetch_array($pvresult);
									$vquery="UPDATE page_views SET added=NOW() WHERE id='".$pvrow['id']."'";
									mysqli_query($conn,$vquery);
								}
								else
								{
									mysqli_query($conn,"INSERT INTO page_views SET user_id='$user_id',page_id='$page_id',added=NOW()");
									$vquery="UPDATE pages SET views=views+1 WHERE id='$page_id'";
									mysqli_query($conn,$vquery);
								}
								insertPageViewNotification($user_id,$page_id);
							}
						}
					?>
					<aside class="col col-12 col-md-4 col-lg-4 col-xl-4 col-sm-4 order-lg-1 order-xl-1 order-1 order-md-1" id="left_side_bar" style="position:static;">
						<div class="shadow-sm rounded bg-white mb-3 list-sidebar">
							<!--<div class="box-title border-bottom p-3">
								<h5 class="mb-2"><?php echo $page_detail_row['title']; ?></h5>
							</div>-->
							<input type="hidden" name="session_attribute" id="session_attribute" value="<?php echo time().$_COOKIE['uid']; ?>">
							<div class="box-body" style="min-height:500px;max-height:550px;overflow-y:auto;padding:0px;margin:0px;">
								<?php
									$pages_query="SELECT * FROM pages WHERE id='$page_id'";
									$pages_result=mysqli_query($conn,$pages_query);
									$category_name="";
									if(mysqli_num_rows($pages_result)>0)
									{
										while($page_row=mysqli_fetch_array($pages_result))
										{
											$category=$page_row['category'];
											$category_query="SELECT * FROM page_types WHERE id='$category'";
											$category_result=mysqli_query($conn,$category_query);
											$category_row=mysqli_fetch_array($category_result);
											$category_name=$category_row['overlay_title'];
											?>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;text-align:center;">
															<a href="<?php echo page_url.$page_row['username']; ?>" title="<?php echo $page_row['title']; ?>" style="text-align:center;">
																<img src="<?php echo base_url.$page_row['page_logo']; ?>" style="min-height:70px;min-width:70px;max-height:71px;max-width:71px;border-radius:50%;border:5px solid #efefef;background:#000;" class="img-fluid">
															</a>
														</div>
														<div class="col-md-9" style="padding:10px;">
															<a href="<?php echo page_url.$page_row['username']; ?>" title="<?php echo $page_row['title']; ?>">
																<b><?php echo $page_row['title']; ?></b><br/>
																<u><?php echo "@".$page_row['username']; ?></u><br/>
																<?php echo $category_row['overlay_title']; ?>&nbsp;Page
															</a>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Website
														</div>
														<div class="col-md-9" style="padding:10px;">
															<?php
																if($page_row['website']!="")
																{
																	?>
																	<a href="<?php echo $page_row['website']; ?>"><?php echo $page_row['website']; ?></a>
																	<?php
																}
																else
																{
																	echo 'NA';
																}
															?>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Email
														</div>
														<div class="col-md-9" style="padding:10px;">
															<?php
																if($page_row['contact_email']!="")
																{
																	?>
																	<a href="mailto:<?php echo $page_row['contact_email']; ?>"><?php echo $page_row['contact_email']; ?></a>
																	<?php
																}
																else
																{
																	echo 'NA';
																}
															?>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Contact
														</div>
														<div class="col-md-9" style="padding:10px;">
															<?php
																if($page_row['contact_mobile']!="")
																{
																	?>
																	<a href="tel:<?php echo $page_row['contact_mobile']; ?>"><?php echo $page_row['contact_mobile']; ?></a>
																	<?php
																}
																else
																{
																	echo 'NA';
																}
															?>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Page URL
														</div>
														<div class="col-md-9" style="padding:10px;">
															<?php
																if($page_row['username']!="")
																{
																	?>
																	<a href="<?php echo page_url.$page_row['username']; ?>"><?php echo '@'.$page_row['username']; ?></a>
																	<?php
																}
																else
																{
																	echo 'NA';
																}
															?>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Category
														</div>
														<div class="col-md-9" style="padding:10px;">
															<?php
																if($category_name!="")
																{
																	?>
																	<?php echo $category_name; ?>
																	<?php
																}
																else
																{
																	echo 'NA';
																}
															?>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Views
														</div>
														<div class="col-md-9" style="padding:10px;">
															<?php echo $page_detail_row['views']; ?>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="padding:0px;margin:0px;">
												<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
													<div class="row" style="padding:0px;margin:0px;">
														<div class="col-md-3 border-right" style="padding:10px;">
															Likes
														</div>
														<div class="col-md-9" style="padding:10px;">
															<span id="pages_likes_count"><?php echo $page_detail_row['likes']; ?></span>
														</div>
													</div>
												</div>
											</div>
											<?php
											if(isLogin())
											{
												?>
												<div class="row" style="padding:0px;margin:0px;">
													<div class="col-md-12 align-items-center job-item-header border-bottom" id="like_button_section" style="padding:10px;margin:0px;">
													<?php
														$user_id=$_COOKIE['uid'];
														$page_like_query="SELECT * FROM page_likes WHERE page_id='$page_id' AND user_id='".$_COOKIE['uid']."'";
														$page_like_result=mysqli_query($conn,$page_like_query);
														if(mysqli_num_rows($page_like_result)>0)
														{
															?>
															<button class="btn btn-danger" onclick="Unlike('<?php echo $page_id; ?>','<?php echo $user_id; ?>');" type="button"><i class="fa fa-thumbs-down"></i>&nbsp;Unlike</button>
															<?php
														}
														else
														{
															?>
															<button class="btn btn-primary" onclick="Like('<?php echo $page_id; ?>','<?php echo $user_id; ?>');" type="button"><i class="fa fa-thumbs-up"></i>&nbsp;Like</button>
															<?php
														}
													?>
													</div>
												</div>
											<?php
											}		
										}
									}
									else
									{
										?>
										<p style="margin-top:20px;">You do not have any page.start by creating a page. </p>
										<?php
									}
								?>
								<div class="sharethis-inline-share-buttons" style="margin-top:30px;"></div>
							</div>
						</div>
					</aside>
					<main class="col col-12 col-md-6 col-lg-6 col-xl-6 col-sm-6 order-lg-2 order-xl-2 order-2 order-md-2">
						<?php
							if(isLogin())
							{
								$posting_user_profile=getUserProfileImage($_COOKIE['uid']);
						?>
						<div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post">
							<div class="w-100 py-3">
								<div class="row" style="width:100%;">
									<div class="col-md-2" style="padding:15px;">
										<div class="dropdown-list-image mr-3" style="width:50px;height:50px;">
											<img class="rounded-circle" src="<?php echo $posting_user_profile; ?>" alt="User Profile Picture"  style="border:1px solid #eaebec !important;height:50px;width:50px;">
											<div class="status-indicator bg-success"></div>
										</div>
									</div>
									<div class="col-md-8" style="padding:10px;">
										<textarea placeholder="Write your thoughts..." data-userid="<?php echo $_COOKIE['uid']; ?>" data-pageid="<?php echo $page_id; ?>" name="page_post_textarea" id="page_post_textarea" class="form-control p-0 border-0 shadow-none status_text" style="resize:none;" rows="3"></textarea>
									</div>
									<div class="col-md-2" style="padding:10px;">
										<button type="button" onclick="doPost();" id="do_post_button" class="btn btn-success pull-right" style="float:right;">Post</button><br/><br/>
										<button type="button" class="btn btn-warning pull-right" style="float:right;" id="file_chooser" onclick="chooseFile('paper_clip_attachment');"><i class="fa fa-paperclip"></i></button>
										<input type="file" style="display:none;" id="paper_clip_attachment" name="paper_clip_attachment[]" multiple="multiple" accept="image/*,video/*">
									</div>
									<div class="col-md-12" style="padding:5px;">
										<div class="row" id="page_post_media_preview">
											
										</div>	
									</div>
								</div>
							</div>
						</div>
						<?php
							}
							$page_post_query="SELECT id FROM page_posts WHERE page_id='$page_id' AND status=1 ORDER BY id DESC";
							$page_post_result=mysqli_query($conn,$page_post_query);
							if(mysqli_num_rows($page_post_result)>0)
							{
								?>
								<div class="w-100 post_area" id="post_area">
									<?php
										while($page_post_row=mysqli_fetch_array($page_post_result))
										{
											$page_post_id=$page_post_row['id'];
											echo getPagePostHtml($page_post_id);
										}
									?>
								</div>
								<?php
							}
							else
							{
								?>
								<div class="w-100" id="no_post_area">
									<div class="w-100 py-3">
										<div class="row" style="width:100%;">
											<div class="col-md-12" style="padding:10px;">
												<p style="text-align:center;font-size:16px;">There is no post available for this page.</p>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						?>
					</main>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<script>
			var base_url="<?php echo base_url; ?>";
			var page_id="<?php echo $page_id; ?>";
			var Upload = function (file) {
				this.file = file;
			};

			Upload.prototype.getType = function() {
				return this.file.type;
			};
			Upload.prototype.getSize = function() {
				return this.file.size;
			};
			Upload.prototype.getName = function() {
				return this.file.name;
			};
			Upload.prototype.doUpload = function (img_id) {
				//$("#save_brodcast_post").attr("disabled",true);
				makeUnPostable();
				var that = this;
				var formData = new FormData();

				// add assoc key values, this will be posts values
				formData.append("file", this.file, this.getName());
				formData.append("session_attribute", $("#session_attribute").val());
				formData.append("page_id", page_id);
				formData.append("upload_file", true);

				$.ajax({
					type: "POST",
					url: base_url+"upload-file-to-page-post",
					xhr: function () {
						var myXhr = $.ajaxSettings.xhr();
						if (myXhr.upload) {
							myXhr.upload.addEventListener('progress', that.progressHandling, false);
						}
						return myXhr;
					},
					success: function (data) {
						console.log(data);
						var this_parsed_json=JSON.parse(data);
						if(this_parsed_json.id!="")
						{
							uploaded_files=uploaded_files+1;
							$("#"+img_id).attr("imgid",this_parsed_json.id);
							if(uploaded_files==selected_files)
							{
								makePostable();
							}
						}
						else
						{
							$("."+img_id).remove();
							uploaded_files=uploaded_files-1;
							selected_files=selected_files-1;
							if(uploaded_files==selected_files)
							{
								makePostable();
							}
						}
						//$("#save_brodcast_post").attr("disabled",false);
					},
					error: function (error) {
						// handle error
					},
					async: true,
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					timeout: 6000000
				});
			};

			Upload.prototype.progressHandling = function (event) {
				var percent = 0;
				var position = event.loaded || event.position;
				var total = event.total;
				var progress_bar_id = "#progress-wrp";
				if (event.lengthComputable) {
					percent = Math.ceil(position / total * 100);
				}
				// update progressbars classes so it fits your code
				$(progress_bar_id + " .progress-bar").css("width", +percent + "%");
				$(progress_bar_id + " .status").text(percent + "%");
			};
			function doPost()
			{
				var text=$("#page_post_textarea").val();
				var page_id=$("#page_post_textarea").data('pageid');
				var user_id=$("#page_post_textarea").data('userid');
				if((text!="" || $("#page_post_media_preview").html()!=null))
				{
					$.ajax({
						url:base_url+'do-page-post',
						type:'post',
						data:{page_id:page_id,user_id:user_id,method:'doNewPost',text:text,session_attribute:$("#session_attribute").val()},
						success:function(response)
						{
							var parsed_response=JSON.parse(response);
							uploaded_files=0;selected_files=0;
							$("#session_attribute").val(parsed_response.session_attribute)
							if(parsed_response.html!="")
							{
								if($("#no_post_area").length>0)
								{
									$("#no_post_area").html('');
									$("#no_post_area").attr('id',"post_area");
									$("#post_area").removeClass("bg-gray");
									$("#post_area").addClass("bg-white");
								}
								$("#post_area").prepend(parsed_response.html);
							}
							$("#page_post_textarea").val('');
							$("#page_post_media_preview").html('');
							$("#paper_clip_attachment").val('');
							$("#paper_clip_attachment").get(0).files=null;
						}
					});
				}
			}
			function Like(page_id,user_id)
			{
				if(page_id!="" && user_id!="")
				{
					$.ajax({
						url:base_url+'do-page-like',
						type:'post',
						data:{page_id:page_id,user_id:user_id,method:'like'},
						success:function(response)
						{
							var parsed_response=JSON.parse(response);
							$("#pages_likes_count").text(parsed_response.likes_count);
							if(parsed_response.html!="")
							{
								$("#like_button_section").html(parsed_response.html);
							}
						}
					});
				}
			}
			function Unlike(page_id,user_id)
			{
				if(page_id!="" && user_id!="")
				{
					$.ajax({
						url:base_url+'do-page-like',
						type:'post',
						data:{page_id:page_id,user_id:user_id,method:'unlike'},
						success:function(response)
						{
							var parsed_response=JSON.parse(response);
							$("#pages_likes_count").text(parsed_response.likes_count);
							if(parsed_response.html!="")
							{
								$("#like_button_section").html(parsed_response.html);
							}
						}
					});
				}
			}
			function chooseFile(file_chooser)
			{
				$("#"+file_chooser).click();
			}
			function isImage(file){
			   return file['type'].split('/')[0]=='image';
			}
			function isVideo(file){
			   return file['type'].split('/')[0]=='video';
			}
			//var files_selected = [];
			var video_preview_counter=0;
			var image_preview_counter=0;
			function videoPreviewHtml(id)
			{
				var html='<div class="col-md-4 '+id+'" style="padding:10px;"><video width="200px" controls>'+
				  '<source src="mov_bbb.mp4" id="'+id+'">'+
					'Your browser does not support HTML5 video.'+
				'</video></div>';
				return html;
			}
			function imagePreviewHtml(id)
			{
				var html='<div class="col-md-2 '+id+'" style="padding:10px;"><img src="" id="'+id+'" style="height:75px;width:75px;"></div>';
				return html;
			}
			var selected_files=0;
			var uploaded_files=0;
			function previewVideo(file)
			{
				$("#page_post_media_preview").append(videoPreviewHtml('video_preview_'+video_preview_counter));
				var $source = $('#video_preview_'+video_preview_counter);
				$source[0].src = URL.createObjectURL(file);
				$source.parent()[0].load();
				selected_files=selected_files+1;
				var upload = new Upload(file);
				upload.doUpload('video_preview_'+video_preview_counter);
				video_preview_counter=video_preview_counter+1;
			}	
			function previewImage(file)
			{
				if (file) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$("#page_post_media_preview").append(imagePreviewHtml('image_preview_'+image_preview_counter));
						$('#image_preview_'+image_preview_counter).attr("src",e.target.result);
						selected_files=selected_files+1;
						var upload = new Upload(file);
						upload.doUpload('image_preview_'+image_preview_counter);
						image_preview_counter=image_preview_counter+1;
					}
					reader.readAsDataURL(file); // convert to base64 string
				}
			}			
    
			$("#paper_clip_attachment").change(function(){
				console.log($("#paper_clip_attachment").get(0).files);
				for (var i = 0; i < $("#paper_clip_attachment").get(0).files.length; ++i) {
					var this_file=$("#paper_clip_attachment").get(0).files[i];
					//files_selected.push(this_file);
					if(isImage(this_file))
					{
						previewImage(this_file);
					}
					else if(isVideo(this_file))
					{
						previewVideo(this_file);
					}
				}
				//$("input[name=paper_clip_attachment_1]").val(files_selected);
			});
			function makeUnPostable()
			{
				if(uploading(1))
				{
					$("#do_post_button").attr("disabled",true);
				}
			}
			function makePostable()
			{
				if(uploading(0))
				{
					$("#do_post_button").attr("disabled",false);
				}
			}
			function uploading(token)
			{
				return true;
			}
			function loadImageSlider(div)
			{
				$("."+div+" img").css("cursor","pointer");
				$("."+div+" img").click(function(){
					var image=$(this).data("image");
					var user_id=$(this).data("userid");
					$.ajax({
						url:base_url+'get-user-public-gallery-slider',
						type:'post',
						data:{user_id:user_id,image:image},
						success:function(response){
							$("#backdrop_image_container_html").html(response);
							$("#image_backdrop_slider_modal").modal('show');
						}
					});
				});
			}
			loadImageSlider("post_area");
		</script>
	</body>
</html>
