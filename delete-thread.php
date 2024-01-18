<?php
	include_once 'connection.php';
	$thread_id=$_POST['thread_id'];
	$query="SELECT * FROM users_chat WHERE id='$thread_id' AND ((r_status=1 AND r_user_id='".$_COOKIE['uid']."') OR (s_status=1 AND user_id='".$_COOKIE['uid']."'))";
	$result=mysqli_query($conn,$query);
	$response=array();
	if(mysqli_num_rows($result)>0)
	{
		if(isLogin())
		{
			$row=mysqli_fetch_array($result);
			if($row['r_user_id']==$_COOKIE['uid'])
			{
				if(mysqli_query($conn,"UPDATE users_chat SET r_status=2 WHERE id='$thread_id'"))
				{
					$response['status']="success";
				}
				else{
					$response['status']="error";
					$response['reason']="Network error";
				}
			}
			else
			{
				if(mysqli_query($conn,"UPDATE users_chat SET s_status=2 WHERE id='$thread_id'"))
				{
					$response['status']="success";
				}
				else{
					$response['status']="error";
					$response['reason']="Network error";
				}
			}
		}
		else
		{
			$response['status']="error";
			$response['reason']="User not loggedin";
		}
	}
	else
	{
		$response['status']="error";
		$response['reason']="thread not found";
	}
	echo json_encode($response);
?>