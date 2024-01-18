<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$comment_id=$_POST['comment_id'];
	$data_feeling=$_POST['data_feeling'];
	mysqli_query($conn,"DELETE FROM users_comments_activity WHERE comment_id='$comment_id' AND user_id='$user_id' AND activity_id='Reacted'");
	mysqli_query($conn,"INSERT INTO users_comments_activity SET comment_id='$comment_id',user_id='$user_id',added=NOW(),activity_id='Reacted',data_feeling='$data_feeling'");
	echo "success";
?>