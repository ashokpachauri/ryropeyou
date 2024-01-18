<!doctype html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<title>Profile Settings | RopeYou Connects</title>
		<!-- CSS 
		================================================== -->
		<link rel="stylesheet" href="<?php echo base_url.'chat_bot/';?>css/style.css">
		<link rel="stylesheet" href="<?php echo base_url.'chat_bot/';?>css/night-mode.css">
		<link rel="stylesheet" href="<?php echo base_url.'chat_bot/';?>css/framework.css">
		<!-- icons
		================================================== -->
		<link rel="stylesheet" href="<?php echo base_url.'chat_bot/';?>css/icons.css">
		<link rel="stylesheet" href="<?php echo base_url.'';?>country-picker/build/css/countrySelect.css">
		<link rel="stylesheet" href="<?php echo base_url.'';?>country-picker/build/css/demo.css">
		<!-- Google font
		================================================== -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
		<style>
			.border_box_section{
				border:1px solid gray;
				border-radius:10px;
				margin-top:15px;
				margin-bttom:15px;
			}
			.modal-backdrop{
				z-index:0;
			}
		</style>
	</head>
	<?php
		if(isset($_POST['close_account']))
		{
			if(isset($_POST['delete_account_option']))
			{
				$delete_account_option=$_POST['delete_account_option'];
				$user_id=$_COOKIE['uid'];
				$query="SELECT * FROM users WHERE id='$user_id'";
				$result=mysqli_query($conn,$query);
				if(mysqli_num_rows($result)>0)
				{
					if($delete_account_option=="0")
					{
						$query="UPDATE users SET status=2 WHERE id='$user_id'";
						if(mysqli_query($conn,$query))
						{
							?>
							<script>
								alert('Account deleted temporarily.');
								window.location.href="<?php echo base_url; ?>logout";
							</script>
							<?php
							die();
						}
						else{
							?>
							<script>
								alert('Something went wrong please try again.');
								var active_tab=localStorage.getItem("active_tab");
								window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
							</script>
							<?php
							die();
						}
					}
					else if($delete_account_option=="1")
					{
						$query="UPDATE users SET status=3 WHERE id='$user_id'";
						if(mysqli_query($conn,$query))
						{
							mysqli_query($conn,"DELETE FROM users_personal WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts_activity SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts_comments SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts_comments_media SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts_links SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts_media SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_posts_tags SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_profiles SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_profile_pics SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE users_profile_views SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE blog_spaces SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE blog_space_posts SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE documents SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE followers SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE jobs SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE pages SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE page_posts SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"UPDATE videos SET status=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"DELETE FROM  recommendations WHERE r_user_id='$user_id'");
							mysqli_query($conn,"UPDATE gallery SET is_public=0,is_private=1,is_protected=0,is_friendly_protected=0,is_magic=0 WHERE user_id='$user_id'");
							mysqli_query($conn,"DELETE FROM distance_matrix  WHERE user_id='$user_id' OR r_user_id='$user_id'");
							?>
							<script>
								alert('Account deleted permanently.');
								window.location.href="<?php echo base_url; ?>logout";
							</script>
							<?php
							die();
						}
						else{
							?>
							<script>
								alert('Something went wrong please try again.');
								var active_tab=localStorage.getItem("active_tab");
								window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
							</script>
							<?php
							die();
						}
					}
				}
				else
				{
					?>
					<script>
						alert('Something went wrong please try again.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
					</script>
					<?php
					die();
				}
			}
			else
			{
				?>
				<script>
					alert('Please select account delete type.');
					var active_tab=localStorage.getItem("active_tab");
					window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
				</script>
				<?php
				die();
			}
		}
		if(isset($_POST['add_new_email_button']))
		{
			$new_email=$_POST['add_new_email_to_account'];
			$code=$_POST['new_email_otp'];
			$query="SELECT * FROM account_contacts WHERE contact='$new_email' AND user_id='".$_COOKIE['uid']."' AND code='$code'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$query="UPDATE account_contacts SET status=1,code='' WHERE contact='$new_email' AND user_id='".$_COOKIE['uid']."' AND code='$code'";
				if(mysqli_query($conn,$query))
				{
					?>
					<script>
						alert('Email has been added.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
					</script>
					<?php
					die();
				}
				else
				{
					?>
					<script>
						alert('Something went wrong please contact developer.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab+"&new_email=<?php echo $new_email; ?>";
					</script>
					<?php
					die();
				}
			}
			else{
				?>
				<script>
					alert('Invalid Email or Verification Code. Please enter correct details.');
					var active_tab=localStorage.getItem("active_tab");
					window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab+"&new_email=<?php echo $new_email; ?>";
				</script>
				<?php
				die();
			}
		}
		if(isset($_POST['add_new_mobile_button']))
		{
			$new_mobile=$_POST['add_new_mobile_to_account'];
			$code=$_POST['new_mobile_otp'];
			$query="SELECT * FROM account_contacts WHERE contact='$new_mobile' AND user_id='".$_COOKIE['uid']."' AND code='$code'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$query="UPDATE account_contacts SET status=1,code='' WHERE contact='$new_mobile' AND user_id='".$_COOKIE['uid']."' AND code='$code'";
				if(mysqli_query($conn,$query))
				{
					?>
					<script>
						alert('Email has been added.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
					</script>
					<?php
					die();
				}
				else
				{
					?>
					<script>
						alert('Something went wrong please contact developer.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab+"&new_email=<?php echo $new_email; ?>";
					</script>
					<?php
					die();
				}
			}
			else{
				?>
				<script>
					alert('Invalid Email or Verification Code. Please enter correct details.');
					var active_tab=localStorage.getItem("active_tab");
					window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab+"&new_email=<?php echo $new_email; ?>";
				</script>
				<?php
				die();
			}
		}
		if(isset($_POST['save_profile_name']))
		{
			$first_name=$_POST['first_name'];
			$last_name=$_POST['last_name'];
			$username=$_POST['username'];
			$day_birth=$_POST['day_birth'];
			$month_birth=$_POST['month_birth'];
			$year_birth=$_POST['year_birth'];
			$gender=$_POST['gender'];
			$passport=$_POST['passport'];
			$relocate_abroad=$_POST['relocate_abroad'];
			$profile_title=$_POST['profile_title'];
			$update_query="UPDATE users SET first_name='$first_name',last_name='$last_name',profile_title='$profile_title' WHERE id='".$_COOKIE['uid']."'";
			if(mysqli_query($conn,$update_query))
			{
				$update_query="UPDATE users_personal SET gender='$gender',relocate_abroad='$relocate_abroad',passport='$passport',day_birth='$day_birth',month_birth='$month_birth',year_birth='$year_birth' WHERE user_id='".$_COOKIE['uid']."'";
				if(mysqli_query($conn,$update_query))
				{
					$query="SELECT * FROM users WHERE (username='$username' OR email='$username' OR mobile='$username') AND id!='".$_COOKIE['uid']."'";
					$result=mysqli_query($conn,$query);
					$numRows=mysqli_num_rows($result);
					if($numRows>0)
					{
						?>
						<script>
							alert('Username has been already taken.');
							var active_tab=localStorage.getItem("active_tab");
							window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
						</script>
						<?php
						die();
					}
					else
					{
						$update_query="UPDATE users SET username='$username' WHERE id='".$_COOKIE['uid']."'";
						if(mysqli_query($conn,$update_query))
						{
							?>
							<script>
								alert('Profile Updated Successfully.');
								var active_tab=localStorage.getItem("active_tab");
								window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
							</script>
							<?php
							die();
						}
						else{
							?>
							<script>
								alert('Something went wrong.Username has not been updated.');
								var active_tab=localStorage.getItem("active_tab");
								window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
							</script>
							<?php
							die();
						}
					}
				}
				else
				{
					?>
					<script>
						alert('Something went wrong.Personal Profile does not updated.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
					</script>
					<?php
					die();
				}
			}
			else
			{
				?>
				<script>
					alert('Something went wrong.Profile does not updated.');
					var active_tab=localStorage.getItem("active_tab");
					window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
				</script>
				<?php
				die();
			}
		}
		if(isset($_POST['save_password']))
		{
			$old_password=trim($_POST['old_password']);
			$new_password=trim($_POST['new_password']);
			$confirm_password=trim($_POST['confirm_password']);
			if($old_password=="" && $new_password=="" && $confirm_password=="")
			{
				?>
				<script>
					alert('Please fill all required fields.');
					var active_tab=localStorage.getItem("active_tab");
					window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
				</script>
				<?php
				die();
			}
			if($confirm_password!=$new_password)
			{
				?>
				<script>
					alert('New password & Confirm password does not matches');
					var active_tab=localStorage.getItem("active_tab");
					window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
				</script>
				<?php
				die();
			}
			else
			{
				$password=md5($new_password);
				$cquery="SELECT * FROM users WHERE id='".$_COOKIE['uid']."' AND password='".md5($old_password)."'";
				$cresult=mysqli_query($conn,$cquery);
				if(mysqli_num_rows($cresult)>0)
				{
					$update_query="UPDATE users SET password='$password' WHERE id='".$_COOKIE['uid']."'";
					if(mysqli_query($conn,$update_query))
					{
						?>
						<script>
							alert('Password updated successfully.');
							var active_tab=localStorage.getItem("active_tab");
							window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
						</script>
						<?php
						die();
					}
					else{
						?>
						<script>
							alert('Something went wrong.Password does not updated.');
							var active_tab=localStorage.getItem("active_tab");
							window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
						</script>
						<?php
						die();
					}
				}
				else
				{
					?>
					<script>
						alert('Incorrect password.Please enter correct password.');
						var active_tab=localStorage.getItem("active_tab");
						window.location.href="<?php echo base_url; ?>profile-settings.php?tab="+active_tab;
					</script>
					<?php
				}
			}
		}
		/*--------------Manage Active Tab--------------*/
		$Tab_To_Open="";
		$total_tabs=array("profile_settings","contact_settings","password_settings","privacy_settings","account_settings");
		if(isset($_REQUEST['tab']) && $_REQUEST['tab']!="")
		{
			$Tab_To_Open=strtolower($_REQUEST['tab']);
			if(!in_array($Tab_To_Open,$total_tabs))
			{
				$Tab_To_Open=$total_tabs[0];
			}
		}
		else
		{
			$Tab_To_Open=$total_tabs[0];
		}
		/*--------------Manage Active Tab--------------*/
	?>
	<script>
		localStorage.setItem("active_tab","<?php echo $Tab_To_Open; ?>");
	</script>
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4" style="padding-top:5px !important;">
			<div class="container">
				<div class="main_content" style="margin-left:0px;padding-top:0px;">
					<div class="modal fade retrieve_password" id="retrieve_password" style="z-index:9999 !important;" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="retrieve_password_static" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header" style="width:100%;">
									<div class="row" style="width:100%;">
										<div class="col-md-8">
											<h5 class="modal-title" id="retrieve_password_static">Reset Password</h5>
										</div>
										<div class="col-md-4">
											<button  data-dismiss="modal" style="border-color:none !important;float:right !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-times"></i></button>
										</div>
									</div>
								</div>
								<div class="modal-body" style="padding:0px;">
									<div class="w-100">
										<div class="row" style="width:100%;padding:0px;margin:0px;">
											<div class="col-md-12" id="retrieve_password_html" style="padding:10px;border-top:1px solid #dee2e6;margin-bottom:20px;">
												
											</div>	
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="main_content_inner p-sm-0 ml-sm-4" style="width:100%;max-width:100%;">
						<div class="uk-position-relative" uk-grid>
							<div class="uk-width-1-4@m uk-flex-first@m pl-sm-0">

								<nav class="responsive-tab style-3 setting-menu"
									uk-sticky="top:30 ; offset:70; media:@m ;bottom:true; animation: uk-animation-slide-top">
									<ul>
										<li class="<?php if($Tab_To_Open==$total_tabs[0]){ echo "uk-active"; } ?> profile_settings_list" id="profile_settings_item" onclick="openTab('profile_settings');"><a href="javascript:void(0);"> <i class="uil-user"></i> Profile </a></li>
										<li class="<?php if($Tab_To_Open==$total_tabs[1]){ echo "uk-active"; } ?> profile_settings_list" id="contact_settings_item"><a href="javascript:void(0);" onclick="openTab('contact_settings');"> <i class="uil-cog"></i> Contact </a></li>
										<li class="<?php if($Tab_To_Open==$total_tabs[2]){ echo "uk-active"; } ?> profile_settings_list" id="password_settings_item"><a href="javascript:void(0);" onclick="openTab('password_settings');"> <i class="uil-unlock-alt"></i> Password </a></li>
										<li class="<?php if($Tab_To_Open==$total_tabs[3]){ echo "uk-active"; } ?> profile_settings_list" id="privacy_settings_item"><a href="javascript:void(0);" onclick="openTab('privacy_settings');"> <i class="uil-shield-check"></i> Privacy</a></li>
										<li class="<?php if($Tab_To_Open==$total_tabs[4]){ echo "uk-active"; } ?> profile_settings_list" id="account_settings_item"><a href="javascript:void(0);" onclick="openTab('account_settings');"> <i class="uil-trash-alt"></i> Delete account</a></li>
									</ul>
								</nav>

							</div>
							
							<div class="uk-width-2-3@m mt-sm-3 pl-sm-0 p-sm-4" style="padding-top: 0px !important;margin-top: 0px !important;bottom:0px;">
								<?php
									$users_data=getUsersData($_COOKIE['uid']);
									$users_personal_data=getUsersPersonalData($_COOKIE['uid']);
								?>
								<div class="uk-card-default rounded profile_settings" <?php if($Tab_To_Open!=$total_tabs[0]){ echo 'style="display:none;"'; } ?> id="profile_settings">
									<div class="p-3">
										<h5 class="mb-0"> Profile Settings </h5>
									</div>
									<hr class="m-0">
									<form class="uk-child-width-1-3@s uk-grid-small p-4" uk-grid action="" method="post">
										<div>
											<h5 class="uk-text-bold mb-2"> First Name </h5>
											<input type="text" name="first_name" required class="uk-input" placeholder="Your First Name" value="<?php echo ucwords(strtolower($users_data['first_name'])); ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Last Name </h5>
											<input type="text" name="last_name" required class="uk-input" placeholder="Your Last Name" value="<?php echo ucwords(strtolower($users_data['last_name'])); ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Username </h5>
											<input type="text" class="uk-input" name="username" required placeholder="Your Username" value="<?php echo strtolower($users_data['username']); ?>">
										</div>
										<div style="width:100% !important;padding-left:0px;padding-right:0px;margin-left:0px;margin-right:0px;padding-top:10px;padding-bottom:10px;">
											<h5 class="uk-text-bold mb-2 p-2"> Date Of Birth </h5>
											<hr class="m-0">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Day </h5>
											<select class="uk-select" name="day_birth" id="day_birth">
												<?php 
													for($i=1;$i<=31;$i++)
													{
														if($i<=9)
														{
															$extra="0";
														}
														else{
															$extra="";
														}
														?>
														<option value="<?php echo $i; ?>" <?php if($i==$users_personal_data['day_birth']){ echo "selected"; } ?>><?php echo $extra.$i; ?></option>
														<?php
													}
												?>
											</select>
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Month </h5>
											<select class="uk-select" name="month_birth" id="month_birth">
												<option value="1" <?php if("1"==$users_personal_data['month_birth']){ echo "selected"; } ?>>January</option>
													<option value="2" <?php if("2"==$users_personal_data['month_birth']){ echo "selected"; } ?>>February</option>
													<option value="3" <?php if("3"==$users_personal_data['month_birth']){ echo "selected"; } ?>>March</option>
													<option value="4" <?php if("4"==$users_personal_data['month_birth']){ echo "selected"; } ?>>April</option>
													<option value="5" <?php if("5"==$users_personal_data['month_birth']){ echo "selected"; } ?>>May</option>
													<option value="6" <?php if("6"==$users_personal_data['month_birth']){ echo "selected"; } ?>>Jun</option>
													<option value="7" <?php if("7"==$users_personal_data['month_birth']){ echo "selected"; } ?>>July</option>
													<option value="8" <?php if("8"==$users_personal_data['month_birth']){ echo "selected"; } ?>>August</option>
													<option value="9" <?php if("9"==$users_personal_data['month_birth']){ echo "selected"; } ?>>September</option>
													<option value="10" <?php if("10"==$users_personal_data['month_birth']){ echo "selected"; } ?>>October</option>
													<option value="11" <?php if("11"==$users_personal_data['month_birth']){ echo "selected"; } ?>>November</option>
													<option value="12" <?php if("12"==$users_personal_data['month_birth']){ echo "selected"; } ?>>December</option>
											</select>
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Year </h5>
											<select class="uk-select" name="year_birth" id="year_birth">
												<?php 
													for($i=2020;$i>=1900;$i--)
													{
														?>
														<option value="<?php echo $i; ?>" <?php if($i==$users_personal_data['year_birth']){ echo "selected"; } ?>><?php echo $i; ?></option>
														<?php
													}
												?>
											</select>
										</div>
										<div style="width:100% !important;padding-left:0px;padding-right:0px;margin-left:0px;margin-right:0px;padding-top:10px;padding-bottom:10px;">
											<hr class="m-0">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Gender </h5>
											<select class="uk-select" name="gender" id="gender">
												<option value="Male" <?php if($users_personal_data['gender']=="Male"){ echo "selected"; } ?>>Male</option>
												<option value="Female" <?php if($users_personal_data['gender']=="Female"){ echo "selected"; } ?>>Female</option>
												<option value="Prefer not to mention" <?php if($users_personal_data['gender']=="Prefer not to mention"){ echo "selected"; } ?>>Prefer not to mention</option>
											</select>
										</div>
										<div>
											<div class="form-group">
												<h5 class="uk-text-bold mb-2">Passport Status</h5>
												<input type="text" name="passport" id="passport"  value="<?php echo $users_personal_data['passport']; ?>" class="uk-input" placeholder="Having Indian Passport" value="">
											</div>
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">Relocation Status*</h5>
											<select id="relocate_abroad" name="relocate_abroad" title="let us know if you are ready or not to relocate to abroad?" class="uk-select" required>
												<option value="2" <?php if($users_personal_row['relocate_abroad']=="2"){ echo "selected"; } ?>>Can Relocate worldwide</option>
												<option value="0" <?php if($users_personal_row['relocate_abroad']=="0"){ echo "selected"; } ?>>Can not relocate outside home town</option>
												<option value="1" <?php if($users_personal_row['relocate_abroad']=="1"){ echo "selected"; } ?>>Can Relocate within home country</option>
											</select>
										</div>
										<div style="width:100%">
											<h5 class="uk-text-bold mb-2">Profile Title*</h5>
											<textarea name="profile_title" rows="3" id="profile_title" class="uk-textarea" required style="resize:none;"><?php echo $users_data['profile_title']; ?></textarea>
										</div>	
										<div class="uk-flex uk-flex-right p-4 pull-right" style="width:100% !important;">
											<button type="submit" name="save_profile_name" class="button primary" style="float:right !important;">Save Changes</button>
										</div>
									</form>
								</div>
								<div class="uk-card-default rounded mt-4 profile_settings" <?php if($Tab_To_Open!=$total_tabs[1]){ echo 'style="display:none;"'; } ?> id="contact_settings">
									<div class="p-3">
										<h5 class="mb-0">Email Settings </h5>
									</div>
									<?php
										if($users_data['email']!="")
										{
											?>
											<hr class="m-0">
											<form class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid action="" method="post">
												<div>
													<h5 class="uk-text-bold mb-2">Primary Email</h5>
													<input type="text" class="uk-input" style="font-weight:bold;" disabled placeholder="email@gmail.com" value="<?php echo $users_data['email']; ?>">
												</div>
												<div>
													<h5>&nbsp;</h5>
													<p>Primary Email</p>
												</div>
											</form>
											<?php
										}
										$oquery="SELECT * FROM account_contacts WHERE user_id='".$_COOKIE['uid']."' AND type='email' AND contact!='".$users_data['email']."' AND status=1";
										$oresult=mysqli_query($conn,$oquery);
										if(mysqli_num_rows($oresult)>0)
										{
											?>
											<hr class="m-0">
											<div class="p-3">
												<h5 class="mb-0">Other Emails</h5>
											</div>
											<div class="p-2">
												<table border="1" style="width:100%;" cellspacing="5" cellpadding="5">
													<?php
														while($orow=mysqli_fetch_array($oresult))
														{
															?>
															<tr style="width:100%;">
																<td style="width:90%;"><?php echo $orow['contact']; ?></td><td style="width:10%;"> <i title="Verified" style="cursor:pointer;" class="text-success fa fa-check"></i></td>
															</tr>
															<?php
														}
													?>
												</table>
											</div>
											<?php
										}
									?>
									<hr class="m-0">
									<form class="uk-child-width-1-3@s uk-grid-small p-4" uk-grid action="" method="post">
										<div>
											<h5 class="uk-text-bold mb-2">New Email *</h5>
											<input type="text" class="uk-input" name="add_new_email_to_account" id="add_new_email_to_account" required placeholder="email@gmail.com" value="">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">Verification OTP</h5>
											<input type="text" class="uk-input" name="new_email_otp" id="new_email_otp" required placeholder="Verification OTP" value="">
											
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">&nbsp;</h5>
											<a href="javascript:void(0);" onclick="RequestEmailAddOtp();" class="button primary">Request OTP</a>
											<button type="submit" name="add_new_email_button" id="add_new_email_button" class="button primary" style="float:right !important;">Add</button>
										</div>
									</form>
									<div class="p-3">
										<h5 class="mb-0"> Mobile Settings </h5>
									</div>
									<hr class="m-0">
									<?php
										$oquery="SELECT * FROM account_contacts WHERE user_id='".$_COOKIE['uid']."' AND type='mobile' AND contact!='".$users_data['email']."' AND status=1";
										$oresult=mysqli_query($conn,$oquery);
										if(mysqli_num_rows($oresult)>0)
										{
											?>
											<hr class="m-0">
											<div class="p-3">
												<h5 class="mb-0">Other Mobiles</h5>
											</div>
											<div class="p-2">
												<table border="1" style="width:100%;" cellspacing="5" cellpadding="5">
													<?php
														while($orow=mysqli_fetch_array($oresult))
														{
															?>
															<tr style="width:100%;">
																<td style="width:90%;"><?php echo $orow['country_code']." - ".$orow['contact']; ?></td><td style="width:10%;"> <i title="Verified" style="cursor:pointer;" class="text-success fa fa-check"></i></td>
															</tr>
															<?php
														}
													?>
												</table>
											</div>
											<?php
										}
									?>
									<hr class="m-0">
									<form class="uk-child-width-1-3@s uk-grid-small p-4" uk-grid action="" method="post">
										<div>
											<h5 class="uk-text-bold mb-2"> Country Code </h5>
											<div class="form-item" style="margin:0px;">
												<input id="country_selector" class="uk-input" type="text">
												<label for="country_selector" style="display:none;">Select a country here...</label>
											</div>
											<div class="form-item" style="display:none;">
												<input type="text" id="country_selector_code" name="country_selector_code" data-countrycodeinput="1" readonly="readonly" placeholder="Selected country code will appear here" />
												<label for="country_selector_code">...and the selected country code will be updated here</label>
											</div>
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Mobile </h5>
											<input type="text" name="add_new_mobile_to_account" id="add_new_mobile_to_account" class="uk-input" placeholder="Mobile Number" value="">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">Verification OTP</h5>
											<input type="text" class="uk-input" name="new_mobile_otp" id="new_mobile_otp" required placeholder="Verification OTP" value="">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">&nbsp;</h5>
											<a href="javascript:void(0);" onclick="RequestMobileAddOtp();" class="button primary">Request OTP</a>
											<button type="submit" name="add_new_mobile_button" class="button primary" style="float:right !important;">Save</button>
										</div>
									</form>
									<div class="p-3">
										<h5 class="mb-0"> Social Media Accounts Settings </h5>
									</div>
									<hr class="m-0">
									<form class="uk-child-width-1-3@s uk-grid-small p-4" uk-grid action="" method="post">
										<div>
											<h5 class="uk-text-bold mb-2"> Facebook </h5>
											<input type="text" name="fb_p" id="fb_p" class="uk-input" placeholder="Facebook Profile" value="<?php echo $users_personal_data['fb_p']; ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Twitter </h5>
											<input type="text" name="tw_p" id="tw_p" class="uk-input" placeholder="Twitter Profile" value="<?php echo $users_personal_data['tw_p']; ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Linkedin </h5>
											<input type="text" name="in_p" id="in_p" class="uk-input" placeholder="Linkedin Profile" value="<?php echo $users_personal_data['in_p']; ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Instagram </h5>
											<input type="text" name="ig_p" id="ig_p" class="uk-input" placeholder="Instagram Profile" value="<?php echo $users_personal_data['ig_p']; ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Skype </h5>
											<input type="text" name="skype_p" id="skype_p" class="uk-input" placeholder="Skype Profile" value="<?php echo $users_personal_data['skype_p']; ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Whatsapp </h5>
											<input type="text" name="wa_p" id="wa_p" class="uk-input" placeholder="Whatsapp Number" value="<?php echo $users_personal_data['wa_p']; ?>">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">&nbsp;</h5>
											<button type="submit" name="save_social" class="button primary" style="float:right !important;">Save</button>
										</div>
									</form>
								</div>
								<div class="uk-card-default rounded mt-4 profile_settings" <?php if($Tab_To_Open!=$total_tabs[2]){ echo 'style="display:none;"'; } ?> id="password_settings">
									<div class="p-3">
										<h5 class="mb-0"> Change Password </h5>
									</div>
									<hr class="m-0">
									<form class="uk-child-width-1-1@s uk-grid-small p-4" uk-grid action="" method="post">
										<div style="">
											<h5 class="uk-text-bold mb-2"> Old Password </h5>
											<input type="password" class="uk-input" name="old_password" placeholder="Old Password" style="width:40%;margin-bottom:10px;">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> New Password </h5>
											<input type="password" class="uk-input" name="new_password" placeholder="New Password" style="width:40%;margin-bottom:10px;">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2"> Re-Enter Password </h5>
											<input type="password" class="uk-input" name="confirm_password" placeholder="Re-Enter Password" style="width:40%;margin-bottom:10px;">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">&nbsp;</h5>
											<button type="submit" name="save_password" class="button primary" style="float:left !important;">Save</button>
										</div>
									</form>
									<div class="p-3">
										<h5 class="mb-0"> Retrieve Lost Password </h5>
									</div>
									<hr class="m-0">
									<form class="uk-child-width-1-3@s uk-grid-small p-4" uk-grid action="" method="post">
										<div>
											<h5 class="uk-text-bold mb-2"> Username </h5>
											<input type="text" name="forgot_password_username" id="forgot_password_username" class="uk-input" placeholder="Username">
										</div>
										<div>
											<h5 class="uk-text-bold mb-2">&nbsp;</h5>
											<button type="button" onclick="searchUser('forgot_password_username');" id="get_user_otp_prompt_password_lost" name="get_user_otp_prompt_password_lost" class="button primary" style="float:right !important;">Search Account</button>
										</div>
									</form>
								</div>
								<div class="uk-card-default rounded mt-4 profile_settings" <?php if($Tab_To_Open!=$total_tabs[3]){ echo 'style="display:none;"'; } ?> id="privacy_settings">
									<div class="p-3">
										<h5 class="mb-0"> Privacy Settings</h5>
									</div>
									<hr class="m-0">
									<form class="uk-child-width-1-1@s uk-grid-small p-4" uk-grid action="" method="post" id="privacy_settings_option_form">
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#users_text_post_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Personalise your activities <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Set your post, blogs appearence accross the broadcasts and blog space.
											<hr class="m-0">
										</div>
										<?php
											$who_can_see_broadcast_post_option=getPrivacySetting("who_can_see_broadcast_post_option",$_COOKIE['uid']);
											$who_can_see_blog_post_option=getPrivacySetting("who_can_see_blog_post_option",$_COOKIE['uid']);
											$blog_space_follow_option=getPrivacySetting("blog_space_follow_option",$_COOKIE['uid']);
											$who_can_see_connections=getPrivacySetting("who_can_see_connections",$_COOKIE['uid']);
											$who_can_see_professional_gallery=getPrivacySetting("who_can_see_professional_gallery",$_COOKIE['uid']);
											$who_can_see_personal_gallery=getPrivacySetting("who_can_see_personal_gallery",$_COOKIE['uid']);
										?>
										<div class="w-100" id="users_text_post_settings" style="display:none;">
											<div style="padding-top:20px;padding-bottom:20px;" class="p-4 border_box_section" >
												<h6>Who can see your future broadcast posts? <a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_broadcast_post_option');" style="float:right;">Save</a></h6>
												<table style="width:100%;" class="mt-2 mb-4" cellspacing="5" cellpadding="5" border="1">
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_broadcast_post_option" <?php if($who_can_see_broadcast_post_option=="1,0,0,0,0"){ echo "checked"; } ?> value="1,0,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_broadcast_post_option" <?php if($who_can_see_broadcast_post_option=="0,1,0,0,0"){ echo "checked"; } ?> value="0,1,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Me</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">	
														<th style="width:50%;"><input type="radio" name="who_can_see_broadcast_post_option" <?php if($who_can_see_broadcast_post_option=="0,0,1,0,0"){ echo "checked"; } ?> value="0,0,1,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_broadcast_post_option" <?php if($who_can_see_broadcast_post_option=="0,0,1,1,0"){ echo "checked"; } ?> value="0,0,1,1,0" style="width:15px;height:15px;">&nbsp;&nbsp;Connection's of Connection</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_broadcast_post_option" <?php if($who_can_see_broadcast_post_option=="0,0,1,0,1"){ echo "checked"; } ?> value="0,0,1,0,1" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Allowed</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_broadcast_post_option" <?php if($who_can_see_broadcast_post_option=="0,0,1,0,2"){ echo "checked"; } ?> value="0,0,1,0,2" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Blocked</th>
													</tr>
												</table>
											</div>
											<div style="padding-top:20px;padding-bottom:20px;" class="p-4 border_box_section" >
												<h6>Who can see your future blog space posts?<a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_blog_post_option');" style="float:right;">Save</a></h6>
												<table style="width:100%;" class="mt-2 mb-4" cellspacing="5" cellpadding="5" border="1">
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_blog_post_option" <?php if($who_can_see_blog_post_option=="1,0,0,0,0"){ echo "checked"; } ?> value="1,0,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_blog_post_option" <?php if($who_can_see_blog_post_option=="0,1,0,0,0"){ echo "checked"; } ?> value="0,1,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Me</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">	
														<th style="width:50%;"><input type="radio" name="who_can_see_blog_post_option" <?php if($who_can_see_blog_post_option=="0,0,1,0,0"){ echo "checked"; } ?> value="0,0,1,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_blog_post_option" <?php if($who_can_see_blog_post_option=="0,0,1,1,0"){ echo "checked"; } ?> value="0,0,1,1,0" style="width:15px;height:15px;">&nbsp;&nbsp;Connection's of Connection</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_blog_post_option" <?php if($who_can_see_blog_post_option=="0,0,1,0,1"){ echo "checked"; } ?> value="0,0,1,0,1" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Allowed</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_blog_post_option" <?php if($who_can_see_blog_post_option=="0,0,1,0,2"){ echo "checked"; } ?> value="0,0,1,0,2" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Blocked</th>
													</tr>
												</table>
											</div>
											<div style="padding-top:20px;padding-bottom:20px;" class="p-4 border_box_section" >
												<h6>Who can see peoples, pages, blog spaces your follows?<a href="javascript:void(0);" onclick="savePrivacyForm('blog_space_follow_option');" style="float:right;">Save</a></h6>
												<table style="width:100%;" class="mt-2 mb-4" cellspacing="5" cellpadding="5" border="1">
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="blog_space_follow_option" <?php if($blog_space_follow_option=="1,0,0,0,0"){ echo "checked"; } ?> value="1,0,0,0,0"  style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
														<th style="width:50%;"><input type="radio" name="blog_space_follow_option" <?php if($blog_space_follow_option=="0,1,0,0,0"){ echo "checked"; } ?> value="0,1,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Me</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">	
														<th style="width:50%;"><input type="radio" name="blog_space_follow_option" <?php if($blog_space_follow_option=="0,0,1,0,0"){ echo "checked"; } ?> value="0,0,1,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
														<th style="width:50%;"><input type="radio" name="blog_space_follow_option" <?php if($blog_space_follow_option=="0,0,1,1,0"){ echo "checked"; } ?> value="0,0,1,1,0" style="width:15px;height:15px;">&nbsp;&nbsp;Connection's of Connection</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="blog_space_follow_option" <?php if($blog_space_follow_option=="0,0,1,0,1"){ echo "checked"; } ?> value="0,0,1,0,1" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Allowed</th>
														<th style="width:50%;"><input type="radio" name="blog_space_follow_option" <?php if($blog_space_follow_option=="0,0,1,0,2"){ echo "checked"; } ?> value="0,0,1,0,2" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Blocked</th>
													</tr>
												</table>
											</div>
											<div style="padding-top:20px;padding-bottom:20px;" class="p-4 border_box_section" >
												<h6>Who can see your connections<a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_connections');" style="float:right;">Save</a></h6>
												<table style="width:100%;" class="mt-2 mb-4" cellspacing="5" cellpadding="5" border="1">
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_connections" <?php if($who_can_see_connections=="1,0,0,0,0"){ echo "checked"; } ?> value="1,0,0,0,0"  style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_connections" <?php if($who_can_see_connections=="0,1,0,0,0"){ echo "checked"; } ?> value="0,1,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Me</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">	
														<th style="width:50%;"><input type="radio" name="who_can_see_connections" <?php if($who_can_see_connections=="0,0,1,0,0"){ echo "checked"; } ?> value="0,0,1,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_connections" <?php if($who_can_see_connections=="0,0,1,1,0"){ echo "checked"; } ?> value="0,0,1,1,0" style="width:15px;height:15px;">&nbsp;&nbsp;Connection's of Connection</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_connections" <?php if($who_can_see_connections=="0,0,1,0,1"){ echo "checked"; } ?> value="0,0,1,0,1" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Allowed</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_connections" <?php if($who_can_see_connections=="0,0,1,0,2"){ echo "checked"; } ?> value="0,0,1,0,2" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Blocked</th>
													</tr>
												</table>
											</div>
											<div style="padding-top:20px;padding-bottom:20px;" class="p-4 border_box_section" >
												<h6>Who can see your Personal Gallery<a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_personal_gallery');" style="float:right;">Save</a></h6>
												<table style="width:100%;" class="mt-2 mb-4" cellspacing="5" cellpadding="5" border="1">
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_personal_gallery" <?php if($who_can_see_personal_gallery=="1,0,0,0,0"){ echo "checked"; } ?> value="1,0,0,0,0"  style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_personal_gallery" <?php if($who_can_see_personal_gallery=="0,1,0,0,0"){ echo "checked"; } ?> value="0,1,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Me</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">	
														<th style="width:50%;"><input type="radio" name="who_can_see_personal_gallery" <?php if($who_can_see_personal_gallery=="0,0,1,0,0"){ echo "checked"; } ?> value="0,0,1,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_personal_gallery" <?php if($who_can_see_personal_gallery=="0,0,1,1,0"){ echo "checked"; } ?> value="0,0,1,1,0" style="width:15px;height:15px;">&nbsp;&nbsp;Connection's of Connection</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_personal_gallery" <?php if($who_can_see_personal_gallery=="0,0,1,0,1"){ echo "checked"; } ?> value="0,0,1,0,1" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Allowed</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_personal_gallery" <?php if($who_can_see_personal_gallery=="0,0,1,0,2"){ echo "checked"; } ?> value="0,0,1,0,2" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Blocked</th>
													</tr>
												</table>
											</div>
											<div style="padding-top:20px;padding-bottom:20px;" class="p-4 border_box_section" >
												<h6>Who can see your Professional Gallery<a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_professional_gallery');" style="float:right;">Save</a></h6>
												<table style="width:100%;" class="mt-2 mb-4" cellspacing="5" cellpadding="5" border="1">
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_professional_gallery" <?php if($who_can_see_professional_gallery=="1,0,0,0,0"){ echo "checked"; } ?> value="1,0,0,0,0"  style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_professional_gallery" <?php if($who_can_see_professional_gallery=="0,1,0,0,0"){ echo "checked"; } ?> value="0,1,0,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Me</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">	
														<th style="width:50%;"><input type="radio" name="who_can_see_professional_gallery" <?php if($who_can_see_professional_gallery=="0,0,1,0,0"){ echo "checked"; } ?> value="0,0,1,0,0" style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_professional_gallery" <?php if($who_can_see_professional_gallery=="0,0,1,1,0"){ echo "checked"; } ?> value="0,0,1,1,0" style="width:15px;height:15px;">&nbsp;&nbsp;Connection's of Connection</th>
													</tr>
													<tr style="width:100%;border-bottom:1px solid gray;">
														<th style="width:50%;"><input type="radio" name="who_can_see_professional_gallery" <?php if($who_can_see_professional_gallery=="0,0,1,0,1"){ echo "checked"; } ?> value="0,0,1,0,1" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Allowed</th>
														<th style="width:50%;"><input type="radio" name="who_can_see_professional_gallery" <?php if($who_can_see_professional_gallery=="0,0,1,0,2"){ echo "checked"; } ?> value="0,0,1,0,2" style="width:15px;height:15px;">&nbsp;&nbsp;Specific Blocked</th>
													</tr>
												</table>
											</div>
										</div>	
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#notifications_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;"> How you get your notifications<a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Controls to make sure you only get notified about what's important to you.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="notifications_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('notification_settings');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<th style="width:60%;">Notification Type</th>
													<th style="width:40%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%;">
																<th style="width:33%;">Ropeyou</th>
																<th style="width:33%;">Email</th>
																<!--<th style="width:33%;">Mobile</th>-->
															</tr>
														</table>
													</th>
												</tr>
												<?php
													$ropeyou_conversation_notification_option=getPrivacySetting("ropeyou_conversation_notification_option",$_COOKIE['uid']);
													$email_conversation_notification_option=getPrivacySetting("email_conversation_notification_option",$_COOKIE['uid']);
													$mobile_conversation_notification_option=getPrivacySetting("mobile_conversation_notification_option",$_COOKIE['uid']);
													
												?>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:60%;"><b>Conversation</b><br/>Messages, Posts, Comments</td>
													<td style="width:40%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%;">
																<td style="width:33%;"><input type="checkbox" value="1" name="ropeyou_conversation_notification_option" style="width:15px;height:15px;" <?php if($ropeyou_conversation_notification_option=="1"){ echo "checked"; } ?>></td>
																<td style="width:33%;"><input type="checkbox" value="1" name="email_conversation_notification_option" style="width:15px;height:15px;" <?php if($email_conversation_notification_option=="1"){ echo "checked"; } ?>></td>
																<!--<td style="width:33%;"><input type="checkbox" value="1" name="mobile_conversation_notification_option" style="width:15px;height:15px;" <?php if($mobile_conversation_notification_option=="1"){ echo "checked"; } ?>></td>-->
															</tr>
														</table>
													</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:60%;"><b>Jobs</b><br/>Job Activities, Hiring Insights</td>
													<td style="width:40%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<?php
																$ropeyou_job_activity_notification_option=getPrivacySetting("ropeyou_job_activity_notification_option",$_COOKIE['uid']);
																$email_job_activity_notification_option=getPrivacySetting("email_job_activity_notification_option",$_COOKIE['uid']);
																$mobile_job_activity_notification_option=getPrivacySetting("mobile_job_activity_notification_option",$_COOKIE['uid']);
															?>
															<tr style="width:100%;">
																<td style="width:33%;"><input type="checkbox" value="1" name="ropeyou_job_activity_notification_option" style="width:15px;height:15px;" <?php if($ropeyou_job_activity_notification_option=="1"){ echo "checked"; } ?>></td>
																<td style="width:33%;"><input type="checkbox" value="1" name="email_job_activity_notification_option" style="width:15px;height:15px;" <?php if($email_job_activity_notification_option=="1"){ echo "checked"; } ?>></td>
																<!--<td style="width:33%;"><input type="checkbox" value="1" name="mobile_job_activity_notification_option" style="width:15px;height:15px;" <?php if($mobile_job_activity_notification_option=="1"){ echo "checked"; } ?>></td>-->
															</tr>
														</table>
													</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:60%;"><b>Network</b><br/>Events, Anniversaries, Invites, Birthdays</td>
													<td style="width:40%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<?php
																$ropeyou_events_invite_notification_option=getPrivacySetting("ropeyou_events_invite_notification_option",$_COOKIE['uid']);
																$email_events_invite_notification_option=getPrivacySetting("email_events_invite_notification_option",$_COOKIE['uid']);
																$mobile_events_invite_notification_option=getPrivacySetting("mobile_events_invite_notification_option",$_COOKIE['uid']);
															
															?>
															<tr style="width:100%;">
																<td style="width:33%;"><input type="checkbox" value="1" name="ropeyou_events_invite_notification_option" style="width:15px;height:15px;" <?php if($ropeyou_events_invite_notification_option=="1"){ echo "checked"; } ?>></td>
																<td style="width:33%;"><input type="checkbox" value="1" name="email_events_invite_notification_option" style="width:15px;height:15px;" <?php if($email_events_invite_notification_option=="1"){ echo "checked"; } ?>></td>
																<!--<td style="width:33%;"><input type="checkbox" value="1" name="mobile_events_invite_notification_option" style="width:15px;height:15px;" <?php if($mobile_events_invite_notification_option=="1"){ echo "checked"; } ?>></td>-->
															</tr>
														</table>
													</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:60%;"><b>Profile</b><br/>Endorsement, Profile Views</td>
													<td style="width:40%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<?php
																$ropeyou_profile_endorsement_notification_option=getPrivacySetting("ropeyou_profile_endorsement_notification_option",$_COOKIE['uid']);
																$email_profile_endorsement_notification_option=getPrivacySetting("email_profile_endorsement_notification_option",$_COOKIE['uid']);
																$mobile_profile_endorsement_notification_option=getPrivacySetting("mobile_profile_endorsement_notification_option",$_COOKIE['uid']);
															
															?>
															<tr style="width:100%;">
																<td style="width:33%;"><input type="checkbox" value="1" name="ropeyou_profile_endorsement_notification_option" style="width:15px;height:15px;" <?php if($ropeyou_profile_endorsement_notification_option=="1"){ echo "checked"; } ?>></td>
																<td style="width:33%;"><input type="checkbox" value="1" name="email_profile_endorsement_notification_option" style="width:15px;height:15px;" <?php if($email_profile_endorsement_notification_option=="1"){ echo "checked"; } ?>></td>
																<!--<td style="width:33%;"><input type="checkbox" value="1" name="mobile_profile_endorsement_notification_option" style="width:15px;height:15px;" <?php if($mobile_profile_endorsement_notification_option=="1"){ echo "checked"; } ?>></td>-->
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#profile_viewing_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;"> Profile Viewing Options <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose whether you are visible or viewing in private mode.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="profile_viewing_settings">
											<?php
												$user_profile_picture=getUserProfileImage($_COOKIE['uid']);
												$users_data=getUsersData($_COOKIE['uid']);
												$profile_viewing_option=getPrivacySetting("profile_viewing_option",$_COOKIE['uid']);
											?>
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('profile_viewing_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<th style="width:20%;">Mode</th>
													<th style="width:80%;">
														Visibility
													</th>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:20%;"><input type="radio" name="profile_viewing_option" style="width:15px;height:15px;" <?php if($profile_viewing_option=="1"){ echo "checked"; } ?>>&nbsp; Public</td>
													<td style="width:80%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%">
																<td style="width:10%;">
																	<img src="<?php echo $user_profile_picture; ?>" value="1" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																</td>
																<td style="width:90%;">
																	<a href="<?php echo base_url.'u/'.strtolower($users_data['username']); ?>"><?php echo ucwords(strtolower($users_data['first_name']." ".$users_data['last_name'])); ?></a><br/>
																	<?php if($users_data['profile_title']!=""){ echo $users_data['profile_title']; } ?><br/>
																	<?php echo getUsersCurrentDesignation($_COOKIE['uid']); ?><br/>
																	<?php echo getUsersCurrentEducation($_COOKIE['uid']); ?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:20%;"><input type="radio" name="profile_viewing_option" value="2" style="width:15px;height:15px;" <?php if($profile_viewing_option=="2"){ echo "checked"; } ?>>&nbsp; Protected</td>
													<td style="width:80%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%">
																<td style="width:10%;">
																	<img src="<?php echo base_url; ?>uploads/default.png" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																</td>
																<td style="width:90%;">
																	<?php if($users_data['profile_title']!=""){ echo $users_data['profile_title']; } ?><br/>
																	<?php echo getUsersCurrentDesignation($_COOKIE['uid']); ?><br/>
																	<?php echo getUsersCurrentEducation($_COOKIE['uid']); ?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:20%;"><input type="radio" name="profile_viewing_option" value="0" style="width:15px;height:15px;" <?php if($profile_viewing_option=="0"){ echo "checked"; } ?>>&nbsp; Private</td>
													<td style="width:80%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%">
																<td style="width:10%;">
																	<img src="<?php echo base_url; ?>uploads/default.png" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																</td>
																<td style="width:90%;">
																	Anonymous User From Ropeyou
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#broadcasts_viewing_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;"> Broadcasts Viewing Options <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose whether you are visible or viewing in private mode.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="broadcasts_viewing_settings">
											<?php
												$user_profile_picture=getUserProfileImage($_COOKIE['uid']);
												$users_data=getUsersData($_COOKIE['uid']);
												$broadcasts_viewing_option=getPrivacySetting("broadcasts_viewing_option",$_COOKIE['uid']);
											?>
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('broadcasts_viewing_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<th style="width:20%;">Mode</th>
													<th style="width:80%;">
														Visibility
													</th>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:20%;"><input type="radio" value="1" name="broadcasts_viewing_option" style="width:15px;height:15px;" <?php if($broadcasts_viewing_option=="1"){ echo "checked"; } ?>>&nbsp; Public</td>
													<td style="width:80%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%">
																<td style="width:10%;">
																	<img src="<?php echo $user_profile_picture; ?>" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																</td>
																<td style="width:90%;">
																	<a href="<?php echo base_url.'u/'.strtolower($users_data['username']); ?>"><?php echo ucwords(strtolower($users_data['first_name']." ".$users_data['last_name'])); ?></a><br/>
																	<?php if($users_data['profile_title']!=""){ echo $users_data['profile_title']; } ?><br/>
																	<?php echo getUsersCurrentDesignation($_COOKIE['uid']); ?><br/>
																	<?php echo getUsersCurrentEducation($_COOKIE['uid']); ?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:20%;"><input type="radio" value="2" name="broadcasts_viewing_option" style="width:15px;height:15px;" <?php if($broadcasts_viewing_option=="2"){ echo "checked"; } ?>>&nbsp; Protected</td>
													<td style="width:80%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%">
																<td style="width:10%;">
																	<img src="<?php echo base_url; ?>uploads/default.png" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																</td>
																<td style="width:90%;">
																	<?php if($users_data['profile_title']!=""){ echo $users_data['profile_title']; } ?><br/>
																	<?php echo getUsersCurrentDesignation($_COOKIE['uid']); ?><br/>
																	<?php echo getUsersCurrentEducation($_COOKIE['uid']); ?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:20%;"><input type="radio" value="0" name="broadcasts_viewing_option" style="width:15px;height:15px;" <?php if($broadcasts_viewing_option=="0"){ echo "checked"; } ?>>&nbsp; Private</td>
													<td style="width:80%;">
														<table style="width:100%;" cellspacing="5" cellpadding="5">
															<tr style="width:100%">
																<td style="width:10%;">
																	<img src="<?php echo base_url; ?>uploads/default.png" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																</td>
																<td style="width:90%;">
																	Anonymous User From Ropeyou
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#blocked_users_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;"> Blocked Users <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Have a look at the users, you have blocked.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="blocked_users_settings">
											<?php
												$bquery="SELECT * FROM user_blocked_user WHERE r_user_id='".$_COOKIE['uid']."'";
												$bresult=mysqli_query($conn,$bquery);
												$blocked_user_arr=array();
												if(mysqli_num_rows($bresult)>0)
												{
													?>
													<table cellspacing="5" cellpadding="5" style="width:100%">
														<tr style="width:100%;border-bottom:1px solid gray;">
															<th style="width:80%;">User Detail</th>
															<th style="width:20%;text-align:right;">Action</th>
														</tr>
														<?php
															while($brow=mysqli_fetch_array($bresult))
															{
																$user_blocked=$brow['user_id'];
																$blocked_user_arr[]=$user_blocked;
																$blocked_user_data=getUsersData($user_blocked);
																$blocked_user_image=getUserProfileImage($user_blocked);
																?>
																<tr style="width:100%;border-bottom:1px solid gray;">
																	<td style="width:80%;">
																		<table cellspacing="5" cellpadding="5" style="width:100%">
																			<tr style="width:100%;">
																				<td style="width:10%;">
																					<img src="<?php echo $blocked_user_image; ?>" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																				</td>
																				<td style="width:90%;">
																					<a href="<?php echo base_url.'u/'.strtolower($blocked_user_data['username']); ?>"><?php echo ucwords(strtolower($blocked_user_data['first_name']." ".$blocked_user_data['last_name'])); ?></a><br/>
																					<?php if($blocked_user_data['profile_title']!=""){ echo $blocked_user_data['profile_title']; } ?><br/>
																					<?php echo getUsersCurrentDesignation($user_blocked); ?><br/>
																					<?php echo getUsersCurrentEducation($user_blocked); ?>
																				</td>
																			</tr>
																		</table>
																	</td>
																	<td style="width:20%;text-align:right;"><button class="btn btn-info" type="button">Unblock</button></td>
																</tr>
																<?php
															}
														?>
													</table>
													<?php
												}
												else
												{
													?>
													<p style="text-align:center;">There is no more blocked users.</p>
													<?php
												}
												$blocked_user_arr[]=$_COOKIE['uid'];
												$blockable_user_query="SELECT * FROM user_joins_user WHERE (user_id='".$_COOKIE['uid']."' AND r_user_id NOT IN('".implode("','",$blocked_user_arr)."')) OR (r_user_id='".$_COOKIE['uid']."' AND user_id NOT IN('".implode("','",$blocked_user_arr)."'))";
												$blockable_user_result=mysqli_query($conn,$blockable_user_query);
												if($blockable_user_result>0)
												{
													?>
													<div class="p-3">
														<h5 class="mb-0"> Block Users</h5>
													</div>
													<table style="width:100%;max-height:500px;overflow-y:auto;" cellspacing="5" cellpadding="5">
														<tr style="width:100%;border-bottom:1px solid gray;">
															<th style="width:80%;">User's Detail</th>
															<th style="width:20%;text-align:right;">Action</th>
														</tr>
														<?php
															while($brow=mysqli_fetch_array($blockable_user_result))
															{
																$user_blocked=$brow['user_id'];
																if($_COOKIE['uid']==$brow['user_id'])
																{
																	$user_blocked=$brow['r_user_id'];
																}
																$blocked_user_arr[]=$user_blocked;
																$blocked_user_data=getUsersData($user_blocked);
																$blocked_user_image=getUserProfileImage($user_blocked);
																?>
																<tr style="width:100%;border-bottom:1px solid gray;">
																	<td style="width:80%;">
																		<table cellspacing="5" cellpadding="5" style="width:100%">
																			<tr style="width:100%;">
																				<td style="width:10%;">
																					<img src="<?php echo $blocked_user_image; ?>" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
																				</td>
																				<td style="width:90%;">
																					<a href="<?php echo base_url.'u/'.strtolower($blocked_user_data['username']); ?>"><?php echo ucwords(strtolower($blocked_user_data['first_name']." ".$blocked_user_data['last_name'])); ?></a><br/>
																					<?php if($blocked_user_data['profile_title']!=""){ echo $blocked_user_data['profile_title']; } ?><br/>
																					<?php echo getUsersCurrentDesignation($user_blocked); ?><br/>
																					<?php echo getUsersCurrentEducation($user_blocked); ?>
																				</td>
																			</tr>
																		</table>
																	</td>
																	<td style="width:20%;text-align:right;"><button class="btn btn-info" type="button">Block</button></td>
																</tr>
																<?php
															}
														?>
													</table>
													<?php
												}
											?>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#text_resume_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Text Resume Settings  <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can download Text Resume
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="text_resume_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_download_text_resume');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_download_text_resume=getPrivacySetting("who_can_download_text_resume",$_COOKIE['uid']); ?>
													<th style="width:25%;"><input type="radio" name="who_can_download_text_resume" value="3" <?php if($who_can_download_text_resume=="3"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Upon Request</th>
													<th style="width:25%;"><input type="radio" name="who_can_download_text_resume" value="2" <?php if($who_can_download_text_resume=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:25%;"><input type="radio" name="who_can_download_text_resume" value="1" <?php if($who_can_download_text_resume=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:25%;"><input type="radio" name="who_can_download_text_resume" value="0" <?php if($who_can_download_text_resume=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#web_view_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;"> Web View Settings <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose how your web view looks to the world
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="web_view_settings">
											<a href="javascript:void(0);" onclick="savePrivacyForm('web_view_settings');" style="float:right;">Save</a>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<th style="width:10%;">Display</th>
													<th style="width:90%;">Module</th>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php 
														$web_view_about_included=getPrivacySetting("web_view_about_included",$_COOKIE['uid']);
														$web_view_work_experience_included=getPrivacySetting("web_view_work_experience_included",$_COOKIE['uid']);
														$web_view_education_included=getPrivacySetting("web_view_education_included",$_COOKIE['uid']);
														$web_view_skill_included=getPrivacySetting("web_view_skill_included",$_COOKIE['uid']);
														$web_view_interest_included=getPrivacySetting("web_view_interest_included",$_COOKIE['uid']);
														$web_view_acheivement_included=getPrivacySetting("web_view_acheivement_included",$_COOKIE['uid']);
														$web_view_social_presence_included=getPrivacySetting("web_view_social_presence_included",$_COOKIE['uid']);
														$web_view_contact_included=getPrivacySetting("web_view_contact_included",$_COOKIE['uid']);
														$web_view_text_resume_link_included=getPrivacySetting("web_view_text_resume_link_included",$_COOKIE['uid']);
														$web_view_video_cv_included=getPrivacySetting("web_view_video_cv_included",$_COOKIE['uid']);
														$web_view_featured_blog_included=getPrivacySetting("web_view_featured_blog_included",$_COOKIE['uid']);
														$web_view_professional_gallery_included=getPrivacySetting("web_view_professional_gallery_included",$_COOKIE['uid']);
														$web_view_personal_gallery_included=getPrivacySetting("web_view_personal_gallery_included",$_COOKIE['uid']);
													?>
													
													<td style="width:10%;">
														<input type="checkbox" <?php if($web_view_about_included=="1"){ echo "checked"; } ?> value="1" name="web_view_about_included" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include About Information</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_work_experience_included" <?php if($web_view_work_experience_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Work Experiences</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_education_included" <?php if($web_view_education_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Education</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_skill_included" <?php if($web_view_skill_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Skills</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_interest_included" <?php if($web_view_interest_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Interests</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_acheivement_included" <?php if($web_view_acheivement_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Achievements</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_social_presence_included" <?php if($web_view_social_presence_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Social Presence</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_contact_included" <?php if($web_view_contact_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Contact Details</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_text_resume_link_included" <?php if($web_view_text_resume_link_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Text Resume Link</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_video_cv_included" <?php if($web_view_video_cv_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Video CV</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_featured_blog_included" <?php if($web_view_featured_blog_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Featured Blogs</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_professional_gallery_included" <?php if($web_view_professional_gallery_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Professional Gallery</td>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<td style="width:10%;">
														<input type="checkbox" name="web_view_personal_gallery_included" <?php if($web_view_personal_gallery_included=="1"){ echo "checked"; } ?> value="1" style="width:15px;height:15px;">
													</td>
													<td style="width:10%;">Include Personal Gallery</td>
												</tr>
											</table>
										</div>
										
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#email_download_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Who can see or download your email address <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can see or download your email address in data exports.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="email_download_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_email_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_see_email_option=getPrivacySetting("who_can_see_email_option",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_see_email_option" value="2" <?php if($who_can_see_email_option=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_see_email_option" value="1" <?php if($who_can_see_email_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_see_email_option" value="0" <?php if($who_can_see_email_option=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#mobile_download_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Who can see or download your mobile <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can see or download your mobile in data exports.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="mobile_download_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_mobile_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_see_mobile_option=getPrivacySetting("who_can_see_mobile_option",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_see_mobile_option" value="2" <?php if($who_can_see_mobile_option=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_see_mobile_option" value="1" <?php if($who_can_see_mobile_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_see_mobile_option" value="0" <?php if($who_can_see_mobile_option=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#email_visibility_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Manage who can discover your profile from your email address <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can discover your profile if they haven't connected with you, but have your email address.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="email_visibility_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_search_using_email');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_search_using_email=getPrivacySetting("who_can_search_using_email",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_search_using_email" value="2" <?php if($who_can_search_using_email=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_search_using_email" value="1" <?php if($who_can_search_using_email=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_search_using_email" value="0" <?php if($who_can_search_using_email=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#mobile_visibility_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Manage who can discover your profile from your phone number <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can discover your profile if they haven't connected with you, but have your phone number.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="mobile_visibility_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_search_using_mobile');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_search_using_mobile=getPrivacySetting("who_can_search_using_mobile",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_search_using_mobile" value="2" <?php if($who_can_search_using_mobile=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_search_using_mobile" value="1" <?php if($who_can_search_using_mobile=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_search_using_mobile" value="0" <?php if($who_can_search_using_mobile=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#active_status_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Manage active status<a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can see when you are on RopeYou.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="active_status_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_see_on_ropeyou');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_see_on_ropeyou=getPrivacySetting("who_can_see_on_ropeyou",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_see_on_ropeyou" value="2" <?php if($who_can_see_on_ropeyou=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_see_on_ropeyou" value="1" <?php if($who_can_see_on_ropeyou=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_see_on_ropeyou" value="0" <?php if($who_can_see_on_ropeyou=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#post_update_share_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Share job changes, education changes, work anniversaries, and birth anniversaries from profile <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose whether your network is notified.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="post_update_share_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('birth_anniversary_share_option,work_anniversary_share_option,job_changes_share_option,education_changes_share_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $job_changes_share_option=getPrivacySetting("job_changes_share_option",$_COOKIE['uid']); ?>
													<th style="width:10%;"><input type="checkbox" value="1" <?php if($job_changes_share_option=="1"){ echo "checked"; } ?> name="job_changes_share_option" style="width:15px;height:15px;"></th>
													<th style="width:90%;">Share my job changes to my connections.</th>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $education_changes_share_option=getPrivacySetting("education_changes_share_option",$_COOKIE['uid']); ?>
													<th style="width:10%;"><input type="checkbox" name="education_changes_share_option" value="1" <?php if($education_changes_share_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;"></th>
													<th style="width:90%;">Share my education changes to my connections.</th>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $work_anniversary_share_option=getPrivacySetting("work_anniversary_share_option",$_COOKIE['uid']); ?>
													<th style="width:10%;"><input type="checkbox" name="work_anniversary_share_option" value="1" <?php if($work_anniversary_share_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;"></th>
													<th style="width:90%;">Share my work anniversaries to my connections.</th>
												</tr>
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $birth_anniversary_share_option=getPrivacySetting("birth_anniversary_share_option",$_COOKIE['uid']); ?>
													<th style="width:10%;"><input type="checkbox" name="birth_anniversary_share_option" value="1" <?php if($birth_anniversary_share_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;"></th>
													<th style="width:90%;">Share my birthday anniversaries to my connections.</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#mention_notification_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Notify connections when you're in the news <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose whether we notify people in your network that you’ve been mentioned in an article or blog post
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="mention_notification_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('on_mention_connection_notification_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $on_mention_connection_notification_option=getPrivacySetting("on_mention_connection_notification_option",$_COOKIE['uid']); ?>
													<th style="width:10%;"><input type="checkbox" name="on_mention_connection_notification_option" value="1" <?php if($on_mention_connection_notification_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;"></th>
													<th style="width:90%;">Notify my connections when some one mention me in post, media, or blog.</th>
												</tr>
											</table>
										</div>
										
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#who_can_mention_you_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Mentioned by others<a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose whether other members can mention you
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="who_can_mention_you_settings">
											<h5> <a href="javascript:void(0);" onclick="savePrivacyForm('who_can_mention_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_mention_option=getPrivacySetting("who_can_mention_option",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_mention_option" value="2" <?php if($who_can_mention_option=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_mention_option" value="1" <?php if($who_can_mention_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_mention_option" value="0" <?php if($who_can_mention_option=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#who_can_follow_you_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Followers <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can follow you and see your public updates
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="who_can_follow_you_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_follow_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_follow_option=getPrivacySetting("who_can_follow_option",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_follow_option" value="2" <?php if($who_can_follow_option=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_follow_option" value="1" <?php if($who_can_follow_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_follow_option" value="0" <?php if($who_can_follow_option=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
										<div style="cursor:pointer;" title="Click to toggle" onclick="$('#who_can_connect_you_settings').toggle('display');">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Connect Requests <a href="javascript:void(0);" style="height:25px;width:25px;border:1px solid #000;border-radius:50%;padding:5px;float:right;padding-left:6px;padding-top:3px;"><i class="fa fa-angle-down"></i></a></h5>
											Choose who can connect you.
											<hr class="m-0">
										</div>
										<div style="padding-top:20px;padding-bottom:20px;display:none;" class="p-4 border_box_section" id="who_can_connect_you_settings">
											<h5><a href="javascript:void(0);" onclick="savePrivacyForm('who_can_connect_option');" style="float:right;">Save</a></h5>
											<table style="width:100%;" cellspacing="5" cellpadding="5">
												<tr style="width:100%;border-bottom:1px solid gray;">
													<?php $who_can_connect_option=getPrivacySetting("who_can_connect_option",$_COOKIE['uid']); ?>
													<th style="width:33%;"><input type="radio" name="who_can_connect_option" value="2" <?php if($who_can_connect_option=="2"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Only Connections</th>
													<th style="width:33%;"><input type="radio" name="who_can_connect_option" value="1" <?php if($who_can_connect_option=="1"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;Anyone</th>
													<th style="width:33%;"><input type="radio" name="who_can_connect_option" value="0" <?php if($who_can_connect_option=="0"){ echo "checked"; } ?> style="width:15px;height:15px;">&nbsp;&nbsp;No One</th>
												</tr>
											</table>
										</div>
									</form>
								</div>
								<div class="uk-card-default rounded mt-4 profile_settings" <?php if($Tab_To_Open!=$total_tabs[4]){ echo 'style="display:none;"'; } ?> id="account_settings">
									<div class="p-3">
										<h5 class="mb-0"> Account Settings</h5>
									</div>
									<hr class="m-0">
									<form class="uk-child-width-1-1@s uk-grid-small p-4" uk-grid action="" method="post" id="account_delete_form">
										<div style="cursor:pointer;">
											<h5 class="uk-text-bold mb-2" style="width:100%;">Manage Your Account Settings.</h5>
											Please tell us your concern for deleting account.
											<hr class="m-0">
											
										</div>
									</form>
									<div class="p-2">
										<div class="p-3 border_box_section">
											<form action="" method="post">
												<table style="width:100%;" class="mt-2 mb-4" border="1" cellspacing="5" cellpadding="5">
													<tr style="width:100%">
														<td style="width:10%;">
															<input type="radio" required id="delete_account_option" style="width:20px;height:20px;" name="delete_account_option" checked value="0">
														</td>
														<td style="width:90%;">
															<h5>Delete My Account Temporary</h5>
															<p>I want to take a break. I will come back later.Keep my data as it is and close my account temporarily.</p>
														</td>
													</tr>
													<tr style="width:100%">
														<td style="width:10%;">
															<input type="radio" required id="delete_account_option" style="width:20px;height:20px;" name="delete_account_option" value="1">
														</td>
														<td style="width:90%;">
															<h5>Delete My Account Permanently</h5>
															<p>I want to permanently close my account.</p>
														</td>
													</tr>
													<tr style="width:100%">
														<td style="width:100%;text-align:center;" align="center" colspan="2">
															<button type="submit" name="close_account" class="btn btn-danger">Delete Account</button>
														</td>
													</tr>
												</table>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
	</body>
	<!--<script src="<?php echo base_url.'chat_bot/'; ?>js/framework.js"></script>
	<script src="<?php echo base_url.'chat_bot/'; ?>js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url.'chat_bot/'; ?>js/simplebar.js"></script>
	<script src="<?php echo base_url.'chat_bot/'; ?>js/main.js"></script>-->
	<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
	<script src="<?php echo base_url.''; ?>country-picker/build/js/countrySelect.js"></script>
	<script>
		var base_url="<?php echo base_url; ?>";
		function RequestEmailAddOtp()
		{
			var new_email=$("#add_new_email_to_account").val();
			if(new_email!="")
			{
				$.ajax({
					url:base_url+"add-new-email-to-account",
					type:"post",
					data:{new_email:new_email},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							//$("#retrieve_password").modal("hide");
							alert("An OTP has been sent to your email.");
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
				alert("Please enter an email.");
			}
		}
		function RequestMobileAddOtp()
		{
			var new_mobile=$("#add_new_mobile_to_account").val();
			var country_code=$("#country_selector_code").val();
			if(new_mobile!="" && country_code!="")
			{
				$.ajax({
					url:base_url+"add-new-mobile-to-account",
					type:"post",
					data:{new_mobile:new_mobile,country_code:country_code},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							//$("#retrieve_password").modal("hide");
							alert("An OTP has been sent to your mobile.");
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
				alert("Please enter an email.");
			}
		}
		function sendPasswordResetEmail()
		{
			var hash=$("#password_reset_hash").val();
			if(hash!="")
			{
				$.ajax({
					url:base_url+"send_password_retrieve_email",
					type:"post",
					data:{reset_hash:hash},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							$("#retrieve_password").modal("hide");
							alert("A password reset link has been sent to registered email or mobile");
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
				alert("something went wrong please try again.");
			}
		}
		function searchUser(search_input)
		{
			var username=$("#"+search_input).val();
			if(username!="")
			{
				$.ajax({
					url:base_url+"search-user-account",
					type:"post",
					data:{username:username},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							//console.log(parsedJson);
							$("#retrieve_password_html").html(parsedJson.html);
							$("#retrieve_password").modal("show");
						}
						else
						{
							alert(parsedJson.message);
						}
					}
				});
			}
		}
		function savePrivacyForm(options)
		{
			var post_data={};
			if(options=="notification_settings")
			{
				var ropeyou_conversation_notification_option=0;
				var email_conversation_notification_option=0;
				var mobile_conversation_notification_option=0;
				
				var ropeyou_job_activity_notification_option=0;
				var email_job_activity_notification_option=0;
				var mobile_job_activity_notification_option=0;
				
				var ropeyou_events_invite_notification_option=0;
				var email_events_invite_notification_option=0;
				var mobile_events_invite_notification_option=0;
				
				var ropeyou_profile_endorsement_notification_option=0;
				var email_profile_endorsement_notification_option=0;
				var mobile_profile_endorsement_notification_option=0;
				
				
				
				if($("input[name^='ropeyou_conversation_notification_option']").is(":checked"))
				{
					ropeyou_conversation_notification_option=$("input[name^='ropeyou_conversation_notification_option']").val();
				}
				if($("input[name^='email_conversation_notification_option']").is(":checked"))
				{
					email_conversation_notification_option=$("input[name^='email_conversation_notification_option']").val();
				}
				if($("input[name^='mobile_conversation_notification_option']").is(":checked"))
				{
					mobile_conversation_notification_option=$("input[name^='mobile_conversation_notification_option']").val();
				}
				
				if($("input[name^='ropeyou_job_activity_notification_option']").is(":checked"))
				{
					ropeyou_job_activity_notification_option=$("input[name^='ropeyou_job_activity_notification_option']").val();
				}
				if($("input[name^='email_job_activity_notification_option']").is(":checked"))
				{
					email_job_activity_notification_option=$("input[name^='email_job_activity_notification_option']").val();
				}
				if($("input[name^='mobile_job_activity_notification_option']").is(":checked"))
				{
					mobile_job_activity_notification_option=$("input[name^='mobile_job_activity_notification_option']").val();
				}
				
				if($("input[name^='ropeyou_events_invite_notification_option']").is(":checked"))
				{
					ropeyou_events_invite_notification_option=$("input[name^='ropeyou_events_invite_notification_option']").val();
				}
				if($("input[name^='email_events_invite_notification_option']").is(":checked"))
				{
					email_events_invite_notification_option=$("input[name^='email_events_invite_notification_option']").val();
				}
				if($("input[name^='mobile_events_invite_notification_option']").is(":checked"))
				{
					mobile_events_invite_notification_option=$("input[name^='mobile_events_invite_notification_option']").val();
				}
				
				if($("input[name^='ropeyou_profile_endorsement_notification_option']").is(":checked"))
				{
					ropeyou_profile_endorsement_notification_option=$("input[name^='ropeyou_profile_endorsement_notification_option']").val();
				}
				if($("input[name^='email_profile_endorsement_notification_option']").is(":checked"))
				{
					email_profile_endorsement_notification_option=$("input[name^='email_profile_endorsement_notification_option']").val();
				}
				if($("input[name^='mobile_profile_endorsement_notification_option']").is(":checked"))
				{
					mobile_profile_endorsement_notification_option=$("input[name^='mobile_profile_endorsement_notification_option']").val();
				}
				
				post_data['ropeyou_conversation_notification_option']=ropeyou_conversation_notification_option;
				post_data['email_conversation_notification_option']=email_conversation_notification_option;
				post_data['mobile_conversation_notification_option']=mobile_conversation_notification_option;
				
				
				post_data['ropeyou_job_activity_notification_option']=ropeyou_job_activity_notification_option;
				post_data['email_job_activity_notification_option']=email_job_activity_notification_option;
				post_data['mobile_job_activity_notification_option']=mobile_job_activity_notification_option;
				
				post_data['ropeyou_events_invite_notification_option']=ropeyou_events_invite_notification_option;
				post_data['email_events_invite_notification_option']=email_events_invite_notification_option;
				post_data['mobile_events_invite_notification_option']=mobile_events_invite_notification_option;
				
				post_data['ropeyou_profile_endorsement_notification_option']=ropeyou_profile_endorsement_notification_option;
				post_data['email_profile_endorsement_notification_option']=email_profile_endorsement_notification_option;
				post_data['mobile_profile_endorsement_notification_option']=mobile_profile_endorsement_notification_option;
			}
			else if(options=="web_view_settings")
			{
				var web_view_about_included=0;
				var web_view_work_experience_included=0;
				var web_view_education_included=0;
				var web_view_acheivement_included=0;
				var web_view_interest_included=0;
				var web_view_skill_included=0;
				var web_view_video_cv_included=0;
				var web_view_text_resume_link_included=0;
				var web_view_contact_included=0;
				var web_view_social_presence_included=0;
				var web_view_personal_gallery_included=0;
				var web_view_professional_gallery_included=0;
				var web_view_featured_blog_included=0;
				
				if($("input[name^='web_view_about_included']").is(":checked"))
				{
					web_view_about_included=$("input[name^='web_view_about_included']").val();
				}
				if($("input[name^='web_view_work_experience_included']").is(":checked"))
				{
					web_view_work_experience_included=$("input[name^='web_view_work_experience_included']").val();
				}
				if($("input[name^='web_view_education_included']").is(":checked"))
				{
					web_view_education_included=$("input[name^='web_view_education_included']").val();
				}
				
				if($("input[name^='web_view_acheivement_included']").is(":checked"))
				{
					web_view_acheivement_included=$("input[name^='web_view_acheivement_included']").val();
				}
				if($("input[name^='web_view_featured_blog_included']").is(":checked"))
				{
					web_view_featured_blog_included=$("input[name^='web_view_featured_blog_included']").val();
				}
				if($("input[name^='web_view_professional_gallery_included']").is(":checked"))
				{
					web_view_professional_gallery_included=$("input[name^='web_view_professional_gallery_included']").val();
				}
				
				if($("input[name^='web_view_personal_gallery_included']").is(":checked"))
				{
					web_view_personal_gallery_included=$("input[name^='web_view_personal_gallery_included']").val();
				}
				if($("input[name^='web_view_social_presence_included']").is(":checked"))
				{
					web_view_social_presence_included=$("input[name^='web_view_social_presence_included']").val();
				}
				if($("input[name^='web_view_contact_included']").is(":checked"))
				{
					web_view_contact_included=$("input[name^='web_view_contact_included']").val();
				}
				
				if($("input[name^='web_view_text_resume_link_included']").is(":checked"))
				{
					web_view_text_resume_link_included=$("input[name^='web_view_text_resume_link_included']").val();
				}
				if($("input[name^='web_view_video_cv_included']").is(":checked"))
				{
					web_view_video_cv_included=$("input[name^='web_view_video_cv_included']").val();
				}
				if($("input[name^='web_view_interest_included']").is(":checked"))
				{
					web_view_interest_included=$("input[name^='web_view_interest_included']").val();
				}
				
				if($("input[name^='web_view_skill_included']").is(":checked"))
				{
					web_view_skill_included=$("input[name^='web_view_skill_included']").val();
				}
				
				post_data['web_view_about_included']=web_view_about_included;
				post_data['web_view_work_experience_included']=web_view_work_experience_included;
				post_data['web_view_education_included']=web_view_education_included;
				
				
				post_data['web_view_acheivement_included']=web_view_acheivement_included;
				post_data['web_view_interest_included']=web_view_interest_included;
				post_data['web_view_skill_included']=web_view_skill_included;
				
				post_data['web_view_video_cv_included']=web_view_video_cv_included;
				post_data['web_view_text_resume_link_included']=web_view_text_resume_link_included;
				post_data['web_view_contact_included']=web_view_contact_included;
				
				post_data['web_view_social_presence_included']=web_view_social_presence_included;
				post_data['web_view_personal_gallery_included']=web_view_personal_gallery_included;
				post_data['web_view_professional_gallery_included']=web_view_professional_gallery_included;
				post_data['web_view_featured_blog_included']=web_view_featured_blog_included;
			}
			else
			{
				var options_arr=options.split(",");
				for(i=0;i<options_arr.length;i++)
				{
					post_data[options_arr[i]]="";
					post_data[options_arr[i]]=$("input[name^='"+options_arr[i]+"']:checked").val();
				}
				
			}
			//console.log(post_data);
			$.ajax({
				url:base_url+"update-privacy-options",
				type:"post",
				data:post_data,
				success:function(response)
				{
					var parsedJson=JSON.parse(response);
					if(parsedJson.status=="success")
					{
						alert("Privacy has been updated.");
					}
					else{
						alert("Something went wrong.");
					}
				}
			});
		}
		$("#country_selector").countrySelect({
			preferredCountries: ['in', 'uk', 'us',"jpn","aus"]
		});
		function openTab(dom_element)
		{
			localStorage.setItem("active_tab",dom_element);
			$(".profile_settings").hide();
			$("#"+dom_element).show();
			$(".profile_settings_list").removeClass("uk-active");
			$("#"+dom_element+"_item").addClass("uk-active");
		}
	</script>
</html>