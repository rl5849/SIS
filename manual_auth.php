<?php
$username = $_POST["username"];
$password = $_POST["password"];


// Hash password
// Get salt from config file
$myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
$readfile = fread($myfile,filesize("LinkedIn/config.ini"));
$arr = explode("\n", $readfile);
$salt = explode("=", $arr[2])[1];

fclose($myfile);

$hashed_password = hash("sha256", $password);
$hashed_password = $hashed_password.$salt;
$hashed_password = hash("sha256", $hashed_password);

$request = file_get_contents("http://127.0.0.1:5002/GetUserIDFromLogin?username=".$username."&password=".$hashed_password);
$results = json_decode($request);

if( $results->user_id == null) {
    // Redirect back to login page
    echo "INVALID_LOGIN";
} else {
    session_start();

    $_SESSION['start'] = time(); // Taking now logged in time.
    // Ending a session in 30 minutes from the starting time.
    $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

    // Set admin status if needed
    $is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$results->user_id);
    $is_admin = json_decode($is_admin, true);
    $_SESSION["is_admin"] = $is_admin["is_admin"];

    $_SESSION["user_id"] = $results->user_id;
    echo $results->user_id;
}

?>