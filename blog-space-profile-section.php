<style>
.image-container-custom{
	position: relative;
}
.overlay {
	cursor: pointer;
	position: absolute;
	bottom: 0;
	left: 0;
	background: rgba(0, 0, 0, 0.5);
	width: 150px;
	height: 75px;
	transition: .5s ease;
	opacity: 0;
	color: white;
	font-size: 15px;
	text-align: center;
	border-bottom-left-radius: 150px;
	border-bottom-right-radius: 150px;
	right: 0;
	margin: auto;
	padding: 17px 0;
}
.image-container-custom:hover .overlay {
  opacity: 1;
}
.hidden-on-dashboard {
	display:none;
}
#progress-bar{
  appearance:none;
  width: 100%;
  color: #000;
  height: 2px;
  margin: 0 auto;
}
.pp{
  font-size: 12pt;
  color: #000;
  text-align: center;
}
</style>
<?php
	include_once 'connection.php';
	include_once('fileuploader/src/php/class.fileuploader.php');
	$enabled = true;
?>
<div class="box mb-3 shadow-sm border rounded bg-white profile-box text-center">
	<div class="py-3 px-3 border-bottom">
		<?php 
			$space_url=$_REQUEST['_page'];
			$blog_space_content=getBlogSpace($space_url);
			$blog_space_profile_image=getBlogSpaceProfileImage($space_url); 
		?>
		<div class="image-container-custom" style="width:100%;">
			<img id="user_profile_picture" src="<?php echo $blog_space_profile_image; ?>"  data-src="<?php echo $blog_space_profile_image; ?>"  class="img-fluid mt-2 rounded-circle image" style="width:150px;height:150px;border:1px solid #eaebec !important;" alt="<?php echo ucwords(strtolower($blog_space_content['title'])); ?>">
			<div class="overlay" onclick="personal_gallery_media_data();" data-toggle="modal" data-target="#amazing_profile_image_backdrop_modal"><i class="feather-edit"></i><br>Change</div>		
		</div>
		<h6 class="font-weight-bold text-dark mb-1 mt-4"><?php echo ucwords(strtolower($blog_space_content['title'])); ?></h6>
	</div>
</div>