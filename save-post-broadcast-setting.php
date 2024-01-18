<?php
	include_once 'connection.php';
	$random_number=$_POST['random_number'];
	//post_id//blog_post_id//blog_space_id depends upon type parameter
	$response=array();
	$type=$_POST['type'];
	$settings=$_POST['settings'];
	$users_allowed=$_POST['users_allowed'];
	$users_blocked=$_POST['users_blocked'];
	
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="" && $settings!="")
	{
		$settings_arr=explode(",",$settings);
		$is_public=$settings_arr[0];
		$is_private=$settings_arr[1];
		$is_protected=$settings_arr[2];
		$is_friendly_protected=$settings_arr[3];
		$is_magic=$settings_arr[4];
		
		$user_id=$_COOKIE['uid'];
		if($type=="" || $type=="post")
		{
			$query="SELECT * FROM users_posts WHERE user_id='$user_id' AND id='$random_number'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$update_query="UPDATE users_posts SET users_blocked='$users_blocked',users_allowed='$users_allowed',is_magic='$is_magic',is_public='$is_public',is_private='$is_private',is_protected='$is_protected',is_friendly_protected='$is_friendly_protected' WHERE id='$random_number'";
				if(mysqli_query($conn,$update_query))
				{
					$response['status']="success";
				}
				else
				{
					$response['status']="error";
					$response['message']="Some Technical Error.Please Retry.";
				}
			}
			else
			{
				$response['status']="error";
				$response['message']="You are not authorised.";
			}
		}
		else if($type=="blog_space")
		{
			$query="SELECT * FROM blog_spaces WHERE user_id='$user_id' AND id='$random_number'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$update_query="UPDATE blog_spaces SET visibility='$settings',users_blocked='$users_blocked',users_allowed='$users_allowed',is_magic='$is_magic',is_public='$is_public',is_private='$is_private',is_protected='$is_protected',is_friendly_protected='$is_friendly_protected' WHERE id='$random_number'";
				if(mysqli_query($conn,$update_query))
				{
					$response['status']="success";
				}
				else
				{
					$response['status']="error";
					$response['message']="Some Technical Error.Please Retry.";
				}
			}
			else
			{
				$response['status']="error";
				$response['message']="You are not authorised.";
			}
		}
		else if($type=="blog_space_post")
		{
			$query="SELECT * FROM blog_space_posts WHERE user_id='$user_id' AND id='$random_number'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$update_query="UPDATE blog_space_posts SET visibility='$settings',users_blocked='$users_blocked',users_allowed='$users_allowed',is_magic='$is_magic',is_public='$is_public',is_private='$is_private',is_protected='$is_protected',is_friendly_protected='$is_friendly_protected' WHERE id='$random_number'";
				if(mysqli_query($conn,$update_query))
				{
					$response['status']="success";
				}
				else
				{
					$response['status']="error";
					$response['message']="Some Technical Error.Please Retry.";
				}
			}
			else
			{
				$response['status']="error";
				$response['message']="You are not authorised.";
			}
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Session Out. Please Login.";
	}
	echo json_encode($response);
?>