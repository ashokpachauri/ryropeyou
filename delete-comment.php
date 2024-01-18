<?php
	include_once 'connection.php';
	$comment_id=$_POST['comment_id'];
	$type=$_POST['type'];
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$uid=$_COOKIE['uid'];
		if($type=="post_comment")
		{
			$query="UPDATE users_posts_comments SET status=2 WHERE id='$comment_id'";
			$result=mysqli_query($conn,$query);
			if($result)
			{
				$response['status']="success";
			}
			else
			{
				$response['status']="error";
				$response['message']="Something went wrong please try again.";
			}
		}
	}
	else{
		$response['status']="error";
		$response['message']="Session out.Please Login.";
	}
	echo json_encode($response);
?>