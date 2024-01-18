<?php
	include_once 'connection.php';
	$response=array();
	$issue_type=$_POST['issue_type'];
	$post_id=$_POST['post_id'];
	$porc=$_POST['porc'];
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		if($porc=="post" || $porc=="")
		{
			$query="INSERT INTO posts_reports SET user_id='".$_COOKIE['uid']."',post_id='$post_id',issue_type='$issue_type',added=NOW()";
			$result=mysqli_query($conn,$query);
			if($result)
			{
				$response['status']="success";
			}
			else{
				$response['status']="error";
			}
		}
		else if($porc=="comment"){
			$query="INSERT INTO comments_reports SET user_id='".$_COOKIE['uid']."',comment_id='$post_id',issue_type='$issue_type',added=NOW()";
			$result=mysqli_query($conn,$query);
			if($result)
			{
				$response['status']="success";
			}
			else{
				$response['status']="error";
			}
		}
	}
	else
	{
		$response['status']="error";
	}
	echo json_encode($response);
?>