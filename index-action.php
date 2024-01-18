<?php
	include_once 'connection.php';
	if(isset($_REQUEST['back']) && $_REQUEST['back']=="home")
	{
		unset($_SESSION);
		session_destroy();
		?>
		<script>
			location.href="<?php echo base_url; ?>";
		</script>
		<?php
		die();
	}
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		if(isset($_SESSION['last_url']) && $_SESSION['last_url']!="")
		{
			$last_url=urldecode($_SESSION['last_url']);
			$_SESSION['last_url']="";
			?>
			<script>
				location.href="<?php echo $last_url; ?>";
			</script>
			<?php
			die();
		}
		else
		{
			?>
			<script>
				location.href="<?php echo base_url; ?>onboarding";
			</script>
			<?php
			die();
		}
	}
	if(isset($_REQUEST['register']))
	{
		$first_name=strip_tags(mysqli_real_escape_string($conn,$_REQUEST['first_name']));
		$last_name=strip_tags(mysqli_real_escape_string($conn,$_REQUEST['last_name']));
		$country_code=strip_tags(mysqli_real_escape_string($conn,$_REQUEST['country_code']));
		if($country_code=="")
		{
			$_SESSION['ccode']="Please select a country you belongs to.";
		}
		$mobile_email=strip_tags($_REQUEST['mobile_email']);
		$password=$_REQUEST['password'];
		$md5Pass=md5($password);
		$random=mt_rand(1000,9999);
		$error=false;
		if($first_name=="")
		{
			$_SESSION['first_name']="First name can't be blank.";
			$error=true;
		}
		if($last_name=="")
		{
			$_SESSION['last_name']="Last name can't be blank.";
			$error=true;
		}
		if($mobile_email=="")
		{
			$_SESSION['mobile_email']="Mobile or Email can't be blank.";
			$error=true;
		}
		if($password=="")
		{
			$_SESSION['password']="Password can't be blank.";
			$error=true;
		}
		if(strlen($password)<6)
		{
			$_SESSION['password']="Password must be atleast 6 character's long.";
			$error=true;
		}
		if(!$error)
		{
			$role="RY_USER";
			$check_query="SELECT * FROM users WHERE (mobile='$mobile_email' OR email='$mobile_email') AND status!=3";
			$check_result=mysqli_query($conn,$check_query);
			if(mysqli_num_rows($check_result)>0)
			{
				$check_again="SELECT * FROM users WHERE (mobile='$mobile_email' OR email='$mobile_email') AND password='$md5Pass'";
				$check_again_result=mysqli_query($conn,$check_again);
				if(mysqli_num_rows($check_again_result)>0)
				{
	
					session_destroy();
					session_start();
					$check_again_row=mysqli_fetch_array($check_again_result);
					if($check_again_row['validated']=="1")
					{
						$user_id_exists=$check_again_row['id'];
						$_SESSION['uid']=$user_id_exists;
						setcookie("uid",$user_id_exists,time()+(30*24*60*60),"/","");
						setcookie("blogger_id",$user_id_exists,time()+(30*24*60*60),"/","");
						$_SESSION['u_name']=$check_again_row['first_name'];
						
						
						$_SESSION['mesg_type']="success";
						$_SESSION['mesg']="";
						?>
						<script>
							location.href='<?php echo base_url."onboarding"; ?>';
						</script>
						<?php
						die();
					}
					else
					{
						$user_id_exists=$check_again_row['id'];
						mysqli_query($conn,"UPDATE users SET code='$random',country_code='$country_code' WHERE id='$user_id_exists'");
						if(ctype_digit($mobile_email))
						{
							if(sendOTP($mobile_email,$random,$country_code,'mobile'))
							{
								session_destroy();
								session_start();
								$_SESSION['atv']='mobile';
								$_SESSION['page']='null';
								$_SESSION['is_page']='0';
								$_SESSION['mobile']=trim($mobile_email);
								$_SESSION['ccode']=trim($country_code);
								?>
								<script>
									location.href='<?php echo base_url."verify-otp"; ?>';
								</script>
								<?php
								die();
							}
							else
							{
								session_destroy();
								session_start();
								$_SESSION['u_name']='user';
								$_SESSION['mesg_type']="error";
								$_SESSION['mesg']="Invalid Phone Number - Check Number Format";
							}
						}
						else
						{
							if(sendOTP($mobile_email,$random,$country_code,'email'))
							{
								session_destroy();
								session_start();
								$_SESSION['atv']='email';
								$_SESSION['mobile']=trim($mobile_email);
								$_SESSION['ccode']=trim($country_code);
								$_SESSION['page']='null';
								$_SESSION['is_page']='0';
								?>
								<script>
									location.href='<?php echo base_url."verify-otp"; ?>';
								</script>
								<?php
								die();
							}
							else
							{
								session_destroy();
								session_start();
								$_SESSION['u_name']='user';
								$_SESSION['mesg_type']="error";
								$_SESSION['mesg']="OTP generation failed.";
							}
						}
						
					}
				}
				else
				{
					session_destroy();
					session_start();
					$_SESSION['u_name']='user';
					$_SESSION['mesg_type']="error";
					$_SESSION['mesg']="An account has been already created with this mobile or email.";
				}
			}
			else
			{
				if(ctype_digit($mobile_email))
				{
					$query="INSERT INTO users SET country_code='$country_code',first_name='$first_name',last_name='$last_name',mobile='$mobile_email',password='$md5Pass',added=NOW(),role='$role',code='$random'";
					if(mysqli_query($conn,$query))
					{
						session_destroy();
						session_start();
						$uid=mysqli_insert_id($conn);
						
						$_SESSION['uid']=$uid;
						$username=generateUniqueUserName($mobile_email);
						mysqli_query($conn,"UPDATE users SET username='$username' WHERE id='$uid'");
						
						$_SESSION['u_name']=$first_name;
						$_SESSION['mesg_type']="success";
						$_SESSION['mesg']="We have sent an OTP on your registered mobile.Please verify your account in next step.";
						mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$uid."',added=NOW(),message='New account created.Update your profile to increase your visibility.',heading='New account created.',redirect_url_segment='dashboard'");
						$contact_type="mobile";
						mysqli_query($conn,"INSERT INTO users_contact SET user_id='".$uid."',contact_name='Self',contact_type='$contact_type',contact='$mobile_email'");
						//mysqli_query($conn,"INSERT INTO users_personal SET user_id='".$_SESSION['uid']."'");
						if(sendOTP($mobile_email,$random,$country_code,'mobile'))
						{
							session_destroy();
							session_start();
							$_SESSION['atv']='mobile';
							$_SESSION['mobile']=trim($mobile_email);
							$_SESSION['ccode']=trim($country_code);
							$_SESSION['page']='null';
							$_SESSION['is_page']='0';
							?>
							<script>
								location.href='<?php echo base_url."verify-otp"; ?>';
							</script>
							<?php
							die();
						}
						else
						{
							mysqli_query($conn,"DELETE FROM users WHERE id='$uid'");
							mysqli_query($conn,"DELETE FROM threats_to_user WHERE user_id='$uid'");
							mysqli_query($conn,"DELETE FROM users_contact WHERE user_id='$uid'");
							mysqli_query($conn,"DELETE FROM users_personal WHERE user_id='$uid'");
							session_destroy();
							session_start();
							$_SESSION['u_name']='user';
							$_SESSION['mesg_type']="error";
							$_SESSION['mesg']="Invalid Phone Number - Check Number Format";
						}
					}
					else
					{
						session_destroy();
						session_start();
						$_SESSION['u_name']=$first_name;
						$_SESSION['mesg_type']="error";
						$_SESSION['mesg']="We are sorry that you are facing this.We are working on this.Please try back after a moment.";
					}
				}
				else
				{
					if(filter_var($mobile_email, FILTER_VALIDATE_EMAIL))
					{
						$username=generateUniqueUserName($mobile_email);
						$query="INSERT INTO users SET first_name='$first_name',last_name='$last_name',email='$mobile_email',password='$md5Pass',added=NOW(),role='$role',code='$random'";
						if(mysqli_query($conn,$query))
						{
							session_destroy();
							session_start();
							$uid=mysqli_insert_id($conn);
							
							$_SESSION['uid']=$uid;
							mysqli_query($conn,"UPDATE users SET username='$username' WHERE id='$uid'");
							$_SESSION['u_name']=$first_name;
							$_SESSION['mesg_type']="success";
							$_SESSION['mesg']="We have sent an OTP on your registered email.Please verify your account in next step or follow the link send in email.";
							mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$uid."',added=NOW(),message='New account created.Update your profile to increase your visibility.',heading='New account created.',redirect_url_segment='dashboard'");
						
							$email_content=email_html;
							$email_content=str_replace("RY-CODE",$random,$email_content);
							$email_content=str_replace("RY-USR",$uid,$email_content);
							$email_content=str_replace("RY-BOOL","0",$email_content);
							$email_content=str_replace("RY-PAGE","null",$email_content);
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							$headers .= 'From: <no-reply@ropeyou.com>' . "\r\n";
							
							mail($mobile_email,"RopeYou Confirmation Email",$email_content,$headers);
							$contact_type="email";
							
							//mysqli_query($conn,"INSERT INTO users_personal SET user_id='".$_SESSION['uid']."'");
							if(mysqli_query($conn,"INSERT INTO users_contact SET user_id='".$uid."',contact_name='Self',contact_type='$contact_type',contact='$mobile_email'"))
							{
								session_destroy();
								session_start();
								$_SESSION['atv']='email';
								$_SESSION['mobile']=trim($mobile_email);
								$_SESSION['ccode']=trim($country_code);
								$_SESSION['page']='null';
								$_SESSION['is_page']='0';
								?>
								<script>
									location.href='<?php echo base_url."verify-otp"; ?>';
								</script>
								<?php
								die();
							}
							else
							{
								mysqli_query($conn,"DELETE FROM threats_to_user WHERE user_id='$uid'");
								session_destroy();
								session_start();
								$_SESSION['u_name']='user';
								$_SESSION['mesg_type']="error";
								$_SESSION['mesg']="OTP generation failed.";
							}
						}
						else
						{
							session_destroy();
							session_start();
							$_SESSION['u_name']=$first_name;
							$_SESSION['mesg_type']="error";
							$_SESSION['mesg']="We are sorry that you are facing this.We are working on this.Please try back after a moment.";
						}
					}
					else
					{
						?>
						<script>
							location.href='<?php echo base_url."invalid-username"; ?>';
						</script>
						<?php
					}
				}
			}
		}
	}
	if(isset($_REQUEST['user_login']))
	{
		$username=$_REQUEST['mobile_email'];
		$password=$_REQUEST['password'];
		$pass=md5($password);
		$query="SELECT * FROM users WHERE (username='$username' OR mobile='$username' OR email='$username' OR id='$username') AND password='$pass'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			if($row['validated']==1 && $row['status']!=3)
			{
				$uid=$row['id'];
				$_SESSION['uid']=$row['id'];			
				$cookie_name="uid";
				$cookie_value=$row['id'];
				if($row['status']==2)
				{
					mysqli_query($conn,"UPDATE users SET status=1 WHERE id='".$row['id']."'");
				}
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/","");				
				$username=$row['username'];	
				setcookie("blogger_id",$cookie_value,time()+(30*24*60*60),"/","");
				setcookie("username",$username,time()+(30*24*60*60),"/","");
				if(isset($_SESSION['last_url']) && $_SESSION['last_url']!="")
				{
					$last_url=urldecode($_SESSION['last_url']);
					$_SESSION['last_url']="";
					?>
					<script>
						var user_id="<?php echo $row['id']; ?>";
						var user_role="<?php echo $row['role']; ?>";
						localStorage.setItem("user_id",user_id);
						localStorage.setItem("user_role",user_role);
						location.href="<?php echo $last_url; ?>";
					</script>
					<?php
					die();
				}
				else
				{
					?>
					<script>
						var user_id="<?php echo $row['id']; ?>";
						var user_role="<?php echo $row['role']; ?>";
						localStorage.setItem("user_id",user_id);
						localStorage.setItem("user_role",user_role);
						location.href="<?php echo base_url; ?>broadcasts";
					</script>
					<?php
					die();
				}
			}
			else if($row['validated']==0 && $row['status']!=3)
			{
				$_SESSION['ccode']=$row['country_code'];
				if($row['mobile']==$username)
				{
					$_SESSION['atv']='mobile';
					$_SESSION['mobile']=$row['mobile'];
					$_SESSION['page']='null';
					$_SESSION['is_page']='0';
				}
				else
				{
					$_SESSION['atv']='email';
					$_SESSION['mobile']=$row['email'];
					$_SESSION['page']='null';
					$_SESSION['is_page']='0';
				}
				//echo $_SESSION['ccode'];die();
				//$_SESSION['uid']=$row['id'];
				?>
				<script>
					location.href="<?php echo base_url; ?>verify-otp";
				</script>
				<?php
				
				die();
			}
			else
			{
				?>
				<script>
					window.location.href="<?php echo base_url; ?>?user=invalid";
				</script>
				<?php
			}
		}
		else
		{
			?>
			<script>
				window.location.href="<?php echo base_url; ?>?user=invalid";
			</script>
			<?php
		}
	}
?>