<!DOCTYPE html>
<html ng-app="ropeyou">
   <head>
		<?php include_once 'head.php'; ?>
		<?php
			if(isset($_POST['save_contact']))
			{
				$contact_type=$_POST['contact_type'];
				$contact=$_POST['contact'];
				$contact_name=$_POST['contact_name'];
				$location=$_POST['location'];
				$check_query="SELECT id FROM users_contact WHERE contact_type='$contact_type' AND contact='$contact' AND contact_name='$contact_name' AND location='$location'";
				$check_result=mysqli_query($conn,$check_query);
				if(mysqli_num_rows($check_result)>0)
				{
					?>
					<script>
						alert('contact already exists.');
					</script>
					<?php
				}
				else
				{
					$user_id=$_COOKIE['uid'];
					$insert_query="INSERT INTO users_contact SET contact_type='$contact_type',contact='$contact',contact_name='$contact_name',status=1,user_id='$user_id',location='$location'";
					if(mysqli_query($conn,$insert_query))
					{
						?>
						<script>
							alert('contact saved successfully.');
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server error.Please contact developer.');
						</script>
						<?php
					}
				}
			}
		?>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap.min.css">
		<title>Bridge | RopeYou Connects</title>
	</head>
	<body ng-controller="BridgeController">
		<style>
			.overlap-rounded-circle>.rounded-circle{
				width:25px;
				height:25px;
			}
			.network-item-body{
				min-height: 39px;
				max-height: 40px;
			}
            .nav-item-spec{
                min-width:100px;
                max-width:101px;
                border-right:2px solid gray;
                border-top-right-radius:8px;
                border-bottom-right-radius
            }
		</style>
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <main class="col col-xl-12 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					  <div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post">
						 <ul class="nav border-bottom osahan-line-tab" id="myTab" role="tablist">
							<li class="nav-item nav-item-spec" onclick="LoadData('home');">
							   <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Browse</a>
							</li>
							<li class="nav-item nav-item-spec" onclick="LoadData('requests');">
							   <a class="nav-link" id="requests-tab" data-toggle="tab" href="#requests" role="tab" aria-controls="requests" aria-selected="false">Requests</a>
							</li>
                             
							<li class="nav-item nav-item-spec" onclick="LoadData('requested');">
							   <a class="nav-link" id="requested-tab" data-toggle="tab" href="#requested" role="tab" aria-controls="requested" aria-selected="false">Requested</a>
							</li>
                             
							<li class="nav-item nav-item-spec" onclick="LoadData('bridge');" id="bridge_tab">
							   <a class="nav-link" id="bridge-tab" data-toggle="tab" href="#bridge" role="tab" aria-controls="bridge" aria-selected="false">Bridge</a>
							</li>
                            
							<li class="nav-item nav-item-spec" onclick="LoadData('follow-content');">
							   <a class="nav-link" id="follow-content-tab" data-toggle="tab" href="#follow-content" role="tab" aria-controls="follow-content" aria-selected="false">Follow</a>
							</li>
							<li class="nav-item nav-item-spec" onclick="LoadData('nearby');">
							   <a class="nav-link" id="nearby-tab" data-toggle="tab" href="#nearby" role="tab" aria-controls="nearby" aria-selected="false">Nearby</a>
							</li>
							<li class="nav-item nav-item-spec" onclick="LoadData('contacts');">
							   <a class="nav-link" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">Contacts</a>
							</li>
						 </ul>
						 <div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" style="min-height:500px;">
							     <?php echo loading(); ?>
                            </div>
							<div class="tab-pane fade" id="requests" role="tabpanel" aria-labelledby="requests-tab" style="min-height:500px;">
								<?php echo loading(); ?>
							</div>
                            <div class="tab-pane fade" id="requested" role="tabpanel" aria-labelledby="requested-tab" style="min-height:500px;">
								<?php echo loading(); ?>
							</div>
							<div class="tab-pane fade" id="bridge" role="tabpanel" aria-labelledby="bridge-tab" style="min-height:500px;">
                                <?php echo loading(); ?>
							</div>
							<div class="tab-pane fade" id="nearby" role="tabpanel" aria-labelledby="nearby-tab" style="min-height:500px;">
								<?php echo loading(); ?>
							</div>
                             
							<div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab" style="min-height:500px;">
								<?php echo loading(); ?>
							</div>
                            <div class="tab-pane fade" id="follow-content" role="tabpanel" aria-labelledby="follow-content-tab" style="min-height:500px;">
								<?php echo loading(); ?>
							</div>
						</div>
					  </div>
				   </main>
				</div>
			</div>
		</div>
		<div class="modal fade add_contact" id="add_contact" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="add_contact_backdrop" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="modal-title" id="add_contact_backdrop">Add Contact </h6>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body add_contact_modal_body">											
						<form action="" method="post">
							<div class="row" id="add_contact_modal_body">
								<div class="col-12">
									<h6>Contact Type</h6>
									<select id="contact_type" name="contact_type" class="form-control" required>
										<option value="">Select Contact Type</option>
										<option value="email">Email</option>
										<option value="mobile">Mobile</option>
										<option value="skype">Skype</option>
										<option value="hangout">Hangout</option>
										<option value="whatsapp">Whatsapp</option>
										<option value="website">Website</option>
										<option value="facebook">Facebook</option>
										<option value="instagram">Instagram</option>
										<option value="twitter">Twitter</option>
										<option value="linkedin">Linkedin</option>
										<option value="blog">Blog</option>
									</select>
								</div>
								<div class="col-12" style="margin-top:15px;">
									<h6>Name</h6>
									<input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Name of contact" required>
								</div>
								<div class="col-12" style="margin-top:15px;">
									<h6>Contact</h6>
									<input type="text" id="contact" name="contact" class="form-control" placeholder="Contact" required>
								</div>
								<div class="col-12" style="margin-top:15px;">
									<h6>Location</h6>
									<input type="text" id="location" name="location" class="form-control" placeholder="Location">
								</div>
								<div class="col-12" style="margin-top:15px;">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>&nbsp;&nbsp;
									<button type="submit" class="btn btn-success" name="save_contact">Save</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<script>
			var user_id="<?php echo $_COOKIE['uid']; ?>";
			var base_url="<?php echo base_url; ?>";
			function users_contacts_data_hub()
			{
				$.ajax({
					url:base_url+'users-contacts',
					type:"post",
					success:function(response)
					{
						var parsed_json=JSON.parse(response);
						if(parsed_json.status=="success")
						{
							$("#users_contacts_data_hub").html(parsed_json.data);
							$("#users_contacts").modal("show");
						}
						else if(parsed_json.status=="timeout")
						{
							alert("session has been timeout.");
							window.location.href=base_url+"logout";
						}
					}
				});
			}
			function followed_users_data_hub()
			{
				$.ajax({
					url:base_url+'followed-users',
					type:"post",
					success:function(response)
					{
						var parsed_json=JSON.parse(response);
						if(parsed_json.status=="success")
						{
							$("#followed_users_data_hub").html(parsed_json.data);
							$("#followed_users").modal("show");
						}
						else if(parsed_json.status=="timeout")
						{
							alert("session has been timeout.");
							window.location.href=base_url+"logout";
						}
					}
				});
			}
			function requested_user_data_hub()
			{
				$.ajax({
					url:base_url+'requested-users',
					type:"post",
					success:function(response)
					{
						var parsed_json=JSON.parse(response);
						if(parsed_json.status=="success")
						{
							$("#requested_user_data_hub").html(parsed_json.data);
							$("#requested_users").modal("show");
						}
						else if(parsed_json.status=="timeout")
						{
							alert("session has been timeout.");
							window.location.href=base_url+"logout";
						}
					}
				});
			}
			function ConnectUser(connection_user_id)
			{
				if(connection_user_id!='')
				{
					$.ajax({
						url:base_url+'connection-action',
						type:"post",
						data:{connection_user_id:connection_user_id,method:"connect"},
						success:function(response)
						{
							var parsed_json=JSON.parse(response);
							if(parsed_json.status=="success")
							{
								$("#user_section_home_"+connection_user_id).remove();
								//alert(parsed_json.message);
							}
							else
							{
								alert(parsed_json.message);
							}
						}
					});
				}
				else
				{
					alert('invalid action.');
				}
			}
			function DisConnectUser(connection_user_id)
			{
				if(connection_user_id!='')
				{
					$.ajax({
						url:base_url+'connection-action',
						type:"post",
						data:{connection_user_id:connection_user_id,method:"disconnect"},
						success:function(response)
						{
							var parsed_json=JSON.parse(response);
							if(parsed_json.status=="success")
							{
								$("#user_section_bridge_"+connection_user_id).remove();
								//alert(parsed_json.message);
							}
							else
							{
								alert(parsed_json.message);
							}
						}
					});
				}
				else
				{
					alert('invalid action.');
				}
			}
			function RejectUser(connection_user_id)
			{
				if(connection_user_id!='')
				{
					$.ajax({
						url:base_url+'connection-action',
						type:"post",
						data:{connection_user_id:connection_user_id,method:"reject"},
						success:function(response)
						{
							var parsed_json=JSON.parse(response);
							if(parsed_json.status=="success")
							{
								$("#user_section_requests_"+connection_user_id).remove();
								//alert(parsed_json.message);
							}
							else
							{
								alert(parsed_json.message);
							}
						}
					});
				}
				else
				{
					alert('invalid action.');
				}
			}
			function CancelUser(connection_user_id)
			{
				if(connection_user_id!='')
				{
					$.ajax({
						url:base_url+'connection-action',
						type:"post",
						data:{connection_user_id:connection_user_id,method:"cancel"},
						success:function(response)
						{
							var parsed_json=JSON.parse(response);
							if(parsed_json.status=="success")
							{
								$("#user_section_requested_"+connection_user_id).remove();
								//alert(parsed_json.message);
							}
							else
							{
								alert(parsed_json.message);
							}
						}
					});
				}
				else
				{
					alert('invalid action.');
				}
			}
			function AcceptUser(connection_user_id)
			{
				if(connection_user_id!='')
				{
					$.ajax({
						url:base_url+'connection-action',
						type:"post",
						data:{connection_user_id:connection_user_id,method:"accept"},
						success:function(response)
						{
							var parsed_json=JSON.parse(response);
							if(parsed_json.status=="success")
							{
								$("#user_section_requests_"+connection_user_id).remove();
								//alert(parsed_json.message);
							}
							else
							{
								alert(parsed_json.message);
							}
						}
					});
				}
				else
				{
					alert('invalid action.');
				}
			}
			function LoadData(data_section)
			{
                localStorage.setItem('load_section',data_section);
				$("#"+data_section).load(base_url+"load-"+data_section);
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
			loadImageSlider("user_section_home");
            var load_section='home';
            if(localStorage.getItem('load_section')===null)
            {
                localStorage.setItem('load_section',load_section);
            }
            else
            {
                load_section=localStorage.getItem('load_section');
            }
            $("#"+load_section+"-tab").click();
		</script>
		<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
		<script>
			var ropeyou = angular.module('myApp',[]);
			ropeyou.controller('BridgeController', ['$scope', function($scope) {
				$scope.greeting = 'Hola!';
				//alert($scope.greeting);
			}]);
		</script>
	</body>
</html>
