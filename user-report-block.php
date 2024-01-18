<?php
	include_once 'connection.php';
	$response=array();
	if(isLogin())
	{
		$window_user_id=$_POST['window_user_id'];
		$report_type=$_POST['report_type'];
		$block="";
		$report="";
		if($report_type=="block")
		{
			$block="block";
		}
		else if($report_type=="report")
		{
			$report="report";
		}
		else if($report_type=="report,block" || $report_type=="block,report")
		{
			$report="report";
			$block="block";
		}
		else if($report_type=="unblock")
		{
			$block="unblock";
		}
		$issue_type=$_POST['issue_type'];
		if($block=="block")
		{
			$query="SELECT * FROM user_blocked_user WHERE r_user_id='".$_COOKIE['uid']."' AND user_id='$window_user_id'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				mysqli_query($conn,"UPDATE user_blocked_user SET status=1,added=NOW() WHERE id='".$row['id']."'");
			}
			else
			{
				mysqli_query($conn,"INSERT INTO user_blocked_user SET user_id='$window_user_id',r_user_id='".$_COOKIE['uid']."',status=1,added=NOW()");
			}
			$response['blocked']="blocked";
		}
		else if($block=="unblock")
		{
			$query="SELECT * FROM user_blocked_user WHERE r_user_id='".$_COOKIE['uid']."' AND user_id='$window_user_id'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				mysqli_query($conn,"DELETE FROM user_blocked_user WHERE id='".$row['id']."'");
			}
			$response['blocked']="unblocked";
		}
		if($report=="report")
		{
			$query="INSERT INTO user_reports SET user_id='".$_COOKIE['uid']."',user_being_reported='$window_user_id',issue_type='$issue_type',added=NOW()";
			mysqli_query($conn,$query);
		}
		$response['status']="success";
		$response['_acqf']=md5(time());
	}
	else
	{
		$response['status']="error";
		$response['message']="You have been logged out.Please Try by Login.";
	}
	echo json_encode($response);
?>