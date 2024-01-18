<?php
    include_once 'connection.php';
    $rec_id=$_REQUEST['rec_id'];
    $user_id=$_COOKIE['uid'];
    $response=array();
    $recommendation_text=$_REQUEST['recommendation_text'];
    if($recommendation_text!="" && $rec_id!="")
    {
       $query="UPDATE recommendations SET r_text='$recommendation_text',status=1,rec_type='Text' WHERE id='$rec_id'"; 
        if(mysqli_query($conn,$query))
        {
             $response['status']="success";
        }
        else{
             $response['status']="error";
            $response['message']="There is some issue.Please contact Develpoer";
        }
    }
    else
    {
        $response['status']="error";
        $response['message']="Recommendation Text can not be blank";
    }
echo json_encode($response);
?>