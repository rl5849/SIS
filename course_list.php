<?php
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    $user_id = NIL;
}
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
                  <th>Fav.</th>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Room</th>
              </tr>

              <?php
                  $current_semester = file_get_contents("http://127.0.0.1:5002/GetCurrentSemester");
                  $current_semester = json_decode($current_semester, true)["current_semester"];

                  $classes = file_get_contents("http://127.0.0.1:5002/GetClasses");
                  $classes = json_decode($classes, true);
                  $classes = $classes["classes"]; //fix this

                  $favorites = file_get_contents("http://127.0.0.1:5002/GetFavoritedClasses?user_id=" . $user_id);
                  $favorites = json_decode($favorites, true);

                  $fav_ids = [];
                  foreach ($favorites as $fav){
                      array_push($fav_ids, $fav['class_id']);
                  }


              foreach ($classes as $class){
                    if (in_array($class['class_id'], $fav_ids)){
                        $favorite = true;
                    }
                    else{
                        $favorite = false;
                    }


               ?>
              <tr name="class_listing">
                  <td>
                      <?php
                        if ($favorite){
                            echo ("<i class=\"fi-heart favorited\"></i>");
                        }else{
                            echo ("<i class=\"fi-heart unfavorited\"></i>");
                        }
                      ?>
                  </td>
                <td><a href="course_view.php?class_id=<?php echo $class["class_id"];?>"><?php echo $class["name"];?></a></td>
                <td><?php echo $class["section"];?></td>
                <td><?php echo $class["time"];?></td>
                <td><?php echo $class["professor_name"];?></td>
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
