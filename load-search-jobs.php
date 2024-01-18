<div class="p-3 user_section_jobs">
	<div class="row">
	<?php
		include_once 'connection.php';
		$search=str_replace("-"," ",$_REQUEST['search']);
		$search_arr=explode(" ",$search);
		$user_search_arr=array();
		
		/*$designation_query="SELECT * FROM users_work_experience WHERE title IN('".implode("','",$search_arr)."') OR company IN('".implode("','",$search_arr)."') OR description LIKE '%".$search."%' OR title LIKE '%".$search."%' OR company LIKE '%".$search."%'";
		$designation_result=mysqli_query($conn,$designation_query);
		if(mysqli_num_rows($designation_result)>0)
		{
			while($designation_row=mysqli_fetch_array($designation_result))
			{
				$user_search_arr[]=$designation_row['user_id'];
			}
		}*/
		$people_search_query="SELECT * FROM jobs WHERE (job_title LIKE '%".$search."%' OR job_description LIKE '%".$search."%' OR skills LIKE '%".$search."%' OR job_company LIKE '%".$search."%' OR job_location LIKE '%".$search."%' OR job_title IN('".implode("','",$search_arr)."') OR id IN('".implode("','",$user_search_arr)."')) AND user_id!='".$_COOKIE['uid']."' AND status=1 AND in_appropriate=0";
		if($search=="")
		{
			$people_search_query="SELECT * FROM jobs WHERE user_id!='".$_COOKIE['uid']."'  AND in_appropriate=0 AND status=1";
		}
		$people_search_result=mysqli_query($conn,$people_search_query);
		if(mysqli_num_rows($people_search_result)>0)
		{
			while($latest_opening_row=mysqli_fetch_array($people_search_result))
			{
				$status_row=mysqli_fetch_array(mysqli_query($conn,"SELECT title FROM job_status WHERE id='".$latest_opening_row['status']."'"));
				$og_title=base_url."job/".trim(strtolower($latest_opening_row['job_title']))." ".trim(strtolower($latest_opening_row['job_company']));
				$og_title=str_replace(" ","-",$og_title);
				$og_url=$og_title."-".$latest_opening_row['id'].".html";
	?>
				 <div class="col col-6 col-lg-4" id="user_section_jobs_<?php echo $latest_opening_row['id']; ?>">
					<a href="<?php echo $og_url; ?>">
					   <div class="border job-item mb-3">
						  <div class="d-flex align-items-center p-3 job-item-header">
							 <div class="overflow-hidden mr-2">
								<h6 class="font-weight-bold text-dark mb-0 text-truncate"><?php echo $latest_opening_row['job_title']; ?></h6>
								<div class="text-truncate text-primary"><?php echo $latest_opening_row['job_company']; ?></div>
								<div class="small text-gray-500"><i class="feather-map-pin"></i><?php echo $latest_opening_row['job_location']; ?></div>
							 </div>
							 <img class="img-fluid ml-auto" src="<?php echo base_url; ?>alphas/<?php echo substr(strtolower($latest_opening_row['job_company']),0,1).".png"; ?>" alt="">
						  </div>
						  <?php
								getCommonPersonsOnJob($latest_opening_row['id'],$_COOKIE['uid']);
						  ?>
						  <div class="p-3 job-item-footer">
							 <small class="text-gray-500"><i class="feather-clock"></i><?php echo date("d M Y",strtotime($latest_opening_row['added'])); ?></small>
						  </div>
					   </div>
					</a>
				</div>
										
		
		<?php
			}
		}
		else
		{
			?>
			<div class="col-md-12">
				<h5 style="text-align:center;">unable to found any data related to your query.</h5>
			</div>
			<?php
		}
	?>
	</div>
</div>
							