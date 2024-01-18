<div class="p-2 text-left border-bottom">
	<h6 class="font-weight-bold mb-0" style=" text-transform: uppercase;"><img src="img/videocv.png" style="cursor: pointer;width: 25px;margin-right: 5px;"> Video cv or profile 
		<div class="btn-group ml-auto pull-right">
			<a type="button" class="dropdown-toggle float-right btn small btn-sm btn-dark title-action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="feather-settings"></i>														
			</a>
			<div class="dropdown-menu dropdown-menu-right" style="">
				<button class="dropdown-item" onclick="recordVideoCV('2');" type="button">Record Video CV</button>
				<button class="dropdown-item" type="button" onclick="getVideoCV();">Upload Video CV</button>
				<button class="dropdown-item" type="button" onclick="book_a_video_cv_request();">Book a Request</button>
			</div>
		</div>
	</h6>
</div>
<?php
	$v_query="SELECT * FROM users_resume WHERE user_id='".$_COOKIE['uid']."' AND profile_type=2 AND is_default=1 ORDER BY id DESC";
	$v_result=mysqli_query($conn,$v_query);
	$video_num_rows=mysqli_num_rows($v_result);
	$video_file="";
	$token_video="";
	$video_file_id="";
	$clear_crystal_clear_url="";
	$visibility=1;
	$thumbnail=="";
	if($video_num_rows>0)
	{
		
		
		$v_row=mysqli_fetch_array($v_result);
		$video_file_id=$v_row['file'];
		
		$video_query="SELECT * FROM videos WHERE id='$video_file_id'";
		$video_result=mysqli_query($conn,$video_query);
		if(mysqli_num_rows($video_result)>0)
		{
			$video_row=mysqli_fetch_array($video_result);
			$video_tags=$video_row['video_tags'];
			$thumbnail=$video_row['cover_image'];
			$visibility=$video_row['visibility'];
			$video_file=base_url.'ajax.videostream.php?action=stream&file_key='.base64_encode($video_row['file']);
			$clear_crystal_clear_url=base_url.'streaming.php?file_key='.md5($video_row['id']);
		}
		
		
		$profile_title=$v_row['profile_title'];
		$video_type=$v_row['video_type'];
		$token_video=$v_row['id'];
		$resume_headline=$v_row['resume_headline'];
		$profile_title=$v_row['profile_title'];
	}
	if($thumbnail=="")
	{
		$cover_image=default_cover_image;
	}
	else
	{
		$cover_image=$thumbnail;
	}
	if($video_file!="")
	{
?>
		<div>
			<video class="w-100" style="height:150px;" poster="<?php echo base_url.$cover_image; ?>" controls controlsList="nodownload" nodownload id="video_preview_data">
			  <source src="<?php echo $video_file; ?>">
				Your browser does not support HTML5 video.
			</video>
		</div>
		<?php
			if($profile_title!="")
			{
		?>
				<div class="p-1">
					<p class="m-0 font-weight-normel" id="video_profile_title" style="font-weight:normal !important;margin-top:20px;font-size:12px;"><?php echo substr($profile_title,0,160); ?></p>
					<hr/>
				</div>
<?php
			}
?>
		<div class="row" style="margin-bottom:10px;">
			<div class="col-md-6">
				<h6>Share</h6>
			</div>
			<div class="col-md-6" style="padding-right:20px;margin-top:-5px;">
				<div class="row">
					<div class="col-md-4">
						<a title="Share with facebook" class="font-weight-bold d-block text-center social_icon_temp" href="javascript:void(0);" onclick="shareUrlOnFB('<?php echo $clear_crystal_clear_url ?>');" style="font-size:20px;"><i class="fa fa-facebook"></i></a>
					</div>
					<div class="col-md-4">
						<a title="Share with twitter" class="font-weight-bold d-block text-center social_icon_temp" href="https://twitter.com/intent/tweet?text=<?php echo urlencode($profile_title) ?>&url=<?php echo urlencode($clear_crystal_clear_url); ?>&hashtags=<?php echo urlencode($video_tags) ?>" target="_blank" style="font-size:20px;background-color:#00b7d6 !important;"><i class="fa fa-twitter"></i></a>
					</div>
					<div class="col-md-4">
						<a title="Send in Email" class="font-weight-bold d-block text-center social_icon_temp" href = "mailto:youremail@gmail.com?subject=<?php echo $profile_title; ?>&body = <?php echo $clear_crystal_clear_url; ?>" style="font-size:20px;background-color:#00b7d6 !important;">
							<i class="fa fa-envelope-o"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
<?php
	}
	else{
		?>
		<div>
			<video class="w-100" style="height:150px;" poster="<?php echo base_url.$cover_image; ?>" controls controlsList="nodownload" nodownload id="video_preview_data">
			  <source src="">
				Your browser does not support HTML5 video.
			</video>
		</div>
		<div class="p-1">
			<p class="m-0 font-weight-normel" id="video_profile_title" style="font-weight:normal !important;margin-top:20px;font-size:12px;">Please upload your video cv</p>
		</div>
		<?php
	}
?>
						