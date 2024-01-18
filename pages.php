<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head_without_session.php'; ?>
		<?php
			if(isset($_POST['continue']))
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
		?>
		<title>Pages | RopeYou Connects</title>
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
	</style>
	<body style="min-height:calc(100vh);">
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container" style="bottom:0px;position:relative;">
				<div class="row">
					<aside class="col col-12 col-md-4 col-lg-4 col-xl-4 col-sm-4 order-lg-1 order-xl-1 order-1 order-md-1" id="left_side_bar" style="position:static;">
						<div class="shadow-sm rounded bg-white mb-3 list-sidebar">
							<div class="box-title border-bottom p-3">
								<h5 class="mb-2">My Pages</h5>
							</div>
							<div class="box-body" style="min-height:500px;max-height:550px;overflow-y:auto;padding:0px;margin:0px;">
								<?php
									if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
									{
										$user_id=$_COOKIE['uid'];
										$pages_query="SELECT * FROM pages WHERE user_id='$user_id'";
										$pages_result=mysqli_query($conn,$pages_query);
										if(mysqli_num_rows($pages_result)>0)
										{
											while($page_row=mysqli_fetch_array($pages_result))
											{
												$category=$page_row['category'];
												$category_query="SELECT * FROM page_types WHERE id='$category'";
												$category_result=mysqli_query($conn,$category_query);
												$category_row=mysqli_fetch_array($category_result);
												?>
												<div class="row" style="padding:0px;margin:0px;">
													<div class="col-md-12 align-items-center job-item-header border-bottom" style="padding:0px;margin:0px;">
														<div class="row" style="padding:0px;margin:0px;">
															<div class="col-md-3 border-right" style="padding:10px;text-align:center;">
																<a href="<?php echo page_url.$page_row['username']; ?>" title="<?php echo $page_row['title']; ?>">
																	<img src="<?php echo base_url.$page_row['page_logo']; ?>" style="padding:2px;height:50px;width:50px;background-color:#efefef;" class="img-fluid">
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
												<?php
											}
										}
										else
										{
											?>
											<p style="margin-top:20px;text-align:center;">You do not have any page.start by creating a page. </p>
											<?php
										}
									}
									else
									{
										?>
										<p style="margin-top:20px;">You are not logged-in either log-in to existing account if any or start by creating a page. </p>
										<?php
									}
								?>
							</div>
						</div>
					</aside>
					<main class="col col-12 col-md-8 col-lg-8 col-xl-8 col-sm-8 order-lg-2 order-xl-2 order-2 order-md-2">
						<div class="shadow-sm rounded bg-white mb-3">
							<div class="box-title border-bottom p-3">
								<h5 class="mb-2">Create New Page</h5>
							</div>
							<div class="box-body px-2 py-3">
								<div class="col-md-12">
									<form action="" id="page_form" method="post" enctype="multipart/form-data">
										<div class="row">
											<?php
												if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
												{
													$user_query="SELECT * FROM users WHERE id='".$_COOKIE['uid']."'";
													$user_result=mysqli_query($conn,$user_query);
													$user_row=mysqli_fetch_array($user_result);
													?>
													<input name="first_name" value="<?php echo $user_row['first_name']; ?>" id="first_name" type="hidden">
													<input name="last_name" value="<?php echo $user_row['last_name']; ?>" id="last_name" type="hidden">
													<input name="do_register" value="0" id="do_register" type="hidden">
													<?php
												}
												else
												{
													?>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<input name="do_register" value="1" id="do_register" type="hidden">
															<h6 style="margin-bottom:7px;">First Name <span style="color:red;">*</span></h6>
															<input class="form-control" name="first_name" autocomplete="off" value="" id="first_name" required title="" type="text" placeholder="e.g, John.">
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<h6 style="margin-bottom:7px;">Last Name <span style="color:red;">*</span></h6>
															<input class="form-control" name="last_name" autocomplete="off" value="" id="last_name" required title="" type="text" placeholder="e.g, Doe.">
														</div>
													</div>
													<?php
												}
											?>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Page Title <span style="color:red;">*</span></h6>
													<input class="form-control" name="page_title" autocomplete="off" value="" id="page_title" required title="" type="text" placeholder="e.g, Google INC.">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Category <span style="color:red;">*</span></h6>
													<select class="form-control" name="category" autocomplete="off" id="category" required>
														<option value="">Select Category</option>
														<?php
															$cat_query="SELECT * FROM page_types WHERE status=1";
															$cat_result=mysqli_query($conn,$cat_query);
															while($cat_row=mysqli_fetch_array($cat_result))
															{
																?>
																<option value="<?php echo $cat_row['id']; ?>"><?php echo $cat_row['overlay_title']; ?></option>
																<?php
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Contact Number (optional)</h6>
													<input class="form-control" value="" name="contact_mobile" id="contact_mobile" title="" type="text" placeholder="e.g, +919876543210">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Website (optional)</h6>
													<input class="form-control" name="website" autocomplete="off" value="" id="website" title="" type="text" placeholder="e.g, https://google.com">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Contact Email <?php if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="") { ?>(optional) <?php } else { ?> <span style="color:red;">*</span> <?php }?></h6>
													<input class="form-control" <?php if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="") { } else { echo 'required'; }?> value="" name="contact_email" id="contact_email" title="" type="text" placeholder="e.g, pagecontact@pager.com">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Logo or Image <span style="color:red;">*</span></h6>
													<input class="form-control" name="logo" autocomplete="off" value="" id="logo" required title="" type="file" accept="image/*">
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="form-group">
													<?php if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="") {} else { ?> <p style="margin-top:20px;color:red;">Note :- We will send an OTP to your email for email verification.</p> <?php } ?>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12 text-right">
												<button class="btn btn-primary" type="submit" name="continue">Create</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
				   </main>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
	</body>
</html>
