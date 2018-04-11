<?php
// PHP for using the local SIS API
date_default_timezone_set("America/New_York");
session_start();

if (isset($_GET["editprofile"]) && $_GET["editprofile"] == "true") {
    $is_editing = true;
}

if(isset($_SESSION['user_id'])){
    $student_id = $_SESSION['user_id'];
}else{
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
    exit();
}

// If an update action was made and sent to this page, then process it.
if (isset($_POST["action"]) && $_POST["action"] == "update-profile") {
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $gradYear = $_POST["grad-year"];
    $userType = $_POST["user-type"];
    $profilePic = $_POST["profile-pic"];

    $profile_data = array (
            'user_id' => $_SESSION["user_id"],
            'name' => $name,
            'date_of_birth' => $dob,
            'grad_year' => $gradYear,
            'user_type' => $userType,
            'profile_pic' => $profilePic,
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
  
      <div class="large-1 medium-2 small-3 cell">
        <div class="profile-picture-wrapper">
            <img class="profile-picture"
                 src="<?php
                function showProfilePicIf200($profile_picture) {
                    error_reporting(0);
                    $placeholder = "images/user_profile_placeholder.png";
                    if ( $profile_picture == null ) { // If not set
                        echo $placeholder;
                        $shown = 0;
                    } else if ( get_headers($profile_picture)) { // if loads from URL
                        echo $profile_picture;
                        $shown = 1;
                    } else { // set but didn't load
                        echo $placeholder;
                        $shown = -1;
                    }
                    error_reporting(E_STRICT);
                    return $shown;
                }

                $profile_picture = $student_info["student_info"][0]["profile_pic"];

                $shown = showProfilePicIf200($profile_picture);
                echo '" alt="Profile Picture">';
                if ($shown == -1) {
                    echo "<div class='descriptor' id='load-fail-descriptor'>Image could not be loaded.</div>";
                    echo "<i class='fi-page load-fail' onmouseover='showLoadFail()' onmouseout='hideLoadFail()'></i>";
                }
                ?>
        </div>
      </div>
      <div class="large-6 medium-6 small-8 cell">
        <form action="account.php" method="post">
            <ul class="profile-list">
              <li><?php
                  if ($is_editing) {
                      echo "Name: <input name='name' value='".($student_info["student_info"][0]["student_name"])."'>";
                  } else {
                      echo "<h4>".($student_info["student_info"][0]["student_name"])."</h4>";
                  }
                  ?>
              </li>
              <li>
                  <table>
                      <tr>
                          <td>DoB</td>
                          <td><?php
                              $date = strtotime($student_info["student_info"][0]["date_of_birth"]);
                              $date = date("M d, Y", $date);
                              if ($is_editing) {
                                  echo "<input name='dob' placeholder='ex. 5-15-1980' value='".$date."'>";
                              } else {
                                  echo $date;
                              }
                              ?></td>
                          <td>Expected Grad Year</td>
                          <td><?php
                              if ($is_editing) {
                                  echo "<input name='grad-year' placeholder='ex. 2020' value='".$student_info["student_info"][0]["graduation_year"]."'>";
                              } else {
                                  echo $student_info["student_info"][0]["graduation_year"];
                              }
                              ?></td>
                      </tr>
                      <tr>
                          <td>Major</td>
                          <td> <?php
                              if ($is_editing) {
                                  echo "<input name='major' placeholder='ex. Software Engineering' value='".$student_info["student_info"][0]["major"]."'>";
                              } else {
                                  echo $student_info["student_info"][0]["major"];
                              }
                              ?></td>
                          <td>GPA</td>
                          <td><?php
                              if ($is_editing) {
                                  echo "<input name='gpa' placeholder='ex. 3.2' value='".$student_info["student_info"][0]["GPA"]."'>";
                              } else {
                                  echo $student_info["student_info"][0]["GPA"];
                              }
                              ?></td>
                      </tr>
                      <?php if ($is_editing) { ?>
                      <tr>
                          <td>Profile Picture</td>
                          <td colspan="3">
                              <input name='profile-pic' placeholder='Enter a URL' value='<?php echo $profile_picture; ?>'>
                          </td>
                      </tr>
                      <?php } ?>
                  </table>
                  <?php
                  if ($is_editing) {
                      echo "<input type='hidden' name='user-type' value='student'>"; // TODO correct user type
                      echo "<input type='hidden' name='action' value='update-profile'>";
                      echo "<input class='button expanded rit-orange' type='submit' name='submit' value='Update Profile'>";
                  }
                  ?>

              </li>
            </ul>
        </form>
      </div>
          <?php if(!$is_editing) {?>
      <div class="large-2 medium-2 small-3 cell">
        <input type="button" href="https://www.linkedin.com" class="button expanded rit-orange" value="LinkedIn">
      </div>
        <?php
        $semesters = file_get_contents("http://127.0.0.1:5002/GetSemesters");
        $semesters = json_decode($semesters, true)["semesters"];
        //var_dump($semesters[0][0])
        ?>
    </div>
        <div class="grid-x grid-padding-x" style="padding-top: 2%;">
          <div class="large-12 medium-12 small-12 columns">
              <ul class="horizontal tabs" data-tabs id="course-tabs">
                <li class="tabs-title favorited-classes-title"><a href="#panel0v">Favorited</a></li>
                <li class="tabs-title is-active"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[0][0]?>)">Current Semester</a></li>
                <li class="tabs-title"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[1][0]?>);"><?php echo $semesters[1][1]?></a></li>
                <li class="tabs-title"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[2][0]?>);"><?php echo $semesters[2][1]?></a></li>
                <li class="tabs-title"><a href="#panel1v">Earlier</a></li>
              </ul>
          </div>


          <div class="large-12 medium-12 small-12 cell">
            <div class="tabs-content" data-tabs-content="course-tabs">
              <div class="tabs-panel is-active" id="panel1v">
                  <table class="hover">
                    <tr>
                        <th align="left">Course</th>
                        <th align="right">Section</th>
                        <th>Time</th>
                        <th>Instructor</th>
                        <th align="right">Room</th>
                    </tr>
                      <tbody id="classes">
                      <!--                              Javascript builds table here-->

                      </tbody>
                </table>
                  <img style="margin:auto; width:256px " src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" id="loading-image">
              </div>
            </div>
          </div>
        </div>
  </div>
    <center><img src="images/LOGO.png" style="width:75px;height:75px;"></center>
        <?php }// End $is_editing check ?>

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
                                    <td align='right'>" + item.section + "</td> \
                                    <td align='center'>" + item.time + "</td> \
                                    <td align='center'>" + item.professor_name + "</td> \
                                    <td align='right'>" + item.room_number + "</td> \
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
