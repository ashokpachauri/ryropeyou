<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head.php'; ?>
		<title>Wishes | RopeYou Connects</title>
	</head>
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4" style="min-height:650px;">
			<div class="container">
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-12">
								<?php
									$nthread=$_REQUEST['nthread'];
									$uthread=$_REQUEST['uthread'];
									if(isset($_REQUEST['uthread']) && $_REQUEST['uthread']!="")
									{
										mysqli_query($conn,"UPDATE threats_to_user SET flag=1,checkout=1,seen=1 WHERE md5(id)='$nthread' AND user_id='".$_COOKIE['uid']."'");
										$query="SELECT * FROM threats_to_user WHERE md5(id)='$nthread' AND user_id='".$_COOKIE['uid']."'";
										$result=mysqli_query($conn,$query);
										if(mysqli_num_rows($result)>0)
										{
											$uthread_query="SELECT * FROM users WHERE status!=3 AND status!=2 AND md5(id)='$uthread'";
											$uthread_result=mysqli_query($conn,$uthread_query);
											if(mysqli_num_rows($uthread_result)>0)
											{
												$user_data=mysqli_fetch_array($uthread_result);
												$users_personal_data=getUsersPersonalData($user_data['id']);
												$gender=strtolower($users_personal_data['gender']);
												$pronounciation=" them ";
												$pronounciation_title=" They ";
												if($gender=="male")
												{
													$pronounciation=" him ";
													$pronounciation_title=" He ";
												}
												else if($gender=="female"){
													$pronounciation=" her ";
													$pronounciation_title=" She ";
												}
												
												$wish=$_REQUEST['wish'];
												if($wish=="")
												{
													$wish="birthday";
												}
												if($wish=="birthday")
												{
													
													?>
													<div class="box shadow-sm border rounded bg-white mb-3">
														<div class="box-title border-bottom p-3">
															<h6 class="m-0 font-weight-bold"><?php echo ucwords(strtolower($user_data['first_name']." ".$user_data['last_name'])); ?> has its <?php echo $wish; ?> today</h6>
														</div>
														<div class="box-body p-3">
															<div class="row">
																<div class="col-md-12" style="margin-top:20px;">
																	Let <?php echo ucwords(strtolower($user_data['first_name']." ".$user_data['last_name'])); ?> know you are thinking about <?php echo $pronounciation; ?>, by sending <?php echo $pronounciation; ?> a greeting.
																</div>
																<div class="col-md-12" style="margin-top:20px;">
																	<input type="hidden" name="wish_type" id="wish_type" value="<?php echo $wish; ?>">
																	<input type="hidden" name="user_to_wish" id="user_to_wish" value="<?php echo $user_data['id']; ?>">
																	<textarea rows="3" style="resize:none;" class="form-control" name="greeting_message" id="greeting_message">Happy birthday <?php echo ucwords(strtolower($user_data['first_name']." ".$user_data['last_name'])); ?>.</textarea>
																	<button onclick="sendGreeting();" class="btn btn-primary pull-right" type="button" style="margin-top:20px;">Wish</button>
																</div>
															</div>	
														</div>
													</div>
													<?php
												}
											}
											else
											{
												?>
												<h4 style="text-align:center;margin-top:50px;">The page you are looking for is not available.</h4>
												<?php
											}
										}
										else
										{
											?>
											<h4 style="text-align:center;margin-top:50px;">The page you are looking for is not available.</h4>
											<?php
										}
									}
									else
									{
										?>
										<h4 style="text-align:center;margin-top:50px;">The page you are looking for is not available.</h4>
										<?php
									}
								?>
								
							</div>
						</div>
					</div>
					<div class="col-md-2"></div>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<script>
			var user_id="<?php echo $_COOKIE['uid']; ?>";
			var base_url="<?php echo base_url; ?>";
			function sendGreeting()
			{
				var user_to_wish=$("#user_to_wish").val();
				var greeting_message=$("#greeting_message").val();
				var wish_type=$("#wish_type").val();
				if(user_to_wish!="" && greeting_message!="" && wish_type!="")
				{
					$.ajax({
						url:base_url+"send-greeting-message",
						type:'post',
						data:{user_to_wish:user_to_wish,greeting_message:greeting_message,wish_type:wish_type},
						success:function(response)
						{
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								$("#greeting_message").val("")
								alert('Your greetings has been sent successfully.');
							}
							else
							{
								alert(parsedJson.message);
							}
						}
					});
				}
				else
				{
					alert("Message can not be blank.");
				}
			}
		</script>
	</body>
</html>
