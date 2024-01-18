<style>
/*.container, .container-lg, .container-md, .container-sm, .container-xl {
    max-width: 1240px !important;
}*/
.auto-w .slick-arrow{
	z-index:99 !important;
}
.header-navbar-main-custom{
	position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
}
.py4 .container{
	margin-top:55px;
}
.unseen-notification{
	background-color:#efefef;
}
.seen-notification{
	
}
#cover_loader {
  background-color: #edf2f6;
  animation-name: example;
  animation-duration: 5s;
}

@keyframes example {
  from {background-color: #edf2f6;}
  to {background-color: #edf2f6;}
}
#cover_loader {position: fixed; height: 100%; width: 100%; top:0; left: 0; background: #edf2f6; z-index:9999;}
</style>
<div id="cover_loader">
	<img src="<?php echo base_url; ?>ryl3.png" style="margin-top:13%;margin-left:42%;height:50px;">
	<?php
		echo loading();
	?>
</div>
<nav class="navbar navbar-expand navbar-dark bg-dark osahan-nav-top p-0 header-navbar-main-custom">
	<input type="hidden" name="base_url" id="base_url" value="<?php echo $base_url; ?>">
	<div class="container">
		<!--d-none-->
		<a class="d-none navbar-brand mr-2  d-lg-inline" href="<?php if($_COOKIE['uid']!=""){ echo base_url.'dashboard'; } else{ echo base_url; } ?>"><img class="logo_desktop" src="<?php echo base_url; ?>ryl3.png"  alt="">
		</a>
		<a class="d-inline navbar-brand mr-2  d-lg-none" href="<?php if($_COOKIE['uid']!=""){ echo base_url.'dashboard'; } else{ echo base_url; } ?>"><img class="logo_mobile" src="<?php echo base_url; ?>ryl3alt.png"  alt="">
		</a>
		
		<?php
			if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
			{
				?>
				<form  action="<?php echo base_url.'browse'; ?>" method="post" class="d-none d-sm-inline-block form-inline mr-auto my-2 my-md-0 mw-100 navbar-search" style="margin-left:20px;">
				   <div class="input-group desktop_device">
					  <input type="text" class="form-control shadow-none border-0" name="search" id="search" placeholder="Search people, jobs & more..." aria-label="Search" aria-describedby="basic-addon2">
					  <div class="input-group-append desktop_device_append">
						 <button class="btn" type="submit">
						 <i class="feather-search"></i>
						 </button>
					  </div>
				   </div>
				</form>
				<ul class="navbar-nav ml-auto d-flex align-items-center">
				   <!-- Nav Item - Search Dropdown (Visible Only XS) -->
					<!--<li class="nav-item dropdown no-arrow d-sm-none">
					  <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					  <i class="feather-search mr-2"></i>
					  </a>
					  <div class="dropdown-menu dropdown-menu-right p-3 shadow-sm animated--grow-in" aria-labelledby="searchDropdown">
						 <form class="form-inline mr-auto w-100 navbar-search">
							<div class="input-group">
							   <input type="text" class="form-control border-0 shadow-none" placeholder="Search people, jobs and more..." aria-label="Search" aria-describedby="basic-addon2">
							   <div class="input-group-append">
								  <button class="btn" type="button">
								  <i class="feather-search"></i>
								  </button>
							   </div>
							</div>
						 </form>
					  </div>
					</li>-->
				   <li class="nav-item">
					  <a class="nav-link" href="<?php echo base_url; ?>dashboard" style="color:#fff !important;"><i class="feather-clipboard mr-2"></i><span class="d-none d-lg-inline">Dashboard</span></a>
				   </li>
				   <li class="nav-item">
					  <a class="nav-link" href="<?php echo base_url; ?>broadcasts" style="color:#fff !important;"><i class="feather-compass mr-2"></i><span class="d-none d-lg-inline">Broadcasts</span></a>
				   </li>
				   <li class="nav-item">
					  <a class="nav-link" href="<?php echo base_url; ?>bridge" style="color:#fff !important;"><i class="feather-users mr-2"></i><span class="d-none d-lg-inline">My Bridge</span></a>
				   </li>
				   <li class="nav-item">
					  <a class="nav-link" href="<?php echo base_url; ?>jobs" style="color:#fff !important;"><i class="feather-briefcase mr-2"></i><span class="d-none d-lg-inline">Jobs</span></a>
				   </li>
				   <li class="nav-item">
					  <a class="nav-link" href="<?php echo getBlogSpaceUrl(); ?>" style="color:#fff !important;"><i class="feather-tablet mr-2"></i><span class="d-none d-lg-inline">Blogger</span></a>
				   </li>
					<?php
						$user_id=$_COOKIE['uid'];
						$data_count=0;
						$chat_query="SELECT DISTINCT(user_id),added FROM users_chat WHERE r_user_id='$user_id' AND status=1 AND flag!=2 AND s_status!=0 ORDER BY added DESC";
						$chat_result=mysqli_query($conn,$chat_query);
						$chat_num_rows=mysqli_num_rows($chat_result);
						$data=array();
						if($chat_num_rows>0)
						{
							$friends=array();
							while($chat_row=mysqli_fetch_array($chat_result))
							{
								$friend=$chat_row['user_id'];
								if(!(in_array($friend,$friends)))
								{
									$friends[]=$friend;
									$data_count=$data_count+1;
								}
							}
						}
					?>
				   <li class="nav-item dropdown no-arrow mx-1 osahan-list-dropdown">
					  <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						 <i class="feather-message-square"></i>
						 <!-- Counter - Alerts -->
						 <span class="badge badge-danger badge-counter" id="messages_counter"><?php echo $data_count; ?></span>
						 <div id="sound"></div>
						 <div id="sound1"></div>
					  </a>
					  <!-- Dropdown - Alerts -->
						<div class="dropdown-list dropdown-menu dropdown-menu-right shadow-sm">
							<h6 class="dropdown-header">
								New Messages
								<input type="hidden" name="unread_messages_count" id="unread_messages_count" value="<?php echo $chat_num_rows; ?>">
							</h6>	
							<div id="new_messages_data">
								<a class="dropdown-item text-center small text-gray-500" href="javascript:void(0);">Loading new messages...</a>
							</div>
							<a class="dropdown-item text-center small text-gray-500" href="<?php echo base_url; ?>messenger">Message Center</a>
					  </div>
				   </li>
				   <?php
						$alert_query="SELECT id FROM threats_to_user WHERE user_id='".$_COOKIE['uid']."' AND seen=0 ORDER BY id DESC";
						$alert_result=mysqli_query($conn,$alert_query);
						$alert_num_rows=mysqli_num_rows($alert_result);
				   ?>
				   <li class="nav-item dropdown no-arrow mx-1 osahan-list-dropdown">
					  <a class="nav-link dropdown-toggle" href="#" onclick="updateCheckout();" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						 <i class="feather-bell"></i>
						 <!-- Counter - Alerts -->
						 <span class="badge badge-info badge-counter" id="notifications_counter"><?php echo $alert_num_rows; ?></span>
					  </a>
					  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow-sm">
							<h6 class="dropdown-header">
								Notifications
								<input type="hidden" name="unread_notifications_count" id="unread_notifications_count" value="<?php echo $alert_num_rows; ?>">
							</h6>
							<div id="new_notifications_data" style="max-height:350px;overflow-y:auto;">
								<?php
									$alert_query="SELECT id FROM threats_to_user WHERE user_id='".$_COOKIE['uid']."' ORDER BY id DESC LIMIT 10";
									$alert_result=mysqli_query($conn,$alert_query);
									$alert_num_rows=mysqli_num_rows($alert_result);
									if($alert_num_rows>0)
									{
										while($alert_row=mysqli_fetch_array($alert_result))
										{
											echo getNotification($alert_row['id']);
											mysqli_query($conn,"UPDATE threats_to_user SET flag=1 WHERE id='".$alert_row['id']."'");
										}
									}
								?>
							</div>
						 <a class="dropdown-item text-center small text-gray-500" href="<?php echo base_url; ?>notifications">Show All Alerts</a>
					  </div>
				   </li>
				   <!-- Nav Item - User Information -->
					<?php
						$profile=getUserProfileImage($_COOKIE['uid']);
						$user_query="SELECT * FROM users WHERE id='".$_COOKIE['uid']."'";
						$user_result=mysqli_query($conn,$user_query);
						$user_row=mysqli_fetch_array($user_result);
				   ?>
				   <li class="nav-item dropdown no-arrow ml-1 osahan-profile-dropdown">
					  <a class="nav-link dropdown-toggle pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					  <img class="img-profile rounded-circle" src="<?php echo str_replace(base_url,image_kit,$profile).'?tr=w-30,h-30'; ?>" style="border:1px solid #fff !important;">
					  </a>
					  <!-- Dropdown - User Information -->
					  <div class="dropdown-menu dropdown-menu-right shadow-sm">
						 <div class="p-3 d-flex align-items-center">
							<div class="dropdown-list-image mr-3">
							   <img class="rounded-circle" src="<?php echo $profile; ?>" alt="" style="border:1px solid #eaebec !important;">
							   <div class="status-indicator bg-success"></div>
							</div>
							<div class="font-weight-bold">
							   <div class="text-truncate"><?php echo $user_row['first_name']." ".$user_row['last_name']; ?></div>
							   <!--<div class="small text-gray-500"><?php echo $user_row['profile_title']; ?></div>-->
							</div>
						 </div>
						 <div class="dropdown-divider"></div>
						 <a class="dropdown-item" target="_blank" href="<?php echo base_url; ?>w/<?php echo $user_row['username']; ?>"><i class="feather-globe mr-1"></i> My Web Page</a>
						 <a class="dropdown-item" target="_blank" href="<?php echo base_url; ?>post-job"><i class="feather-edit mr-1"></i> Post a Job</a>
						 <a class="dropdown-item" href="<?php echo base_url; ?>jobs-posted"><i class="feather-user mr-1"></i>My Posted Jobs</a>
						 <a class="dropdown-item" href="<?php echo base_url; ?>pages"><i class="feather-aperture mr-1"></i> Pages</a>
						 <a class="dropdown-item" href="<?php echo base_url; ?>profile-settings.php?tab=privacy_settings"><i class="feather-aperture mr-1"></i> Privacy & Settings</a>
						 <div class="dropdown-divider"></div>
						 <a class="dropdown-item" href="<?php echo base_url; ?>logout"><i class="feather-log-out mr-1"></i> Logout</a>
					  </div>
				   </li>
				</ul>
				
				<?php
			}
			else{
				?>
				<ul class="navbar-nav ml-auto d-flex align-items-center">
				   <li class="nav-item dropdown no-arrow d-sm-none">
					  <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					  <i class="feather-search mr-2"></i>
					  </a>
					  <!-- Dropdown - Messages -->
					  <div class="dropdown-menu dropdown-menu-right p-3 shadow-sm animated--grow-in" aria-labelledby="searchDropdown">
						 <form  action="<?php echo base_url.'browse'; ?>" method="post" class="form-inline mr-auto w-100 navbar-search">
							<div class="input-group">
							   <input type="text" class="form-control border-0 shadow-none" name="search" id="search" placeholder="Search people, jobs and more..." aria-label="Search" aria-describedby="basic-addon2">
							   <div class="input-group-append">
								  <button class="btn" type="submit">
								  <i class="feather-search"></i>
								  </button>
							   </div>
							</div>
						 </form>
					  </div>
				   </li>
				   <li class="nav-item">
					  <a class="nav-link" href="<?php echo base_url; ?>logout"><i class="feather-log-in mr-2"></i><span class="d-none d-lg-inline">Login</span></a>
				   </li>
				<?php
			}
		?>
	</div>
</nav>      
<div class="modal fade image_backdrop_modal" id="image_backdrop_modal" style="z-index:99999;" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="image_backdrop" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="image_backdrop">&nbsp;</h6>
				<button type="button" class="close" onclick="$('#image_backdrop_modal').modal('hide');">&times;</button>
			</div>
			<div class="modal-body">											
				<div class="d-flex" style="width:100%;">
					<img class="form-control" id="backdrop_image_to_show" src="" style="width:100%;min-height:300px;max-height:310px;">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade image_backdrop_slider_modal" id="image_backdrop_slider_modal" style="z-index:99999;" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="image_backdrop_slider_modal_backdrop" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="image_backdrop_slider_modal_backdrop">&nbsp;</h6>
				<button type="button" class="close" onclick="$('#image_backdrop_slider_modal').modal('hide');">&times;</button>
			</div>
			<div class="modal-body">											
				<div class="row" style="width:100%;margin:0px;padding:0px;">
					<div class="col-md-12" id="backdrop_image_container_html" style="width:100%;margin:0px;padding:0px;">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
							