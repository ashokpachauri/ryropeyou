<?php
	include_once 'connection.php';
	$id=$_REQUEST['reset_hash'];
	$response=array();
	$query="SELECT * FROM users WHERE md5(id)='$id'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0)
	{
		$random_number=mt_rand(1000,9999);
		$response['status']="success";
		$row=mysqli_fetch_array($result);
		
		mysqli_query($conn,"UPDATE users SET code='$random_number' WHERE id='".$row['id']."'");
		
		$sent=false;
		if($row['email']!="")
		{
			$email=$row['email'];
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <no-reply@ropeyou.com>' . "\r\n";
			$email_content="Hello ".ucwords(strtolower($row['first_name']." ".$row['last_name']))."<br/><br/>";
			$email_content."Below is the password reset link for your account as per your request.<br/><br/>";
			$email_content.="<a href='".base_url."reset-password.php?code=".$random_number."&hash=".$id."&ctype=email'>".base_url."reset-password.php?code=".$random_number."&hash=".$id."&ctype=email</a><br/><br/>";
			$email_content.="If you did not made this request then please ignore the email and your account is secure with us.<br/><br/><b>Team RopeYou</b>";
			mail($email,"RopeYou - Password Reset Email",$email_content,$headers);
			$sent=true;
		}
		if($row['mobile']!="")
		{
			$mobile=$row['mobile'];
			$sent=true;
		}
		if(!$sent)
		{
			$response['status']="error";
			$response['message']="No Registered email or mobile found. Please login with your social media & register an email or mobile.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Account does not exists. Please try again.";
	}
	echo json_encode($response);
?>