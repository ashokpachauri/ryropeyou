<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$user_id=$_COOKIE['uid'];
		$interest_id=$_POST['interest_id'];
		$query="DELETE FROM users_interests WHERE user_id='$user_id' AND id='$interest_id'";
		mysqli_query($conn,$query);
		$query="SELECT * FROM users_interests WHERE status=1 AND user_id='$user_id'";
		$result=mysqli_query($conn,$query);
		$response['status']='success';
		$htmlData="";
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
						<a href='javascript:void(0);' title='Remove' class='remove_skill' onclick='removeInterests(".$row['id'].");' style='text-decoration:none;'>
							<i class='fa fa-minus' style='font-size:20px;'></i>
						</a>
					</h6>
				</div></div></div>";
				$htmlData.="</div></div>";
			}
				$htmlData.="<script>
				var base_url=localStorage.getItem('base_url');
				function removeInterests(interest_id){
					if(interest_id!=='')
					{
						$.ajax({
							url:base_url+'removeinterests',
							type:'post',
							data:{interest_id:interest_id},
							success:function(data)
							{
								var parsedJson=JSON.parse(data);
								if(parsedJson.status=='success')
								{
									$('.value_wrapper_1').html(parsedJson.htmlData);
								}
							}
						});
					}
				}
				</script>";
			}
		}
		else
		{
			$htmlData="<div class='col-12 col-lg-12'><h6 style='text-align:center;'>No Skills has been added yet.</h6></div>";
		}
		$response['htmlData']=$htmlData;
	}
	else
	{
		$response['status']='error';
		$response['message']='Session Loggedout Try After login';
	}
	echo json_encode($response);
?>