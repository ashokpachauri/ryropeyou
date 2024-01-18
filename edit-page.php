<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head_without_session.php'; ?>
		<?php
			if(isset($_POST['continue']))
			{
				$page_title=$_POST['page_title'];
			}
		?>
		<title>Pages | RopeYou Connects</title>
	</head>
	<body style="min-height:calc(100vh);">
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container" style="bottom:0px;">
				<div class="row">
				   <!-- Main Content -->
					<?php
						if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="")
						{
							$mx_auto="";
							?>
							<aside class="col-md-4">
							
							</aside>
							<?php
						}
						else
						{
							$mx_auto="mx-auto";
						}
					?>
					<main class="col-md-8 <?php echo $mx_auto; ?>">
						<div class="shadow-sm rounded bg-white mb-3">
							<div class="box-title border-bottom p-3">
								<h5 class="mb-2">Page Info</h5>
							</div>
							<div class="box-body px-2 py-3">
								<div class="col-md-12">
									<form action="" id="page_form" method="post" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Page Title <span style="color:red;">*</span></h6>
													<input class="form-control" name="page_title" autocomplete="off" value="" id="page_title" required title="" type="text" placeholder="e.g, Google INC.">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Category <span style="color:red;">*</span></h6>
													<select class="form-control" name="category" autocomplete="off" id="category" required>
														<option value="">Select Category</option>
														<?php
															$cat_query="SELECT * FROM page_types WHERE status=1";
															$cat_result=mysqli_query($conn,$cat_query);
															while($cat_row=mysqli_fetch_array($cat_result))
															{
																?>
																<option value="<?php echo $cat_row['id']; ?>"><?php echo $cat_row['overlay_title']; ?></option>
																<?php
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Contact Number (optional)</h6>
													<input class="form-control" value="" name="contact_mobile" id="contact_mobile" title="" type="text" placeholder="e.g, +919876543210">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Website (optional)</h6>
													<input class="form-control" name="website" autocomplete="off" value="" id="website" title="" type="text" placeholder="e.g, https://google.com">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Contact Email <?php if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="") { ?>(optional) <?php } else { ?> <span style="color:red;">*</span> <?php }?></h6>
													<input class="form-control" <?php if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="") { } else { echo 'required'; }?> value="" name="contact_email" id="contact_email" title="" type="text" placeholder="e.g, pagecontact@pager.com">
												</div>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="form-group">
													<h6 style="margin-bottom:7px;">Logo or Image <span style="color:red;">*</span></h6>
													<input class="form-control" name="logo" autocomplete="off" value="" id="logo" required title="" type="file" accept="image/*">
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="form-group">
													<?php if(isset($_COOKIE['uid']) && $_COOKIE['uid']!="") {} else { ?> <p style="margin-top:20px;color:red;">Note :- We will send an OTP to your email for email verification.</p> <?php } ?>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12 text-right">
												<button class="btn btn-primary" type="submit" name="continue">Continue</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
				   </main>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<script src="https://cdn.tiny.cloud/1/uvg9xyxkcmqkpjaacpgnzrroxnefi5c72vf0k2u5686rwdmv/tinymce/5/tinymce.min.js"></script>
		<script>tinymce.init({selector:'textarea',height:520,branding:false,resize:false,statusbar:false});</script>
		<script>
			$("#job_company").change(function(){
				var job_company=$("#job_company").val();
				if(job_company=="")
				{
					$("#company_id").val('');
				}
				else
				{
					$("#company_id").val($("#job_company option:selected").text());
					//console.log($("#company_id option:selected").text());
				}
			});
			
			$("#video_description").change(function(){
				//var domele=$('#video_description_preview');
				var dom_data="<video controls id='video_description_ele' style='width:100%;'><source src='"+URL.createObjectURL(this.files[0])+"' type='video/mp4'></source></video>";
				$('#video_description_preview').html(dom_data);
				$("#video_description_ele").load();
				//$source[0].src = URL.createObjectURL(this.files[0]);
				//$source.parent()[0].load();
			});
		</script>
		<script>
		
		var placeSearch, autocomplete;

		var componentForm = {
		  street_number: 'short_name',
		  route: 'long_name',
		  locality: 'long_name',
		  administrative_area_level_1: 'short_name',
		  country: 'long_name',
		  postal_code: 'short_name'
		};

		function initAutocomplete() {
		  autocomplete = new google.maps.places.Autocomplete(
			  document.getElementById('autocomplete'), {types: ['geocode']});

		  autocomplete.setFields(['address_component']);

		  autocomplete.addListener('place_changed', fillInAddress);
		}

		function fillInAddress() {
		  var place = autocomplete.getPlace();
		  console.log(autocomplete);
		  for (var component in componentForm) {
			document.getElementById(component).value = '';
			document.getElementById(component).disabled = false;
		  }

		  for (var i = 0; i < place.address_components.length; i++) {
			var addressType = place.address_components[i].types[0];
			if (componentForm[addressType]) {
			  var val = place.address_components[i][componentForm[addressType]];
			  document.getElementById(addressType).value = val;
			}
		  }
		}

		function geolocate() {
		  if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
			  var geolocation = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			  };
			  var circle = new google.maps.Circle(
				  {center: geolocation, radius: position.coords.accuracy});
			  autocomplete.setBounds(circle.getBounds());
			});
		  }
		}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa27gHtUg_79772tpFkwzruM89feLVmiI&libraries=places&callback=initAutocomplete" async defer></script>
   </body>
</html>
