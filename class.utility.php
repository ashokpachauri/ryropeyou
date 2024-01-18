<?php
	include_once 'dbconnection.php';
	include_once 'connection.php';
	class Utility extends DBConnection{
		public $database;
		public $dbconn;
		public function __construct()
		{
			$this->database=new DBConnection();
			$this->dbconn=$this->database->DBConnect();
		}
		public function changeConstantsToPHP($constants_array,$html_string)
		{
			foreach($constants_array as $key=>$val)
			{
				$html_string=str_replace($key, $val, $html_string);
			}
			return $html_string;
		}
		public function getUserProfileURL($username="")
		{
			if($username!="")
			{
				return base_url.'u/'.$username;
			}
			else{
				return false;
			}
		}
		public function doUcWords($string="")
		{
			return ucwords(strtolower($string));
		}
		public function arrayToJson($array)
		{
			return json_encode($array);
		}
		public function jsonToArray($json)
		{
			return json_decode($json,TRUE);
		}
	}
?>