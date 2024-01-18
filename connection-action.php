<?php
	include_once 'connection.php';
	$method=$_POST['method'];
	$user_id=$_COOKIE['uid'];
	$r_user_id=$_POST['connection_user_id'];
	$response=array();
	if($method=="connect")
	{
		$response['class_param']="connect";
		$response['function_param']="connect";
		$query="SELECT * FROM user_joins_user WHERE (user_id='$user_id' AND r_user_id='$r_user_id') OR (r_user_id='$user_id' AND user_id='$r_user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			if($row['r_user_id']==$user_id && $row['status']!="1")
			{
				$update_query="UPDATE user_joins_user SET status=4 WHERE id='".$row['id']."'";
				if(mysqli_query($conn,$update_query))
				{
					$response['status']="success";
					$response['message']="Connect request has been sent.";
					$response['function_param']="cancel";
					
				}
				else
				{
					$response['status']="error";
					$response['message']="Connect request can not be sent.";
				}
			}
			else if($row['user_id']==$user_id && $row['status']!="1")
			{
				$update_query="UPDATE user_joins_user SET status=1 WHERE id='".$row['id']."'";
				if(mysqli_query($conn,$update_query))
				{
					$response['status']="success";
					$response['message']="you are now connected.";
				}
				else
				{
					$response['function_param']="disconnect";
					$response['status']="error";
					$response['message']="Connect request can not be proceed.";
				}
			}
			else
			{
				$response['status']="success";
				$response['message']="you are already connected.";
			}
		}
		else
		{
			$insert_query="INSERT INTO user_joins_user SET r_user_id='$user_id',user_id='$r_user_id',status=4,added=NOW()";
			if(mysqli_query($conn,$insert_query))
			{
				$response['function_param']="cancel";
				$response['status']="success";
				$response['message']="Connect request has been sent.";
			}
			else
			{
				$response['status']="error";
				$response['message']="Connect request can not be sent.";
			}
		}
	}
	else if($method=="disconnect")
	{
		$response['class_param']="connect";
		$response['function_param']="disconnect";
		$query="SELECT * FROM user_joins_user WHERE (user_id='$user_id' AND r_user_id='$r_user_id') OR (r_user_id='$user_id' AND user_id='$r_user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$delete_query="DELETE FROM user_joins_user WHERE id='".$row['id']."'";
			if(mysqli_query($conn,$delete_query))
			{
				$response['function_param']="connect";
				mysqli_query($conn,"INSERT INTO users_chat SET user_id='$user_id',r_user_id='$r_user_id',added=NOW(),text_message='**RUDISCONNECTED**'");
				$response['status']="success";
				$response['message']="you are no longer connected.";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in disconnecting with.";
			}
		}
		else
		{
			$response['function_param']="connect";
			$response['status']="success";
			$response['message']="you are already disconnected.";
		}
	}
	else if($method=="accept")
	{
		$response['class_param']="connect";
		$response['function_param']="accept";
		$query="SELECT * FROM user_joins_user WHERE (user_id='$user_id' AND r_user_id='$r_user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$update_query="UPDATE user_joins_user SET status=1 WHERE id='".$row['id']."' AND (user_id='$user_id' AND r_user_id='$r_user_id')";
			if(mysqli_query($conn,$update_query))
			{
				$_query="SELECT * FROM users_personal WHERE id='$user_id'";
				$_result=mysqli_query($conn,$_query);
				$_row=mysqli_fetch_array($_result);
				$_user_row=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'"));
				$_user_email=$_row['communication_email'];
				$location=$_row['address'];
				$_user_mobile=$_row['phonecode'].$_row['communication_mobile'];
				$contact_name=ucwords(strtolower($_user_row['first_name'].' '.$_user_row['last_name']));
				if($_user_email!="")
				{
					$contact_type="email";
					mysqli_query($conn,"INSERT INTO users_contact SET user_id='$r_user_id',contact_name='$contact_name',contact_type='$contact_type',contact='$_user_email',location='$location'");
				}
				if($_user_mobile!="")
				{
					$contact_type="mobile";
					mysqli_query($conn,"INSERT INTO users_contact SET user_id='$r_user_id',contact_name='$contact_name',contact_type='$contact_type',contact='$_user_mobile',location='$location'");
				}
				$_query_1="SELECT * FROM users_personal WHERE id='$r_user_id'";
				$_result_1=mysqli_query($conn,$_query_1);
				$_row_1=mysqli_fetch_array($_result_1);
				$_user_row_1=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE id='$r_user_id'"));
				$_user_email=$_row_1['communication_email'];
				$location=$_row_1['address'];
				$_user_mobile=$_row_1['phonecode'].$_row_1['communication_mobile'];
				$contact_name=ucwords(strtolower($_user_row_1['first_name'].' '.$_user_row_1['last_name']));
				if($_user_email!="")
				{
					$contact_type="email";
					mysqli_query($conn,"INSERT INTO users_contact SET user_id='$user_id',contact_name='$contact_name',contact_type='$contact_type',contact='$_user_email',location='$location'");
				}
				if($_user_mobile!="")
				{
					$contact_type="mobile";
					mysqli_query($conn,"INSERT INTO users_contact SET user_id='$user_id',contact_name='$contact_name',contact_type='$contact_type',contact='$_user_mobile',location='$location'");
				}
				
				mysqli_query($conn,"INSERT INTO users_chat SET user_id='$user_id',r_user_id='$r_user_id',added=NOW(),text_message='**RUCONNECTED**'");
				$response['status']="success";
				$response['message']="you are connected now.";
				$response['function_param']="disconnect";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in connecting with.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="you are not allowed to perform this action.";
		}
	}
	else if($method=="reject")
	{
		$response['class_param']="connect";
		$response['function_param']="reject";
		$query="SELECT * FROM user_joins_user WHERE (r_user_id='$user_id' AND user_id='$r_user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$update_query="DELETE FROM user_joins_user WHERE id='".$row['id']."' AND (r_user_id='$user_id' AND user_id='$r_user_id')";
			if(mysqli_query($conn,$update_query))
			{
				$response['status']="success";
				$response['message']="request has been rejected.";
				$response['function_param']="connect";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in rejecting with.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="you are not allowed to perform this action.";
		}
	}
	else if($method=="cancel")
	{
		$response['class_param']="connect";
		$response['function_param']="cancel";
		$query="SELECT * FROM user_joins_user WHERE (r_user_id='$user_id' AND user_id='$r_user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$update_query="DELETE FROM user_joins_user WHERE id='".$row['id']."' AND (r_user_id='$user_id' AND user_id='$r_user_id')";
			if(mysqli_query($conn,$update_query))
			{
				$response['function_param']="connect";
				$response['status']="success";
				$response['message']="request has been cancelled.";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in cancelling with.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="you are not allowed to perform this action.";
		}
	}
	else if($method=="follow")
	{
		$response['class_param']="follow";
		$response['function_param']="follow";
		$query="SELECT * FROM followers WHERE (user_id='$r_user_id' AND follower_id='$user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$update_query="UPDATE followers SET status=1 WHERE id='".$row['id']."' AND (user_id='$r_user_id' AND follower_id='$user_id')";
			if(mysqli_query($conn,$update_query))
			{
				$response['function_param']="unfollow";
				$response['status']="success";
				$response['message']="Following successfully.";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in following.";
			}
		}
		else
		{
			$update_query="INSERT INTO followers SET status=1,user_id='$r_user_id',follower_id='$user_id',added=NOW()";
			if(mysqli_query($conn,$update_query))
			{
				$response['function_param']="unfollow";
				$response['status']="success";
				$response['message']="Following successfully.";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in following.";
			}
		}
	}
	else if($method=="unfollow")
	{
		$response['class_param']="follow";
		$response['function_param']="unfollow";
		$query="SELECT * FROM followers WHERE (user_id='$r_user_id' AND follower_id='$user_id')";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			$update_query="UPDATE followers SET status=0 WHERE id='".$row['id']."' AND (user_id='$r_user_id' AND follower_id='$user_id')";
			if(mysqli_query($conn,$update_query))
			{
				$response['function_param']="follow";
				$response['status']="success";
				$response['message']="User has been unfollowed.";
			}
			else
			{
				$response['status']="error";
				$response['message']="there is a problem in unfollowing.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="You are not following this user.";
		}
	}
	echo json_encode($response);
?>