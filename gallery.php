<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<title>Gallery | RopeYou Connects</title>
	</head>
	<body>
		<?php include_once 'header.php'; ?>
		<?php
			if(isset($_REQUEST['nthread']) && $_REQUEST['nthread']!="")
			{
				mysqli_query($conn,"UPDATE threats_to_user SET seen=1 WHERE user_id='".$_COOKIE['uid']."' AND md5(id)='".$_REQUEST['nthread']."'");
			}
		?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
						<div class="modal fade amazing_file_upload_personal_backdrop_modal" id="amazing_file_upload_personal_backdrop_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="amazing_file_upload_personal_backdrop_modalBackdrop" aria-hidden="true">
							<div class="modal-dialog modal-lg " role="document">
								<div class="modal-content">
									<div class="modal-header modal-header-full-width">
										<h6 class="modal-title" id="amazing_file_upload_personal_backdrop_modalBackdrop">Upload Personal Media</h6>
									</div>
									<div class="modal-body" style="min-height:500px;overflow-y:auto;">											
										<div class="p-0" id="personal_gallery_media_data">												
											
										</div>
									</div>
									<div class="modal-footer-full-width p-4">
										<button type="button" class="btn btn-secondary" onclick="reloadMediaType('personal');" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
							<div class="modal fade amazing_file_upload_professional_backdrop_modal" id="amazing_file_upload_professional_backdrop_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="amazing_file_upload_professional_backdrop_modalBackdrop" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header modal-header-full-width">
											<h6 class="modal-title" id="amazing_file_upload_professional_backdrop_modalBackdrop">Upload Professional Media</h6>
										</div>
										<div class="modal-body" style="min-height:500px;overflow-y:auto;">											
											<div class="p-0" id="professional_gallery_media_data">												
												
											</div>
										</div>
										<div class="modal-footer-full-width p-4">
											<button type="button" class="btn btn-secondary" onclick="reloadMediaType('professional');" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post" id="reload_media_data_section">
							<h5 class="pl-3 pt-3 pr-3 border-bottom mb-0 pb-3">Manage your gallery</h5>
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
													<div class="col-md-12 mb-2">
														<a href="javascript:void(0);" onclick="personal_gallery_media_data();"  data-toggle="modal" data-target="#amazing_file_upload_personal_backdrop_modal" class="button primary small circle uk-visible@s pull-right"> 
															<i class="uil-plus"> </i> Upload New Media 
														</a>
													</div>
													<?php
														$is_professional=0;
														$preloadedFiles = array();
														$query = $conn->query("SELECT * FROM gallery WHERE user_id='".$_COOKIE['uid']."' AND is_professional='$is_professional' AND is_draft=0 AND delete_status=0 ORDER BY id DESC");
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
																	$media_id=$row['id'];
																	if($itype=="image")
																	{
																		?>
																		<div class="col-md-3" style="margin-top:10px;">
																			<div class="card" style="padding:0px;box-sizing: border-box;border:5px solid #eaebec!important;">
																				<div class="btn-group">
																					<!--dropdown-toggle-->
																					<!--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"-->
																					<button type="button" onclick="deleteMedia('<?php echo $media_id; ?>','personal');" class="btn btn-danger ">
																						Delete
																					</button>
																					<!--<div class="dropdown-menu" style="">
																						<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMedia('<?php echo $media_id; ?>');">Delete</a>
																						<a class="dropdown-item" href="#">Another action</a>
																						<a class="dropdown-item" href="#">Something else here</a>
																						<div class="dropdown-divider"></div>
																						<a class="dropdown-item" href="#">Separated link</a>
																					</div>-->
																				</div>
																				<a href="javascript:void(0);" onclick="" data-m="image" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>"  id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																					<img data-id="<?php echo $row['id']; ?>" data-media="image" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo base_url.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;">
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
																	else if($itype=="video")
																	{
																		?>
																		<div class="col-md-3" style="margin-top:10px;box-sizing: border-box;">
																			<div class="card" style="padding:0px;box-sizing: border-box;border:5px solid #eaebec!important;">
																				<div class="btn-group">
																					<!--dropdown-toggle-->
																					<!--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"-->
																					<button type="button" onclick="deleteMedia('<?php echo $media_id; ?>','personal');" class="btn btn-danger ">
																						Delete
																					</button>
																					<!--<div class="dropdown-menu" style="">
																						<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMedia('<?php echo $media_id; ?>');">Delete</a>
																						<a class="dropdown-item" href="#">Another action</a>
																						<a class="dropdown-item" href="#">Something else here</a>
																						<div class="dropdown-divider"></div>
																						<a class="dropdown-item" href="#">Separated link</a>
																					</div>-->
																				</div>
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
													<div class="col-md-12 mb-2">
														<a href="javascript:void(0);" onclick="professional_gallery_media_data();" data-toggle="modal" data-target="#amazing_file_upload_professional_backdrop_modal" class="button primary small circle uk-visible@s pull-right"> 
															<i class="uil-plus"> </i> Upload New Media 
														</a>
														
													</div>
													<?php
														$is_professional=1;
														$preloadedFiles = array();
														$query = $conn->query("SELECT * FROM gallery WHERE user_id='".$_COOKIE['uid']."' AND is_professional='$is_professional' AND is_draft=0 AND delete_status=0 ORDER BY id DESC");
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
																	$media_id=$row['id'];
																	if($itype=="image")
																	{
																		?>
																		<div class="col-md-3" style="margin-top:10px;box-sizing: border-box;">
																			<div class="card" style="padding:0px;box-sizing: border-box;border:5px solid #eaebec!important;">
																				<div class="btn-group">
																					<!--dropdown-toggle-->
																					<!--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"-->
																					<button type="button" onclick="deleteMedia('<?php echo $media_id; ?>','professional');" class="btn btn-danger ">
																						Delete
																					</button>
																					<!--<div class="dropdown-menu" style="">
																						<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMedia('<?php echo $media_id; ?>');">Delete</a>
																						<a class="dropdown-item" href="#">Another action</a>
																						<a class="dropdown-item" href="#">Something else here</a>
																						<div class="dropdown-divider"></div>
																						<a class="dropdown-item" href="#">Separated link</a>
																					</div>-->
																				</div>
																				<a href="javascript:void(0);" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>" data-m="image" id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																					<img data-id="<?php echo $row['id']; ?>" data-media="image" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo image_kit.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;">
																					<div class="photo-card-custom" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
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
																	else if($itype=="video")
																	{
																		
																		?>
																		<div class="col-md-3" style="margin-top:10px;box-sizing: border-box;">
																			<div class="card" style="padding:0px;box-sizing: border-box;border:5px solid #eaebec!important;">
																				<div class="btn-group">
																					<!--dropdown-toggle-->
																					<!--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"-->
																					<button type="button" onclick="deleteMedia('<?php echo $media_id; ?>','professional');" class="btn btn-danger ">
																						Delete
																					</button>
																					<!--<div class="dropdown-menu" style="">
																						<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMedia('<?php echo $media_id; ?>');">Delete</a>
																						<a class="dropdown-item" href="#">Another action</a>
																						<a class="dropdown-item" href="#">Something else here</a>
																						<div class="dropdown-divider"></div>
																						<a class="dropdown-item" href="#">Separated link</a>
																					</div>-->
																				</div>
																				<a href="javascript:void(0);" data-m="video" data-prevm="<?php echo $prev_m; ?>" data-nextm="<?php echo $next_m; ?>" id="media_file_clicked_<?php echo $row['id']; ?>" class="media_file_clicked" data-nextuserid="<?php if($next_row){ echo $next_row['user_id']; } ?>" data-prevuserid="<?php if($last_row){ echo $last_row['user_id']; } ?>" data-previd="<?php if($last_row){ echo $last_row['id']; } ?>" data-prevcaption="<?php if($last_row){ echo $last_row['title']; } ?>" data-prevsrc="<?php if($last_row){ echo base_url.$last_row['file']; } ?>" data-nextid="<?php if($next_row){ echo $next_row['id']; } ?>" data-nextcaption="<?php if($next_row){ echo $next_row['title']; } ?>" data-nextsrc="<?php if($next_row){ echo base_url.$next_row['file']; } ?>" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
																					<video data-id="<?php echo $row['id']; ?>" data-media="video" data-src="<?php echo base_url.$row['file']; ?>" src="<?php echo image_kit.$row['file'].'?tr=w-151,h-191'; ?>" data-caption="<?php echo $row['title']; ?>" class="img-fluid" style="width:100%;min-height:190px;max-height:191px;"></video>
																					<div class="photo-card-custom" data-id="<?php echo $row['id']; ?>" data-src="<?php echo base_url.$row['file']; ?>" data-caption="<?php echo $row['title']; ?>">
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
								<img src="img/jobs.jpg" class="img-fluid rounded-circle shadow-sm" alt="Responsive image">
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
		<script>
			var click_counter=0;
			var base_url="<?php echo base_url; ?>";
			function reloadMediaType(media_type)
			{
				$("#"+media_type+"_gallery_data_tab").load(media_type+'_gallery_media_data_html.php');
			}
			
			function deleteMedia(media_id,media_type)
			{
				$.ajax({
					url:base_url+"delete-gallery-media-webservice",
					type:"post",
					data:{media_id:media_id,media_type:media_type},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							$("#"+media_type+"_gallery_data_tab").load(media_type+'_gallery_media_data_html.php');
						}
					}
				});
			}
			function personal_gallery_media_data()
			{
				if(click_counter==0)
				{
					click_counter=click_counter+1;
					$("#personal_gallery_media_data").html('');
					$("#personal_gallery_media_data").load('gallery_media.php?gallery_type=personal&pre=0');
				}
			}
			var click_counter_1=0;
			function professional_gallery_media_data()
			{
				if(click_counter_1==0)
				{
					click_counter_1=click_counter_1+1;
					$("#professional_gallery_media_data").html('');
					$("#professional_gallery_media_data").load('gallery_media.php?gallery_type=professional&pre=0');
				}
			}
		</script>
	</body>
</html>
