<?php
// PHP for using the LinkedIn API
echo "3";
// Get the Access Token from LinkedIn
$code = $_GET["code"];

// Parse config file for codes
$myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
$readfile = fread($myfile,filesize("LinkedIn/config.ini"));
$arr = explode("\n", $readfile);
$client_id = explode("=", $arr[0])[1];
$client_secret = explode("=", $arr[0])[1];

fclose($myfile);

$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => 'https://vm344p.se.rit.edu/SIS/account.php',
    'state' => 'nk537n234vn12'
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params)
    )
);

$context  = stream_context_create($options);
$url = "https://www.linkedin.com/oauth/v2/accessToken";
echo "url:".$url."\n";
$access_token_request = file_get_contents($url, false, $context);
$access_token = json_decode($access_token_request);

var_dump($access_token_request);
var_dump($access_token);

$garbage = file_get_contents($url);

//$linkedin_user_info = file_get_contents("https://api.linkedin.com/v1/people/~?format=json");
//$linkedin_user_info = json_decode($linkedin_user_info, true);
//echo "\ntest".$linkedin_user_info;
?>