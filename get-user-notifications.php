<?php
	include_once 'connection.php';
	$response=array();
	if(isset($_POST['endpoint']) && $_POST['endpoint']!="" && isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		$endpoint=$_POST['endpoint'];
		if($endpoint=="unread")
		{
			$sound_to_play=0;
			$alert_query="SELECT id FROM threats_to_user WHERE user_id='".$_COOKIE['uid']."' AND seen=0 AND checkout=0 ORDER BY id DESC";
			$alert_result=mysqli_query($conn,$alert_query);
			$alert_num_rows=mysqli_num_rows($alert_result);
			$response['count']=$alert_num_rows;
			
			$alert_query="SELECT id,flag FROM threats_to_user WHERE user_id='".$_COOKIE['uid']."' ORDER BY id DESC LIMIT 10";
			$alert_result=mysqli_query($conn,$alert_query);
			$alert_num_rows=mysqli_num_rows($alert_result);
			if($alert_num_rows>0)
			{
				$response['status']="success";
				$html="";
				while($alert_row=mysqli_fetch_array($alert_result))
				{
					$html.=getNotification($alert_row['id']);
					if($alert_row['flag']==0)
					{
						$sound_to_play=1;
					}
					mysqli_query($conn,"UPDATE threats_to_user SET flag=1 WHERE id='".$alert_row['id']."'");
				}
				$response['data']=$html;
				$response['sound_to_play']=$sound_to_play;
			}
			else
			{
				$response['status']="success";
				$response['count']="0";
				$response['sound_to_play']="0";
				$response['data']='<a class="dropdown-item text-center small text-gray-500" href="javascript:void(0)">No more notifications</a>';
			}
		}
	}
	else
	{
		$response['status']="error";
	}
	echo json_encode($response);
?>