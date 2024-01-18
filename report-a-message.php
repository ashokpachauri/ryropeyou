<?php
	include_once 'connection.php';
	$response=array();
	$issue_type=$_POST['issue_type'];
	$message_id=$_POST['message_id'];
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$query="INSERT INTO message_reports SET user_id='".$_COOKIE['uid']."',message_id='$message_id',issue_type='$issue_type',added=NOW()";
		$result=mysqli_query($conn,$query);
		if($result)
		{
			mysqli_query($conn,"UPDATE users_chat SET r_status=2 WHERE id='$message_id'");
			$response['status']="success";
		}
		else
		{
			$response['status']="error";
		}
	}
	else
	{
		$response['status']="error";
	}
	echo json_encode($response);
?>