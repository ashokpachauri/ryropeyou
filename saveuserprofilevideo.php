<?php
	include_once 'connection.php';
	$response=array();
	$downloadable=0;
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$user_id=$_COOKIE['uid'];
		if(isset($_FILES['profile_video_cv']['tmp_name']) && $_FILES['profile_video_cv']['tmp_name']!="" && $_FILES['profile_video_cv']['tmp_name']!=null)
		{
			$user_data=getUsersData($user_id);
			$target_dir="uploads/";
			$image_file_name=$user_data['username'].'-'.mt_rand(0,99999).'-'.str_replace(" ","-",trim(basename($_FILES["profile_video_cv"]["name"])));
			$target_file = $target_dir . $image_file_name;
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$size=$_FILES["profile_video_cv"]["size"];
			if ($size > 50000000) {
				$error_message="File size should not exceeds 50 mb.<br/>";
				$uploadOk = 0;
			}
			$check_array=array("mp4");
			if(!in_array($imageFileType,$check_array))
			{
				$error_message=$error_message."Only mp4 extensions allowed.<br/>";
				$uploadOk = 0;
			}
			if($uploadOk==1)
			{
				if(move_uploaded_file($_FILES["profile_video_cv"]["tmp_name"], $target_file))
				{
					$media_url=base_url.$target_file;
					$in_appropriate=0;
					$is_uploadable=isUploadable($media_url);
					if(!$is_uploadable)
					{
						$in_appropriate=1;
					}
					$img_type=$imageFileType;
					$type="video/".$img_type;
					
					$profile_title=filter_var(addslashes($_POST['profile_title']),FILTER_SANITIZE_STRING);
					$resume_headline=filter_var(addslashes($_POST['resume_headline']),FILTER_SANITIZE_STRING);
					$video_tags=filter_var(addslashes($_POST['video_tags']),FILTER_SANITIZE_STRING);
					$profile_type=filter_var(addslashes($_POST['video_type']),FILTER_SANITIZE_STRING);
					$cover_image=$_POST['cover_image'];
					$visibility=$_POST['visibility'];
					$status=1;
					if($profile_title!="" && $resume_headline!="" && $video_tags!="" && $profile_type!="")
					{
						$video_insert_query="INSERT INTO videos SET in_appropriate='$in_appropriate',user_id='".$_COOKIE['uid']."',category='$profile_type',title='$profile_title',file='$target_file',cover_image='$cover_image',description='$resume_headline',video_tags='$video_tags',status='$status',visibility='$visibility',downloadable='$downloadable',added=NOW()";
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
								echo json_encode($response);die();
							}
							else
							{
								$response['status']="error";
								$response['message']="Server error please contact developer.";
								echo json_encode($response);die();
							}
						}
						else
						{
							$response['status']="error";
							$response['message']="Server error please contact developer.";
							echo json_encode($response);die();
						}
					}
					else
					{
						$response['status']="error";
						$response['message']="Please fill all required fields";
						echo json_encode($response);die();
					}
				}	
				else
				{
					$error_message=$error_message."Error Uploading Image";
				}
			}
			else
			{
				$response['status']="error";
				$response['message']=$error_message;
				echo json_encode($response);die();
			}
		}
		else
		{
			if(isset($_POST['token']) && $_POST['token']!='' && $_POST['video_file_id'] && $_POST['video_file_id']!="")
			{
				$id=$_POST['token'];
				$video_file_id=$_POST['video_file_id'];
				$profile_title=filter_var(addslashes($_POST['profile_title']),FILTER_SANITIZE_STRING);
				$resume_headline=filter_var(addslashes($_POST['resume_headline']),FILTER_SANITIZE_STRING);
				$video_tags=filter_var(addslashes($_POST['video_tags']),FILTER_SANITIZE_STRING);
				$profile_type=filter_var(addslashes($_POST['video_type']),FILTER_SANITIZE_STRING);
				$cover_image=$_POST['cover_image'];
				$visibility=$_POST['visibility'];
				$status=1;
				if($profile_title!="" && $resume_headline!="" && $video_tags!="" && $profile_type!="")
				{
					$video_insert_query="UPDATE videos SET category='$profile_type',title='$profile_title',cover_image='$cover_image',description='$resume_headline',video_tags='$video_tags',visibility='$visibility',downloadable='$downloadable' WHERE id='$video_file_id'";
					$video_insert_result=mysqli_query($conn,$video_insert_query);
					if($video_insert_result)
					{
						$query="UPDATE users_resume SET is_default=1,resume_headline='$resume_headline',profile_title='$profile_title',profile_type='$profile_type' WHERE id='$id'";
						$result=mysqli_query($conn,$query);
						if($result)
						{
							mysqli_query($conn,"UPDATE users_resume SET is_default=0 WHERE profile_type!=1 AND user_id='$user_id' AND id!='$id'");
							$query="SELECT * FROM users_resume WHERE id='$id'";
							$result=mysqli_query($conn,$query);
							$row=mysqli_fetch_array($result);
							
							$query_v="SELECT * FROM videos WHERE id='$video_file_id'";
							$result_v=mysqli_query($conn,$query_v);
							$row_v=mysqli_fetch_array($result_v);
							
							$image_file_name=$row_v['file'];
							$data=base_url.'ajax.videostream.php?action=stream&file_key='.base64_encode($image_file_name);
							$response['status']="success";
							$response['id']=$id;
							$response['data']=$data;
							$response['profile_title']=$profile_title;
							$response['video_tags']=$video_tags;
							echo json_encode($response);die();
						}
						else
						{
							$response['status']="error";
							$response['message']="Server error please contact developer.";
							echo json_encode($response);die();
						}
					}
					else
					{
						$response['status']="error";
						$response['message']="Server error please contact developer.";
						echo json_encode($response);die();
					}
				}
				else
				{
					$response['status']="error";
					$response['message']="Please fill all required fields";
					echo json_encode($response);die();
				}
			}
			else
			{
				$response['status']="error";
				$response['message']="Empty or invalid file.";
				echo json_encode($response);die();
			}
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Session out please login";
		echo json_encode($response);die();
	}
?>