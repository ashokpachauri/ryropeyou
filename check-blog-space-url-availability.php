<?php
	include_once 'connection.php';
	$space_url=$_POST['space_url'];
	$response=array();
	$query="SELECT * FROM blog_spaces WHERE space_url='$space_url'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0)
	{
		$response['status']="error";
	}
	else
	{
		$response['status']="success";
	}
	echo json_encode($response);
?>