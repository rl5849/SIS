<?php
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    $user_id = NIL;
}

$is_prof = file_get_contents("http://127.0.0.1:5002/CheckIfProfessor?id=".$user_id);
$is_prof = json_decode($is_prof, true);

$is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$user_id);
$is_admin = json_decode($is_admin, true);

$is_student = (!$is_admin["is_admin"] && !$is_prof["is_prof"] && $user_id);

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

                  $favorites = file_get_contents("http://127.0.0.1:5002/GetFavoritedClasses?user_id=" . $user_id);
                  $favorites = json_decode($favorites, true);
                  $favorites = $favorites['classes'];


                  foreach ($classes as $class){
                        if (in_array($class, $favorites)){
                            $favorite = true;}
                        else{$favorite = false;}
               ?>
              <tr name="class_listing">
                  <?php if ($is_student) {
                        if ($favorite){
                            echo ("<td><div class=\"fi-heart favorited\" id='favorite' value='" . $class['class_id'] . "'></div></td>");
                        }else{
                            echo ("<td><div class=\"fi-heart unfavorited\" id='favorite' value='" . $class['class_id'] . "'></div></td>");
                        }
                      }
                  ?>
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
        $('.fi-heart').on('click', function () {
            var action = "";
            var orig = "";
            if ($(this).hasClass('favorited')){
                console.log("Clicked was favorited, unfavoriting");
                orig = "favorited";
                action = 1;
            }else {
                console.log("Clicked was unfavorited, favoriting");
                action = 0; //0 means you're going to favorite the class
                orig = 'unfavorite';
            }

            //Make the request
            $.ajax({
                type: 'POST',
                data: {'action': 'favorite', 'user_id' : "<?php echo $user_id;?>", 'class_id' : $(this).attr('value'), 'favorite' : action},
                url: 'user_ajax_funcs.php',
                success: function (data) {
                    if (data.includes("Success")) {
                        //Do nothing here
                    }
                    else {
                        showMessage("failure", data);
                    }
                },
                error: function (msg) {
                    console.log(msg.responseText);
                }
            });
            if ($(this).hasClass('favorited')) {
                $(this).removeClass('favorited');
                $(this).addClass('unfavorited');
            }
            else {
                $(this).removeClass('unfavorited');
                $(this).addClass('favorited');
            }

        });

      instantiateFilter("filter", "class_listing", false);
     </script>
  </body>
</html>
