<?php
	include_once 'connection.php';
	$response=array();
	
	$action=$_REQUEST['action'];
	$file_key=$_REQUEST['file_key'];
	if($action!="" && $file_key!="")
	{
		if($action=="stream")
		{
			include_once 'class.videostream.php';
			$stream = new VideoStream(base64_decode($file_key));
			$stream->start();
		}
	}
?>