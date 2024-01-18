<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head.php'; ?>
		<?php
			$username=$_REQUEST['__username'];
			$uquery="SELECT * FROM users WHERE username='$username' AND status=1";
			$uresult=mysqli_query($conn,$uquery);
			if(mysqli_num_rows($uresult)>0)
			{
				$user_row=mysqli_fetch_array($uresult);
				$profile_user_id=$user_row['id'];
			}
			else
			{
				include_once '404.php';
				die();
			}
		?>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap.min.css">
		<title><?php echo ucwords(strtolower($user_row['first_name'].' '.$user_row['last_name'])); ?>'s Connections | RopeYou Connects</title>
	</head>
	<body>
		<style>
			.overlap-rounded-circle>.rounded-circle{
				width:25px;
				height:25px;
			}
			.network-item-body{
				min-height: 39px;
				max-height: 40px;
			}
		</style>
		<?php 
			include_once 'header.php'; 
			$uquery="SELECT * FROM users WHERE username='$username' AND status=1";
			$uresult=mysqli_query($conn,$uquery);
			$user_row=mysqli_fetch_array($uresult);
		?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					  <div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post">
						 <h5 class="pl-3 pt-3 pr-3 border-bottom mb-0 pb-3"><?php echo ucwords(strtolower($user_row['first_name'].' '.$user_row['last_name'])); ?>'s Connections</h5>
						 
						 <div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="bridge" onload="" role="tabpanel" aria-labelledby="bridge-tab">
							</div>
						</div>
					  </div>
				   </main>
					<aside class="hide_on_dashboard col col-xl-3 order-xl-2 col-lg-12 order-lg-2 col-12">
					  <?php
						include_once 'people_you_may_know.php';
						$profile=getUserProfileImage($_COOKIE['uid']);
					  ?>
						<div class="box shadow-sm mb-3 border rounded bg-white ads-box text-center">
							 <div class="image-overlap-2 pt-4">
								<img src="<?php echo $profile; ?>" class="img-fluid rounded-circle shadow-sm" alt="Responsive image">
								<img src="<?php echo base_url; ?>img/jobs.jpg" class="img-fluid rounded-circle shadow-sm" alt="Responsive image">
							 </div>
							 <div class="p-3 border-bottom">
								<h6 class="text-dark"><?php echo $user_row['first_name']." ".$user_row['last_name'] ?>, grow your career by following <span class="font-weight-bold"> RopeYou</span></h6>
								<p class="mb-0 text-muted">Stay up-to industry trends!</p>
							 </div>
							 <div class="p-3">
								<button type="button" class="btn btn-outline-primary btn-sm pl-4 pr-4"> FOLLOW </button>
							 </div>
						</div>
					</aside>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<script>
			var user_id="<?php echo $_COOKIE['uid']; ?>";
			var base_url="<?php echo base_url; ?>";
			
			function LoadData(data_section,requested_user_id)
			{
				$("#"+data_section).load(base_url+"load-view-"+data_section+".php?requested_user_id="+requested_user_id);
			}
			function loadImage(div)
			{
				$("."+div+" img").css("cursor","pointer");
				$("."+div+" img").click(function(){
					$("#backdrop_image_to_show").attr("src",$(this).attr("src"));
					$("#image_backdrop_modal").modal('show');
				});
			}
			function loadImageSlider(div)
			{
				$("."+div+" img").css("cursor","pointer");
				$("."+div+" img").click(function(){
					var image=$(this).data("image");
					var user_id=$(this).data("userid");
					$.ajax({
						url:base_url+'get-user-public-gallery-slider',
						type:'post',
						data:{user_id:user_id,image:image},
						success:function(response){
							$("#backdrop_image_container_html").html(response);
							$("#image_backdrop_slider_modal").modal('show');
						}
					});
				});
			}
			LoadData('bridge','<?php echo $profile_user_id; ?>');
			//loadImageSlider("py-4");
		</script>
	</body>
</html>
