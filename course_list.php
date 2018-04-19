<?php
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    $user_id = NIL;
}

//used to check if a professor type
$is_prof = file_get_contents("http://127.0.0.1:5002/CheckIfProfessor?id=".$user_id);
$is_prof = json_decode($is_prof, true);

$is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$user_id);
$is_admin = json_decode($is_admin, true);

$is_student = (!$is_admin && !$is_prof && $user_id != NIL);
 ?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Course List</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/foundation-icons.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>

      <?php
          // Load Nav bar and callouts
          include 'nav.php';
          include 'callouts.html';
      ?>
    
    
    <div class="grid-container">
	  
      <div class="grid-x grid-padding-x">

        <ul class="menu" style="margin-top:2%;">
          <li><input id="filter" type="search" placeholder="Enter a course ID, Instructor, Time, etc. (ex. SWEN-344)" style="width:200%" value="<?php if (isset($_GET['search_parameters'])){echo $_GET['search_parameters'];};?>"></li>
          <li><button type="button" class="button" style="margin-left:267%;">Search</button></li>
        </ul>

          <table class="hover" style="margin-top:2%;">
              <tr>
                  <?php if ($is_student) { ?>
                  <th align="left">Fav.</th>
                  <?php } ?>
                  <th align="left">Course</th>
                  <th align="left">Section</th>
                  <th align="left">Time</th>
                  <th align="left">Instructor</th>
                  <th align="left">Room</th>
              </tr>

              <?php
                  $current_semester = file_get_contents("http://127.0.0.1:5002/GetCurrentSemester");
                  $current_semester = json_decode($current_semester, true)["current_semester"];


                $classes = file_get_contents("http://127.0.0.1:5002/GetClasses");
                $classes = json_decode($classes, true);
                $classes = $classes["classes"]; //fix this

                foreach ($classes as $class){

                  $course = file_get_contents("http://127.0.0.1:5002/GetCourseInfo?course_id=" . $class["course_id"]);
                  $course = json_decode($course, true);
                  $course = $course["course_info"][0];

                  $professor = file_get_contents("http://127.0.0.1:5002/GetProfessorByID?professor_id=" . $class["professor_id"]);
                  $professor = json_decode($professor, true);

                    //TODO: refactor, get users list of favs and compare
                  $favorite = file_get_contents("http://127.0.0.1:5002/CheckFavoriteStatus?class_id=" . $class["class_id"] . "&user_id=" . $user_id);
                  $favorite = json_decode($favorite, true);
                  $favorite = ($favorite['favorite_status'] == "True" ? true: false);

                    ?>
              <tr name="class_listing">
                  <?php if ($is_student) {?>
                  <td>
                      <?php
                        if ($favorite){
                            echo ("<i class=\"fi-heart favorited\"></i>");
                        }else{
                            echo ("<i class=\"fi-heart unfavorited\"></i>");
                        }
                      ?>
                  </td>
                  <?php } ?>
                <td><a href="course_view.php?class_id=<?php echo $class["class_id"];?>"><?php echo $course["course_name"];?></a></td>
                <td><?php echo $class["section"];?></td>
                <td><?php echo $class["time"];?></td>
                <td><?php echo $professor["professor_name"];?></td>
                <td><?php echo $class["room_number"];?></td>
              </tr>

              <?php } ?>

        </table>
      </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="bower_components/motion-ui/dist/motion-ui.js"></script>
    <script src="js/app.js"></script>
    
    <script>
      instantiateFilter();
     </script>
  </body>
</html>
