<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once 'head.php'; ?>
		<?php
			if(isset($_GET['action']) && $_GET['action']!="")
			{
				$action = $_GET['action'];
				$t=$_GET['t'];
				if($t=="")
				{
					?>
					<script>
						alert('invalid action');
						window.location.href="<?php echo base_url; ?>profile-selections";
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
							window.location.href="<?php echo base_url; ?>profile-selections";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server issue please retry.');
							window.location.href="<?php echo base_url; ?>profile-selections";
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
							window.location.href="<?php echo base_url; ?>profile-selections";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server issue please retry.');
							window.location.href="<?php echo base_url; ?>profile-selections";
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
							window.location.href="<?php echo base_url; ?>profile-selections";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Some server issue please retry.');
							window.location.href="<?php echo base_url; ?>profile-selections";
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
						window.location.href="<?php echo base_url; ?>profile-selections";
					</script>
					<?php
				}
				else
				{
					?>
					<script>
						alert('There is some server issue.please contact developer.');
						window.location.href="<?php echo base_url; ?>profile-selections";
					</script>
					<?php
				}
			}
		?>
		<title>Profile Selections | RopeYou Connects</title>
	</head>
	<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" />
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
   <body>
      <!-- Navigation -->
      <?php include_once 'header.php'; ?>
	  <div class="py-4">
         <div class="container">
            <div class="row">
               <!-- Main Content -->
				<main class="col col-xl-12 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
					<div class="box shadow-sm border rounded bg-white mb-3">
						<div class="box-title border-bottom p-3">
							<div class="row">
								<div class="col-md-12" style="margin-bottom:5px;padding:10px;">
									<h6>Profile Selections</h6>
								</div>
							</div>
						</div>
						<div class="box-body p-3">
							<table id="example" class="display" style="width:100%">
								<thead>
									<tr>
										<th>S.R.</th>
										<th>Job</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>Expected Salery</th>
										<th>Experience</th>
										<th>Status</th>
										<th>Apply Date</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$psquery="SELECT * FROM job_applications WHERE status!=0 AND status!=1 AND status!=6 AND user_id='".$_COOKIE['uid']."' ORDER BY id DESC";
										$psresult=mysqli_query($conn,$psquery);
										if(mysqli_num_rows($psresult)>0)
										{
											$i=0;
											while($psrow=mysqli_fetch_array($psresult))
											{
												$job_id=$psrow['job_id'];
												$job_query="SELECT id,job_title,job_company,job_location FROM jobs WHERE id='$job_id'";
												$job_result=mysqli_query($conn,$job_query);
												$job_row=mysqli_fetch_array($job_result);
												
												$job_url=base_url."job/".trim(strtolower($job_row['job_title']))." ".trim(strtolower($job_row['job_company']));
												$job_url=str_replace(" ","-",$job_url);
												$job_url=$job_url."-".$job_row['id'].".html";
												?>
												<tr>
													<th><?php  $i=$i+1; echo $i; ?></th>
													<th><a href="<?php echo $job_url; ?>"><?php echo $job_row['job_title'].' at '.$job_row['job_company']; ?></a></th>
													<th><?php echo $psrow['email']; ?></th>
													<th><?php echo $psrow['mobile']; ?></th>
													<th><?php echo $psrow['expected_salery'].' '.$psrow['paid_as']; ?></th>
													<th><?php echo $psrow['experience']; ?> years</th>
													<th>Profile Selected</th>
													<th><?php echo date('M d, Y',strtotime($psrow['added'])); ?></th>
												</tr>
												<?php
											}
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<th>S.R.</th>
										<th>Job</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>Expected Salery</th>
										<th>Experience</th>
										<th>Status</th>
										<th>Apply Date</th>
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
      <!-- Bootstrap core JavaScript -->
    <script src="<?php echo base_url; ?>vendor/jquery/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- slick Slider JS-->
    <script type="text/javascript" src="<?php echo base_url; ?>vendor/slick/slick.min.js"></script>
      <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url; ?>js/osahan.js"></script>
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
