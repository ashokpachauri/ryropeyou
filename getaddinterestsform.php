<?php
	include_once 'connection.php';
?>
<div class="row" style="margin-bottom:50px !important;">
	<div class="col-8 col-lg-6">
		<h6>Title*</h6>
		<input type="text" name="interest_title" id="interest_title" value="" class="form-control required"/>
	</div>
	<div class="col-2 col-lg-2">
		<h6>&nbsp;</h6>
		<button type="button" name="add_interest" id="add_interest" onclick="addInterest();" class="btn btn-primary">Save</button>
	</div>
	<div class="col-2 col-lg-2">
		<h6>&nbsp;</h6>
		<button href="javascript:void(0);" onclick="removeIntForm();" class="remove_interest_button btn btn-danger">X</button>
	</div>
	<script>
		var base_url=localStorage.getItem("base_url");
		function removeIntForm(){
			$('.field_wrapper_1').html('');
		}
		function addInterest(){
			var interest_title=$("#interest_title").val().trim();
			if(interest_title!=="")
			{
				$.ajax({
					url:base_url+'addinterests',
					type:'post',
					data:{interest_title:interest_title},
					success:function(data)
					{
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							$(".value_wrapper_1").html(parsedJson.htmlData);
							$(".field_wrapper_1").html("");
						}
					}
				});
			}
			else
			{
				alert('please fill required fields');
			}
		}
	</script>
</div>