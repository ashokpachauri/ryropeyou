<?php
	$response=array();
	include_once 'connection.php';
	$media_id=$_POST['media_id'];
	$user_id=$_POST['user_id'];
	$text_content=htmlentities($_POST['text_content']);
	$in_appropriate=1;
	if(isUploadableText($text_content))
	{
		$in_appropriate=0;
	}
	$comment_id=$_POST['comment_id'];
	if($comment_id!="" && $comment_id!=null)
	{
		$query="UPDATE media_comments SET in_appropriate='$in_appropriate',media_id='$media_id',date=NOW(),user_id='$user_id',text_content='$text_content' WHERE id='$comment_id'";
		if(mysqli_query($conn,$query))
		{
			$response['status']="success";
			$response['id']=$comment_id;
			$response['user_id']=$user_id;
			$response['media_id']=$media_id;
		}
		else{
			$response['status']="error";
		}
	}
	else
	{
		$query="INSERT INTO media_comments SET in_appropriate='$in_appropriate',media_id='$media_id',date=NOW(),user_id='$user_id',text_content='$text_content'";
		if(mysqli_query($conn,$query))
		{
			$query="SELECT user_id FROM gallery WHERE id='$media_id' AND is_draft=0";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$user_id_to_notify=$row['user_id'];
				if($user_id_to_notify!=$_COOKIE['uid'])
				{
					$author_data=getUsersData($user_id_to_notify);
					$redirect_url_segment="gallery.php?mthread=".md5($media_id)."&luid=".md5($_COOKIE['uid']);
				
					$user_data=getUsersData($_COOKIE['uid']);
					$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has commented on your media.";
					mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$user_id_to_notify."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");
				}
				
				$query="SELECT DISTINCT(user_id) AS notify_user FROM media_comments WHERE media_id='$media_id' AND status=1";
				$result=mysqli_query($conn,$query);
				if(mysqli_num_rows($result)>0)
				{
					while($row=mysqli_fetch_array($result))
					{
						if($user_id_to_notify!=$row['notify_user'])
						{
							$notify_user_data=getUsersData($user_id_to_notify);
							
							$notify_user=$row['notify_user'];
							$author_data=getUsersData($notify_user);
							$redirect_url_segment="gallery.php?mthread=".md5($media_id)."&luid=".md5($_COOKIE['uid']);
				
							$user_data=getUsersData($_COOKIE['uid']);
							$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has also commented on ".ucwords(strtolower($notify_user_data['first_name']." ".$notify_user_data['last_name']))."'s media.";
							mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$notify_user."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");
				
						}
					}
				}
				
				
			}
			$response['status']="success";
			$response['id']=mysqli_insert_id($conn);
			$response['user_id']=$user_id;
			$response['media_id']=$media_id;
		}
		else{
			$response['status']="error";
		}
	}
	echo json_encode($response);
?>