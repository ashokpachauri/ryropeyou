<?php
	include_once 'connection.php';
	$day_today=(int)(date('d',strtotime('now')));
	$month_today=(int)(date('m',strtotime('now')));
	
	$query="SELECT day_birth,year_birth,month_birth,user_id FROM users_personal WHERE day_birth='$day_today' AND month_birth='$month_today'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0)
	{
		while($row=mysqli_fetch_array($result))
		{
			$user_id=$row['user_id'];
			
			$day_birth=(int)($row['day_birth']);
			$month_birth=(int)($row['month_birth']);
			
			/*echo $day_birth."<br/>";
			echo $month_birth."<br/>";
			echo $day_today."<br/>";
			echo $month_today."<br/>";
			continue;*/
			if(userActive($user_id))
			{
				$birth_anniversary_share_option=getPrivacySetting('birth_anniversary_share_option',$user_id);
				
				echo $birth_anniversary_share_option;
				if($birth_anniversary_share_option=="1")
				{
					//echo "Hello";
					$friends_to_notify=array();
					$friends_query="SELECT r_user_id FROM user_joins_user WHERE user_id='$user_id' AND status=1";
					$friends_result=mysqli_query($conn,$friends_query);
					if(mysqli_num_rows($friends_result)>0)
					{
						while($friends_row=mysqli_fetch_array($friends_result))
						{
							$friends_to_notify[]=$friends_row['r_user_id'];
						}
					}
					$friends_query="SELECT user_id FROM user_joins_user WHERE r_user_id='$user_id' AND status=1";
					$friends_result=mysqli_query($conn,$friends_query);
					if(mysqli_num_rows($friends_result)>0)
					{
						while($friends_row=mysqli_fetch_array($friends_result))
						{
							$friends_to_notify[]=$friends_row['user_id'];
						}
					}
					if(count($friends_to_notify)>0)
					{
						$user_data=getUsersData($user_id);
						for($loopvar=0;$loopvar<count($friends_to_notify);$loopvar++)
						{
							$friend_id=$friends_to_notify[$loopvar];
							
							if(userActive($friend_id))
							{
								$redirect_url_segment="wishes.php?uthread=".md5($user_id)."&wish=birthday";
								$heading=ucwords(strtolower($user_data['first_name']." ".$user_data['last_name']))." has its birthday today.";
								mysqli_query($conn,"INSERT INTO threats_to_user SET user_id='$friend_id',heading='$heading',message='$heading',added=NOW(),seen=0,redirect_url_segment='$redirect_url_segment'");
							}
						}
					}
					continue;
				}
				continue;
			}
			else{
				//echo "HI";
			}
			continue;
		}
	}
?>