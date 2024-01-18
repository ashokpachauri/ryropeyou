<?php
	include_once 'connection.php';
	$script="";
	if(isset($_REQUEST['script']) && $_REQUEST['script']!="")
	{
		$script=$_REQUEST['script'];
		
	}
	$user_id=$_COOKIE['uid'];
?>
<div class="row" id="internal_dom_recorder">
   <div class="col-md-12" style="margin-bottom:10px;">
       <h6>Video Recording Interface</h6>
   </div>
	<div class="col-md-12">
        <div class="row">
            <input type="hidden" name="video_module_id" id="video_module_id" value="<?php echo $_REQUEST['thread']; ?>">
            <input type="hidden" name="video_module_type" id="video_module_type" value="<?php echo urldecode($_REQUEST['video_module_type']); ?>">
            <div class="col-md-12">
                <div class="row">
                    <!--<div class="col-md-3">
                        <div class="form-group">
                            <button class="btn btn-warning" data-toggle="modal" data-target="#video_recommendation_recording_modal" type="button">Record Video</button>
                        </div>
                    </div>-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12" style="/*border:1px solid #000;*/">
                                <div class="modal fade instruction_modal" id="instruction_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="instruction_modalBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="width:100%;">
                                                <h6 class="modal-title" id="instruction_modalBackdrop" style="width:100%;">Instructions<button type="button" onclick="$('#instruction_modal').modal('hide');" style="float:right;"><i class="fa fa-times"></i></button></h6>
                                            </div>
                                            <div class="modal-body" style="max-height:550px;overflow-y:auto;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <video id="imstruction_video" playsinline loop controls nodownload controlsList="nodownload" style="width:100%;">
                                                            <source src="<?php echo base_url; ?>sample-video-cv.mp4" type="video/mp4"></source>
                                                        </video>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p style="line-height:23px;font-size:15px;font-weight:bold;margin-top:10px;">Instructions:</p>
                                                        <p style="line-height:23px;font-size:13px;font-weight:bold;">1. Click on <button class="btn btn-warning" style="height:19px;width:19px;padding:1px;margin:0px;margin:0px;" disabled id="start1" title="Start Camera"><i class="feather-video-off"></i></button>&nbsp;&nbsp; to start your web camera.<br/>
                                                        2. Allow access to your web-cam & Audio devices in browser popup.<br/>
                                                        3. Wait for the video stream to appear.<br/>
                                                        4. Set yourself in the frame.<br/>
                                                        5. Once you are ready, click on <button class="btn btn-primary" disabled style="height:19px;width:19px;padding:1px;margin:0px;margin:0px;" id="record1" title="Record Video"><i class="feather-circle"></i></button> to start recording your video cv.<br/>
                                                        6.If you wants to use teleprompter, click on <button disabled class="btn btn-primary" style="height:19px;width:19px;padding:1px;margin:0px;margin:0px;" ><i class="feather-settings"></i></button><br/>
                                                        7.Once you finish recording, to stop click on <button disabled class="btn btn-primary" style="height:19px;width:19px;padding:1px;margin:0px;margin:0px;" id="record2" title="Stop Video"><i class="feather-pause"></i></button><br/>
                                                        8. click on <button class="btn btn-danger" disabled style="height:19px;width:19px;padding:1px;margin:0px;margin:0px;" id="play1" title="Play"><i class="feather-play"></i></button>&nbsp;&nbsp;to watch the preview of recorded video cv.<br/>
                                                        9.Click on <button class="btn btn-success" disabled style="height:19px;width:19px;padding:1px;margin:0px;margin:0px;" id="download1" title="Save"><i class="feather-save"></i></button>&nbsp;&nbsp; to save your video cv.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade recorded_modal" id="recorded_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="recorded_modalBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="width:100%;">
                                                <h6 class="modal-title" id="recorded_modalBackdrop" style="width:100%;">Preview<button type="button" onclick="$('#recorded_modal').modal('hide');"  style="float:right;"><i class="fa fa-times"></i></button></h6>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <video id="recorded" playsinline controls  nodownload controlsList="nodownload" style="width:100%;">
                                                            <source src="" type="video/mp4"></source>
                                                        </video>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade loadMe" id="loadMe" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="loadMeBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="loadMeBackdrop">Action in Progress.</h6>
                                            </div>
                                            <div class="modal-body">											
                                                <div class="p-2 d-flex">

                                                </div>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="loader"></div>
                                                <div class="loader-txt">
                                                  <p>the action performed is in progress. please wait a little.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade teleprompter_backdrop_modal" id="teleprompter_backdrop_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="amazingProfileImageBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="teleprompter_backdrop_modal_backdrop">Lets set the teleprompter script.</h6>
                                            </div>
                                            <div class="modal-body">
                                                <?php 
                                                    $teleprompter="";
                                                    $query="SELECT * FROM teleprompter_script WHERE module_id='".$_REQUEST['thread']."' AND is_active=1 AND user_id='$user_id' AND module_type='".$_REQUEST['video_module_type']."'";
                                                    $result=mysqli_query($conn,$query);
                                                    if(mysqli_num_rows($result)>0)
                                                    {
                                                        $row=mysqli_fetch_array($result);
                                                        $teleprompter=$row['teleprompter'];
                                                    }
													if($teleprompter=="")
													{
														$teleprompter=$script;
													}
                                                ?>
                                                <div class="d-flex">
                                                    <div class="col-12">
                                                        <textarea name="teleprompter_script" id="teleprompter_script" rows="10" class="form-control" placeholder="Enter an attractive script to run over teleprompter."><?php if($teleprompter!=""){ echo $teleprompter; } ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" id="close_modal" onclick="$('#teleprompter_backdrop_modal').modal('hide');" >Close</button>
                                                <button type="button" class="btn btn-primary" onclick="saveTeleprompter();">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" style="padding:0px;max-height:400px;min-height:399px;border:2px solid gray;">
                                        <video playsinline autoplay muted controlsList="nodownload" id="gum" style="width:100%;height:100% !important;padding-left:18px;padding-bottom:20px;padding-top:10px;padding-right:10px;">    
                                            <source src="" type="video/mp4"></source>
                                        </video>
                                    <br/>
                                    <br/>&nbsp;
                                    </div>
                                    <div class="col-md-12" id="toggle_popover_display" style="background:#000;opacity:0.7;color:#fff;max-height:250px;overflow-y:hidden;border:2px solid #efefef;text-align:justify;padding-top:10px;padding-bottom:10px;position:absolute;bottom:90px;display:none;">
                                        <div class="row">
                                            <div class="col-md-12" style="padding-bottom:20px;border-bottom:2px solid #fff;">
                                                <button type="button" class="btn btn-primary" id="pause_animation" data-pause="0"><i class="fa fa-play"></i></button>
                                                <button type="button" class="btn btn-danger pull-right" onclick="$('#toggle_popover_display').toggle('display');getTelePrompter();" style="float:right !important;"><i class="fa fa-times"></i></button>
                                            </div>
                                            <div class="col-md-12" id="animation_box" style="max-height:250px;overflow:hidden;">
                                                <p style="font-weight:bold;font-size:22px;color:#fff;"><?php
												echo $teleprompter;
												?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="text-align:center;padding-top:10px;padding-bottom:10px;position:absolute;bottom:2px;background:#efefef;border-top:1px solid #000;">
                                        <button  type="button" title="Add Teleprompter Script" data-toggle="modal" data-target="#teleprompter_backdrop_modal" class="btn btn-primary" style="margin-right:10px !important;"><i class="feather-settings"></i></button>
                                        <button type="button" class="btn btn-warning" style="margin-right:10px !important;" id="start" data-start="1" title="Start Camera"><i class="feather-video-off"></i></button>&nbsp;&nbsp;

                                        <button type="button" class="btn btn-success" style="margin-right:10px !important;" title="Start Teleprompter" onclick="$('#toggle_popover_display').toggle('display');getTelePrompter();"><i class="fa fa-television"></i></button>
                                        &nbsp;&nbsp;<button type="button" class="btn btn-primary" id="record" style="margin-right:10px !important;" title="Record Video"><i class="feather-circle"></i></button>&nbsp;&nbsp;
                                        <button type="button" class="btn btn-danger" style="margin-right:10px !important;" id="play" title="Play"><i class="feather-play"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;

                                        <button type="button" title="Reload Proccess" onclick="reloadProcess();" class="btn btn-danger pull-right" style="float:right !important;margin-right:10px !important;"><i class="feather-refresh-cw"></i></button>
                                    </div>
                                </div>
                                 <span id="errorMsg"></span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:20px;">
                <div class="form-group">
                    <button type="button" name="save_recorded_video" class="btn btn-info" id="download">Save</button>
                </div>
            </div>
        </div>
    </div>
	<script id="sweat_alert_js" src="<?php echo base_url; ?>/js/sweetalert.min.js"></script>
	<script id="adapter_js" src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
	<script id="global_js">
		var base_url="<?php echo base_url; ?>";
		function openInstruction()
		{
			$("#instruction_modal").modal('show');
		}
		var tpeompter=0;
		var cover_image="<?php echo default_cover_image; ?>";
		var visibility=1;//public
		var video_tags="";
		var animation_container=$("#animation_box");
		function getTelePrompter()
		{
			if(tpeompter==0)
			{
				console.log('teleprompter started...');
				tpeompter=tpeompter+1;
				$("#animation_box").teleprompter();
				$("#pause_animation").data('pause',0);
				$("#pause_animation").html('<i class="fa fa-play"></i>');
				
			}
			else
			{
				tpeompter=0;
				$("#pause_animation").data('pause',1);
				$("#pause_animation").html('<i class="fa fa-play"></i>');
				
				animation_container.clearQueue();
				animation_container.stop();
			}
		}
		(function($) {
			  "use strict"; 
			$('[data-toggle="tooltip"]').tooltip();
			var tDate=localStorage.getItem("tDate");
			var d = new Date();
			var month = d.getMonth()+1;
			var day = d.getDate();

			var outputDate = d.getFullYear() + '/' +
				((''+month).length<2 ? '0' : '') + month + '/' +
				((''+day).length<2 ? '0' : '') + day;
				localStorage.setItem("tDate",outputDate)
				//localStorage.setItem("teleprompter",$("#animation_box").html());
			if(tDate==outputDate)
			{
				$("#animation_box").html(localStorage.getItem("teleprompter"));
				$("#teleprompter_script").val($(localStorage.getItem("teleprompter")).text().trim());
			}
			else
			{
				localStorage.setItem("teleprompter",$("#animation_box").html());
			}
		})(jQuery);
		
		
		
		
		
		if (typeof mediaRecorder !== 'undefined') {
			
		}
		else{
			let mediaRecorder;
		}
		if (typeof recordedBlobs !== 'undefined') {
			recordedBlobs=[];
		}
		else{
			let recordedBlobs;
		}
		var errorMsgElement = document.querySelector('span#errorMsg');
		var recordedVideo = document.querySelector('video#recorded');
		var recordButton = document.querySelector('button#record');
		var playButton = document.querySelector('button#play');
		var downloadButton = document.querySelector('button#download');
		recordButton.addEventListener('click', () => {
		  if (recordButton.innerHTML === '<i class="feather-circle"></i>') {
			startRecording();
		  } else {
			stopRecording();
			recordButton.innerHTML = '<i class="feather-circle"></i>';
			playButton.disabled = false;
			downloadButton.disabled = false;
		  }
		});

		playButton.addEventListener('click', () => {
		  const superBuffer = new Blob(recordedBlobs, {type: 'video/mp4'});
		  recordedVideo.src = null;
		  recordedVideo.srcObject = null;
		  recordedVideo.src = window.URL.createObjectURL(superBuffer);
		  recordedVideo.controls = true;
		  recordedVideo.play();
		  $("#recorded_modal").modal({
					backdrop: "static", //remove ability to close modal with click
					keyboard: false, //remove option to close with keyboard
					show: true //Display loader!
				});
		});
		
		downloadButton.addEventListener('click', () => {
			if(recordedBlobs==null || recordedBlobs.length<0)
			{
				alert('You have not recorded anything yet.');
				return false;
			}
			const blob = new Blob(recordedBlobs, {type: 'video/mp4'});
			const url = window.URL.createObjectURL(blob);
			
			var video_module_type=$("#video_module_type").val();
			var video_module_id=$("#video_module_id").val();
			var data = new FormData();
			data.append('video_module_type',video_module_type);
			data.append('video_module_id',video_module_id);
			data.append('video', blob);  //Correct: sending the Blob itself
			if(blob!=null)
			{
				$("#loadMe").modal({
					backdrop: "static", //remove ability to close modal with click
					keyboard: false, //remove option to close with keyboard
					show: true //Display loader!
				});
				$.ajax({
					type: "POST",
					enctype: 'multipart/form-data',
					url: base_url+"global-save-recorded-video",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 600000,
					success: function (data) {
						$("#loadMe").modal('hide');
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							alert('Your Video Has Been Saved Successfully.');
							var file_name=parsedJson.file;
							var play_url=parsedJson.file_key_url;
							var share_url=parsedJson.file_key_id_url;
							$("#video_id").val(parsedJson.video_id);
						}
						else
						{
							alert(parsedJson.message);
						}
					},
					error: function (e) {
						console.log("ERROR : ", e);
					}
				});
			}
			else
			{
				alert('Please fill all required fields.');
			}
		});

		function handleDataAvailable(event) {
		  console.log('handleDataAvailable', event);
		  if (event.data && event.data.size > 0) {
			recordedBlobs.push(event.data);
		  }
		}

		function startRecording() {
			 recordedBlobs = [];
			 let options = {mimeType: 'video/mp4;codecs=vp9,opus'};
			 if (!MediaRecorder.isTypeSupported(options.mimeType)) {
				console.error(`${options.mimeType} is not supported`);
				options = {mimeType: 'video/mp4;codecs=vp8,opus'};
				if (!MediaRecorder.isTypeSupported(options.mimeType)) {
					console.error(`${options.mimeType} is not supported`);
					options={mimeType:'video/webm;codecs=vp9'};
					if(!MediaRecorder.isTypeSupported(options.mimeType))
					{
						console.error(`${options.mimeType} is not supported`);
						options = {mimeType: 'video/webm'};
						if (!MediaRecorder.isTypeSupported(options.mimeType)) {
							console.error(`${options.mimeType} is not supported`);
						   options = {mimeType: ''};
						}
					}
				}
			}

		  try {
			mediaRecorder = new MediaRecorder(window.stream, options);
		  } catch (e) {
			console.error('Exception while creating MediaRecorder:', e);
			errorMsgElement.innerHTML = `Exception while creating MediaRecorder: ${JSON.stringify(e)}`;
			return;
		  }

		  console.log('Created MediaRecorder', mediaRecorder, 'with options', options);
		  recordButton.innerHTML = '<i class="feather-pause"></i>';
		  playButton.disabled = true;
		  downloadButton.disabled = true;
		  mediaRecorder.onstop = (event) => {
			console.log('Recorder stopped: ', event);
			console.log('Recorded Blobs: ', recordedBlobs);
		  };
		  mediaRecorder.ondataavailable = handleDataAvailable;
		  mediaRecorder.start();
		  console.log('MediaRecorder started', mediaRecorder);
		}

		function stopRecording() {
		  mediaRecorder.stop();
		  stream.getTracks().forEach(track => track.stop());
		  //localMediaStream.stop()
		}

		function handleSuccess(stream) {
		  recordButton.disabled = false;
		  console.log('getUserMedia() got stream:', stream);
		  window.stream = stream;

		  const gumVideo = document.querySelector('video#gum');
		  gumVideo.srcObject = stream;
		}

		async function init(constraints) {
		  try {
			const stream = await navigator.mediaDevices.getUserMedia(constraints);
			handleSuccess(stream);
		  } catch (e) {
			console.error('navigator.getUserMedia error:', e);
			errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
		  }
		}

		document.querySelector('button#start').addEventListener('click', async () => {
			var data_start=$("#start").data("start");
			if(data_start=="1")
			{
				$("#start").data("start","0");
				$("#start").html('<i class="feather-video"></i>');
				const hasEchoCancellation = true;
				const constraints = {
				audio: {
					echoCancellation: {exact: hasEchoCancellation}
				},
				video: {
					width:540, height: 320
				}
			  };
			  await init(constraints);
			}
			else
			{
				$("#start").data("start","1");
				$("#start").html('<i class="feather-video-off"></i>');
				recordButton.disabled = true;
				playButton.disabled = true;
				downloadButton.disabled = true;
				stream.getTracks().forEach(track => track.stop());
			}
		  
		});
		(function($) {
		  $.fn.teleprompter = function() {
			return this.each(function() {
				var container = $(this),
				containerHeight,
				script = container.children().first()[0],
				maxScrollTop;
				containerHeight = this.offsetHeight;
				script.style.marginBottom = containerHeight + "px";
				script.style.marginTop = containerHeight + "px";
				maxScrollTop = this.scrollHeight - containerHeight;
				
				$("#pause_animation").click(function(){
					var data_attr=$("#pause_animation").data('pause');
					if(data_attr=="1")
					{
						$("#pause_animation").data('pause',0);
						$("#pause_animation").html('<i class="fa fa-play"></i>');
						container.clearQueue();
						container.stop();
					}
					else
					{
						$("#pause_animation").data('pause',1);
						$("#pause_animation").html('<i class="fa fa-pause"></i>');
						container.animate(
							{ scrollTop: maxScrollTop },
							maxScrollTop * 95, // duration
							"linear" // easing
						);
					}
				});
			});
		  };
		}(jQuery));
		function saveTeleprompter()
		{
			if(confirm('Page will be reloaded if any add/change are made in script,do you want to continue?'))
			{
				animation_container.clearQueue();
				animation_container.stop();
				var teleprompter=$("#teleprompter_script").val();
				teleprompter='<p style="font-weight:bold;font-size:22px;color:#fff;">'+teleprompter+'</p>';
				/*teleprompter=teleprompter+'<p style="font-weight:bold;font-size:22px;color:#fff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>';
				localStorage.setItem("teleprompter",teleprompter);*/
				$("#animation_box").html(teleprompter);
				window.location.reload();
			}
		}
		function reloadProcess()
		{
			if(confirm("Your previous content will be deleted, Should you reload the page?"))
			{
				window.location.reload();
			}
		}
	</script>
</div>