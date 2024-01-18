<?php
	include_once 'dbconnection.php';
	include_once 'connection.php';
	include_once 'class.utility.php';
	include_once 'class.user.php';
	class Chat extends DBConnection
	{
		public $database;
		public $dbconn;
		public $utility;
		public $user;
		public function __construct()
		{
			$this->database=new DBConnection();
			$this->dbconn=$this->database->DBConnect();
			$this->utility=new Utility();
			$this->user=new User();
		}
		public function chatCount($to_user,$from_user)
		{
			$chat_count_query="SELECT * FROM ";
		}
		/************* For Chat Bot*********************/
		public function getMessages($window_user_id)
		{
			$response_html='';
			$response=array();
			if(isLogin())
			{
				$user_id=$_COOKIE['uid'];
				$query="SELECT * FROM users_chat WHERE ((r_user_id='$window_user_id' AND user_id='$user_id' AND s_status=1 AND status=1) OR (user_id='$window_user_id' AND r_user_id='$user_id' AND status=1 AND r_status=1)) AND in_appropriate=0 ORDER BY id ASC";
				$result=mysqli_query($this->dbconn,$query);
				if(mysqli_num_rows($result)>0)
				{
					$response_html.='<div class="row user_thread_'.$window_user_id.'"><div class="col-md-12">';
					$messages=array();
					$thread_id="";
					while($row=mysqli_fetch_array($result))
					{
						$message=array();
						$message['id']=$row['id'];
						$message['thread']=md5($message['id']);
						$thread_id=$message['thread'];
						if($row['img_mesg']=="1")
						{
							$message['img_mesg']=$row['img_mesg'];
							$message['text_message']=base_url.$row['text_message'];
						}
						else if($row['attachment_mesg']=="1")
						{
							$attachments=explode(",",$row['text_message']);
							for($count=0;$count<$attachments;$count++)
							{
								if($attachments[$count]!="")
								{
									$attachments[$count]=base_url.$attachments[$count];
								}
							}
							$attachments=implode(",",$attachments);
							$message['text_message']=$attachments;
						}
						else
						{
							$message['text_message']=$row['text_message'];
						}
						
						$message['date']=date('M d, Y',strtotime($row['added']));
						$message['time']=date('h:i a',strtotime($row['added']));
						if($row['text_message']=="**RUCONNECTED**")
						{
							$response_html.='<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8" style="margin-top:10px;" data-toggle="tooltip" data-placement="top" data-original-title="'.$message['date'].' - '.$message['time'].'">
									<p style="text-align:center;">Bridge Constructed. Start Knowing Eachother.</p>
								</div>
								<div class="col-md-2"></div>
							</div>';
						}
						else if($row['text_message']=="**RUDISCONNECTED**")
						{
							$response_html.='<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8" style="margin-top:10px;" data-toggle="tooltip" data-placement="top" data-original-title="'.$message['date'].' - '.$message['time'].'">
									<p style="text-align:center;">You are no longer connected.</p>
								</div>
								<div class="col-md-2"></div>
							</div>';
						}
						else if($row['r_user_id']==$window_user_id)
						{
							$response_html.='<div class="row thread_'.$row['id'].'">
								<div class="col-md-2" style="text-align:center;font-size:10px;margin-top:10px;">
									<div class="chat-bot-dropdown">
										<a href="javascript:void(0);" class="chat-bot-dropbtn"><i class="fa fa-ellipsis-v"></i></a>
										<div class="chat-bot-dropdown-content-left">
											<a href="javascript:void(0);" title="Delete This Message" onclick="deleteThread('.$row['id'].');">Delete</a>
										</div>
									</div>
								</div>
								<div class="col-md-10" style="margin-top:10px;">
									<div class="row">
										<div class="col-md-12" style="padding-right:10px;text-align:right;">
											';
											if($row['img_mesg']=="1")
											{
												$response_html.='<img src="'.base_url.$row['text_message'].'" style="width:100px;border-radius:10px;border:1px solid #ddd;" data-toggle="tooltip" data-placement="top" data-original-title="'.$message['date'].' - '.$message['time'].'">';
											}
											else if($row['attachment_mesg']=="1")
											{
												$attachments_arr=explode(",",$attachments);
												for($counter=0;$counter<count($attachments_arr);$count++)
												{
													if($attachments_arr[$counter]!="")
													{
														$item=$attachments_arr[$counter];
														$item_arr=explode(".",$item);
														$item_type=end($item_arr);
														$response_html.='<a href="'.$item.'" target="_blank" download="download.'.$item_type.'" style="margin-top:10px;">'.$item_type.' -file </a>';
													}
												}
											}
											else
											{
												$response_html.='<p data-toggle="tooltip" data-placement="top" data-original-title="'.$message['date'].' - '.$message['time'].'">'.$row['text_message'].'</p>';
											}
											$response_html.='
										</div>
									</div>
								</div>							
							</div>';
						}
						else
						{
							$response_html.='<div class="row thread_'.$row['id'].'">
								<div class="col-md-10" style="margin-top:10px;">
									<div class="row">
										<div class="col-md-12" style="padding-left:10px;text-align:left;">
											';
											if($row['img_mesg']=="1")
											{
												$response_html.='<img src="'.base_url.$row['text_message'].'" style="width:100px;border-radius:10px;border:1px solid #ddd;" data-toggle="tooltip" data-placement="top" data-original-title="'.$message['date'].' - '.$message['time'].'">';
											}
											else if($row['attachment_mesg']=="1")
											{
												$attachments_arr=explode(",",$attachments);
												for($counter=0;$counter<count($attachments_arr);$count++)
												{
													if($attachments_arr[$counter]!="")
													{
														$item=$attachments_arr[$counter];
														$item_arr=explode(".",$item);
														$item_type=end($item_arr);
														$response_html.='<a href="'.$item.'" target="_blank" download="download.'.$item_type.'" style="margin-top:10px;">'.$item_type.' -file </a>';
													}
												}
											}
											else
											{
												$response_html.='<p>'.$row['text_message'].'</p>';
											}
											$response_html.='
										</div>
									</div>
								</div>	
								<div class="col-md-2" style="text-align:center;font-size:10px;margin-top:10px;">
									<div class="chat-bot-dropdown">
										<a href="javascript:void(0);" class="chat-bot-dropbtn"><i class="fa fa-ellipsis-v"></i></a>
										  <div class="chat-bot-dropdown-content">
											<a href="javascript:void(0);" title="Delete This Message" onclick="deleteThread('.$row['id'].');">Delete</a>
											<a href="javascript:void(0);" onclick="reportThread('.$row['id'].');">Report</a>
										  </div>
									</div>
								</div>
							</div>';
						}
						
						$messages[]=$message;
					}
					$response_html.='</div></div>';
					$messages['thread']=$thread_id;
					$response['messages']=$messages;
					$response['status']="success";
					$time=time();
					$response['time']=$time;
					$response['response_html']=$response_html;
					$response['ry_acq_stmp']=md5($time);
					$response['_bvqstf']=md5(date('M/D/Y'));
				}
				else
				{
					$response['status']="error";
					$response['reason']="MESSAGE_NOT_FOUND";
				}
			}
			else
			{
				$response['status']="error";
				$response['reason']="USER_NOT_LOGGEDIN";
			}
			return $response;
		}
		
		public function chatContactList()
		{
			if(isLogin())
			{
				$me=$_COOKIE['uid'];
				$friends=array();
				$user_id=$_COOKIE['uid'];
				$chat_list_query="SELECT * FROM user_joins_user WHERE user_id='".$_COOKIE['uid']."' OR r_user_id='".$_COOKIE['uid']."' AND status=1 ORDER BY id DESC";
				$chat_list_result=mysqli_query($this->dbconn,$chat_list_query);
				$chat_list_num_row=mysqli_num_rows($chat_list_result);
				if($chat_list_num_row>0)
				{
					while($chat_list_row=mysqli_fetch_array($chat_list_result))
					{
						if($chat_list_row['user_id']==$_COOKIE['uid'])
						{
							$friend=$chat_list_row['r_user_id'];
							if(!(in_array($friend,$friends)))
							{
								$friends[]=$friend;
							}
						}
						else
						{
							$friend=$chat_list_row['user_id'];
							if(!(in_array($friend,$friends)))
							{
								$friends[]=$friend;
							}
						}
					}
				}
				$selected_user=$friend_id;
				$html_contact_list="<script>var dataJson={};</script>";
				for($loopVar=0;$loopVar<count($friends);$loopVar++)
				{
					$friend_id=$friends[$loopVar];
					$friends_row=$this->user->fetchUser($friend_id);
					if($friends_row)
					{
						$message_part="";
						$message_query="SELECT * FROM users_chat WHERE ((r_user_id='$friend_id' AND user_id='$user_id' AND s_status=1 AND status=1) OR (user_id='$friend_id' AND r_user_id='$user_id' AND status=1 AND r_status=1)) AND in_appropriate=0 ORDER BY id DESC LIMIT 1";
						$message_result=mysqli_query($this->dbconn,$message_query);
						if(mysqli_num_rows($message_result)>0)
						{
							$message_row=mysqli_fetch_array($message_result);
							$text_message=$message_row['text_message'];
							$img_mesg=$message_row['img_mesg'];
							$added=$message_row['added'];
							$add_content=date('d-m-Y h:i a',strtotime($added));
							if($img_mesg=="0")
							{
								if($text_message=="**RUCONNECTED**")
								{
									$text_message="Bridge Constructed. Start Knowing Eachother.";
								}
								else if($text_message=="**RUDISCONNECTED**")
								{
									$text_message="You are no longer connected.";
								}
								$message_part.="<span>".substr($text_message,0,50)."</span><br/>";
							}
							else{
								
								$message_part.="<span><i class='fa fa-picture'></i>&nbsp;Image File</span><br/>";
							}
							$message_part.="<span>".$add_content."</span>";
						}
						$chat_window_image=$this->user->userImage($friend_id);
						$chat_window_image_alt=$this->utility->doUcWords($friends_row['first_name']." ".$friends_row['last_name']);
						$chat_window_user_online="online";
						$chat_window_user_name=$this->utility->doUcWords($friends_row['first_name']." ".$friends_row['last_name']);
						$chat_window_user_url=$this->utility->getUserProfileURL($friends_row['username']);
						$blocked_status="unblocked";
						if(userBlocked($friend_id))
						{
							$blocked_status="blocked";
						}
						if(userLoggedIn($friend_id))
						{
							$chat_window_user_online='online';
						}
						else
						{ 
							$chat_window_user_online='offline'; 
						}
						$user_chat_window_config=array("window_user_id"=>$friend_id,
							"chat_window_image"=>$chat_window_image,
							"chat_window_image_alt"=>$chat_window_image_alt,
							"chat_window_user_online"=>$chat_window_user_online,
							"chat_window_user_name"=>$chat_window_user_name,
							"chat_window_user_url"=>$chat_window_user_url						
						);
						$show_form="openChatWindow('".$friend_id."');";
						$html_contact_list.='<script>
							var key="'.$friend_id.'";
							var json_var='.json_encode($user_chat_window_config).';
							dataJson[key]=json_var;
						</script><a href="javascript:void(0);" data-blocked="'.$blocked_status.'" id="friend_open_'.$friend_id.'" onclick="'.$show_form.'">
							<div class="contact-list" style="padding:0px;margin:0px;">
								<div class="contact-list-media"> 
									<img src="'.$this->user->userImage($friend_id).'" alt="'.$this->utility->doUcWords($friends_row['first_name']." ".$friends_row['last_name']).'" style="height:30px;width:30px;">
									<span class="'.$chat_window_user_online.'-dot"></span> 
								</div>
								<h6 style="padding-top:5px;font-size:14px;">'.$this->utility->doUcWords($friends_row['first_name']." ".$friends_row['last_name']).'</h6>
							</div>
							<div class="row">
								<div class="col-md-12">
									<p style="margin-left:40px;margin-top:-15px;">'.$message_part.'</p>
								</div>
							</div>
						</a>';
					}
				}
				return $html_contact_list;
			}
			else
			{
				return false;
			}
		}
	}
?>