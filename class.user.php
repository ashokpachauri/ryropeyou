<?php
	include_once 'dbconnection.php';
	include_once 'connection.php';
	class User extends DBConnection{
		public $database;
		public $dbconn;
		public function __construct()
		{
			$this->database=new DBConnection();
			$this->dbconn=$this->database->DBConnect();
		}
		public function fetchUser($user_id)
		{
			$_user_query="SELECT * FROM users WHERE id='$user_id' AND in_appropriate=0";
			$_user_result=mysqli_query($this->dbconn,$_user_query);
			if(mysqli_num_rows($_user_result)>0)
			{
				$user_row=mysqli_fetch_array($_user_result);
				return $user_row;
			}
			else
			{
				return false;
			}
		}
		public function userImage($user_id)
		{
			$_query="SELECT media_id FROM users_profile_pics WHERE user_id='$user_id' AND status=1 AND in_appropriate=0";
			$_result=mysqli_query($this->dbconn,$_query);
			$_profile="";
			if(mysqli_num_rows($_result)>0)
			{
				$_row=mysqli_fetch_array($_result);
				$media_id=$_row['media_id'];
				$media_query="SELECT * FROM gallery WHERE id='$media_id'";
				$media_result=mysqli_query($this->dbconn,$media_query);
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
					$_user_result=mysqli_query($this->dbconn,$_user_query);
					if(mysqli_num_rows($_user_result)>0)
					{
						$user_row=mysqli_fetch_array($_user_result);
						$_profile=base_url."alphas/".strtolower(substr($user_row['first_name'],0,1)).".png";
					}
					else
					{
						$_profile="default.png";
						$_profile=base_url."uploads/".$_profile;
					}
				}
				else
				{
					$_profile=base_url.$_profile;
				}
			}
			return $_profile;
		}
	}
?>