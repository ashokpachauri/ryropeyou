<?php
	include_once 'connection.php';
	$session_attribute=$_POST['session_attribute'];
	$page_id=$_POST['page_id'];
	$response=array();
	$title=time()."-".strtolower(basename(str_replace(" ","-",$_FILES["file"]["name"])));
	$target_file="uploads/".$title;
	$file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$file_type_img_arr=array("jpg","jpeg","png","gif");
	$file_type_vid_arr=array("mp4","mov","wmb","flv","avi");
	$type="image/".$file_type;
	if(in_array($file_type,$file_type_vid_arr))
	{
		$type="video/".$file_type;
	}
	$size=$_FILES["file"]["size"];
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		$query="INSERT INTO page_posts_media SET user_id='".$_COOKIE['uid']."',title='$title',file='$target_file',type='$type',size='$size',added=NOW(),status=0,page_id='$page_id',session_attribute='$session_attribute'";
		if(mysqli_query($conn,$query))
		{
			$response['id']=mysqli_insert_id($conn);
			$response['status']="success";
		}
		else
		{
			$response['id']=$query;//"";
			$response['status']="error";
			@unlink($target_file);
		}
	} 
	else 
	{
		$response['id']="";
		$response['status']="error";
	}
	echo json_encode($response);
?>