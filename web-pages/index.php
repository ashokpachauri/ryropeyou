<?php include_once 'data_master.php'; ?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta property="og:url" content="<?php echo base_url."w/".$_username; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo "{$_WEB_AUTHOR}"; ?> - <?php echo "{$_WEB_DESIGNATION}"; ?> Powered by @RopeYou Connects" />
	<meta property="og:description" content="<?php echo @strip_tags($_WEB_AUTHOR_ABOUT); ?>" />
	<meta property="og:image" content="<?php echo "{$_WEB_AUTHOR_IMAGE}";?>"/>
	<meta property="fb:app_id" content="465307587452391"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <meta name="author" content="RopeYou Connects,<?php echo "{$_WEB_AUTHOR}"; ?>,<?php echo "{$_WEB_DESIGNATION}"; ?> #xboyonweb #xgirlonweb"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <title><?php echo "{$_WEB_TITLE}"; ?></title>
    <link rel="icon" href="<?php echo base_url; ?>images/fav.png" />
    <link rel="apple-touch-icon" href="<?php echo base_url; ?>images/fav.png"/>
	<?php include_once "simple/".$_theme_selected."/theme-head.php"; ?>
	<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5fe339f07c936200185ee881&product=inline-share-buttons" async="async"></script>
</head>
<style>
	@media only screen and (max-device-width: 768px)
	{
		#about #profile{
			left:50% !important;
			position:relative;
		}
		#about #intro-div{
			padding:0px !important;
			width:100% !important;
			margin-top:-170px;
		}
		.row .col {
			box-sizing: border-box;
			float: none;
			
			/* padding: 0 0.75rem; */
		}
		#header nav #side-nav-mask.visible{
			visibility:hidden !important;
		}
	}
</style>
<body>
<?php 
	include_once "simple/".$_theme_selected."/theme-loader.php";
	include_once "simple/".$_theme_selected."/theme-header.php";
	include_once "simple/".$_theme_selected."/theme-sections.php";
	include_once "simple/".$_theme_selected."/theme-footer.php"; 
?>
</body>
</html>