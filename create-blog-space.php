<!DOCTYPE html>
<html lang="en">
	<head>
		<?php 
			include_once 'head.php';
			if(isset($_POST['save']))
			{
				$title=addslashes(filter_var($_POST['title'],FILTER_SANITIZE_STRING));
				$space_url=filter_var(strtolower($_POST['space_url']),FILTER_SANITIZE_STRING);
				$space_url=str_replace(" ","-",$space_url);
				$category=$_POST['category'];
				$url_available=$_POST['url_available'];
				$twitter=$_POST['twitter'];
				$facebook=$_POST['facebook'];
				$linkedin=$_POST['linkedin'];
				$instagram=$_POST['instagram'];
				$website=$_POST['website'];
				$visibility=$_POST['visibility'];
				
				$description=addslashes(filter_var($_POST['description'],FILTER_SANITIZE_STRING));
				$seo_tags=filter_var($_POST['seo_tags'],FILTER_SANITIZE_STRING);
				$seo_keywords=filter_var($_POST['seo_keywords'],FILTER_SANITIZE_STRING);
				$seo_title=filter_var($_POST['seo_title'],FILTER_SANITIZE_STRING);
				$seo_description=addslashes(filter_var($_POST['seo_description'],FILTER_SANITIZE_STRING));
				$space_image="";
				$space_banner="";
				$upload_space_image=uploadImageToDir("space_image");
				$upload_space_banner=uploadImageToDir("space_banner");
				if($upload_space_image[0])
				{
					$space_image=$upload_space_image[2];
				}
				if($upload_space_banner[0])
				{
					$space_banner=$upload_space_banner[2];
				}
				
				$visibility_arr=explode(",",$visibility);
				$is_public=$visibility_arr[0];
				$is_private=$visibility_arr[1];
				$is_protected=$visibility_arr[2];
				$is_friendly_protected=$visibility_arr[3];
				$is_magic=$visibility_arr[4];
				
				$check_query="SELECT * FROM blog_spaces WHERE space_url='$space_url'";
				$check_result=mysqli_query($conn,$check_query);
				if(mysqli_num_rows($check_result)>0)
				{
					?>
					<script>
						alert("This Space URL has been aquired by someone else.");
					</script>
					<?php
				}
				else
				{
					$users_allowed="";
					$users_blocked="";
					if($is_magic!="0")
					{
						if($is_magic=="1")
						{
							$users_allowed=$_POST['post_users_allowed'];
						}
						else if($is_magic=="2")
						{
							$users_blocked=$_POST['post_users_blocked'];
						}
					}
					$insert_query="INSERT INTO blog_spaces SET users_blocked='$users_blocked',users_allowed='$users_allowed',is_magic='$is_magic',is_friendly_protected='$is_friendly_protected',is_protected='$is_protected',is_private='$is_private',is_public='$is_public',title='$title',space_image='$space_image',space_banner='$space_banner',category='$category',space_url='$space_url',twitter='$twitter',instagram='$instagram',facebook='$facebook',linkedin='$linkedin',website='$website',visibility='$visibility',description='$description',seo_tags='$seo_tags',seo_title='$seo_title',seo_description='$seo_description',seo_keywords='$seo_keywords',created=NOW(),status=1,user_id='".$_COOKIE['uid']."'";
					if(mysqli_query($conn,$insert_query))
					{
						?>
						<script>
							alert("Blog space has been created.");
							window.location.href="<?php echo blog_space_url; ?>";
						</script>
						<?php
					}
					else
					{
						echo $insert_query;die();
						?>
						<script>
							alert("There was some issue please try again.");
							//window.location.href="<?php echo blog_space_url; ?>";
						</script>
						<?php
					}
				}
			}
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Create-Blog-Space | RopeYou Connects</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>css/feeling.css" />
	</head>
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   	<aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
						<?php
							include_once 'user-profile-section.php';
						?>
						<div class="box mb-3 shadow-sm border rounded bg-white list-sidebar">
						 	<div class="box-title p-2 border-bottom">
								<h6 class="m-0">Your Blog Space</h6>
						 	</div>
						 	<div class="box-body p-0" style="min-height:200px;">
						 		<div class="row">
								 	<?php
										if(userHasBlogs($_COOKIE['uid']))
										{
											echo getUserBlogNavigationHtml($_COOKIE['uid']);
										}
										else
										{
											echo createBlogNavigationHtml($_COOKIE['uid']);
										}
									?>
								</div>
							</div>
						</div>
					</aside>
					<main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
						<div class="box mb-3 p-3 shadow-sm border rounded bg-white list-sidebar">
							<div class="row">
								<div class="col-md-12">
									<form action="" method="post" id="blog_space_form" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-12" style="padding:20px;">
												<h6>Create New Blog Space <button class="btn btn-success pull-right" name="save" value="1">Save</button></h6>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="hidden" name="blogdata" id="blogdata" value="">
													<h6>Blog Space Title*</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="title" placeholder="Your blog space title..." required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Blog Space Username*</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="space_url" id="space_url" placeholder="Your blog space username..." required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Category*</h6>
													<select class="form-control" name="category" id="category" required>
														<option value="">Select Category</option>
														<?php
															$blog_space_category_query="SELECT * FROM blog_space_categories WHERE status=1 ORDER BY title";
															$blog_space_category_result=mysqli_query($conn,$blog_space_category_query);
															if(mysqli_num_rows($blog_space_category_result)>0)
															{
																while($blog_space_category_row=mysqli_fetch_array($blog_space_category_result))
																{
																	?>
																	<option value="<?php echo $blog_space_category_row['id']; ?>"><?php echo $blog_space_category_row['title']; ?></option>
																	<?php
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-12 bg-grey text-white" style="margin-bottom:10px;padding:10px;font-size:12px;">
												<p>Your blog space url will be look like : <a href="javascript:void(0);" class="pull-right btn btn-warning" onclick="checkIfUrlAvaialable();">Check Availability</a></p>
												<?php echo blog_space_url; ?><span style="font-size:12px;" id="blog_space_url_preview"><?php echo time().'-'.mt_rand(0,9999) ?></span>

												<p id="url_available_status" class="text-danger"></p>
											</div>
											<input type="hidden" name="url_available" id="url_available" value="">
											<div class="col-md-6">
												<div class="form-group">
													<h6>Space Profile Image</h6>
													
													<input type="file" accept="image/*" style="height:30px;border-radius:0px;" class="form-control" name="space_image">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Space Banner Image</h6>
													<input type="file" accept="image/*" style="height:30px;border-radius:0px;" class="form-control" name="space_banner">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Twitter URL</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="twitter" placeholder="https://twitter.com/username">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Facebook URL</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="facebook" placeholder="https://facebook.com/username">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Instagram URL</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="instagram" placeholder="https://instagram.com/username">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Linkedin URL</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="linkedin" placeholder="https://linkedin.com/in/username">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Website</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="website" placeholder="Your Website URL">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Visibility*</h6>
													<select class="form-control" required name="visibility" id="visibility">
														<option value="1,0,0,0,0" selected>Anyone</option>
														<option value="0,1,0,0,0">Only Me</option>
														<option value="0,0,1,0,0">Only Connections</option>
														<option value="0,0,1,1,0">Connections of Connections</option>
														<option value="0,0,1,0,1">Allow Specific Connections</option>
														<option value="0,0,1,0,2">Block Specific Connections</option>
													</select>
												</div>
											</div>
											<input type="hidden" name="post_users_blocked" value="" id="post_users_blocked">
											<input type="hidden" name="post_users_allowed" value="" id="post_users_allowed">
										</div>
										<div class="form-group">
											<h6>Description*</h6>
											<textarea name="description" style="resize:none;" id="description" class="form-control" rows="3" placeholder="Description for the blog space"></textarea>
										</div>
										<div class="form-group">
											<h6>SEO Title</h6>
											<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="seo_title" id="seo_title" placeholder="Your SEO Title can be different then your blog space title.">
										</div>
										<div class="form-group">
											<h6>SEO Tags</h6>
											<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="seo_tags" placeholder="Comma Seperated Tags...">
										</div>
										<div class="form-group">
											<h6>SEO Keywords</h6>
											<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="seo_keywords" placeholder="Comma Seperated Keywords...">
										</div>
										<div class="form-group">
											<h6>Seo Description</h6>
											<textarea name="seo_description" style="resize:none;" id="seo_description" class="form-control" rows="3" placeholder="Seo Meta description for the blog space"></textarea>
										</div>
										<div class="col-md-12" style="padding:20px;">
											<button class="btn btn-success pull-right" name="save" value="1">Save</button>
										</div>
									</form>
								</div>
							</div>	
						</div>	
					</main>
					<aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
						<?php include_once 'people_you_may_know.php'; ?>
					</aside>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php'; ?>
		<script type="text/javascript" src="<?php echo base_url; ?>js/feeling.js"></script>
		<script>
			localStorage.setItem("uid","<?php echo $_COOKIE['uid']; ?>");
			localStorage.setItem("base_url","<?php echo base_url; ?>");
		</script>
   </body>
</html>
<script async src="https://guteurls.de/guteurls.js" selector=".url_meta"> </script> 
<script>
	var base_url="<?php echo base_url; ?>";
	$("#space_url").on("keyup",function(){
		var space_url=$("#space_url").val();
		space_url=space_url.replace(/ /g, "-");
		$("#blog_space_url_preview").text(space_url);
		$("#url_available").val(0);
		$("#url_available_status").text("Please click on check availability button to know about availability of this url.");
	});
	function checkIfUrlAvaialable(){
		var space_url=$("#space_url").val();
		space_url=space_url.replace(/ /g, "-");
		$.ajax({
			url:base_url+"check-blog-space-url-availability",
			type:"post",
			data:{space_url:space_url},
			success:function(response)
			{
				var parsedJson=JSON.parse(response);
				if(parsedJson.status=="success")
				{
					$("#url_available").val(1);
					$("#url_available_status").text("Available");
				}
				else
				{
					$("#url_available").val(0);
					$("#url_available_status").text("Already aquired by someone else.");
				}
			}

		});
	}
</script>