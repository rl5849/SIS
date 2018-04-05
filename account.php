<?php
// PHP for using the local SIS API
date_default_timezone_set("America/New_York");
session_start();


if (isset($_POST['login'])) {
    if ($_POST['login'] == "Login as student"){
        $student_id = 1;
        $_SESSION['user_id'] = 1;
    }else{
        $student_id = 67;
        $_SESSION['user_id'] = 67;
    }
}
else if(isset($_SESSION['user_id'])){
    $student_id = $_SESSION['user_id'];
}else{
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
    exit();
}

// If an update action was made and sent to this page, then process it.
if (isset($_POST["action"]) && $_POST["action"] == "update-profile") {
    $fName = $_POST["fName"];
    $lName = $_POST["lName"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $gradYear = $_POST["grad-year"];
    $userType = $_POST["user-type"];

    $profile_data = array (
            'user_id' => $_SESSION["user_id"],
            'name' => $fName." ".$lName,
            'date_of_birth' => $dob,
            'gender' => $gender,
            'grad_year' => $gradYear,
            'user_type' => $userType,
    );



    $results = file_get_contents("http://127.0.0.1:5002/ModProfile?".http_build_query($profile_data));
    $results = json_decode($results);

    // TODO if results are positive, report



    if ($userType == "professor") {
        $results = file_get_contents("http://127.0.0.1:5002/RequestProfessorApproval?user_id=".$_SESSION["user_id"]);
        $results = json_decode($results);

        // TODO if results are positive, report
    }
}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - My Account</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/foundation-icons.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>

    <!-- Load Nav Bar and Callouts -->
    <div id="nav-placeholder"></div>
    <div id="callouts-placeholder"></div>
    <!-- End load Nave Bar and Callouts -->


    
    <?php
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
                echo "DoB: ".($date);
              ?>
          </li>
          <li>
              <?php echo "Major: " . $student_info["student_info"][0]["major"]?>
          </li>

		  <li><?php echo "Expected Grad. Year: ".($student_info["student_info"][0]["graduation_year"])?></li>
        </ul>
      </div>
      <div class="large-2 medium-2 small-3 cell">
        <ul class="profile-list">
          <p><input type="button" href="https://www.linkedin.com" class="button expanded rit-orange" value="LinkedIn"></input></p>
          <li>GPA: <?php echo $student_info["student_info"][0]["GPA"];?></li>
        </ul>
      </div>
        <?php
        $semesters = file_get_contents("http://127.0.0.1:5002/GetSemesters");
        $semesters = json_decode($semesters, true)["semesters"];
        var_dump($semesters[0][0])
        ?>
    </div>
        <div class="grid-x grid-padding-x" style="padding-top: 2%;">
      <div class="large-12 medium-12 small-12 columns">
      <ul class="horizontal tabs" data-tabs id="course-tabs">
        <li class="tabs-title favorited-classes-title"><a href="#panel0v">Favorited</a></li>
        <li class="tabs-title is-active"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[0][0]?>)">Current Semester</a></li>
        <li class="tabs-title" onclick="load_class_table(<?php echo $semesters[1][0]?>);"><?php echo $semesters[1][1]?></li> <!--On click reload with other semester-->
        <li class="tabs-title" onclick="load_class_table(<?php echo $semesters[2][1]?>);"><?php echo $semesters[2][1]?></li>
        <li class="tabs-title"><a href="#panel4v">Earlier</a></li>
      </ul>
      </div>


      <div class="large-12 medium-12 small-12 cell">
        <div class="tabs-content" data-tabs-content="course-tabs">
          <div class="tabs-panel is-active" id="panel1v">
              <table class="hover">
                <tr>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Time</th>
                    <th>Instructor</th>
                    <th>Room</th>
                </tr>
              </table>
            <table class="hover">
                <img style="text-align: center" src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" id="loading-image">
                  <tbody id="classes">
                  <!--                              Javascript builds table here-->

                  </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
    <center><img src="images/LOGO.png" style="width:75px;height:75px;"></center>

  <script>
      $(document).ready(function() {
          load_class_table(<?php echo $semesters[0][0]?>);
      });
      function load_class_table(semester){
          $('#loading-image').show();
          $.ajax({
              type : 'POST',
              url : 'admin_ajax_funcs.php',
              dataType : 'json',
              data: {'action':'get_student_classes_by_semester', 'user_id' :  <?php echo $student_id?>, "semester_id": semester},
              contentType: "application/x-www-form-urlencoded",
              async : false,
              //beforeSend : function(){/*loading*/},

              success : function(result){
                  var buffer="";
                  $.each(result, function(index, val){
                      for(var i=0; i < val.length; i++){
                          var item = val[i];
                          buffer+="<tr>\
                                    <td><a href='course_view.php?class_id=" + item.course_id + "'>" + item.name + "</a></td>\
                                    <td>" + item.section + "</td> \
                                    <td>" + item.time + "</td> \
                                    <td>" + item.professor_name + "</td> \
                                    <td>" + item.room_number + "</td> \
                                 </tr>";
                      }
                      $("#classes").empty();
                      $("#classes").append(buffer);
                  });
              },
              error: function (msg) {
                  console.log(msg.responseText);
              },
              complete: function(){
                  $('#loading-image').hide();
              }
          });
      }
  </script>

  <script src="bower_components/jquery/dist/jquery.js"></script>
  <script src="bower_components/what-input/dist/what-input.js"></script>
  <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
  <script src="bower_components/motion-ui/dist/motion-ui.js"></script>
  <script src="js/app.js"></script>
  <script>
    makeNav();
    makeCallouts();
  </script>

  </body>
</html>
