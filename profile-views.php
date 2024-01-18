<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<title>Profile Views | RopeYou Connects</title>
	</head>
   <body>
      <!-- Navigation -->
      <?php include_once 'header.php'; ?>
	  <div class="py-4">
         <div class="container">
            <div class="row">
               <!-- Main Content -->
               <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
                  <div class="box shadow-sm border rounded bg-white mb-3">
                     <div class="box-title border-bottom p-3">
                        <h6 class="m-0">Recent</h6>
                     </div>
                     <div class="box-body p-0">
						<?php
							$ids=array();
							$recent_profile_views_query="SELECT * FROM users_profile_views WHERE user_id='".$_COOKIE['uid']."' AND status!=0 ORDER BY id DESC LIMIT 5";
							$recent_profile_views_result=mysqli_query($conn,$recent_profile_views_query);
							$recent_num_rows=mysqli_num_rows($recent_profile_views_result);
							if($recent_num_rows>0)
							{
								while($views_row=mysqli_fetch_array($recent_profile_views_result))
								{
									$ids[]=$views_row['id'];
									$viewer_id=$views_row['viewer_id'];
									$users_personal_data=getUsersPersonalData($viewer_id);
									$users_data=getUsersData($viewer_id);
									
									$active_query="SELECT * FROM users_logs WHERE user_id='$viewer_id'";
									$active_res=mysqli_query($conn,$active_query);
									$active_row=mysqli_fetch_array($active_res);
									$active_status="bg-success";
									if($active_row['is_active']=="0")
									{
										$active_status="bg-danger";
									}
									if($users_data)
									{
									?>
										 <a href="<?php echo base_url; ?>u/<?php echo $users_data['username']; ?>"><div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
										   <div class="dropdown-list-image mr-3">
											 <img class="rounded-circle" src="<?php echo getUserProfileImage($viewer_id); ?>" alt="<?php echo ucwords(strtolower($users_data['first_name']." ".$users_data['last_name'])); ?>"> 
											  <div class="status-indicator <?php echo $active_status; ?>"></div>
										   </div>
										   <div class="font-weight-bold" style="width:100%;">
												<div class="text-truncate" style="width:100%;">
													<?php echo ucwords(strtolower($users_data['first_name']." ".$users_data['last_name'])); ?>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<a href="javascript:void(0);" class="small" style="float:right !important;font-weight:normal;font-size:8px;">
														<!--<?php //echo date("h:i a",strtotime($views_row['added'])); ?>
														<br/>-->
														<?php echo date("M d",strtotime($views_row['added'])); ?>
													</a>
												</div>
												<div class="small"><?php echo strip_tags(substr($users_data['profile_title'],0,100).".."); ?></div>
										   </div>
										</div></a>
									<?php
									}
								}
							}
							else
							{
								?>
								<p style="text-align:center;">no data available for profile views</p>
								<?php
							}
						?>
                     </div>
                  </div>
				  <?php
					if($recent_num_rows>0)
					{
						$recent_profile_views_query="SELECT * FROM users_profile_views WHERE user_id='".$_COOKIE['uid']."' AND status!=0 AND id NOT IN ('".implode("','",$ids)."') ORDER BY id DESC LIMIT 50";
						$recent_profile_views_result=mysqli_query($conn,$recent_profile_views_query);
						$earlier_num_rows=mysqli_num_rows($recent_profile_views_result);
				  ?>
						<div class="box shadow-sm border rounded bg-white mb-3">
						 <div class="box-title border-bottom p-3">
							<h6 class="m-0">Earlier</h6>
						 </div>
						 <div class="box-body p-0">
							<?php
								if($earlier_num_rows>0)
								{
									while($earlier_row=mysqli_fetch_array($recent_profile_views_result))
									{
										$viewer_id=$earlier_row['viewer_id'];
										$users_personal_data=getUsersPersonalData($viewer_id);
										$users_data=getUsersData($viewer_id);
										
										$active_query="SELECT * FROM users_logs WHERE user_id='$viewer_id'";
										$active_res=mysqli_query($conn,$active_query);
										$active_row=mysqli_fetch_array($active_query);
										$active_status="bg-success";
										if($active_row['is_active']=="0")
										{
											$active_status="bg-danger";
										}
										if($users_data)
										{
										?>
										<a href="<?php echo base_url; ?>u/<?php echo $users_data['username']; ?>"><div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
										   <div class="dropdown-list-image mr-3">
											 <img class="rounded-circle" src="<?php echo getUserProfileImage($viewer_id); ?>" alt="">
											  <div class="status-indicator <?php echo $active_status; ?>"></div>
										   </div>
										   <div class="font-weight-bold">
											  <div class="text-truncate"><?php echo ucwords(strtolower($users_data['first_name']." ".$users_data['last_name'])); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right;"><?php echo date("M d",strtotime($earlier_row['added'])); ?></span></div>
											  <div class="small"><?php echo substr($users_data['profile_title'],0,100).".."; ?></div>
										   </div>
										</div></a>
										<?php
										}
									}
								}
								else
								{
									?>
									<p style="text-align:center;">no more data available for profile views</p>
									<?php
								}
							?>
						 </div>
					  </div>
				   <?php
					}
					
				   ?>
			   </main>
               <aside class="hide_on_dashboard col col-xl-3 order-xl-1 col-lg-12 order-lg-1 col-12" id="left_side_bar" style="position:static;">
					<?php
						include_once 'user-profile-section.php';
					?>
               </aside>
               <aside class="hide_on_dashboard col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
					<?php include_once 'recent-jobs.php'; ?>
					<?php include_once 'people_you_may_know.php'; ?>
               </aside>
            </div>
         </div>
      </div>
	  <?php include_once 'scripts.php'; ?>
   </body>
</html>
