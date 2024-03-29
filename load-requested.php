<?php
	include_once 'connection.php';
?>
<div class="p-3 requested_user_data_hub">
	<div class="row">
        <div class="col-md-12">
            <h6>Outgoing Connection Requests</h6>
        </div>
	<?php
		$user_id=$_COOKIE['uid'];
		$response['status']="success";
		$requested_query="SELECT * FROM user_joins_user WHERE r_user_id='$user_id' AND status=4";
		$requested_result=mysqli_query($conn,$requested_query);
		$requested=mysqli_num_rows($requested_result);
		if($requested<=0)
		{
			$data="<div class='col-md-12'><h5 style='text-align:center;'>You have no pending requests.</h5></div>";
		}
		else
		{
			$data="";
			while($requested_row=mysqli_fetch_array($requested_result))
			{
				$connect_user_id=$requested_row['user_id'];
				$user_query="SELECT * FROM users WHERE id='$connect_user_id' AND status=1";
				$user_result=mysqli_query($conn,$user_query);
				if(mysqli_num_rows($user_result)>0)
				{
					$bridge_row=mysqli_fetch_array($user_result);
					$home_town="";
					$bridge_personal_query="SELECT * FROM users_personal WHERE user_id='".$connect_user_id."'";
					$bridge_personal_result=mysqli_query($conn,$bridge_personal_query);
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
					$last_education=getUsersCurrentEducation($connect_user_id);
					$data.='<div class="col col-6 col-lg-3" id="user_section_requested_'.$connect_user_id.'">
					<b href="profile">
					   <div class="border network-list network-item rounded mb-3">
						  <div class="p-3 text-center network-header-custom">
							 <div class="mb-3">
								<img class="rounded-circle" data-userid="'.$connect_user_id.'" data-image="'.$profile.'" src="'.$profile.'" alt="" style="border:1px solid #eaebec !important;">
							 </div>
							 <div class="font-weight-bold">
								<h6 class="font-weight-bold text-dark mb-0"><a href="'.base_url.'u/'.$bridge_row['username'].'" style="text-decoration:none;">'.$bridge_row['first_name']." ".$bridge_row['last_name'].'</a></h6>
								<div class="small text-black-50 user-desc-small">';
								if($last_designation){ $data.= $last_designation.'<br/>'; } if($last_education){ $data.= $last_education.'<br/>'; }  if($home_town!="") { $data.= "Location : ".$home_town; } 
								$data.='</div>
							 </div>
						  </div>';
							
							$data.='<div class="mutual_connections d-flex align-items-center p-3 border-top border-bottom network-item-body">'.getCommonPersons($bridge_row['id'],$_COOKIE['uid']).
								'</div>';
							$data.='<div class="network-item-footer py-3 d-flex text-center" style="min-height:60px;">';
							$text="Connect";
							$follow="Follow";
							$connect_user_click_data="ConnectUser('".$connect_user_id."','connect');";
							$follow_user_click_data="ConnectUser('".$connect_user_id."','follow');";
							//canConnectToUser($_COOKIE['uid'],$connect_user_id,"connect")
							if(true)
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
								$data.='<div class="col-6 pl-3 pr-1 connect_user_'.$connect_user_id.'">
									<button type="button" onclick="'.$connect_user_click_data.'" class="btn btn-primary btn-sm btn-block">'.$text.'</button>
								</div>';
							}
							//canConnectToUser($_COOKIE['uid'],$connect_user_id,"follow")
							if(true)
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
								$data.='<div class="col-6 pr-3 pl-1 follow_user_'.$connect_user_id.'">
									<button type="button" onclick="'.$follow_user_click_data.'" class="btn btn-outline-primary btn-sm btn-block"> <i class="feather-user-plus"></i> '.$follow.' </button>
								</div>';
							}
							$data.='</div>
						</div>
					</b>
				 </div>';
				}
			}
		}
		$data=$data.'<script>
	loadImageSlider("requested_user_data_hub");
</script>';
        echo $data;
	?>
	</div>
</div>	
							
							