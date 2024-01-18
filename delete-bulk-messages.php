<?php
	include_once 'connection.php';
	$response=array();
	if(isLogin())
	{
		$window_user_id=$_POST['window_user_id'];
		$query="UPDATE users_chat SET r_status=2 WHERE r_user_id='".$_COOKIE['uid']."' AND user_id='$window_user_id' AND text_message!='**RUCONNECTED**'";
		$query_1="UPDATE users_chat SET s_status=2 WHERE user_id='".$_COOKIE['uid']."' AND r_user_id='$window_user_id' AND text_message!='**RUCONNECTED**'";
		$result=mysqli_query($conn,$query);
		$result_1=mysqli_query($conn,$query_1);
		$response['status']="success";
		$response['_acqf']=md5(time());
	}
	else
	{
		$response['status']="error";
		$response['message']="You have been logged out.Please Try by Login.";
	}
	echo json_encode($response);
?>