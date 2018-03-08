<?php
// Start the linkedin session
session_name('linkedin');
session_start();

// Get the code from the linkedin query
$code = $_GET["code"];

// Parse config file for codes
$myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
$readfile = fread($myfile,filesize("LinkedIn/config.ini"));
$arr = explode("\n", $readfile);
$client_id = explode("=", $arr[0])[1];
$client_secret = explode("=", $arr[1])[1];

fclose($myfile);

// Prepare parameters for retrieving the auth token from linkedin
$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => 'https://vm344p.se.rit.edu/SIS/auth.php'
);

/* Unused headers -- idk how to implement with these
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded",
        'method'  => "POST"
    )
);
$context  = stream_context_create($options);
*/

// Make a get request for the auth token
$url = "https://www.linkedin.com/oauth/v2/accessToken?".http_build_query($params);
$access_token_request = file_get_contents($url, true);
$access_token = json_decode($access_token_request);

// Add auth token to the session
$_SESSION['access_token'] = $access_token->access_token; // guard this!
$_SESSION['expires_in']   = $access_token->expires_in; // relative time (in seconds)
$_SESSION['expires_at']   = time() + $_SESSION['expires_in']; // absolute time

// Prepare parameters to query for user info from linkedin
$params = array(
    'oauth2_access_token' => $_SESSION['access_token'],
    'format' => 'json'
);

$linkedin_user_info = file_get_contents("https://api.linkedin.com/v1/people/~:(first-name,last-name,id,picture-url)?".http_build_query($params));
$linkedin_user_info = json_decode($linkedin_user_info);


$fName = $linkedin_user_info->firstName;
$lName = $linkedin_user_info->lastName;
$id = $linkedin_user_info->id;
$profilePic = $linkedin_user_info->pictureUrl;

// TODO Add user to system

echo $fName."<br/>";
echo $lName."<br/>";
echo $id."<br/>";
echo "<img src='".$profilePic."'>";

// Reroute user to account page TODO get working
//$accountPage = "account.php";
//header('Location: '.$accountPage);
?>