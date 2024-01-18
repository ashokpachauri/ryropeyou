<?php
header("Access-Control-Allow-Origin: ropeyou.in");
date_default_timezone_set('Asia/Kolkata');
define("default_cover_image",'uploads/default-video-cover.jpg');
//echo date("Y-m-d H:i:s");
session_start();
define("DOMAIN","https://ropeyou.in/");
define("DB_HOST","localhost");
define("DB_USERNAME","ropeyou_master_i");
define("DB_PASSWORD","ropeyou_master_in");
define("DB_NAME","ropeyou_master_in");
$conn=mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$conn->set_charset('utf8');
error_reporting(0);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
/*---------------- $window_user_id is $r_user_id------------------------------*/
function getBlogSpaceUrl()
{
	return "https://ropeyou.in/blog-space";
}
define("base_url","https://ropeyou.in/");
define("blog_space_url",getBlogSpaceUrl()."/");
define("blog_base_url","https://ryblogger.ropeyou.com/");
define("interview_url","https://ropeyou.com/enable-x/client/");
define("image_kit","https://ik.imagekit.io/sz9ehps046a/");
define("page_url",base_url.'p/');
define("site_title","RopeYou Connects");
define("blog_image",base_url.'uploads/blank.jpg');
define("blog_post_image",blog_image);
define("fb_app_id", "465307587452391");
define("twitter_share","https://twitter.com/intent/tweet?");
define("CLIENT_ID", "81988qedme6y7f");
define("CLIENT_SECRET", "Z5jo8h3JV3UybHt2");
define("REDIRECT_URI", "https://ropeyou.com/cut/backup/index.php");
define("SCOPE", 'r_basicprofile r_emailaddress');
define("linkedin_client_id","78ii8moox2cfh6");
define("linkedin_client_secret","UOB47rK8Aa4AenOn");
define("linkedin_scope", 'r_basicprofile r_emailaddress');
define("linkedin_redirect_uri", base_url."index.php");

$BASEURL=base_url;
$DBUSERNAME=DB_USERNAME;
$DBPASSWORD=DB_PASSWORD;
$DBNAME=DB_NAME;
$DBHOST=DB_HOST;
function getPrivacySetting($setting_term,$user_id="",$single="")
{
	
	$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
	if($single=="")
	{
		$query="SELECT setting_value FROM users_privacy_settings WHERE setting_term='$setting_term' AND user_id='$user_id'";
		if($user_id=="")
		{
			$query="SELECT setting_value FROM default_privacy_settings WHERE setting_term='$setting_term'";
		}
		else
		{
			setDefaultPrivacyOptionIfNotAlready($user_id);
		}
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$row=mysqli_fetch_array($result);
			return $row['setting_value'];
		}
		else
		{
			return false;
		}
	}
	else
	{
		$query="SELECT * FROM users_privacy_settings WHERE setting_term='$setting_term' AND user_id='$user_id'";
		if($user_id=="")
		{
			$query="SELECT * FROM default_privacy_settings WHERE setting_term='$setting_term'";
		}
		else
		{
			setDefaultPrivacyOptionIfNotAlready($user_id);
		}
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
function setDefaultPrivacyOptionIfNotAlready($user_id="")
{
	if($user_id!="")
	{
		$CONNECT=mysqli_connect($GLOBALS['DBHOST'],$GLOBALS['DBUSERNAME'],$GLOBALS['DBPASSWORD'],$GLOBALS['DBNAME']);
		$query="SELECT * FROM users_privacy_settings WHERE user_id='$user_id'";
		$result=mysqli_query($CONNECT,$query);
		if(mysqli_num_rows($result)>0)
		{
			$query1="SELECT * FROM default_privacy_settings";
			//echo $query1;
			$result1=mysqli_query($CONNECT,$query1);
			$num_rows1=mysqli_num_rows($result1);
			$num_rows=mysqli_num_rows($result);
			if($num_rows==$num_rows1)
			{
				return true;
			}
			else
			{
				while($row1=mysqli_fetch_array($result1))
				{
					$chk_query="SELECT * FROM users_privacy_settings WHERE setting_term='".$row1['setting_term']."' AND user_id='$user_id'";
					$chk_result=mysqli_query($CONNECT,$chk_query);
					if(mysqli_num_rows($chk_result)>0)
					{
						continue;
					}
					else
					{
						mysqli_query($CONNECT,"INSERT INTO users_privacy_settings SET user_id='$user_id',added=NOW(),updated=NOW(),setting_term='".$row1['setting_term']."',setting_value='".$row1['setting_value']."',status='".$row1['status']."'");
					}
				}
				return true;
			}
		}
		else
		{
			$query1="SELECT * FROM default_privacy_settings";
			$result1=mysqli_query($CONNECT,$query1);
			$num_rows1=mysqli_num_rows($result1);
			if($num_rows1>0)
			{
				while($row1=mysqli_fetch_array($result1))
				{
					mysqli_query($CONNECT,"INSERT INTO users_privacy_settings SET user_id='$user_id',added=NOW(),updated=NOW(),setting_term='".$row1['setting_term']."',setting_value='".$row1['setting_value']."',status='".$row1['status']."'");
				}
				return true;
			}
			else{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}
if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
{
	$true_or_false=setDefaultPrivacyOptionIfNotAlready($_COOKIE['uid']);
}
?>