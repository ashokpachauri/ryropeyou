<?php
	include_once 'dbconnection.php';
	include_once 'connection.php';
	include_once 'class.utility.php';
	include_once 'class.user.php';
	include_once 'class.chat.php';
	class ChatAjax extends DBConnection{
		public $database;
		public $dbconn;
		public $utility;
		public $user;
		public $chat;
		public function __construct()
		{
			$this->database=new DBConnection();
			$this->dbconn=$this->database->DBConnect();
			$this->utility=new Utility();
			$this->user=new User();
			$this->chat=new Chat();
		}
		public function getChatList()
		{
			if(isLogin())
			{
				$data=$this->chat->chatContactList();
				return $this->utility->arrayToJson(array("status"=>"success","data"=>$data));
			}
			else
			{
				return $this->utility->arrayToJson(array("status"=>"error","data"=>"User Not Loggedin","reason"=>"NOT_LOGGED_IN"));
			}
		}
	}
?>