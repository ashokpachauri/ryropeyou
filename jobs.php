<!DOCTYPE html>
<html lang="en">
   <head>
		<?php include_once 'head.php'; ?>
		<title>Jobs | RopeYou Connects</title>
	</head>
	<body>
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container">
				<div class="row">
				   <!-- Main Content -->
					<?php
						$filter="";
						if(isset($_REQUEST['filter']) && $_REQUEST['filter']!="")
						{
							$filter=$_REQUEST['filter'];
						}
						$filter_val=str_replace("-","_",strtoupper($filter));
					?>
				   <aside class="hide_on_dashboard col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-12">
						<div class="box p-3 shadow-sm mb-3 rounded bg-gray overflow-hidden ">
						    <div class="uk-width-expand">
								<div class="sidebar-filter" uk-sticky="offset:30 ; media : @s: bottom: true">
									<div class="sidebar-filter-contents">
										<h3> Filter Jobs</h3>
										<ul class="sidebar-filter-list uk-accordion" uk-accordion="multiple: true">
											<li class="uk-open">
												<a class="uk-accordion-title" href="#"> Experience Levels </a>
												<div class="uk-accordion-content" aria-hidden="false">
													<div class="uk-form-controls">
														<label>
															<input class="uk-radio job_filter_trigger" value="beginner" type="radio" name="radio1" data-tag="beginner" <?php if($filter=="beginner"){ echo 'checked'; } ?>>
															<span class="test"> Beginner <span> (<?php echo getBegginerJobCount(); ?>) </span> </span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="intermediate" type="radio" name="radio1" data-tag="intermediate" <?php if($filter=="intermediate"){ echo 'checked'; } ?>>
															<span class="test"> Intermidate<span> (<?php echo getIntermediateJobCount(); ?>) </span></span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="expert" type="radio" name="radio1" data-tag="expert" <?php if($filter=="expert"){ echo 'checked'; } ?>>
															<span class="test"> Expert <span> (<?php echo getExpertJobCount(); ?>) </span></span>
														</label>
													</div>
												</div>
											</li>

											<li class="uk-open">
												<a class="uk-accordion-title" href="#"> Job Type </a>
												<div class="uk-accordion-content" aria-hidden="false">
													<div class="uk-form-controls">
														<label>
															<input class="uk-radio job_filter_trigger" value="FULL_TIME" type="radio" name="radio2" data-tag="full-time" <?php if($filter=="full-time"){ echo 'checked'; } ?>>
															<span class="test"> Full Time (<?php echo getFullTimeJobCount(); ?>)</span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="CONTRACT" type="radio" name="radio2" data-tag="freelance" <?php if($filter=="freelance"){ echo 'checked'; } ?>>
															<span class="test"> Freelance (<?php echo getFreelanceJobCount(); ?>) </span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="INTERNSHIP" type="radio" name="radio2" data-tag="internship" <?php if($filter=="internship"){ echo 'checked'; } ?>>
															<span class="test"> Internship (<?php echo getInternshipJobCount(); ?>)</span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="PART_TIME" type="radio" name="radio2" data-tag="part-time" <?php if($filter=="part-time"){ echo 'checked'; } ?>>
															<span class="test"> Part Time (<?php echo getPartTimeJobCount(); ?>)</span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="TEMPORARY" type="radio" name="radio2" data-tag="temporary" <?php if($filter=="temporary"){ echo 'checked'; } ?>>
															<span class="test"> Temporary (<?php echo getTemporaryJobCount(); ?>)</span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" value="VOLUNTEER" type="radio" name="radio2" data-tag="volunteer" <?php if($filter=="volunteer"){ echo 'checked'; } ?>>
															<span class="test"> Volunteer (<?php echo getVolunteerJobCount(); ?>)</span>
														</label>
													</div>
												</div>
											</li>
											<li class="uk-open">
												<a class="uk-accordion-title" href="#"> Notice Period </a>
												<div class="uk-accordion-content" aria-hidden="false">
													<div class="uk-form-controls">
														<label>
															<input class="uk-radio job_filter_trigger" type="radio" name="radio3" data-tag="immediate" <?php if($filter=="immediate"){ echo 'checked'; } ?>>
															<span class="test"> Immediate <span> (<?php echo getImmediateJobCount(); ?>) </span> </span>
														</label>
														<label>
															<input class="uk-radio job_filter_trigger" type="radio" name="radio3" data-tag="later" <?php if($filter=="later"){ echo 'checked'; } ?>>
															<span class="test"> Later<span> (<?php echo getLaterJobCount(); ?>) </span></span>
														</label>
													</div>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</aside>
				   
					<main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
						<div class="box shadow-sm border rounded bg-white mb-3 osahan-share-post">
							<form class="job-search p-3 border-bottom" action="" method="post">
								<div class="input-group">
								   <input type="text" class="form-control" placeholder="Search jobs" id="search_field" aria-label="Search" aria-describedby="basic-addon2">
								   <div class="input-group-append">
										<button class="btn btn-outline-secondary" onclick="change_dom('yes');" type="button">
											<i class="feather-search"></i>
										</button>
								   </div>
								</div>
							</form>
							<div class="row">
								<div class="col-12 show_on_dashboard">
									<h6 class="standard_h6"> Filter Jobs</h3>
									<div class="row">
										<div class="col-4">
											<select class="job_filter_trigger_select form-control mobile_select" id="">
												<option value="" disabled>Experience Levels</option>
												<option  data-tag="beginner" <?php if($filter=="beginner"){ echo 'selected'; } ?>>beginner</option>
												<option  data-tag="intermediate" <?php if($filter=="intermediate"){ echo 'selected'; } ?>>intermediate</option>
												<option  data-tag="expert" <?php if($filter=="expert"){ echo 'selected'; } ?>>expert</option>
											</select>
										</div>
										<div class="col-4">
											<select class="job_filter_trigger_select form-control mobile_select" id="">
												<option value="" disabled>Job Type</option>
												<option  value="FULL_TIME" data-tag="full-time" <?php if($filter=="full-time"){ echo 'selected'; } ?>>Full Time</option>
												<option  value="CONTRACT" data-tag="contract" <?php if($filter=="contract"){ echo 'selected'; } ?>>Contract</option>
												<option  value="INTERNSHIP" data-tag="internship" <?php if($filter=="internship"){ echo 'selected'; } ?>>Internship</option>
												<option  value="PART_TIME" data-tag="part-time" <?php if($filter=="part-time"){ echo 'selected'; } ?>>Part time</option>
												<option  value="TEMPORARY" data-tag="temporary" <?php if($filter=="temporary"){ echo 'selected'; } ?>>Temporary</option>
												<option  value="VOLUNTEER" data-tag="volunteer" <?php if($filter=="volunteer"){ echo 'selected'; } ?>>Volunteer</option>
											</select>
										</div>
										<div class="col-4">
											<select class="job_filter_trigger_select form-control mobile_select" id="">
												<option value="" disabled>Notice Period</option>
												<option value="immediate" data-tag="immediate" <?php if($filter=="immediate"){ echo 'selected'; } ?>>Immediate</option>
												<option  value="later" data-tag="later" <?php if($filter=="later"){ echo 'selected'; } ?>>Later</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
								   <div class="p-3 border-top">
										<div class="row">
											<?php 
												$latest_opening_query="SELECT * FROM jobs WHERE status=1 AND in_appropriate=0 ".$extra_query." ORDER BY id DESC LIMIT 20";
												$latest_opening_result=mysqli_query($conn,$latest_opening_query);
												$latest_opening_num_rows=mysqli_num_rows($latest_opening_result);
											
												if($latest_opening_num_rows>0)
												{
													while($latest_opening_row=mysqli_fetch_array($latest_opening_result))
													{
														$status_row=mysqli_fetch_array(mysqli_query($conn,"SELECT title FROM job_status WHERE id='".$latest_opening_row['status']."'"));
														$og_title=base_url."job/".trim(strtolower($latest_opening_row['job_title']))." ".trim(strtolower($latest_opening_row['job_company']));
														$og_title=str_replace(" ","-",$og_title);
														$og_url=$og_title."-".$latest_opening_row['id'].".html";
											?>
														<div class="col-lg-6">
															<div class="border job-item mb-3 jobs_main_panel" style="min-height:200px">
																<div class="d-flex align-items-center p-3 job-item-header">
																	<div class="overflow-hidden mr-2">
																		<h6 class="font-weight-bold text-dark mb-0 text-truncate"><a href="<?php echo $og_url; ?>"><?php echo $latest_opening_row['job_title']; ?></a></h6>
																		<div class="text-truncate text-primary"><?php echo $latest_opening_row['job_company']; ?></div>
																		<div class="small text-gray-500"><i class="feather-map-pin"></i><?php echo $latest_opening_row['job_location']; ?></div>
																	</div>
																	<a href="<?php echo $og_url; ?>" class="ml-auto">
																		<img class="img-fluid ml-auto jobs_image_mobile" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($latest_opening_row['job_company']),0,1).".png"; ?>" alt="">
																	</a>
																</div>
																<?php
																	getCommonPersonsOnJob($latest_opening_row['id'],$_COOKIE['uid']);
																?>
																<div class="p-3 job-item-footer">
																	<small class="text-gray-500"><i class="feather-clock"></i>&nbsp;&nbsp;<?php echo date("M d",strtotime($latest_opening_row['added'])); ?></small>
																</div>
														   </div>
														</div>
											
											<?php
													}
												}
											?>
										</div>
								   </div>
								</div>
							</div>
						</div>
				   </main>
					<aside class="hide_on_dashboard col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
						<div class="pb-3">
							<h6 class="font-weight-bold text-dark mb-1">Sponsered Jobs</h6>
						</div>
						<?php 
							$latest_opening_query="SELECT * FROM jobs ORDER BY rand() ASC LIMIT 5";
							$latest_opening_result=mysqli_query($conn,$latest_opening_query);
							$latest_opening_num_rows=mysqli_num_rows($latest_opening_result);
							while($job_row=mysqli_fetch_array($latest_opening_result))
							{
								$status_row=mysqli_fetch_array(mysqli_query($conn,"SELECT title FROM job_status WHERE id='".$latest_opening_row['status']."'"));
								$og_title=base_url."job/".trim(strtolower($latest_opening_row['job_title']))." ".trim(strtolower($latest_opening_row['job_company']));
								$og_title=str_replace(" ","-",$og_title);
								$og_url=$og_title."-".$latest_opening_row['id'].".html";
								?>
									<div class="shadow-sm rounded bg-white job-item mb-3">
										<div class="d-flex align-items-center p-3 job-item-header">
											<div class="overflow-hidden mr-2">
											  <h6 class="font-weight-bold text-dark mb-0 text-truncate"><a href="<?php echo $og_url; ?>"><?php echo $job_row['job_title']; ?></a></h6>
											  <div class="text-truncate text-primary"><?php echo $job_row['job_company']; ?></div>
											  <div class="small text-gray-500"><i class="feather-map-pin"></i><?php echo $job_row['job_location']; ?></div>
											</div>
											<a href="<?php echo $og_url; ?>" class="ml-auto">
												<img class="img-fluid ml-auto" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($job_row['job_company']),0,1).".png"; ?>" alt="<?php echo $job_row['job_title']; ?>">
											</a>
										</div>
										<?php getCommonPersonsOnJob($job_row['id'],$_COOKIE['uid']); ?>
										<div class="p-3 job-item-footer">
										   <small class="text-gray-500"><i class="feather-clock"></i>&nbsp;&nbsp;<?php echo date("M d",strtotime($job_row['added'])); ?></small>
										</div>
									</div>
								<?php
							}
						?>
						<?php include_once 'people_you_may_know.php'; ?>
					</aside>
				</div>
			</div>
		</div>
		<?php include_once 'scripts.php';  ?>
		<script src="<?php echo base_url; ?>jquery.sticky-kit.js"></script>
		<script>
			$(".is_stuck").stick_in_parent();
			var base_url="<?php echo base_url; ?>";
			function change_dom(search_field='yes')
			{
				if(search_field=="yes")
				{
					var value=$("#search_field").val();
					search_field=value.split(" ").join("+");
				}
				else
				{
					search_field='';
				}
				$("#home").load(base_url+'get-job-search.php?search='+search_field);
			}
			function loadIndustries(industry)
			{
				$("#home").load(base_url+'get-job-search.php?industry='+industry);
			}
			$(".job_filter_trigger").on("click",function(){
				//console.log($(this).data('tag'));
				window.location.href=base_url+'jobs/'+$(this).data('tag');
			});
			$(".job_filter_trigger_select").change(function(){				
				window.location.href=base_url+'jobs/'+$(this).find(':selected').data('tag');
			});
		</script>
   </body>
</html>
