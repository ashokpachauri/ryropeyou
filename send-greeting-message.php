<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$user_to_wish=$_REQUEST['user_to_wish'];
		$greeting_message=trim($_REQUEST['greeting_message']);
		$wish_type=$_REQUEST['wish_type'];
		
		if($greeting_message!="" && $user_to_wish!="")
		{
			if($wish_type=="")
			{
				$wish_type="birthday";
			}
			$user_id=$_COOKIE['uid'];
			$user_data=getUsersData($user_id);
			$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has send you ".$wish_type." wishes.";
			$redirect_url_segment="messenger.php?uthread=".md5($user_id)."&wish=birthday&thread=".md5($user_id);
			mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='$user_to_wish',heading='$heading',message='$heading',added=NOW(),seen=0,redirect_url_segment='$redirect_url_segment'");
			
			mysqli_query($conn,"INSERT INTO users_chat SET user_id='$user_id',r_user_id='$user_to_wish',text_message='$greeting_message',added=NOW()");
			$response['status']="success";
		}
		else
		{
			$response['status']="error";
			$response['message']="Message can not be blank.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Session Out Please login again.";
	}
	echo json_encode($response);
?>