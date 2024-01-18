<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head.php'; ?>
		<title>Browse Connections, Jobs & Others | RopeYou Connects</title>
	</head>
	<body>
		<style>
			.overlap-rounded-circle>.rounded-circle{
				width:25px;
				height:25px;
			}
			.network-item-body{
				min-height: 39px;
				max-height: 40px;
			}
		</style>
		<?php include_once 'header.php';$search=$_REQUEST['search']; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					  <div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post">
						 <h5 class="pl-3 pt-3 pr-3 border-bottom mb-0 pb-3">Search Results</h5>
						 <ul class="nav border-bottom osahan-line-tab" id="myTab" role="tablist">
							<li class="nav-item" onclick="LoadData('peoples','peoples.php?search=<?php echo str_replace(" ","-",$search); ?>');">
							   <a class="nav-link active" id="peoples-tab" data-toggle="tab" href="#peoples" role="tab" aria-controls="peoples" aria-selected="true">Peoples</a>
							</li>
							<li class="nav-item" onclick="LoadData('jobs','jobs.php?search=<?php echo str_replace(" ","-",$search); ?>');">
							   <a class="nav-link" id="jobs-tab" data-toggle="tab" href="#jobs" role="tab" aria-controls="jobs" aria-selected="false">Jobs</a>
							</li>
							<!--<li class="nav-item" onclick="LoadData('blogs','blogs.php?search=<?php echo str_replace(" ","-",$search); ?>');" id="blogs_tab">
							   <a class="nav-link" id="blogs-tab" data-toggle="tab" href="#blogs" role="tab" aria-controls="blogs" aria-selected="false">Blogs</a>
							</li>-->
						 </ul>
						 <div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="peoples" role="tabpanel" aria-labelledby="peoples-tab">
								<div class="p-3 user_section_home">
									<div class="row">
									<?php
										$search_arr=explode(" ",$search);
										$user_search_arr=array();
										
										$designation_query="SELECT * FROM users_work_experience WHERE title IN('".implode("','",$search_arr)."') OR company IN('".implode("','",$search_arr)."') OR description LIKE '%".$search."%' OR title LIKE '%".$search."%' OR company LIKE '%".$search."%'";
										$designation_result=mysqli_query($conn,$designation_query);
										if(mysqli_num_rows($designation_result)>0)
										{
											while($designation_row=mysqli_fetch_array($designation_result))
											{
												$user_search_arr[]=$designation_row['user_id'];
											}
										}
										$people_search_query="SELECT * FROM users WHERE (first_name IN('".implode("','",$search_arr)."') OR last_name IN('".implode("','",$search_arr)."') OR id IN('".implode("','",$user_search_arr)."') OR username IN('".implode("','",$search_arr)."') OR profile_title LIKE '%".$search."%') AND id!='".$_COOKIE['uid']."' AND status=1 AND in_appropriate=0";
										if($search=="")
										{
											$people_search_query="SELECT * FROM users WHERE id!='".$_COOKIE['uid']."' AND status=1 AND in_appropriate=0";
										}
										$people_search_result=mysqli_query($conn,$people_search_query);
										if(mysqli_num_rows($people_search_result)>0)
										{
											while($bridge_row=mysqli_fetch_array($people_search_result))
											{
												$connect_user_id=$bridge_row['id'];
												$bridge_personal_query="SELECT * FROM users_personal WHERE user_id='".$bridge_row['id']."'";
												$bridge_personal_result=mysqli_query($conn,$bridge_personal_query);
												$home_town="";
												if(mysqli_num_rows($bridge_personal_result))
												{
													$bridge_personal_row=mysqli_fetch_array($bridge_personal_result);
													$home_town_id=$bridge_personal_row['home_town'];
													if($home_town_id!="" && $home_town_id!="0")
													{
														$home_town=getCityByID($home_town_id);
														if($home_town=="NA")
														{
															$home_town="";
														}
													}
												}
												$profile=getUserProfileImage($connect_user_id);
												$last_designation=getUsersCurrentDesignation($connect_user_id);
									?>
												 <div class="col-md-4" id="user_section_peoples_<?php echo $connect_user_id; ?>">
													<b href="profile">
													   <div class="border network-list network-item rounded mb-3">
															<div class="p-3 text-center" style="min-height:260px;max-height:261px;overflow:hidden;">
																<div class="mb-3">
																	<img class="rounded-circle" data-userid="<?php echo $connect_user_id; ?>" data-image="<?php echo str_replace(base_url,image_kit,$profile); ?>" src="<?php echo $profile; ?>" alt="" style="border:1px solid #eaebec !important;">
																</div>
																<div class="font-weight-bold">
																	<h6 class="font-weight-bold text-dark mb-0"><a href="<?php echo base_url; ?>u/<?php echo $bridge_row['username']; ?>" style="text-decoration:none;"><?php echo ucwords(strtolower($bridge_row['first_name']." ".$bridge_row['last_name'])); ?></a></h6>
																	<div class="small text-black-50" style="font-size:14px;"><?php echo $last_designation;  echo "<br/>"; echo $home_town; ?></div>
																</div>
															</div>
														  
															<div class="d-flex align-items-center p-3 border-top border-bottom network-item-body">
																<?php echo getCommonPersons($bridge_row['id'],$_COOKIE['uid']); ?>
															</div>
															<?php
																$connect_query="SELECT * FROM user_joins_user WHERE (user_id='".$_COOKIE['uid']."' AND r_user_id='".$bridge_row['id']."') OR (r_user_id='".$_COOKIE['uid']."' AND user_id='".$bridge_row['id']."')";
																$connect_result=mysqli_query($conn,$connect_query);
																$num_rows=mysqli_num_rows($connect_result);
																$text="Connect";
																$follow="Follow";
																if($num_rows>0)
																{
																	$connect_row=mysqli_fetch_array($connect_result);
																	if($connect_row['status']==1)
																	{
																		$text="Disconnect";
																		$follow="Following";
																	}
																	else if($connect_row['status']==4)
																	{
																		$text="Requested";
																		$follow="Following";
																	}
																	else 
																	{
																		$text="Connect";
																		$follow="Follow";
																	}
																}
																
															?>
															<div class="network-item-footer py-3 d-flex text-center">
																<?php
																	$text="Connect";
																	$follow="Follow";
																	$connect_user_click_data="ConnectUser('".$connect_user_id."','connect');";
																	$follow_user_click_data="ConnectUser('".$connect_user_id."','follow');";
																	if(canConnectToUser($_COOKIE['uid'],$connect_user_id,"connect"))
																	{
																		$connect_query="SELECT * FROM user_joins_user WHERE (user_id='".$_COOKIE['uid']."' AND r_user_id='".$connect_user_id."') OR (r_user_id='".$_COOKIE['uid']."' AND user_id='".$connect_user_id."')";
																		$connect_result=mysqli_query($conn,$connect_query);
																		$num_rows=mysqli_num_rows($connect_result);
																		
																		if($num_rows>0)
																		{
																			$connect_row=mysqli_fetch_array($connect_result);
																			if($connect_row['status']==1)
																			{
																				$text="Disconnect";
																				$connect_user_click_data="ConnectUser('".$connect_user_id."','disconnect');";
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
																				$connect_user_click_data="ConnectUser('".$connect_user_id."','connect');";
																			}
																		}
																		
																		?>
																		<div class="col-6 pl-3 pr-1 connect_user_<?php echo $connect_user_id; ?>">
																			<button type="button" onclick="<?php echo $connect_user_click_data; ?>" class="btn btn-primary btn-sm btn-block"> <?php echo $text; ?> </button>
																		</div>
																		<?php
																	}
																	if(canConnectToUser($_COOKIE['uid'],$connect_user_id,"follow"))
																	{
																		$following=doIFollow($_COOKIE['uid'],$connect_user_id);
																		if($following)
																		{
																			$follow_user_click_data="ConnectUser('".$connect_user_id."','unfollow');";
																			$follow="Unfollow";
																		}
																		else
																		{
																			$follow_user_click_data="ConnectUser('".$connect_user_id."','follow');";
																			$follow="Follow";
																		}
																		?>
																		<div class="col-6 pr-3 pl-1 follow_user_<?php echo $connect_user_id; ?>">
																			<button type="button" onclick="<?php echo $follow_user_click_data; ?>" class="btn btn-outline-primary btn-sm btn-block"> <i class="feather-user-plus"></i> <?php echo $follow; ?> </button>
																		</div>
																		<?php
																	}
																?>
															</div>
													   </div>
													</b>
												 </div>
										
										<?php
											}
										}
										else
										{
											?>
											<div class="col-md-12">
												<h5 style="text-align:center;">unable to found any data related to your query.</h5>
											</div>
											<?php
										}
									?>
									</div>
							   </div>
							</div>
							<div class="tab-pane fade" id="jobs" role="tabpanel" aria-labelledby="jobs-tab">
								
							</div>
							<div class="tab-pane fade" id="blogs" role="tabpanel" aria-labelledby="blogs-tab">
							
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
		<script>
			var user_id="<?php echo $_COOKIE['uid']; ?>";
			var base_url="<?php echo base_url; ?>";
			function LoadData(dom,data_section)
			{
				$("#"+dom).load(base_url+"load-search-"+data_section);
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
			loadImageSlider("user_section_home");
		</script>
	</body>
</html>
