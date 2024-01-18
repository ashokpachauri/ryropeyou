<?php
	include_once 'connection.php';
	$comment_id=trim($_POST['comment_id']);
	$response=array();
	if($comment_id=="")
	{
		$response['status']="error";
		$response['message']="Unauthorised access.";
	}
	else
	{
		$query="SELECT * FROM users_videos_comments WHERE user_id='".$_COOKIE['uid']."' AND id='$comment_id'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$response['status']="success";
			$response['comment_text']=$row['comment_text'];
			$response['video_id']=$row['video_id'];
		}
		else
		{
			$response['status']="error";
			$response['message']="Either content has been removed or not available.";
		}
	}
	echo json_encode($response);
?>