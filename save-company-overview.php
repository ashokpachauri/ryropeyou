<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$user_id=$_COOKIE['uid'];
		$data_posted=$_POST;
		$data_to_return=array();
		$query="SELECT * FROM companies WHERE user_id='$user_id' AND id='".$_POST['id']."'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			$in_appropriate=1;
			$count=0;
			$query_string="";
			foreach($_POST as $key=>$value){
				if($count!=0)
				{
					$query_string.=',';
				}
				$count=$count+1;
				$data_value=filter_var(htmlentities($value),FILTER_SANITIZE_STRING);
				if(isUploadableText($query_string))
				{
					
				}
				$query_string.=$key."='".addslashes($data_value)."'";
				$data_to_return[''.$key.'']=$data_value;
			}
			$query="UPDATE companies SET ".$query_string." WHERE id='".$_POST['id']."'";
			if(mysqli_query($conn,$query))
			{
				$response['status']="success";
				$response['message']="Records updated successfully.";
				$response['data']=$data_to_return;
			}
			else
			{
				$response['status']="error";
				$response['message']="Server error please contact developer.";
			}
		}
		else
		{
			$response['status']="error";
			$response['message']="You are not authorized to make these changes.";
		}
	}
	else{
		$response['status']="error";
		$response['message']="Session out please login.";
	}
	//print_r($response);
	echo json_encode($response);
?>