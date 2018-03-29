<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Login</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>

<!-- Load Callouts -->
<div id="callouts-placeholder"></div>
<!-- End Callouts -->


<div class="background-image">
    <div class="grid-container">
        <div class="grid-x grid-padding-x" style="padding-top:15%;">

            <div class="large-4 medium-3 small-2 cell">
                <!-- Intentionally Blank -->
            </div>

            <div class="large-4 medium-6 small-8 cell translucent-background">

                <!--<h2> SIS++ </h2>-->

                <img class="login-page-logo" src="images/LOGO.png" alt="SIS"/>

                <?php
                $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
                $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
                $arr = explode("\n", $readfile);
                $client_id = explode("=", $arr[0])[1];
                $client_secret = explode("=", $arr[1])[1];

                fclose($myfile);
                ?>

                <!-- LinkedIn Button -->
                <?php

                $args = array(
                    'response_type' => 'code',
                    'client_id' => $client_id,
                    'redirect_uri' => 'https://vm344p.se.rit.edu/SIS/auth.php',
                    'state' => '12vk41b254kvn1l2'
                );

                echo "<a href='https://www.linkedin.com/oauth/v2/authorization?".http_build_query($args)."'>";
                echo    "<img id='linkedin-login-button' src='images/Sign-In-Large---Default.png' alt='Sign in with LinkedIn'>";
                echo "</a>";
                ?>

                <!-- Load the images of hover and active so that they aren't loaded first when the user interacts with the button -->
                <img style="display:none;" src="images/Sign-In-Large---Hover.png">
                <img style="display:none;" src="images/Sign-In-Large---Active.png">

                <form method="post" action="account.php" style="padding-top:5%">
                    <p>
                        <input name="username" type="text" class="" value="Username"/>
                        <input name="password" type="password" class="" value="Password"/>
                        <input name="manual" type="hidden" />
                        <input name="login" type="submit" class="button expanded rit-brown" value=""/>
                    </p>
                </form>


                <!-- <p class="text-center"><a href="#">Forgot your password?</a></p> -->
                <!--
                <a href="https://www.linkedin.com/start/join?trk=uno-reg-guest-home-join"><p style="padding-top:15%;"><input type="submit" class="button expanded rit-orange" value="Register through LinkedIn"><a href="https://www.linkedin.com/start/join?trk=uno-reg-guest-home-join"></a></p> </input></p></a>
                -->
            </div>

        </div>
    </div>
</div>



<script src="bower_components/jquery/dist/jquery.js"></script>
<script src="bower_components/what-input/dist/what-input.js"></script>
<script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
<script src="js/app.js"></script>

<script>
    makeCallouts();
</script>
</body>
</html>
