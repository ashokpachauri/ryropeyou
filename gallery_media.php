<?php
	include_once 'connection.php';
	$gallery_type="personal";
	$type_array=array("personal","professional");
	if(isset($_REQUEST['gallery_type']) && $_REQUEST['gallery_type']!="" && in_array($_REQUEST['gallery_type'],$type_array))
	{
		$gallery_type=$_REQUEST['gallery_type'];
	}
	$pre=1;
	$pre_array=array(0,1);
	if(isset($_REQUEST['pre']) && $_REQUEST['pre']!="" && in_array($_REQUEST['pre'],$pre_array))
	{
		$pre=$_REQUEST['pre'];
	}
	
?>
<link href="<?php echo base_url; ?>fileuploader/dist/font/font-fileuploader.css" rel="stylesheet">
<link href="<?php echo base_url; ?>fileuploader/dist/jquery.fileuploader.min.css" media="all" rel="stylesheet">
<link href="<?php echo base_url; ?>fileuploader/examples/gallery/css/jquery.fileuploader-theme-gallery.css" media="all" rel="stylesheet">
<div class="form" style="width:100%;"> <input type="file" name="files" data-pre="<?php echo $pre; ?>" id="<?php echo $gallery_type; ?>" class="gallery_media"></div>
<script src="<?php echo base_url; ?>fileuploader/dist/jquery.fileuploader.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>fileuploader/examples/gallery/js/custom.js" type="text/javascript"></script>