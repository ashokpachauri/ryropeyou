<html lang="en">
	<?php
		include_once 'connection.php';
		$user_row=null;
		$username=$_REQUEST['__username'];
		$uquery="SELECT * FROM users WHERE username='$username' AND status=1";
		$uresult=mysqli_query($conn,$uquery);
		if(mysqli_num_rows($uresult)>0)
		{
			$user_row=mysqli_fetch_array($uresult);
			$profile_user_id=$user_row['id'];
			if($profile_user_id!=$_COOKIE['uid'] && isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
			{
				
				mysqli_query($conn,"DELETE FROM users_profile_views WHERE user_id='$profile_user_id' AND viewer_id='".$_COOKIE['uid']."'");
				$pvquery="INSERT INTO users_profile_views SET user_id='$profile_user_id',viewer_id='".$_COOKIE['uid']."',status=1,added=NOW()";
				mysqli_query($conn,$pvquery);
				mysqli_query($conn,"UPDATE users_profile_views SET status=2 WHERE viewer_id='".$_COOKIE['uid']."' AND user_id='".$profile_user_id."'");
			}
			$user_id=$profile_user_id;
	?>
		<head>
			<?php 
				include_once 'head.php';
				$user_row=getUsersData($profile_user_id);
			?>
			<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php echo $user_row['first_name']." ".$user_row['last_name']; ?>'s profile | RopeYou Connects</title>
		</head>
		<body>
			<?php include_once 'header.php'; ?>
			<?php 
				$user_id=$profile_user_id;
				$users_personal_row=getUsersPersonalData($profile_user_id);
			?>
			<div class="py-4">
				<div class="container">
					<div class="row main_row">
						<aside class="col col-xl-3 order-xl-1 col-12 col-lg-12 order-lg-1 col-12" id="left_side_bar">
							<div class="row">
								<div class="col col-xl-12 order-xl-1 col-12 col-lg-12 order-lg-1 col-12 order-sm-1 order-1">
									<div class="box mb-3 shadow-sm border rounded bg-white profile-box text-center">
										<div class="py-3 px-3 border-bottom">
											<?php $profile=getUserProfileImage($profile_user_id); ?>
											<div class="image-container-custom w-100">
												<img id="user_profile_picture" src="<?php echo $profile; ?>" class="img-fluid mt-2 rounded-circle image" style="border:1px solid #eaebec !important;" alt="<?php echo $user_row['first_name']." ".$user_row['last_name']; ?>">
											</div>
											<h6 class="font-weight-bold text-dark mb-1 mt-4"><?php echo $user_row['first_name']." ".$user_row['last_name']; ?></h6>
											<?php
												$last_designation=getUsersCurrentDesignation($profile_user_id);
												$last_education=getUsersCurrentEducation($profile_user_id);
												$page_post_html="";
												if($last_designation)
												{
													$page_post_html.='<p style=""><a href="'.base_url.'u/'.$user_row['username'].'">'.$last_designation.'</a></p>';
												}
												if($last_education)
												{
													$page_post_html.='<p style=""><a href="'.base_url.'u/'.$user_row['username'].'">'.$last_education.'</a></p>';
												}
												echo $page_post_html;
											?>
											<div class="progress progress-striped"> 
												<div class="progress-bar progress-bar-success"> Your Profile is 0% Completed.</div>
											</div>
											<p style="text-align:center;margin-bottom:-5px;">Profile Completeness</p>
										</div>
										<?php
											
											if($profile_user_id!=$_COOKIE['uid'])
											{
												?>
												<div class="network-item-footer py-3 d-flex text-center">
												<?php
												$text="Connect";
												$follow="Follow";
												$connect_user_click_data="ConnectUser('".$profile_user_id."','connect');";
												$follow_user_click_data="ConnectUser('".$profile_user_id."','follow');";
												if(canConnectToUser($_COOKIE['uid'],$profile_user_id,"connect"))
												{
													$connect_query="SELECT * FROM user_joins_user WHERE (user_id='".$_COOKIE['uid']."' AND r_user_id='".$profile_user_id."') OR (r_user_id='".$_COOKIE['uid']."' AND user_id='".$profile_user_id."')";
													$connect_result=mysqli_query($conn,$connect_query);
													$num_rows=mysqli_num_rows($connect_result);
													
													if($num_rows>0)
													{
														$connect_row=mysqli_fetch_array($connect_result);
														if($connect_row['status']==1)
														{
															$text="Disconnect";
															$connect_user_click_data="ConnectUser('".$profile_user_id."','disconnect');";
														}
														else if($connect_row['status']==4)
														{
															if($connect_row['r_user_id']==$connect_user_id)
															{
																$text="Accept";
																$connect_user_click_data="ConnectUser('".$connect_user_id."','accept');";
															}
															else
															{
																$text="Cancel";
																$connect_user_click_data="ConnectUser('".$connect_user_id."','cancel');";
															}
														}
														else 
														{
															$text="Connect";
															$connect_user_click_data="ConnectUser('".$profile_user_id."','connect');";
														}
													}
													?>
													<div class="col-6 pl-3 pr-1 connect_user_<?php echo $profile_user_id; ?>">
														<button type="button" onclick="<?php echo $connect_user_click_data; ?>" class="btn btn-primary btn-sm btn-block"> <?php echo $text; ?> </button>
													</div>
													<?php
												}
												if(canConnectToUser($_COOKIE['uid'],$profile_user_id,"follow"))
												{
													$following=doIFollow($_COOKIE['uid'],$profile_user_id);
													if($following)
													{
														$follow_user_click_data="ConnectUser('".$profile_user_id."','unfollow');";
														$follow="Unfollow";
													}
													else
													{
														$follow_user_click_data="ConnectUser('".$profile_user_id."','follow');";
														$follow="Follow";
													}
													?>
													<div class="col-6 pr-3 pl-1 follow_user_<?php echo $profile_user_id; ?>">
														<button type="button" onclick="<?php echo $follow_user_click_data; ?>" class="btn btn-outline-primary btn-sm btn-block"> <i class="feather-user-plus"></i> <?php echo $follow; ?> </button>
													</div>
													<?php
												}
												?>
												</div>
												<?php
											}
										?>
										<div class="d-flex">
											<div class="col-6 border-right px-3 py-2">
											   <p class="mb-0 text-black-50 small"><a href="<?php echo base_url; ?>u/<?php echo $user_row['username']; ?>/connections"><span class="font-weight-bold text-dark"><?php echo getUserConnectionCounts($profile_user_id); ?></span>  Connections</a></p>
											</div>
											<div class="col-6 px-3 py-2">
											   <p class="mb-0 text-black-50 small"><span class="font-weight-bold text-dark"><?php echo getUserProfileViews($profile_user_id); ?></span>  Views</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col col-xl-12 order-xl-2 col-12 col-lg-12 order-lg-2 col-12 order-sm-4 order-4">
									<div class="box mb-3 shadow-sm border rounded bg-white profile-box text-center">
										<?php
											$v_query="SELECT * FROM users_resume WHERE user_id='".$profile_user_id."' AND profile_type=1 AND is_default=1 ORDER BY id DESC";
											$v_result=mysqli_query($conn,$v_query);
											$video_num_rows=mysqli_num_rows($v_result);
											if($video_num_rows>0)
											{
												$v_row=mysqli_fetch_array($v_result);
												$resume_file_name="";
												$resume_file_id='';
												if($v_row['file']!="" && $v_row['file']!=null)
												{
													$resume_file_id=$v_row['file'];
												}
												$r_query="SELECT * FROM documents WHERE id='$resume_file_id'";
												$r_result=mysqli_query($conn,$r_query);
												if(mysqli_num_rows($r_result)>0)
												{
													$r_row=mysqli_fetch_array($r_result);
													$resume_file_name=$r_row['file'];
													
													$resume_file_title=$r_row['profile_title'];
													
													$profile_title=$r_row['profile_title'];
												}
												else
												{
													$resume_file_name="";
													$profile_title="";
												}
											}
										?>
										<div class="p-2 text-left border-bottom">
											<h6 class="font-weight-bold mb-0 text-transform">
												<img src="<?php echo base_url; ?>img/videocv.png" style="cursor: pointer;width: 25px;margin-right: 5px;"> CV Maker 
											</h6>
										</div>
										<script>
											var base_url="<?php echo base_url; ?>";
											function requestResumeDownload(request_user_id)
											{
												$.ajax({
													url:base_url+"request-resume-download",
													type:"post",
													data:{request_user_id:request_user_id},
													success:function(response)
													{
														var parsedJson=JSON.parse(response);
														if(parsedJson.status=="success")
														{
															alert("A request has been sent. We will notify you once responded.");
														}
														else{
															alert(parsedJson.message);
														}
													}
												});
											}
										</script>
										
										<div class="p-1">
											<?php
												$v_query="SELECT * FROM users_resume WHERE user_id='".$profile_user_id."' AND profile_type=1 AND is_default=1 ORDER BY id DESC";
												$v_result=mysqli_query($conn,$v_query);
												$video_num_rows=mysqli_num_rows($v_result);
												if($video_num_rows>0)
												{
													$v_row=mysqli_fetch_array($v_result);
													$resume_file_name="";
													$resume_file_id='';
													if($v_row['file']!="" && $v_row['file']!=null)
													{
														$resume_file_id=$v_row['file'];
													}
													$r_query="SELECT * FROM documents WHERE id='$resume_file_id'";
													$r_result=mysqli_query($conn,$r_query);
													if(mysqli_num_rows($r_result)>0)
													{
														$r_row=mysqli_fetch_array($r_result);
														$resume_file_name=$r_row['file'];
														
														$resume_file_title=$r_row['profile_title'];
														//$profile_percentage=$profile_percentage+10;
														$profile_title=$r_row['profile_title'];
													}
													else
													{
														$resume_file_name="";
														$profile_title="";
													}
												}
												$who_can_download_text_resume=getPrivacySetting("who_can_download_text_resume",$profile_user_id); 
												if($profile_title!="" || $resume_file_name!="")
												{
													$resume_file_title=str_replace("uploads/","",$resume_file_name);
													if($resume_file_name!="")
													{
														$resume_file=$resume_file_name;
														$resume_file_name=base_url.$resume_file_name;
														if(($who_can_download_text_resume=="1" || $profile_user_id==$_COOKIE['uid']))
														{
															?>
															<a href="jvascript:void(0);" onclick="downloadFile('<?php echo $resume_file_name; ?>');" style="padding:5px;margin-bottom:20px;" title="Download File">Click here to download CV&nbsp;&nbsp;<i class="fa fa-download"></i></a><br/><br/>
															<?php
														}
														else{
															?>
															<a href="jvascript:void(0);" onclick="requestResumeDownload('<?php echo $profile_user_id; ?>');" style="padding:5px;margin-bottom:20px;" title="Request Resume Download">Request Resume Download&nbsp;&nbsp;<i class="fa fa-download"></i></a><br/><br/>
															<?php
														}
													}
													if($profile_title!="")
													{
														?>
														<p class="m-0 font-weight-normel border-top" id="video_profile_title" style="font-weight:normal !important;margin-top:20px;padding-top:10px;"><?php echo $profile_title; ?></p>
														<?php
													}
												}
												else
												{
													?>
													<p class="m-0 font-weight-normel" id="video_profile_title" style="font-weight:normal !important;margin-top:20px;text-align:center;">Nothing to show</p>
													<?php
												}
											?>
										</div>
										<script>
											function downloadFile(url)
											{
												var link = document.createElement('a');
												link.href = url;
												link.target = "_blank";
												document.body.appendChild(link);
												link.click(); 
												document.body.removeChild(link);
											}
										</script>
									</div>
							
								</div>
								<div class="col col-xl-12 order-xl-4 col-12 col-lg-12 order-lg-4 col-12 order-sm-2 order-2">
									
									<div class="box shadow-sm border rounded bg-white mb-3">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">Contact Details<a href="javascript:void(0);" onclick="getContacts();" title="Edit contact details" class="float-right btn small btn-sm btn-dark title-action-btn"><i class="feather-edit"></i></a></h6>
										</div>
										<div class="box-body">
											<div class="d-flex border-bottom align-button w-100">
												<div class="col-12 px-3 py-2">
													<a href="<?php echo base_url; ?>w/<?php echo strtolower($user_row['username']); ?>" target="_blank" style="color: #fff; background-color: #6fb4ff; padding: 5px 10px;" class="custom-button-dash btn-primary pull-left">Web View</a>&nbsp;&nbsp;&nbsp;&nbsp;
													<a href="<?php echo base_url; ?>u/<?php echo strtolower($user_row['username']); ?>" class="btn-warning custom-button-dash pull-right" style="color: rgb(29, 47, 56); padding: 5px 10px;" >Profile View</a>
												</div>
											</div>
											<div class="p-3 contact-details-box">
											   <div>
													<div>
														<?php
															if($users_personal_row!=false)
															{
																if($users_personal_row['communication_email']!="" && $users_personal_row['communication_email']!=null){
														?>
																<!--<i class="feather-mail"></i>-->
																	<p title="Email"> <i class="feather-mail"></i>
																		<a href="mailto:<?php echo $users_personal_row['communication_email']; ?>" id="communication_email_html" target="_blank"><?php echo $users_personal_row['communication_email']; ?></a>
																</p>
																<?php
																}
																if($users_personal_row['communication_mobile']!="" && $users_personal_row['communication_mobile']!=null){
														?>
																	<!--<i class="feather-phone"></i>-->
																	<p class="text-truncate" title="Mobile">
																		<i class="feather-smartphone"></i>
																		<a href="tel:<?php echo $users_personal_row['communication_mobile']; ?>" target="_blank"><?php echo "(".$users_personal_row['phonecode'].") - ".$users_personal_row['communication_mobile']; ?></a>
																	</p>
																<?php
																}
																if($users_personal_row['website']!="" && $users_personal_row['website']!=null){
														?>
																<!--<i class="feather-globe"></i>-->
																	<p class="text-truncate mb-0" title="Website">
																		<i class="feather-globe"></i>
																		<a href="<?php echo $users_personal_row['website']; ?>" target="_blank"><?php echo $users_personal_row['website']; ?></a>
																	</p>
																<?php
																}
																?>
														<?php
															}
															else
															{
																?>
																<div class="text-truncate" title="Contact Details">
																	<p>Nothing to show in contacts.</p>
																</div>
																<?php
															}
														?>
													</div>
											   </div>
											</div>
											
											<?php
												$social_counts=0;
												$social_counts_arr=array();
												if($users_personal_row['fb_p']!="" && $users_personal_row['fb_p']!=null){
													$social_counts=$social_counts+1;
													$social_counts_arr[]=array($users_personal_row['fb_p'],"feather-facebook");
												}if($users_personal_row['ig_p']!="" && $users_personal_row['ig_p']!=null){
													$social_counts=$social_counts+1;
													$social_counts_arr[]=array($users_personal_row['ig_p'],"feather-instagram");
												}if($users_personal_row['tw_p']!="" && $users_personal_row['tw_p']!=null){
													$social_counts=$social_counts+1;
													$social_counts_arr[]=array($users_personal_row['tw_p'],"feather-twitter");
												}if($users_personal_row['in_p']!="" && $users_personal_row['in_p']!=null){
													$social_counts=$social_counts+1;
													$social_counts_arr[]=array($users_personal_row['in_p'],"feather-linkedin");
												}
												if($social_counts>0)
												{
													$col_width=12/$social_counts;
											?>
													<div class="d-flex text-center">
														<div class="col-3 border-right border-top p-2">
															<p class="mb-0 text-black-50 small text-center">
																<a class="font-weight-bold d-block text-center social_icon_temp" href="<?php 
																if($users_personal_row['fb_p']!="" && $users_personal_row['fb_p']!=null){
																	echo $users_personal_row['fb_p'];
																}else{ echo 'javascript:void(0);'; }
															?>"  style="font-size:20px;"><i class="fa fa-facebook"></i></a></p><!--feather-facebook-->
														</div>
														<div class="col-3 border-right border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="<?php 
																if($users_personal_row['ig_p']!="" && $users_personal_row['ig_p']!=null){
																	echo $users_personal_row['ig_p'];
																}else{ echo 'javascript:void(0);'; }
															?>" style="font-size:20px;background-color:#cf2217 !important;"><i class="fa fa-instagram"></i></a></p><!--feather-instagram-->
														</div>
														<div class="col-3 border-right border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="<?php 
																if($users_personal_row['tw_p']!="" && $users_personal_row['tw_p']!=null){
																	echo $users_personal_row['tw_p'];
																}else{ echo 'javascript:void(0);'; }
															?>" style="font-size:20px;background-color:#00b7d6 !important;"><i class="fa fa-twitter"></i></a></p><!--feather-twitter-->
														</div>
														<div class="col-3 border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="<?php 
																if($users_personal_row['in_p']!="" && $users_personal_row['in_p']!=null){
																	echo $users_personal_row['in_p'];
																}else{ echo 'javascript:void(0);'; }
															?>" style="font-size:20px;background-color|:#0281ac !important"><i class="fa fa-linkedin"></i></a></p><!--feather-linkedin-->
														</div>
													</div>
											<?php
												}
												else
												{
													?>
													<div class="d-flex text-center">
														<div class="col-3 border-right border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="javascript:void(0);" style=""><i class="fa fa-facebook"></i></a></p>
														</div>
														<div class="col-3 border-right border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="javascript:void(0);"  style="background-color:#cf2217 !important;"><i class="fa fa-instagram"></i></a></p>
														</div>
														<div class="col-3 border-right border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="javascript:void(0);" style="background-color:#00b7d6 !important;"><i class="fa fa-twitter"></i></a></p>
														</div>
														<div class="col-3 border-top p-2">
														   <p class="mb-0 text-black-50 small text-center"><a class="font-weight-bold d-block text-center social_icon_temp" href="javascript:void(0);" style="background-color|:#0281ac !important;"><i class="fa fa-linkedin"></i></a></p>
														</div>
													</div>
													<?php
												}
											?>
										</div>
									</div>
							
								</div>
								<div class="show_on_dashboard col col-xl-12 order-xl-3 col-12 col-lg-12 order-lg-3 col-12 order-sm-3 order-3" style="display:none;">
									<div class="box mb-3 shadow-sm border rounded bg-white profile-box text-center">
										<div class="p-2 text-left border-bottom">
											<h6 class="font-weight-bold mb-0" style=" text-transform: uppercase; font-size: 14px !important; ">
												<img src="<?php echo base_url; ?>img/videocv.png" style="cursor: pointer;width: 25px;margin-right: 5px;"> Video cv or profile 
											</h6>
										</div>
										<?php
											$v_query="SELECT * FROM users_resume WHERE user_id='".$profile_user_id."' AND (profile_type=2 OR profile_type=3) AND is_default=1 ORDER BY id DESC";
											$v_result=mysqli_query($conn,$v_query);
											$video_num_rows=mysqli_num_rows($v_result);
											$video_file="";
											$token_video="";
											$video_file_id="";
											$clear_crystal_clear_url="";
											$visibility=1;
											$thumbnail=="";
											if($video_num_rows>0)
											{
												//$profile_percentage=$profile_percentage+10;
												
												$v_row=mysqli_fetch_array($v_result);
												$video_file_id=$v_row['file'];
												
												$video_query="SELECT * FROM videos WHERE id='$video_file_id'";
												$video_result=mysqli_query($conn,$video_query);
												if(mysqli_num_rows($video_result)>0)
												{
													$video_row=mysqli_fetch_array($video_result);
													$video_tags=$video_row['video_tags'];
													$thumbnail=$video_row['cover_image'];
													$visibility=$video_row['visibility'];
													$video_file=base_url.'ajax.videostream.php?action=stream&file_key='.base64_encode($video_row['file']);
													$clear_crystal_clear_url=base_url.'streaming.php?file_key='.md5($video_row['id']);
												}
												
												
												$profile_title=$v_row['profile_title'];
												$video_type=$v_row['video_type'];
												$token_video=$v_row['id'];
												$resume_headline=$v_row['resume_headline'];
												$profile_title=$v_row['profile_title'];
											}
											if($thumbnail=="")
											{
												$cover_image=default_cover_image;
											}
											else
											{
												$cover_image=$thumbnail;
											}
										?>
										<?php
											if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
											{
												?>
												<div>
													<video muted="" class="w-100" controls="" poster="<?php echo base_url.$cover_image; ?>" controlsList="nodownload" id="video_preview_data">
														<source src="<?php echo $video_file; ?>" type="video/mp4">
														Your browser does not support HTML5 video.
													</video>
												</div>
												<div class="p-3">
													<p class="m-0 font-weight-normel" id="video_profile_title" style="font-weight:normal !important;margin-top:20px;"><?php echo $profile_title; ?></p>
												</div>
												<?php
											}
											else
											{
												?>
												<div>
													<p style="text-align:center;">You needs to be login to view video cv/profile</p>
												</div>
												<?php
											}
										?>
									</div>
								</div>
								<div class="show_on_dashboard col col-xl-12 order-xl-3 col-12 col-lg-12 order-lg-3 col-12 order-sm-6 order-6" style="display:none;">
									<div class="box shadow-sm border rounded bg-white mb-3">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">About You</h6>
										</div>
										<div class="box-body p-3">
											<?php if($users_personal_row!=false) { echo '<p class="more amazing_about_you_text" id="amazing_about_you_text" style="text-align:justify;">'.trim(filter_var(html_entity_decode($users_personal_row['about']),FILTER_SANITIZE_STRING)).'</p>'; } else { echo '<p id="amazing_about_you_text" class="more" style="font-size:14px !important;text-align:justify;">Your about information will be apear here when you provide.</p>'; } ?>
										</div>
									</div>
									<div class="box shadow-sm border rounded bg-white mb-3 experiences-box">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">Experiences </h6>
										</div>
										<div class="box-body experience_data_matrix" id="experience_data_matrix_1">
										<?php
											$user_id=$profile_user_id;
											$experience_query="SELECT * FROM users_work_experience WHERE user_id='$user_id' AND status=1 ORDER BY from_year DESC";
											$experience_result=mysqli_query($conn,$experience_query);
											if(mysqli_num_rows($experience_result)>0)
											{
												while($experience_row=mysqli_fetch_array($experience_result))
												{
													$experience_id=$experience_row['id'];
													$country_query="SELECT title FROM country WHERE id='".$experience_row['country']."'";
													$country_result=mysqli_query($conn,$country_query);
													$country_row=mysqli_fetch_array($country_result);
													
													$city_query="SELECT title FROM city WHERE id='".$experience_row['city']."'";
													$city_result=mysqli_query($conn,$city_query);
													$city_row=mysqli_fetch_array($city_result);
												?>
													<div class="box-body p-3 border-bottom work_exp_<?php echo $experience_id; ?>" id="work_exp_<?php echo $experience_id; ?>">
														<div class="d-flex align-items-top job-item-header pb-2">
														   <div class="mr-2">
															  <h6 class="font-weight-bold text-dark mb-0" style="font-weight:normal !important;margin-bottom:5px !important;"><?php echo ucfirst(strtolower($experience_row['title'])); ?>&nbsp;&nbsp;
															  </h6>
															  <div class="text-truncate text-primary" style="margin-bottom:4px;"><?php echo ucfirst(strtolower($experience_row['company'])); ?></div>
															  <div class="small text-gray-500"><?php echo print_month($experience_row['from_month'])." ".$experience_row['from_year']; ?>  to <?php if($experience_row['working']=="1"){ echo "Present"; } else { echo print_month($experience_row['to_month'])." ".$experience_row['to_year']; } ?></div>
														   </div>
														   <img class="img-fluid ml-auto mb-auto" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($experience_row['company']),0,1).".png"; ?>" alt="">
														</div>
														<p class="mb-0 more">
															<?php
																if($experience_row['description']==""){
																	echo "<b>".ucfirst(strtolower($experience_row['title']))."</b> at <b>".ucfirst(strtolower($experience_row['company']))."</b> in <b>".ucfirst(strtolower($city_row['title'])).", ".ucfirst(strtolower($country_row['title']))."</b> from <b>".print_month($experience_row['from_month'])." ".$experience_row['from_year']."</b> to <b>";
																	if($experience_row['working']=="1"){ echo "Present</b>."; } else { echo print_month($experience_row['to_month'])." ".$experience_row['to_year']."</b>."; }
																}
																else
																{
																	echo ucfirst(strtolower($experience_row['description']));
																}
															?>
														</p>
													</div>
												<?php
												}
											}
											else
											{
												?>
												<div class="box-body p-3 border-bottom nothing_to_show_exp" id="nothing_to_show_exp">
													<div class="d-flex align-items-top job-item-header pb-2">
														<div class="col-12 p-1">
															<div class="font-weight-bold p-2">
																<h6 style="text-align:center;">There is no data to show.</h6>
															</div>
														</div>
													</div>
												</div>
												<?php
											}
										?>
										</div>
									</div>
									<div class="box shadow-sm border rounded bg-white mb-3 education-box">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">Education</h6></div>
										<div class="box-body educational_data_matrix" id="educational_data_matrix_1">
											<?php
												$education_query="SELECT * FROM users_education WHERE user_id='$user_id' AND status=1 ORDER BY from_year DESC";
												$education_result=mysqli_query($conn,$education_query);
												//echo $education_query;
												if(mysqli_num_rows($education_result)>0)
												{
													while($education_row=mysqli_fetch_array($education_result))
													{
														$education_id=$education_row['id'];
														$country_query="SELECT title FROM country WHERE id='".$education_row['country']."'";
														$country_result=mysqli_query($conn,$country_query);
														$country_row=mysqli_fetch_array($country_result);
														
														$city_query="SELECT title FROM city WHERE id='".$education_row['city']."'";
														$city_result=mysqli_query($conn,$city_query);
														$city_row=mysqli_fetch_array($city_result);
											?>
													<div class="box-body p-3 border-bottom edu_<?php echo $education_id; ?>" id="edu_<?php echo $education_id; ?>">
														<div class="d-flex align-items-top job-item-header pb-2">
														   <div class="mr-2">
															  <h6 class="font-weight-bold text-dark mb-0" style="font-weight:normal !important;margin-bottom:5px !important;"><?php echo ucfirst(strtolower($education_row['university'])); ?>&nbsp;&nbsp;
															  </h6>
															  <div class="text-truncate text-primary" style="margin-bottom:4px;"><?php echo ucfirst(strtolower($education_row['title'])); ?></div>
															  <div class="small text-gray-500"><?php echo print_month($education_row['from_month'])." ".$education_row['from_year']; ?>  to <?php if($education_row['studying']=="1"){ echo "Present"; } else { echo print_month($education_row['to_month'])." ".$education_row['to_year']; } ?>  </div>
														   </div>
														   <img class="img-fluid ml-auto mb-auto" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($education_row['university']),0,1).".png"; ?>" alt="">
														</div>
														<p class="mb-0 more">
															<?php 
																if($education_row['description']==""){
																	echo "<b>".ucfirst(strtolower($education_row['title']))."</b> in <b>".ucfirst(strtolower($education_row['major']))."</b> at <b>".ucfirst(strtolower($education_row['university']))."</b> in <b>".ucfirst(strtolower($city_row['title'])).", ".ucfirst(strtolower($country_row['title']))."</b> from <b>".print_month($education_row['from_month'])." ".$education_row['from_year']."</b> to <b>";
																	if($education_row['studying']=="1"){ echo "Present</b>."; } else { echo print_month($education_row['to_month'])." ".$education_row['to_year']."</b>."; }
																}
																else
																{
																	echo ucfirst(strtolower($education_row['description']));
																}
															?>
														</p>
													</div>
											<?php
													}
												}
												else{
													?>
														<div class="box-body p-3 border-bottom nothing_to_show_edu" id="nothing_to_show_edu">
															<div class="d-flex align-items-top job-item-header pb-2">
																<div class="col-12 p-1">
																	<div class="font-weight-bold p-2">
																		<h6 style="text-align:center;">Nothing to show.</h6>
																	</div>
																</div>
															</div>
														</div>
													<?php
												}
											?>
										</div>	
									</div>
									
								</div>
								<div class="col col-xl-12 order-xl-3 col-12 col-lg-12 order-lg-3 col-12 order-sm-5 order-5">
									<div class="box shadow-sm border rounded bg-white mb-3 gallery-box-main">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0">Gallery<a href="<?php echo base_url.'gallery'; ?>" class="small float-right">View All <i class="feather-chevron-right"></i></a></h6>
										</div>
										<div class="box-body">
											<div class="d-flex border-bottom align-button" style="width:100%;">
												<div class="col-12 px-3 py-2">
													<a href="javascript:void(0);" onclick="showPersonal();" style="color: #fff; background-color: #6fb4ff; padding: 5px 10px;" class="btn-primary custom-button-dash pull-left">Personal</a>&nbsp;&nbsp;&nbsp;&nbsp;
													<a href="javascript:void(0);"  onclick="showProfessional();" style="color: rgb(29, 47, 56); padding: 5px 10px;" class="btn-warning custom-button-dash pull-right" >Professional</a>
												</div>
											</div>
											<div class="w-100" id="personal_gallery">
												<div id="personal_data_matrix" class="w-100" style="min-height:100px;">
													<?php
														$gquery="SELECT * FROM gallery WHERE is_professional=0 AND is_private=0 AND is_draft=0 AND user_id='".$profile_user_id."' AND type LIKE 'image/%' AND delete_status=0 ORDER BY id DESC LIMIT 6";
														//echo $gquery;
														$gresult=mysqli_query($conn,$gquery);
														if(mysqli_num_rows($gresult)>0)
														{
															while($grow=mysqli_fetch_array($gresult))
															{
																if(is_file($grow['file']))
																{
																	?>
																	<img src="<?php echo base_url.$grow['file']; ?>">
																	<?php
																}
															}
														}
														else
														{
															?>
															<div class="col-12 p-1" id="nothing_to_show_gallery_1">
																<div class="font-weight-bold p-2">
																	<p>Nothing to show.</p>
																</div>
															</div>
															<?php
														}
													?>										
												</div>
											</div>
											<div class="w-100" style="display:none;" id="professional_gallery">
												<div id="professional_data_matrix" class="w-100" style="min-height:100px;">
													<?php
														$gquery="SELECT * FROM gallery WHERE is_professional=1 AND is_private=0 AND is_draft=0 AND user_id='".$profile_user_id."' AND type LIKE 'image/%' AND delete_status=0 ORDER BY id DESC LIMIT 6";
														$gresult=mysqli_query($conn,$gquery);
														if(mysqli_num_rows($gresult)>0)
														{
															while($grow=mysqli_fetch_array($gresult))
															{
																if(is_file($grow['file']))
																{
																	?>
																	<img src="<?php echo base_url.$grow['file']; ?>">
																	<?php
																}
															}
														}
														else
														{
															?>
															<div class="col-12 p-1" id="nothing_to_show_gallery">
																<div class="font-weight-bold p-2">
																	<p>Nothing to show.</p>
																</div>
															</div>
															<?php
														}
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col col-xl-12 order-xl-6 col-12 col-lg-12 order-lg-6 col-12 order-sm-6 order-6">
									
									<div class="box shadow-sm border rounded bg-white mb-3" id="left_sidebar_interests" style="margin-bottom:150px;">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">Interests<h6>	
										</div>
										<div class="box-body p-3">	
											<?php
												$query="SELECT * FROM users_interests WHERE user_id='$user_id'";
												$result=mysqli_query($conn,$query);
												if(mysqli_num_rows($result)>0)
												{
													
													while($row=mysqli_fetch_array($result))
													{
													?>
													<div class="interests-tag">
													<?php echo $row['title']; ?>
													</div>
													<?php
													}
												}
												else
												{
													?>
													<div class="d-flex" style="width:100%;">
													   <div class="col-12 p-1">
														   <div class="font-weight-bold">
															  <div class="text-truncate" style="font-size:12px !important;">nothing to show.</div>
														   </div>
													   </div>
													</div>
													<?php
												}
											?>
										</div>
									</div>
								</div>
								<div class="col col-xl-12 order-xl-5 col-12 col-lg-12 order-lg-5 col-12 order-sm-6 order-6">
									<div class="box shadow-sm border rounded bg-white mb-3 skills-boxs">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">Skills
										</h6></div>
										<div class="box-body">
											<?php
												$skills_query="SELECT * FROM users_skills WHERE user_id='".$user_id."' AND status=1 ORDER BY proficiency DESC";
												$skills_result=mysqli_query($conn,$skills_query);
												if(mysqli_num_rows($skills_result)>0)
												{
													while($skills_row=mysqli_fetch_array($skills_result))
													{
														$skillMeterHtml="";
														$skillMeterTitle="";
														if(((int)($skills_row['proficiency']))<=33)
														{
															$skillMeterHtml='<span class="badge badge-danger ml-1" style="border: 2px solid red;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span><span class="badge badge-dark ml-1" style="color: #f54295 !important;background-color: #fff !important;border: 2px solid #343a40 !important;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span><span class="badge badge-dark ml-1" style="color: #343a40 !important;background-color: #fff !important;border: 2px solid #343a40 !important;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span>';
															$skillMeterTitle="Basic";
														}
														else if(((int)($skills_row['proficiency']))<=66)
														{
															$skillMeterHtml='<span class="badge badge-warning ml-1" style="border: 2px solid #dbb716;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span><span class="badge badge-warning ml-1" style="border: 2px solid #dbb716;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span><span class="badge badge-dark ml-1" style="color: #343a40 !important;background-color: #fff !important;border: 2px solid #343a40 !important;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span>';
															$skillMeterTitle="Proficient";
														}
														else if(((int)($skills_row['proficiency']))<=100)
														{
															$skillMeterHtml='<span class="badge badge-success ml-1" style="border: 2px solid #00c9a7;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span><span class="badge badge-success ml-1" style="border: 2px solid #00c9a7;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span><span class="badge badge-success ml-1" style="border: 2px solid #00c9a7;border-radius:50% !important;">&nbsp;&nbsp;&nbsp;</span>';
															$skillMeterTitle="Expert";
														}
														//$htmlData.=$skillMeterHtml;
											?>			
														<div class="d-flex" style="width:100%;">
														   <div class="col-12 border-right border-top p-1">
															   <div class="font-weight-bold">
																  <div class="text-truncate" style="font-size:10px !important;"><span style="min-width:70% !important;"><?php echo ucfirst(strtolower($skills_row['title'])); ?></span><span style="max-width:30% !important;float:right !important;" title="<?php echo $skillMeterTitle; ?>"><?php echo $skillMeterHtml; ?></span></div>
															   </div>
														   </div>
														</div>
											<?php
													}
												}
											?>
										</div>
										
									</div>
							
								</div>
								
								<div class="col col-xl-12 order-xl-6 col-12 col-lg-12 order-lg-6 col-12 order-sm-6 order-6">
									<div class="box shadow-sm border rounded bg-white mb-3 is_stuck" id="left_sidebar_languages" style="margin-bottom:150px;">
										<div class="box-title border-bottom p-3">
											<h6 class="m-0 font-weight-bold">Languages
										</h6>
										</div>
										<div class="box-body p-3">	
											<?php
												$query="SELECT * FROM users_languages WHERE user_id='$user_id'";
												$result=mysqli_query($conn,$query);
												if(mysqli_num_rows($result)>0)
												{
													
													while($row=mysqli_fetch_array($result))
													{
													?>
													<div class="interests-tag">
													<?php echo $row['title']; ?>
													</div>
													<?php
													}
												}
												else
												{
													?>
													<div class="d-flex" style="width:100%;">
													   <div class="col-12 p-1">
														   <div class="font-weight-bold">
															  <div class="text-truncate">nothing to show.</div>
														   </div>
													   </div>
													</div>
													<?php
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</aside>
							
						<main class="col col-xl-6 order-xl-2 col-12 col-lg-12 order-lg-2 col-12 col-lg-12 col-sm-12 col-12">
							<div class="hide_on_dashboard box shadow-sm border rounded bg-white mb-3">
								<div class="box-title border-bottom p-3">
									<h6 class="m-0 font-weight-bold">About You
									</h6>
								</div>
								<div class="box-body p-3">
									<?php if($users_personal_row!=false) { echo '<p class="more amazing_about_you_text" id="amazing_about_you_text" style="text-align:justify;">'.trim(strip_tags(filter_var(html_entity_decode($users_personal_row['about']),FILTER_SANITIZE_STRING))).'</p>'; } else { echo '<p id="amazing_about_you_text" class="more" style="font-size:14px !important;text-align:justify;">Your about information will be apear here when you provide.</p>'; } ?>
								</div>
							</div>
							<div class="hide_on_dashboard box shadow-sm border rounded bg-white mb-3 experiences-box">
								<div class="box-title border-bottom p-3">
									<h6 class="m-0 font-weight-bold">Experiences</h6> 
								</div>
								<div class="box-body experience_data_matrix" id="experience_data_matrix">
								<?php
									$experience_query="SELECT * FROM users_work_experience WHERE user_id='$user_id' AND status=1 ORDER BY from_year DESC";
									$experience_result=mysqli_query($conn,$experience_query);
									if(mysqli_num_rows($experience_result)>0)
									{
										while($experience_row=mysqli_fetch_array($experience_result))
										{
											$experience_id=$experience_row['id'];
											$country_query="SELECT title FROM country WHERE id='".$experience_row['country']."'";
											$country_result=mysqli_query($conn,$country_query);
											$country_row=mysqli_fetch_array($country_result);
											
											$city_query="SELECT title FROM city WHERE id='".$experience_row['city']."'";
											$city_result=mysqli_query($conn,$city_query);
											$city_row=mysqli_fetch_array($city_result);
										?>
											<div class="box-body p-3 border-bottom work_exp_<?php echo $experience_id; ?>" id="work_exp_<?php echo $experience_id; ?>">
												<div class="d-flex align-items-top job-item-header pb-2">
												   <div class="mr-2">
													  <h6 class="font-weight-bold text-dark mb-0" style="font-weight:normal !important;margin-bottom:5px !important;"><?php echo ucfirst(strtolower($experience_row['title'])); ?></h6>
													  <div class="text-truncate text-primary" style="margin-bottom:4px;"><?php echo ucfirst(strtolower($experience_row['company'])); ?></div>
													  <div class="small text-gray-500"><?php echo print_month($experience_row['from_month'])." ".$experience_row['from_year']; ?>  to <?php if($experience_row['working']=="1"){ echo "Present"; } else { echo print_month($experience_row['to_month'])." ".$experience_row['to_year']; } ?></div>
												   </div>
												   <img class="img-fluid ml-auto mb-auto" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($experience_row['company']),0,1).".png"; ?>" alt="">
												</div>
												<p class="mb-0 more">
													<?php
														if($experience_row['description']==""){
															echo "<b>".ucfirst(strtolower($experience_row['title']))."</b> at <b>".ucfirst(strtolower($experience_row['company']))."</b> in <b>".ucfirst(strtolower($city_row['title'])).", ".ucfirst(strtolower($country_row['title']))."</b> from <b>".print_month($experience_row['from_month'])." ".$experience_row['from_year']."</b> to <b>";
															if($experience_row['working']=="1"){ echo "Present</b>."; } else { echo print_month($experience_row['to_month'])." ".$experience_row['to_year']."</b>."; }
														}
														else
														{
															echo ucfirst(strtolower($experience_row['description']));
														}
													?>
												</p>
											</div>
										<?php
										}
									}
									else
									{
										?>
										<div class="box-body p-3 border-bottom nothing_to_show_exp" id="nothing_to_show_exp">
											<div class="d-flex align-items-top job-item-header pb-2">
												<div class="col-12 p-1">
													<div class="font-weight-bold p-2">
														<h6 style="text-align:center;">There is no data to show.</h6>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
								?>
								</div>
							</div>
							<div class="hide_on_dashboard box shadow-sm border rounded bg-white mb-3 education-box">
								<div class="box-title border-bottom p-3">
									<h6 class="m-0 font-weight-bold">Education</h6>
								</div>
								<div class="box-body educational_data_matrix" id="educational_data_matrix">
									<?php
										$education_query="SELECT * FROM users_education WHERE user_id='$user_id' AND status=1 ORDER BY from_year DESC";
										$education_result=mysqli_query($conn,$education_query);
										//echo $education_query;
										if(mysqli_num_rows($education_result)>0)
										{
											while($education_row=mysqli_fetch_array($education_result))
											{
												$education_id=$education_row['id'];
												$country_query="SELECT title FROM country WHERE id='".$education_row['country']."'";
												$country_result=mysqli_query($conn,$country_query);
												$country_row=mysqli_fetch_array($country_result);
												
												$city_query="SELECT title FROM city WHERE id='".$education_row['city']."'";
												$city_result=mysqli_query($conn,$city_query);
												$city_row=mysqli_fetch_array($city_result);
									?>
											<div class="box-body p-3 border-bottom edu_<?php echo $education_id; ?>" id="edu_<?php echo $education_id; ?>">
												<div class="d-flex align-items-top job-item-header pb-2">
												   <div class="mr-2">
													  <h6 class="font-weight-bold text-dark mb-0" style="font-weight:normal !important;margin-bottom:5px !important;"><?php echo ucfirst(strtolower($education_row['university'])); ?></h6>
													  <div class="text-truncate text-primary" style="margin-bottom:4px;"><?php echo ucfirst(strtolower($education_row['title'])); ?></div>
													  <div class="small text-gray-500"><?php echo print_month($education_row['from_month'])." ".$education_row['from_year']; ?>  to <?php if($education_row['studying']=="1"){ echo "Present"; } else { echo print_month($education_row['to_month'])." ".$education_row['to_year']; } ?>  </div>
												   </div>
												   <img class="img-fluid ml-auto mb-auto" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($education_row['university']),0,1).".png"; ?>" alt="">
												</div>
												<p class="mb-0 more">
													<?php 
														if($education_row['description']==""){
															echo "<b>".ucfirst(strtolower($education_row['title']))."</b> in <b>".ucfirst(strtolower($education_row['major']))."</b> at <b>".ucfirst(strtolower($education_row['university']))."</b> in <b>".ucfirst(strtolower($city_row['title'])).", ".ucfirst(strtolower($country_row['title']))."</b> from <b>".print_month($education_row['from_month'])." ".$education_row['from_year']."</b> to <b>";
															if($education_row['studying']=="1"){ echo "Present</b>."; } else { echo print_month($education_row['to_month'])." ".$education_row['to_year']."</b>."; }
														}
														else
														{
															echo ucfirst(strtolower($education_row['description']));
														}
													?>
												</p>
											</div>
									<?php
											}
										}
										else{
											?>
												<div class="box-body p-3 border-bottom nothing_to_show_edu" id="nothing_to_show_edu">
													<div class="d-flex align-items-top job-item-header pb-2">
														<div class="col-12 p-1">
															<div class="font-weight-bold p-2">
																<h6 style="text-align:center;">Nothing to show.</h6>
															</div>
														</div>
													</div>
												</div>
											<?php
										}
									?>
								</div>	
							</div>
							
							<?php
								$awards_query="SELECT * FROM users_awards WHERE status=1 AND user_id='".$profile_user_id."'";
								$awards_result=mysqli_query($conn,$awards_query);
							?>
							<div class="box shadow-sm border rounded bg-white mb-3 achievements-box">
								<div class="box-title border-bottom p-3">
									<h6 class="m-0 font-weight-bold">Achievements</h6>
								</div>
								<div class="box-body" id="award_data_matrix">	
									<?php
										if(mysqli_num_rows($awards_result)>0)
										{
											
											while($awards_row=mysqli_fetch_array($awards_result))
											{
												$award_id=$awards_row['id'];
									?>
											<div class="d-flex border-bottom p-3" style="width:100%;" id="award_<?php echo $award_id; ?>">
												<?php
													if($awards_row['image']!="" && $awards_row['image']!=null)
													{
														$image=base_url.$awards_row['image'];
												?>
														<div class="col-3 pl-0 pr-3">
															<img class="img-fluid rounded border w-100" src="<?php echo $image; ?>" alt="<?php echo ucfirst(strtolower($awards_row['title'])); ?>">
														</div>
												<?php
													}
												?>
												<div class="col-9 px-0 col-<?php if($awards_row['image']!="" && $awards_row['image']!=null){ echo "9"; }else { echo "12"; } ?>">
													<h6 class="mb-1 f-15"><?php echo ucfirst(strtolower($awards_row['title'])); ?></h6>
													<p class="m-0"><?php echo ucfirst(strtolower($awards_row['description'])); ?></p>
												</div>
											</div>
									<?php
											}
										}
										else
										{
											?>
											<div class="d-flex border-bottom" style="width:100%;" id="nothing_to_show_award">
												<div class="col-12 p-3">
													<h6 class="m-0" style="text-align:center;">Nothing to show.</h6>
												</div>
											</div>
											<?php
										}
									?>
								</div>
							</div>
							<div class="box shadow-sm border rounded bg-white mb-3">
								<div class="box-title border-bottom p-3">
									<h6 class="m-0 font-weight-bold">Influencers Following</h6>
								</div>
								<div class="box-body">
									<?php
										$follower_query="SELECT * FROM users_followers WHERE status=1 AND follower_id='".$profile_user_id."' AND type=1";
										$follower_result=mysqli_query($conn,$follower_query);
										//echo $follower_query;
										if(mysqli_num_rows($follower_result)>0)
										{
											?>
											<div class="mb-3 shadow-sm rounded box bg-white osahan-slider-main">
												<div class="osahan-slider">
												<?php
													while($follower_row=mysqli_fetch_array($follower_result))
													{
														$influencer_id=$follower_row['user_id'];
														$follower_data=getUsersData($influencer_id);
														$follower_data_personal=getUsersPersonalData($influencer_id);
														?>
														<div class="osahan-slider-item">
															<a href="<?php echo base_url."u/".$follower_data['username']; ?>">
																<div class="shadow-sm border rounded bg-white job-item job-item mr-2 mt-3 mb-3">
																	<div class="d-flex align-items-center p-3 job-item-header">
																		<img class="img-fluid img-responsive" src="<?php echo getUserProfileImage($influencer_id); ?>" alt="" style="border:1px solid #eaebec !important;padding: 2px;border-radius: 7px;width:50px;height:50px;border-radius:50%;">
																		<div class="overflow-hidden p-1">
																			<h6 class="font-weight-bold text-dark mb-0 text-truncate" style="font-size:12px;font-weight:normal !important;"><?php echo ucfirst(strtolower($follower_data['first_name']))." ".ucfirst(strtolower($follower_data['last_name'])); ?></h6>
																			<div class="text-truncate text-primary"><?php echo ucfirst(strtolower($follower_data['profile_title'])); ?></div>
																			<?php
																				if($follower_data_personal!=false)
																				{
																		   ?>
																					<div class="small text-gray-500"><i class="feather-map-pin"></i> <?php echo getCityByID(ucfirst(strtolower($follower_data_personal['home_town']))).", ".ucfirst(strtolower(getCountryByID($follower_data_personal['country']))); ?></div>
																			<?php
																				}
																			?>
																		</div>
																	</div>
																	<div class="d-flex align-items-center p-3 border-top border-bottom job-item-body">
																		<div class="overlap-rounded-circle d-flex">
																			<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="" src="img/p1.png" alt="" data-original-title="Sophia Lee">
																			<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="" src="img/p2.png" alt="" data-original-title="John Doe">
																			<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="" src="img/p3.png" alt="" data-original-title="Julia Cox">
																			<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="" src="img/p4.png" alt="" data-original-title="Robert Cook">
																			<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="" src="img/p5.png" alt="" data-original-title="Sophia Lee">
																		</div>
																		<span class="font-weight-bold text-primary">18 connections</span>
																	</div>
																</div>
															</a>
														</div>
														<?php
													}	
											?>
												</div>
											</div>
											<?php
										}
										else
										{
											?>
											<div class="d-flex" style="width:100%;">
												<div class="col-12 py-2 px-3">
													<h6 class="m-0" style="text-align:center;">Nothing to show.</h6>
												</div>
											</div>
											<?php
										}
									?>
								</div>
							</div>
							<div class="box shadow-sm border rounded bg-white mb-3 recommendations-box">
								<div class="box-title border-bottom p-3">
									<h6 class="m-0 font-weight-bold">Recommendations</h6>
								</div>
								<div class="box-body">
									<?php
										$r_query_1="SELECT * FROM recommendations WHERE r_user_id='$profile_user_id' AND status=1 ORDER BY added ASC";
										$r_result_1=mysqli_query($conn,$r_query_1);
										
										$r_query="SELECT * FROM recommendations WHERE user_id='$profile_user_id' AND status=1 ORDER BY status DESC";
										$r_result=mysqli_query($conn,$r_query);
									?>
									<div class="d-flex border-bottom" style="width:100%;">
										<div class="col-12 px-3 py-2">
											<a href="javascript:void(0);" id="rr_anchor" onclick="showReceivedRec();">Received (<?php echo mysqli_num_rows($r_result); ?>)</a>
											&nbsp;&nbsp;
											<a href="javascript:void(0);" id="gr_anchor" onclick="showGivenRec();" style="color:black;">Given (<?php echo mysqli_num_rows($r_result_1); ?>)</a>
										</div>
									</div>
									<div style="width:100%;" id="received_recommendations">
										<div id="recommendation_data_matrix" style="width:100%;">
										<?php
											if(mysqli_num_rows($r_result))
											{
												$i=1;
												while($r_row=mysqli_fetch_array($r_result))
												{
													$user_data_r=getUsersData($r_row['r_user_id']);
													$data='<div class="d-flex border-bottom p-3" style="width:100%;" id="rec_'.$r_row['id'].'">
														<div class="col-2 pl-0 pr-3">
															<img class="img-fluid" style="width:100% !important;border-radius: 50%;" src="'.getUserProfileImage($r_row['r_user_id']).'">
														</div>
														<div class="col-10 px-0">
															<h6 class="mb-1">'.ucfirst(strtolower($user_data_r['first_name'].' '.$user_data_r['last_name'])).' - ';
															if($r_row['status']=="0")
															{
																$data=$data.'<span class="badge badge-danger">Pending</span>';
																$data=$data.'</h6>
																		<p class="m-0">'.ucfirst(strtolower($r_row['custom_message'])).'</p>';
															}
															else
															{
																$data=$data.'<span class="badge badge-success">Active</span>';
																if($_COOKIE['uid']==$profile_user_id)
																{
																	$data=$data.'<span style="float: right !important;">&nbsp;&nbsp;<a class="text-danger action-btn delete-btn" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRec('.$r_row['id'].');" href="javascript:void(0);"><i class="feather-trash-2"></i></a></span>';
																}
																$data=$data.'</h6><p class="m-0" id="received_rec_'.$i++.'">'.ucfirst(strtolower($r_row['r_text'])).'</p>';
															}
													$data=$data.'</div></div>';
															
													echo $data;
												}
											}
											else
											{
												?>
												<div class="col-12 p-1" id="nothing_to_show_rec">
													<div class="font-weight-bold p-2">
														<h6 style="text-align:center;font-size:14px;">Nothing to show.</h6>
													</div>
												</div>
												<?php
											}
										?>
										</div>
									</div>
									<div class="" style="width:100%;display:none;" id="given_recommendations">
										<div id="recommendation_data_matrix_1" style="width:100%;">
											<?php
												if(mysqli_num_rows($r_result_1))
												{
													while($r_row=mysqli_fetch_array($r_result_1))
													{
														$user_data_r=getUsersData($r_row['user_id']);
														$data='<div class="d-flex border-bottom" style="width:100%;" id="rec_'.$r_row['id'].'">
															<div class="col-3 border-right border-top p-1">
																<img class="img-fluid" style="border:1px solid gray;width:100% !important;border-radius: 50%;" src="'.getUserProfileImage($r_row['user_id']).'">
															</div>
															<div class="col-9 border-top p-1">
																<h6 class="m-0" style="text-align:center;font-size:14px;font-weight:normal !important;">'.ucfirst(strtolower($user_data_r['first_name'].' '.$user_data_r['last_name'])).'';
																$data=$data.'';
																if($_COOKIE['uid']==$profile_user_id)
																{
																	$data=$data.'<span style="float: right !important;">&nbsp;&nbsp;<a class="text-danger action-btn delete-btn" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRec('.$r_row['id'].');" href="javascript:void(0);"><i class="feather-trash-2"></i></a></span>';
																}
																$data=$data.'</h6>
																		<p class="mt-1 p-1">'.ucfirst(strtolower($r_row['r_text'])).'</p>
																	</div>
																</div>';
																
														echo $data;
													}
												}
												else
												{
													?>
													<div class="col-12 p-1" id="nothing_to_show_rec_1">
														<div class="font-weight-bold p-2">
															<h6 style="text-align:center;font-size:14px;font-weight:normal !important;">Nothing to show.</h6>
														</div>
													</div>
													<?php
												}
											?>
										</div>
									</div>
								</div>
							</div>
						
						</main>
										
						<aside class="col col-xl-3 order-xl-3 col-12 col-lg-12 order-lg-3 col-12" style="position:static;">
							<div class="hide_on_dashboard box mb-3 shadow-sm border rounded bg-white profile-box text-center">
								<div class="p-2 text-left border-bottom">
									<h6 class="font-weight-bold mb-0" style=" text-transform: uppercase; font-size: 14px !important; ">
										<img src="<?php echo base_url; ?>img/videocv.png" style="cursor: pointer;width: 25px;margin-right: 5px;"> Video cv or profile 
									</h6>
								</div>
								<?php
									$v_query="SELECT * FROM users_resume WHERE user_id='".$profile_user_id."' AND (profile_type=2 OR profile_type=3) AND is_default=1 ORDER BY id DESC";
									$v_result=mysqli_query($conn,$v_query);
									$video_num_rows=mysqli_num_rows($v_result);
									$video_file="";
									$token_video="";
									$video_file_id="";
									$clear_crystal_clear_url="";
									$visibility=1;
									$thumbnail=="";
									if($video_num_rows>0)
									{
										//$profile_percentage=$profile_percentage+10;
										
										$v_row=mysqli_fetch_array($v_result);
										$video_file_id=$v_row['file'];
										
										$video_query="SELECT * FROM videos WHERE id='$video_file_id'";
										$video_result=mysqli_query($conn,$video_query);
										if(mysqli_num_rows($video_result)>0)
										{
											$video_row=mysqli_fetch_array($video_result);
											$video_tags=$video_row['video_tags'];
											$thumbnail=$video_row['cover_image'];
											$visibility=$video_row['visibility'];
											$video_file=base_url.'ajax.videostream.php?action=stream&file_key='.base64_encode($video_row['file']);
											$clear_crystal_clear_url=base_url.'streaming.php?file_key='.md5($video_row['id']);
										}
										
										
										$profile_title=$v_row['profile_title'];
										$video_type=$v_row['video_type'];
										$token_video=$v_row['id'];
										$resume_headline=$v_row['resume_headline'];
										$profile_title=$v_row['profile_title'];
									}
									if($thumbnail=="")
									{
										$cover_image=default_cover_image;
									}
									else
									{
										$cover_image=$thumbnail;
									}
								?>
								<?php
									if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
									{
										?>
										<div>
											<video muted="" class="w-100" controls="" poster="<?php echo base_url.$cover_image; ?>" controlsList="nodownload" id="video_preview_data">
												<source src="<?php echo $video_file; ?>" type="video/mp4">
												Your browser does not support HTML5 video.
											</video>
										</div>
										<div class="p-3">
											<p class="m-0 font-weight-normel" id="video_profile_title" style="font-weight:normal !important;margin-top:20px;"><?php echo $profile_title; ?></p>
										</div>
										<?php
									}
									else
									{
										?>
										<div>
											<p style="text-align:center;">You needs to be login to view video cv/profile</p>
										</div>
										<?php
									}
								?>
							</div>
							
							<div class="hide_on_dashboard box shadow-sm mb-3 rounded bg-white ads-box text-center overflow-hidden">
								<img src="<?php echo base_url; ?>img/ads1.png" class="img-fluid" alt="RopeYou Premium">
								<div class="p-3 border-bottom">
									<h6 class="font-weight-bold text-gold">RopeYou Premium</h6>
									<p class="mb-0 text-muted">Grow &amp; nurture your network</p>
								</div>
								<div class="p-3">
									<button type="button" class="btn btn-outline-gold pl-4 pr-4"> ACTIVATE </button>
								</div>
							</div>
							<div class="hide_on_dashboard box shadow-sm mb-3 rounded bg-white ads-box text-center overflow-hidden is_stuck">
								 <img src="<?php echo base_url; ?>img/job1.png" class="img-fluid" alt="Responsive image">
								 <div class="p-3 border-bottom">
									<h6 class="font-weight-bold text-dark">RopeYou Solutions</h6>
									<p class="mb-0 text-muted">Looking for talent?</p>
								 </div>
								 <div class="p-3">
									<a href="<?php echo base_url; ?>post-job" class="btn btn-primary pl-4 pr-4"> POST A JOB </a>
								 </div>
							</div>
						</aside>
					
					</div>
				</div>
			</div>
			<?php include_once 'scripts.php'; $profile_percentage=getProfilePercentage($profile_user_id); ?>
			<script>
				var profile_percentage="<?php echo $profile_percentage; ?>";
				$(document).ready( function(){
					var x = parseInt(profile_percentage);
					window.percent = 0;
					window.progressInterval = window.setInterval( function(){
						if(window.percent < x) {
							window.percent++;
							$('.progress').addClass('progress-striped').addClass('active');
							$('.progress .progress-bar:first').removeClass().addClass('progress-bar')
							.addClass ( (percent < 30) ? 'progress-bar-danger' : ( (percent < 60) ? 'progress-bar-warning' : 'progress-bar-success' ) ) ;
							$('.progress .progress-bar:first').width(window.percent+'%');
							$('.progress .progress-bar:first').text(window.percent+'%');
						} else {
							window.clearInterval(window.progressInterval);
							// jQuery('.progress').removeClass('progress-striped').removeClass('active');
							//jQuery('.progress .progress-bar:first').text('Done!');
						}
					}, 50 );
				});
				if($(".is_stuck").length>0)
				{
					$(".is_stuck").stick_in_parent();
				}
				var base_url="<?php echo base_url; ?>";
				
				/*-------------------SHARE URL ON FACEBOOK--------------*/
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
		</body>
	<?php
		}
		else
		{
			include_once '404.php';
		}
	?>
</html>