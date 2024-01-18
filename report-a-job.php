<?php
	include_once 'connection.php';
	$response=array();
	$issue_type=$_POST['issue_type'];
	$job_id=$_POST['job_id'];
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$query="INSERT INTO jobs_reports SET user_id='".$_COOKIE['uid']."',job_id='$job_id',issue_type='$issue_type',added=NOW()";
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