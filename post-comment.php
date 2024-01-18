<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$post_id=$_POST['post_id'];
	$arr=array();
	$comment_text=filter_var(addslashes($_POST['comment_text']),FILTER_SANITIZE_STRING);
	if($comment_text=="")
	{
		$arr['status']="error";
		$arr['message']="Blank comment can not be posted.";
	}
	else
	{
		$threat_message="";
		$post_user_id="";
		$post_query="SELECT * FROM users_posts WHERE id='$post_id'";
		$post_result=mysqli_query($conn,$post_query);
		if(mysqli_num_rows($post_result)>0)
		{
			$post_row=mysqli_fetch_array($post_result);
			$post_user_id=$post_row['user_id'];
		}
		$query="INSERT INTO users_posts_comments SET user_id='$user_id',post_id='$post_id',comment_text='$comment_text'";
		if(mysqli_query($conn,$query))
		{
			$comment_id=mysqli_insert_id($conn);
			$arr['status']="success";
			$arr['id']=$comment_id;
			$arr['comment_text']=$comment_text;
			$redirect_url_segment="broadcasts.php?pthread=".md5($post_id)."&cthread=".md5($comment_id);
			if($post_user_id!=$user_id)
			{
				$owner_notified=false;
				$new_query="SELECT DISTINCT(user_id) as uid FROM users_posts_comments WHERE post_id='$post_id'";
				$new_result=mysqli_query($conn,$new_query);
				if(mysqli_num_rows($new_result)>0)
				{
					$user_data=getUsersData($user_id);
					$post_user_data=getUsersData($post_user_id);
					while($new_row=mysqli_fetch_array($new_result))
					{
						$c_user_id=$new_row['uid'];
						if($c_user_id==$post_user_id)
						{
							$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has commented on your post.";
							$owner_notified=true;
							mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$c_user_id."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");
						}
						else if($user_id!=$c_user_id)
						{
							$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." and others has commented on a post of ".ucwords(strtolower($post_user_data['first_name']." ".$post_user_data['last_name']));
							mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$c_user_id."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");
						}
						if($owner_notified===false)
						{
								$arr['status']="success";
								$user_data=getUsersData($user_id);
								$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has commented on your post.";
								mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$post_user_id."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");		
							
						}
					}
				}
				else
				{
					$arr['status']="success";
					$user_data=getUsersData($user_id);
					$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has commented on your post.";
					mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$post_user_id."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");		
				}
			}
			else
			{
				$new_query="SELECT DISTINCT(user_id) as uid FROM users_posts_comments WHERE post_id='$post_id'";
				$new_result=mysqli_query($conn,$new_query);
				if(mysqli_num_rows($new_result)>0)
				{
					$user_data=getUsersData($user_id);
					$post_user_data=getUsersData($post_user_id);
					while($new_row=mysqli_fetch_array($new_result))
					{
						$c_user_id=$new_row['uid'];
						if($c_user_id==$post_user_id)
						{
							continue;
						}
						else if($c_user_id!=$user_id)
						{
							$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." and others has commented on a post of ".ucwords(strtolower($post_user_data['first_name']." ".$post_user_data['last_name']));
							mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$c_user_id."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment'");
						}
					}
				}
			}
		}
		else
		{
			$arr['status']="error";
			$arr['message']="We are fixing it.please try after a moment.";
			$arr['debug']=$query;
		}
	}
	echo json_encode($arr);
?>