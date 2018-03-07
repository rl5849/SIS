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
            <option>Class</option>
        <?php
        $class_list = file_get_contents("http://127.0.0.1:5002/GetClasses");
        $class_list = json_decode($class_list, true);

        foreach ($class_list["classes"] as $class){ ?>
            <option value="<?php echo $class["class_id"]; ?>"><?php echo $class["name"]; ?></option>
        <?php } ?>
        </select>

        
        <input type="number" name="Capacity" min="1" max="300" placeholder="Capacity">
        <input type="number" name="Room Number" min="1" max="1000" placeholder="Room Number">
        <input type="text" name="Time" placeholder="Time">
        
        <select name="professor">
            <option>Professor</option>
            <?php
            //Professor list
            $prof_list = file_get_contents("http://127.0.0.1:5002/GetProfs");
            $prof_list = json_decode($prof_list, true);

            foreach ($prof_list["profs"] as $prof){ ?>
                <option value="<?php echo $prof; ?>"><?php echo $prof; ?></option>
            <?php } ?>
        </select>

        <input type="submit" class="button expanded rit-orange" value="Create Section">
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
