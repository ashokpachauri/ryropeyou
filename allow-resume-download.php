<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<title>Respond to resume download requests | RopeYou Connects</title>
	</head>
   <body>
	<style>
		.border_box_section{
			border:1px solid gray;
			border-radius:10px;
			margin-top:15px;
			margin-bttom:15px;
			padding:20px;
		}
	</style>
      <!-- Navigation -->
      <?php include_once 'header.php'; ?>
	  <div class="py-4">
         <div class="container" style="min-height:600px;">
            <div class="row">
               <!-- Main Content -->
               <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					<div class="box shadow-sm border rounded bg-white mb-3">
						<div class="box-title border-bottom p-3">
							<h6 class="m-0">Respond to resume download requests</h6>
						</div>
						<div class="box-body p-3">
							<div class="row">
								<div class="col-md-12 border_box_section">
									<?php
										$uthread="";
										if(isset($_REQUEST['uthread']) && $_REQUEST['uthread']!="")
										{
											$uthread=$_REQUEST['uthread'];
										}
										$nthread="";
										if(isset($_REQUEST['nthread']) && $_REQUEST['nthread']!="")
										{
											$nthread=$_REQUEST['nthread'];
											mysqli_query($conn,"UPDATE threats_to_user SET seen=1 WHERE md5(id)='$nthread' AND user_id='".$_COOKIE['uid']."'");
										}
										$query="SELECT * FROM resume_download_request WHERE user_id='".$_COOKIE['uid']."'";
										$result=mysqli_query($conn,$query);
										if(mysqli_num_rows($result)>0)
										{
											?>
											<table style="width:100%;" cellspacing="5" cellpadding="5" border="1">
											<?php
												while($row=mysqli_fetch_array($result))
												{
													$r_user_id=$row['r_user_id'];
													$r_user_data=getUsersData($r_user_id);
													$user_profile_picture=getUserProfileImage($r_user_id);
													?>
													<tr style="width:100%;">
														<td style="width:10%;text-align:center;" align="center">
															<a href="<?php echo base_url.'u/'.strtolower($r_user_data['username']); ?>">
																<img src="<?php echo $user_profile_picture; ?>" value="1" class="img-circle" style="width:50px;height:50px;border-radius:50%;">
															</a>
														</td>
														<td style="width:70%;">
															<a href="<?php echo base_url.'u/'.strtolower($r_user_data['username']); ?>"><?php echo ucwords(strtolower($r_user_data['first_name']." ".$r_user_data['last_name'])); ?></a><br/>
															<?php if($r_user_data['profile_title']!=""){ echo $r_user_data['profile_title']."<br/>"; } ?>
															<?php echo getUsersCurrentDesignation($r_user_id)."<br/>"; ?>
															<?php echo getUsersCurrentEducation($r_user_id); ?>
														</td>
														<td style="width:10%;text-align:center;" align="center">
															<?php
																if($row['status']=="1")
																{
																	?>
																	<a href="<?php echo base_url;?>allow-resume-download.php?rthread=<?php echo md5($row['id']); ?>&disallow=1" class="btn btn-danger">Disallow</a>
																	<?php
																}
																else if($row['status']=="0"){
																	?>
																	<a href="<?php echo base_url;?>allow-resume-download.php?rthread=<?php echo md5($row['id']); ?>&allow=1" class="btn btn-info">Allow</a>
																	<?php
																}
															?>
														</td>
														<td style="width:10%;text-align:center;" align="center">
															<?php
																if($row['status']=="0"){
																	?>
																	<a href="<?php echo base_url;?>allow-resume-download.php?rthread=<?php echo md5($row['id']); ?>&reject=1" class="btn btn-danger">Reject</a>
																	<?php
																}
															?>
														</td>
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
											<p style="text-align:center;">No requests found.</p>
											<?php
										}
									?>
								</div>
							</div>
						</div>
				  </div>
			   </main>
               <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                  <?php include_once 'user-profile-section.php'; ?>
               </aside>
               <!--<aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
					<?php //include_once 'recent-jobs.php'; ?>
					<?php //include_once 'people_you_may_know.php'; ?>
               </aside>-->
            </div>
         </div>
      </div>
      <?php include_once 'scripts.php'; ?>
   </body>
</html>