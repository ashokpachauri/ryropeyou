<?php
	include_once "connection.php";
	$blog_space_post_id=$_POST['blog_space_post_id'];
	$response=array();
	if(isLogin() && isDeserving($_COOKIE['uid'],$blog_space_post_id))
	{
		$query="UPDATE blog_space_posts SET visibility=0 WHERE id='$blog_space_post_id' AND user_id='".$_COOKIE['uid']."'";
		if(mysqli_query($conn,$query))
		{
			$response['status']="success";
		}
		else
		{
			$response['status']="error";
			$response['message']="You are not authorised for this action";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="You are not authorised for this action";
	}
	echo json_encode($response);
?>