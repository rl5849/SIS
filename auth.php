<?php
// PHP for using the LinkedIn API

// Get the Access Token from LinkedIn
$code = $_GET["code"];

// Parse config file for codes
$myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
$readfile = fread($myfile,filesize("LinkedIn/config.ini"));
$arr = explode("\n", $readfile);
$client_id = explode("=", $arr[0])[1];
$client_secret = explode("=", $arr[0])[1];

fclose($myfile);

$options = array(
    'http' => array(
        'header'  => "content-type: application/x-www-form-urlencoded",
        'method'  => 'POST'
    )
);

$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => 'https://vm344p.se.rit.edu/SIS/account.php'
);

$context  = stream_context_create($options);
$url = "https://www.linkedin.com/oauth/v2/accessToken?" . http_build_query($params);
echo "url:".$url;
$access_token_request = file_get_contents($url, false, $context);
$access_token = json_decode($access_token_request)["access_token"];

echo "Message from server: ".$access_token["error_description"];
echo "First 3 of token: ".substr($access_token, 0, 3);

$linkedin_user_info = file_get_contents("https://api.linkedin.com/v1/people/~?format=json");
$linkedin_user_info = json_decode($linkedin_user_info, true);
echo "test".$linkedin_user_info;
?>