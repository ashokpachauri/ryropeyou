<?php
include_once 'connection.php';
$response_arr=array();
$country_code=$_POST['country_code'];
$mobile=$_POST['mobile'];
$query="SELECT * FROM users WHERE country_code='$country_code' AND mobile='$mobile'";
$result=mysqli_query($conn,$query);
if(mysqli_num_rows($mobile)>0)
{
	$row=mysqli_fetch_array($result);
	$random=mt_rand(1000,9999);
	if($row['code']=="")
	{
		mysqli_query($conn,"UPDATE users SET code='$random' country_code='$country_code' AND mobile='$mobile'");
	}
	else
	{
		$random=$row['code'];
	}
	if(sendOTP($mobile,$random,$country_code,'mobile'))
	{
		$response_arr['status']="success";
	}
	else{
		$response_arr['status']="error";
	}
}
else{
	$response_arr['']="unauth";
}
echo json_encode($response_arr);
?>