<?php
	include_once 'connection.php';
	$response=array();
	$new_email=filter_var($_POST['new_email'],FILTER_SANITIZE_EMAIL);
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		if($new_email)
		{
			$insert_query="";
			$user_id=$_COOKIE['uid'];
			$random_number=mt_rand(10000,99999);
			$query="SELECT * FROM account_contacts WHERE contact='$new_email' AND type='email' AND user_id='$user_id'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				if($row['status']=="1")
				{
					$response['status']="error";
					$response['message']="Email already attachedto your account.";
				}
				else{
					$insert_query="UPDATE account_contacts SET code='$random_number' WHERE contact='$new_email' AND type='email' AND user_id='$user_id'";
				}
			}
			else
			{
				$insert_query="INSERT INTO account_contacts SET code='$random_number',contact='$new_email',type='email',user_id='$user_id',added=NOW(),status=0";
			}
			if($insert_query!="")
			{
				if(mysqli_query($conn,$insert_query))
				{
					$response['status']="success";
					$email=$new_email;
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: <no-reply@ropeyou.com>' . "\r\n";
					$email_content="Hey! there,<br/><br/>";
					$email_content."Below is the one time verification code for adding a new email to your account.<br/><br/>";
					$email_content.="One Time Verification Code is : ".$random_number."<br/><br/>";
					$email_content.="If you did not made this request then please ignore the email and your account is secure with us.<br/><br/><b>Team RopeYou</b>";
					mail($email,"RopeYou - Verification code for contact verification",$email_content,$headers);
				}
				else
				{
					$response['status']="error";
					$response['message']="Something went wrong. Please retry.";
				}
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="Enter a valid email address";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="You are not authorised.";
	}
	echo json_encode($response);
?>