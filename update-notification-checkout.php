<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		mysqli_query($conn,"UPDATE threats_to_user SET checkout=1 WHERE user_id='".$_COOKIE['uid']."'");
		$response['status']="success";
	}
	else
	{
		$response['status']="error";
		$response['message']="Session Out.Please Login.";
	}
	echo json_encode($response);
?>