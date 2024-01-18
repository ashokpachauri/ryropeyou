<?php
	include_once 'connection.php';
	$page_id=$_REQUEST['page_id'];
	$user_id=$_REQUEST['user_id'];
	$method=$_REQUEST['method'];
	$response=array();
	$like_count=0;
	if($method=="like")
	{
		$query="SELECT * FROM page_likes WHERE page_id='$page_id' AND user_id='$user_id'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$response['status']="success";
			$html='<button class="btn btn-danger" onclick="Unlike('.$page_id.','.$user_id.');" type="button"><i class="fa fa-thumbs-down"></i>&nbsp;Unlike</button>';
			$response['html']=$html;
		}
		else
		{
			if(mysqli_query($conn,"INSERT INTO page_likes SET page_id='$page_id',user_id='$user_id',added=NOW()"))
			{
				$response['status']="success";
				$html='<button class="btn btn-danger" onclick="Unlike('.$page_id.','.$user_id.');" type="button"><i class="fa fa-thumbs-down"></i>&nbsp;Unlike</button>';
				$response['html']=$html;
				mysqli_query($conn,"UPDATE pages SET likes=likes+1 WHERE id='$page_id'");
				insertPageLikeNotification($user_id,$page_id);
			}
			else
			{
				$response['status']="error";
				$html='<button class="btn btn-primary" onclick="Like('.$page_id.','.$user_id.');" type="button"><i class="fa fa-thumbs-up"></i>&nbsp;Like</button>';
				$response['html']=$html;
			}
		}
	}
	else if($method=="unlike")
	{
		$query="SELECT * FROM page_likes WHERE page_id='$page_id' AND user_id='$user_id'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)<=0)
		{
			$response['status']="success";
			$html='<button class="btn btn-primary" onclick="Like('.$page_id.','.$user_id.');" type="button"><i class="fa fa-thumbs-up"></i>&nbsp;Like</button>';
			$response['html']=$html;
		}
		else
		{
			if(mysqli_query($conn,"DELETE FROM page_likes WHERE page_id='$page_id' AND user_id='$user_id'"))
			{
				mysqli_query($conn,"UPDATE pages SET likes=likes-1 WHERE id='$page_id'");
				$response['status']="success";
				$html='<button class="btn btn-primary" onclick="Like('.$page_id.','.$user_id.');" type="button"><i class="fa fa-thumbs-up"></i>&nbsp;Like</button>';
				$response['html']=$html;
			}
			else
			{
				$response['status']="error";
				$html='<button class="btn btn-danger" onclick="Unlike('.$page_id.','.$user_id.');" type="button"><i class="fa fa-thumbs-down"></i>&nbsp;Unlike</button>';
				$response['html']=$html;
			}
		}
	}
	else
	{
		$response['status']="unauth";
		$response['html']="";
	}
	$page_like_query="SELECT * FROM pages WHERE id='$page_id'";
	$page_like_result=mysqli_query($conn,$page_like_query);
	$page_like_row=mysqli_fetch_array($page_like_result);
	$response['likes_count']=$page_like_row['likes'];
	echo json_encode($response);
?>