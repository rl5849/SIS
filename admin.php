<?php
session_start();
if (!isset($_SESSION['user_id'])){
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
    exit();
}
else{
    $user_id = $_SESSION['user_id'];
} ?>

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

<?php
    if (isset($_POST["action"])){
        switch ($_POST["action"]){
            case "add_class":
                add_class();
//                case "add_couse":
//                    add_course();
//                case "add_semester":
//                    add_semeseter();
        }
    }

    function add_class() {

        $url_params = $_POST['course_id'] . "&time=" . urlencode($_POST['time']) . "&room_number=" . $_POST['room_number'] . "&prof_id=" . $_POST['professor_id']. "&capacity=" . $_POST['capacity'];
        $result = file_get_contents("http://127.0.0.1:5002/AddClass?course_id=" . $url_params);
        $result = json_decode($result, true);
        if ($result == "SUCCESS"){
            echo "<script>showMessage(\"success\", \"Successfully added Section\");</script>";
        }
        else{
            echo "<script>showMessage(\"failure\", \"Failed to add Section\");</script>";
        }
    }

    if (isset($_POST["enroll"])){
        enroll($_POST["user_id"], $_POST["class"]);
    }
    function unenroll($user_id, $local_class_id) {
        $unenroll = file_get_contents("http://127.0.0.1:5002/DropStudent?class_id=" . $local_class_id . "&user_id=" . $user_id);
        $unenroll = json_decode($unenroll, true);
        if ($unenroll == "SUCCESS"){
            echo "<script>showMessage(\"success\", \"Successfully Dropped class\");</script>";
        }
        else{
            echo "<script>showMessage(\"failure\", \"Failed to Drop class\");</script>";
        }
    }
?>
    <!-- Load Nav Bar and Callouts -->
    <div id="nav-placeholder"></div>
    <div id="callouts-placeholder"></div>
    <!-- End load Nave Bar and Callouts -->
    <div class="grid-container">

        <div class="grid-x grid-padding-x" style="padding-top:2%;">

            <div class="small-12 medium-3 large-3 columns">
                <div>
                    <form class="callout text-center" method="post">
                        <h4>Add a New Class Section</h4>
                        <div class="floated-label-wrapper">
                            <label for="class_new_class">Class</label>
                            <input type="hidden" name="action" value="add_class">
                            <select name="course_id" id="class_new_class">

                                <?php
                                $course_list = file_get_contents("http://127.0.0.1:5002/GetCourses");
                                $course_list = json_decode($course_list, true);

                                foreach ($course_list["courses"] as $course){
                                    ?>
                                    <option value="<?php echo $course["course_id"]; ?>"><?php echo $course["course_name"]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="professor">Professor</label>
                            <select name="professor_id" id="professor">

                                <?php
                                $prof_list = file_get_contents("http://127.0.0.1:5002/GetProfs");
                                $prof_list = json_decode($prof_list, true);

                                foreach ($prof_list["profs"] as $prof){
                                    ?>
                                    <option value="<?php echo $prof[1]; ?>"><?php echo $prof[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="capacity_new_class">Capacity</label>
                            <input type="number" name="capacity" id="capacity_new_class" min="1" max="300" placeholder="Capacity">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="room_number_new_class">Room Number</label>
                            <input type="number" name="room_number" id="room_number_new_class" min="1" max="1000" placeholder="Room Number">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="time_new_class">Time</label>
                            <input type="text" name="time" id="time_new_class" placeholder="Time (eg. 'TuTh 10:00-10:55')">
                        </div>
                        <input type="submit" class="button expanded rit-orange" value="Create Section">
                    </form>
                    <!-- End new form -->
                </div>
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                <h4>Add Course</h4>
                <div class="floated-label-wrapper">
                    <label for="course_name">Course Name</label>
                    <input type="text" id="course_name" name="full name input" placeholder="Course Name">
                </div>
                <div class="floated-label-wrapper">
                    <label for="course_code">Course Code</label>
                    <input type="text" id="course_code" name="course_code input" placeholder="Course Code">
                </div>
                <div class="floated-label-wrapper">
                    <label for="credits_new_class">Room Number</label>
                    <input type="number" name="room_number" id="credits" min="1" max="7" placeholder="Course Credits">
                </div>
                <div class="floated-label-wrapper">
                    <label for="course_description">Password</label>
                    <input type="text" id="course_description" name="description input" placeholder="Course Description">
                </div>
                <input class="button expanded" type="submit" value="Create Course">
                </form>
                <!-- End new form -->
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                <h4>Add a New Semester</h4>
                <div class="floated-label-wrapper">
                    <label for="semester_code">Semester Code</label>
                    <input type="text" id="semester_code" name="semester_code" placeholder="Semester Code">
                </div>
                <input class="button expanded" type="submit" value="Create Semester">
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
