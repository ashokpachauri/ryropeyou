<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		foreach ($_POST as $key=>$val)
		{
			if($val!="")
			{
				$query="SELECT * FROM users_privacy_settings WHERE setting_term='$key'";
				$result=mysqli_query($conn,$query);
				if(mysqli_num_rows($result)>0)
				{
					mysqli_query($conn,"UPDATE users_privacy_settings SET setting_value='$val',updated=NOW() WHERE setting_term='$key' AND user_id='".$_COOKIE['uid']."'");
				}
			}
		}
		$response['status']="success";
	}
	else
	{
		$response['status']="error";
	}
	echo json_encode($response);
?>