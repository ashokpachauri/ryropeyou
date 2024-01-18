<?php
	include_once 'connection.php';
	include_once 'index-action.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Recruiters, Jobs & Social Network | RopeYou Connects</title>
	<meta property="og:url" content="<?php echo base_url; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Recruiters, Jobs & Social Network" />
	<meta property="og:description" content="Recruiters & Social Network,Video CV,Video Interviews" />
	<meta property="og:image" content="<?php echo base_url; ?>uploads/@native.jpg"/>
	<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
	<meta name="google-signin-scope" content="profile email">
	<meta name="google-signin-client_id" content="940004341323-ubu6e063ut7bafuosk2952k8s84nenvs.apps.googleusercontent.com">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<meta name="keywords" content="RopeYou Connects,Recruiter,Job Seekers,Login,Register,Connect with Recruiters Socially,Watch your success grow,#ropeyou,@ropeyou,<?php echo base_url; ?>" />
	<script src="https://apis.google.com/js/api:client.js"></script>
	<link rel="shortcut icon" type="image/png" href="<?php echo base_url; ?>images/fav.png"/>
	<script type="application/x-javascript">
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
		
	</script>
	<link href="<?php echo base_url; ?>custom-css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo base_url; ?>custom-css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo base_url; ?>custom-css/prettyPhoto.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url; ?>custom-css/easy-responsive-tabs.css" rel='stylesheet' type='text/css' />
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo base_url; ?>custom-css/index.css" rel="stylesheet">
	<!-- //for bootstrap working -->
	<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
	
</head>

<body class="bg">
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v5.0&appId=2561662937402369&autoLogAppEvents=1"></script>
	<div class="top_header" id="home" style="background-color:#1d2f38 !important;">
		<!-- Fixed navbar -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="nav_top_fx_w3layouts_agileits">
				<div class="navbar-header hidden-sm hidden-xs">
					<button type="button" class="navbar-toggle collapsed hidden-sm hidden-xs" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					    aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="logo_wthree_agile  hidden-sm hidden-xs">
						<h1 class="text-center">
							<a class="navbar-brand" href="index.php">
								<img src="<?php echo base_url; ?>/ryl3.png" alt="RopeYou">
							</a>
						</h1>
					</div>
				</div>
				<div class="navbar-header hidden-md hidden-lg hidden-xl mleft75">
					<div class="logo_wthree_agile hidden-md hidden-lg hidden-xl mtop_55">
						<h1 style="text-align:center;">
							<a class="navbar-brand navbar-brand-text-logo" href="index.php">
								RopeYou<br/><span>Watch Your Success Grow</span>
							</a>
						</h1>
					</div>
				</div>
				<div id="navbar" class="navbar-collapse collapse hidden-sm hidden-xs">
					<div class="nav_right_top">
						<ul class="nav navbar-nav">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 col-sm-12 hidden-sm hidden-xs padding15">
									<div class="row">
										<div class="col-md-2 col-sm-2 col-xs-5 col-sm-3 col-5">
											<button type="button" name="user_login_button" id="user_login_button" class="btn btn-primary width80" onclick="login_div_show();"><?php if(isset($_REQUEST['user'])){ echo "Signup"; }else { echo "Login"; } ?></button>
										</div>
									</div>
								</div>
							</div>
						</ul>
					</div>
				</div>
				<!--/.nav-collapse -->
			</div>
		</nav>
		<div class="clearfix"></div>
	</div>
	<div class="container">
		<div class="row">
			
			<div class="modal fade retrieve_password" id="retrieve_password" style="z-index:9999 !important;" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="retrieve_password_static" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header" style="width:100%;">
							<div class="row" style="width:100%;">
								<div class="col-md-8">
									<h5 class="modal-title" id="retrieve_password_static">Reset Password</h5>
								</div>
								<div class="col-md-4">
									<button  data-dismiss="modal" style="border-color:none !important;float:right !important;background-color:#efefef;color:#000;font-size:15px;border-radius:50%;height:30px;width:30px;" type="button"><i class="fa fa-times"></i></button>
								</div>
							</div>
						</div>
						<div class="modal-body" style="padding:0px;">
							<div class="w-100">
								<div class="row" style="width:100%;padding:0px;margin:0px;">
									<div class="col-md-12" id="retrieve_password_html" style="padding:10px;border-top:1px solid #dee2e6;margin-bottom:20px;">
										
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-sm-8 col-lg-8 hidden-xs">
			</div>
			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 col-12 bg_over_ridden mheight490" style="<?php if(isset($_REQUEST['user'])){ echo "display:none;"; }else {  } ?>" id="register_div">
				<div class="row">
					<div class="col-md-12">
						<h3 style="text-align:center;font-size:18px;">Be great at what you do.</h3>
						<h4 style="text-align:center;margin-top:8px;margin-bottom:14px;font-size:14px;">Get started - it's free.</h4>
					</div>
				</div>
				<form action="" method="post" autocomplete="off">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
							<div class="form-group">
								<input type="text" name="first_name" class="form-control" id="first_name" placeholder="First name*" autocomplete="off" required>
								<?php
									if(isset($_SESSION['first_name']) && $_SESSION['first_name']!='')
									{
										?>
										<span class="text-danger" style="font-size:10px;padding:5px;"><?php echo $_SESSION['first_name']; ?></span>
										<?php
										$_SESSION['first_name']='';
									}
								?>
							</div>
						</div>
						<input type="hidden" name="csrf_token" value="<?php echo md5(time()); ?>">
						<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
							<div class="form-group">
								<input type="text" name="last_name" class="form-control" id="last_name" autocomplete="off" placeholder="Last name*" required>
								<?php
									if(isset($_SESSION['last_name']) && $_SESSION['last_name']!='')
									{
										?>
										<span class="text-danger" style="font-size:10px;padding:5px;"><?php echo $_SESSION['last_name']; ?></span>
										<?php
										$_SESSION['last_name']='';
									}
								?>
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">
							<div class="form-group">
								<img id="country_flag" src="https://www.countryflags.io/in/shiny/32.png" style="height:48px;margin-top:-7px;">
							</div>
						</div>
						<div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
							<div class="form-group">
								<select name="country_code" class="form-control" id="country_code" autocomplete="off" required>
									<option value="" data-code="">Select Country</option>
									<?php
										$country_query="SELECT * FROM country WHERE status=1 ORDER by title";
										$country_result=mysqli_query($conn,$country_query);
										while($country_row=mysqli_fetch_array($country_result))
										{
											?>
											<option data-code="<?php echo strtolower($country_row['code']); ?>" value="<?php echo $country_row['phonecode']; ?>" <?php if($country_row['phonecode']=="+91"){ echo 'selected'; } ?>><?php echo $country_row['title'].' - ('.$country_row['phonecode'].')'; ?></option>
											<?php
										}
									?>
								</select>
								<?php
									if(isset($_SESSION['ccode']) && $_SESSION['ccode']!='')
									{
										?>
										<span class="text-danger" style="font-size:10px;padding:5px;"><?php echo $_SESSION['ccode']; ?></span>
										<?php
										$_SESSION['ccode']='';
									}
									
								?>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<div class="form-group">
								<input type="text" name="mobile_email" class="form-control" autocomplete="off" id="mobile_email" placeholder="Mobile or email address*" required>
								<span class="text-warning" style="font-size:12px;padding:5px;">We will use this to send an One Time Password for verification.</span>
								<?php
									if(isset($_SESSION['mobile_email']) && $_SESSION['mobile_email']!='')
									{
										?>
										<span class="text-danger" style="font-size:10px;padding:5px;"><?php echo $_SESSION['mobile_email']; ?></span>
										<?php
										$_SESSION['mobile_email']='';
									}
								?>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<div class="form-group">
								<input type="password" name="password" class="form-control" autocomplete="off" id="password" placeholder="Password (6 or more characters)*" required>
								<?php
									if(isset($_SESSION['password']) && $_SESSION['password']!='')
									{
										?>
										<span class="text-danger" style="font-size:10px;padding:5px;"><?php echo $_SESSION['password']; ?></span>
										<?php
										$_SESSION['password']='';
									}
									
								?>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="margin-top:10px;font-size:12px;">
							By clicking Register, you agree to our <a href="<?php echo base_url; ?>terms">Terms and Conditions, Privacy Policy and Cookie Policy</a>. You may receive SMS,Email or both kind of notifications from us and can opt out at any time.
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="margin-top:10px;">
							<button class="btn btn-primary form-control" type="submit" name="register">Register</button>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-12 col-md-12" style="margin-top:10px;">
						<span style="text-align:center;">Have an account? <a href="javascript:void(0);" onclick="login_div_show();" style="text-align:center;">Login</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="margin-top:10px;">
						<h3 style="text-align:center;margin-bottom:5px;">______________</h3>
						<div class="login100-form-social flex-c-m">
							<a href="javascript:void(0);" class="login100-form-social-item flex-c-m bg1 m-r-5" id="fb_login_1" title="Login With Facebook">
								<image src="<?php echo base_url; ?>images/f.png" class="fb-login-button" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
							<!--<div class="fb-login-button" data-width="50" data-size="small" data-button-type="login_with" data-auto-logout-link="false" data-use-continue-as="false" style="display:none;"></div>-->
							<a href="javascript:void(0);" class="login100-form-social-item flex-c-m bg2 m-r-5 customGPlusSignIn" title="Login With Google" id="customBtn1">
								<image src="<?php echo base_url; ?>images/g.png" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
							<a href="<?php echo base_url.'twitter-login.php'; ?>" class="login100-form-social-item flex-c-m bg3 m-r-5" title="Login With Twitter" id="twitter-button">
								<image src="<?php echo base_url; ?>images/t.png" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
							<a href="<?php echo base_url.'linkedin-login.php'; ?>" class="login100-form-social-item flex-c-m bg2 m-r-5" title="Login With Linkedin">
								<image src="<?php echo base_url; ?>images/l.png" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 col-12 bg_over_ridden_1" style="min-height:350px;<?php if(isset($_REQUEST['user'])){  }else { echo "display:none;"; } ?>" id="login_div">
				<div class="row">
					<div class="col-md-12">
						<h3 style="text-align:center;font-size:18px;">Welcome Back!</h3>
						<?php if(isset($_REQUEST['user'])){ echo "<h6 style='text-align:center;margin-top:15px;color:red;'>Invalid Username/Password</h6>"; } ?>
					</div>
				</div>
				<form action="" method="post">
					<div class="row" style="margin-top:30px;">
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<div class="form-group">
								<input type="text" name="mobile_email" autocomplete="off" class="form-control" id="username" placeholder="Mobile or email address" required>
							</div>
						</div>
						<input type="hidden" name="csrf_token" value="<?php echo md5(time()); ?>">
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<div class="form-group">
								<input type="password" name="password" autocomplete="off" class="form-control" id="user_password" placeholder="Password" required>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="margin-top:10px;">
							<button class="btn btn-primary form-control" type="submit" name="user_login">Login</button>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-12 col-md-12" style="margin-top:15px;">
						<span class="pull-left"><a href="javascript:void(0);" onclick="forgot_div_show();" style="text-align:center;">Forgot Password?</a></span>
						<span style="text-align:center;" class="pull-right">New to RopeYou? <a href="javascript:void(0);" onclick="register_div_show();" style="text-align:center;">Signup Now</a></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="margin-top:10px;">
						<h3 style="text-align:center;margin-bottom:5px;">______________</h3>
						<div class="login100-form-social flex-c-m">
							<a href="#" class="login100-form-social-item flex-c-m bg1 m-r-5" id="fb_login_2" title="Login With Facebook">
								<image src="<?php echo base_url; ?>images/f.png" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
							<a href="javascript:void(0);" class="login100-form-social-item flex-c-m bg2 m-r-5 customGPlusSignIn" title="Login With Google" id="customBtn">
								<image src="<?php echo base_url; ?>images/g.png" class="g-signin2" data-onsuccess="onSignIn" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
							<a href="<?php echo base_url.'twitter-login.php'; ?>" class="login100-form-social-item flex-c-m bg3 m-r-5" title="Login With Twitter">
								<image src="<?php echo base_url; ?>images/t.png" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
							<a href="<?php echo base_url.'linkedin-login.php'; ?>" class="login100-form-social-item flex-c-m bg2 m-r-5" title="Login With Linkedin">
								<image src="<?php echo base_url; ?>images/l.png" style="width:40px;border:1px solid #515e72;border-radius: 50%;">
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 col-12 bg_over_ridden_1" style="min-height:350px;<?php if(isset($_REQUEST['forgot_password'])){  }else { echo "display:none;"; } ?>" id="forgot_div">
				<div class="row">
					<div class="col-md-12">
						<h3 style="text-align:center;font-size:18px;">Forgot Password?</h3>
					</div>
				</div>
				<form action="" method="post">
					<div class="row" style="margin-top:30px;">
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<h5 class="uk-text-bold mb-2" style="margin-bottom:10px;"> Username </h5>
							<input type="text" name="forgot_password_username" id="forgot_password_username" class="form-control" placeholder="Username">
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
							<h5 class="uk-text-bold mb-2">&nbsp;</h5>
							<button type="button" onclick="searchUser('forgot_password_username');" id="get_user_otp_prompt_password_lost" name="get_user_otp_prompt_password_lost" class="btn btn-primary" style="float:right !important;">Search Account</button>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-12 col-md-12" style="margin-top:15px;">
						<span class="pull-left"><a href="javascript:void(0);" onclick="login_div_show();" style="text-align:center;">Login?</a></span>
						<span style="text-align:center;" class="pull-right">New to RopeYou? <a href="javascript:void(0);" onclick="register_div_show();" style="text-align:center;">Signup Now</a></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="contact" style="max-height:50px !important;position: fixed;bottom: 0;width: 110%;">
		<div class="footer_inner_info_wthree_agileits" style="background-color:#fff !important;">
			<p class="copy-right">2020 © RopeYou. All rights reserved | Going through testing (Internally)
				<!--<a href="<?php echo base_url; ?>">With Love</a>-->
			</p>
		</div>
	</div>
	<!-- footer -->
	<!-- //footer -->
	
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/jquery-2.2.3.min.js"></script>
	<script src="<?php echo base_url; ?>custom-js/bootstrap.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/all.js"></script>
	<script type="text/javascript" src=" https://cdn.rawgit.com/oauth-io/oauth-js/c5af4519/dist/oauth.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/easing.js"></script>
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/move-top.js"></script>
	<script src="<?php echo base_url; ?>custom-js/sweetalert.min.js"></script>
	<?php include_once 'indexjs.php'; ?>
	<a href="#home" class="scroll" id="toTop" style="display: block;">
		<span id="toTopHover" style="opacity: 1;"> </span>
	</a>
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo base_url; ?>custom-js/jquery.quicksand.js" type="text/javascript"></script>
	<script src="<?php echo base_url; ?>custom-js/script.js" type="text/javascript"></script>
	
</body>
</html>