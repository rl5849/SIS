<?php
    // Get post vars if they are there
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Login</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/foundation-icons.css">
</head>
<body>

<div class="background-image">
    <div class="grid-container">
        <div class="grid-x grid-padding-x" style="padding-top:15%;">

            <div class="large-4 medium-3 small-2 cell">
                <!-- Intentionally Blank -->
            </div>


            <div class="small-12 medium-6 large-4 columns"  style="display:none;">
                <div>
                    <form class="callout translucent-background" method="post">
                        <h4>Register</h4>
                        <!-- Load Callouts -->
                        <div id="callouts-placeholder"></div>
                        <!-- End Callouts -->
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
                        <input type="submit" class="button expanded rit-orange" value="Create Account">
                    </form>
                    <!-- End new form -->
                </div>
            </div>

            <div class="small-12 medium-6 large-4 columns">
                <div>
                    <form class="callout translucent-background" method="post" action="account.php">
                        <h4>Account Information</h4>
                        <!-- Load Callouts -->
                        <div id="callouts-placeholder"></div>
                        <!-- End Callouts -->
                        <div class="floated-label-wrapper">
                            <label for="fName">First Name</label>
                            <input type="text" name="fName" id="fName" placeholder="First Name" required>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="lName">Last Name</label>
                            <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                        </div>
                        <div class="floated-label-wrapper">
                            <label class="show" for="user-type">I am a...</label>
                            <select name="user-type" id="user-type">
                                <option value="student">Student</option>
                                <option value="professor">Professor</option>
                            </select>
                        </div>
                        <div class="floated-label-wrapper">
                            <label class="show" for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="self-identify">Self-Identify</option>
                            </select>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="dob">Date of Birth</label>
                            <input type="text" name="dob" id="dob" placeholder="Date of Birth">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="grad-year">Graduation Year</label>
                            <input type="text" name="grad-year" id="grad-year" placeholder="Graduation Year">
                        </div>
                        <input type="hidden" name="action" value="update-profile">
                        <input type="submit" class="button expanded rit-orange" value="Continue to Profile">
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

