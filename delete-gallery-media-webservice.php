<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$user_id=$_COOKIE['uid'];
		$media_id=$_REQUEST['media_id'];
		$query="UPDATE gallery SET delete_status=1 WHERE id='$media_id' AND delete_status=0";
		if(mysqli_query($conn,$query))
		{
			$response['status']="success";
		}
		else
		{
			$response['status']="error";
			$response['message']="Media does not exists.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Session Logged out. Please Login.";
	}
	echo json_encode($response);
?>