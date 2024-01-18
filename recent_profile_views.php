<?php						
	$viewers_query="SELECT * FROM users_profile_views WHERE user_id='".$_COOKIE['uid']."' ORDER BY id DESC LIMIT 20";
	$viewers_result=mysqli_query($conn,$viewers_query);
	if(mysqli_num_rows($viewers_result)>0)
	{
		$usersData=getUsersData($_COOKIE['uid']);
?>
		<div class="box shadow-sm border rounded bg-white mb-3 hide_on_dashboard">
			<div class="box-title border-bottom p-3" style="width:100%;">
				<h6 class="m-0" style="width:100%;">Profile Views <a href="<?php echo base_url.'u/'.strtolower($usersData['username']).'/profile-views'; ?>" class="pull-right" title="More"><i class="fa fa-arrow-right"></i></a></h6>
			</div>
			<div class="box-body p-3" style="max-height:400px;overflow-y:auto;">
				<?php
					while($viewers_row=mysqli_fetch_array($viewers_result))
					{
						$viewer_id=$viewers_row['viewer_id'];
						$viewer_user=getUsersData($viewer_id);
						
						?>
						<div class="d-flex align-items-center osahan-post-header mb-3 people-list">
							<div class="dropdown-list-image mr-3">
								<a href="<?php echo base_url."u/".$viewer_user['username']; ?>">
									<img class="rounded-circle" style="border:1px solid #eaebec !important;" src="<?php echo getUserProfileImage($viewer_id); ?>" alt="<?php echo ucfirst(strtolower($viewer_user['first_name']." ".$viewer_user['last_name'])); ?>">
									<div class="status-indicator <?php if(userLoggedIn($viewer_id)){ echo 'bg-success';} else{ echo 'bg-danger'; } ?>">
									</div>
								</a>
							</div>
							<div class="font-weight-bold mr-2">
								<div class="text-truncate">
									<a href="<?php echo base_url."u/".$viewer_user['username']; ?>">
										<?php echo ucfirst(strtolower($viewer_user['first_name']." ".$viewer_user['last_name'])); ?>
									</a>
								</div>
								<div class="small text-gray-500">
									<?php echo ucfirst(strtolower($viewer_user['profile_title'])); ?>
								</div>
							</div>
							<span class="ml-auto connect_user_<?php echo $viewer_id; ?>">
								<?php
									if(canConnectToUser($_COOKIE['uid'],$viewer_id,"connect"))
									{
										$text='Connect';
										$connect_user_click_data="ConnectUser('".$viewer_id."','connect');";
										$uj_query="SELECT * FROM user_joins_user WHERE ((user_id='".$_COOKIE['uid']."' AND r_user_id='".$viewer_id."') OR (r_user_id='".$_COOKIE['uid']."' AND user_id='".$viewer_id."'))";
										$uj_result=mysqli_query($conn,$uj_query);
										if(mysqli_num_rows($uj_result))
										{
											$uj_row=mysqli_fetch_array($uj_result);
											if($uj_row['status']==1)
											{
												$text="Disconnect";
												$connect_user_click_data="ConnectUser('".$connect_user_id."','disconnect');";
											}
											else if($uj_row['status']==4)
											{
												if($uj_row['r_user_id']==$connect_user_id)
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
											<button type="button" onclick="<?php echo $connect_user_click_data; ?>" class="btn btn-primary btn-sm btn-block"> <?php echo $text; ?> </button>
										<?php
									}
								?>
							</span>
						</div>
						<?php
					}
				?>
			</div>
		</div>
<?php
	}
?>