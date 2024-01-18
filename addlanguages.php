<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$user_id=$_COOKIE['uid'];
		//$proficiency=filter_var($_POST['proficiency'],FILTER_SANITIZE_STRING);
		$language_title=filter_var($_POST['language_title'],FILTER_SANITIZE_STRING);
		
		$query="SELECT * FROM users_languages WHERE title='$language_title' AND user_id='$user_id'";
		$result=mysqli_query($conn,$query);
		$query="INSERT INTO users_languages SET title='$language_title',user_id='$user_id'";
		if(mysqli_num_rows($result)>0)
		{
			//$query="UPDATE users_languages SET proficiency='$proficiency' WHERE title='$language_title' AND user_id='$user_id'";
		}
		$htmlData="";
		$response['status']='success';
		mysqli_query($conn,$query);
		$query="SELECT * FROM users_languages WHERE user_id='$user_id'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			while($row=mysqli_fetch_array($result))
			{
				$htmlData=$htmlData."<div class='col col-12 col-lg-6' style='padding:15px;'><div class='row' style='margin-top:5px;'>";
				$htmlData.="<div class='col-xl-12 mobile_skill' style='border:1px solid gray;border-radius:10px;height:30px;'><div class='row'><div class='col-10 col-lg-10'>
					<h6 style='font-size:14px;'>".$row['title']."</h6>
				</div>";
				
				$htmlData.="<div class='col-2 col-lg-2'>
					<h6>
						<a href='javascript:void(0);' title='Remove' class='remove_language' onclick='removeLanguages(".$row['id'].");' style='text-decoration:none;'>
							<i class='fa fa-minus' style='font-size:20px;'></i>
						</a>
					</h6>
				</div></div></div>";
				$htmlData.="</div></div>";
			}
		}
		else
		{
			$htmlData="<div class='col-12 col-lg-12'><h6 style='text-align:center;'>No Languages has been added yet.</h6></div>";
		}
		$htmlData.="<script>
		var base_url=localStorage.getItem('base_url');
		function removeLanguages(language_id){
			if(language_id!=='')
			{
				$.ajax({
					url:base_url+'removelanguages',
					type:'post',
					data:{language_id:language_id},
					success:function(data)
					{
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=='success')
						{
							$('.value_wrapper_2').html(parsedJson.htmlData);
						}
					}
				});
			}
		}
		</script>";
		$response['htmlData']=$htmlData;
	}
	else
	{
		$response['status']='error';
		$response['message']='Session Loggedout Try After login';
	}
	echo json_encode($response);
?>