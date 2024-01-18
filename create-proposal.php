<?php include 'connection.php';define("DOMAIN_NAME","https://vsarv.com/"); ?>
<title>Create Proposal</title>
<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME;?>css/bootstrap.css">
<link href="<?php echo DOMAIN_NAME;?>css/font-awesome.css" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo DOMAIN_NAME;?>images/favicon.png" type="image/x-icon">
<link rel="icon" href="<?php echo DOMAIN_NAME;?>images/favicon.png" type="image/x-icon">
<?php
	$client_company_logo="";
	$sender_company_logo="";
	if(isset($_REQUEST['create_proposal']))
	{
		$imageFolder = "proposal_images/";
		if(isset($_FILES["client_company_logo"]["name"]) && $_FILES["client_company_logo"]["name"]!="")
		{
			$client_company_logo=$imageFolder.time().basename($_FILES["client_company_logo"]["name"]);
			move_uploaded_file($_FILES["client_company_logo"]["tmp_name"], $client_company_logo);
		}
		else
		{
			$client_company_logo=$_REQUEST['client_company_logo_old'];
		}
		/*if(isset($_FILES["sender_company_logo"]["name"]) && $_FILES["sender_company_logo"]["name"]!="")
		{
			$sender_company_logo=$imageFolder.time().basename($_FILES["sender_company_logo"]["name"]);
			move_uploaded_file($_FILES["sender_company_logo"]["tmp_name"], $sender_company_logo);
		}
		else
		{
			$sender_company_logo=$_REQUEST['sender_company_logo_old'];
		}*/
		$sender_company_logo="https://www.vsarv.com/images/logo2.png";
		$posted_data=array();
		$posted_data['client_company_logo']=$client_company_logo;
		$posted_data['sender_company_logo']=$sender_company_logo;
		foreach($_REQUEST as $key=>$value)
		{
			if($key=="service_name")
			{
				$service_name=implode("@@@",$_REQUEST['service_name']);
				$posted_data['service_name']=$service_name;
			}
			else if($key=="service_charges")
			{
				$service_charges=implode("@@@",$_REQUEST['service_charges']);
				$posted_data['service_charges']=$service_charges;
			}
			else if($key=="service_quantity")
			{
				$service_quantity=implode("@@@",$_REQUEST['service_quantity']);
				$posted_data['service_quantity']=$service_quantity;
			}
			else if($key=="sub_total")
			{
				$sub_total=implode("@@@",$_REQUEST['sub_total']);
				$posted_data['sub_total']=$sub_total;
			}
			else if($key=="create_proposal" || $key=="__tawkuuid" || $key=="_ga")
			{
				
			}
			else
			{
				$posted_data[''.$key.'']=$value;
			}
		}
		?>
			<script>
				var data=<?php echo json_encode($posted_data) ?>;
				var posted_data=JSON.stringify(data);
				localStorage.setItem('posted_data',posted_data);
			</script>
			<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>css/invoice.css">
			<style>
			.bullet-square{
				list-style-type: square !important;
			}
			</style>
			<title>Proposal Preview</title>
			<div id="send_in_mail_modal" class="modal fade" role="dialog" style="display:none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" onclick="updateIndex();" data-dismiss="modal">&times;</button>
							<h4 class="modal-title" id="modal_title">Send Proposal in Email</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<h5 id="error_mesg" style="text-align:center;color:red;display:none;">Please enter atleast one Email Id</h5>
								</div>
								<div class="col-md-12">
									<input type="hidden" name="proposal_title_in_full" id="proposal_title_in_full" value="<?php echo $_REQUEST['proposal_title']; ?>">
									<input type="hidden" name="sender_name_in_full" id="sender_name_in_full" value="<?php echo $_REQUEST['sender_first_name']." ".$_REQUEST['sender_last_name']; ?>">
									<input type="hidden" name="client_name_in_full" id="client_name_in_full" value="<?php echo $_REQUEST['client_first_name']." ".$_REQUEST['client_last_name']; ?>">
									<input type="hidden" name="client_company_name_in_full" id="client_company_name_in_full" value="<?php echo $_REQUEST['client_company_name']; ?>">
									<input type="hidden" name="client_company_logo_in_full" id="client_company_logo_in_full" value="<?php echo DOMAIN_NAME.$client_company_logo; ?>">
									<input type="hidden" name="sender_company_logo_in_full" id="sender_company_logo_in_full" value="<?php echo $sender_company_logo; ?>">
									<div class="form-group">
										<textarea name="email_ids" id="email_ids" rows="7" class="form-control" placeholder="Email Id(s) seperate each by , in case of multiple Email Ids"></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info pull-left" onclick="sendInMail('proposal_div','<?php echo $_REQUEST['proposal_title']; ?>');">Send</button>
							<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<section class="clients-section">
			<!-- style="background-color:#dcdde2;"-->
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h3 style="text-align:left;color:red;">Proposal Preview<span class="pull-right"><a href="javascript:void(0);" class=" btn btn-info" onclick="Export2Doc('proposal_div','<?php echo $_REQUEST['proposal_title']; ?>');">Download</a> | <a href="javascript:void(0);" class="btn btn-success" data-toggle="modal" data-target="#send_in_mail_modal">Send in Email</a> | <a href="?edit=1" class="btn btn-success">Edit</a></span></h3>
						</div>
						<div class="col-md-12" id="proposal_div">
							<div class="row" style="font-family:calibri;">
								<div class="col-md-12" style="padding:10px;">
									<h2 style="text-align:center;margin-top:50px;"><?php echo $_REQUEST['proposal_title']; ?></h2>
									<br/><br/><br/><br/></br/>
									<h5 style="text-align:center;"><i>Prepared for:</i></h5>
									<h3 style="text-align:center;"><b><?php echo $_REQUEST['client_first_name']." ".$_REQUEST['client_last_name']; ?></b></h3>
									<h4 style="text-align:center;color:gray;"><?php echo $_REQUEST['client_company_name']; ?></h4>
									<div class="row" style="min-height:300px;"><div class="col-md-12"><p style="text-align:center;"><img src="<?php echo DOMAIN_NAME.$client_company_logo; ?>" style="max-width:300px;max-height:200px;text-align:center;position:center;" /></p></div></div>
									<br/><br/><br/><br/><br/><br/><br/></br/>
									<h5 style="text-align:center;"><i>Prepared by:</i></h5>
									<h3 style="text-align:center;"><b>VSARV TEAM</b></h3>
									<h5 style="text-align:center;"><span class="pull-left"><img src="<?php echo DOMAIN_NAME.'images/icon_email.png'; ?>" style="height:25px;"><a href="javascript:void(0);" target="_blank"> info@vsarv.com</a></span>&nbsp; <span class="pull-right"><img src="<?php echo DOMAIN_NAME.'images/icons/phone.png'; ?>" style="height:25px;"><a href="javascript:void(0);" target="_blank"> +91 9654 000 505</a></span>&nbsp; <a href="https://www.vsarv.com" target="_blank"><img src="<?php echo DOMAIN_NAME.'images/icon-web.png'; ?>" style="height:25px;"> https://www.vsarv.com</a></h5>
									<h4 style="text-align:center;"><i>Contact Person</i></h4>
									<h5 style="text-align:center;"><span class="pull-left">Name: &nbsp;<b><?php echo $_REQUEST['sender_first_name']." ".$_REQUEST['sender_last_name']; ?></span></b><span class="pull-right">&nbsp;&nbsp;&nbsp;Mobile: &nbsp;<?php echo $_REQUEST['sender_contact_number']; ?></span></h5>
									<div class="row" style="min-height:300px;margin-top:30px;"><div class="col-md-12"><p style="text-align:center;"><img src="<?php echo $sender_company_logo; ?>" style="max-width:300px;max-height:200px;text-align:center;position:center;" /></p></div></div>
									<br/><br/><br/><br/><br/><br/></br/>
								</div>
								<div class="col-md-12" style="margin-top:50px;">
									<h3 style="color:#5bc0de;">Introduction</h3>
									<p>
										The purpose of this proposal is to give you a bit of information about VSARV and the various services we offer, along with information and pricing for solution based on your needs. Based on our previous discussion and Searches online, I feel like we are a good fit for one another. I’ve spoken with my team, and they’re excited to get to work helping you reach your goals.
									</p>
									<p>
										At the end of this document, you’ll find a pricing table that includes the services we’ve about to Offer you. If after reviewing our full list of services you feel like the items in the pricing table don’t fit your needs appropriately, just send me a comment (to the right) and I’ll make any necessary changes.
									</p>
									<p>
										Once you’re happy with the services and prices for your solution, go ahead and sign at the bottom of this proposal and we’ll move forward from there!
									</p>
								</div>
								<div class="col-md-12" style="padding:10px;">
									<h3 style="color:#5bc0de;">About Us</h3>
									<p>
										VSARV  is a full-service digital marketing agency based in Gurgaon, India. Our in-house services include:
									</p>
									<ul>
										<li class="bullet-square">Search Engine Optimization (SEO)</li>
										<li class="bullet-square">Social Media</li>
										<li class="bullet-square">Web Design & Development</li>
										<li class="bullet-square">Pay-Per-Click (PPC)</li>
										<li class="bullet-square">Digital Content & Video</li>
										<li class="bullet-square">Mobile apps Development </li>
										<li class="bullet-square">Mobile apps Marketing</li>
										<li class="bullet-square">Virtual Employee for your end to end Support</li>
									</ul>
									<p>
									In today’s digital business world, you need a partner who can help you take advantage of marketing opportunities across a variety of channels in real-time. VSARV combines a data-driven approach with knowledge gained from years in digital marketing to deliver outstanding results to our clients.
									</p>
								</div>
								<div class="col-md-12" style="padding:10px;">
									<h3 style="color:#5bc0de;">Our Services</h3>
									<p>
										VSARV is an end-to-end provider of digital marketing services. Whether you’re looking for a turnkey managed strategy, an independent audit, or services specific to a short-term campaign, our experience and approach are sure to prove to be a valuable asset.
									</p>
									<h4 style="text-align:center;color:#5bc0de;">Search Engine Optimization</h4>
									<p>
										Billions of web browsing sessions begin with a search query every day. With more than a billion websites competing for the top spot in search results, it can be difficult to drive traffic to your site from search engines. At VSARV, we specialize in an innovative approach to SEO that uses white-hat tactics to put your website at the top of your target audience’s searches.<br/>
										Our SEO services include:
									</p>
									<ul>
										<li class="bullet-square">Keyword Research</li>
										<li class="bullet-square">Technical SEO</li>
										<li class="bullet-square">Full SEO Audits</li>
										<li class="bullet-square">SEO Consulting</li>
										<li class="bullet-square">Voice Based Search Algo implementation</li>
									</ul>
									<h4 style="text-align:center;color:#5bc0de;">Social Media</h4>
									<p>
										Social Media has changed how brands communicate with their audiences forever. Whether your business is a B2B or B2C brand, social media is a powerful way to build brand awareness, build a positive image, and drive lead generation. More than 1 billion people use social media every day, and platforms like Facebook, LinkedIn, and Snapchat have sophisticated advertising platforms that can help you grow your audience.
									</p>
									<p>
										We specialize in strategic social media campaigns that focus on building and protecting a positive brand image, creating loyalty among fans, and driving new leads for your business. Our full suite of social media services includes:
									</p>
									<ul>
										<li class="bullet-square">Social Strategy Development</li>
										<li class="bullet-square">Social Media Consulting</li>
										<li class="bullet-square">Social Media Advertising</li>
										<li class="bullet-square">Community Engagement</li>
									</ul>
									<p>
										Our data driven approach ensures that you understand the true ROI of your social media efforts, and our team works tirelessly to improve the return on your investment in social media.
									</p>
									<h4 style="text-align:center;color:#5bc0de;">Web Design & Development</h4>
									<p>
										Your website is the center of your digital presence. It’s one of the few places on the internet where you can deliver your brand’s message free of distortion or distraction. VSARV’s web development services are perfect for brands at any stage.
									</p>
									<p>
										Our web development team can help you build your brand’s website from the ground up. We specialize in building websites that tell a unique brand story while meeting the expectations of today’s most discerning consumers.
									</p>
									<p>
										If your website is already built but isn’t performing to expectations, we can perform a detailed audit and work with you to improve site architecture, design, and responsiveness.<br/>
										We offer a full range of web design & development services including:
									</p>
									<ul>
										<li class="bullet-square">Website Design</li>
										<li class="bullet-square">Website Coding</li>
										<li class="bullet-square">Conversion Optimization</li>
										<li class="bullet-square">Mobile Development</li>
									</ul>
									<h4 style="text-align:center;color:#5bc0de;">Pay-Per-Click</h4>
									<p>
										More than 60% of website traffic starts with a search engine query. Pay-Per-Click (PPC) puts your brand at the top of search results for queries relevant to your brand and audience. This valuable advertising real estate can provide an immediate source of targeted traffic to your website, driving conversions and contributing to revenue growth.<br/>
										Our PPC services include:
									</p>
									<ul>
										<li class="bullet-square">PPC Strategy Development</li>
										<li class="bullet-square">PPC Research</li>
										<li class="bullet-square">Campaign Setup</li>
										<li class="bullet-square">Turnkey PPC Campaigns</li>
										<li class="bullet-square">PPC Optimization</li>
									</ul>
									<p>
										At VSARV, we have extensive experience leveraging PPC to drive growth for our clients. Our approach to PPC is data-driven, which allows us to deploy campaigns that focus on efficiency and constant improvement.
									</p>
									<h4 style="text-align:center;color:#5bc0de;">Digital Content & Video</h4>
									<p>
										Content is the king of today’s marketing environment. The most successful brands in the world have developed detailed content strategies that help them inspire, entertain, and educate their target audiences. At VSARV, we specialize in helping our clients plan, produce, and promote content that drives audience engagement and conversions. <br/>
										Our full suite of content services includes:
									</p>
									<ul>
										<li class="bullet-square">Digital Content Strategy</li>
										<li class="bullet-square">Content Production</li>
										<li class="bullet-square">Video Production</li>
										<li class="bullet-square">Graphic Design</li>
										<li class="bullet-square">Multi-language Content</li>
									</ul>
									<p>
										Whether you’re producing blog posts, images, or videos, VSARV can be trusted to support your content marketing efforts.
									</p><br/>
									<h3 style="color:#5bc0de;">Project Details</h3><br/>
									<?php
										echo $_REQUEST['service_description'];
									?>
									<br/>
									<h3 style="color:#5bc0de;margin-top:25px;">Pricing</h3>
									<div class="row">
										<div class="col-md-1"></div>
										<div class="col-md-10 invoice" style="padding:15px;">
											<table border="0" cellspacing="0" cellpadding="0" style="width:100%;border:solid thin #ccc;">
												<thead style="width:100%;">
													<tr style="width:100%;">
														<th style="width:5%;">#</th>
														<th style="width:35%;" class="text-left">Name</th>
														<th style="width:20%;" class="text-right">Price</th>
														<th style="width:20%;" class="text-right">Quantity</th>
														<th style="width:20%;" class="text-right">Subtotal</th>
													</tr>
												</thead>
												<tbody style="width:100%;">
													<?php
														$i=0;
														foreach($_REQUEST['service_name'] as $service)
														{
															$service_charges=$_REQUEST['service_charges'][$i];
															$service_quantity=$_REQUEST['service_quantity'][$i];
															$sub_total_price=$_REQUEST['sub_total'][$i++];
															?>
															<tr style="width:100%">
																<td style="width:5%;border:solid thin #ccc;"><?php echo $i; ?></td>
																<td class="text-left" style="width:35%;border:solid thin #ccc;"><?php echo $service; ?></td>
																<td style="width:20%;border:solid thin #ccc;"> <?php echo $service_charges;  ?></td>
																<td class="qty" style="width:20%;border:solid thin #ccc;"><?php echo $service_quantity;  ?></td>
																<td style="width:20%;border:solid thin #ccc;"><?php echo $sub_total_price; ?></td>
															</tr>
															<?php
														}
													?>
												</tbody>
												<tfoot style="width:100%;">
													<tr>
														<td colspan="2" style="width:40%;border:solid thin #ccc;"></td>
														<td colspan="2" style="width:40%;border:solid thin #ccc;">SUBTOTAL</td>
														<td style="width:20%;border:solid thin #ccc;"><?php echo $_REQUEST['subtotal']; ?></td>
													</tr>
													<tr>
														<td colspan="2" style="width:40%;border:solid thin #ccc;"></td>
														<td colspan="2" style="width:40%;border:solid thin #ccc;">Discount ( <?php if($_REQUEST['discount_percentage']!="") {echo $_REQUEST['discount_percentage'];}else{ echo 'N/A'; } ?> )</td>
														<td style="width:20%;border:solid thin #ccc;"><?php if($_REQUEST['discount_amount']!="") {echo $_REQUEST['discount_amount'];}else{ echo 'N/A'; } ?></td>
													</tr>
													<tr>
														<td colspan="2" style="width:40%;border:solid thin #ccc;"></td>
														<td colspan="2" style="width:40%;border:solid thin #ccc;">TOTAL</td>
														<td style="width:20%;border:solid thin #ccc;"><?php echo $_REQUEST['total_service_charges']; ?></td>
													</tr>
												</tfoot>
											</table>
										</div>
										<div class="col-md-1"></div>
									</div>
									<div class="row">
										<div class="col-md-1"></div>
										<div class="col-md-10" style="padding:15px;">
											<table id="proposal_table_1" style="width:100%;">
												<tr style="width:100%">
													<td style="width:50%;text-align:left;color:gray;padding:10px;">VSARV</td>
													<td style="width:50%;text-align:right;color:gray;padding:10px;"><?php echo $_REQUEST['client_company_name']; ?></td>
												</tr>
												<tr style="width:100%">
													<td style="width:50%;text-align:left;padding:10px;">Signed By: <br/><?php echo $_REQUEST['sender_first_name']." ".$_REQUEST['sender_last_name']; ?></td>
													<td style="width:50%;text-align:right;padding:10px;">Signed By: <br/><?php echo $_REQUEST['client_first_name']." ".$_REQUEST['client_last_name']; ?></td>
												</tr>
												<tr style="width:100%;">
													<td style="width:50%;text-align:left;padding:10px;">------------------------</td>
													<td style="width:50%;text-align:right;padding:10px;">------------------------</td>
												</tr>
												<tr style="width:100%">
													<td style="width:50%;text-align:left;padding:10px;">Date : _______________________</td>
													<td style="width:50%;text-align:right;padding:10px;">Date : _______________________</td>
												</tr>
											</table>
										</div>
										<div class="col-md-1"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
			<script>
			function Export2Doc(element,filename = '')
			{
				var html_content=$("#"+element).html();
				$.ajax({
					url: "export2pdf.php",
					type: "post",
					data: {html_content:html_content},
					success: function(result)
					{
						var link = document.createElement('a');
						link.href = result;
						link.setAttribute('download',filename);
						document.body.appendChild(link);
						link.click(); 
					}
				});
			}
			$("#email_ids").keyup(function(){
				$("#error_mesg").hide();
			});
			function sendInMail(element,filename = '')
			{
				$("#error_mesg").hide();
				var email_ids=$("#email_ids").val();
				if(email_ids!="" && email_ids!=null)
				{
					var proposal_title=$("#proposal_title_in_full").val();
					var sender_company_logo=$("#sender_company_logo_in_full").val();
					var client_company_logo=$("#client_company_logo_in_full").val();
					var client_company_name=$("#client_company_name_in_full").val();
					var sender_name=$("#sender_name_in_full").val();
					var client_name=$("#client_name_in_full").val();
					var html_content=$("#"+element).html();
					$.ajax({
						url: "send-in-email.php",
						type: "post",
						data: {html_content:html_content,client_name:client_name,sender_name:sender_name,client_company_name:client_company_name,client_company_logo:client_company_logo,proposal_title:proposal_title,sender_company_logo:sender_company_logo,email_ids:email_ids},
						success: function(result)
						{
							$("#email_ids").val('')
							$("#send_in_mail_modal").modal("hide");
						}
					});
				}
				else 
				{
					$("#error_mesg").show();
				}
			}

			</script>
		<?php
	}
	else if(isset($_REQUEST['edit']) && $_REQUEST['edit']!="")
	{
		?>
		<section class="clients-section">
		<!-- style="background-color:#dcdde2;"-->
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h3 style="text-align:center;">Edit Proposal</h3>
					</div>
					<form method="post" action="" enctype="multipart/form-data">
						<div class="col-md-12" id="response_div">
						</div>
					</form>
				</div>
			</div>
		</section>
		<!--End Clients Section-->
		<!--End pagewrapper-->

		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=sb4h3aa9hheyhbvehdwg0g4uqvuji4e7r8tc2nlzkjqn9xar"></script>
		<script>
		$(document).ready(function(){
			var posted_data=localStorage.posted_data;
			$.ajax({
				url:'webservices/create-proposal-html.php',
				type:'post',
				data:JSON.parse(posted_data),
				success:function(response)
				{
					$("#response_div").html(response);
				}
			});
		});
		var html=$("#pricing_div").html();
		$(".add_pricing").click(function(){
			$("#pricing_div").append(html);
			removePricing();
		});
		function removePricing()
		{
			$(".remove_pricing").click(function(){
				$(this).parent().parent().parent().remove();
			});
		}
		$(".remove_pricing").click(function(){
			$(this).parent().parent().parent().remove();
		});
		tinymce.init({
			selector:'textarea',
			plugins: [
			'advlist autolink lists link image charmap print preview anchor textcolor',
			'searchreplace visualblocks code fullscreen',
			'image code'
		  ],
		  images_upload_url: 'upload-proposal-images.php',
		  images_upload_handler: function (blobInfo, success, failure) {
				var xhr, formData;
			  
				xhr = new XMLHttpRequest();
				xhr.withCredentials = false;
				xhr.open('POST', 'upload-proposal-images.php');
			  
				xhr.onload = function() {
					var json;
				
					if (xhr.status != 200) {
						failure('HTTP Error: ' + xhr.status);
						return;
					}
				
					json = JSON.parse(xhr.responseText);
				
					if (!json || typeof json.location != 'string') {
						failure('Invalid JSON: ' + xhr.responseText);
						return;
					}
				
					success(json.location);
				};
			  
				formData = new FormData();
				formData.append('file', blobInfo.blob(), blobInfo.filename());
			  
				xhr.send(formData);
			},
			 setup: function (editor) {
					editor.on('keyup', function (e) {
						//$(".content_1").html(this.getContent().replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|(\s+)/ig,''));
						//custom logic  
					});
				}
		 });
		</script>
		<?php
	}
	else
	{
		?>
		<section class="clients-section">
		<!-- style="background-color:#dcdde2;"-->
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h3 style="text-align:center;">Create Proposal</h3>
					</div>
					<form method="post" action="" enctype="multipart/form-data">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Proposal Title</h6>
									<div class="form-group">
										<input type="text" name="proposal_title" id="proposal_title" placeholder="Proposal Title" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Client First Name</h6>
									<div class="form-group">
										<input type="text" name="client_first_name" id="client_first_name" placeholder="Client First Name" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Client Last Name</h6>
									<div class="form-group">
										<input type="text" name="client_last_name" id="client_last_name" placeholder="Client Last Name" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Client Company Name</h6>
									<div class="form-group">
										<input type="text" name="client_company_name" id="client_company_name" placeholder="Client Company Name" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Client Company Logo</h6>
									<div class="form-group">
										<input type="file" name="client_company_logo" id="client_company_logo" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Sender First Name</h6>
									<div class="form-group">
										<input type="text" name="sender_first_name" id="sender_first_name" placeholder="Sender First Name" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Sender Last Name</h6>
									<div class="form-group">
										<input type="text" name="sender_last_name" id="sender_last_name" placeholder="Sender Last Name" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-4">
									<h6 style="margin-bottom:10px;">Sender Contact Number</h6>
									<div class="form-group">
										<input type="text" name="sender_contact_number" id="sender_contact_number" placeholder="Sender Contact Number" class="form-control" required>
									</div>	
								</div>
								<div class="col-md-12">
									<h6 style="margin-bottom:10px;">Service Description</h6>
									<div class="form-group">
										<textarea name="service_description" id="service_description" class="form-control" rows="10" placeholder="Service Description"></textarea>
									</div>
								</div>
								<div class="col-md-12" style="padding:20px;">
									<h4 style="margin-top:10px;margin-bottom:10px;text-align:center;">Pricing<a href="javascript:void(0);" class="btn btn-info add_pricing pull-right"><i class="fa fa-plus"></i></a></h4>
								</div>
							</div>
							<div class="row" id="pricing_div" style="border:1px solid #ccc;padding:20px;">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4">
											<h6 style="margin-bottom:10px;">Service Name</h6>
											<div class="form-group">
												<input type="text" name="service_name[]" placeholder="Service Name" class="form-control service_name" required>
											</div>	
										</div>
										<div class="col-md-2">
											<h6 style="margin-bottom:10px;">Service Charges</h6>
											<div class="form-group">
												<input type="text" name="service_charges[]"  placeholder="Service Charges" class="form-control service_charges" required>
											</div>	
										</div>
										<div class="col-md-2">
											<h6 style="margin-bottom:10px;">Quantity</h6>
											<div class="form-group">
												<input type="text" name="service_quantity[]"  placeholder="Service Quantity" class="form-control service_quantity" required>
											</div>	
										</div>
										<div class="col-md-3">
											<h6 style="margin-bottom:10px;">Subtotal</h6>
											<div class="form-group">
												<input type="text" name="sub_total[]"  placeholder="Subtotal" class="form-control sub_total" required>
											</div>	
										</div>
										<div class="col-md-1" style="padding:40px;"><a href="javascript:void(0);" class="btn btn-danger remove_pricing"><i class="fa fa-times"></i></a></div>
									</div>
								</div>
							</div>
							<div class="row" style="margin-top:50px;">
								<div class="col-md-3">
									<h6 style="margin-bottom:10px;">Subtotal</h6>
									<div class="form-group">
										<input type="text" name="subtotal" placeholder="Subtotal" class="form-control subtotal" required>
									</div>	
								</div>
								<div class="col-md-3">
									<h6 style="margin-bottom:10px;">Discount</h6>
									<div class="form-group">
										<input type="text" name="discount_percentage" placeholder="Discount in percentage (if any)" class="form-control discount_percentage">
									</div>	
								</div>
								<div class="col-md-3">
									<h6 style="margin-bottom:10px;">Discount Amount</h6>
									<div class="form-group">
										<input type="text" name="discount_amount" placeholder="Discount Amount (if any)" class="form-control discount_amount" required>
									</div>	
								</div>
								<div class="col-md-3">
									<h6 style="margin-bottom:10px;">Total Price</h6>
									<div class="form-group">
										<input type="text" name="total_service_charges"  placeholder="Total Price" class="form-control total_service_charges" required>
									</div>	
								</div>
								<div class="col-md-12" style="padding:20px;">
									<div class="row">
										<div class="col-md-5"></div>
										<div class="col-md-2"><button type="submit" name="create_proposal" class="btn btn-info">Submit</button></div>
										<div class="col-md-5"></div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!--End Clients Section-->
		<!--End pagewrapper-->

		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=sb4h3aa9hheyhbvehdwg0g4uqvuji4e7r8tc2nlzkjqn9xar"></script>
		<script>
		var html=$("#pricing_div").html();
		$(".add_pricing").click(function(){
			$("#pricing_div").append(html);
			removePricing();
		});
		function removePricing()
		{
			$(".remove_pricing").click(function(){
				$(this).parent().parent().parent().remove();
			});
		}
		$(".remove_pricing").click(function(){
			$(this).parent().parent().parent().remove();
		});
		tinymce.init({
			selector:'textarea',
			plugins: [
			'advlist autolink lists link image charmap print preview anchor textcolor',
			'searchreplace visualblocks code fullscreen',
			'image code'
		  ],
		  images_upload_url: 'upload-proposal-images.php',
		  images_upload_handler: function (blobInfo, success, failure) {
				var xhr, formData;
			  
				xhr = new XMLHttpRequest();
				xhr.withCredentials = false;
				xhr.open('POST', 'upload-proposal-images.php');
			  
				xhr.onload = function() {
					var json;
				
					if (xhr.status != 200) {
						failure('HTTP Error: ' + xhr.status);
						return;
					}
				
					json = JSON.parse(xhr.responseText);
				
					if (!json || typeof json.location != 'string') {
						failure('Invalid JSON: ' + xhr.responseText);
						return;
					}
				
					success(json.location);
				};
			  
				formData = new FormData();
				formData.append('file', blobInfo.blob(), blobInfo.filename());
			  
				xhr.send(formData);
			},
			 setup: function (editor) {
					editor.on('keyup', function (e) {
						//$(".content_1").html(this.getContent().replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|(\s+)/ig,''));
						//custom logic  
					});
				}
		 });
		</script>
		<?php
	}
?>
<script src="<?php echo DOMAIN_NAME; ?>js/bootstrap.min.js"></script>