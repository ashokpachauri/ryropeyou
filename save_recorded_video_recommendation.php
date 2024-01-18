<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$response=array();
    $rec_id=$_POST['rec_id'];
	$recommendation_text=filter_var(addslashes($_POST['recommendation_text']));
	$file_name="video_recommendation_".$user_id."_".time().".mp4";
	$to_upload_file_name="uploads/".$file_name;
	$file_size=$_FILES["video"]["size"];
	$downloadable=0;
	$status=1;
	if(move_uploaded_file($_FILES['video']['tmp_name'],$to_upload_file_name))
	{
		$media_url=base_url.$target_file;
		$in_appropriate=0;
		$is_uploadable=isUploadable($media_url);
		if(!$is_uploadable)
		{
			$in_appropriate=1;
		}
		$user_data=getUsersData($user_id);
        $video_tags="";
        $cover_image="";
        $profile_title="Video Recommendation By ".ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']));
        $profile_type=4;
        $status=1;
        $visibility=2;
		if($profile_title!="" && $profile_type!="")
		{
			$video_insert_query="INSERT INTO videos SET in_appropriate='$in_appropriate',user_id='".$_COOKIE['uid']."',category='$profile_type',title='$profile_title',file='$to_upload_file_name',cover_image='$cover_image',description='$recommendation_text',video_tags='$video_tags',status='$status',visibility='$visibility',downloadable='$downloadable',added=NOW()";
			$video_insert_result=mysqli_query($conn,$video_insert_query);
			if($video_insert_result)
			{
				$video_id=mysqli_insert_id($conn);
				$query="UPDATE recommendations SET r_text='$recommendation_text',rec_file='$video_id',rec_type='Video',status=1 WHERE id='$rec_id'";
				$result=mysqli_query($conn,$query);
				if($result)
				{
				    $response['status']="success";	
				}
				else
				{
					$response['status']="error";
					$response['message']="Server error please contact developer.";
					
				}
			}
			else
			{
				$response['status']="error";
				$response['message']="Server error please contact developer.";
				
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="Please fill all required fields";
		}
	}
	else
	{
		$response['status']="error";
	}
	echo json_encode($response);
?>