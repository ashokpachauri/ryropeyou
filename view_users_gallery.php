<?php 
	include_once 'connection.php';
	$user_row=null;
	$username=$_REQUEST['__username'];
	$uquery="SELECT * FROM users WHERE username='$username' AND status=1";
	$uresult=mysqli_query($conn,$uquery);
	if(mysqli_num_rows($uresult)>0)
	{
	
		$userrow=mysqli_fetch_array($uresult);
		$profile_user_id=$userrow['id'];
	?>
		<head>
				<?php 
					include_once 'profile-head.php';
					$user_id=$profile_user_id;
				?>
		<title><?php echo ucwords(strtolower($userrow['first_name'].' '.$userrow['last_name'])); ?>'s Gallery | RopeYou Connects</title>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body>
		<?php 
			include_once 'header.php';
			$user_id=$profile_user_id;
			$uquery="SELECT * FROM users WHERE id='$profile_user_id'";
			$uresult=mysqli_query($conn,$uquery);
			$userrow=mysqli_fetch_array($uresult);
		?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					  <div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post">
						 <h5 class="pl-3 pt-3 pr-3 border-bottom mb-0 pb-3"><?php echo ucwords(strtolower($userrow['first_name'].' '.$userrow['last_name'])); ?>'s Gallery</h5>
						 <ul class="nav border-bottom osahan-line-tab" id="myTab" role="tablist">
							<li class="nav-item">
							   <a class="nav-link active" id="home-tab" data-toggle="tab" href="#personal_gallery_data_tab" role="tab" aria-controls="home" aria-selected="true">Personal</a>
							</li>
							<li class="nav-item">
							   <a class="nav-link" id="profile-tab" data-toggle="tab" href="#professional_gallery_data_tab" role="tab" aria-controls="profile" aria-selected="false">Professional</a>
							</li>
						 </ul>
						 <div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="personal_gallery_data_tab" role="tabpanel" aria-labelledby="home-tab">
								<div class="p-3">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-lg-12" style="min-height:150px;">
											<div class="row">
											<?php
												$is_professional=0;
												$preloadedFiles = array();
												$query = $conn->query("SELECT * FROM gallery WHERE user_id='".$profile_user_id."' AND is_professional='$is_professional' AND is_draft=0 AND delete_status=0 ORDER BY `index` ASC");
												if ($query && $query->num_rows > 0) {
													while($row = $query->fetch_assoc()) {
														
														#===========================================
														$c_query="SELECT * FROM media_comments WHERE media_id='".$row['id']."'";
														$c_result=mysqli_query($conn,$c_query);
														$comments_num_rows=mysqli_num_rows($c_result);
														#============================================
														$l_query="SELECT * FROM media_likes WHERE media_id='".$row['id']."'";
														$l_result=mysqli_query($conn,$l_query);
														$likes_num_rows=mysqli_num_rows($l_result);
														#============================================
														$s_query="SELECT * FROM media_shares WHERE media_id='".$row['id']."'";
														$s_result=mysqli_query($conn,$s_query);
														$shares_num_rows=mysqli_num_rows($s_result);
														#============================================
														$preloadedFiles[] = array(
															'id' => $row['id'],
															'title' => $row['title'],
															'user_id' => $row['user_id'],
															'type' => $row['type'],
															'size' => $row['size'],
															'file' => $row['file'],
															'comments_num_rows' => $comments_num_rows,
															'likes_num_rows' => $likes_num_rows,
															'shares_num_rows' => $shares_num_rows,
															'date' => $row['date'],
															'isMain' => $row['is_main'],
															'isBanner' => $row['is_banner']
														);
														
													}
													if(count($preloadedFiles)>0)
													{
														$last_row=false;
														$next_row=false;
														$count=count($preloadedFiles);
														$i=0;
														foreach($preloadedFiles as $row)
														{
															if($i==0)
															{
																if($count>1)
																{
																	$last_row=$preloadedFiles[$count-1];
																	$next_row=$preloadedFiles[$i+1];
																}
															}
															else
															{
																$last_row=$preloadedFiles[$i-1];
																if($i==($count-1))
																{
																	$next_row=$preloadedFiles[0];
																}
																else
																{
																	$next_row=$preloadedFiles[$i+1];
																}
															}
															$type_slug=$last_row['type'];
															$type_slug_arr=explode("/",$type_slug);
															$prev_m=$type_slug_arr[0];
															
															$type_slug=$next_row['type'];
															$type_slug_arr=explode("/",$type_slug);
															$next_m=$type_slug_arr[0];
															
															$type_slug=$row['type'];
															$type_slug_arr=explode("/",$type_slug);
															$itype=$type_slug_arr[0];
															if($itype=="image")
															{
																?>
																<div class="col-md-3" style="margin-top:10px;">
																	<div class="card" style="padding:0px;">
																		<a href="javascript:void(0);" onclick="" data-m="image" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>"  id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																			<img data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo image_kit.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;">
																			<div class="photo-card-custom" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>" style="max-height:35px;">
																				<div class="photo-card-custom-content">
																					<div style="padding:0px;margin:0px;width:100%;">
																						<!--<h6 style="text-align:center;"><?php //echo $row['title']; ?></h6>-->
																						<p style="text-align:center;color:#bfc3da;"> <span class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-thumbs-up"></i> <?php echo $row['likes_num_rows']; ?></span>
																							<span><i class="fa fa-comments-o"></i> <?php echo $row['comments_num_rows']; ?>   </span>
																							<span class="pull-right"><i class="fa fa-share"></i> <?php echo $row['shares_num_rows']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
																						</p>
																					</div>
																				</div>
																			</div>
																		</a>
																	</div>
																</div>
																<?php
															}
															else if($itype=="video")
															{
																?>
																<div class="col-md-3" style="margin-top:10px;">
																	<div class="card" style="padding:0px;">
																		<a href="javascript:void(0);" onclick="" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>"  data-m="video" id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																			<video controls controlsList="nodownload" data-media="video" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo base_url.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;"></video>
																			<div class="photo-card-custom" style="max-height:35px;" data-id="<?php echo $row['id']; ?>" data-src="<?php echo image_kit.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																				<div class="photo-card-custom-content">
																					<div style="padding:0px;margin:0px;width:100%;">
																						<!--<h6 style="text-align:center;"><?php echo $row['title']; ?></h6>-->
																						<p style="text-align:center;color:#bfc3da;"> <span class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-thumbs-up"></i> <?php echo $row['likes_num_rows']; ?></span>
																							<span><i class="fa fa-comments-o"></i> <?php echo $row['comments_num_rows']; ?>   </span>
																							<span class="pull-right"><i class="fa fa-share"></i> <?php echo $row['shares_num_rows']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
																						</p>
																					</div>
																				</div>
																			</div>
																		</a>
																	</div>
																</div>
																<?php
															}
															$i=$i+1;
														}
													}
												}
												else{
													?>
													<div class="col-md-12 mt-2">
														<p style="text-align:center;">Nothing in personal gallery</p>
													</div>
													<?php
												}
											?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="professional_gallery_data_tab" role="tabpanel" aria-labelledby="profile-tab">
								<div class="p-3">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-lg-12" style="min-height:150px;">
											<div class="row">
											<?php
												$is_professional=1;
												$preloadedFiles = array();
												$query = $conn->query("SELECT * FROM gallery WHERE user_id='".$profile_user_id."' AND is_professional='$is_professional' AND is_draft=0 AND delete_status=0 ORDER BY `index` ASC");
												if ($query && $query->num_rows > 0) {
													while($row = $query->fetch_assoc()) {
														
														#===========================================
														$c_query="SELECT * FROM media_comments WHERE media_id='".$row['id']."'";
														$c_result=mysqli_query($conn,$c_query);
														$comments_num_rows=mysqli_num_rows($c_result);
														#============================================
														$l_query="SELECT * FROM media_likes WHERE media_id='".$row['id']."'";
														$l_result=mysqli_query($conn,$l_query);
														$likes_num_rows=mysqli_num_rows($l_result);
														#============================================
														$s_query="SELECT * FROM media_shares WHERE media_id='".$row['id']."'";
														$s_result=mysqli_query($conn,$s_query);
														$shares_num_rows=mysqli_num_rows($s_result);
														#============================================
														$preloadedFiles[] = array(
															'id' => $row['id'],
															'title' => $row['title'],
															'user_id' => $row['user_id'],
															'type' => $row['type'],
															'size' => $row['size'],
															'file' => $row['file'],
															'comments_num_rows' => $comments_num_rows,
															'likes_num_rows' => $likes_num_rows,
															'shares_num_rows' => $shares_num_rows,
															'date' => $row['date'],
															'isMain' => $row['is_main'],
															'isBanner' => $row['is_banner']
														);
														
													}
													if(count($preloadedFiles)>0)
													{
														$last_row=false;
														$next_row=false;
														$count=count($preloadedFiles);
														$i=0;
														foreach($preloadedFiles as $row)
														{
															if($i==0)
															{
																if($count>1)
																{
																	$last_row=$preloadedFiles[$count-1];
																	$next_row=$preloadedFiles[$i+1];
																}
															}
															else
															{
																$last_row=$preloadedFiles[$i-1];
																if($i==($count-1))
																{
																	$next_row=$preloadedFiles[0];
																}
																else
																{
																	$next_row=$preloadedFiles[$i+1];
																}
															}
															$type_slug=$last_row['type'];
															$type_slug_arr=explode("/",$type_slug);
															$prev_m=$type_slug_arr[0];
															
															$type_slug=$next_row['type'];
															$type_slug_arr=explode("/",$type_slug);
															$next_m=$type_slug_arr[0]; 
															
															$type_slug=$row['type'];
															$type_slug_arr=explode("/",$type_slug);
															$itype=$type_slug_arr[0];
															if($itype=="image")
															{
																?>
																<div class="col-md-3" style="margin-top:10px;">
																	<div class="card" style="padding:0px;">
																		<a href="javascript:void(0);" onclick="" data-m="image" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>" id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																			<img data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo image_kit.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;">
																			<div class="photo-card-custom" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>" style="max-height:35px;">
																				<div class="photo-card-custom-content">
																					<div style="padding:0px;margin:0px;width:100%;">
																						<!--<h6 style="text-align:center;"><?php //echo $row['title']; ?></h6>-->
																						<p style="text-align:center;color:#bfc3da;"> <span class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-thumbs-up"></i> <?php echo $row['likes_num_rows']; ?></span>
																							<span><i class="fa fa-comments-o"></i> <?php echo $row['comments_num_rows']; ?>   </span>
																							<span class="pull-right"><i class="fa fa-share"></i> <?php echo $row['shares_num_rows']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
																						</p>
																					</div>
																				</div>
																			</div>
																		</a>
																	</div>
																</div>
																<?php
															}
															else if($itype=="video")
															{
																?>
																<div class="col-md-3" style="margin-top:10px;">
																	<div class="card" style="padding:0px;">
																		<a href="javascript:void(0);" onclick="" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>" data-m="video" id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																			<video controls controlsList="nodownload" data-media="video" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo base_url.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;"></video>
																			<div class="photo-card-custom" style="max-height:35px;" data-id="<?php echo $row['id']; ?>" data-src="<?php echo image_kit.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																				<div class="photo-card-custom-content">
																					<div style="padding:0px;margin:0px;width:100%;">
																						<!--<h6 style="text-align:center;"><?php echo $row['title']; ?></h6>-->
																						<p style="text-align:center;color:#bfc3da;"> <span class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-thumbs-up"></i> <?php echo $row['likes_num_rows']; ?></span>
																							<span><i class="fa fa-comments-o"></i> <?php echo $row['comments_num_rows']; ?>   </span>
																							<span class="pull-right"><i class="fa fa-share"></i> <?php echo $row['shares_num_rows']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
																						</p>
																					</div>
																				</div>
																			</div>
																		</a>
																	</div>
																</div>
																<?php
															}
															$i=$i+1;
														}
													}
												}
												else{
													?>
													<div class="col-md-12 mt-2">
														<p style="text-align:center;">Nothing in professional gallery</p>
													</div>
													<?php
												}
											?>
											</div>
										</div>
									</div>
								</div>
							</div>
						 </div>
					  </div>
				   </main>
				   <aside class="col col-xl-3 order-xl-2 col-lg-12 order-lg-2 col-12">
					<?php
						include_once 'people_you_may_know.php';
						$profile=getUserProfileImage($_COOKIE['uid']);
					?>
					  <div class="box shadow-sm mb-3 border rounded bg-white ads-box text-center">
						 <div class="image-overlap-2 pt-4">
							<img src="<?php echo $profile; ?>" class="img-fluid rounded-circle shadow-sm" alt="Responsive image">
							<img src="<?php echo base_url; ?>img/jobs.jpg" class="img-fluid rounded-circle shadow-sm" alt="Responsive image">
						 </div>
						 <div class="p-3 border-bottom">
							<h6 class="text-dark"><?php echo $user_row['first_name']." ".$user_row['last_name'] ?>, grow your career by following <span class="font-weight-bold"> RopeYou</span></h6>
							<p class="mb-0 text-muted">Stay up-to industry trends!</p>
						 </div>
						 <div class="p-3">
							<button type="button" class="btn btn-outline-primary btn-sm pl-4 pr-4"> FOLLOW </button>
						 </div>
					  </div>
					</aside>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<?php include_once 'media_viewer.php'; ?>
	</body>
</html>
<?php
	}
	else
	{
		include_once '404.php';
	}
?>
