<?php
	include_once 'connection.php';
	$response=array();
	$extra="";
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$extra=" AND id='".$_COOKIE['uid']."'";
	}
	if(isset($_POST['username']) && $_POST['username']!="")
	{
		$username=mysqli_real_escape_string($conn,strip_tags(filter_var($_POST['username'],FILTER_SANITIZE_STRING)));
		if($username)
		{
			$query="SELECT * FROM users WHERE (username='$username' OR email='$username' OR mobile='$username')".$extra;
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$response['status']="success";
				$u_name=ucwords(strtolower($row['first_name']." ".$row['last_name']));
				if($row['username']!="")
				{
					$username=substr($row['username'],0,3)."......".substr($row['username'],6);
				}
				else
				{
					$username="";
				}
				if($row['email']!="" && $row['email']!=null)
				{
					$explode_arr=explode("@",$row['email']);
					$email=substr($row['email'],0,3)."......@".$explode_arr[1];
				}
				else
				{
					$email="";
				}
				if($row['mobile']!="" && $row['mobile']!=null)
				{
					$mobile=$row['country_code']." ".substr($row['mobile'],0,2)."......".substr($row['mobile'],8);
				}
				else
				{
					$mobile="";
				}
				
				$response['hash']=md5($row['id']);
				$profile=getUserProfileImage($row['id']);
				$html='<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" id="password_reset_hash" name="password_reset_hash" value="'.$response['hash'].'">
									<div class="card mb-3" style="max-width: 540px;">
										<div class="row no-gutters">
											<div class="col-md-4">
												<img src="'.$profile.'" class="card-img" alt="'.$u_name.'">
											</div>
											<div class="col-md-8">
												<div class="card-body">
													<h5 class="card-title">'.$u_name.'</h5>
													<table class="card-text" style="width:100%;" border="1" cellspacing="2" cellpadding="2">
														';
														if($username!="")
														{
															$html.='<tr style="width:100%;"><td><b>username</b></td><td>'.$username.'</td></tr>';
														}
														if($email!="")
														{
															$html.='<tr style="width:100%;"><td><b>email</b></td><td>'.$email.'</td></tr>';
														}
														if($mobile!="")
														{
															$html.='<tr style="width:100%;"><td><b>mobile</b></td><td>'.$mobile.'</td></tr>';
														}
															
														
													$html.='</table>
												</div>
											</div>
										</div>
									</div>
									<p class="card-text" style="margin-top:30px;">if this is you?. &nbsp;<a href="javascript:void(0);" onclick="sendPasswordResetEmail();" class="btn btn-primary">Send Reset Email</a></p>
								</div>
							</div>
						</div>
					</div>';
				$response['html']=$html;
			}
			else
			{
				$response['status']="error";
				if($extra=="")
				{
					$response['message']="username, mobile or email does not registered with us.";
				}
				else
				{
					$response['message']="This username does not belongs to your account.";
				}
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="Invalid username, mobile or email.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Please provide a username, mobile or email.";
	}
	echo json_encode($response);
?>