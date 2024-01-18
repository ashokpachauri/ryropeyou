<?php
	include_once 'connection.php';
	$skipped=0;
	$onboarding=getOnBoarding($_COOKIE['uid'],$skipped);
	$profile_percentage=getProfilePercentage($_COOKIE['uid']);
	
	$profile_pic=getUserProfileImage($_COOKIE['uid']);
	$profile_pic_arr=explode("/",$profile_pic);
	$arr=array("a.png","b.png","c.png","d.png","e.png","f.png","g.png","h.png","i.png","j.png","k.png","l.png","m.png","n.png","o.png","p.png","q.png","r.png","s.png","t.png","u.png","v.png","w.png","x.png","y.png","z.png");
	if(in_array(end($profile_pic_arr),$arr))
	{
		$onboarding="profile_pic";
	}
	include_once('fileuploader/src/php/class.fileuploader.php');
	$enabled = true;
?>
<div class="box mb-3 shadow-sm border rounded bg-white profile-box text-center">
	<div class="py-3 px-3 border-bottom">
		<div class="w-100">
			<a title="Edit Profile" class="pull-right btn small btn-sm btn-dark title-action-btn" style="cursor:pointer;float:right;" href="<?php echo base_url.'profile-settings.php?tab=profile_settings'; ?>" ><i class="feather-edit"></i></a>
		</div>
		<?php $profile=getUserProfileImage($_COOKIE['uid']); ?>
		<div class="image-container-custom w-100">
			<img id="user_profile_picture" src="<?php echo $profile; ?>"  data-src="<?php echo $profile; ?>"  class="img-fluid mt-2 rounded-circle image" alt="<?php echo $user_row['first_name']." ".$user_row['last_name']; ?>">
			<div class="overlay" onclick="personal_gallery_media_data();" data-toggle="modal" data-target="#amazing_profile_image_backdrop_modal"><i class="feather-edit"></i><br>Change</div>
			<!--<input type="file" name="profiles" data-fileuploader-default="<?php echo $profile;?>" data-fileuploader-files='<?php echo isset($avatar) ? json_encode(array($avatar)) : '';?>'<?php echo !$enabled ? ' disabled' : ''?>>-->
		
		</div>
		<h6 class="font-weight-bold text-dark mb-1 mt-4"><?php echo $user_row['first_name']." ".$user_row['last_name']; ?></h6>
		<?php
			$last_designation=getUsersCurrentDesignation($_COOKIE['uid']);
			$last_education=getUsersCurrentEducation($_COOKIE['uid']);
			$page_post_html="";
			if($last_designation)
			{
				$page_post_html.='<p style=""><a href="'.base_url.'u/'.$user_row['username'].'">'.$last_designation.'</a></p>';
			}
			if($last_education)
			{
				$page_post_html.='<p style=""><a href="'.base_url.'u/'.$user_row['username'].'">'.$last_education.'</a></p>';
			}
			echo $page_post_html;
		?>
		<!--<p class="mb-0 text-muted"><?php echo $user_row['profile_title']; ?></p>-->
		<div class="progress progress-striped"> 
			<div class="progress-bar progress-bar-success"> Your Profile is 0% Completed.</div>
		</div>
		<p style="text-align:center;margin-bottom:-5px;">Profile Completeness</p>
	</div>
	<div class="modal fade amazing_profile_image_backdrop_modal" id="amazing_profile_image_backdrop_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="amazingProfileImageBackdrop" aria-hidden="true">
		<div class="modal-dialog modal-lg " role="document">
			<div class="modal-content ">
				<div class="modal-header">
					<h6 class="modal-title" id="amazingProfileImageBackdrop">Lets upload a beautiful picture to stand out of croud.</h6>
				</div>
				<div class="modal-body" style="min-height:500px;overflow-y:auto;">											
					<div class="p-0" id="personal_gallery_media_data">												
						<div class="form w-100">
							<input type="file" name="files" data-pre="1" id="personal" class="gallery_media">
						</div>
					</div>
				</div>
				<div class="modal-footer-full-width p-4">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="d-flex">
		<div class="col-6 border-right px-3 py-2">
		   <p class="mb-0 text-black-50 small"><a href="<?php echo base_url; ?>u/<?php echo $user_row['username']; ?>/connections"><span class="font-weight-bold text-dark"><?php echo getUserConnectionCounts($_COOKIE['uid']); ?></span>  Connections</a></p>
		</div>
		<div class="col-6 px-3 py-2">
		   <p class="mb-0 text-black-50 small"><a href="<?php echo base_url; ?>u/<?php echo $user_row['username']; ?>/profile-views"><span class="font-weight-bold text-dark"><?php echo getUserProfileViews($_COOKIE['uid']); ?></span>  Views</a></p>
		</div>
	</div>
</div>
<script>
	var profile_percentage="<?php echo $profile_percentage; ?>";
	$(document).ready( function(){
		var x = parseInt(profile_percentage);
		window.percent = 0;
		window.progressInterval = window.setInterval( function(){
			if(window.percent < x) {
				window.percent++;
				$('.progress').addClass('progress-striped').addClass('active');
				$('.progress .progress-bar:first').removeClass().addClass('progress-bar')
				.addClass ( (percent < 30) ? 'progress-bar-danger' : ( (percent < 60) ? 'progress-bar-warning' : 'progress-bar-success' ) ) ;
				$('.progress .progress-bar:first').width(window.percent+'%');
				$('.progress .progress-bar:first').text(window.percent+'%');
			} else {
				window.clearInterval(window.progressInterval);
				// jQuery('.progress').removeClass('progress-striped').removeClass('active');
				//jQuery('.progress .progress-bar:first').text('Done!');
			}
		}, 50 );
	});
	var click_counter=0;
	function personal_gallery_media_data()
	{
		if(click_counter==0)
		{
			click_counter=click_counter+1;
			$("#personal_gallery_media_data").html('');
			$("#personal_gallery_media_data").load('gallery_media.php');
		}
	}
</script>