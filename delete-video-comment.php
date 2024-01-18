<?php
	include_once "connection.php";
	$comment_id=$_POST['comment_id'];
	$comment_type=$_POST['comment_type'];
	$response=array();
	if(isLogin() && isDeservingPostCommentUser($_COOKIE['uid'],$comment_id,"users_videos_comments"))
	{
		$query="UPDATE users_videos_comments SET status=0 WHERE id='$comment_id' AND user_id='".$_COOKIE['uid']."'";
		if(mysqli_query($conn,$query))
		{
			$response['status']="success";
			$response['c_id']=$comment_id;
			$response['c_type']=$comment_type;
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