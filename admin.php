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
<body>
    <!-- Load Nav Bar and Callouts -->
    <div id="nav-placeholder"></div>
    <div id="callouts-placeholder"></div>
    <!-- End load Nave Bar and Callouts -->
    <div class="grid-container">

        <div class="grid-x grid-padding-x" style="padding-top:2%;">

            <div class="small-12 medium-3 large-3 columns">
                <div>
                    <!--
                    <h4>Add new class section</h4>
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
                    -->
                    <!-- Start new form -->
                    <form class="callout text-center" method="post">
                        <h4>Add a New Class Section</h4>
                        <div class="floated-label-wrapper">
                            <label for="class_new_class">Class</label>
                            <select name="class" id="class_new_class">

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
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="capacity_new_class">Capacity</label>
                            <input type="number" name="Capacity" id="capacity_new_class" min="1" max="300" placeholder="Capacity">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="room_number_new_class">Room Number</label>
                            <input type="number" name="Room Number" id="room_number_new_class" min="1" max="1000" placeholder="Room Number">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="time_new_class">Time</label>
                            <input type="text" name="Time" id="time_new_class" placeholder="Time">
                        </div>
                        <input type="submit" class="button expanded rit-orange" value="Create Section">
                    </form>
                    <!-- End new form -->
                </div>
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                <h4>Add a New Class Section</h4>
                <div class="floated-label-wrapper">
                    <label for="full-name">Full name</label>
                    <input type="text" id="full-name" name="full name input" placeholder="Full name">
                </div>
                <div class="floated-label-wrapper">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email input" placeholder="Email">
                </div>
                <div class="floated-label-wrapper">
                    <label for="pass">Password</label>
                    <input type="password" id="pass" name="password input" placeholder="Password">
                </div>
                <input class="button expanded" type="submit" value="Sign up">
                </form>
                <!-- End new form -->
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                <h4>Add a New Class Section</h4>
                <div class="floated-label-wrapper">
                    <label for="full-name">Full name</label>
                    <input type="text" id="full-name" name="full name input" placeholder="Full name">
                </div>
                <div class="floated-label-wrapper">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email input" placeholder="Email">
                </div>
                <div class="floated-label-wrapper">
                    <label for="pass">Password</label>
                    <input type="password" id="pass" name="password input" placeholder="Password">
                </div>
                <input class="button expanded" type="submit" value="Sign up">
                </form>
                <!-- End new form -->
            </div>


            <div class="small-12 medium-3 large-3">
                <!-- Start new form -->
                <form class="callout text-center">
                    <h4>Add a New Class Section</h4>
                    <div class="floated-label-wrapper">
                        <label for="full-name">Full name</label>
                        <input type="text" id="full-name" name="full name input" placeholder="Full name">
                    </div>
                    <div class="floated-label-wrapper">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email input" placeholder="Email">
                    </div>
                    <div class="floated-label-wrapper">
                        <label for="pass">Password</label>
                        <input type="password" id="pass" name="password input" placeholder="Password">
                    </div>
                    <input class="button expanded" type="submit" value="Sign up">
                </form>
                <!-- End new form -->
            </div>
        </div>
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
