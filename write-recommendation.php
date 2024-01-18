<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	<head>
		<?php 
			include_once 'head.php';
            if(!isset($_REQUEST['thread']) || $_REQUEST['thread']=="")
            {
                include_once '404.php';
                die();
            }
            else
            {
                $thread=$_REQUEST['thread'];
                $query="SELECT * FROM recommendations WHERE id='$thread' AND r_user_id='".$_COOKIE['uid']."'";
                $result=mysqli_query($conn,$query);
                if(mysqli_num_rows($result)<=0)
                {
                    include_once '404.php';
                    die();
                }   
            }
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Write Recommendation</title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>css/feeling.css" />
		<link href="<?php echo base_url; ?>fileuploader/dist/font/font-fileuploader.css" rel="stylesheet">
		<link href="<?php echo base_url; ?>fileuploader/dist/jquery.fileuploader.min.css" rel="stylesheet">
		<link href="<?php echo base_url; ?>fileuploader/examples/avatar/css/jquery.fileuploader-theme-avatar.css" rel="stylesheet">
	</head>
	<body>
		<style>
			.commentssec .text-gray-500 {
				color: #72808c !important;
			}
			.fileuploader {
				width: 160px;
				height: 160px;
				margin: 15px;
			}
		</style>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
		<?php include_once 'header.php'; ?>
		<div class="py-4">
			<div class="container" style="min-width:99%;">
                <div class="row">
                    <div class="col-md-12" style="min-height:550px;">
                        <div class="row">
                            <div class="col-md-3 text-center" style="margin-bottom:20px;padding-top:25px;">
                                <?php
                                    $theatre=0;
                                    $text_active="active";
                                    $video_active="";
                                    $load_str="get-text-recommendation-html.php?thread=".$thread;
                                    $thread=$_REQUEST['thread'];
                                    if(isset($_REQUEST['theatre']) && $_REQUEST['theatre']!=""){
                                        $theatre=$_REQUEST['theatre']; 
                                    }  
                                    if($theatre=="0" || $theatre=="")
                                    {
                                        $load_str="get-text-recommendation-html.php?thread=".$thread;
                                        $text_active="active";
                                        $video_active="";
                                    }
                                    else
                                    {
                                        $text_active="";
                                        $video_active="active";
                                        $load_str="get-video-recommendation-html.php?thread=".$thread;
                                    }
                                ?>
                                <div class="box shadow-sm border rounded bg-white mb-3" style="padding-top:75px;padding-bottom:75px;">
                                    <button class="btn btn-info" onclick="loadHtml('get-text-recommendation-html.php?thread=<?php echo $thread; ?>');" type="button">Text Recommendation</button><br/><br/><button class="btn btn-primary" type="button" onclick="loadHtml('get-video-recommendation-html.php?thread=<?php echo $thread; ?>')" >Video Recommendation</button>
                                </div>
                            </div>
                            <div class="col-md-8" style="min-height:200px;padding-top:25px;">
                                <div class="box shadow-sm border rounded bg-white mb-3 p-3" id="recommendation_centre" >
                                
                                </div>
                            </div>
                            <script>
                                function loadHtml(source)
                                {
                                    $("#recommendation_centre").load(source);
                                }
                                loadHtml('<?php echo $load_str; ?>');
                            </script>
                        </div>
			         </div>
                </div>
			</div>
		</div>
		<?php include_once 'scripts.php'; ?>
		<script src="<?php echo base_url; ?>/js/sweetalert.min.js"></script>
		<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
		<script>
			var base_url="<?php echo base_url; ?>";
		</script>
   </body>
</html>
