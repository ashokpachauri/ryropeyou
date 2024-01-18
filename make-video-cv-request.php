<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<?php
            function getItsStatus($status)
            {
                switch($status){
                    case '0':return 'Pending Verification';break;
                    case '1':return 'Accepted';break;
                    case '2':return 'Cancelled';break;
                    case '3':return 'Rejected';break;
                    default:return 'Pending Verification';break;
                }
            }
			if(isset($_GET['action']) && $_GET['action']!="")
			{
				$action = $_GET['action'];
				$t=$_GET['t'];
				if($t=="")
				{
					?>
					<script>
						alert('invalid action');
						window.location.href="<?php echo base_url; ?>make-video-cv-request";
					</script>
					<?php
				}
				if($action=="ccl")
				{
					$query="UPDATE video_cv_requests SET status=2 WHERE id='$t' AND user_id='".$_COOKIE['uid']."'";
					$result=mysqli_query($conn,$query);
					if($result)
					{
						?>
						<script>
							alert('Request has been cancelled.');
							window.location.href="<?php echo base_url; ?>make-video-cv-request";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server issue please retry.');
							window.location.href="<?php echo base_url; ?>make-video-cv-request";
						</script>
						<?php
					}
				}
				if($action=="rit")
				{
					$query="UPDATE video_cv_requests SET status=0,added=NOW() WHERE id='$t' AND user_id='".$_COOKIE['uid']."'";
					$result=mysqli_query($conn,$query);
					if($result)
					{
						?>
						<script>
							alert('Request has been Re-initiated.');
							window.location.href="<?php echo base_url; ?>make-video-cv-request";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server issue please retry.');
							window.location.href="<?php echo base_url; ?>make-video-cv-request";
						</script>
						<?php
					}
				}
				if($action=="dlt")
				{
					$query="DELETE FROM video_cv_requests WHERE id='$t' AND user_id='".$_COOKIE['uid']."'";
					$result=mysqli_query($conn,$query);
					if($result)
					{
						?>
						<script>
							alert('Request has been deleted.');
							window.location.href="<?php echo base_url; ?>make-video-cv-request";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server issue please retry.');
							window.location.href="<?php echo base_url; ?>make-video-cv-request";
						</script>
						<?php
					}
				}
			}
			if(isset($_POST['save_video_request']))
			{
				$request_for=$_POST['request_for'];
				$email=$_POST['email'];
				$mobile=$_POST['mobile'];
				$city=$_POST['city'];
				$country=$_POST['country'];
				$text_message=$_POST['text_message'];
				$user_id=$_COOKIE['uid'];
				$request_number="R-".$user_id."-".time();
				$status=0;
				$query="INSERT INTO video_cv_requests SET text_message='$text_message',added=NOW(),status='$status',request_number='$request_number',request_for='$request_for',email='$email',mobile='$mobile',city='$city',country='$country',user_id='$user_id'";
				$result=mysqli_query($conn,$query);
				if($result)
				{
					unset($_POST);
					?>
					<script>
						alert('Your request has been saved.We will notify updates on given email address and/or mobile.');
						window.location.href="<?php echo base_url; ?>make-video-cv-request";
					</script>
					<?php
				}
				else
				{
					?>
					<script>
						alert('There is some server issue.please contact developer.');
						window.location.href="<?php echo base_url; ?>make-video-cv-request";
					</script>
					<?php
				}
			}
		?>
		<title>Book a video cv request| RopeYou Connects</title>
	</head>
	<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" />
   <body>
      <!-- Navigation -->
      <?php include_once 'header.php'; ?>
	  <div class="py-4">
         <div class="container" style="min-height:600px;">
            <div class="row">
               <!-- Main Content -->
				<main class="col col-xl-12 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					<div class="modal fade raise_a_video_cv_request" id="raise_a_video_cv_request" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="raise_a_video_cv_request_backdrop" aria-hidden="true">
						<div class="modal-dialog modal-md" role="document">
							<div class="modal-content">
								<form action="" method="post">
									<div class="modal-header">
										<h5 class="modal-title" id="raise_a_video_cv_request_backdrop">Raise a request for professional video cv</h5>
									</div>
									<div class="modal-body">
										<div class="p-2 d-flex" style="max-height:20px;min-height:1px;">
											<div style="font-size:10px;min-width:100% !important;color:red;" id="video_request_error"></div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<h6>Video Type*</h6>
													<select class="form-control" name="request_for" required id="request_for">
														<option value="">Select a Video Type</option>
														<option value="Video CV">Video CV</option>
														<option value="Video Profile">Video Profile</option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Email*</h6>
													<input type="text" class="form-control" name="email" required id="email" placeholder="Your Email Address">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Mobile*</h6>
													<input type="text" class="form-control" name="mobile" required id="email" placeholder="Your Mobile Number">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>City*</h6>
													<input type="text" class="form-control" name="city" required id="city" placeholder="Your City">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<h6>Country*</h6>
													<input type="text" class="form-control" name="country" required id="country" placeholder="Your Country">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<h6>Additional Message</h6>
													<textarea name="text_message" id="text_message" rows="3" placeholder="Have an additional message?Write it here..." class="form-control" style="resize:none;"></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="submit" name="save_video_request" id="save_video_request" class="btn btn-primary">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="box shadow-sm border rounded bg-white mb-3">
						<div class="box-title border-bottom p-3">
							<div class="row">
								<div class="col-md-12" style="margin-bottom:5px;padding:10px;">
									<h6>Video CV Requests<button type="button" data-toggle="modal" data-target="#raise_a_video_cv_request" class="btn btn-info pull-right">Raise New Request</button></h6>
								</div>
							</div>
						</div>
						<div class="box-body p-3">
							<table id="example" class="display" style="width:100%">
								<thead>
									<tr>
										<th title="Service Request Number">S.R. Number</th>
										<th>Service</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>City</th>
										<th>Country</th>
										<th>Status</th>
										<th>Request Date</th>
										<th>Action</th>
									</tr>
								</thead>
                                <tbody>
                                    <?php
                                        $psquery="SELECT * FROM video_cv_requests WHERE  user_id='".$_COOKIE['uid']."' ORDER BY id DESC";
										$psresult=mysqli_query($conn,$psquery);
										if(mysqli_num_rows($psresult)>0)
										{
											while($psrow=mysqli_fetch_array($psresult))
											{
                                                ?>
                                                <tr>
                                                    <td><?php echo $psrow['request_number']; ?></td>
                                                    <td><?php echo $psrow['email']; ?></td>
                                                    <td><?php echo $psrow['mobile']; ?></td>
                                                    <td><?php echo $psrow['city']; ?></td>
                                                    <td><?php echo $psrow['country']; ?></td>
                                                    <td><?php echo getItsStatus($psrow['status']); ?></td>
                                                    <td><?php echo date('M d,Y',strtotime($psrow['added'])); ?></td>
                                                    <td>
                                                       <?php
                                                            $html="";
                                                            if($psrow['status']=="2" || $psrow['status']=="3")
                                                            {
                                                                $html.= "<a href='".base_url."make-video-cv-request.php?t=".$psrow['id']."&action=rit' class='btn btn-success' title='Re-Initiate'><i class='fa fa-check-square-o'></i></a>";
                                                            }
                                                            else
                                                            {
                                                                $html.= "<a href='".base_url."make-video-cv-request.php?t=".$psrow['id']."&action=ccl' class='btn btn-danger' title='Cancel Request'><i class='fa fa-times'></i></a>";
                                                            }
                                                            $html.= "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".base_url."make-video-cv-request.php?t=".$psrow['id']."&action=dlt' class='btn btn-danger' title='Delete Request'><i class='fa fa-trash'></i></a>";
                                                            echo $html;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    ?>
                                </tbody>
								<tfoot>
									<tr>
										<th title="Service Request Number">S.R. Number</th>
										<th>Service</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>City</th>
										<th>Country</th>
										<th>Status</th>
										<th>Request Date</th>
										<th>Action</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</main>
				<!--<aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
					<?php
						//include_once 'user-profile-section.php';
						//include_once 'people_you_may_know.php';
					?>
				</aside>-->
            </div>
        </div>
	</div>
    <?php include_once 'scripts.php'; ?>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script>
		var base_url="<?php echo base_url; ?>";
		$(document).ready(function(){
			$('#example').DataTable({
				"order": [],
				"responsive": true
			});
		});
	</script>
   </body>
</html>
