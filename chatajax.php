<?php
	include_once 'connection.php';
	$response=array();
	$action=$_POST['action'];
	if($action=="chat_bot_list")
	{
		include_once 'class.chat.php';
		$chat=new Chat();
		$list=$chat->chatContactList();
		if($list===false)
		{
			$response['status']="error";
			$response['reason']="NOT_LOGGED_IN";
			$response['data']='null';
		}
		else
		{
			$response['status']="success";
			$time=time();
			$response['time']=$time;
			$response['ry_acq_stmp']=md5($time);
			$response['_bvqstf']=md5(date('M/D/Y'));
			$response['data']=$list;
		}
		echo json_encode($response);
	}
	else if($action=="chat_bot_window_content"){
		include_once 'class.chat.php';
		$window_user_id=$_POST['__acrf'];
		$chat=new Chat();
		$response=$chat->getMessages($window_user_id);
		if(userBlocked($window_user_id))
		{
			$response['blocked']="blocked";
		}
		else
		{
			$response['blocked']="unblocked";
		}
		echo json_encode($response);
	}
?>