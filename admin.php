<?php
session_start();
//if (!isset($_SESSION['user_id'])){
//
//    echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
//    exit();
//}
//else{
    $_SESSION["user_id"] = 67;
    $is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$_SESSION["user_id"]);
    $is_admin = json_decode($is_admin, true);
    $is_admin = $is_admin["is_admin"];

    if ($is_admin != "true") {
        echo "<center><h1 class=\"text-center\">Forbidden</h1></center>";
        exit();
//    }
} ?>

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
    <!-- Load Nav Bar and Callouts -->
    <div id="nav-placeholder"></div>
    <div id="callouts-placeholder"></div>
    <!-- End load Nave Bar and Callouts -->
    <div class="grid-container">

        <div class="grid-x grid-padding-x" style="padding-top:2%;">

            <div class="small-12 medium-3 large-3 columns">
                <div>
                    <form class="callout text-center" method="post">
                        <h4>Add a New Class Section</h4>
                        <div class="floated-label-wrapper">
                            <label for="class_new_class">Class</label>
                            <input type="hidden" name="action" value="add_class">
                            <select name="course_id" id="class_new_class">

                                <?php
                                $course_list = file_get_contents("http://127.0.0.1:5002/GetCourses");
                                $course_list = json_decode($course_list, true);

                                foreach ($course_list["courses"] as $course){
                                    ?>
                                    <option value="<?php echo $course["course_id"]; ?>"><?php echo $course["course_name"]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="professor">Professor</label>
                            <select name="professor_id" id="professor">

                                <?php
                                $prof_list = file_get_contents("http://127.0.0.1:5002/GetProfs");
                                $prof_list = json_decode($prof_list, true);

                                foreach ($prof_list["profs"] as $prof){
                                    ?>
                                    <option value="<?php echo $prof[1]; ?>"><?php echo $prof[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="capacity_new_class">Capacity</label>
                            <input type="number" name="capacity" id="capacity_new_class" min="1" max="300" placeholder="Capacity">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="room_number_new_class">Room Number</label>
                            <input type="number" name="room_number" id="room_number_new_class" min="1" max="1000" placeholder="Room Number">
                        </div>
                        <div class="floated-label-wrapper">
                            <label for="time_new_class">Time</label>
                            <input type="text" name="time" id="time_new_class" placeholder="Time (eg. 'TuTh 10:00-10:55')">
                        </div>
                        <input type="submit" class="button expanded rit-orange" value="Create Section">
                    </form>
                    <!-- End new form -->
                </div>
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                    <input type="hidden" name="action" value="add_course">
                <h4>Add Course</h4>
                <div class="floated-label-wrapper">
                    <label for="course_name">Course Name</label>
                    <input type="text" id="course_name" name="course_name" placeholder="Course Name">
                </div>
                <div class="floated-label-wrapper">
                    <label for="course_code">Course Code</label>
                    <input type="text" id="course_code" name="course_code" placeholder="Course Code">
                </div>
                <div class="floated-label-wrapper">
                    <label for="course_credits">Course Credits</label>
                    <input type="number" name="course_credits" id="credits" min="1" max="7" placeholder="Course Credits">
                </div>
                <div class="floated-label-wrapper">
                    <label for="course_description">Course Description</label>
                    <input type="text" id="course_description" name="course_description" placeholder="Course Description">
                </div>
                <input class="button expanded" type="submit" value="Create Course">
                </form>
                <!-- End new form -->
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                    <input type="hidden" name="action" value="add_semester">
                <h4>Add a New Semester</h4>
                <div class="floated-label-wrapper">
                    <label for="semester_code">Semester Code</label>
                    <input type="text" id="semester_code" name="semester_code" placeholder="Semester Code">
                </div>
                <input class="button expanded" type="submit" value="Create Semester">
                </form>
                <!-- End new form -->
            </div>
            <div class="small-12 medium-3 large-3 columns">
                <!-- Start new form -->
                <form class="callout text-center" method="post">
                    <input type="hidden" name="action" value="add_semester">
                    <h4>Approve Professor Status</h4>
                    <div class="floated-label-wrapper">
                        <table class="hover">
                            <tbody id="profs">
                            <!--                              Javascript builds table here-->
                            </tbody>
                        </table>
                    </div>

                </form>
                <!-- End new form -->
            </div>
            <div class="small-12 medium-6 large-6 columns">
<!--                <div class="callout text-center" >-->
                    <h4>Delete Class</h4>
                    <div class="floated-label-wrapper">
                        <table class="hover">
                            <tbody id="classes">
<!--                              Javascript builds table here-->
                            </tbody>
                        </table>
                    </div>
<!--            </div>-->
        </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="bower_components/motion-ui/dist/motion-ui.js"></script>
    <script src="js/app.js"></script>

    <script>
        makeNav();
        makeCallouts();




        $(document).ready(function() {

            $(".form_delete").on('submit',function (e) {
                console.log("Making ajax delete");
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data: $(this).serialize(),
                    // dataType: 'json',
                    // contentType: "application/x-www-form-urlencoded",
                    url: 'admin_ajax_funcs.php',
                    success: function (data) {
                        if (data.includes("Success")) {
                            showMessage("success", data);
                            this.parent.remove();
                            //$(load_class_table())
                        }
                        else {
                            showMessage("failure", data);
                        }

                    },
                    error: function (msg) {
                        alert(msg.responseText);
                    }
                });
                return false;
            });

            function load_class_table(){
                console.log("Building table");
                $.ajax({
                    type : 'POST',
                    url : 'admin_ajax_funcs.php',
                    dataType : 'json',
                    data: {'action':'get_classes'},
                    contentType: "application/x-www-form-urlencoded",
                    async : true,
                    //beforeSend : function(){/*loading*/},

                    success : function(result){
                        var buffer="";
                        $.each(result, function(index, val){

                            for(var i=0; i < val.length; i++){
                                var item = val[i];
                                buffer+="<tr>\
                                            <td><a href='course_view.php?class_id=" + item.course_id + "'>" + item.name + "</a></td>\
                                            <td>" + item.time + "</td> \
                                            <td> \
                                                 <form class='form_delete' method='post'>\
                                                     <input type='hidden' name='action' value='delete_class'> \
                                                     <input type='hidden' name='class_id' value='" + item.id + "'> \
                                                     <input id='delete-class' class='button expanded rit-orange' type='submit' value='Delete'>\
                                                 </form>\
                                             </td>\
                                         </tr>";
                                $("#classes").append(buffer);
                            }
                        });
                    },
                    error: function (msg) {

                        alert(msg.responseText);
                    }

                });

            }



        function load_prof_requests(){
            $.ajax({
                type : 'POST',
                url : 'admin_ajax_funcs.php',
                dataType : 'json',
                data: {'action':'get_prof_requests'},
                contentType: "application/x-www-form-urlencoded",
                async : true,
                beforeSend : function(){/*loading*/},
                success : function(result){
                    var buffer="";
                    $.each(result, function(index, val){

                        for(var i=0; i < val.length; i++){ //iterate over list of mappings
                            var item = val[i];

                            buffer+="<tr>\
                                        <td>" + item[0] + "</td>\
                                        <td>\
                                            <form class=confirm_prof>\
                                                <input type='hidden' name='action' value='approve'>\
                                                <input type='hidden' name='prof' value=" + item[1] +">\
                                                <input class=\"button expanded\" type=\"submit\" value=\"Approve\">\
                                             </form>   \
                                        </td> \
                                        <td>\
                                            <form class=confirm_prof> \
                                                <input type='hidden' name='action' value='approve'> \
                                                <input type='hidden' name='prof' value=\"" + item[1] + "\">\
                                                <input class=\"button expanded\" type=\"submit\" value=\"Deny\">\
                                            </form>\
                                        </td> \
                                </tr>";

                        }
                        $("#profs").append(buffer);
                    });
                },
                error: function (msg) {
                    alert(msg.responseText);
                }

            });
        }






            $(".text-center").on('submit', function (e) {
                console.log("Making ajax generic");
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data: $(this).serialize(),
                    // dataType : 'json',
                    // contentType: "application/x-www-form-urlencoded",
                    url: 'admin_ajax_funcs.php',
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

                        alert(msg.responseText);
                    }
                });
            });

        $(load_prof_requests());
        $(load_class_table());
        });




    </script>
</body>
</html>
