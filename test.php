<?php
	$auth_key="347898AXXW3bQR5fbe1320P1";
	$sender_id="RYMSGS";
	$template_id="5fbe1a3d12254379e112904c";
	$mobile_number_with_country_code="919999324567";
	$otp=4986;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=".$auth_key."&template_id=".$template_id."&mobile=".$mobile_number_with_country_code."&country=0&otp=".$otp,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_SSL_VERIFYHOST => 0,
	  CURLOPT_SSL_VERIFYPEER => 0,
	  CURLOPT_HTTPHEADER => array(
		"content-type: application/json"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	  var_dump($response);
	}
?>