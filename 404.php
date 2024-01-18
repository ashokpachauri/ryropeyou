<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'connection.php'; define('global_url',base_url); ?>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" type="image/png" href="<?php echo base_url; ?>img/fav.png">
		<title>404 Page Not Found | RopeYou Connects</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>vendor/slick/slick.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>vendor/slick/slick-theme.min.css"/>
		<link href="<?php echo base_url; ?>vendor/icons/feather.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url; ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url; ?>css/style.css" rel="stylesheet">
	</head>
	<style>
		.nav-link>span{
			color:#fff;
			font-family:"Roboto" sens-serif;
		}
	</style>
   <body>
		<?php
			include_once 'header.php';
		?>
		<div class="vh-100">
         <div class="container">
            <div class="row align-items-center vh-100">
				<div class="col-md-6">
                  <h1 class="text-primary display-3 font-weight-light">Page not <span class="font-weight-bold">found</span></h1>
                  <p class="mb-0 lead">Oops! Looks like you followed a bad link.</p>
                  <p class="lead mb-5">If you think this is a problem with us, please <a href="<?php echo base_url; ?>#">tell us</a>.</p>
                  <a href="<?php echo base_url; ?>" class="btn btn-primary btn-lg">Go to home</a>
                  <!--<a href="<?php echo base_url; ?>help" class="btn btn-light btn-lg">Help</a>-->
				</div>
				<div class="col-md-6">
					
				</div>
            </div>
         </div>
      </div>
      
		<footer class="bg-light py-4 fixed-bottom">
			<div class="container">
				<div class="d-flex justify-content-between align-items-center">
					<p class="small text-muted mb-0">© RopeYou Connects. 2020 All Rights Reserved.</p>
					<!--<ul class="list-inline mb-0">
					  <li class="list-inline-item">
						 <a class="btn btn-sm btn-light" href="<?php echo base_url; ?>#">
						 <span class="feather-facebook"></span>
						 </a>
					  </li>
					  <li class="list-inline-item">
						 <a class="btn btn-sm btn-light" href="<?php echo base_url; ?>#">
						 <span class="feather-youtube"></span>
						 </a>
					  </li>
					  <li class="list-inline-item">
						 <a class="btn btn-sm btn-light" href="<?php echo base_url; ?>#">
						 <span class="feather-twitter"></span>
						 </a>
					  </li>
					  <li class="list-inline-item">
						 <a class="btn btn-sm btn-light" href="<?php echo base_url; ?>#">
						 <span class="feather-github"></span>
						 </a>
					  </li>
					</ul>-->
				</div>
			</div>
		</footer>
		<script src="<?php echo base_url; ?>vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url; ?>vendor/slick/slick.min.js"></script>
		<script src="<?php echo base_url; ?>js/osahan.js"></script>
		<script>
			$(window).on('load', function() {
			   $("#cover_loader").hide();
			});
		</script>
	</body>
</html>
