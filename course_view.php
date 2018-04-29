<?php
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else {
    $user_id = NULL;
    $is_admin = false;
    $is_prof = false;
}

//used to check if a professor type
if($user_id){
    $is_prof = file_get_contents("http://127.0.0.1:5002/CheckIfProfessor?id=".$user_id);
    $is_prof = json_decode($is_prof, true);
    $is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$user_id);
    $is_admin = json_decode($is_admin, true);
}

$is_student = (!$is_admin["is_admin"] && !$is_prof["is_prof"] && $user_id);

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
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=course_list.php\" />";
            exit();
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
                $enrollment_status = 0;
                break;
            case "WAITLISTED":
                $enrollment_status_msg = "Drop Waitlist";
                $enrollment_status = 0;
                break;
            default:
                $enrollment_status_msg = "Enroll";
                $enrollment_status = 1;
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

        $enrolled_students = file_get_contents("http://127.0.0.1:5002/GetStudentsByClassId?class_id=" . $class_id);
        $enrolled_students = json_decode($enrolled_students, true);


        $prerequisites = file_get_contents("http://127.0.0.1:5002/GetPrereqs?course_id=" . $class_info["class_info"][0]["course_id"]);
        $prerequisites = json_decode($prerequisites, true);
        $prerequisites = $prerequisites["prereqs"];

        if($is_student){

            $meetsPrereq = array();
            foreach ($prerequisites as $prereq){
                $meetsPrereq[$prereq['prereq_id']] = file_get_contents("http://127.0.0.1:5002/CheckPrereq?prereq_id=" . $prereq['prereq_id'] . "&student_id=" . $user_id);
                $meetsPrereq[$prereq['prereq_id']] = json_decode($meetsPrereq[$prereq['prereq_id']], true);
                $meetsPrereq[$prereq['prereq_id']] = $meetsPrereq[$prereq['prereq_id']]["meets_prereq"];
                if ($meetsPrereq[$prereq['prereq_id']] == False){
                    $meetsAllPrereqs = False;
                }
            }
        }

    ?>

	</div>
    <div class="grid-container">
	  
      <div class="grid-x grid-padding-x" style="padding-top:2%;">

        <div class="large-10 medium-10 small-9 cell">
            <table>
                <tr>
                    <td>
                        Course Name:
                    </td>
                    <td>
                        <?php echo ($course_info["course_info"][0]["course_name"])?>
                    </td>
                    <td>
                        Room Number:
                    </td>
                    <td>
                        <?php echo ($class_info["class_info"][0]["room_number"]) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Course Code:
                    </td>
                    <td>
                        <?php echo ($class_info["class_info"][0]["room_number"]) ?> <!-- course code not in db, use course ID?-->
                    </td>
                    <td>
                        Time:
                    </td>
                    <td>
                        <?php echo ($class_info["class_info"][0]["time"]) ?> <!-- needs getclassinfo -->
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        Professor Name:
                    </td>
                    <td>
                        <?php echo ($prof_info["professor_name"]) ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="large-2 medium-2 small-3 cell">
          <ul class="profile-list">

              <?php //Only show buttons if they are logged in
                if ($user_id) {
                    if(($is_admin["is_admin"] != true) && ($is_prof["is_prof"] != true)){
					?>
					
                    <input type="button" class="button expanded rit-orange favorite" value="<?php echo $favorite_status_msg; ?>">
                    <input type="submit" class="button expanded rit-orange enroll" value="<?php echo $enrollment_status_msg; ?>">
                    <?php
                }
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
                          <li>Wait List: <?php echo count($wait_list) ?>  </li> <!-- needs waitlist capacity -->
                          <!-- <li>...</li> -->
                      </ul>
                  </div>
              </div>
          </div>

          <?php if ($is_student && $user_id) { ?>

          <div class="large-4 medium-4 small-4 cell">
              <div class="card">
                  <div class="card-divider">
                      Special Access Required
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
          <?php } ?>

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
                    if (sizeof($prerequisites) > 0){
                        foreach ($prerequisites as $prereq ){
                            if($is_student){ 
                                //only display icons for students
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
                            }else{
                                echo("<li>");
                            }

                            switch ($prereq["type"]){
                                case 0:
                                    echo ("Major: " . $prereq["major_name"] . "</li>");
                                    break;
                                case 1:
                                    echo ("Year Level: " . $prereq["year_level"] . "</li>");
                                    break;
                                case 2:
                                    echo ("Must have taken and passed " . $prereq["prereq_course"] . "</li>");
                                    break;
                                default:
                                    echo ("</li>");
                            }
                        }
                    }
                    else{
                        echo("<li>No Prerequisites</li>");
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

          <?php
			//$is_enroll = file_get_contents("http://127.0.0.1:5002/CheckEnrollmentStatus?class_id=" . $class_id . "&user_id=" . $user_id);
			//$is_enroll = json_decode($is_enroll, true);
			if(($prof_id == $user_id) || ($is_admin["is_admin"] == True) || ($enrollment_status == 0)){
			?>

              <div class="large-12 medium-12 small-12 cell">
                  <div class="card">
                      <div class="card-divider">
                          Enrolled Students
                      </div>
                      <div class="card-section">
                          <table class="student-table hover">
						
                              <tr>
                                  <th><!-- Intentionally blank for picture --></th>
                                  <th align="left">Name</th>
                                  <th align="left">Major</th>
								  <?php if(($is_prof["is_prof"] == True) || ($is_admin["is_admin"] == True)){ ?>
									<th align="left">Status</th>
								  <?php } ?>
								  <?php if(($is_prof["is_prof"] == True && ($prof_id == $user_id)  )|| ($is_admin["is_admin"] == True)) { ?>
                                  <th align="left">Grade</th>
								  <?php } ?>
                              </tr>

                              <?php
                              // TODO generate dynamically w/ php
                              // TODO add functionality for submitting grades using ajax
                              foreach ($enrolled_students['enrolled'] as $enroll_stud){
								  $curr_stud = file_get_contents("http://127.0.0.1:5002/GetStudentInfo?student_id=".$enroll_stud["user_id"]);
								  $curr_stud = json_decode($curr_stud,true);
                                  //Add each enrolled student to teh table with each field
                                    ?>
									<tr>
										<td><img src ='<?php echo $curr_stud["student_info"][0]["profile_pic"] ?>' onerror="this.src='images/user_profile_placeholder.png'"></td>
										<td><?php echo $curr_stud["student_info"][0]["student_name"] ?></td>
										<td><?php echo ($curr_stud["student_info"][0]["major_name"] ? $curr_stud["student_info"][0]["major_name"] : "Not Specified");  ?></td>
										<?php if(($is_prof["is_prof"] == True) || ($is_admin["is_admin"] == True)){ ?>
										<td>
											<i class="fi-check enrolled-check"></i>
											Enrolled
										</td>
										<?php } ?>
										<?php if(($is_prof["is_prof"] == True && ($prof_id == $user_id)  )|| ($is_admin["is_admin"] == True)) { ?>
										  <td>
											<form action="post">
											<input class="grade-number" type="number" name="grade" min=0 max=100 placeholder= "0" value=<?php echo $curr_stud["grade"] ?>>
											<p class="grade-total" style="">/100</p>
											<input type="hidden" name="submit" value="submit_grade">
											<input class="button expanded submit-button subGrade" type="submit" value="Submit Grade">
										  </td>
                                      </form>
										<?php } ?>
									</tr>
							  <?php } ?>
							  <?php foreach ($enrolled_students["waitlisted"] as $enroll_stud){
								  $curr_stud = file_get_contents("http://127.0.0.1:5002/GetStudentInfo?student_id=".$enroll_stud["user_id"]);
								  $curr_stud = json_decode($curr_stud,true);
								  ?>
									<tr>
										<td><img src ='<?php echo $curr_stud["student_info"][0]["profile_pic"] ?>' onerror="this.src='images/user_profile_placeholder.png'"></td>
										<td><?php echo $curr_stud["student_info"][0]["student_name"] ?></td>
										<td><?php echo ($curr_stud["student_info"][0]["major_name"] ? $curr_stud["student_info"][0]["major_name"]  : "Not Specified"); ?></td>
										<?php if(($is_prof["is_prof"] == True) || ($is_admin["is_admin"] == True)){ ?>
											<td>
												<i class="fi-minus waitlisted-minus"></i>
												Waitlisted (<?php echo ($enroll_stud["position"] +1) ?>)
											</td>
										<?php } ?>
										<?php if(($is_prof["is_prof"] == True && (prof_id == user_id)  )|| ($is_admin["is_admin"] == True)) { ?>
											<td>Not Enrolled</td>
										<?php } ?>
									</tr>
							  <?php } ?>
                          </table>
                      </div>
                  </div>
              </div>
        <?php  } ?>
      </div>
      
    </div>

<script>
    //Ajax for forms
    $(".ajax").on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            url: 'user_ajax_funcs.php',
            success: function (data) {
                if (data.includes("Success")) {
                    showMessage("success", data);
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

    //Ajax for favorite
    $('.favorite').on('click', function () {
        var action = "";
        if ($(this).attr('value') ==  'Favorite'){
            action = 0;//0 means you're going to favorite the class
        }else { // else text is Unfavorite
            action = 1;
        }
        //Make the request
        var success = $.ajax({
            type: 'POST',
            data: {'action': 'favorite', 'user_id' : "<?php echo $user_id;?>", 'class_id' : <?php echo $class_id;?>, 'favorite' : action},
            url: 'user_ajax_funcs.php',
            success: function (data) {
                if (data.includes("Success")) {
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
        if (success) {
            if ($(this).attr('value') == 'Favorite') {
                $(this).attr('value', 'Unfavorite');
            } else {
                $(this).attr('value', 'Favorite');
            }
        }
    });

    //Ajax for enroll
    $('.enroll').on('click', function () {
        var action = "";
        if ($(this).attr('value') ==  'Enroll'){
            action = 1;
        }else { // else text is Unfavorite
            action = 0;//0 means you're going to unenroll in the class
        }
        //Make the request
        var success = false;
            $.ajax({
            type: 'POST',
            data: {'action': 'enroll', 'user_id' : "<?php echo $user_id;?>", 'class_id' : <?php echo $class_id;?>, 'enroll' : action, 'course_id' : <?php echo $course_id?>},
            url: 'user_ajax_funcs.php',
            success: function (data) {
                if (data.includes("Success")) {
                    showMessage("success", data);
                    success = true;
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
            if ($(this).attr('value') ==  'Enroll'){
                $(this).attr('value', 'Drop');
            }else {
                $(this).attr('value', 'Enroll');
            }
        }

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
	
	//AJAX for submit Grade
    $('.subGrade').on('click', function () {
        var action = "";

        //Make the request
        var success = $.ajax({
            type: 'POST',
            data: {
                'action': 'submitGrade',
                'user_id': "<?php echo $user_id;?>",
                'class_id': <?php echo $class_id;?>,
                'submitGrade': action
            },
            url: 'user_ajax_funcs.php',
            success: function (data) {
                if (data.includes("Success")) {
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
    });
	

</script>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="bower_components/motion-ui/dist/motion-ui.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
