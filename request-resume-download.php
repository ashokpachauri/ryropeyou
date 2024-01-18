<?php
	include_once 'connection.php';
	$response=array();
	$request_user_id=$_POST['request_user_id'];
	$user_id=$_COOKIE['uid'];
	$user_data=getUsersData($user_id);
	$request_user_data=getUsersData($request_user_id);
	mysqli_query($conn,"INSERT INTO resume_download_request SET user_id='$request_user_id',r_user_id='$user_id',added=NOW(),status=0");
	$request_id=mysqli_insert_id($conn);
	$redirect_url_segment="allow-resume-download.php?uthread=".md5($user_id)."&rthread=".md5($request_id);
	$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has made a request to download your Text Resume.";
	$query="INSERT INTO threats_to_user SET user_id='$request_user_id',heading='$heading',message='$heading',seen=0,added=NOW(),redirect_url_segment='$redirect_url_segment',flag=0";
	$result=mysqli_query($conn,$query);
	if($result)
	{
		$response['status']="success";
		$response['message']="A Request has been made. we will notify once it responded.";
	}
	else{
		$response['status']="error";
		$response['message']="Something went wrong.Please contact developer.";
	}
	echo json_encode($response);
?>