<?php
	include_once 'connection.php';
	$user_id=$_COOKIE['uid'];
	$comment_id=$_POST['comment_id'];
	mysqli_query($conn,"DELETE FROM users_comments_activity WHERE comment_id='$comment_id' AND user_id='$user_id' AND activity_id='Reacted'");
	echo "success";
?>