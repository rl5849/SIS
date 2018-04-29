<?php
    // Get post vars if they are there
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password_confirm"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_confirm = $_POST["password_confirm"];
    }

// If user hasn't entered anything
if (!isset($_POST["username"]) && !isset($_POST["password"])) {
    // Do nothing
}
// If user has entered a password, but not a username
else if (!isset($_POST["username"]) && isset($_POST["password"])) {
    echo "<script>window.onload = function() {showMessage('failure', 'Please enter a username.');};</script>";
}
// If user has entered a username, but not a password
else if (!isset($_POST["password"]) && isset($_POST["username"])) {
    echo "<script>window.onload = function() {showMessage('failure', 'Please enter a password.');};</script>";
}
// User has entered a confirmation password that does not match the first password
else if ($password != $password_confirm) {
    echo "<script>window.onload = function() {showMessage('failure', 'Passwords do not match.');};</script>";
}
// User has submitted an invalid password. This is also checked for in the HTML but that can be tampered with
else if (isset($_POST["password"]) && !preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{5,}$/", $password)) {
    echo "<script>window.onload = function() {showMessage('failure', 'Passwords is not of specified format.');};</script>";
}
// User account submission form is valid
else {
    // Check and see if there exists an account with that username
    $results = file_get_contents("http://127.0.0.1:5002/UserExists?username=".$username);
    $user_exists = json_decode($results, true)["exists"];
    if ($user_exists == "True") {
        echo "<script>window.onload = function() {showMessage('failure', 'A user account with that username already exists.');};</script>";
    } else {
        $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
        $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
        $arr = explode("\n", $readfile);
        $captcha = explode("=", $arr[3])[1];
        rewind($myfile);

       //check captcha
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => $captcha, 'response' => $_POST['g-recaptcha-response']);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);

        if ($result['success'] != true) {
            echo "You're a bot!";
            return;
        }


        // Get salt from config file
        $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
        $arr = explode("\n", $readfile);
        $salt = explode("=", $arr[2])[1];

        fclose($myfile);

        $hashed_password = hash("sha256", $password);
        $hashed_password = $hashed_password.$salt;
        $hashed_password = hash("sha256", $hashed_password);

        $results = file_get_contents("http://127.0.0.1:5002/UserExists?username=".$username);
        $user_exists = json_decode($results, true)["user_exists"];
        // Check if user already exists
        if ($user_exists == "True") {
            echo "<script>window.onload = function() {showMessage('failure', 'A user account with that name already exists');};</script>";
        }
        // Use account can be created
        else {

            $results = file_get_contents("http://127.0.0.1:5002/CreateLogin?username=".$username."&password=".$hashed_password);
            $results = json_decode($results);

            if ($results->message == "SUCCESS") {
                session_start();
                $_SESSION["user_id"] = $results->user_id;
                $_SESSION['start'] = time(); // Taking now logged in time.
                // Ending a session in 30 minutes from the starting time.
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
                header("Location: account.php?editprofile=true&fromregister=true");
            }
        }
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Login</title>
    <link rel="stylesheet" href="css/app.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118378709-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-118378709-1');
    </script>

</head>
<body>

<div class="background-image">
    <div class="grid-container">
        <div class="grid-x grid-padding-x" style="padding-top:15%;">

            <div class="large-4 medium-3 small-2 cell">
                <!-- Intentionally Blank -->
            </div>


            <div class="small-12 medium-6 large-4 columns">
                <div>
                    <form class="callout translucent-background" method="post">
                        <h4>Register</h4>
                        <?php
                            // Load callouts
                            include 'callouts.html';
                        ?>
                        <div class="floated-label-wrapper">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" placeholder="Username" required value="<?php echo $username;?>">
                        </div>

                        Passwords must:
                        <ul>
                            <li>Have 1 uppercase letter</li>
                            <li>Have 1 number</li>
                            <li>Be at least 5 characters long</li>
                        </ul>
                        <div class="floated-label-wrapper">
                            <label for="password">Password</label>
                            <input pattern="^(?=.*[A-Z])(?=.*[0-9]).{5,}$" type="password" name="password" id="password" placeholder="Enter Password" required>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="password_confirm">Password Confirmation</label>
                            <input pattern="^(?=.*[A-Z])(?=.*[0-9]).{5,}$" type="password" name="password_confirm" id="password_confirm" placeholder="Confirm Password" required>
                        </div>

                        <div class="g-recaptcha" data-sitekey="6LfYx1UUAAAAADUfxmugrKx1nIZHw8Cx8EQmcNY8"></div>

                        <input type="submit" class="button expanded rit-orange" value="Create Account">
                    </form>
                    <!-- End new form -->
                </div>
            </div>

        </div>
    </div>
</div>



<script src="bower_components/jquery/dist/jquery.js"></script>
<script src="bower_components/what-input/dist/what-input.js"></script>
<script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
<script src="bower_components/motion-ui/dist/motion-ui.js"></script>
<script src="js/app.js"></script>


<script>
    makeCallouts();
</script>

<?php
// If user hasn't entered anything
if (!isset($_POST["username"]) && !isset($_POST["password"])) {
    // Do nothing
}
// If user has entered a password, but not a username
else if (!isset($_POST["username"]) && isset($_POST["password"])) {
    echo "<script>window.onload = function() {showMessage('failure', 'Please enter a username.');};</script>";
}
// If user has entered a username, but not a password
else if (!isset($_POST["password"]) && isset($_POST["username"])) {
    echo "<script>window.onload = function() {showMessage('failure', 'Please enter a password.');};</script>";
}
// User has entered a confirmation password that does not match the first password
else if ($password != $password_confirm) {
    echo "<script>window.onload = function() {showMessage('failure', 'Passwords do not match.');};</script>";
}
// User has submitted an invalid password. This is also checked for in the HTML but that can be tampered with
else if (isset($_POST["password"]) && !preg_match("/^(?=.*[A-Z])(?=.*[0-9]).{5,}$/", $password)) {
    echo "<script>window.onload = function() {showMessage('failure', 'Passwords is not of specified format.');};</script>";
}
// User account submission is valid
else {
    // Check and see if the user already has an account
    $results = file_get_contents("http://127.0.0.1:5002/UserExists?username=".$username);
    $user_exists = json_decode($results, true)["user_exists"];
    if ($user_exists == "True") {
        echo "<script>window.onload = function() {showMessage('failure', 'A user account with that username already exists. <a href=\"#\">Forgot Password?</a>');};</script>";
    } else {
        // Get salt from config file

        $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
        $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
        $arr = explode("\n", $readfile);
        $salt = explode("=", $arr[2])[1];

        fclose($myfile);

        $hashed_password = hash("sha256", $password);
        $hashed_password = $hashed_password.$salt;
        $hashed_password = hash("sha256", $hashed_password);

        $results = file_get_contents("http://127.0.0.1:5002/UserExists?username=".$username);
        $user_exists = json_decode($results, true)["user_exists"];

        // Check if user already exists
        if ($user_exists == "True") {
            echo "<script>window.onload = function() {showMessage('failure', 'A user account with that name already exists');};</script>";
        }
        // Use account can be created
        else {
            $results = file_get_contents("http://127.0.0.1:5002/CreateLogin?username=".$username."&password=".$hashed_password);
            $results = json_decode($results);
            if ($results == "SUCCESS") {
                echo "<script>window.onload = function() {showMessage('success', 'Your account has been created.');};</script>";
            }
        }
    }
}
?>
</body>
</html>

