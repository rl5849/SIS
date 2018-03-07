<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Course List</title>
    <link rel="stylesheet" href="css/app.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<!-- Load Nav Bar and Callouts -->
<div id="nav-placeholder"></div>
<div id="callouts-placeholder"></div>
<!-- End load Nave Bar and Callouts -->
<div>
    <h2>Add new class section</h2>
    <form method ="post" >
        <select name="class" id="class">

        <?php
        $class_list = file_get_contents("http://127.0.0.1:5002/GetClasses");
        $class_list = json_decode($class_list, true);

        foreach ($class_list["classes"] as $class){
            ?>
            <option value="<?php echo $class["class_id"]; ?>"><?php echo $class["name"]; ?></option>
            <?php
        }
        ?>
        </select>

        <input type="" name="enroll" value="<?php ?>">
        <input type="hidden" name="class_id" value="<?php ?>">
        <input type="hidden" name="user_id" value="<?php ?>">
        <input type="submit" class="button expanded rit-orange" value="<?php ?>">
    </form>
</div>









<script src="bower_components/jquery/dist/jquery.js"></script>
<script src="bower_components/what-input/dist/what-input.js"></script>
<script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
<script src="js/app.js"></script>

<script>
    makeNav();
    makeCallouts();
</script>
</body>
</html>
