<?php
include_once __DIR__.'/conn-settings.php';
include_once 'content-detection/vendor/autoload.php';
use \Sightengine\SightengineClient;	
header('Access-Control-Allow-Origin: *.ropeyou.com');  
function isUploadableText($text="")
{
	$text=trim($text);
	if($text=="")
	{
		return true;
	}
	$client = new SightengineClient('1696532987','7DESGvo3N62R3nPXTqv9');
	$params = array(
	  'text' => $text,
	  'lang' => 'en',
	  'mode' => 'standard',
	  'api_user' => '1696532987',
	  'api_secret' => '7DESGvo3N62R3nPXTqv9',
	);

	// this example uses cURL
	$ch = curl_init('https://api.sightengine.com/1.0/text/check.json');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	$response = curl_exec($ch);
	curl_close($ch);
	$profenity_detected=false;
	$output = json_decode($response, true);
	if($output['status']=="success")
	{
		if(isset($output['profanity']['matches']) && count($output['profanity']['matches'])>0)
		{
			$matches=$output['profanity']['matches'];
			if(count($matches)>0)
			{
				$profenity_detected=true;
			}
		}
		
	}
	return (!$profenity_detected);
}
function isUploadable($image)
{
	$client = new SightengineClient('1696532987','7DESGvo3N62R3nPXTqv9');
	$output = $client->check(['celebrities','nudity','wad','offensive','text-content','face-attributes'])->set_url($image);
	$thresholds=array();
	$thresholds['weapon']=10;
	$thresholds['alcohol']=10;
	$thresholds['drugs']=5;
	$thresholds['safe_nudity']=75;
	$thresholds['partial_nudity']=35;
	$thresholds['raw_nudity']=5;
	$thresholds['offensive']=5;
	$thresholds['celebrity']=50;
	//print_r($output);
	$status=$output->status;
	$weapen_detected=false;
	$celebrity_detceted=false;
	$alcohol_detected=false;
	$partial_nudity_detected=false;
	$raw_nudity_detected=false;
	$offensive_detected=false;
	$safe_nudity_detected=true;
	$drugs_detected=false;
	if($status=="success")
	{
		if(isset($output->faces) && count($output->faces)>0)
		{
			//print_r($output->faces);
			foreach($output->faces as $face)
			{
				//echo "Hello";
				if(isset($face->celebrity) && count($face->celebrity)>0)
				{
					//echo "Hi";
					foreach($face->celebrity as $celebrity)
					{
						//echo "Hey";
						if(isset($celebrity->prob) && $celebrity->prob!="")
						{
							//echo "Hmm";
							$probability=(int)($celebrity->prob*100);
							//echo $probability;
							if($probability>=$thresholds['celebrity'])
							{
								
								//echo $celebrity->name;
								$celebrity_detceted=true;
							}
						}
					}
				}
			}
		}
		if(isset($output->weapon) && $output->weapon!="")
		{
			$weapon=(int)($output->weapon*100);
			if($weapon>=$thresholds['weapon'] && (!$celebrity_detceted))
			{
				$weapen_detected=true;
			}
		}
		if(isset($output->alcohol) && $output->alcohol!="")
		{
			$alcohol=(int)($output->alcohol*100);
			if($alcohol>=$thresholds['alcohol'] && (!$celebrity_detceted))
			{
				$alcohol_detected=true;
			}
		}
		if(isset($output->drugs) && $output->drugs!="")
		{
			$drugs=(int)($output->drugs*100);
			if($drugs>=$thresholds['drugs'] && (!$celebrity_detceted))
			{
				$drugs_detected=true;
			}
		}
		if(isset($output->nudity->partial) && $output->nudity->partial!="")
		{
			$partial_nudity=(int)($output->nudity->partial*100);
			if($partial_nudity>=$thresholds['partial_nudity'] && (!$celebrity_detceted))
			{
				$partial_nudity_detected=true;
			}
		}
		if(isset($output->nudity->raw) && $output->nudity->raw!="")
		{
			$raw_nudity=(int)($output->nudity->raw*100);
			if($raw_nudity>=$thresholds['raw_nudity'])
			{
				$raw_nudity_detected=true;
			}
		}
		if(isset($output->offensive->prob) && $output->offensive->prob!="")
		{
			$offensive=(int)($output->offensive->prob*100);
			if($offensive>=$thresholds['offensive'] && (!$celebrity_detceted))
			{
				$offensive_detected=true;
			}
		}
		if(isset($output->nudity->safe) && $output->nudity->safe!="")
		{
			$safe_nudity=(int)($output->nudity->safe*100);
			if($safe_nudity<=$thresholds['safe_nudity'])
			{
				$safe_nudity_detected=false;
			}
		}
		if($safe_nudity_detected && (!$weapen_detected) && (!$alcohol_detected) && (!$drugs_detected) && (!$partial_nudity_detected) && (!$raw_nudity_detected) && (!$offensive_detected))
		{
			return true;
		}
		else{
			return false;
		}
	}
	else
	{
		return false;
	}
}
//header('Access-Control-Allow-Origin: https://ropeyou.com');
//header('X-Frame-Options: SAMEORIGIN');
/*---------------- $window_user_id is $r_user_id------------------------------*/
#=================================================================
function displayDistance($distance)
{
	/*if($distance>=1000)
	{
		$distance=floor($distance/1000)." KM";
	}
	else
	{
		$distance=$distance." meters";
	}*/
	return floor($distance)." Meters";
}
function rsine($coordinates)
{
	//print_r($coordinates);die();
    return '(6371 * acos(cos(radians('. $coordinates['latitude'] .')) 
    * cos(radians(`lattitude`)) 
    * cos(radians(`longitude`) 
    - radians(' . $coordinates['longitude'] . ')) 
    + sin(radians(' . $coordinates['latitude'] . ')) 
    * sin(radians(`lattitude`))))';
}
function scopeIsWithinMaxDistance($rsine,$user_id,$radius = 50000)
{
	return "SELECT *,{$rsine} AS distance FROM users WHERE {$rsine} < '$radius' AND id!='$user_id' ORDER BY distance ASC"; 
}
function getNearbyUsers($coordinates,$user_id)
{
	$rsine=rsine($coordinates);
	return scopeIsWithinMaxDistance($rsine,$user_id);
}
//getNearbyUsers();
function getCommonPersons($bridge_user_id="",$loggedin_user_id="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	
	$html="";
	$mutual_connections_count=0;
	$m1_query="SELECT * FROM user_joins_user WHERE ((user_id=".$loggedin_user_id." AND r_user_id!=".$bridge_user_id.") OR (r_user_id=".$loggedin_user_id." AND user_id!=".$bridge_user_id.")) AND status=1 AND blocked=0";
	$m1_result=mysqli_query($CONNECT,$m1_query);
	$num_rows=mysqli_num_rows($m1_result);
	$users_connection_1=array();
	$users_connection_2=array();
	if($num_rows>0)
	{
		$m2_query="SELECT * FROM user_joins_user WHERE ((user_id=".$bridge_user_id." AND r_user_id!=".$loggedin_user_id.") OR (r_user_id=".$bridge_user_id." AND user_id!=".$loggedin_user_id.")) AND status=1 AND blocked=0";
		$m2_result=mysqli_query($CONNECT,$m2_query);
		//echo $m1_query;
		//echo $m2_query;
		$num_rows=mysqli_num_rows($m2_result);
		if($num_rows>0)
		{
			while($m1_row=mysqli_fetch_array($m1_result))
			{
				if($m1_row['user_id']==$loggedin_user_id)
				{
					$users_connection_1[]=$m1_row['r_user_id'];
				}
				else{
					$users_connection_1[]=$m1_row['user_id'];
				}
			}
			while($m2_row=mysqli_fetch_array($m2_result))
			{
				if($m2_row['user_id']==$bridge_user_id)
				{
					$users_connection_2[]=$m2_row['r_user_id'];
				}
				else{
					$users_connection_2[]=$m2_row['user_id'];
				}
			}
			$intersect_1=array_intersect($users_connection_1, $users_connection_2);
			$mutual_connections_count=count($intersect_1);
		}
	}
	$counter=0;		
	$bridge_user_details_data=getUsersData($bridge_user_id);
	$bridge_user_username="'".$bridge_user_details_data['username']."'";
	$mutual="'mutual-connections'";
	$onclick='onclick="redirectToURICustom('.$bridge_user_username.','.$mutual.');"';
	$html.='<div class="row"><div class="col-md-12 d-flex align-items-center p-1" '.$onclick.' style="cursor:pointer !important;padding-top:0px !important;">';
	if($mutual_connections_count>0)
	{	
		$html.='<div class="overlap-rounded-circle">';
		for($loopvar=0;$loopvar<count($users_connection_1);$loopvar++)
		{
			$m_user_id=$intersect_1[$loopvar];
			if($m_user_id!="" && $counter<5)
			{
				$counter=$counter+1;
				$m_u_profile=getUserProfileImage($m_user_id);
				$m_u_query="SELECT * FROM users WHERE id='".$m_user_id."'";
				$m_u_result=mysqli_query($CONNECT,$m_u_query);
				$m_u_row=mysqli_fetch_array($m_u_result);
				
				$last_designation=getUsersCurrentDesignation($m_user_id);
				$last_education=getUsersCurrentEducation($m_user_id);
				$home_town="";
				$bridge_personal_query="SELECT * FROM users_personal WHERE user_id='".$m_user_id."'";
				$bridge_personal_result=mysqli_query($CONNECT,$bridge_personal_query);
				if(mysqli_num_rows($bridge_personal_result))
				{
					$bridge_personal_row=mysqli_fetch_array($bridge_personal_result);
					$home_town_id=$bridge_personal_row['home_town'];
					if($home_town_id!="" && $home_town_id!="0")
					{
						$home_town=getCityByID($home_town_id);
						if($home_town=="NA")
						{
							$home_town="";
						}
					}

				}
				
				$html.='<img class="rounded-circle shadow-sm contact-list img-responsive" style="border:1px solid #eaebec !important;padding:0px;margin:0px;height:30px;width:30px;" data-toggle="tooltip" data-sm="'.$m_user_id.'" data-placement="top" title="'.ucwords(strtolower($m_u_row['first_name']." ".$m_u_row['last_name'])).'" src="'.$m_u_profile.'" alt="'.ucwords(strtolower($m_u_row['first_name']." ".$m_u_row['last_name'])).'">';
				$html.='<div uk-drop="pos: left-center ;animation: uk-animation-slide-left-small">
							<div class="contact-list-box">
								<div class="contact-list-box-media">
									<a href="'.base_url.'u/'.$m_u_row['username'].'">
									<img src="'.$m_u_profile.'" class="img-responsive" style="height:100px;" alt="'.ucwords(strtolower($m_u_row['first_name']." ".$m_u_row['last_name'])).'"></a>
									<span class="online-dot"></span>
								</div>
								<h4><a href="'.base_url.'u/'.$m_u_row['username'].'">'.ucwords(strtolower($m_u_row['first_name']." ".$m_u_row['last_name'])).'</a></h4>
								<p>';
								if($last_designation){ $html.= $last_designation.'<br/>'; } if($last_education){ $html.= $last_education.'<br/>'; }  if($home_town!="") { $html.= "Location : ".$home_town.'<br/>'; } $html.='</p>
								<div class="contact-list-box-btns">
									<a href="'.base_url.'u/'.$m_u_row['username'].'" title="View Profile" class="button primary block mr-2">
										<i class="uil-user mr-1"></i> View Profile</a>
									<a href="'.base_url.'u/'.$m_u_row['username'].'/gallery" title="View Gallery" class="button secondary button-icon mr-2">
										<i class="fa fa-picture-o"> </i> </a>
									<a href="'.base_url.'w/'.$m_u_row['username'].'" title="Web View" class="button secondary button-icon mr-2"> <i class="fa fa-snowflake-o"></i> </a>
								</div>
							</div>
						</div>';
			}
		}
		$html.='</div>';
	}
	$html.='<span class="font-weight-bold small text-primary">'.$mutual_connections_count.' mutual connections</span></div></div>';
	return $html;
}
function canSeeConnections($user_id="",$required_user_id="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($user_id==$required_user_id)
	{
		return true;
	} 
	else if($user_id=="" || $required_user_id=="")
	{
		return false;
	}
	else
	{
		if(noBlocking($user_id,$required_user_id))
		{
			$privacy_arr=getPrivacySetting("who_can_see_connections",$required_user_id,"all");
			if($privacy_arr!=false)
			{
				$settings=$privacy_arr['setting_value'];
				if($settings=="1,0,0,0,0")
				{
					return true;
				}
				else if($settings=="0,1,0,0,0")
				{
					return false;
				}
				else if($settings=="0,0,1,0,0")
				{
					$query="SELECT * FROM user_joins_user WHERE ((user_id='$required_user' AND r_user_id='$user_id') OR (user_id='$user_id' AND r_user_id='$required_user')) AND status=1";
					$result=mysqli_query($CONNECT,$query);
					if(mysqli_num_rows($result)>0)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else if($settings=="0,0,1,1,0")
				{
					$query="SELECT * FROM user_joins_user WHERE ((user_id='$required_user' AND r_user_id='$user_id') OR (user_id='$user_id' AND r_user_id='$required_user')) AND status=1";
					$result=mysqli_query($CONNECT,$query);
					if(mysqli_num_rows($result)>0)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					$query="SELECT * FROM user_joins_user WHERE ((user_id='$required_user' AND r_user_id='$user_id') OR (user_id='$user_id' AND r_user_id='$required_user')) AND status=1";
					$result=mysqli_query($CONNECT,$query);
					if(mysqli_num_rows($result)>0)
					{
						if($settings=="0,0,1,0,1")
						{
							$users_allowed=$privacy_arr['users_allowed'];
							$users_allowed_arr=explode(",",$users_allowed);
							if(in_array($user_id,$users_allowed_arr))
							{
								return true;
							}
							else
							{
								return false;
							}
						}
						else if($settings=="0,0,1,0,1")
						{
							$users_blocked=$privacy_arr['users_blocked'];
							$users_blocked=explode(",",$users_blocked);
							if(in_array($user_id,$users_allowed_arr))
							{
								return false;
							}
							else
							{
								return true;
							}
						}
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}
function conn_selec_text_arr($mode_seection_dropdown){
	switch($mode_seection_dropdown)
	{
		case "1,0,0,0,0": return "<i class='fa fa-globe'></i>&nbsp;&nbsp;Any One";break;
		case "0,1,0,0,0":return "<i class='fa fa-user'></i>&nbsp;&nbsp;Only Me";break;
		case "0,0,1,0,0":return "<i class='fa fa-users'></i>&nbsp;&nbsp;Only Connections";break;
		case "0,0,1,1,0":return "<i class='fa fa-users'></i>&nbsp;&nbsp;Connections of Connections";break;
		case "0,0,1,0,1":return "<i class='fa fa-users'></i>&nbsp;+&nbsp;Allowed Specific";break;
		case "0,0,1,0,2":return "<i class='fa fa-users'></i>&nbsp;-&nbsp;Blocked Specific";break;
	}
}
function noBlocking($user_id="",$required_user="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($user_id=="" || $required_user=="")
	{
		return true;
	}
	else if($user_id==$required_user)
	{
		return true;
	}
	else
	{
		$query="SELECT * FROM user_blocked_user WHERE ((r_user_id='".$required_user."' AND user_id='$user_id') OR (r_user_id='".$user_id."' AND user_id='".$required_user."')) AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
function canConnectToUser($user_id="",$required_user="",$type="connect")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($type=="connect" || $type=="")
	{
		if($user_id==$required_user || $user_id=="" || $required_user=="")
		{
			return false;
		}
		else
		{
			if(noBlocking($user_id,$required_user))
			{
				$query="SELECT * FROM users_privacy_settings WHERE setting_term='who_can_connect_option' AND user_id='$required_user'";
				$result=mysqli_query($CONNECT,$query);
				if(mysqli_num_rows($result)>0)
				{
					$row=mysqli_fetch_array($result);
					$who_can_connect_option=$row['setting_value'];//who_can_connect_option
					if($who_can_connect_option==1)
					{
						return true;
					}
					else if($who_can_connect_option==0)
					{
						return false;
					}
					else if($who_can_connect_option==2)
					{
						$query="SELECT * FROM user_joins_user WHERE ((user_id='$required_user' AND r_user_id='$user_id') OR (user_id='$user_id' AND r_user_id='$required_user')) AND status=1";
						$result=mysqli_query($CONNECT,$query);
						if(mysqli_num_rows($result)>0)
						{
							return true;
						}
						else
						{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
	}
	else if($type=="follow")
	{
		if($user_id==$required_user)
		{
			return false;
		}
		else
		{
			if(noBlocking($user_id,$required_user))
			{
				$query="SELECT * FROM users_privacy_settings WHERE setting_term='who_can_follow_option' AND user_id='$required_user'";
				$result=mysqli_query($CONNECT,$query);
				if(mysqli_num_rows($result)>0)
				{
					$row=mysqli_fetch_array($result);
					$who_can_follow_option=$row['setting_value'];
					if($who_can_follow_option=="1")
					{
						return true;
					}
					else if($who_can_follow_option=="0")
					{
						return false;
					}
					else if($who_can_follow_option=="2")
					{
						return false;
					}
					else
					{
						$query="SELECT * FROM user_joins_user WHERE ((user_id='$required_user' AND r_user_id='$user_id') OR (user_id='$user_id' AND r_user_id='$required_user')) AND status=1";
						$result=mysqli_query($CONNECT,$query);
						if(mysqli_num_rows($result)>0)
						{
							return true;
						}
						else
						{
							return false;
						}
					}
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}
function canSeeThisPost($user_id="",$post_id="",$type="post")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	
	if($type=="post" || $type=="")
	{
		$query="SELECT * FROM users_posts WHERE id='$post_id' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			if($user_id==$row['user_id'])
			{
				return true;
			}
			else
			{
				$who_can_see_broadcast_post_option_post=$row['is_public'].",".$row['is_private'].",".$row['is_protected'].",".$row['is_friendly_protected'].",".$row['is_magic'];
				if($who_can_see_broadcast_post_option_post=="0,1,0,0,0")
				{
					return false;
				}
				else if($who_can_see_broadcast_post_option_post=="0,0,1,0,1")
				{
					$users_allowed=$row['users_allowed'];
					$users_allowed_arr=explode(",",$users_allowed);
					if(in_array($user_id,$users_allowed_arr))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else if($who_can_see_broadcast_post_option_post=="0,0,1,0,2")
				{
					$users_blocked=$row['users_blocked'];
					$users_blocked_arr=explode(",",$users_blocked);
					if(!in_array($user_id,$users_blocked_arr))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			return false;
		}
	}
	else if($type=="blog_space")
	{
		$query="SELECT * FROM blog_spaces WHERE id='$post_id' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			if($user_id==$row['user_id'])
			{
				return true;
			}
			else
			{
				$who_can_see_broadcast_post_option_post=$row['is_public'].",".$row['is_private'].",".$row['is_protected'].",".$row['is_friendly_protected'].",".$row['is_magic'];
				if($who_can_see_broadcast_post_option_post=="0,1,0,0,0")
				{
					return false;
				}
				else if($who_can_see_broadcast_post_option_post=="0,0,1,0,1")
				{
					$users_allowed=$row['users_allowed'];
					$users_allowed_arr=explode(",",$users_allowed);
					if(in_array($user_id,$users_allowed_arr))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else if($who_can_see_broadcast_post_option_post=="0,0,1,0,2")
				{
					$users_blocked=$row['users_blocked'];
					$users_blocked_arr=explode(",",$users_blocked);
					if(!in_array($user_id,$users_blocked_arr))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			return false;
		}
	}
	else if($type=="blog_space_post")
	{
		$query="SELECT * FROM blog_space_posts WHERE id='$post_id' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			if($user_id==$row['user_id'])
			{
				return true;
			}
			else
			{
				$who_can_see_broadcast_post_option_post=$row['is_public'].",".$row['is_private'].",".$row['is_protected'].",".$row['is_friendly_protected'].",".$row['is_magic'];
				if($who_can_see_broadcast_post_option_post=="0,1,0,0,0")
				{
					return false;
				}
				else if($who_can_see_broadcast_post_option_post=="0,0,1,0,1")
				{
					$users_allowed=$row['users_allowed'];
					$users_allowed_arr=explode(",",$users_allowed);
					if(in_array($user_id,$users_allowed_arr))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else if($who_can_see_broadcast_post_option_post=="0,0,1,0,2")
				{
					$users_blocked=$row['users_blocked'];
					$users_blocked_arr=explode(",",$users_blocked);
					if(!in_array($user_id,$users_blocked_arr))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			return false;
		}
	}
}
function print_user_link($name,$username)
{
	return "<a href='".base_url."u/".$username."'>".$name."</a>";
}
function userBlocked($window_user_id)
{
	/*it checks if user is blocked by anyside of the bridge*/
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM user_blocked_user WHERE ((r_user_id='".$_COOKIE['uid']."' AND user_id='$window_user_id') OR (r_user_id='".$window_user_id."' AND user_id='".$_COOKIE['uid']."')) AND status=1";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function getNotification($notification_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM threats_to_user WHERE id='$notification_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$class="unseen-notification";
		if($row['seen']==1)
		{
			$class="seen-notification";
		}
		return '<a class="dropdown-item d-flex align-items-center '.$class.'" title="'.$row['heading'].'" href="'.base_url.$row['redirect_url_segment'].'&nthread='.md5($notification_id).'">
				<div class="mr-3">
				   <div class="icon-circle bg-success">
					  <i class="feather-edit text-white"></i>
				   </div>
				</div>
				<div>
				   <div class="small text-gray-500">'.date('M d, Y',strtotime($row['added'])).'</div>
				   '.$row['heading'].'
				</div>
			 </a>';
	}
	else{
		return "";
	}
	
}
function getNotificationPageNotification($notification_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM threats_to_user WHERE id='$notification_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$userData=getUsersData($row['user_id']);
		$class="unseen-notification";
		if($row['seen']==1)
		{
			$class="seen-notification";
		}
		$html='<a href="'.base_url.$row['redirect_url_segment'].'&nthread='.md5($notification_id).'">
		<div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header '.$class.'">
			<div class="dropdown-list-image mr-3">
				<img class="rounded-circle" src="'.getUserProfileImage($row['user_id']).'" alt="'.ucwords(strtolower($userData['first_name']." ".$userData['last_name'])).'">
				<div class="status-indicator bg-success"></div>
			</div>
			<div class="font-weight-bold" style="width:100%;">
				<div class="row">
					<div class="col-md-10">
						<div class="text-truncate">'.$row['heading'].'</div>
					</div>
					<div class="col-md-2">
						<span style="float:right;font-size:10px;">'.date("M d, Y",strtotime($row['added'])).'</span>
					</div>
				</div>';
			if($earlier_row['heading']!=$earlier_row['message'])
			{
				$html.='<div class="small">'.$row['message'].'</div>';
			}
				$html.='</div>
			</div>
		</a>';
		return $html;
	}
	else{
		return "";
	}
}
function getSubscribedCategories($user_id="")
{
	$subscribed=array();
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT category FROM users_blog_category WHERE user_id='$user_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		while($row=mysqli_fetch_array($result))
		{
			$subscribed[]=$row['category'];
		}
	}
	return $subscribed;
}
function outputFileToLocation($base64_string="",$output_file="")
{
	if($base64_string!="" && $output_file!="")
	{
		$ifp = @fopen($output_file, 'wb'); 
		$data = explode(',',$base64_string);
		@fwrite($ifp, base64_decode($data[1]));
		@fclose($ifp);
		return 	true;
	}
	else
	{
		return false;
	}
}
function loading($slug="message")
{
	if($slug=="message")
	{
		return '<style>
			#cover-spin {
				position:absolute;
				width:100%;
				left:50;right:50;top:0;bottom:0;
				background-color: rgba(255,255,255,0.7);
				z-index:9999;
			}

			@-webkit-keyframes spin {
				from {-webkit-transform:rotate(0deg);}
				to {-webkit-transform:rotate(360deg);}
			}

			@keyframes spin {
				from {transform:rotate(0deg);}
				to {transform:rotate(360deg);}
			}

			#cover-spin::after {
				content:"";
				display:block;
				position:absolute;
				left:48%;top:40%;
				width:20px;height:20px;
				border-style:solid;
				border-color:black;
				border-top-color:transparent;
				border-width: 4px;
				border-radius:50%;
				-webkit-animation: spin .8s linear infinite;
				animation: spin .8s linear infinite;
			}
		</style>
		<div id="cover-spin"></div>';
	}
}
function isDeservingBlogSpace($user_id="",$space_id="")
{
	if($space_id=="" || $user_id=="")
	{
		return false;
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM blog_spaces WHERE id='$space_id' AND user_id='$user_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function isDeserving($user_id="",$post_id="")
{
	if($post_id=="" || $user_id=="")
	{
		return false;
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM blog_space_posts WHERE id='$post_id' AND user_id='$user_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function isDeservingPostCommentUser($user_id="",$comment_id="",$table="")
{
	if($table=="")
	{
		$table="users_blog_post_comments";
	}
	if($comment_id=="" || $user_id=="")
	{
		return false;
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM ".$table." WHERE id='$comment_id' AND user_id='$user_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function subscribedCategories($user_id="")
{
	if($user_id=="")
	{
		return false;
	}
	else
	{
		$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
		$query="SELECT * FROM users_blog_category WHERE user_id='$user_id'";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			return true;
		}
		return false;
	}
}
function isValidSpace($space_url="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($space_url=="")
	{
		return false;
	}
	else
	{
		$query="SELECT * FROM blog_spaces WHERE space_url='$space_url' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
function isValidSpacePost($post_url="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($post_url=="")
	{
		return false;
	}
	else
	{
		$query="SELECT * FROM blog_space_posts WHERE post_url='$post_url' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
function getBlogSpaceProfileImage($space_url="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM blog_spaces WHERE space_url='$space_url' AND status=1";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$space_image=$row['space_image'];
		if($space_image=="")
		{
			$title=$row['title'];
			$sub_str=strtolower(substr($title,0,1));
			return base_url.'alphas/'.$sub_str.'.png';
		}
		else
		{
			return base_url.$space_image;
		}
	}
	else
	{
		return base_url.'alphas/a.png';
	}
}
function putTag($string_tag)
{
	$string_tag_modified=str_replace(" ","_",$string_tag);
	return '<span class="badge badge-warning" style="padding:10px;margin-top:10px;">#'.$string_tag_modified.'</span>&nbsp;&nbsp;&nbsp;&nbsp;';
}
function putKeyword($string_keyword)
{
	return '<span class="badge badge-primary" style="padding:10px;margin-top:10px;">'.$string_keyword.'</span>&nbsp;&nbsp;&nbsp;&nbsp;';
}
function getBlogSpacePostProfileImage($post_url="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM blog_space_posts WHERE post_url='$post_url' AND status=1";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$post_image=$row['post_image'];
		if($post_image=="")
		{
			$title=$row['title'];
			$sub_str=strtolower(substr($title,0,1));
			return base_url.'alphas/'.$sub_str.'.png';
		}
		else
		{
			return base_url.$post_image;
		}
	}
	else
	{
		return base_url.'alphas/a.png';
	}
}
function getBlogSpace($space_url="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($space_url=="")
	{
		return false;
	}	
	else
	{
		$query="SELECT * FROM blog_spaces WHERE space_url='$space_url' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			return $row;
		}
		else
		{
			return false;
		}
	}
}
function getBlogSpacePost($post_url="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($post_url=="")
	{
		return false;
	}	
	else
	{
		$query="SELECT * FROM blog_space_posts WHERE post_url='$post_url' AND status=1";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			return $row;
		}
		else
		{
			return false;
		}
	}
}
function uploadImageToDir($image_field_name,$target_dir="uploads/")
{
  $file_name=time()."-".str_replace(" ","-",basename($_FILES["".$image_field_name]["name"]));
  $target_file = $target_dir . $file_name;
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["".$image_field_name]["tmp_name"]);
  if($check !== false) {
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      return array(false);
    }
    else
    {
      if (move_uploaded_file($_FILES["".$image_field_name]["tmp_name"], $target_file)) 
      {
        return array(true,$file_name,$target_file);
      }
      else
      {
         return array(false);
      }
    }
  } 
  else
  {
     return array(false);
  }
}
function userHasBlogs($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$blog_query="SELECT * FROM blog_spaces WHERE user_id='$user_id'";
	$blog_result=mysqli_query($CONNECT,$blog_query);
	if(mysqli_num_rows($blog_result)>0)
	{
		return true;
	}
	return false;
}
function getUserBlogNavigationHtml($user_id)
{
	$html="";
	$html.=createBlogNavigationHtml($user_id);
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$blog_query="SELECT * FROM blog_spaces WHERE user_id='$user_id' ORDER BY id DESC";
	$blog_result=mysqli_query($CONNECT,$blog_query);
	if(mysqli_num_rows($blog_result)>0)
	{
		while($blog_row=mysqli_fetch_array($blog_result))
		{
			$blog_space_category_id=$blog_row['category'];
			$blog_space_category="";
			$blog_space_category_query="SELECT * FROM blog_space_categories WHERE id='$blog_space_category_id'";
			$blog_space_category_result=mysqli_query($CONNECT,$blog_space_category_query);
			if(mysqli_num_rows($blog_space_category_result)>0)
			{
				$blog_space_category_row=mysqli_fetch_array($blog_space_category_result);
				$blog_space_category=$blog_space_category_row['title'];
			}
			$space_url=$blog_row['space_url'];
			$html.='<div class="col-md-12 border-bottom">
				<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1"><a href="'.blog_space_url.$space_url.'"><b>'.$blog_row['title'].'</b> in '.$blog_space_category.'</a></h6>
			</div>';
		}
	}
	
	return $html;
}
function createBlogNavigationHtml($user_id="")
{
	$html='<div class="col-md-12 border-bottom">
		<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1"><a href="'.blog_space_url.'create-blog-space">Create New Blog Space&nbsp;&nbsp;<i class="fa fa-pencil pull-right"></i></a></h6>
	</div>';
	return $html;
}
function getBlogSpaceFeeds($user_id,$start=0,$limit=10)
{
	
}
function getPagePostMedia($page_post_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT * FROM page_posts_media WHERE page_post_id='$page_post_id' AND status=1";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$page_post_media=array();
		while($_row=mysqli_fetch_array($_result))
		{
			$page_post_media[]=$_row;
		}
		return $page_post_media;
	}
	else
	{
		return false;
	}
}
function getPagePostHtml($page_post_id) 
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT * FROM page_posts WHERE id='$page_post_id'";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		$user_id=$_row['user_id'];
		$user_data=getUsersData($user_id);
		//$user_personal_data=getUsersPersonalData($user_id);
		$user_profile_image=getUserProfileImage($user_id);
		$last_designation=getUsersCurrentDesignation($user_id);
		$last_education=getUsersCurrentEducation($user_id);
		$page_post_html="";
		$page_post_html='<div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post"><div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2" style="padding:10px;">
						<img src="'.$user_profile_image.'" data-image="'.$user_profile_image.'" data-userid="'.$user_id.'" alt="'.$user_data['first_name'].' '.$user_data['last_name'].'" style="height:50px;width:50px;margin-left:15px;" class="rounded-circle">
					</div>
					<div class="col-md-10">
						<h5 style="margin-top:15px;"><a href="'.base_url.'u/'.$user_data['username'].'">'.$user_data['first_name'].' '.$user_data['last_name'].'</a></h5>';
						if($last_designation)
						{
							$page_post_html.='<p style="font-size:12px;margin-top:-15px;"><a href="'.base_url.'u/'.$user_data['username'].'">'.$last_designation.'</a></p>';
						}
						if($last_education)
						{
							$page_post_html.='<p style="font-size:12px;margin-top:-12px;"><a href="'.base_url.'u/'.$user_data['username'].'">'.$last_education.'</a></p>';
						}
						
					$page_post_html.='</div>
				</div>
				<div class="row">';
					if($_row['text_content']!="")
					{
						$page_post_html.='<div class="col-md-12 border-top" style="margin-top:10px;padding-top:10px;">
							<p style="font-size:14px;margin-left:15px;">'.$_row['text_content'].'</p>
						</div>';
					}
					$page_post_media=getPagePostMedia($page_post_id);
					if($page_post_media)
					{
						$page_post_html.='<div class="col-md-12 border-top" style="margin-top:10px;padding-top:10px;"><div class="row" style="padding-left:20px;padding-right:20px;padding-bottom:20px;">';
						$countFinal=count($page_post_media);
						$class_var=($countFinal%3);
						$class="col-md-4";
						if($class_var==1 && $countFinal==1)
						{
							$class="col-md-12";
						}
						else if($class_var==1 && $countFinal>1)
						{
							$class="col-md-4";
						}
						if($class_var==2 && $countFinal==2)
						{
							$class="col-md-6";
						}
						else if($class_var==1 && $countFinal>2)
						{
							$class="col-md-4";
						}
						for($count=0;$count<$countFinal;$count++)
						{
							
							$media_file_data=$page_post_media[$count];
							if($media_file_data['file']!="")
							{
								$page_post_html.='<div class="'.$class.'" style="padding:10px;border-radius:10px;border:2px solid gray;">';
								if(substr($media_file_data['type'],0,5)=="video")
								{
									$page_post_html.='<video controls nodownload="nodownload" style="width:100%;"><source src="'.base_url.$media_file_data['file'].'" type="'.$media_file_data['type'].'"></video>';
								}
								else if(substr($media_file_data['type'],0,5)=="image")
								{
									$page_post_html.='<img src="'.base_url.$media_file_data['file'].'" data-image="'.base_url.$media_file_data['file'].'" data-userid="'.$user_id.'" style="width:100%;">';
								}	
								$page_post_html.='</div>';
							}
						}
						$page_post_html.='</div></div>';
					}
				$page_post_html.='</div>';
				$page_post_html.='<div class="row" style="padding:10px;">
					<div class="col-md-12 border-top">
						<div class="row">';
							if(pagePostLiked($user_id,$page_post_id))
							{
								$page_post_html.='<div class="12" style="margin-top:10px;margin-left:10px;">';
								if(isLogin())
								{
									$page_post_html.='<button class="btn btn-danger"><i class="fa fa-thumbs-down"></i>&nbsp;Unlike</button>&nbsp;&nbsp;You and ';
								}
								$page_post_html.=pagePostLikeCount($page_post_id).'&nbsp;others likes
								</div>';
							}
							else 
							{
								$page_post_html.='<div class="12" style="margin-top:10px;margin-left:10px;">';
								if(isLogin())
								{
									$page_post_html.='<button class="btn btn-primary"><i class="fa fa-thumbs-up"></i>&nbsp;Like</button>&nbsp;&nbsp;';
								}
								$page_post_html.=pagePostLikeCount($page_post_id).'&nbsp;likes
								</div>';
							}
						$page_post_html.='</div>
					</div>
				</div>';
				if(isLogin())
				{
					$loggedin_user_id=$_COOKIE['uid'];
					$logged_in_user_data=getUsersData($loggedin_user_id);
					$page_post_html.='<div class="row" style="padding:10px;">
						<div class="col-md-12 border-top">
							<div class="row">
								<div class="col-md-2" style="padding:10px;">
									<img src="'.$user_profile_image.'" title="'.$logged_in_user_data['first_name'].' '.$logged_in_user_data['last_name'].'" alt="'.$logged_in_user_data['first_name'].' '.$logged_in_user_data['last_name'].'" style="height:50px;width:50px;margin-left:15px;cursor:pointer;" class="rounded-circle">
								</div>
								<div class="col-md-8" style="padding:10px;">
									<input type="text" name="post_comment" class="form-control" id="post_comment_'.$page_post_id.'" placeholder="Write your comment here" style="margin-top:10px;">
								</div>
								<div class="col-md-2" style="padding:10px;">
									<buttton class="btn btn-success" style="float:right;margin-top:10px;">Post</button>
								</div>
							</div>
						</div>
					</div>';
				}
			$page_post_html.='</div>
		</div></div>';
		return $page_post_html;
	}
	else
	{
		return "";
	}
}
function pagePostLiked($user_id,$page_post_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM page_posts_like WHERE user_id='$user_id' AND page_post_id='$page_post_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function pagePostLikeCount($page_post_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM page_posts_like WHERE page_post_id='$page_post_id'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getBegginerJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND (min_exp=0 OR min_exp='')";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getIntermediateJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND (min_exp>0 OR min_exp<=10)";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getExpertJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND (min_exp>10)";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
#=================================================================
function getFreelanceJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND employment_type='CONTRACT'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getFullTimeJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND employment_type='FULL_TIME'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getInternshipJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND employment_type='INTERNSHIP'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}

function getPartTimeJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND employment_type='PART_TIME'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getTemporaryJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND employment_type='TEMPORARY'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getVolunteerJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND employment_type='VOLUNTEER'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
#=================================================================
function getImmediateJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND notice_period='Immediate'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
function getLaterJobCount()
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT id FROM jobs WHERE status=1 AND notice_period!='Immediate'";
	$result=mysqli_query($CONNECT,$query);
	return mysqli_num_rows($result);
}
#=================================================================
/*-----------Manage User Activeness ----------*/
function getIPAddress() {  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }   
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  
function getLatLong($ip_address)
{
	$access_key = '4985d1432ad4a137720f0660bf48267d';
	$ch = curl_init('http://api.ipstack.com/'.$ip_address.'?access_key='.$access_key.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close($ch);
	$api_result = json_decode($json, true);
	return $api_result;
}
function distance_between_points_on_earth($latitudeFrom=0, $longitudeFrom=0, $latitudeTo=0,  $longitudeTo=0) 
{ 
	if($latitudeFrom=="" || $latitudeFrom==null || $latitudeFrom=="NULL")
	{
		$latitudeFrom=0;
	}
	if($longitudeFrom=="" || $longitudeFrom==null || $longitudeFrom=="NULL")
	{
		$longitudeFrom=0;
	}
	if($latitudeTo=="" || $latitudeTo==null || $latitudeTo=="NULL")
	{
		$latitudeTo=0;
	}
	if($longitudeTo=="" || $longitudeTo==null || $longitudeTo=="NULL")
	{
		$longitudeTo=0;
	}
	if(($longitudeTo==$longitudeFrom) && ($latitudeFrom==$latitudeTo))
	{
		return mt_rand(0,50);
	}
   $long1 = deg2rad($longitudeFrom); 
   $long2 = deg2rad($longitudeTo); 
   $lat1 = deg2rad($latitudeFrom); 
   $lat2 = deg2rad($latitudeTo); 
	  
   //Haversine Formula 
   $dlong = $long2 - $long1; 
   $dlati = $lat2 - $lat1; 
	  
   $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2); 
	  
   $res = 2 * asin(sqrt($val)); 
	  
   $radius = 3958.756; 
	  
   return ($res*$radius)*1.609344*1000; 
}
function sendOTP($mobile_email,$otp,$country_code,$otp_type='mobile')
{
	$country_code=trim($country_code);
	$mobile_email=trim($mobile_email);
	$auth_key="347898AXXW3bQR5fbe1320P1";
	$sender_id="RYMSGS";
	$template_id="5fbe1a3d12254379e112904c";
	$mobile_number_with_country_code=$country_code.$mobile_email;
	//$otp=4986;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=".$auth_key."&template_id=".$template_id."&mobile=".$mobile_number_with_country_code."&country=0&otp=".$otp,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_SSL_VERIFYHOST => 0,
	  CURLOPT_SSL_VERIFYPEER => 0,
	  CURLOPT_HTTPHEADER => array(
		"content-type: application/json"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  return false;
	} else {
		$res=json_decode($response,TRUE);
		if($res['type']=="success")
		{
			return true;
		}
		else{
			return false;
		}
	}
	/*$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "http://2factor.in/API/V1/0bc7eee2-1ea7-11eb-b380-0200cd936042/SMS/".$country_code.$mobile_email."/".$random,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS => "",
		CURLOPT_HTTPHEADER => array("content-type: application/x-www-form-urlencoded"),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		return false;
	} else {
		$res=json_decode($response,TRUE);
		//print_r($res);
		if($res['Status']=="Error")
		{
			return false;
		}
		else{
			return true;
		}
	}*/
}
if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
{
	$user_id=$_COOKIE['uid'];
	$ip_address=getIPAddress();
	mysqli_query($conn,"UPDATE users SET ip_address='$ip_address',location_updated=NOW() WHERE id='$user_id' AND location_update NOT LIKE '%DATE(NOW())%'");
	$lat_long=getLatLong($ip_address);
	$lattitude=$lat_long['latitude'];
	$longitude=$lat_long['longitude'];
	if($lattitude!="" && $longitude!="")
	{
		mysqli_query($conn,"UPDATE users SET lattitude='$lattitude',longitude='$longitude' WHERE id='$user_id' AND location_update NOT LIKE '%DATE(NOW())%'");
	}
}
function userActive($user_id="")
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($user_id=="")
	{
		return false;
	}
	$query="SELECT * FROM users WHERE id='$user_id' AND status!=2 and status!=3";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return true;
	}
	return false;
}
function getCommonPersonsOnJob($job_id,$loggedin_user_id)
{
	echo '<div class="d-flex align-items-center p-3 border-top border-bottom job-item-body">
			 <!--<div class="overlap-rounded-circle d-flex">
				<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="Sophia Lee" src="'.base_url.'img/p1.png" alt="">
				<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="John Doe" src="'.base_url.'img/p2.png" alt="">
				<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="Julia Cox" src="'.base_url.'img/p3.png" alt="">
				<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="Robert Cook" src="'.base_url.'img/p4.png" alt="">
				<img class="rounded-circle shadow-sm" data-toggle="tooltip" data-placement="top" title="Sophia Lee" src="'.base_url.'img/p5.png" alt="">
			 </div>-->
			 <span class="font-weight-bold text-primary">0 connections</span>
		  </div>';
}
mysqli_query($conn,"UPDATE users_logs SET is_active=0 WHERE is_active=1 AND TIMESTAMPDIFF(MINUTE,added,NOW())>5");
function showVideo($video)
{
	if($video=="")
	{
		//return $GLOBALS['BASEURL']."uploads/".$video;
		return $video;
	}
	else
	{
		$video_arr=explode("ropeyou.com",$video);
		if(count($video_arr)=="2")
		{
			//return $GLOBALS['BASEURL']."uploads/".$video;
			return $video;
		}
		else
		{
			return $video;
		}
	}
}
if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
{
	$uid=$_COOKIE['uid'];
	$logquery="SELECT * FROM users_logs WHERE user_id='$uid'";
	$logresult=mysqli_query($conn,$logquery);
	if(mysqli_num_rows($logresult)>0)
	{
		mysqli_query($conn,"UPDATE users_logs SET is_active=1,added=NOW() WHERE user_id='$uid'");
	}
	else
	{
		mysqli_query($conn,"INSERT INTO users_logs SET is_active=1,user_id='$uid',added=NOW()");
	}
}
/*-----------End Manage User Activeness ----------*/

function sendInviteEmail($user_id,$email_id)
{
	
}
function generateUniqueCompanyUsername($username)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$username=trim($username);
	if($username=="")
	{
		$username="company_".mt_rand(1,10000);
	}
	$username_to_test=$username;
	$numRows=1;
	$counter=1;
	while($numRows>0)
	{
		$query="SELECT * FROM companies WHERE username='$username_to_test'";
		$result=mysqli_query($CONNECT,$query);
		$numRows=mysqli_num_rows($result);
		if($numRows>0)
		{
			$username_to_test=$username."_".$counter++;
		}
	}
	return $username_to_test;
}
function doIFollow($follower_id="",$user_id=""){
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($follower_id=="" || $user_id=="")
	{
		return false;
	}
	else if($follower_id==$user_id)
	{
		return true;
	}
	else
	{
		$following_query="SELECT * FROM followers WHERE follower_id='".$follower_id."' AND user_id='".$user_id."' AND status=1";
		$following_result=mysqli_query($CONNECT,$following_query);
		if(mysqli_num_rows($following_result)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
function generateUniqueUserName($email)
{
	$username="";
	if($email=="")
	{
		$username=time();
	}
	else
	{
		$email_arr=explode("@",$email);
		$username=$email_arr[0];
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$username=trim($username);
	$username = preg_replace('/[^\w.]/', '', $username);
	$username_to_test=$username;
	$numRows=1;
	$counter=1;
	while($numRows>0)
	{
		$query="SELECT * FROM users WHERE username='$username_to_test' OR email='$username_to_test' OR mobile='$username_to_test'";
		$result=mysqli_query($CONNECT,$query);
		$numRows=mysqli_num_rows($result);
		if($numRows>0)
		{
			$username_to_test=time();
		}
	}
	return $username_to_test;
}
function generateUniquePageUserName($page_title,$email="")
{
	$page_title=strtolower(str_replace(" ","_",$page_title));
	$username=$page_title;
	
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$username=trim($username);
	$username = preg_replace('/[^\w.]/', '', $username);
	$username_to_test=$username;
	$numRows=1;
	$counter=time();
	while($numRows>0)
	{
		$query="SELECT * FROM pages WHERE username='$username_to_test'";
		$result=mysqli_query($CONNECT,$query);
		$numRows=mysqli_num_rows($result);
		if($numRows>0)
		{
			$username_to_test=$username."_".$counter++;
		}
	}
	return $username_to_test;
}
function insertPageLikeNotification($visitor_user_id,$page_id)
{
	
}
function insertPageViewNotification($visitor_user_id,$page_id)
{
	
}
function isLogin()
{
	if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
	{
		return true;
	}
	return false;
}
function getProfilePercentage($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$profile_percentage=0;
	$query="SELECT * FROM users WHERE id='$user_id' AND (status=1 OR status=2)";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$profile_percentage=10;
		$basic_query="SELECT * FROM users_personal WHERE user_id='$user_id'";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$basic_row=mysqli_fetch_array($basic_result);
			if($basic_row['country']!="")
			{
				$profile_percentage=$profile_percentage+2;
			}
			if($basic_row['home_town']!="")
			{
				$profile_percentage=$profile_percentage+2;
			}
			if($basic_row['passport']!="")
			{
				$profile_percentage=$profile_percentage+2;
			}
			if($basic_row['relocate_abroad']!="")
			{
				$profile_percentage=$profile_percentage+2;
			}
			if($basic_row['about']!="")
			{
				$profile_percentage=$profile_percentage+2;
			}
		}
		$basic_query="SELECT * FROM users_work_experience WHERE user_id='$user_id'";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$basic_query="SELECT * FROM users_education WHERE user_id='$user_id'";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$basic_query="SELECT * FROM users_skills WHERE user_id='$user_id'";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$basic_query="SELECT * FROM users_interests WHERE user_id='$user_id'";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$basic_query="SELECT * FROM users_resume WHERE user_id='$user_id' AND profile_type=1 AND status=1 AND is_default=1";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$basic_query="SELECT * FROM users_resume WHERE user_id='$user_id' AND (profile_type=3 OR profile_type=2) AND status=1 AND is_default=1";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$basic_query="SELECT * FROM user_joins_user WHERE (r_user_id='$user_id' OR user_id='$user_id') AND status=1";
		$basic_result=mysqli_query($CONNECT,$basic_query);
		if(mysqli_num_rows($basic_result)>0)
		{
			$profile_percentage=$profile_percentage+10;
		}
		$profile_pic=getUserProfileImage($user_id);
		$profile_pic_arr=explode("/",$profile_pic);
		$arr=array("a.png","b.png","c.png","d.png","e.png","f.png","g.png","h.png","i.png","j.png","k.png","l.png","m.png","n.png","o.png","p.png","q.png","r.png","s.png","t.png","u.png","v.png","w.png","x.png","y.png","z.png");
		if(!in_array(end($profile_pic_arr),$arr))
		{
			$profile_percentage=$profile_percentage+10;
		}
	}	
	return $profile_percentage;
}
function getOnBoarding($user_id,$skipped)
{
	$onboarding="dashboard";
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	
	$users_query="SELECT * FROM users WHERE id='$user_id'";
	$users_result=mysqli_query($CONNECT,$users_query);
	if(mysqli_num_rows($users_result)>0)
	{
		$users_row=mysqli_fetch_array($users_result);
		switch($skipped){
			case "0": 	$basic_query="SELECT * FROM users_personal WHERE user_id='$user_id'";
						$basic_result=mysqli_query($CONNECT,$basic_query);
						if(mysqli_num_rows($basic_result)>0)
						{
							$basic_row=mysqli_fetch_array($basic_result);
							if($basic_row['country']=="" || $basic_row['home_town']=="" || $basic_row['passport']==""  || $basic_row['relocate_abroad']=="" || $basic_row['about']=="")
							{
								$onboarding="bio";
							}
							else
							{
								$basic_query="SELECT * FROM users_work_experience WHERE user_id='$user_id'";
								$basic_result=mysqli_query($CONNECT,$basic_query);
								if(mysqli_num_rows($basic_result)>0)
								{
									$basic_query="SELECT * FROM users_education WHERE user_id='$user_id'";
									$basic_result=mysqli_query($CONNECT,$basic_query);
									if(mysqli_num_rows($basic_result)>0)
									{
										$basic_query="SELECT * FROM users_skills WHERE user_id='$user_id'";
										$basic_result=mysqli_query($CONNECT,$basic_query);
										if(mysqli_num_rows($basic_result)>0)
										{
											$basic_query="SELECT * FROM users_resume WHERE user_id='$user_id'";
											$basic_result=mysqli_query($CONNECT,$basic_query);
											if(mysqli_num_rows($basic_result)>0)
											{
												$profile_pic=getUserProfileImage($user_id);
												$profile_pic_arr=explode("/",$profile_pic);
												$arr=array("a.png","b.png","c.png","d.png","e.png","f.png","g.png","h.png","i.png","j.png","k.png","l.png","m.png","n.png","o.png","p.png","q.png","r.png","s.png","t.png","u.png","v.png","w.png","x.png","y.png","z.png");
												if(in_array(end($profile_pic_arr),$arr))
												{
													$onboarding="profile_pic";
												}
												else if($users_row['status']=="0")
												{
													$onboarding="email_verification";
												}
											}
											else
											{
												$onboarding="resume";
											}
										}
										else
										{
											$onboarding="skills";
										}
									}
									else
									{
										$onboarding="education";
									}
								}
								else
								{
									$onboarding="work_experience";
								}
							}
						}
						else
						{
							$onboarding="basic_profile";
						}break;
			case "1": 	$basic_query="SELECT * FROM users_work_experience WHERE user_id='$user_id'";
						$basic_result=mysqli_query($CONNECT,$basic_query);
						if(mysqli_num_rows($basic_result)>0)
						{
							$basic_query="SELECT * FROM users_education WHERE user_id='$user_id'";
							$basic_result=mysqli_query($CONNECT,$basic_query);
							if(mysqli_num_rows($basic_result)>0)
							{
								$basic_query="SELECT * FROM users_skills WHERE user_id='$user_id'";
								$basic_result=mysqli_query($CONNECT,$basic_query);
								if(mysqli_num_rows($basic_result)>0)
								{
									if($users_row['status']=="0")
									{
										$onboarding="email_verification";
									}
								}
								else
								{
									$onboarding="skills";
								}
							}
							else
							{
								$onboarding="education";
							}
						}
						else
						{
							$onboarding="work_experience";
						}break;
			case "2": 	$basic_query="SELECT * FROM users_education WHERE user_id='$user_id'";
						$basic_result=mysqli_query($CONNECT,$basic_query);
						if(mysqli_num_rows($basic_result)>0)
						{
							$basic_query="SELECT * FROM users_skills WHERE user_id='$user_id'";
							$basic_result=mysqli_query($CONNECT,$basic_query);
							if(mysqli_num_rows($basic_result)>0)
							{
								if($users_row['status']=="0")
								{
									$onboarding="email_verification";
								}
							}
							else
							{
								$onboarding="skills";
							}
						}
						else
						{
							$onboarding="education";
						}break;
			case "3": 	$basic_query="SELECT * FROM users_skills WHERE user_id='$user_id'";
						$basic_result=mysqli_query($CONNECT,$basic_query);
						if(mysqli_num_rows($basic_result)>0)
						{
							if($users_row['status']=="0")
							{
								$onboarding="email_verification";
							}
						}
						else
						{
							$onboarding="skills";
						}break;
			case "4": 	if($users_row['status']=="0")
						{
							$onboarding="email_verification";
						}break;
			default:$onboarding="broadcasts";break;
		}
	}
	else
	{
		$onboarding="session_expired";
	}
	return $onboarding;
}
function getWishListStatusByJobID($job_id,$user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM job_cart WHERE job_id='$job_id' AND user_id='$user_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return mysqli_fetch_array($result);
	}
	else
	{
		return false;
	}
}
function getResumeByID($file_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT * FROM gallery WHERE id='$file_id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		return mysqli_fetch_array($result);
	}
	else
	{
		return false;
	}
}
function getDesignationByID($id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT title FROM designations WHERE id='$id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		return $row['title'];
	}
	else
	{
		return false;
	}
}
function getCompanyByID($id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT title FROM companies WHERE id='$id'";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		return $row['title'];
	}
	else
	{
		return false;
	}
}
function getUsersCurrentDesignation($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT title,company FROM users_work_experience WHERE user_id='$user_id' ORDER BY from_year DESC";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$designation=$row['title'];
		$company=$row['company'];
		if($designation && $company)
		{
			return $designation." at ".$company;
		}
		else
		{
			return "";
		}
	}
	else
	{
		return "";
	}
}
function getUsersCurrentEducation($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$query="SELECT title,major,university FROM users_education WHERE user_id='$user_id' ORDER BY from_year DESC";
	$result=mysqli_query($CONNECT,$query);
	if(mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$designation=$row['title'];
		$university=$row['university'];
		$major=$row['major'];
		if($designation && $university)
		{
			$html=$designation;
			if($major!='')
			{
				$html.=' in '.$major;
			}
			$html.=" from ".$university;
			return $html;
		}
		else
		{
			return "";
		}
	}
	else
	{
		return "";
	}
}
function companies($id=false,$title=false,$city=false,$image=false,$added=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	if($city)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="city='$city'";
	}
	if($image)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="image='$image'";
	}
	if($added)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="added='$added'";
	}
	$_query="SELECT * FROM companies".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_companies=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_companies[]=$_row;
		}
	}
	return $_companies;
}
function universities($id=false,$title=false,$city=false,$image=false,$added=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	if($city)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="city='$city'";
	}
	if($image)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="image='$image'";
	}
	if($added)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="added='$added'";
	}
	$_query="SELECT * FROM universities".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_companies=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_companies[]=$_row;
		}
	}
	return $_companies;
}

function city($id=false,$title=false,$state=false,$country=false,$status=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	if($state)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="state='$state'";
	}
	if($country)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="country='$country'";
	}
	if($status)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="status='$status'";
	}
	$_query="SELECT * FROM city".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_city=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_city[]=$_row;
		}
	}
	return $_city;
}
function getCityByID($city_id)
{
	if($city_id=="")
	{
		return "NA";
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_result=mysqli_query($CONNECT,"SELECT * FROM city WHERE id='$city_id'");
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		return $_row['title'];
	}
	return "NA";
}
function getCountryByID($country_id)
{
	if($country_id=="")
	{
		return "NA";
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_result=mysqli_query($CONNECT,"SELECT * FROM country WHERE id='$country_id'");
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		return $_row['title'];
	}
	return "NA";
}
function getCompanyCategoryByID($category_id)
{
	if($category_id=="")
	{
		return "NA";
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_result=mysqli_query($CONNECT,"SELECT * FROM company_categories WHERE id='$category_id'");
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		return $_row['title'];
	}
	return "NA";
}
function getCompanyLogo($company_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT image,title FROM companies WHERE id='$company_id'";
	$_result=mysqli_query($CONNECT,$_query);
	$_profile="";
	$_title="";
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		$_profile=$_row['image'];
		$_title=$_row['title'];
	}
	if (strpos($_profile, 'http') !== false) {
		
	}
	else
	{
		if($_profile=="")
		{
			if($_title!="")
			{
				$user_row=mysqli_fetch_array($_user_result);
				$_profile=base_url."alphas/".strtolower(substr($_title,0,1)).".png";
			}
			else
			{
				$_profile="default.jpg";
				$_profile=base_url."uploads/".$_profile;
			}
		}
		else{
			$_profile=base_url."uploads/".$_profile;
		}
	}
	return $_profile;
}
function getCompanyBanner($company_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT banner_image,title FROM companies WHERE id='$company_id'";
	$_result=mysqli_query($CONNECT,$_query);
	$_profile="";
	$_title="";
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		$_profile=$_row['banner_image'];
		$_title=$_row['title'];
	}
	if (strpos($_profile, 'http') !== false) {
		
	}
	else
	{
		if($_profile=="")
		{
			$_profile=base_url."uploads/default-company-banner.png";
		}
		else
		{
			$_profile=base_url."uploads/company/".$company_id."/banner/".$_profile;
		}
	}
	return $_profile;
}
function getCompanyFunctionalAreaByID($functional_area_id)
{
	if($functional_area_id=="")
	{
		return "NA";
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_result=mysqli_query($CONNECT,"SELECT * FROM company_functional_areas WHERE id='$functional_area_id'");
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		return $_row['title'];
	}
	return "NA";
}
function getCompanyTypeByID($company_type)
{
	if($company_type=="")
	{
		return "NA";
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_result=mysqli_query($CONNECT,"SELECT * FROM company_types WHERE id='$company_type'");
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		return $_row['title'];
	}
	return "NA";
}
function state($id=false,$title=false,$country=false,$status=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	
	if($country)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="country='$country'";
	}
	if($status)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="status='$status'";
	}
	$_query="SELECT * FROM state".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_state=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_state[]=$_row;
		}
	}
	return $_state;
}
function country($id=false,$title=false,$code=false,$status=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	
	if($code)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="code='$code'";
	}
	if($status)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="status='$status'";
	}
	$_query="SELECT * FROM country".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_country=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_country[]=$_row;
		}
	}
	return $_country;
}
function professional_skills($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_professional_skills=array();
	$_skills_query="SELECT users_skills.id,users_skills.title,skills.title as skill,skills.icon,users_skills.proficiency AS proficiency FROM users_skills INNER JOIN skills ON skills.id=users_skills.title WHERE users_skills.user_id='".$user_id."' AND users_skills.type=1";
	
	$_skills_result=mysqli_query($CONNECT,$_skills_query);
	if(mysqli_num_rows($_skills_result)>0)
	{
		while($_skills_row=mysqli_fetch_array($_skills_result))
		{
			$_professional_skills[]=$_skills_row;
		}
	}
	else
	{
		$_professional_skills=false;
	}
	return $_professional_skills;
}
function personal_skills($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_professional_skills=array();
	$_skills_query="SELECT users_skills.id,users_skills.title,personal_skills.title as skill,personal_skills.icon,users_skills.proficiency AS proficiency FROM users_skills INNER JOIN personal_skills ON personal_skills.id=users_skills.title WHERE users_skills.user_id='".$user_id."' AND users_skills.type=2";
	
	$_skills_result=mysqli_query($CONNECT,$_skills_query);
	if(mysqli_num_rows($_skills_result)>0)
	{
		while($_skills_row=mysqli_fetch_array($_skills_result))
		{
			$_professional_skills[]=$_skills_row;
		}
	}
	else
	{
		$_professional_skills=false;
	}
	return $_professional_skills;
}
function userLoggedIn($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_response=false;
	$_query="SELECT * FROM users_logs WHERE user_id='$user_id'";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		if($_row['is_active']=="1")
		{
			$_response=true;
		}
		else
		{
			$_response=false;
		}
	}
	return $_response;
}
function is_interview_scheduled($user_id,$user_ref,$job_id,$application_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT * FROM interview_schedules WHERE user_id='$user_id' AND user_ref='$user_ref' AND job_id='$job_id' AND application_id='$application_id'";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		return $_row['room_id'];
	}
	else{
		return false;
	}
}
function getUsersLink($username="",$first_name="",$last_name="",$class="",$style="")
{
	if($username!="" && $first_name!="")
	{
		$anchor='<a href="'.base_url.'u/'.$username.'" class="'.$class.'" style="'.$style.'">'.ucwords(strtolower($first_name." ".$last_name)).'</a>';
		return $anchor;
	}
	else
	{
		return "";
	}
}
function getUsersPersonalData($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_response=false;
	$_query="SELECT * FROM users_personal WHERE user_id='$user_id'";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$_response=mysqli_fetch_array($_result);
	}
	return $_response;
}
function getUsersData($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_response=false;
	$_query="SELECT * FROM users WHERE id='$user_id'";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$_response=mysqli_fetch_array($_result);
	}
	return $_response;
}
function languages($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_professional_skills=array();
	$_skills_query="SELECT users_skills.id,users_skills.title,languages.title as skill,languages.icon,users_skills.proficiency AS proficiency FROM users_skills INNER JOIN languages ON languages.id=users_skills.title WHERE users_skills.user_id='".$user_id."' AND users_skills.type=3";
	
	$_skills_result=mysqli_query($CONNECT,$_skills_query);
	if(mysqli_num_rows($_skills_result)>0)
	{
		while($_skills_row=mysqli_fetch_array($_skills_result))
		{
			$_professional_skills[]=$_skills_row;
		}
	}
	else
	{
		$_professional_skills=false;
	}
	return $_professional_skills;
}
function designations($id=false,$title=false,$stream=false,$status=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	if($stream)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="stream='$stream'";
	}
	if($status)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="status='$status'";
	}
	$_query="SELECT * FROM designations".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_designations=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_designations[]=$_row;
		}
	}
	return $_designations;
}
function courses($id=false,$title=false,$stream=false,$status=false)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$partial="";
	if($id)
	{
		$partial.=" WHERE id='$id'";
	}
	if($title)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="title='$title'";
	}
	if($stream)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="stream='$stream'";
	}
	if($status)
	{
		if($partial=="")
		{
			$partial.=" WHERE ";
		}
		else{
			$partial.=" AND ";
		}
		$partial.="status='$status'";
	}
	$_query="SELECT * FROM courses".$partial;
	$_result=mysqli_query($CONNECT,$_query);
	$_designations=array();
	if(mysqli_num_rows($_result)>0)
	{
		while($_row=mysqli_fetch_array($_result))
		{
			$_designations[]=$_row;
		}
	}
	return $_designations;
}
function getUserProfileImage($user_id="")
{
	if($user_id=="")
	{
		$user_id=$_COOKIE['uid'];
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT media_id FROM users_profile_pics WHERE user_id='$user_id' AND status=1";
	$_result=mysqli_query($CONNECT,$_query);
	$_profile="";
	if(mysqli_num_rows($_result)>0)
	{
		$_row=mysqli_fetch_array($_result);
		$media_id=$_row['media_id'];
		$media_query="SELECT * FROM gallery WHERE id='$media_id'";
		$media_result=mysqli_query($CONNECT,$media_query);
		if(mysqli_num_rows($media_result)>0)
		{
			$media_row=mysqli_fetch_array($media_result);
			$_profile=$media_row['file'];
		}
	}
	if (strpos($_profile, 'http') !== false) {
		
	}
	else
	{
		if($_profile=="")
		{
			$_user_query="SELECT first_name FROM users WHERE id='$user_id'";
			$_user_result=mysqli_query($CONNECT,$_user_query);
			if(mysqli_num_rows($_user_result)>0)
			{
				$user_row=mysqli_fetch_array($_user_result);
				$_profile=base_url."alphas/".strtolower(substr($user_row['first_name'],0,1)).".png";
			}
			else{
				$_profile="default.png";
				$_profile=base_url."uploads/".$_profile;
			}
		}
		else{
			$_profile=base_url.$_profile;
		}
	}
	return $_profile;
}
function getUserConnectionCounts($user_id="")
{
	if($user_id=="")
	{
		$user_id=$_COOKIE['uid'];
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT id FROM user_joins_user WHERE (user_id='".$user_id."' OR r_user_id='".$user_id."') AND status=1 AND blocked=0";
	$_result=mysqli_query($CONNECT,$_query);
	$_myConnections=mysqli_num_rows($_result);
	return $_myConnections;
}
function getUserConnections($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT * FROM user_joins_user WHERE (user_id='".$user_id."' OR r_user_id='".$user_id."') AND status=1 AND blocked=0";
	$_result=mysqli_query($CONNECT,$_query);
	$_myConnections=mysqli_num_rows($_result);
	if($_myConnections>0)
	{
		$_arr=array();
		while($_row=mysqli_fetch_array($_result)){
			if($_row['user_id']==$user_id)
			{
				$_arr[]=$_row['r_user_id'];
			}
			else{
				$_arr[]=$_row['user_id'];
			}
		}
		return $_arr;
	}
	else{
		return $_myConnections;
	}
}
function getUserProfileViews($user_id="")
{
	if($user_id=="")
	{
		$user_id=$_COOKIE['uid'];
	}
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$viewers_query="SELECT * FROM users_profile_views WHERE user_id='".$user_id."' ORDER BY id DESC";
	$viewers_result=mysqli_query($CONNECT,$viewers_query);
	$video_num_rows=mysqli_num_rows($viewers_result);
	return $video_num_rows;
}
function getUserFollowingCounts($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT id FROM user_joins_user WHERE (user_id='".$user_id."' OR r_user_id='".$user_id."') AND (status=1 OR status=4) AND blocked=0";
	$_result=mysqli_query($CONNECT,$_query);
	$_myConnections=mysqli_num_rows($_result);
	return $_myConnections;
}
function getMediaByID($mediaID)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	
	$_profile_query="SELECT * FROM gallery WHERE id='$mediaID'";
	$_profile_result=mysqli_query($CONNECT,$_profile_query);
	$_profile="";
	if(mysqli_num_rows($_profile_result)>0)
	{
		$_profile_row=mysqli_fetch_array($_profile_result);
		$_profile=$_profile_row['title'];
	}
	if (strpos($_profile, 'http') !== false) {
		
	}
	else
	{
		if($_profile=="")
		{
			$_profile="default.png";
		}
		$_profile=base_url."uploads/".$_profile;
	}
	return $_profile;
}
function getMyBridge($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT * FROM user_joins_user WHERE (user_id='$user_id' OR r_user_id='$user_id') AND status=1";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{
		$_arr=array();
		while($_row=mysqli_fetch_array($_result))
		{
			if($user_id==$_row['user_id'])
			{
				$_arr[]=$_row['r_user_id'];
			}
			else
			{
				$_arr[]=$_row['user_id'];
			}
		}
		$_arr[]=$user_id;
		return $_arr;
	}
	else
	{
		return false;
	}
}
function getUserBannerImage($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT banner FROM users WHERE id='$user_id'";
	$_result=mysqli_query($CONNECT,$_query);
	$_row=mysqli_fetch_array($_result);
	
	$_profile_image=$_row['banner'];
	$_profile_query="SELECT * FROM gallery WHERE user_id='$user_id' AND id='$_profile_image'";
	$_profile_result=mysqli_query($CONNECT,$_profile_query);
	$_profile="";
	if(mysqli_num_rows($_profile_result)>0)
	{
		$_profile_row=mysqli_fetch_array($_profile_result);
		$_profile=$_profile_row['title'];
	}
	if (strpos($_profile, 'http') !== false) {
		
	}
	else
	{
		if($_profile=="")
		{
			$_profile="bg.jpg";
		}
		$_profile=base_url."uploads/".$_profile;
	}
	return $_profile;
}
function print_month($month){
	switch($month)
	{
		case "1":return "Jan";break;
		case "2":return "Feb";break;
		case "3":return "Mar";break;
		case "4":return "Apr";break;
		case "5":return "May";break;
		case "6":return "Jun";break;
		case "7":return "Jul";break;
		case "8":return "Aug";break;
		case "9":return "Sep";break;
		case "10":return "Oct";break;
		case "11":return "Nov";break;
		case "12":return "Dec";break;
		case "01":return "Jan";break;
		case "02":return "Feb";break;
		case "03":return "Mar";break;
		case "04":return "Apr";break;
		case "05":return "May";break;
		case "06":return "Jun";break;
		case "07":return "Jul";break;
		case "08":return "Aug";break;
		case "09":return "Sep";break;
		default:return false;break;
	}
}

function getUserResume($user_id)
{
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	$_query="SELECT users_resume.id,users_resume.user_id,users_resume.file,users_resume.resume_headline,users_resume.status,gallery.file as resume_file,gallery.type,gallery.size,gallery.title FROM users_resume INNER JOIN gallery ON users_resume.file=gallery.id WHERE users_resume.user_id='$user_id' AND users_resume.status=1 AND users_resume.file!=''";
	$_result=mysqli_query($CONNECT,$_query);
	if(mysqli_num_rows($_result)>0)
	{	
		$_row=mysqli_fetch_array($_result);
		return $_row;
	}
	return false;
}

$email_html_template='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta name="x-apple-disable-message-reformatting">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="telephone=no" name="format-detection">
		<title></title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet">
	</head>
	<body>
		<div class="es-wrapper-color">
			<table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0">
				<tbody>
					<tr> 
						<td class="esd-email-paddings st-br" valign="top">
							<table cellpadding="0" cellspacing="0" class="es-header esd-header-popover" align="center">
								<tbody>
									<tr>
										<td class="esd-stripe esd-checked" align="center" style="background-image:url(https://fhficc.stripocdn.email/content/guids/CABINET_d21e6d1c5a6807d34e1eb6c59a588198/images/20841560930387653.jpg);background-color: transparent; background-position: center bottom; background-repeat: no-repeat;" bgcolor="transparent" background="https://fhficc.stripocdn.email/content/guids/CABINET_d21e6d1c5a6807d34e1eb6c59a588198/images/20841560930387653.jpg">
											<div style="max-height:80px;">
												<table bgcolor="transparent" class="es-header-body" align="center" cellpadding="0" cellspacing="0" width="600" style="background-color: transparent;">
													<tbody>
														<tr>
															<td class="esd-structure es-p20t es-p20r es-p20l es-p20b" align="left">
																<table cellpadding="0" cellspacing="0" width="100%">
																	<tbody>
																		<tr>
																			<td width="560" class="esd-container-frame" align="center" valign="top">
																				<table cellpadding="0" cellspacing="0" width="100%">
																					<tbody>
																						<tr>
																							<td align="center" class="esd-block-image">
																								<a target="_blank" href="'.base_url.'" style="text-decoration:none;">
																									<h1 style="color:#fff">RopeYou</h1>
																									<h3 style="color:#337ab7;margin-top: -20px;">Watch your success grow</h3>
																								</a>
																							</td>
																						</tr>
																						<tr>
																							<td align="center" class="esd-block-spacer" height="65"></td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
							<table cellpadding="0" cellspacing="0" class="es-content" align="center">
								<tbody>
									<tr>
										<td class="esd-stripe" align="center" bgcolor="transparent" style="background-color: transparent;">
											<table bgcolor="transparent" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="background-color: transparent;">
												<tbody>
													<tr>
														<td class="esd-structure es-p30t es-p15b es-p30r es-p30l" align="left" style="border-radius: 10px 10px 0px 0px; background-color: rgb(255, 255, 255); background-position: left bottom;" bgcolor="#ffffff">
															<table cellpadding="0" cellspacing="0" width="100%">
																<tbody>
																	<tr>
																		<td width="540" class="esd-container-frame" align="center" valign="top">
																			<table cellpadding="0" cellspacing="0" width="100%" style="background-position: left bottom;">
																				<tbody>
																					<tr>
																						<td align="center" class="esd-block-text">
																							<h1>Welcome to the RopeYou!</h1>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
													<tr>
														<td class="esd-structure es-p5t es-p5b es-p30r es-p30l" align="left" style="border-radius: 0px 0px 10px 10px; background-position: left top; background-color: rgb(255, 255, 255);">
															<table cellpadding="0" cellspacing="0" width="100%">
																<tbody>
																	<tr>
																		<td width="540" class="esd-container-frame" align="center" valign="top">
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tbody>
																					<tr>
																						<td align="center" class="esd-block-text">
																							<a href="'.base_url.'verify-email?code=RY-CODE&token=RY-USR&is_page=PAGE-BOOL&page=RY-PAGE" style="text-decoration:none;"><button style="height:35px;width:275px;background-color:#333;margin-bottom:10px;border-radius:5px;color:#fff;font-size:20px;"><b>Click To Confirm Email</b></button></a>
																							<br/><b>OR</b><br/>
																							Use <span style="color:green;font-size:20px;"><b>RY-CODE</b></span> for manual confirmation.
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
													<tr>
														<td class="esd-structure es-p5t es-p5b es-p30r es-p30l" align="left" style="border-radius: 0px 0px 10px 10px; background-position: left top; background-color: rgb(255, 255, 255);">
															<table cellpadding="0" cellspacing="0" width="100%" style="border-top:2px solid #83e837;margin-top:80px;">
																<tbody>
																	<tr>
																		<td width="540" class="esd-container-frame" align="center" valign="top">
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tbody>
																					<tr>
																						<td align="left" class="esd-block-text">
																						   <h2 style="color:#333;margin-left:20px;">Know RopeYou at a Glance</h2> 
																						</td>
																					</tr>
																					<tr>
																						<td align="left" class="esd-block-text">
																							<ul style="margin-top:-10px;">
																								<li>We are creating the &quot;Bridge&quot; between recruiters and job seekers.</li>
																								<li>Connecting Socially</li>
																								<li>We help users to create and represent their Video CV</li>
																								<li>Globalization of jobs and social configurations.</li>
																							</ul>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<table cellpadding="0" cellspacing="0" class="esd-footer-popover es-footer" align="center">
								<tbody>
									<tr>
										<td class="esd-stripe esd-checked" align="center" style="background-image:url(https://fhficc.stripocdn.email/content/guids/CABINET_d21e6d1c5a6807d34e1eb6c59a588198/images/31751560930679125.jpg);background-position: left bottom; background-repeat: no-repeat;">
											<table bgcolor="#31cb4b" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" width="600">
												<tbody>
													<tr>
														<td class="esd-structure" align="left" style="background-position: left top;">
															<table cellpadding="0" cellspacing="0" width="100%">
																<tbody>
																	<tr>
																		<td width="600" class="esd-container-frame" align="center" valign="top">
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tbody>
																					<tr>
																						<td align="center" class="esd-block-spacer" height="40"><h3>&copy; 2019 RopeYou Connects</h3></td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>';
define("email_html",$email_html_template);
define("email_html_1",$email_html_template);
?>