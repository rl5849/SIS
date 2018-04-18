<?php
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    $user_id = NIL;
}

// Load Nav bar and callouts
include 'nav.php';
include 'callouts.html';
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
    <!-- Load Nav Bar and Callouts -->
    <div id="nav-placeholder"></div>
    <div id="callouts-placeholder"></div>
    <!-- End load Nave Bar and Callouts -->
    
    
    <?php
        if (isset($_GET["class_id"])){
            $class_id = $_GET["class_id"];
        }
        else if (isset($_POST["class_id"])){
            $class_id = $_POST["class_id"];
        }
        else{
            $class_id = 1;
        }

        $enrollment_status = file_get_contents("http://127.0.0.1:5002/CheckEnrollmentStatus?class_id=" .$class_id . "&user_id=" . $user_id);
        $enrollment_status = json_decode($enrollment_status, true);
        $enrollment_status = $enrollment_status["enrollment_status"];

        $favorite_status = file_get_contents("http://127.0.0.1:5002/CheckFavoriteStatus?class_id=" .$class_id . "&user_id=" . $user_id);
        $favorite_status = json_decode($favorite_status, true);
        $favorite_status = $favorite_status["favorite_status"];
        $favorite_status = $favorite_status === 'True'? true: false;
        if ($favorite_status == "True"){
            $favorite_status_msg = "Unfavorite";
        }else{
            $favorite_status_msg = "Favorite";
        }


        switch ($enrollment_status){
            case "ENROLLED":
                $enrollment_status_msg = "Drop";
                $enrollment_status = True;
                break;
            case "WAITLIST":
                $enrollment_status_msg = "Drop Waitlist";
                $enrollment_status = True;
                break;
            default:
                $enrollment_status_msg = "Enroll";
                $enrollment_status = False;
                break;
        }


        $class_info = file_get_contents("http://127.0.0.1:5002/GetClassInfo?class_id=" . $class_id);
        $class_info = json_decode($class_info, true);

        $course_id = $class_info["class_info"][0]["course_id"];
        $course_info = file_get_contents("http://127.0.0.1:5002/GetCourseInfo?course_id=".$course_id);
        $course_info = json_decode($course_info, true);


        $prof_id =  ($class_info["class_info"][0]["professor_id"]);
        $prof_info = file_get_contents("http://127.0.0.1:5002/GetProfessorByID?professor_id=" .$prof_id);
        $prof_info = json_decode($prof_info, true);

        $wait_list = file_get_contents("http://127.0.0.1:5002/WaitlistByClass?class_id=" . $class_info["class_info"][0]["class_id"]);
        $wait_list = json_decode($wait_list, true);

        $user_requested_access = file_get_contents("http://127.0.0.1:5002/GetStudentAccess?class_id=" . $class_info["class_info"][0]["class_id"] . "&user_id=" . $user_id);
        $user_requested_access = json_decode($user_requested_access, true);
        $user_requested_access = $user_requested_access['requests'];

        $prerequisites = file_get_contents("http://127.0.0.1:5002/GetPrereqs?course_id=" . $class_info["class_info"][0]["course_id"]);
        $prerequisites = json_decode($prerequisites, true);

        $meetsPrereq = array();
        foreach ($prerequisites['prereqs'][0] as $prereq){
            $meetsPrereq[$prereq['prereq_id']] = file_get_contents("http://127.0.0.1:5002/CheckPrereq?prereq_id=" . $prereq['prereq_id'] . "&student_id=" . $user_id);
            $meetsPrereq[$prereq['prereq_id']] = json_decode($meetsPrereq[$prereq['prereq_id']], true);
            $meetsPrereq[$prereq['prereq_id']] = $meetsPrereq[$prereq['prereq_id']]["meets_prereq"];
            if ($meetsPrereq[$prereq['prereq_id']] == False){
                $meetsAllPrereqs = False;
            }
        }

    ?>

	</div>
    <div class="grid-container">
	  
      <div class="grid-x grid-padding-x" style="padding-top:2%;">
        
        <div class="large-4 medium-4 small-4 cell">
          <ul class="profile-list">
            <li>Course Name: <?php echo ($course_info["course_info"][0]["course_name"])?></li>
            <li>Course Code: <?php echo ($class_info["class_info"][0]["room_number"]) ?> </li> <!-- course code not in db, use course ID?-->
          </ul>
        </div>
        <div class="large-6 medium-6 small-5 cell">
          <ul class="profile-list">
            <li>Room Number: <?php echo ($class_info["class_info"][0]["room_number"]) ?></li>
            <li>Time: <?php echo ($class_info["class_info"][0]["time"]) ?></li> <!-- needs getclassinfo -->
            <li>Professor Name: <?php echo ($prof_info["professor_name"]) ?></li>
          </ul>
        </div>
        <div class="large-2 medium-2 small-3 cell">
          <ul class="profile-list">

              <?php //Only show buttons if they are logged in
                if ($user_id) {
                    ?>
                    <p>
                    <form class="ajax" method="post">
                        <input type="hidden" name="action" value="favorite">
                        <input type="hidden" name="favorite" value="<?php echo($favorite_status) ?>">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="submit" class="button expanded rit-orange"
                               value="<?php echo $favorite_status_msg; ?>">
                    </form>
                    </p>
                    <p>
                    <form class="ajax" method="post">
                        <input type="hidden" name="enroll" value="<?php echo !($enrollment_status) ?>">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="submit" class="button expanded rit-orange"
                               value="<?php echo $enrollment_status_msg; ?>">
                    </form>
                    </p>
                    <?php
                }
              ?>
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


                          <li>Credits: <?php echo ($class_info["class_info"][0]["credits"]) ?> </li>
                          <li>Enrolled: <?php echo ($class_info["class_info"][0]["num_enrolled"]) ?> / <?php echo ($class_info["class_info"][0]["capacity"]) ?>  </li> <!-- needs getPrereqs -->
                          <li>Wait List: <?php echo count($wait_list) ?> / 0 </li> <!-- needs waitlist capacity -->
                          <!-- <li>...</li> -->
                      </ul>
                  </div>
              </div>
          </div>

          <div class="large-4 medium-4 small-4 cell">
              <div class="card">
                  <div class="card-divider">
                      Special access required
                  </div>
                  <div class="card-section">
                      <form class="ajax" method="post">
                          <input type="hidden" name="action" value="RequestSpecialAccess">
                          <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                          <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                          <input class="access" onclick="unchecknone()" type="checkbox" name="hearing" <?php if(in_array('hearing', $user_requested_access)){echo "checked";} ?>>
                          <label>Hearing</label><br>

                          <input class="access" onclick="unchecknone()" type="checkbox" name="note_taking" <?php if(in_array('note_taking', $user_requested_access)){echo "checked";} ?>>
                          <label>Note Taker</label><br>
                          <input class="access" onclick="unchecknone()" type="checkbox" name="test_time" <?php if(in_array('test_time', $user_requested_access)){echo "checked";} ?>>
                          <label>Test Time</label><br>
                          <input id="none" onclick="uncheckaccess()" type="checkbox" name="none" <?php if([] == $user_requested_access){echo "checked";} ?>>
                          <label>None</label><br>
                          <input type="submit" class="button expanded rit-orange" value="Request Special Access">
                      </form>
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
              <ul class="profile-list prereqs">
                <?php
                    //assembles a list of prerequisites for the class
                    try {
                        $prerequisite = $prerequisites["prereqs"][0];
                        foreach ($prerequisites as $prereq ){
                            if ($meetsPrereq[$prereq["prereq_id"]] == True){
                                $class = "fi-check prereq-fulfilled";
                            }
                            elseif ($meetsPrereq[$prereq["prereq_id"]] == False){
                                $class = "fi-x prereq-unfulfilled";
                            }
                            else{
                                $class = "fi-minus prereq-unknown";
                            }
                            echo("<li><i class='$class'></i>");
                            switch ($prereq["type"]){
                                case "program_of_enrollment":
                                    echo ("Major: " . $prereq["program_of_enrollment"] . "</li>");
                                    break;
                                case "year_level":
                                    echo ("Year Level: " . $prereq["Year Level"] . "</li>");
                                    break;
                                default:
                                    echo ($prereq["type"] . "</li>");
                            }
                        }
                    }
                    catch (Exception $e){
                        echo("<li>Prerequisites Unavailable</li>");
                    }
                ?>
                <!-- <li><i class="fi-check prereq-fulfilled"></i>3rd Year Standing</li>
                <li><i class="fi-x prereq-unfulfilled"></i>Major: Software Engineering</li>
                <li><i class="fi-minus prereq-unknown"></i>Must be pretty cool</li> -->
                <!-- <li>...</li> -->
              </ul>
            </div>
          </div>
        </div>

          <?php // TODO make this only display for the professor associated with the class ?>
          <div class="large-12 medium-12 small-12 cell">
              <div class="card">
                  <div class="card-divider">
                      Enrolled Students
                  </div>
                  <div class="card-section">
                      <table class="student-table hover">
                          <?php
                            // TODO generate dynamically w/ php
                            // TODO add functionality for submitting grades using ajax
                          ?>
                          <tr>
                              <th><!-- Intentionally blank for picture --></th>
                              <th align="left">Name</th>
                              <th align="left">Major</th>
                              <th align="left">Status</th>
                              <th align="left">Grade</th>
                          </tr>

                          <tr>
                              <td><img src="https://ia.media-imdb.com/images/M/MV5BOTYxY2Y1NmQtNGY3Yi00OWEzLTgxY2UtZDgxYmM4YWQwODQ4XkEyXkFqcGdeQXVyNTM3MDMyMDQ@._V1_.jpg" alt="Dan Krutz"></td>
                              <td>Dan Krutz</td>
                              <td>Software Engineering</td>
                              <td>
                                  <i class="fi-check enrolled-check"></i>
                                  Enrolled
                              </td>
                              <td>
                                  <!-- TODO remove; The only difference between this form and the other one are that this form has no value for the 'grade' field, and the button text-->
                                  <form action="post">
                                      <input class="grade-number" type="number" name="grade" min=0 max=100 placeholder="0">
                                      <p class="grade-total" style="">/100</p>
                                      <input type="hidden" name="submit" value="submit_grade">
                                      <input class="button expanded submit-button" type="submit" value="Submit Grade">
                                  </form>
                              </td>
                          </tr>
                          <tr>
                              <td><img src="https://ia.media-imdb.com/images/M/MV5BODVjMWI3MDItNGZmYy00YTM4LWFiNjMtYjUzN2NhZWEwZmY5XkEyXkFqcGdeQXVyMTEzNzczMA@@._V1_.jpg" alt="Andy Meneely"></td>
                              <td>Andy Meneely</td>
                              <td>Software Engineering</td>
                              <td>
                                  <!-- TODO remove; Make sure you use the right icon associated with enrollment status -->
                                  <i class="fi-check enrolled-check"></i>
                                  Enrolled
                              </td>
                              <td>
                                  <form action="post">
                                      <input class="grade-number" type="number" name="grade" min=0 max=100 placeholder="0" value="5">
                                      <p class="grade-total" style="">/100</p>
                                      <input type="hidden" name="submit" value="submit_grade">
                                      <input class="button expanded submit-button" type="submit" value="Re-Grade">
                                  </form>
                              </td>
                          </tr>
                          <tr>
                              <td><img src="https://ia.media-imdb.com/images/M/MV5BMjE2MzQ0Mjg5MV5BMl5BanBnXkFtZTgwODkyMjUxMTI@._V1_SY1000_CR0,0,678,1000_AL_.jpg" alt="Larry Kiser"></td>
                              <td>Larry Kiser</td>
                              <td>Software Engineering</td>
                              <td>
                                  <i class="fi-minus waitlisted-minus"></i>
                                  Waitlisted (1)
                              </td>
                              <td>Not Enrolled</td>
                          </tr>
                      </table>
                  </div>
              </div>
          </div>
        
      </div>
      
    </div>

<script>
    $(".ajax").on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            // dataType : 'json',
            // contentType: "application/x-www-form-urlencoded",
            url: 'user_ajax_funcs.php',
            success: function (data) {
                if (data.includes("Success")) {
                    showMessage("success", data);
                    $(load_class_table());

                }
                else {
                    showMessage("failure", data);

                }
            },
            error: function (msg) {
                console.log(msg.responseText);
            }
        });
    });


    function uncheckaccess() {
        // if (this.checked) {
        $(document).ready(function () {
            $('.access').each(function (i, item) {
                $(item).attr('checked', false);
                console.log("Unselecting" + $(item).id)
                //$(item).checked = false;
            });
        });
    }

    function unchecknone() {
        $(document).ready(function () {
            document.getElementById('none').checked = false;
        });

    }

</script>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="bower_components/motion-ui/dist/motion-ui.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
