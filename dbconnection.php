<?php
	include_once 'conn-settings.php';
	class DBConnection{
		private $database_host;
		private $database_user;
		private $database_password;
		private $database_name;
		public $dbconnection;
		public $twitter_share;
		public $instagram_share;
		public $base_url;
		public function __construct()
		{
			$this->database_host="localhost";
			$this->database_user="ropeyou_master";
			$this->database_password="ropeyou#2019";
			$this->database_name="ropeyou_master";
			$this->twitter_share="https://twitter.com/intent/tweet?";
			$this->instagram_share="https://www.instagram.com/?url=";
			$this->base_url="https://ropeyou.com/rope/";
		}
		public function DBConnect()
		{
			$this->dbconnection=mysqli_connect($this->database_host,$this->database_user,$this->database_password,$this->database_name);
			return $this->dbconnection;
		}
		/*public function query($query="")
		{
			if($result_set=mysqli_query($this->dbconnection,$query))
			{
				return $result_set;
			}
			else
			{
				return false;
			}
		}
		public function num_rows($result_set=false)
		{
			if($result_set)
			{
				return mysqli_num_rows($result_set)
			}
			else
			{
				return 0;
			}
		}*/
	}
?>