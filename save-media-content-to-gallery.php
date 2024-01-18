<?php
	include_once 'connection.php';
	$id=$_POST['id'];
	$text_content=htmlentities($_POST['text_content']);
	$in_appropriate=1;
	if(isUploadableText($text_content))
	{
		$in_appropriate=0;
	}
	if($id!="")
	{
		$query="UPDATE gallery SET text_content='$text_content' WHERE in_appropriate='$in_appropriate',id='$id' AND user_id='".$_COOKIE['uid']."'";
		$result=mysqli_query($conn,$query);
		if($result)
		{
			echo json_encode(array("status"=>"success"));
		}
		else{
			echo json_encode(array("status"=>"error in db"));
		}
	}
	else
	{
		echo json_encode(array("status"=>"invalid request"));
	}
?>