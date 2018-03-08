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


<div class="grid-container">
    <div class="grid-x grid-padding-x" style="padding-top:10%;">

        <div class="large-4 medium-3 small-2 cell">
            <!-- Intentionally Blank -->
        </div>

        <div class="large-4 medium-6 small-8 cell">

            <h2> RIT SIS++ </h2>


            <?php
                session_start();
                unset($_SESSION['user_id']);
                session_destroy();
                if (!isset($_SESSION['user_id'])){
                    echo "<h4 class=\"text-center\">Successfully Logged out</h4>";
                }
            ?>
            <a href="login.php"><p style="padding-top:15%;"><input type="submit" class="button expanded rit-orange" value="Login">

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
