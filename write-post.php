<!DOCTYPE html>
<html lang="en">
	<head>
		<?php 
			include_once 'head.php';
			if(isset($_POST['save']))
			{
				$title=filter_var($_POST['title'],FILTER_SANITIZE_STRING);
				$post_url=filter_var(strtolower($_POST['post_url']),FILTER_SANITIZE_STRING);
				$post_url=str_replace(" ","-",$post_url);
				$blog_space_id=$_POST['blog_space_id'];
				$visibility=$_POST['visibility'];
				$description=filter_var($_POST['description'],FILTER_SANITIZE_STRING);
				$seo_tags=filter_var($_POST['seo_tags'],FILTER_SANITIZE_STRING);
				$seo_keywords=filter_var($_POST['seo_keywords'],FILTER_SANITIZE_STRING);
				$seo_title=filter_var($_POST['seo_title'],FILTER_SANITIZE_STRING);
				$seo_description=filter_var($_POST['seo_description'],FILTER_SANITIZE_STRING);
				$post_image="";
				$post_banner="";
				$upload_post_image=uploadImageToDir("post_image");
				$upload_post_banner=uploadImageToDir("post_banner");
				if($upload_post_image[0])
				{
					$post_image=$upload_post_image[2];
				}
				if($upload_post_banner[0])
				{
					$post_banner=$upload_post_banner[2];
				}
				$check_query="SELECT * FROM blog_space_posts WHERE post_url='$post_url'";
				$check_result=mysqli_query($conn,$check_query);
				if(mysqli_num_rows($check_result)>0)
				{
					?>
					<script>
						alert("This Post URL has been aquired by someone else.");
					</script>
					<?php
				}
				else
				{
					$visibility_arr=explode(",",$visibility);
					$is_public=$visibility_arr[0];
					$is_private=$visibility_arr[1];
					$is_protected=$visibility_arr[2];
					$is_friendly_protected=$visibility_arr[3];
					$is_magic=$visibility_arr[4];
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
					$insert_query="INSERT INTO blog_space_posts SET users_blocked='$users_blocked',users_allowed='$users_allowed',is_magic='$is_magic',is_friendly_protected='$is_friendly_protected',is_protected='$is_protected',is_private='$is_private',is_public='$is_public',blog_space_id='$blog_space_id',title='$title',post_image='$post_image',post_banner='$post_banner',post_url='$post_url',visibility='$visibility',description='$description',seo_tags='$seo_tags',seo_title='$seo_title',seo_description='$seo_description',seo_keywords='$seo_keywords',created=NOW(),status=1,user_id='".$_COOKIE['uid']."'";
					if(mysqli_query($conn,$insert_query))
					{
						?>
						<script>
							alert("Post has been created.");
							window.location.href="<?php echo blog_space_url.$space_url; ?>";
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
			$blog_space_content=getBlogSpace($space_url);	
			$blog_space_id=$blog_space_content['id'];
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
						<div class="box mb-3 shadow-sm border rounded bg-white profile-box text-center">
							<div class="py-3 px-3 border-bottom">
								<?php 
									$blog_space_profile_image=getBlogSpaceProfileImage($space_url); 
								?>
								<div class="image-container-custom" style="width:100%;">
									<img id="user_profile_picture" src="<?php echo $blog_space_profile_image; ?>"  data-src="<?php echo $blog_space_profile_image; ?>"  class="img-fluid mt-2 rounded-circle image" style="width:150px;height:150px;border:1px solid #eaebec !important;" alt="<?php echo ucwords(strtolower($blog_space_content['title'])); ?>">
									<div class="overlay" onclick="personal_gallery_media_data();" data-toggle="modal" data-target="#amazing_profile_image_backdrop_modal"><i class="feather-edit"></i><br>Change</div>		
								</div>
								<h6 class="font-weight-bold text-dark mb-1 mt-4"><?php echo ucwords(strtolower($blog_space_content['title'])); ?></h6>
							</div>
						</div>
						<div class="box mb-3 shadow-sm border rounded bg-white list-sidebar">
						 	<div class="box-title p-2 border-bottom">
								<h6 class="m-0">Your Blog Posts</h6>
						 	</div>
						 	<div class="box-body p-0" style="min-height:200px;">
						 		<div class="row" style="padding:10px;">
									<div class="col-md-12 border-bottom">
										<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1"><a href="<?php echo blog_space_url.'create-blog-space'; ?>">Create new blog space&nbsp;&nbsp;<i class="fa fa-pencil pull-right"></i></a></h6>
									</div>
						 			<div class="col-md-12 border-bottom">
										<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1"><a href="<?php echo blog_space_url.$space_url.'/write-post'; ?>">Write a post&nbsp;&nbsp;<i class="fa fa-pencil pull-right"></i></a></h6>
									</div>
								 	<?php
								 		$blog_space_posts_query="SELECT id,post_url,title FROM blog_space_posts WHERE blog_space_id='$blog_space_id' AND status=1 ORDER BY id DESC LIMIT 0,10";
										$blog_space_posts_result=mysqli_query($conn,$blog_space_posts_query);
										if(mysqli_num_rows($blog_space_posts_result)>0)
										{
											while($blog_space_posts_row=mysqli_fetch_array($blog_space_posts_result))
											{
												?>
												<div class="col-md-12 border-bottom">
													<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1">
														<a href="<?php echo blog_space_url.$space_url.'/'.$blog_space_posts_row['post_url']; ?>">
																<i class="fa fa-arrow-right"></i>&nbsp;&nbsp;<?php echo $blog_space_posts_row['title']; ?>
														</a>
													</h6>
												</div>
												<?php
											}
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
											<div class="col-md-12" style="margin-bottom:30px;">
												<h6>Write A Blog Post<button class="btn btn-success pull-right" name="save" value="1">Save</button></h6>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="hidden" name="blog_space_id" id="blog_space_id" value="<?php echo $blog_space_id; ?>">
													<h6>Post Title*</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="title" placeholder="Your post title..." required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Post URL Slug*</h6>
													<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="post_url" id="space_url" placeholder="Your blog space username..." required>
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
											<div class="col-md-12 bg-grey text-white" style="margin-bottom:10px;padding:10px;font-size:12px;">
												<p>Your post url will be look like : <a href="javascript:void(0);" class="pull-right btn btn-warning" onclick="checkIfPostUrlAvaialable();">Check Availability</a></p>
												<?php echo blog_space_url.$space_url.'/'; ?><span style="font-size:12px;" id="blog_space_url_preview"><?php echo time().'-'.mt_rand(0,9999) ?></span>

												<p id="url_available_status" class="text-danger"></p>
											</div>
											<input type="hidden" name="url_available" id="url_available" value="">
											<div class="col-md-6">
												<div class="form-group">
													<h6>Space Profile Image</h6>
													
													<input type="file" accept="image/*" style="height:30px;border-radius:0px;" class="form-control" name="post_image">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Space Banner Image</h6>
													<input type="file" accept="image/*" style="height:30px;border-radius:0px;" class="form-control" name="post_banner">
												</div>
											</div>
										</div>
										<div class="form-group">
											<h6>Description*</h6>
											<textarea name="description" style="resize:none;" id="description" class="form-control" rows="10" placeholder="Description for the Post"></textarea>
										</div>
										<div class="form-group">
											<h6>SEO Title</h6>
											<input type="text" class="form-control" value="" style="height:30px;border-radius:0px;" name="seo_title" id="seo_title" placeholder="Your SEO Title can be different then your post title.">
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
											<textarea name="seo_description" style="resize:none;" id="seo_description" class="form-control" rows="3" placeholder="Seo Meta description for the post"></textarea>
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
	function checkIfPostUrlAvaialable(){
		var space_url=$("#space_url").val();
		space_url=space_url.replace(/ /g, "-");
		$.ajax({
			url:base_url+"check-post-url-availability",
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