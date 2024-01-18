<?php
	include_once 'connection.php';
	$response=array();
	$issue_type=$_POST['issue_type'];
	$video_id=$_POST['video_id'];
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$query="INSERT INTO video_reports SET user_id='".$_COOKIE['uid']."',video_id='$video_id',issue_type='$issue_type',added=NOW()";
		$result=mysqli_query($conn,$query);
		if($result)
		{
			$response['status']="success";
		}
		else{
			$response['status']="error";
		}
	}
	else
	{
		$response['status']="error";
	}
	echo json_encode($response);
?>