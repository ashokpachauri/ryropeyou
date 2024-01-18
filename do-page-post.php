<?php
	include_once 'connection.php';
	$response=array();
	$response['session_attribute']=time().$_COOKIE['uid'];
	$page_id=$_POST['page_id'];
	$user_id=$_COOKIE['uid'];
	$method=$_POST['method'];
	$session_attribute=$_POST['session_attribute'];
	$text=addslashes(filter_var($_POST['text'],FILTER_SANITIZE_STRING));
	if($method=="doNewPost")
	{
		$page_post_insert_query="INSERT INTO page_posts SET page_id='$page_id',user_id='$user_id',text_content='$text',session_attribute='$session_attribute',added=NOW(),status=1";
		$page_post_insert_result=mysqli_query($conn,$page_post_insert_query);
		if($page_post_insert_result)
		{
			$page_post_id=mysqli_insert_id($conn);
			$page_posts_media_query="UPDATE page_posts_media SET status=1,page_post_id='$page_post_id' WHERE session_attribute='$session_attribute' AND page_id='$page_id' AND user_id='$user_id'";
			$page_posts_media_result=mysqli_query($conn,$page_posts_media_query);
			mysqli_query($conn,"DELETE FROM page_posts_media WHERE status!=1 AND added NOT LIKE '%DATE(NOW())%' AND user_id='$user_id'");
			$html_response=getPagePostHtml($page_post_id);
			$response['status']="success";
			$response['html']=$html_response;
		}
		else
		{
			$response['status']="error";
			$response['html']="";
		}
	}
	else if($method=="doEditPost")
	{
		$page_post_id=$_POST['page_post_id'];
		$page_post_insert_query="UPDATE page_posts SET page_id='$page_id',user_id='$user_id',text_content='$text',session_attribute='$session_attribute',updated=NOW() WHERE id='$page_post_id'";
		$page_post_insert_result=mysqli_query($conn,$page_post_insert_query);
		if($page_post_insert_result)
		{
			$page_post_id=mysqli_insert_id($conn);
			$page_posts_media_query="UPDATE page_posts_media SET status=1,page_post_id='$page_post_id' WHERE session_attribute='$session_attribute'";
			$page_posts_media_result=mysqli_query($conn,$page_posts_media_query);
			$page_posts_media_query="UPDATE page_posts_media SET status=1,session_attribute='$session_attribute' WHERE page_post_id='$page_post_id'";
			$page_posts_media_result=mysqli_query($conn,$page_posts_media_query);
			
			mysqli_query($conn,"DELETE FROM page_posts_media WHERE status!=1 AND added NOT LIKE '%DATE(NOW())%' AND user_id='$user_id'");
			$html_response=getPagePostHtml($page_post_id);
			$response['status']="success";
			$response['html']=$html_response;
		}
		else
		{
			$response['status']="error";
			$response['html']="";
		}
	}
	else if($method=="doDeletePost")
	{
		$page_post_id=$_POST['page_post_id'];
		$page_post_delete_query="UPDATE page_posts SET status=0 WHERE id='$page_post_id'";
		if(mysqli_query($conn,$page_post_delete_query))
		{
			$response['status']="success";
			$response['html']="";
		}
		else
		{
			$response['status']="error";
			$response['html']="";
		}
	}
	else if($method=="doDeletePostMedia")
	{
		$page_post_id=$_POST['page_post_id'];
		$page_posts_media_id=$_POST['page_posts_media_id'];
		$page_posts_media_delete_query="UPDATE page_posts_media SET status=0 WHERE page_post_id='$page_post_id' AND id='$page_posts_media_id'";
		if(mysqli_query($conn,$page_posts_media_delete_query))
		{
			$response['status']="success";
			$response['html']="";
		}
		else
		{
			$response['status']="error";
			$response['html']="";
		}
	}
	else if($method=="doPagePostLike")
	{
		$page_post_id=$_POST['page_post_id'];
		$page_post_like_check_query="SELECT * FROM page_posts_like WHERE page_post_id='$page_post_id' AND user_id='$user_id'";
		$page_post_like_check_result=mysqli_query($conn,$page_post_like_check_query);
		if(mysqli_num_rows($page_post_like_check_result)>0)
		{
			$response['status']="success";
			$response['html']="";
		}
		else
		{
			$page_post_like_query="INSERT INTO page_posts_like SET user_id='$user_id',page_id='$page_id',page_post_id='$page_post_id',added=NOW()";
			$page_post_like_result=mysqli_query($conn,$page_post_like_query);
			if($page_post_like_result)
			{
				$response['status']="success";
				$response['html']="";
			}
			else
			{
				$response['status']="error";
				$response['html']="";
			}
		}
	}
	else if($method=="doPagePostUnLike")
	{
		$page_post_id=$_POST['page_post_id'];
		$page_post_like_check_query="SELECT * FROM page_posts_like WHERE page_post_id='$page_post_id' AND user_id='$user_id'";
		$page_post_like_check_result=mysqli_query($conn,$page_post_like_check_query);
		if(mysqli_num_rows($page_post_like_check_result)>0)
		{
			$page_post_like_query="DELETE FROM page_posts_like WHERE user_id='$user_id' AND page_id='$page_id' AND page_post_id='$page_post_id'";
			if(mysqli_query($conn,$page_post_like_query))
			{
				$response['status']="success";
				$response['html']="";
			}
			else
			{
				$response['status']="error";
				$response['html']="";
			}
		}
		else
		{
			$response['status']="success";
			$response['html']="";
		}
	}
	echo json_encode($response);
?>