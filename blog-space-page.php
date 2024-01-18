<!DOCTYPE html>
<html lang="en">
	<head>
		<?php 
			include_once 'head.php';
			include_once 'blog_space_functions.php';
			$blog_space_content=getBlogSpace($space_url);	
			$blog_space_id=$blog_space_content['id'];
									
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
		<title><?php echo $blog_space_content['title']; ?> | RopeYou Connects</title>
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
								<h6 class="font-weight-bold text-dark mb-1 mt-4"><a href="<?php echo blog_space_url.$space_url; ?>"><?php echo ucwords(strtolower($blog_space_content['title'])); ?></a></h6>
								<?php	
									if(isLogin() && isDeservingBlogSpace($_COOKIE['uid'],$blog_space_id))
										{
											$who_can_see_broadcast_post_option_post=$blog_space_content['is_public'].",".$blog_space_content['is_private'].",".$blog_space_content['is_protected'].",".$blog_space_content['is_friendly_protected'].",".$blog_space_content['is_magic'];
											
											?>
											<div class="col-md-12 border-bottom">
												<a href="javascript:void(0);" style="text-align:center;" <?php if($_COOKIE['uid']==$blog_space_content['user_id']){ ?>data-token="<?php echo md5($blog_space_content['id']); ?>" data-ua="<?php echo $blog_space_content['users_allowed']; ?>" data-ub="<?php echo $blog_space_content['users_blocked']; ?>" id="change_blog_space_visibility_<?php echo $blog_space_content['id']; ?>" onclick="changeContentVisibility('<?php echo $blog_space_content['id']; ?>','blog_space');" data-type="post" data-setting="<?php echo $who_can_see_broadcast_post_option_post; ?>" <?php } ?>><i class="fa <?php 
															if($who_can_see_broadcast_post_option_post=="1,0,0,0,0"){ echo "fa-globe"; }
															if($who_can_see_broadcast_post_option_post=="0,1,0,0,0"){ echo "fa-user"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,0,0"){ echo "fa-users"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,1,0"){ echo "fa-users"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,0,1"){ echo "fa-users"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,0,2"){ echo "fa-users"; }
														?>"></i>&nbsp;&nbsp;<?php 
															if($who_can_see_broadcast_post_option_post=="1,0,0,0,0"){ echo "Anyone"; }
															if($who_can_see_broadcast_post_option_post=="0,1,0,0,0"){ echo "Only Me"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,0,0"){ echo "Only Connections"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,1,0"){ echo "Connections of Connections"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,0,1"){ echo "+Allowed Specific Connections"; }
															if($who_can_see_broadcast_post_option_post=="0,0,1,0,2"){ echo "-Blocked Specific Connections"; }
														?>
												</a>
											</div>
								<?php
										}
								?>
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
								 	<?php
										if(isLogin() && isDeservingBlogSpace($_COOKIE['uid'],$blog_space_id))
										{
											?>
											<div class="col-md-12 border-bottom">
												<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1"><a href="<?php echo blog_space_url.$space_url.'/write-post'; ?>">Write a post&nbsp;&nbsp;<i class="fa fa-pencil pull-right"></i></a></h6>
											</div>
											<?php
										}
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
										if($blog_space_content['description']!="")
										{
											?>
											<div class="col-md-12 border-bottom border-top" style="margin-top:100px;">
												<h6 style="font-size:18px;font-weight:normal;margin:0px;" class="p-1">
													<u>About <?php echo $blog_space_content['title']; ?></u>
												</h6>
												<p><?php echo $blog_space_content['description']; ?></p>
											</div>
											<?php
										}
								 	?>
								</div>
							</div>
						</div>
					</aside>
					<main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
						<div class="row">
							<div class="col-md-12">
								<?php
									$blog_space_posts_query="SELECT id FROM blog_space_posts WHERE blog_space_id='$blog_space_id' AND status=1";
									$blog_space_posts_result=mysqli_query($conn,$blog_space_posts_query);
									if(mysqli_num_rows($blog_space_posts_result)>0)
									{
										while($blog_space_posts_row=mysqli_fetch_array($blog_space_posts_result))
										{
											$bsf=new BlogSpaceFunctions();
											$recieved_html= $bsf->blogSpacePostMini($blog_space_posts_row['id']);
											if($recieved_html=="")
											{
												echo $bsf->blogPostNothingFound();
											}
											else
											{
												echo $recieved_html;
											}
										}
									}
									else
									{
										$bsf=new BlogSpaceFunctions();
										echo $bsf->blogPostNothingFound();
										//echo '<h6 style="text-align:center;margin-top:100px;margin-bottom:100px;">Nothing to display</h6>';
									}
								?>
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
			var base_url="<?php echo base_url; ?>";
			
			function shareUrlOnFB(url){
				FB.ui({
				  method: 'share',
				  href: url
				}, function(response){});
			}
			window.fbAsyncInit = function() {
				FB.init({
				  appId      : '<?php echo fb_app_id; ?>',
				  cookie     : true,   
				  xfbml      : true,  
				  version    : 'v5.0' 
				});
				
			};
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "https://connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			  }(document, 'script', 'facebook-jssdk'));
			  
			function deleteBlogPost(blog_space_post_id,html_type,html_remove)
			{
				if(blog_space_post_id!="")
				{
					$.ajax({
						url:base_url+'delete-blog-post',
						type:'post',
						data:{blog_space_post_id:blog_space_post_id},
						success:function(response){
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								if(html_remove=="remove")
								{
									$("#blog_post_"+blog_space_post_id).remove();
								}
								else
								{
									var redirect_url=$("#blog_delete_"+blog_space_post_id).data("url");
									window.location.href=redirect_url;
								}
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
					alert('something is not right.Please try reloading the page.');
				}
			}
		</script>
   </body>
</html>