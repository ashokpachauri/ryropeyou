<?php
	include_once 'connection.php';
	$space_url=trim(strtolower($_POST['space_url']));
	$post_id="";
	if(isset($_POST['post_id']) && $_POST['post_id']!="")
	{
		$post_id=$_POST['post_id'];
	}
	$additional="";
	if($post_id!="")
	{
		$additional=" AND id!='$post_id'";
	}
	$response=array();
	$query="SELECT * FROM blog_space_posts WHERE post_url='$space_url'".$additional;
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