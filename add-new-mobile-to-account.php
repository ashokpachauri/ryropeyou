<?php
	include_once 'connection.php';
	$response=array();
	$new_mobile=strip_tags($_POST['new_mobile']);
	$country_short_code=strip_tags($_POST['country_code']);
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		if($new_mobile && $country_short_code)
		{
			$country_query="SELECT * FROM country WHERE code='$country_short_code' AND status=1";
			$country_result=mysqli_query($conn,$country_query);
			if(mysqli_num_rows($country_result)>0)
			{
				$country_row=mysqli_fetch_array($country_result);
				$country_code=$country_row['phonecode'];
				$insert_query="";
				$user_id=$_COOKIE['uid'];
				$random_number=mt_rand(10000,99999);
				$query="SELECT * FROM account_contacts WHERE country_code='$country_code' AND contact='$new_mobile' AND type='mobile' AND user_id='$user_id'";
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
						$insert_query="UPDATE account_contacts SET country_code='$country_code',code='$random_number' WHERE contact='$new_mobile' AND type='mobile' AND user_id='$user_id'";
					}
				}
				else
				{
					$insert_query="INSERT INTO account_contacts SET country_code='$country_code',code='$random_number',contact='$new_mobile',type='mobile',user_id='$user_id',added=NOW(),status=0";
				}
				if($insert_query!="")
				{
					if(mysqli_query($conn,$insert_query))
					{
						if(sendOTP($new_mobile,$random_number,$country_code,'mobile'))
						{
							$response['status']="success";
						}
						else
						{
							$response['status']="error";
							$response['message']="Unable to send otp. We will try re-sending or you can request new one.";
						}
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
				$response['message']="Invalid country code";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="Enter a valid mobile number";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="You are not authorised.";
	}
	echo json_encode($response);
?>