<?php
	include_once 'content-detection/vendor/autoload.php';
	use \Sightengine\SightengineClient;	
	$response=array();
	function isUploadable($image)
	{
		$client = new SightengineClient('1696532987','7DESGvo3N62R3nPXTqv9');
		$output = $client->check(['celebrities','nudity','wad','offensive','text-content','face-attributes'])->set_url($image);
		$thresholds=array();
		$thresholds['weapon']=10;
		$thresholds['alcohol']=10;
		$thresholds['drugs']=5;
		$thresholds['safe_nudity']=75;
		$thresholds['partial_nudity']=35;
		$thresholds['raw_nudity']=5;
		$thresholds['offensive']=5;
		$thresholds['celebrity']=50;
		print_r($output);
		$status=$output->status;
		$weapen_detected=false;
		$celebrity_detceted=false;
		$alcohol_detected=false;
		$partial_nudity_detected=false;
		$raw_nudity_detected=false;
		$offensive_detected=false;
		$safe_nudity_detected=true;
		$drugs_detected=false;
		if($status=="success")
		{
			if(isset($output->faces) && count($output->faces)>0)
			{
				//print_r($output->faces);
				foreach($output->faces as $face)
				{
					//echo "Hello";
					if(isset($face->celebrity) && count($face->celebrity)>0)
					{
						//echo "Hi";
						foreach($face->celebrity as $celebrity)
						{
							//echo "Hey";
							if(isset($celebrity->prob) && $celebrity->prob!="")
							{
								//echo "Hmm";
								$probability=(int)($celebrity->prob*100);
								//echo $probability;
								if($probability>=$thresholds['celebrity'])
								{
									
									//echo $celebrity->name;
									$celebrity_detceted=true;
								}
							}
						}
					}
				}
			}
			if(isset($output->weapon) && $output->weapon!="")
			{
				$weapon=(int)($output->weapon*100);
				if($weapon>=$thresholds['weapon'] && (!$celebrity_detceted))
				{
					$weapen_detected=true;
				}
			}
			if(isset($output->alcohol) && $output->alcohol!="")
			{
				$alcohol=(int)($output->alcohol*100);
				if($alcohol>=$thresholds['alcohol'] && (!$celebrity_detceted))
				{
					$alcohol_detected=true;
				}
			}
			if(isset($output->drugs) && $output->drugs!="")
			{
				$drugs=(int)($output->drugs*100);
				if($drugs>=$thresholds['drugs'] && (!$celebrity_detceted))
				{
					$drugs_detected=true;
				}
			}
			if(isset($output->nudity->partial) && $output->nudity->partial!="")
			{
				$partial_nudity=(int)($output->nudity->partial*100);
				if($partial_nudity>=$thresholds['partial_nudity'] && (!$celebrity_detceted))
				{
					$partial_nudity_detected=true;
				}
			}
			if(isset($output->nudity->raw) && $output->nudity->raw!="")
			{
				$raw_nudity=(int)($output->nudity->raw*100);
				if($raw_nudity>=$thresholds['raw_nudity'])
				{
					$raw_nudity_detected=true;
				}
			}
			if(isset($output->offensive->prob) && $output->offensive->prob!="")
			{
				$offensive=(int)($output->offensive->prob*100);
				if($offensive>=$thresholds['offensive'] && (!$celebrity_detceted))
				{
					$offensive_detected=true;
				}
			}
			if(isset($output->nudity->safe) && $output->nudity->safe!="")
			{
				$safe_nudity=(int)($output->nudity->safe*100);
				if($safe_nudity<=$thresholds['safe_nudity'])
				{
					$safe_nudity_detected=false;
				}
			}
			if($safe_nudity_detected && (!$weapen_detected) && (!$alcohol_detected) && (!$drugs_detected) && (!$partial_nudity_detected) && (!$raw_nudity_detected) && (!$offensive_detected))
			{
				return true;
			}
			else{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	
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
	
	
	$image="";
	if(isset($_POST['image']) || isset($_POST['text']))
	{
		$image=trim($_POST['image']);
		$text=trim($_POST['text']);
		if($image!="")
		{
			if(isUploadable($image))
			{
				$response['status']="success";
				$response['target']="media";
			}
			else
			{
				$response['status']="error";
				$response['message']="Content against RopeYou guidelines has been detected.";
			}
		}
		if($text!="")
		{
			if(isUploadableText($text))
			{
				if(isset($response['status']) && $response['status']!="error")
				{
					$response['status']="success";
				}
				else if(!isset($response['status']))
				{
					$response['status']="success";
				}
			}
			else
			{
				$response['status']="error";
				$response['target']="text";
				$response['message']="Content against RopeYou guidelines has been detected.";
			}
		}
		if($image=="" && $text=="")
		{
			$response['status']="error";
			$response['message']="Content Not Provided.";
		}
	}
	else
	{
		$response['status']="error";
		$response['message']="Parameter(s) Missing.";
	}
	echo json_encode($response);
?>