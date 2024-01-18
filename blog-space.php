<!DOCTYPE html>
<html lang="en">
	<head>
		<?php 
			include_once 'head_without_session.php';
			if(isset($_POST['update_interests']))
			{
				$user_id=$_COOKIE['uid'];
				$interested=$_POST['interested'];
				if($interested)
				{
					$query="DELETE FROM users_blog_category WHERE user_id='$user_id'";
					if(mysqli_query($conn,$query))
					{
						foreach($interested as $interest)
						{
							if($interest!="")
							{
								$category=$interest;
								mysqli_query($conn,"INSERT INTO users_blog_category SET user_id='$user_id',category='$category',added=NOW(),status=1");
							}
						}
					}
					else{
						?>
						<script>
							alert('There is some issue.Please contact developer.');
						</script>
						<?php
					}
				}
				else{
					?>
					<script>
						alert('please select required fields');
					</script>
					<?php
				}
			}
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="fb:app_id" content="<?php echo fb_app_id; ?>"/>
		<title>Blog-Space | RopeYou Connects</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>css/feeling.css" />
	</head>
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <!-- Main Content -->
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
						<?php
							if(subscribedCategories($_COOKIE['uid']))
							{
								include_once 'blog_space_functions.php';
								$subscribed_categories=getSubscribedCategories($_COOKIE['uid']);
								$blog_space_posts_query="SELECT blog_space_posts.id,blog_spaces.id as blog_space_id FROM blog_space_posts JOIN blog_spaces ON blog_space_posts.blog_space_id=blog_spaces.id WHERE blog_spaces.category in ('".implode("','",$subscribed_categories)."') AND blog_space_posts.status=1 ORDER BY blog_space_posts.id DESC LIMIT 0,20";
								$blog_space_posts_result=mysqli_query($conn,$blog_space_posts_query);
								$show_count=0;
								if(mysqli_num_rows($blog_space_posts_result)>0)
								{
									while($blog_space_posts_row=mysqli_fetch_array($blog_space_posts_result))
									{
										$bsf=new BlogSpaceFunctions();
										$can_see_this_blog=canSeeThisPost("".$_COOKIE['uid'],$blog_space_posts_row['blog_space_id'],"blog_space");
										if($can_see_this_blog)
										{
											$can_see_this_post=canSeeThisPost("".$_COOKIE['uid'],$blog_space_posts_row['id'],"blog_space_post");
											
											if($can_see_this_post)
											{
												$recieved_html= $bsf->blogSpacePostMini($blog_space_posts_row['id']);
												if($recieved_html=="")
												{
													echo "Some Error";
												}
												else
												{
													$show_count=$show_count+1;
													echo $recieved_html;
												}
											}
										}
									}
								}
								else
								{
									$bsf=new BlogSpaceFunctions();
									echo $bsf->blogPostNothingFound();
								}
							}
							else
							{
								if(isLogin())
								{
									?>
									<div class="box mb-3 shadow-sm border rounded bg-white list-sidebar">
										<div class="box-title p-2 border-bottom">
											<h6 class="m-0">What are you interested in?</h6>
										</div>
										<div class="box-body p-0" style="min-height:200px;">
											<div class="row" style="padding:10px;">
												<div class="col-md-12">
													<p>Please select your interested categories</p>
												</div>
												<div class="col-md-12">
													<form action="" method="post">
														<div class="row">
															<?php
																
																$subscribed_categories=getSubscribedCategories($_COOKIE['uid']);
															
																$blog_cat_query="SELECT * FROM blog_space_categories WHERE status=1";
																$blog_cat_result=mysqli_query($conn,$blog_cat_query);
																if(mysqli_num_rows($blog_cat_result)>0)
																{
																	while($blog_cat_row=mysqli_fetch_array($blog_cat_result))
																	{
																		?>
																		<div class="col-md-6" style="margin-bottom:10px;">
																			<?php //print_r($subscribed_categories); ?>
																			<input type="checkbox" name="interested[]" <?php if(in_array($blog_cat_row['id'],$subscribed_categories)){ echo 'checked'; } ?> value="<?php echo $blog_cat_row['id']; ?>">&nbsp;&nbsp;<?php echo $blog_cat_row['title']; ?>
																		</div> 
																		<?php
																	}
																}
															?>
														</div>
														<div class="row">
															<div class="col-md-12">
																<button type="submit" class="btn btn-success pull-right" name="update_interests">Save</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									<?php
								}
							}
						?>
					</main>
					<aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
						<?php
							if(isLogin())
							{
								?>
								<div class="box mb-3 shadow-sm border rounded bg-white list-sidebar">
									<div class="box-title p-2 border-bottom">
										<h6 class="m-0">Your Interests?</h6>
									</div>
									<div class="box-body p-0" style="min-height:200px;">
										<div class="row" style="padding:10px;">
											<div class="col-md-12">
												<p>Please select your interested categories</p>
											</div>
											<div class="col-md-12">
												<form action="" method="post">
													<div class="row">
														<?php
															
															$subscribed_categories=getSubscribedCategories($_COOKIE['uid']);
														
															$blog_cat_query="SELECT * FROM blog_space_categories WHERE status=1";
															$blog_cat_result=mysqli_query($conn,$blog_cat_query);
															if(mysqli_num_rows($blog_cat_result)>0)
															{
																while($blog_cat_row=mysqli_fetch_array($blog_cat_result))
																{
																	?>
																	<div class="col-md-12" style="margin-bottom:10px;">
																		<?php //print_r($subscribed_categories); ?>
																		<input type="checkbox" name="interested[]" <?php if(in_array($blog_cat_row['id'],$subscribed_categories)){ echo 'checked'; } ?> value="<?php echo $blog_cat_row['id']; ?>">&nbsp;&nbsp;<?php echo $blog_cat_row['title']; ?>
																	</div> 
																	<?php
																}
															}
														?>
													</div>
													<div class="row">
														<div class="col-md-12">
															<button type="submit" class="btn btn-success pull-right" name="update_interests">Save</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
							include_once 'people_you_may_know.php'; ?>
					</aside>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php'; ?>
		<script type="text/javascript" src="<?php echo base_url; ?>js/feeling.js"></script>
		<script>
			localStorage.setItem("uid","<?php echo $_COOKIE['uid']; ?>");
			localStorage.setItem("base_url","<?php echo base_url; ?>");
			function reportPost(post_id="",issue_type="")
			{
				if(issue_type=="")
				{
					$("#reporting_post_id").val(post_id);
					$("#report_post_modal").modal("show");
				}
				else{
					saveReport(post_id,issue_type);
				}
			}
			function saveReport(post_id="",issue_type="")
			{
				if(post_id=="" && issue_type=="")
				{
					post_id=$("#reporting_post_id").val();
					if (!$("input[name='issue_type']:checked").val()) {
					  return false;
					}
					else
					{
						var issue_type=$("input[name='issue_type']:checked").val();
						$.ajax({
							url:base_url+'report-a-post',
							type:'post',
							data:{issue_type:issue_type,post_id:post_id},
							success:function(response){
								var parsedJson=JSON.parse(response)
								if(parsedJson.status=="success")
								{
									$("#user_posts_container_number_"+post_id).hide();
								}
							}
						});
					}
				}
				else
				{
					$.ajax({
						url:base_url+'report-a-post',
						type:'post',
						data:{issue_type:issue_type,post_id:post_id},
						success:function(response){
							var parsedJson=JSON.parse(response)
							if(parsedJson.status=="success")
							{
								$("#user_posts_container_number_"+post_id).hide();
							}
						}
					});
				}
			}
			function editPost(post_id="")
			{
				if(post_id!="")
				{
					$.ajax({
						url:base_url+'get-edit-data-for-post',
						type:'post',
						data:{post_id:post_id},
						success:function(response)
						{
							var ParsedResponse=JSON.parse(response);
							if(ParsedResponse.status=="success")
							{
								
							}
							else
							{
								alert('Something is not right.we are working on that please try back later.');
							}
						}
					});
				}
				else
				{
					alert('There is an issue please try back later.');
				}
			}
			function deletePost(post_id="")
			{
				if(post_id!="")
				{
					if(confirm('Do you really want to delete this post?'))
					{
						$.ajax({
							url:base_url+'delete-users-post',
							type:'post',
							data:{id:post_id},
							success:function(response)
							{
								var parsedResponse=JSON.parse(response);
								if(parsedResponse.status=="success")
								{
									if($("#user_posts_container_number_"+post_id).length>0)
									{
										$("#user_posts_container_number_"+post_id).remove();
									}
								}
								else
								{
									alert(parsedResponse.mesg);
								}
							}
						});
						
					}
					else
					{
						return false;
					}
				}
				else
				{
					alert('There is an issue removing this post.');
				}
			}
			function conn_selec_text_arr(mode_seection_dropdown){
				switch(mode_seection_dropdown)
				{
					case "1,0,0,0,0": return "<i class='fa fa-globe'></i>&nbsp;&nbsp;Any One";break;
					case "0,1,0,0,0":return "<i class='fa fa-user'></i>&nbsp;&nbsp;Only Me";break;
					case "0,0,1,0,0":return "<i class='fa fa-users'></i>&nbsp;&nbsp;Only Connections";break;
					case "0,0,1,1,0":return "<i class='fa fa-users'></i>&nbsp;&nbsp;Connections of Connections";break;
					case "0,0,1,0,1":return "<i class='fa fa-users'></i>&nbsp;+&nbsp;Allowed Specific";break;
					case "0,0,1,0,2":return "<i class='fa fa-users'></i>&nbsp;-&nbsp;Blocked Specific";break;
				}
			}
			function connections_selection_div(){
				var mode_seection_dropdown=$("#mode_seection_dropdown").val();
				$("#mode").data("val",mode_seection_dropdown);
				$("#mode").html(conn_selec_text_arr(mode_seection_dropdown));
				if(mode_seection_dropdown=="0,0,1,0,2" || mode_seection_dropdown=="0,0,1,0,1")
				{
					$("#connections_selection_div").show();
				}
				else
				{
					$("#connections_selection_div").hide();
				}
			}
			var Upload = function (file) {
				this.file = file;
			};

			Upload.prototype.getType = function() {
				return this.file.type;
			};
			Upload.prototype.getSize = function() {
				return this.file.size;
			};
			Upload.prototype.getName = function() {
				return this.file.name;
			};
			Upload.prototype.doUpload = function (img_id) {
				$("#save_brodcast_post").attr("disabled",true);
				var that = this;
				var formData = new FormData();

				// add assoc key values, this will be posts values
				formData.append("file", this.file, this.getName());
				formData.append("upload_file", true);

				$.ajax({
					type: "POST",
					url: "upload-file-on-server-temp",
					xhr: function () {
						var myXhr = $.ajaxSettings.xhr();
						if (myXhr.upload) {
							myXhr.upload.addEventListener('progress', that.progressHandling, false);
						}
						return myXhr;
					},
					success: function (data) {
						console.log(data);
						var this_parsed_json=JSON.parse(data);
						if(this_parsed_json.id!="")
						{
							$("#"+img_id).attr("imgid",this_parsed_json.id);
						}
						else
						{
							$("#"+img_id).remove();
						}
						$("#save_brodcast_post").attr("disabled",false);
					},
					error: function (error) {
						// handle error
					},
					async: true,
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					timeout: 6000000
				});
			};

			Upload.prototype.progressHandling = function (event) {
				var percent = 0;
				var position = event.loaded || event.position;
				var total = event.total;
				var progress_bar_id = "#progress-wrp";
				if (event.lengthComputable) {
					percent = Math.ceil(position / total * 100);
				}
				// update progressbars classes so it fits your code
				$(progress_bar_id + " .progress-bar").css("width", +percent + "%");
				$(progress_bar_id + " .status").text(percent + "%");
			};
			
			function uploadMedia(id_slug="")
			{
				$(".post_modal_section").hide();
				$("#create_media_post_modal_section").show();
				$("#do_post_modal").modal('show');
			}
			var offered_posting_as_blog=null;
			function offerPostingAsBlog(id_slug="")
			{
				if(offered_posting_as_blog==null)
				{
					offered_posting_as_blog=1;
					$(".post_modal_section").hide();
					$("#offered_posting_as_blog").show();
				}
			}
			function unOfferPostingAsBlog(id_slug="")
			{
				if(offered_posting_as_blog==1)
				{
					offered_posting_as_blog=null;
					$("#offered_posting_as_blog").hide();
					$("#create_post_modal_section").show();
				}
			}
			function keyupFunction(id_slug="")
			{
				var status_text=$("#status_text").val();
				status_text_trim=status_text.trim();
				var str_arr=status_text_trim.split(" ");
				$("#word_count").text(str_arr.length);
			}
			function saveWallPost(id_slug="")
			{
				var files_uploaded=0;
				var files="";
				if($(".uploaded_file_class").length>0)
				{
					$(".uploaded_file_class").each(function(){
						var img_file_id=$(this).attr("imgid");
						console.log(img_file_id);
						if(files_uploaded>0)
						{
							files=files+",";
						}
						files=files+''+img_file_id;
						files_uploaded=files_uploaded+1;
					});
				}
				
				var status_text=$("#status_text").val();
				$("#status_text").css({"outline":"none"});
				if(files_uploaded>0 || status_text!="")
				{
					var mode=$("#mode").data("val");
					var friends_include_exclude="";
					$(".mode_friends:checkbox:checked").each(function () {
						if(friends_include_exclude!="")
						{
							friends_include_exclude+",";
						}
						friends_include_exclude+=$(this).val();
						
					});
					var tagged="";
					$(".tagged_friends:checkbox:checked").each(function () {
						if(tagged!="")
						{
							tagged+",";
						}
						tagged+=$(this).val();
						
					});
					console.log({files_uploaded:files,tagged:tagged,friends_include_exclude:friends_include_exclude,text_content:status_text,mode:mode});
					
					$.ajax({
						url:base_url+'save-text-post',
						type:'post',
						data:{files_uploaded:files,tagged:tagged,friends_include_exclude:friends_include_exclude,text_content:status_text,mode:mode},
						success:function(response){
							console.log(response);
							var parsed_response=JSON.parse(response);
							if(parsed_response.status=="success")
							{
								swal({
								  title: "Great!",
								  text: "Successfully posted to your timeline.",
								  icon: "success",
								  buttons: {
									cancel: false,
									confirm: "Close",
								  },
								  dangerMode: false,
								  closeOnConfirm: false,
								  closeOnCancel: false
								}).then((value) => {
									location.href=base_url+"broadcasts";
								});
								$("#do_post_modal").modal('hide');
							}
							else if(parsed_response.status=="duplicate")
							{
								swal({
								  title: "OH!",
								  text: "Possibally duplicate post!",
								  icon: "warning",
								  buttons: {
									cancel: false,
									confirm: "Close",
								  },
								  dangerMode: false,
								});
								$("#do_post_modal").modal('hide');
							}
							else
							{
								swal({
								  title: "It's not You!",
								  text: "We are working on this try it after a moment.",
								  icon: "error",
								  buttons: {
									cancel: false,
									confirm: "Close",
								  },
								  dangerMode: false,
								});
								$("#do_post_modal").modal('hide');
							}
						}
					});
				}
				else
				{
					$("#status_text").css({"outline":"1 px solid red"});
				}
			}
			function statusTextKeyup(id_slug="")
			{
				if(id_slug!="")
				{
					var id=id_slug.replace("_","");
				}
				var status_text=$("#status_text"+id_slug).val();
				$("#status_text"+id_slug).css({"outline":"none"});
				status_text_trim=status_text.trim();
				var str_arr=status_text_trim.split(" ");
				$("#word_count"+id_slug).text(str_arr.length);
				if(str_arr.length>300)
				{
					offerPostingAsBlog(id_slug);
				}
				else
				{
					unOfferPostingAsBlog(id_slug);
					saveWallPost(id_slug);
				}
			}
			function resetPost(id_slug="")
			{
				if(id_slug=="")
				{
					$(".post_modal_section"+id_slug).hide();
					$(".tagged_friends"+id_slug).prop("checked",false);
					$("#create_post_modal_section"+id_slug).show();
					$(".image_selector"+id_slug).val(null);
					$(".video_selector"+id_slug).val(null);
					$("#status_text"+id_slug).val();
					$("#post_preview_section"+id_slug).html("");
				}
			}
			var img_id_counter=0;
			var file_id="preview_image_";
			var imagesPreview = function(input, placeToInsertImagePreview,id_slug="") {
				if (input.files) {
					var filesAmount = input.files.length;
					for (i = 0; i < filesAmount; i++) {
						img_id_counter=img_id_counter+1;
						var reader = new FileReader();
						reader.onload = function(event) {
							var image_created=$.parseHTML('<img>');
							$(image_created).attr('src', event.target.result);
							$(image_created).attr('id', file_id+img_id_counter+id_slug);
							$(image_created).attr('class', "uploaded_file_class"+id_slug);
							$(image_created).css({"width": "100%","margin-bottom":"15px","border":"1 px solid gray;","padding":"5px"});
							$(image_created).appendTo(placeToInsertImagePreview);
						}
						/*------------------------------------*/
							var file = input.files[i];
							var upload = new Upload(file);
							upload.doUpload(file_id+img_id_counter+id_slug);
						/*------------------------------------*/
						reader.readAsDataURL(input.files[i]);
					}
				}
			};
			var videoPreview = function(input, placeToInsertImagePreview,id_slug="") {
				if (input.files) {
					var filesAmount = input.files.length;
					for (i = 0; i < filesAmount; i++) {
						img_id_counter=img_id_counter+1;
						var reader = new FileReader();
						reader.onload = function(event) {
							var video_created=$.parseHTML('<video>');
							$(video_created).attr('src', event.target.result);
							$(video_created).attr('id', file_id+img_id_counter+id_slug);
							$(video_created).attr('class', "uploaded_file_class"+id_slug);
							$(video_created).attr('controls', true);
							$(video_created).attr('download', false);
							$(video_created).css({"width": "100%","margin-bottom":"15px","border":"1 px solid gray;","padding":"5px"});
							$(video_created).appendTo(placeToInsertImagePreview);
						}
						/*------------------------------------*/
							var file = input.files[i];
							var upload = new Upload(file);
							upload.doUpload(file_id+img_id_counter+id_slug);
						/*------------------------------------*/
						reader.readAsDataURL(input.files[i]);
					}
				}
			};

			$('#image_selector').on('change',function(){
				imagesPreview(this, $("#post_preview_section"),$(this).data('idslug'));
			});
			$('#video_selector').on('change',function(){
				videoPreview(this, $("#post_preview_section"),$(this).data('idslug'));
			});
			$('#media_image_selector').on('change',function(){
				var id_slug=$(this).data('idslug');
				$(".post_modal_section"+id_slug).hide();
				$("#create_post_modal_section"+id_slug).show();
				imagesPreview(this, $("#post_preview_section"),id_slug);
			});
			$('#media_video_selector').on('change',function(){
				var id_slug=$(this).data('idslug');
				$(".post_modal_section"+id_slug).hide();
				$("#create_post_modal_section"+id_slug).show();
				videoPreview(this, $("#post_preview_section"),id_slug);
			});
			(function($) {
				  "use strict"; // Start of use strict

				// Tooltip
				$('[data-toggle="tooltip"]').tooltip();

				// Osahan Slider
				$('.quote_slider').slick({
				  centerMode: true,
				  centerPadding: '30px',
				  slidesToShow: 3,
				  responsive: [
					{
					  breakpoint: 768,
					  settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
					  }
					},
					{
					  breakpoint: 480,
					  settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
					  }
					}
				  ]
				});

			})(jQuery);
			function addComment(post_id,comment_text="")
			{
				if(comment_text=="")
				{
					comment_text=$("#comment_text_"+post_id).val();
				}
				comment_text=comment_text.trim();
				if(comment_text=="")
				{
					swal({
					  title: "Attention!",
					  text: "Blank comment can not be posted.",
					  icon: "error",
					  buttons: {
						cancel: false,
						confirm: "Close",
					  },
					  dangerMode: false,
					});
				}
				else
				{
					var username=$("#comment_text_"+post_id).attr('data-name');
					var userimage=$("#comment_text_"+post_id).attr('data-img');
					var user_id=$("#comment_text_"+post_id).attr('data-uid');
					$.ajax({
						type:'POST',
						url: base_url+"post-comment",
						data:{post_id:post_id,user_id:user_id,comment_text:comment_text},
						success:function(data){
							var data_obj=JSON.parse(data);
							if(data_obj.status=="success")
							{
								var html='<div class="p-3 d-flex align-items-top border-bottom osahan-post-comment" style="width:100%;">'+
								'<div class="dropdown-list-image mr-3">'+
								   '<img class="rounded-circle img-nf" style="border:1px solid #eaebec; !important;" src="'+userimage+'" alt="'+username+'">'+
								   '<div class="status-indicator bg-success"></div>'+
								'</div>'+
							   ' <div class="font-weight-bold" style="width:100% !important">'+
								  ' <div class="text-truncate"> '+username+' <span class="float-right small">just now</span></div>'+
								   '<div class="small text-gray-500">'+data_obj.comment_text+'</div>'+
								'</div>'+
							 '</div>';
							 $("#post_comments_data_"+post_id).append(html);
							 $("#comment_text_"+post_id).val("");
							}
							else{
								swal({
								  title: "Attention!",
								  text: data_obj.message,
								  icon: "error",
								  buttons: {
									cancel: false,
									confirm: "Close",
								  },
								  dangerMode: false,
								});
							}
						}
					});
				}
			}
			$(".post_matrix").click(function(){
				var post_matrix=$("#post_matrix").val();
				$("#mode").val(post_matrix);
				if(post_matrix=="0,0,1,0,1" || post_matrix=="0,0,1,0,2")
				{
					$("#mode_options_html").css("visibility","visible");
					$("#mode_options").val("");
					$("#selected_mode_options").val($("#post_matrix_users").val());
				}
				else
				{
					$("#mode_options").val("");
					$("#selected_mode_options").val("");
					$("#mode_options_html").css("visibility","hidden");
				}
				$("#post_matrix_settings_modal").modal('show');
			});
			$("#mode").change(function(){
				var mode=$("#mode").val();
				if(mode=="0,0,1,0,1" || mode=="0,0,1,0,2")
				{
					$("#mode_options_html").css("visibility","visible");
					$("#mode_options").val("");
					$("#selected_mode_options").val($("#post_matrix_users").val());
				}
				else
				{
					$("#mode_options").val("");
					$("#selected_mode_options").val("");
					$("#mode_options_html").css("visibility","hidden");
				}
			});
			/* Detect a Url in the status text */
			
			var timeout=null;
			/*$('#status_text').bind("keyup",function() {
				$("#ru-loader").hide();
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					var html=$('#status_text').val();
					var url_caught=0;
					console.log(html);
					var h_words=html.split(" ");
					for(i=0;i<h_words.length;i++)
					{
						if(isUrl(h_words[i])){
							$("#ru-loader").show();
							localStorage.setItem('text_status_url',h_words[i]);
							var url=h_words[i];
							url_caught=1;
							$.ajax({
								url:base_url+"preview",
								type:"post",
								data:{url:url},
								success:function(res)
								{
									var preview_html="";
									var resp=JSON.parse(res);
									if(resp.data=="")
									{
										$("#status_text_preview").html(preview_html);
									}
									else
									{
										var response=resp.data;
										var title=response.title;
										var img_url=response.img_url;
										var desc=response.desc;
										var host_url=response.host_url;
										if(title=="")
										{
											title=getWebTitle(url);
										}
										if(img_url=="")
										{
											img_url=base_url+"alphas/"+getWebImage(url)+".png";
										}
										if(host_url=="")
										{
											host_url=url;
										}
										if(desc=="")
										{
											desc="visit "+url;
										}
										if(title=="" && img_url=="" && desc=="")
										{ 
											preview_html="";
										}
										else
										{
											preview_html='<div class="p-3 osahan-post-body">'+
											'<p class="mb-1">'+title+'</p>'+								
													'<div class="d-flex align-items-center" style="width:100% !important;">'+
														'<div class="box shadow-sm mb-3 rounded bg-white ads-box text-center overflow-hidden" style="width:100% !important;">'+
															 '<a href="'+host_url+'" target="_blank" style="width:100% !important;padding:5px;">'+
																'<img src="'+img_url+'" class="img-fluid img-responsive p-3" title="'+host_url+'" alt="'+img_url+'" style="width:100% !important;">'+
															'</a>'+
															 '<div class="border-top">'+
																'<p class="mb-0 text-muted p-3" style="text-align:justify !important;"><a href="'+host_url+'" target="_blank"></a>'+desc+'</p>'+
															 '</div>'+
														'</div>'+
													'</div>'+
													'<input type="hidden" name="url_preview" id="url_preview" value="'+title+'*RUBREAK*'+img_url+'*RUBREAK*'+desc+'*RUBREAK*'+url+'">'+
												'</div>';
										}
										$("#status_text_preview").html(preview_html);
									}
									$("#ru-loader").hide();
								}
							});
						}
					}
					if(url_caught==0)
					{
						$("#status_text_preview").html("");
					}
				}, 1000);
			});*/
			function isUrl(str) {
				switch (str)
				{
					case "http": return false;break;
					case "https": return false;break;
					case "http:": return false;break;
					case "https:": return false;break;
					case "http:/": return false;break;
					case "https:/": return false;break;
					case "http://": return false;break;
					case "https://": return false;break;
				}
				var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
					'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
					'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
					'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
					'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
					'(\\#[-a-z\\d_]*)?$','i'); // fragment locator
				  return !!pattern.test(str);
			}
			function getWebImage(str) {
				switch (str)
				{
					case "http": return false;break;
					case "https": return false;break;
					case "http:": return false;break;
					case "https:": return false;break;
					case "http:/": return false;break;
					case "https:/": return false;break;
					case "http://": return false;break;
					case "https://": return false;break;
					case "https://w": return false;break;
					case "https://ww": return false;break;
					case "https://www": return false;break;
					case "https://www.": return false;break;
					case "http://w": return false;break;
					case "http://ww": return false;break;
					case "http://www": return false;break;
					case "http://www.": return false;break;
				}
				str = str.replace("https://www.", "");
				str = str.replace("https://", "");
				str = str.replace("http://", "");
				var arr=str.split(".");
				if(arr[0]!="")
				{
					var latter=arr[0].substring(0, 1);
					latter=latter.toLowerCase();
					return latter;
				}
				else if(arr[1]!="")
				{
					var latter=arr[1].substring(0, 1);
					latter=latter.toLowerCase();
					return latter;
				}
				else{
					return "w";
				}
			}
			function getWebTitle(str) {
				switch (str)
				{
					case "http": return false;break;
					case "https": return false;break;
					case "http:": return false;break;
					case "https:": return false;break;
					case "http:/": return false;break;
					case "https:/": return false;break;
					case "http://": return false;break;
					case "https://": return false;break;
					case "https://w": return false;break;
					case "https://ww": return false;break;
					case "https://www": return false;break;
					case "https://www.": return false;break;
					case "http://w": return false;break;
					case "http://ww": return false;break;
					case "http://www": return false;break;
					case "http://www.": return false;break;
				}
				str = str.replace("https://www.", "");
				str = str.replace("https://", "");
				str = str.replace("http://", "");
				var arr=str.split(".");
				if(arr[0]!="")
				{
					return arr[0];
				}
				else if(arr[1]!="")
				{
					return arr[1];
				}
				else{
					return str;
				}
			}
			function submitPost()
			{
				var status_text=$("#status_text").val();
				status_text=status_text.trim();
				if(status_text=="")
				{
					return false;
				}
				var post_matrix=$("#post_matrix").val();
				var post_matrix_users=$("#post_matrix_users").val();
				var url_preview="";
				if($("#url_preview").length>0)
				{
					url_preview=$("#url_preview").val();
				}
				$.ajax({
					url: base_url+"save-text-post",
					type:"post",
					data:{post_text:status_text,url_preview:url_preview,post_matrix:post_matrix,post_matrix_users:post_matrix_users},
					success:function(res){
						if(res=="success")
						{
							swal({
							  title: "Great!",
							  text: "Successfully posted to your timeline.",
							  icon: "success",
							  buttons: {
								cancel: false,
								confirm: "Close",
							  },
							  dangerMode: false,
							  closeOnConfirm: false,
							  closeOnCancel: false
							}).then((value) => {
								location.href=base_url+"broadcasts";
							});
						}
						else if(res=="duplicate")
						{
							swal({
							  title: "OH!",
							  text: "Possibally duplicate post!",
							  icon: "warning",
							  buttons: {
								cancel: false,
								confirm: "Close",
							  },
							  dangerMode: false,
							});
						}
						else 
						{
							swal({
							  title: "It's not You!",
							  text: "We are working on this try it after a moment.",
							  icon: "error",
							  buttons: {
								cancel: false,
								confirm: "Close",
							  },
							  dangerMode: false,
							});
						}
					}
				});
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
		</script>
   </body>
</html>
<script async src="https://guteurls.de/guteurls.js" selector=".url_meta"> </script> 