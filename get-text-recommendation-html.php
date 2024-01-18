<?php
include_once 'connection.php';
?>
<div class="row">
   <div class="col-md-12" style="margin-bottom:10px;">
       <h6>Write a text recommendation</h6>
   </div>
   <div class="col-md-12">
       <form action="" method="post">
            <div class="row">
                <input type="hidden" name="rec_id" id="rec_id" value="<?php echo $_REQUEST['thread']; ?>">
                <div class="col-md-10">
                    <textarea rows="7" name="written_recommendation" id="written_recommendation" class="form-control" style="resize:none;" required placeholder="Write a text recommendation"></textarea>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                    <div class="form-group">
                        <button type="button" onclick="saveTextRecommendation();" name="save_text_recommendation" class="btn btn-info">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>										
</div>		
<script>
    var base_url="<?php echo base_url; ?>";
    function saveTextRecommendation()
    {
        var rec_id=$("#rec_id").val();
        var written_recommendation=$("#written_recommendation").val();
        if(written_recommendation!="" && rec_id!="")
            {
                $.ajax({
                   url: base_url+'save-text-recommendation',
                    type:"post",
                    data:{rec_id:rec_id,recommendation_text:written_recommendation},
                    success:function(response)
                    {
                        var parsedJson=JSON.parse(response);
                        if(parsedJson.status=="success")
                        {
                            window.location.href=base_url+"dashboard";
                        }
                        else{
                            alert(parsedJson.message);
                        }
                    }
                });
            }
        else{
            alert("Please fill all required fields");
        }
    }
</script>