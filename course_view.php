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
    <!-- Load Nav Bar -->
    <div id="nav-placeholder"></div>

    <script>
    $(function(){
      $("#nav-placeholder").load("nav.php");
    });
    </script>
    <!-- End load Nav Bar -->
    
    <?php
        $course_id = $_GET["course_id"];
				$course_info = file_get_contents("http://127.0.0.1:5002/GetCourseInfo?course_id=".$course_id);
				$course_info = json_decode($course_info, true);

        $class_id = $_GET["class_id"];
                $class_info = file_get_contents("http://127.0.0.1:5002/GetClasses?class_id=" .$class_id);
                $class_info = json_decode($class_info, true);

        $prof_id =  ($class_info["class_info"][0]["professor_id"]);
                $prof_info = file_get_contents("http://127.0.0.1:5002/GetProfessorByID?professor_id=" .$prof_id);
                $prof_info = json_decode($prof_info, true);

      ?>

	</div>
    <div class="grid-container">
	  
      <div class="grid-x grid-padding-x" style="padding-top:2%;">
        
        <div class="large-4 medium-4 small-4 cell">
          <ul class="profile-list">
            <li><?php echo ($course_info["course_info"][0]["course_name"])?></li>
            <li>SWEN-344</li> <!-- course code not in db, use course ID?-->
          </ul>
        </div>
        <div class="large-6 medium-6 small-5 cell">
          <ul class="profile-list">
            <li><?php echo ($class_info["class_info"][0]["room_number"]) ?></li>
            <li><?php echo ($class_info["class_info"][0]["time"]) ?></li> <!-- needs getclassinfo -->
            <li><?php echo ($prof_info["professor_name"]) ?></li>
          </ul>
        </div>
        <div class="large-2 medium-2 small-3 cell">
          <ul class="profile-list">
            <li>          
              <p><input type="submit" class="button expanded rit-orange" value="Favorite"></input></p> <!-- make this a post? just gonna leave it blank for now-->
              <p><input type="submit" class="button expanded rit-orange" value="Enroll"></input></p>
            </li>
          </ul>
        </div>
        
      </div>
      
      <div class="grid-x grid-padding-x" style="padding-top:2%;">
        
        <div class="large-4 medium-4 small-4 cell">
          <div class="card">
            <div class="card-divider">
              Info
            </div>
            <div class="card-section">
              <ul class="profile-list">


                <li>3 Credit Hours</li>
                <li>Enrolled: 40/40</li> <!-- needs getPrereqs -->
                <li>Wait List: 3/10</li>
                <li>...</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="large-4 medium-4 small-4 cell">
          <div class="card">
            <div class="card-divider">
              Description
            </div>
            <div class="card-section">
              <?php echo ($course_info["course_info"][0]["course_description"])?>
            </div>
          </div>
        </div>
        <div class="large-4 medium-4 small-4 cell">
          <div class="card">
            <div class="card-divider">
              Prerequisites
            </div>
            <div class="card-section">
              <ul class="profile-list">
                <li>3rd Year status</li>
                <li>Major: Software Engineering</li>
                <li>Must be pretty cool</li>
                <li>...</li>
              </ul>
            </div>
          </div>
        </div>
        
      </div>
      
    </div>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
