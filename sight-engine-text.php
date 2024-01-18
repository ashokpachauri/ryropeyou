<?php
	include_once 'content-detection/vendor/autoload.php';
	use \Sightengine\SightengineClient;	
	$response=array();
	function isUploadableText($text)
	{
		$client = new SightengineClient('1696532987','7DESGvo3N62R3nPXTqv9');
		$params = array(
		  'text' => $text,
		  'lang' => 'en',
		  'mode' => 'standard',
		  'api_user' => '1696532987',
		  'api_secret' => '7DESGvo3N62R3nPXTqv9',
		);

		// this example uses cURL
		$ch = curl_init('https://api.sightengine.com/1.0/text/check.json');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$response = curl_exec($ch);
		curl_close($ch);
		$profenity_detected=false;
		$output = json_decode($response, true);
		if($output['status']=="success")
		{
			if(isset($output['profanity']['matches']) && count($output['profanity']['matches'])>0)
			{
				$matches=$output['profanity']['matches'];
				if(count($matches)>0)
				{
					$profenity_detected=true;
				}
			}
			
		}
		return (!$profenity_detected);
	}
	$text="";
	if(isset($_REQUEST['text']) && $_REQUEST['text']!="")
	{
		$text=$_REQUEST['text'];
		if(isUploadableText($text))
		{
			$response['status']="success";
		}
		else
		{
			$response['status']="error";
			$response['message']="Content against RopeYou guidelines has been detected.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Content Not Provided.";
	}
	echo json_encode($response);
?>