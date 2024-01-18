<?php
	include_once 'connection.php';
	$media_id=$_POST['media_id'];
	$liked=$_POST['liked'];
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$query="SELECT * FROM media_likes WHERE media_id='$media_id' AND user_id='".$_COOKIE['uid']."'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			if(mysqli_query($conn,"DELETE FROM media_likes WHERE media_id='$media_id' AND user_id='".$_COOKIE['uid']."'"))
			{
				$response['status']="success";
				$response['liked']="0";
			}
			else{
				$response['status']="error";
				$response['status']="Something went wrong please try again.";
			}
		}
		else
		{
			if(mysqli_query($conn,"INSERT INTO media_likes SET status=1,media_id='$media_id',user_id='".$_COOKIE['uid']."',reaction='like',date=NOW()"))
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
						$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has liked your media.";
						mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='".$user_id_to_notify."',added=NOW(),message='$heading',heading='$heading',redirect_url_segment='$redirect_url_segment',seen=0");
					}
				}
						
				$response['status']="success";
				$response['liked']="1";
			}
			else{
				$response['status']="error";
				$response['status']="Something went wrong please try again.";
			}
		}
	}
	else{
		$response['status']="error";
		$response['status']="Session Out. Please Login First.";
	}
	echo json_encode($response);
?>