<!DOCTYPE html>
<html lang="en">
	<head>
		<?php 
			include_once 'head_without_session.php';
			include_once 'blog_space_functions.php';
			$blog_space_content=getBlogSpace($space_url);	
			$blog_space_id=$blog_space_content['id'];
			$blog_space_post_content=getBlogSpacePost($post_url);
			$blog_space_post_id=$blog_space_post_content['id'];						
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="<?php echo $blog_space_content['title']; ?> | RopeYou">
		<meta name="title" content="<?php echo $blog_space_post_content['seo_title']; ?>">
		<meta name="keywords" content="<?php echo $blog_space_post_content['seo_keywords']; ?>">
		<meta name="description" content="<?php echo $blog_space_post_content['seo_description']; ?>">
		
		<!----Facebook---------------------->
		<meta property="og:title" content="<?php echo $blog_space_post_content['title']; ?>">
		<meta property="og:description" content="<?php echo substr($blog_space_post_content['description'],0,200); ?>">
		<meta property="og:image" content="<?php echo base_url.$blog_space_post_content['post_image']; ?>">
		<meta property="og:url" content="<?php echo blog_space_url.$space_url.'/'.$post_url; ?>">
		
		<!----------Twitter-------------------------->
		<meta property="twitter:title" content="<?php echo $blog_space_post_content['title']; ?>">
		<meta property="twitter:description" content="<?php echo substr($blog_space_post_content['description'],0,200); ?>">
		<meta property="twitter:image" content="<?php echo base_url.$blog_space_post_content['post_image']; ?>">
		<meta property="twitter:card" content="<?php echo base_url.$blog_space_post_content['post_image']; ?>">
		<meta property="twitter:url" content="<?php echo blog_space_url.$space_url.'/'.$post_url; ?>">
		
		<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
		
		
		<title><?php echo $blog_space_post_content['title']; ?> - <?php echo $blog_space_content['title']; ?> | RopeYou Connects</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>css/feeling.css" />
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
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
								<h6 class="font-weight-bold text-dark mb-1 mt-4"><a href="<?php echo blog_space_url.$space_url; ?>"><?php echo ucwords(strtolower($blog_space_content['title'])); ?></a> / <a href="<?php echo blog_space_url.$space_url.'/'.$post_url; ?>"><?php echo ucwords(strtolower($blog_space_post_content['title'])); ?></a></h6>
								<?php
									if(isLogin() && isDeserving($_COOKIE['uid'],$blog_space_post_id))
									{
										$who_can_see_broadcast_post_option_post=$blog_space_post_content['is_public'].",".$blog_space_post_content['is_private'].",".$blog_space_post_content['is_protected'].",".$blog_space_post_content['is_friendly_protected'].",".$blog_space_post_content['is_magic'];
										?>
										
										<div class="col-md-12 border-bottom">
											<a href="javascript:void(0);" style="text-align:center;" <?php if($_COOKIE['uid']==$blog_space_post_content['user_id']){ ?>data-token="<?php echo md5($blog_space_post_content['id']); ?>" data-ua="<?php echo $blog_space_post_content['users_allowed']; ?>" data-ub="<?php echo $blog_space_post_content['users_blocked']; ?>" id="change_blog_space_post_visibility_<?php echo $blog_space_post_content['id']; ?>" onclick="changeContentVisibility('<?php echo $blog_space_post_content['id']; ?>','blog_space_post');" data-type="post" data-setting="<?php echo $who_can_see_broadcast_post_option_post; ?>" <?php } ?>><i class="fa <?php 
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
										if(isLogin() && isDeserving($_COOKIE['uid'],$blog_space_post_id))
										{
											?>
											<div class="col-md-12 border-bottom">
												<h6 style="font-size:12px;font-weight:normal;margin:0px;" class="p-1"><a href="<?php echo blog_space_url.$space_url.'/write-post'; ?>">Write a post&nbsp;&nbsp;<i class="fa fa-pencil pull-right"></i></a></h6>
											</div>
											<?php
										}
										$blog_space_posts_query="SELECT id,post_url,title FROM blog_space_posts WHERE blog_space_id='$blog_space_id' AND id!='$blog_space_post_id' AND status=1 ORDER BY id DESC LIMIT 0,10";
										$blog_space_posts_result=mysqli_query($conn,$blog_space_posts_query);
										if(mysqli_num_rows($blog_space_posts_result)>0)
										{
											?>
												<div class="col-md-12 border-bottom">
													<h6 style="font-size:14px;font-weight:normal;margin:0px;" class="p-1">
														Other Posts
													</h6>
												</div>
											<?php
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
										if(isLogin() && isDeserving($_COOKIE['uid'],$blog_space_post_id))
										{
											$who_can_see_broadcast_post_option_post=$blog_space_post_content['is_public'].",".$blog_space_post_content['is_private'].",".$blog_space_post_content['is_protected'].",".$blog_space_post_content['is_friendly_protected'].",".$blog_space_post_content['is_magic'];
											?>
											<div class="col-md-12" style="margin:5px;">
												<a href="<?php echo blog_space_url.$space_url.'/'.$blog_space_post_content['post_url'].'/edit'; ?>" class="btn btn-primary pull-left" title="Edit"><i class="feather-edit"></i> Edit</a>&nbsp;&nbsp;
												<a href="javascript:void(0);" data-url="<?php echo blog_space_url.$space_url; ?>" id="blog_delete_<?php echo $blog_space_post_id; ?>" class="btn btn-danger pull-right" onclick="deleteBlogPost('<?php echo $blog_space_post_id; ?>','max','reload');" title="Delete"><i class="feather-trash"></i> Delete</a>&nbsp;&nbsp;
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
									if($blog_space_post_id!="")
									{
										$bsf=new BlogSpaceFunctions();
										$recieved_html= $bsf->blogSpacePostMax($blog_space_post_id);
										if($recieved_html=="")
										{
											echo $bsf->blogPostNothingFound();
										}
										else
										{
											echo $recieved_html;
										}
									}
									else
									{
										$bsf=new BlogSpaceFunctions();
										echo $bsf->blogPostNothingFound();
										//echo '<h6 style="text-align:center;margin-top:100px;margin-bottom:100px;">Nothing to display</h6>';
									}
									if(isset($insert_id)){
								?>
								<div class="row" id="<?php echo $insert_id; ?>"></div>
								<?php
									}
								?>
							</div>
						</div>	
						<div class="modal fade edit_comment" id="edit_comment_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit_comment_modalBackdrop" aria-hidden="true">
							<div class="modal-dialog modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h6 class="modal-title" id="edit_comment_modalBackdrop" style="width:100%;">Edit Comment<button class="pull-right btn btn-danger" data-dismiss="modal" style="float:right !important;"><i class="fa fa-times"></i></button></h6>
									</div>
									<div class="modal-body">											
										<div class="row">
											<div class="col-md-12">
												<textarea id="edit_comment_text" style="resize:none;" rows="3" name="comment_text" class="form-control"></textarea>
											</div>
											<div class="col-md-12" style="margin-top:20px;">
												<button class="btn btn-success pull-right" data-bspid="" data-cid="" data-ct="" onclick="UpdateComment();" id="update_comment" type="button" name="" id="">Update</button>
											</div>
										</div>
									</div>
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
			var base_url="<?php echo base_url; ?>";
			function deleteBlogPostComment(comment_id="",comment_type="")
			{
				if(comment_id!="" && comment_type!="")
				{
					$.ajax({
						url:base_url+'delete-blog-post-comment',
						type:'post',
						data:{comment_id:comment_id,comment_type:comment_type},
						success:function(response){
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								var c_id=parsedJson.c_id;
								var c_type=parsedJson.c_type;
								$("#comment_"+c_type+"_"+c_id).remove();
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
					alert('something is not righ.Please try reloading the page.');
				}
			}
			function editBlogPostComment(comment_id="",comment_type="")
			{
				if(comment_id!="" && comment_type!="")
				{
					$.ajax({
						url:base_url+'get-comment-data',
						type:'post',
						data:{comment_id:comment_id,comment_type:comment_type},
						success:function(response)
						{
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								var ct="main";
								if(comment_type=="child" || comment_type=="")
								{
									ct="";
								}
								$("#edit_comment_text").val(parsedJson.comment_text);
								
								$("#update_comment").data("bspid",parsedJson.blog_space_post_id);
								$("#update_comment").data("cid",comment_id);
								$("#update_comment").data("ct",ct);
								$("#edit_comment_modal").modal('show');
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
					alert('There is some issue please try reloading.');
				}
			}
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
			function UpdateComment()
			{
				$("#edit_comment_text").css({"outline":"none"});
				if($("#edit_comment_text").val()!="")
				{
					var blog_space_post_id=$("#update_comment").data('bspid');
					var comment_id=$("#update_comment").data('cid');
					var comment_type=$("#update_comment").data('ct');
					var comment_text=$("#edit_comment_text").val();
					$.ajax({
						url:base_url+'do-blog-post-comment',
						type:'post',
						data:{update_comment:1,blog_space_post_id:blog_space_post_id,self_id:comment_id,comment_id:comment_id,comment_type:comment_type,comment_text:comment_text},
						success:function(response){
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								$("#comment_data").html(parsedJson.recieved_html);
								$("#edit_comment_modal").modal('hide');
							}
							else
							{
								$("#edit_comment_modal").modal('hide');
								alert(parsedJson.message);
							}
						}
					});
				}
				else
				{
					$("#edit_comment_text").css({"outline":"1px solid red"});
				}
			}
			function doComment(cid=false)
			{
				$(".form-control").css({"outline":"none"});
				if(cid===false)
				{
					cid="y";
				}
				if($("#comment_text_"+cid).val()!="")
				{
					var blog_space_post_id=$("#do_comment_"+cid).data('bspid');
					var comment_id=$("#do_comment_"+cid).data('cid');
					var comment_type=$("#do_comment_"+cid).data('ct');
					var comment_text=$("#comment_text_"+cid).val();
					$.ajax({
						url:base_url+'do-blog-post-comment',
						type:'post',
						data:{do_comment:1,blog_space_post_id:blog_space_post_id,comment_id:comment_id,comment_type:comment_type,comment_text:comment_text},
						success:function(response){
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								$("#comment_data").html(parsedJson.recieved_html);
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
					$("#comment_text_"+cid).css({"outline":"1px solid red"});
				}
			}
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
					alert('something is not righ.Please try reloading the page.');
				}
			}
			$("#blog_space_post_visibility").change(function(){
				var visibility=$("#blog_space_post_visibility").val();
				var post_id=$("#blog_space_post_visibility").data("postid");
				if(post_id!="")
				{
					$.ajax({
						url:base_url+'change-blog-post-visibility',
						type:'post',
						data:{blog_space_post_id:post_id,visibility:visibility},
						success:function(response){
							var parsedJson=JSON.parse(response);
							if(parsedJson.status=="success")
							{
								//alert(parsedJson.message);
							}
							else
							{
								alert(parsedJson.message);
							}
						}
					});
				}
			});
		</script>
   </body>
</html>