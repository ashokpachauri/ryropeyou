<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$response=array();
	$profile_type=$_POST['profile_type'];
	$file_name="video_cv_".$user_id.".mp4";
	$to_upload_file_name="uploads/".$file_name;
	$file_size=$_FILES["video"]["size"];
	$downloadable=0;
	$status=1;
	$in_appropriate=0;
	if(move_uploaded_file($_FILES['video']['tmp_name'],$to_upload_file_name))
	{
		$profile_title=filter_var(addslashes($_POST['profile_title']),FILTER_SANITIZE_STRING);
		$resume_headline=filter_var(addslashes($_POST['resume_headline']),FILTER_SANITIZE_STRING);
		$video_tags=filter_var(addslashes($_POST['video_tags']),FILTER_SANITIZE_STRING);
		$profile_type=filter_var(addslashes($_POST['profile_type']),FILTER_SANITIZE_STRING);
		$cover_image=$_POST['cover_image'];
		$visibility=$_POST['visibility'];
		if($profile_title!="" && $resume_headline!="" && $video_tags!="" && $profile_type!="")
		{
			$media_url=base_url.$to_upload_file_name;
		
			$is_uploadable=isUploadable($media_url);
			if(!$is_uploadable)
			{
				$in_appropriate=1;
			}
			$video_insert_query="INSERT INTO videos SET in_appropriate='$in_appropriate',user_id='".$_COOKIE['uid']."',category='$profile_type',title='$profile_title',file='$to_upload_file_name',cover_image='$cover_image',description='$resume_headline',video_tags='$video_tags',status='$status',visibility='$visibility',downloadable='$downloadable',added=NOW()";
			$video_insert_result=mysqli_query($conn,$video_insert_query);
			if($video_insert_result)
			{
				$video_id=mysqli_insert_id($conn);
				$query="INSERT INTO users_resume SET in_appropriate='$in_appropriate',is_default=1,resume_headline='$resume_headline',profile_title='$profile_title',profile_type='$profile_type',file='$video_id',user_id='$user_id',added=NOW()";
				$result=mysqli_query($conn,$query);
				if($result)
				{
					$media_id=mysqli_insert_id($conn);
					mysqli_query($conn,"UPDATE users_resume SET is_default=0 WHERE profile_type!=1 AND user_id='$user_id' AND id!='$media_id'");
				
					$data=base_url.'ajax.videostream.php?action=stream&file_key='.base64_encode($target_file);
					$response['status']="success";
					$response['id']=$video_id;
					$response['profile_title']=$profile_title;
					$response['video_tags']=$video_tags;
					$response['data']=$data;
					
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