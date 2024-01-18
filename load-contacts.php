<?php
	include_once 'connection.php';
?>
<div class="p-3 user_section_contacts">
	<div class="row">
        <div class="col-md-12" style="margin-bottom:20px;">
            <h6>Manage Contacts <a href="javascript:void(0);" data-toggle="modal" data-target="#add_contact" class="btn btn-info pull-right" style="float:right !important;">Add New Contact</a></h6>
        </div>
	<?php
		$user_id=$_COOKIE['uid'];
		$response['status']="success";
		$contacts_query="SELECT * FROM users_contact WHERE user_id='$user_id' AND status=1 ORDER BY id ASC";
		$contacts_result=mysqli_query($conn,$contacts_query);
		$data='<div class="table-responsive" style="min-width:100%;">
				<table id="example" class="table table-striped table-bordered nowrap" style="min-width:100%">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Name</th>
							<th>Contact</th>
							<th>Type</th>
							<th>Location</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>';
		if(mysqli_num_rows($contacts_result)>0)
		{
			$i=1;
			while($contacts_row=mysqli_fetch_array($contacts_result))
			{
				$data.='<tr>
					<td>'.$i++.'</td>
					<td>'.ucwords(strtolower($contacts_row['contact_name'])).'</td>
					<td>'.$contacts_row['contact'].'</td>
					<td>'.ucfirst($contacts_row['contact_type']).'</td>
					<td>'.ucfirst($contacts_row['location']).'</td>
                    <td></td>
				</tr>';
			}
		}
		$data.='					</tbody>
					<tfoot>
						<tr>
							<th>Sr No</th>
							<th>Name</th>
							<th>Contact</th>
							<th>Type</th>
							<th>Location</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
			</div><script>
	$("#example").DataTable({"bSort" : false,dom: "Bfrtip",
        buttons: [
            "csv", "excel"
        ]});
</script>';
		echo $data;

	?>
	</div>
</div>
<script>
	loadImageSlider("user_section_contacts");
</script>
													