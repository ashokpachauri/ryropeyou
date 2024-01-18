<!DOCTYPE html>
<html lang="EN">
<head>
	<title>Import Google Contacts</title>

	<meta charset="UTF-8">

	<!-- All other meta tag here -->
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" >	

	<!-- CSS styles -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">	


</head>

<body>

<?php

session_start();

//include google api library
require_once '../google-import/google-api-php-client/src/Google/autoload.php';// or wherever autoload.php is located


//Create a Google application in Google Developers Console for obtaining your Client id and Client secret.
// https://www.design19.org/blog/import-google-contacts-with-php-or-javascript-using-google-contacts-api-and-oauth-2-0/

// Your redirect uri should be on a online server. Localhost will not work.

//Important : Your redirect uri should be added in Google Developers Console , in your Authorized redirect URIs

//Declare your Google Client ID, Google Client secret and Google redirect uri in  php variables
$google_client_id = '546516864361-dq1t5s9d83ig9m6mmuo9h5pqqgtkgtv5.apps.googleusercontent.com';
$google_client_secret = 's7dsRJVL4U991AcTslWW00sD';
$google_redirect_uri = 'https://ropeyou.com/rope/import-contacts.php';



//setup new google client
$client = new Google_Client();
$client -> setApplicationName('Ropeyou');
$client -> setClientid($google_client_id);
$client -> setClientSecret($google_client_secret);
$client -> setRedirectUri($google_redirect_uri);
$client -> setAccessType('online');
$client -> setScopes('https://www.google.com/m8/feeds');
$googleImportUrl = $client -> createAuthUrl();


//curl function
function curl($url, $post = "") {
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	curl_setopt($curl, CURLOPT_URL, $url);
	//The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	//The number of seconds to wait while trying to connect.
	if ($post != "") {
		curl_setopt($curl, CURLOPT_POST, 5);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
	//The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	//To follow any "Location: " header that the server sends as part of the HTTP header.
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	//The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	//To stop cURL from verifying the peer's certificate.
	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
}


//google response with contact. We set a session and redirect back
if (isset($_GET['code'])) {
	$auth_code = $_GET["code"];
	$_SESSION['google_code'] = $auth_code;
}


/*
    Check if we have session with our token code and retrieve all contacts, by sending an authorized GET request to the following URL : https://www.google.com/m8/feeds/contacts/default/full
    Upon success, the server responds with a HTTP 200 OK status code and the requested contacts feed. For more informations about parameters check Google API contacts documentation
*/
if(isset($_SESSION['google_code'])) {
	$auth_code = $_SESSION['google_code'];
	$max_results = 200;
    $fields=array(
        'code'=>  urlencode($auth_code),
        'client_id'=>  urlencode($google_client_id),
        'client_secret'=>  urlencode($google_client_secret),
        'redirect_uri'=>  urlencode($google_redirect_uri),
        'grant_type'=>  urlencode('authorization_code')
    );
    $post = '';
    foreach($fields as $key=>$value)
    {
        $post .= $key.'='.$value.'&';
    }	
    $post = rtrim($post,'&');
	
	
    $result = curl('https://accounts.google.com/o/oauth2/token',$post);
    $response =  json_decode($result);
    $accesstoken = $response->access_token;
    $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
    $xmlresponse =  curl($url);
    $contacts = json_decode($xmlresponse,true);
	
	//deg ($contacts['feed']['entry']);
	
	$return = array();
	if (!empty($contacts['feed']['entry'])) {
		foreach($contacts['feed']['entry'] as $contact) {
			
			//$contactidlink = explode('/',$contact['id']['$t']);
			//$contactId = end($contactidlink);
			
			//retrieve user photo
			if (isset($contact['link'][0]['href'])) {
				
				$url =   $contact['link'][0]['href'];
				
				$url = $url . '&access_token=' . urlencode($accesstoken);
				
				$curl = curl_init($url);

		        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
				curl_setopt($curl, CURLOPT_VERBOSE, true);
		
		        $image = curl_exec($curl);
		        curl_close($curl);
				
				
				//echo '<img src="data:image/jpeg;base64,'.base64_encode( $image ).'" />';
				
				    
			}
			
			//retrieve Name + email and store into array
			$return[] = array (
				'name'=> $contact['title']['$t'],
				'email' => $contact['gd$email'][0]['address'],
				'image' => $image
			);
		}				
	}
	
	$google_contacts = $return;
						
	unset($_SESSION['google_code']);
	



	//Now that we have the google contacts stored in an array, display all in a table
	if(!empty($google_contacts)) {
        include_once 'connection.php';
		foreach ($google_contacts as $contact) {
            if($contact['email']!="")
            {
                
                $contact_name=$contact['name'];
                $contact_type="email";
                $contact=$contact['email'];
                $location="";
                $status=1;
                $user_id=$_COOKIE['uid'];
                $google_image="uploads/".$user_id."-google-import-".strtolower(str_replace(" ","-",$contact))."-".time().".jpg";
                if(!empty($contact['image']) and $contact['image']!='Photo not found')
                {
                    if (!outputFileToLocation("data:image/jpeg;base64,".$contact['image'],$google_image))
                    {
                       $google_image=""; 
                    }
                }
                else{
                  $google_image="";   
                }
                $imported_from="Google";
                $query="SELECT * FROM users_contact WHERE user_id='$user_id' AND contact='$contact'";
                $result=mysqli_query($conn,$query);
                if(mysqli_num_rows($result)>0)
                {
                    continue;
                }
                else
                {
                    $insert_query="INSERT INTO users_contact SET user_id='$user_id',contact_name='$contact_name',contact_type='$contact_type',contact='$contact',location='$location',status='$status',imported_from='$imported_from',google_image='$google_image'";
                    mysqli_query($conn,$insert_query);
                }
            }
			
		}
	}
						
}
					
?>
<div class="container">
	<div class="row">
		<br><br><br>
        <div class="col-lg-5"></div>
        <div class="col-lg-2">

			<a class="btn btn-primary" href="<?php echo $googleImportUrl; ?>" role="button">Import google contacts</a>

		</div>
        <div class="col-lg-5"></div>
	</div>
</div>

<!-- Google CDN's jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>


</body>
</html>