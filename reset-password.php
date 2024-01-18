<?php
	include_once 'connection.php';
	if(isset($_REQUEST['hash']) && isset($_REQUEST['code']))
	{
		$hash=$_REQUEST['hash'];
		$code=$_REQUEST['code'];
		$query="SELECT * FROM users WHERE md5(id)='$hash' AND code='$code'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0)
		{
			
		}
		else
		{
			?>
			<script>
				alert("It looks like the link has been expired.");
				window.location.href="<?php echo base_url; ?>";
			</script>
			<?php
			die();
		}
	}
	if(isset($_POST['set_new_password']))
	{
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		if($new_password!="" && $confirm_password!="" && $confirm_password==$new_password)
		{
			$hash=$_POST['hash'];
			$code=$_POST['code'];
			$query="SELECT * FROM users WHERE md5(id)='$hash' AND code='$code'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$password=md5($new_password);
				$query="UPDATE users SET password='$password',code='',validated=1 WHERE id='".$row['id']."'";
				$result=mysqli_query($conn,$query);
				if($result)
				{
					?>
					<script>
						alert("Password has been reset successfully.");
						window.location.href="<?php echo base_url; ?>";
					</script>
					<?php
					die();
				}
				else
				{
					?>
					<script>
						alert("Something went wrong please try again.");
					</script>
					<?php
				}
			}
			else
			{
				?>
				<script>
					alert("It looks like the link has been expired.");
					window.location.href="<?php echo base_url; ?>";
				</script>
				<?php
				die();
			}
		}
		else
		{
			if($confirm_password!=$new_password)
			{
				?>
				<script>
					alert("Password does not matches.");
				</script>
				<?php
			}
			else
			{
				?>
				<script>
					alert("Please fill all required fields");
				</script>
				<?php
			}
		}
	}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Reset Password | RopeYou Connects</title>
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
	<link href="<?php echo base_url; ?>custom-css/fontawesome-all.css" rel="stylesheet">
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
						<h1 style="text-align:center;">
							<a class="navbar-brand" href="index.php">
								<img src="<?php echo base_url; ?>/ryl3.png" alt="RopeYou" style="height:27px;">
							</a>
						</h1>
					</div>
				</div>
				<div class="navbar-header hidden-md hidden-lg hidden-xl" style="margin-left:75px;">
					<div class="logo_wthree_agile hidden-md hidden-lg hidden-xl" style="margin-top:0.55em;">
						<h1 style="text-align:center;">
							<a class="navbar-brand" href="index.php" style="color:#fff;font-size:25px;text-align:center;text-transform: none;">
								RopeYou<br/><span style="font-size:10px;text-align:center;">Watch Your Success Grow</span>
								<!--<span class="desc"  style="color:#fff;">Give a little. Help a lot.</span>-->
							</a>
						</h1>
					</div>
				</div>
				<div id="navbar" class="navbar-collapse collapse hidden-sm hidden-xs">
					<div class="nav_right_top">
						<ul class="nav navbar-nav">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 col-sm-12 hidden-sm hidden-xs" style="padding:15px;">
									<div class="row">
										<div class="col-md-2 col-sm-2 col-xs-5 col-sm-3 col-5">
											<a href="<?php echo base_url; ?>" class="btn btn-primary">Login / Register</a>
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
			<div class="col-md-8 col-sm-8 col-lg-8 hidden-xs">
			</div>
			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 col-12 bg_over_ridden" style="min-height:490px;<?php if(isset($_REQUEST['user'])){ echo "display:none;"; }else {  } ?>" id="register_div">
				<div class="row">
					<div class="col-md-12" style="margin-bottom:50px;">
						<h3 style="text-align:center;font-size:18px;">Reset Your Password</h3>
					</div>
				</div>
				<form action="" method="post" autocomplete="off">
					<div class="row">
						<input type="hidden" name="hash" required id="hash" value="<?php echo $_REQUEST['hash']; ?>">
						<input type="hidden" name="code" required id="code" value="<?php echo $_REQUEST['code']; ?>">
						
						<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
							<div class="form-group">
								<input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password" autocomplete="off" required>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
							<div class="form-group">
								<input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" autocomplete="off" required>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="margin-top:10px;">
							<button class="btn btn-primary form-control" type="submit" name="set_new_password">Save</button>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-12 col-xs-12 col-12 col-md-12" style="margin-top:10px;">
						<span style="text-align:center;">Have / Need an account? <a href="<?php echo base_url; ?>" style="text-align:center;">Login / Register</a>
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
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/bootstrap.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/all.js"></script>
	<script>
		var base_url="<?php echo base_url; ?>";
		$('ul.dropdown-menu li').hover(function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
		}, function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
		});
	</script>
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/easing.js"></script>
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/move-top.js"></script>
	<script src="<?php echo base_url; ?>custom-js/sweetalert.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$(".scroll, .navbar li a, .footer li a").click(function (event) {
				$('html,body').animate({
					scrollTop: $(this.hash).offset().top
				}, 1000);
			});
	</script>
	<a href="#home" class="scroll" id="toTop" style="display: block;">
		<span id="toTopHover" style="opacity: 1;"> </span>
	</a>
	<script type="text/javascript" src="<?php echo base_url; ?>custom-js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo base_url; ?>custom-js/jquery.quicksand.js" type="text/javascript"></script>
	<script src="<?php echo base_url; ?>custom-js/script.js" type="text/javascript"></script>
</body>
</html>