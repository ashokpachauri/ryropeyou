<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_POST['r_user_id']) && $_POST['r_user_id']!="")
	{
		$selected_user=$_POST['r_user_id'];
		if(!userBlocked($selected_user))
		{
			$img_mesg=0;
			$me=$_COOKIE['uid'];
			$text_message=addslashes(trim(filter_var($_POST['text_message']),FILTER_SANITIZE_STRING));
			if(isset($_POST['img_mesg']) && $_POST['img_mesg']!="" && $_POST['img_mesg']!="0")
			{
				$img_mesg=$_POST['img_mesg'];
				$image_file="uploads/image_render_".$_POST['r_user_id']."_".$me."_".time().".jpg";
				outputFileToLocation($text_message,$image_file);
				$text_message=$image_file;
			}
			
			$page_refer=$_POST['page_refer'];
			$query="INSERT INTO users_chat SET img_mesg='$img_mesg',added=NOW(),status=1,text_message='$text_message',user_id='$me',r_user_id='$selected_user',flag=0,s_status=1,r_status=1";
			if(mysqli_query($conn,$query))
			{
				$response['status']="success";
				$response['message']="Message sent successfully";
			}
			else
			{
				$response['status']="error";
				$response['message']="Some technical error.We are looking at this.Please try after a moment.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="You are not allowed to send message to this user.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Invalid message";
	}
	echo json_encode($response);
?>	