<?php
	require_once 'hybrid-auth/vendor/autoload.php';
	include_once 'connection.php';
	
	
	$config = [
		'callback' => 'https://ropeyou.com/rope/twitter-login.php',
		'keys'     => [
						'key' => 'CwtxHzvE1BsBmW0QldCZeWihr',
						'secret' => '8n8gUQk7rooqmJAGXgsicLaedEm0bFxKukyDP4x2IYPTV9vUCc'
					],
		'authorize' => true
	];
	 
	$adapter = new Hybridauth\Provider\Twitter( $config );
	
	try {
		if ($adapter->isConnected()) {
			$adapter->disconnect();
			//echo 'Logged out the user';
			//echo '<p><a href="index.php">Login</a></p>';
		}
		
		$adapter->authenticate();
		$user_profile=array();
		$userProfile = $adapter->getUserProfile();
		//print_r($userProfile);die();
		$user_profile['first_name']=$userProfile->firstName;
		$user_profile['last_name']=$userProfile->lastName;
		$user_profile['identifier']=$userProfile->identifier;
		$user_profile['photoURL']=$userProfile->photoURL;
		$user_profile['phone']=$userProfile->phone;
		$user_profile['email']=($userProfile->email)?$userProfile->email:(($userProfile->emailVerified)?$userProfile->emailVerified:null);
		//$user_profile=json_encode($user_profile);
		if(isset($userProfile->identifier) && $userProfile->identifier!="" && $userProfile->identifier!=null)
		{
			$query="SELECT * FROM users WHERE linkedin_id='".$user_profile['identifier']."' AND status!=3";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				if($row['status']=="2")
				{
					mysqli_query($conn,"UPDATE users SET status=1 WHERE id='".$row['id']."'");
				}
				mysqli_query($conn,"UPDATE users SET validated=1 WHERE id='".$row['id']."'");
				if($row['email']=="")
				{
					mysqli_query($conn,"UPDATE users SET email='".$user_profile['email']."' WHERE id='".$row['id']."'");
				}
				if($row['mobile']=="")
				{
					mysqli_query($conn,"UPDATE users SET mobile='".$user_profile['phone']."' WHERE id='".$row['id']."'");
				}
				$cookie_name="uid";
				$cookie_value=$row['id'];
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
						location.href="<?php echo base_url; ?>onboarding";
					</script>
					<?php
					die();
				}
			}
			else
			{
				$username=generateUniqueUserName($user_profile['email']);
				$query="INSERT INTO users SET username='$username',added=NOW(),role='RY_USER',status=1,validated=1,email='".$user_profile['email']."',mobile='".$user_profile['phone']."',linkedin_id='".$user_profile['identifier']."',first_name='".$user_profile['first_name']."',last_name='".$user_profile['last_name']."'";
				$check_query="SELECT * FROM users WHERE (email='".$user_profile['email']."' OR mobile='".$user_profile['phone']."') AND status!=3";
				$check_result=mysqli_query($conn,$check_query);
				$user_id="";
				if(mysqli_num_rows($check_result)>0)
				{
					$row=mysqli_fetch_array($check_result);
					$user_id=$row['id'];
					$username=$row['username'];
					$query="UPDATE users SET validated=1,linkedin_id='".$user_profile['identifier']."' WHERE id='".$row['id']."'";
				}
				$result=mysqli_query($conn,$query);
				if($result)
				{
					if($user_id=="")
					{
						$user_id=mysqli_insert_id($conn);
					}
					$cookie_name="uid";
					$cookie_value=$user_id;
					setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/","");		
					setcookie("blogger_id",$cookie_value,time()+(30*24*60*60),"/","");
					setcookie("username",$username,time()+(30*24*60*60),"/","");
					if(isset($_SESSION['last_url']) && $_SESSION['last_url']!="")
					{
						$last_url=urldecode($_SESSION['last_url']);
						$_SESSION['last_url']="";
						?>
						<script>
							var user_id="<?php echo $user_id; ?>";
							var user_role="RY_USER";
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
							var user_id="<?php echo $user_id; ?>";
							var user_role="RY_USER";
							localStorage.setItem("user_id",user_id);
							localStorage.setItem("user_role",user_role);
							location.href="<?php echo base_url; ?>onboarding";
						</script>
						<?php
						die();
					}
				}
			}
		}
		else
		{
			?>
			<script>
				alert('Authentication Failed. Redirecting to home.');
				location.href="<?php echo base_url; ?>";
			</script>
			<?php
		}
	}
	catch( Exception $e ){
		echo $e->getMessage() ;
	}
?>