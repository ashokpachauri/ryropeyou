<?php
	include_once 'connection.php';
?>
<div class="row">
	<div class="col-8 col-lg-6">
		<h6>Language*</h6>
		<input type="text" name="language_title" id="language_title" value="" class="form-control required"/>
	</div>
	<div class="col-2 col-lg-2">
	<h6>&nbsp;</h6>
		<button type="button" name="add_language" id="add_language" onclick="addLanguage();" class="btn btn-primary">Save</button>
	</div>
	<div class="col-2 col-lg-2">
	<h6>&nbsp;</h6>
		<button href="javascript:void(0);" onclick="removeLangForm();" class="remove_language_button btn btn-danger">X</button>
	</div>
	<script>
		var base_url=localStorage.getItem("base_url");
		function removeLangForm()
		{
			$(".field_wrapper_2").html('');
		}
		function addLanguage(){
			var language_title=$("#language_title").val().trim();
			if(language_title!=="")
			{
				$.ajax({
					url:base_url+'addlanguages',
					type:'post',
					data:{language_title:language_title},
					success:function(data)
					{
						var parsedJson=JSON.parse(data);
						if(parsedJson.status=="success")
						{
							$(".value_wrapper_2").html(parsedJson.htmlData);
							$(".field_wrapper_2").html("");
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