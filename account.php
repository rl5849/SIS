<?php
// PHP for using the local SIS API
date_default_timezone_set("America/New_York");
session_start();

if (isset($_GET["editprofile"]) && $_GET["editprofile"] == "true") {
    $is_editing = true;
}

if(isset($_SESSION['user_id'])){
    $now = time(); // Checking the time now when home page starts.
    if ($now > $_SESSION['expire']) {
        session_destroy();
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
    }
    else{
        $student_id = $_SESSION['user_id'];
    }

}else{
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
    exit();
}

// If an update action was made and sent to this page, then process it.
if (isset($_POST["action"]) && $_POST["action"] == "update-profile") {

    // htmlspecialchars sanitizes xss attempts
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
    $dob = htmlspecialchars($_POST["dob"], ENT_QUOTES, 'UTF-8');
    $gradYear = htmlspecialchars($_POST["grad-year"], ENT_QUOTES, 'UTF-8');
    $userType = $_POST["user-type"];
    $major = $_POST["major"];
    $profilePic = htmlspecialchars($_POST["profile-pic"], ENT_QUOTES, 'UTF-8');

    $profile_data = array (
            'user_id' => $_SESSION["user_id"],
            'name' => $name,
            'date_of_birth' => $dob,
            'grad_year' => $gradYear,
            'user_type' => $userType,
            'profile_pic' => $profilePic,
            'major' => $major,
    );

    $results = file_get_contents("http://127.0.0.1:5002/ModProfile?".http_build_query($profile_data));
    $results = json_decode($results);

    // TODO if results are positive, report
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
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118378709-1"></script>
      <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-118378709-1');
      </script>

  </head>
  <body>

    <?php
        // Load Nav bar and callouts
        include 'nav.php';
        include 'callouts.html';
    ?>

    <?php
    $current_semester = file_get_contents("http://127.0.0.1:5002/GetCurrentSemester");
    $current_semester = json_decode($current_semester, true)["current_semester"];
    $student_info = file_get_contents("http://127.0.0.1:5002/GetStudentInfo?student_id=".$student_id);
    $student_info = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $student_info), true);

    //used to check if a professor type
    $is_prof = file_get_contents("http://127.0.0.1:5002/CheckIfProfessor?id=".$student_id);
    $is_prof = json_decode($is_prof, true);

    $is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$student_id);
    $is_admin = json_decode($is_admin, true);

    $gpaSet = file_get_contents("http://127.0.0.1:5002/SetGPA?user_id=".$student_id);

    ?>

	<div class="grid-container">
  
    <div class="grid-x grid-padding-x" style="padding-top:2%;">
      <div class="large-1 medium-2 small-3 cell">
        <div class="profile-picture-wrapper">
            <img class="profile-picture"
                src="<?php
                $profile_picture = $student_info["student_info"][0]["profile_pic"];
                echo $profile_picture;
                ?>
                " alt="Profile picture failed to load.">
        </div>
      </div>
      <div class="large-6 medium-6 small-8 cell">
        <form action="account.php" method="post">
            <ul class="profile-list">
              <li><?php
                  // $name is defined in nav.php
                  if ($is_editing) {
                      echo "Name: <input required name='name' value='".$name."'>";
                  } else {
                      if ($name == "") {
                          echo "<h4>Unknown User</h4>";
                      } else {
                          echo "<h4>".$name."</h4>";
                      }

                  }
                  ?>
              </li>
              <li>
                  <table>
                      <tr>
                          <td>Status</td>
                          <td><?php
                              if($is_prof["is_prof"] == True){
                                  echo "Professor";
                              }
                              else if($is_admin["is_admin"] == True){
                                  echo "Admin";
                              }
                              else{
                                  echo "Student";
                              }
                              ?></td>
                      </tr>
                      <?php if (!$is_prof["is_prof"] && !$is_admin["is_admin"]) { ?>
                      <tr>
                          <td>DoB</td>
                          <td><?php
                              $date = strtotime($student_info["student_info"][0]["date_of_birth"]);
                              $date = date("M d, Y", $date);
                              if ($is_editing) {
                                  echo "<input name='dob' placeholder='ex. 5-15-1980' value='" . $date . "'>";
                              } else {
                                  if ($date == "") {
                                      echo "N/A";
                                  } else {
                                      echo $date;
                                  }
                              }
                              ?></td>
                          <td>Expected Grad Year</td>
                          <td><?php
                              $grad_year = $student_info["student_info"][0]["graduation_year"];
                              if ($is_editing) {
                                  echo "<input name='grad-year' placeholder='ex. 2020' value='" . $grad_year . "'>";
                              } else {
                                  if ($grad_year == "") {
                                      echo "N/A";
                                  } else {
                                      echo $grad_year;
                                  }
                              }
                              ?></td>
                      </tr>
                      <tr>
                          <td>Major</td>
                          <td> <?php


                              if ($is_editing) {
                                  $majors = file_get_contents("http://127.0.0.1:5002/GetMajors");
                                  $majors = json_decode($majors, true)["majors"];
                                  $html = "<select name='major'>";
                                  if (!$student_info["student_info"][0]["major"]) {
                                      $html = $html . "<option>Choose...</option>";
                                  }
                                  foreach ($majors as $major) {
                                      $current_txt = "";
                                      if ($major['major_id'] == $student_info["student_info"][0]["major"]) {
                                          $current_txt = "selected";
                                      }

                                      $html = $html . "<option value='" . $major['major_id'] . "' " . $current_txt . " >" . $major['major_name'] . "</option>";
                                  }
                                  $html = $html . "</select>";

                                  echo $html;


                              } else {

                                  if ($student_info["student_info"][0]["major_name"] == "") {
                                      echo "N/A";
                                  } else {
                                      echo $student_info["student_info"][0]["major_name"];
                                  }

                              }
                              ?></td>
                          <td>GPA</td>
                          <td><?php

                              $gpa = $student_info["student_info"][0]["GPA"];

                              if ($gpa == "" ) {
                                  echo "N/A";
                              } else {
                                  echo $gpa;
                              }


                              } // End $is_prof and $is_admin check
                              ?></td>
                      </tr>
                      <?php if ($is_editing) { ?>
                      <tr>
                          <td>Profile Picture</td>
                          <td>
                              <input name='profile-pic' placeholder='Enter a URL' value='<?php echo $profile_picture; ?>'>
                          </td>
                          <td></td>
                          <?php if (!$is_prof["is_prof"] && !$is_admin["is_admin"]) {
                              if($student_info["student_info"][0]['prof_requested']){
                                  echo "<td><input class='button expanded rit-orange prof-req' type='submit' name='prof-approval' value='Professor Approval Pending' disabled></td>";

                              }else{
                                  echo "<td><input class='button expanded rit-orange prof-req' type='submit' name='prof-approval' value='Request Professor Approval'></td>";
                              }
                          } ?>
                      </tr>
                      <?php } // End $is_editing check ?>

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
          <?php
          $semesters = file_get_contents("http://127.0.0.1:5002/GetSemesters");
          $semesters = json_decode($semesters, true)["semesters"];
          if(!$is_editing) {?>

        <?php

        ?>
    </div>
        <div class="grid-x grid-padding-x" style="padding-top: 2%;">
          <div class="large-12 medium-12 small-12 columns">
              <?php
              if(!$is_admin["is_admin"]){
              ?>
			  <ul class="horizontal tabs" data-tabs id="course-tabs">

				<?php if($is_prof["is_prof"]){ ?>
					<li class="tabs-title"><a href="#panel1v" aria-selected="true" onclick="load_class_table('favs')">Favorites</a></li>
				<?php } ?>
				<li class="tabs-title is-active"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[0][0]?>)">Current Semester</a></li>

              
                <li class="tabs-title"><a href="#panel1v" aria-selected="true" onclick="load_class_table('favs')">Favorites</a></li>

              
                <li class="tabs-title is-active"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[0][0]?>)">Current Semester</a></li>

                <li class="tabs-title"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[1][0]?>);"><?php echo $semesters[1][1]?></a></li>
                <li class="tabs-title"><a href="#panel1v" aria-selected="true" onclick="load_class_table(<?php echo $semesters[2][0]?>);"><?php echo $semesters[2][1]?></a></li>
                <li class="tabs-title"><a href="#panel1v">Earlier</a></li>
              </ul>
			  <?php } ?>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <div class="tabs-content" data-tabs-content="course-tabs">
              <div class="tabs-panel is-active" id="panel1v">
                  <?php	if($is_admin["is_admin"] != true){	?>
				  <table class="hover">
                    <tr>
                        <th align="left">Course</th>
                        <th align="left">Section</th>
                        <th align="left">Time</th>
						<?php if($is_prof["is_prof"] != true) { ?>
                        <th align="left">Instructor</th>
						<?php } ?>
                        <th align="left">Room</th>
						<?php if($is_prof["is_prof"] != true) { ?>
                        <th align="left">Grade</th>
						<?php } ?>

                    </tr>
                      <tbody id="classes">
                      <!--  Javascript builds table here   -->
						
                      </tbody>
                </table>
                      <img style="margin:auto; width:256px " src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" id="loading-image">
                  <?php } ?>

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
		  var myuser;
		  <?php if($is_prof["is_prof"]) {
					 echo "myuser = 'prof'";
			}else{
                echo "myuser = 'stud'";
          } ?>
			
				
		  
		  
          $('#loading-image').show();
          $.ajax({
              type : 'POST',
              url : 'admin_ajax_funcs.php',
              dataType : 'json',
              data: {'action':'get_student_classes_by_semester', 'user_id' :  <?php echo $student_id?>, "semester_id": semester, 'user_type': myuser},
              contentType: "application/x-www-form-urlencoded",
              async : false,
              //beforeSend : function(){/*loading*/},

              success : function(result){
                  var buffer="";
                  $.each(result, function(index, val){
                      for(var i=0; i < val.length; i++){
                          var item = val[i];
                          var grade = '-';

                          if(item.grade != null){
                              grade = item.grade;
                          }
                          buffer+="<tr>\
                                    <td><a href='course_view.php?class_id=" + item.class_id + "'>" + item.name + "</a></td>\
                                    <td>" + item.section + "</td> \
                                    <td>" + item.time + "</td> \
                                    <td>" + item.professor_name + "</td> \
                                    <td>" + item.room_number + "</td> \";
                          if(myuser == "stud"){
								buffer +="<td>" + grade + "</td>"
						  }
                          buffer +="</tr>";
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
        <?php
        if(isset($_GET["fromregister"]) && $_GET["fromregister"] == "true") {
            echo "<script>window.onload = function() {showMessage('success', 'Account successfully created!');};</script>";
        }

        if (isset($_POST["action"]) && $_POST["action"] == "update-profile") {
            // TODO check if this was actually done
            echo "<script>window.onload = function() {showMessage('success', 'Account information updated successfully.');};</script>";
        }

        ?>


    <script>
        //Ajax for req prof status
        $('.prof-req').on('click', function () {
            //Make the request
            var success = $.ajax({
                type: 'POST',
                data: {'action': 'RequestProfStatus', 'user_id' : "<?php echo $user_id;?>"},
                url: 'user_ajax_funcs.php',
                success: function (data) {
                    if (!(data.includes("Fail"))) {
                        showMessage("success", data);
                        return true;
                    }
                    else {
                        showMessage("failure", data);
                        return false;
                    }
                },
                error: function (msg) {
                    console.log(msg.responseText);
                    return false;
                }
            });
            if (success){
                $(this).attr('value', 'Professor Status Requested');
                $(this).attr("disabled", "disabled");
            }

        });
    </script>

  </body>
</html>
