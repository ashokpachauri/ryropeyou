<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$response=array();
    $video_module_type=$_POST['video_module_type'];
	$profile_type=5;
	$query="SELECT * FROM video_types WHERE title='$video_module_type' AND status=1";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$profile_type=$row['id'];
	}
	$video_module_id=$_POST['video_module_id'];
	$file_name=str_replace(" ","_",strtolower($video_module_type))."_".md5($video_module_id)."_".$user_id."_".time().".mp4";
	$to_upload_file_name="uploads/".$file_name;
	$file_size=$_FILES["video"]["size"];
	$downloadable=0;
	$status=1;
	if(move_uploaded_file($_FILES['video']['tmp_name'],$to_upload_file_name))
	{
		$user_data=getUsersData($user_id);
        $video_tags="Job Description";
        $cover_image="";
        $profile_title="Job Description";
        $status=1;
        $visibility=2;
		$video_insert_query="INSERT INTO videos SET user_id='".$_COOKIE['uid']."',category='$profile_type',title='$profile_title',file='$to_upload_file_name',cover_image='$cover_image',description='$recommendation_text',video_tags='$video_tags',status='$status',visibility='$visibility',downloadable='$downloadable',added=NOW()";
		if(mysqli_query($conn,$video_insert_query))
		{
			$video_id=mysqli_insert_id($conn);
			$response['status']="success";	
			$response['video_id']=$video_id;	
			$file_key=base_url.'ajax.videostream.php?action=stream&file_key='.base64_encode($to_upload_file_name);
			$file_key_id=base_url.'streaming.php?file_key='.md5($video_id);
									
			$response['file_key_id_url']=$file_key_id;	
			$response['file_key_url']=$file_key;
			$response['file']=$to_upload_file_name;
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
		$response['message']="Problem uploading video.";
	}
	echo json_encode($response);
?>