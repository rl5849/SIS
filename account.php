<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - My Account</title>
    <link rel="stylesheet" href="css/app.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>
  
    <!-- Load Nav Bar and Callouts -->
    <div id="nav-placeholder"></div>
    <div id="callouts-placeholder"></div>
    <!-- End load Nave Bar and Callouts -->

    <?php
    // PHP for using the LinkedIn API

    // Get the Access Token from LinkedIn
    $code = $_GET["code"];

    // Parse config file for codes
    $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
    $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
    $arr = explode("\n", $readfile);
    $client_id = explode("=", $arr[0])[1];
    $client_secret = explode("=", $arr[0])[1];

    fclose($myfile);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded",
            'method'  => 'POST'
        )
    );

    $params = array(
        'grant_type' => 'authorization_code',
        'code' => $code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => 'https://vm344p.se.rit.edu/SIS/account.php'
    );

    $context  = stream_context_create($options);
    $url = "https://www.linkedin.com/oauth/v2/accessToken?" . http_build_query($params);
    echo "url:".$url;
    $access_token_request = file_get_contents($url, false, $context);
    $access_token = json_decode($access_token_request)["access_token"];

    echo "First 3 of token: ".substr($access_token, 0, 3);

    $linkedin_user_info = file_get_contents("https://api.linkedin.com/v1/people/~?format=json");
    $linkedin_user_info = json_decode($linkedin_user_info, true);
    echo "test".$linkedin_user_info;
    ?>
    
    <?php
    // PHP for using the local SIS API
    date_default_timezone_set("America/New_York");
    $student_id = 1;
    $current_semester = file_get_contents("http://127.0.0.1:5002/GetCurrentSemester");
    $current_semester = json_decode($current_semester, true)["current_semester"];
    $student_info = file_get_contents("http://127.0.0.1:5002/GetStudentInfo?student_id=".$student_id);
    $student_info = json_decode($student_info, true);

    ?>

	<div class="grid-container">
  
    <div class="grid-x grid-padding-x" style="padding-top:2%;">
  
      <div class="large-4 medium-4 small-4 cell">
        <img src="<?php echo $student_info["student_info"][0]["profile_pic"]; ?>" alt="my_profile_image">
      </div>
      <div class="large-4 medium-4 small-4 cell">
        <ul class="profile-list">
		  <li><?php echo "<h4>".($student_info["student_info"][0]["student_name"])."</h4>"?></li>
		  <li><?php
                $date = strtotime($student_info["student_info"][0]["date_of_birth"]);
                $date = date("d-m-Y", $date);



              echo "DoB: ".($date)?></li>
		  <li><?php echo "Expected Grad. Year: ".($student_info["student_info"][0]["graduation_year"])?></li>
        </ul>
      </div>
      <div class="large-2 medium-2 small-3 cell">
        <ul class="profile-list">
          <p><input type="submit" href="https://www.linkedin.com"class="button expanded rit-orange" value="LinkedIn"></input></p>
          <li>GPA: <?php echo $student_info["student_info"][0]["GPA"];?></li>
        </ul>
      </div>
      
    </div>
  
    <div class="grid-x grid-padding-x" style="padding-top: 2%;">
      <div class="large-12 medium-12 small-12 columns">
      <ul class="horizontal tabs" data-tabs id="course-tabs">
        <li class="tabs-title favorited-classes-title"><a href="#panel0v">Favorited</a></li>
        <li class="tabs-title is-active"><a href="#panel1v" aria-selected="true">Current Semester</a></li>
        <li class="tabs-title"><a href="#panel2v">Fall 2017</a></li>
        <li class="tabs-title"><a href="#panel3v">Summer 2017</a></li>
        <li class="tabs-title"><a href="#panel4v">Earlier</a></li>
      </ul>
      </div>
      <div class="large-12 medium-12 small-12 cell">
        <div class="tabs-content" data-tabs-content="course-tabs">
          <div class="tabs-panel" id="panel0v">
            <table class="hover">
              <tr>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Room</th>
              </tr>
            </table>
          </div>
          <div class="tabs-panel is-active" id="panel1v">
            <table class="hover">
              <tr>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Room</th>
              </tr>
                <!--For loop for query here, delete everything else-->
                  <?php
                  $classes = file_get_contents("http://127.0.0.1:5002/GetStudentsClasses?student_id=" . $student_id); //getclasses
                  $classes = json_decode($classes, true);
                  $classes = $classes["students_classes"];



                  foreach ($classes as $class){
                      $professor = file_get_contents("http://127.0.0.1:5002/GetProfessorByID?professor_id=" . $class["professor_id"]); //get professor
                      $professor = json_decode($professor, true);
                      $professor = $professor["professor_name"];
                  ?>
                <tr>
                    <td><a href="course_view.php?class_id=<?php echo $class["class_id"];?>"><?php echo $class["name"];?></a></td>
                    <td><?php echo $class["section"];?></td>
                    <td><?php echo $class["time"];?></td>
                    <td><?php echo $professor;?></td>
                    <td><?php echo $class["room_number"];?></td>
                </tr>

                <?php } ?>
            </table>
          </div>
          <div class="tabs-panel" id="panel2v">
            <table class="hover">
              <tr>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Room</th>
                  <th>Grade</th>
              </tr>
            </table>
          </div>
          <div class="tabs-panel" id="panel3v">
            <table class="hover">
              <tr>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Room</th>
                  <th>Grade</th>
              </tr>
            </table>
          </div>
          <div class="tabs-panel" id="panel4v">
            <table class="hover">
              <tr>
                  <th>Course</th>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Room</th>
                  <th>Grade</th>
              </tr>
            </table>
          </div>
        </div>
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
