<div class="p-3 user_section_peoples">
	<div class="row">
	<?php
		include_once 'connection.php';
		$search=str_replace("-"," ",$_REQUEST['search']);
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
				 <div class="col col-6 col-lg-4" id="user_section_peoples_<?php echo $connect_user_id; ?>">
					<b href="profile">
					   <div class="border network-list network-item rounded mb-3">
							<div class="p-3 text-center network-header-custom">
								 <div class="mb-3">
									<img class="rounded-circle" data-userid="<?php echo $connect_user_id; ?>" data-image="<?php echo str_replace(base_url,image_kit,$profile); ?>" src="<?php echo $profile; ?>" alt="" style="border:1px solid #eaebec !important;">
								 </div>
								 <div class="font-weight-bold">
									<h6 class="font-weight-bold text-dark mb-0"><a href="<?php echo base_url; ?>u/<?php echo $bridge_row['username']; ?>" style="text-decoration:none;"><?php echo ucwords(strtolower($bridge_row['first_name']." ".$bridge_row['last_name'])); ?></a></h6>
									<div class="small text-black-50 user-desc-small"><?php echo $last_designation;  echo "<br/>"; echo $home_town; ?></div>
								 </div>
							</div>
						  
							<div class="mutual_connections d-flex align-items-center p-3 border-top border-bottom network-item-body">
								<?php echo getCommonPersons($bridge_row['id'],$_COOKIE['uid']); ?>
							</div>
							<div class="network-item-footer py-3 d-flex text-center" style="min-height:60px;">
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
							