<?php
session_start();
if (!isset($_SESSION['user_id'])){

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=login.php\" />";
    exit();
}
else{
    $is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$_SESSION["user_id"]);
    $is_admin = json_decode($is_admin, true);
    $is_admin = $is_admin["is_admin"];

    if ($is_admin != "true") {
        echo "<center><h1 class=\"text-center\">Forbidden</h1></center>";
        exit();
    }
} ?>

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

        <div class="grid-x grid-padding-x" style="padding: 2%;">

            <?php
                function printAdminHeader($field) {
                    echo "<div class=\"small-10 medium-10 large-10 columns\">
                            <h3>Manage ".$field."</h3>
                            <ul style='list-style: none;'>
                                <li style='display:inline;'><a href=\"admin.php?view=system\">System</a> - </li>
                                <li style='display:inline;'><a href=\"admin.php?view=courses\">Courses</a> - </li>
                                <li style='display:inline;'><a href=\"admin.php?view=classes\">Classes</a> - </li>
                                <li style='display:inline;'><a href=\"admin.php?view=users\">Users</a></li>
                            </ul>
                          </div>";
                }

                switch ($_GET["view"]) {
                    case "system":
                        printAdminHeader("System");
                        include("admin_php/addSemester.php");
                        break;
                    case "courses":
                        printAdminHeader("Courses");
                        include("admin_php/addCourse.php");
                        include("admin_php/deleteCourse.php");
                        break;
                    case "classes":
                        printAdminHeader("Classes");
                        include("admin_php/addClass.php");
                        include("admin_php/accessRequests.php");
                        include("admin_php/deleteClass.php");
                        break;
                    case "users":
                        printAdminHeader("Users");
                        include("admin_php/approveProfessor.php");
                        include("admin_php/addAdmin.php");
                        include("admin_php/resetPrivileges.php");

                        break;
                    default:
                        // TODO put the no args text here. This will be the admin landing page
                        printAdminHeader("... nothing? (This will be updated later. Click the links below or use the nav bar to get to where you want to be.)");
                }
            ?>

        </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="bower_components/motion-ui/dist/motion-ui.js"></script>
    <script src="js/app.js"></script>

    <script>

        $(document).ready(function() {
            load_class_table();
            load_prof_requests();
            load_course_table();

            $(".form-delete").on('submit', function (e){
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
                            //this.parent.remove();
                            $(load_class_table())
                        }
                        else {
                            showMessage("failure", data);
                        }

                    },
                    error: function (msg) {
                        console.log(msg.responseText);
                    }
                });
                return false;
            });


            $(".form-delete-course").on('submit', function (e){
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
                            //this.parent.remove();
                            $(load_course_table())
                        }
                        else {
                            showMessage("failure", data);
                        }

                    },
                    error: function (msg) {
                        console.log(msg.responseText);
                    }
                });
                return false;
            });

            $(".text-center").on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data: $(this).serialize(),
                    // dataType : 'json',
                    // contentType: "application/x-www-form-urlencoded",
                    url: 'admin_ajax_funcs.php',
                    success: function (data) {
                        if (data.includes("Success") || data.includes("SUCCESS")) {
                            showMessage("success", data);
                            $(load_class_table());
                            $(load_course_table());
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


            $(".prof-decision").on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data: $(this).serialize(),
                    // dataType : 'json',
                    // contentType: "application/x-www-form-urlencoded",
                    url: 'admin_ajax_funcs.php',
                    success: function (data) {
                        if (data.includes("success")) {
                            showMessage("success", data);
                            load_prof_requests();
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

        });

        function load_class_table(){
            $('#loading-image').show();
            $.ajax({
                type : 'POST',
                url : 'admin_ajax_funcs.php',
                dataType : 'json',
                data: {'action':'get_classes'},
                contentType: "application/x-www-form-urlencoded",
                async : false,
                //beforeSend : function(){/*loading*/},

                success : function(result){
                    var buffer="";
                    $.each(result, function(index, val){
                        for(var i=0; i < val.length; i++){
                            var item = val[i];
                            buffer+="<tr name='course_listing' hidden>\
                                            <td><a href='course_view.php?class_id=" + item.course_id + "'>" + item.name + "</a></td>\
                                            <td>" + item.time + "</td> \
                                            <td> \
                                                 <form class='form-delete' method='post'>\
                                                     <input type='hidden' name='action' value='delete_class'> \
                                                     <input type='hidden' name='class_id' value='" + item.class_id + "'> \
                                                     <input class='button expanded rit-orange' type='submit' value='Delete'>\
                                                 </form>\
                                             </td>\
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

        function load_course_table(){
            $('#loading-image-delete').show();
            $.ajax({
                type : 'POST',
                url : 'admin_ajax_funcs.php',
                dataType : 'json',
                data: {'action':'get_courses'},
                contentType: "application/x-www-form-urlencoded",
                async : false,
                //beforeSend : function(){/*loading*/},

                success : function(result){
                    var buffer="";
                    $.each(result, function(index, val){
                        for(var i=0; i < val.length; i++){
                            var item = val[i];
                            buffer+="<tr name='course_listing' hidden>\
                                            <td>" + item.course_name + "</a></td>\
                                            <td>" + item.course_code + "</td> \
                                            <td>" + item.course_id + "</td> \
                                            <td> \
                                                 <form class='form-delete-course' method='post'>\
                                                     <input type='hidden' name='action' value='delete_course'> \
                                                     <input type='hidden' name='course_id' value='" + item.course_id + "'> \
                                                     <input class='button expanded rit-orange' type='submit' value='Delete'>\
                                                 </form>\
                                             </td>\
                                         </tr>";

                        }
                        $("#courses").empty();
                        $("#courses").append(buffer);
                    });
                },
                error: function (msg) {
                    console.log(msg.responseText);
                },
                complete: function(){
                    $('#loading-image-courses').hide();
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
                async : false,
                beforeSend : function(){/*loading*/},
                success : function(result){
                    var buffer="";
                    $.each(result, function(index, val){

                        for(var i=0; i < val.length; i++){ //iterate over list of mappings
                            var item = val[i];

                            buffer+="<tr>\
                                        <td>" + item[0] + "</td>\
                                        <td>\
                                            <form class='prof-decision'>\
                                                <input type='hidden' name='action' value='prof_approval'>\
                                                <input type='hidden' name='user_id' value=" + item[1] +">\
                                                <input type='hidden' name='decision' value='Approve'>\
                                                <input id='Approve-submit' name='decision' class=\"button expanded\" type=\"submit\" value=\"Approve\">\
                                            </form>\
                                            <form class='prof-decision'>\
                                                <input type='hidden' name='action' value='prof_approval'>\
                                                <input type='hidden' name='user_id' value=" + item[1] +">\
                                                <input type='hidden' name='decision' value='Deny'>\
                                                <input id='Approve-submit' name='decision' class=\"button expanded\" type=\"submit\" value=\"Deny\">\
                                            </form>\
                                        </td> \
                                </tr>";

                        }
                        $("#profs").empty();
                        $("#profs").append(buffer);
                    });
                },
                error: function (msg) {
                    console.log(msg.responseText);
                }

            });
        }
        // Load the filter for adding a new admin. This does nothing if that element is not loaded on the page
        instantiateFilter("user_filter", "user_listing", true);
        instantiateFilter("user_filter_2", "user_listing_2", true);
        instantiateFilter("course_filter", "course_listing", true)
        </script>
</body>
</html>
