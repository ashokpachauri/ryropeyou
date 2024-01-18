<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<title>Notification | RopeYou Connects</title>
	</head>
   <body>
      <!-- Navigation -->
      <?php include_once 'header.php'; ?>
	  <div class="py-4">
         <div class="container">
            <div class="row">
               <!-- Main Content -->
               <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
                  
				  <?php
					$recent_profile_views_query="SELECT id FROM threats_to_user WHERE user_id='".$_COOKIE['uid']."' ORDER BY id DESC LIMIT 50";
					$recent_profile_views_result=mysqli_query($conn,$recent_profile_views_query);
					$earlier_num_rows=mysqli_num_rows($recent_profile_views_result);
					mysqli_query($conn,"UPDATE threats_to_user SET flag=1,checkout=1 WHERE user_id='".$_COOKIE['uid']."'");
				  ?>
					<div class="box shadow-sm border rounded bg-white mb-3">
					 <div class="box-title border-bottom p-3">
						<h6 class="m-0">Notifications</h6>
					 </div>
					 <div class="box-body p-0">
						<?php
							if($earlier_num_rows>0)
							{
								while($earlier_row=mysqli_fetch_array($recent_profile_views_result))
								{
									$notification_id=$earlier_row['id'];
									echo getNotificationPageNotification($notification_id);
								}
							}
							else
							{
								?>
								<p style="text-align:center;">no more data available for notifications</p>
								<?php
							}
						?>
					 </div>
				  </div>
			   </main>
               <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                  <?php include_once 'user-profile-section.php'; ?>
               </aside>
               <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
					<?php //include_once 'recent-jobs.php'; ?>
					<?php include_once 'people_you_may_know.php'; ?>
               </aside>
            </div>
         </div>
      </div>
      <?php include_once 'scripts.php'; ?>
   </body>
</html>