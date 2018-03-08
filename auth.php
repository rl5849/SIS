<?php
// PHP for using the LinkedIn API
echo "10<br/>";

session_name('linkedin');
session_start();

$code = $_GET["code"];

// Parse config file for codes
$myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
$readfile = fread($myfile,filesize("LinkedIn/config.ini"));
$arr = explode("\n", $readfile);
$client_id = explode("=", $arr[0])[1];
$client_secret = explode("=", $arr[1])[1];

fclose($myfile);

$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => 'https://vm344p.se.rit.edu/SIS/auth.php'
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded",
        'method'  => "POST"
    )
);

$context  = stream_context_create($options);
$url = "https://www.linkedin.com/oauth/v2/accessToken?".http_build_query($params);

echo "url:".$url."<br/>";
$access_token_request = file_get_contents($url, true);
$access_token = json_decode($access_token_request);

$_SESSION['access_token'] = $access_token->access_token; // guard this!
$_SESSION['expires_in']   = $access_token->expires_in; // relative time (in seconds)
$_SESSION['expires_at']   = time() + $_SESSION['expires_in']; // absolute time

echo $_SESSION['access_token'];

//$linkedin_user_info = file_get_contents("https://api.linkedin.com/v1/people/~?format=json", false, );
//$linkedin_user_info = json_decode($linkedin_user_info, true);
//echo "\ntest".$linkedin_user_info;
?>