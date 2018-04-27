<?php

/**
 * Created by PhpStorm.
 * User: robertliedka
 * Date: 3/27/18
 * Time: 10:14 AM
 */

if (isset($_POST["action"])){
    switch ($_POST["action"]){
        case "add_class":
            add_class();
            break;
        case "add_course":
            add_course();
            break;
        case "delete_course":
            delete_course();
            break;
        case "add_semester":
            add_semester();
            break;
        case "delete_class":
            delete_class();
            break;
        case "get_classes":
            get_classes();
            break;
        case "get_prof_requests":
            get_prof_requests();
            break;
        case "prof_approval":
            prof_approval();
            break;
        case "get_courses":
            get_courses();
            break;
        case "make_admin":
            make_admin();
            break;
        case "reset_privileges":
            reset_privileges();
            break;
        case "get_student_classes_by_semester":
            if ($_POST['semester_id'] === 'favs'){
                get_student_favs();
            }else{
                get_student_classes_by_semester();
            }
            break;
    }
}

if (isset($_GET["action"])){
    switch ($_GET["action"]){
        case "get_classes":
            get_classes();
            break;
    }
}

function add_semester() {
    $url_params = urlencode($_POST['semester_code']);
    $result = file_get_contents("http://127.0.0.1:5002/AddSemester?semester_code=" . $url_params);
    $result = json_decode($result, true);
    if ($result == "SUCCESS"){
        echo "Successfully added Course";
    }
    else{
        echo "Failed to add Course";
    }
}

function add_class() {

    $url_params = $_POST['course_id'] . "&time=" . urlencode($_POST['time']) . "&room_number=" . $_POST['room_number'] . "&prof_id=" . $_POST['professor_id']. "&capacity=" . $_POST['capacity'];
    $result = file_get_contents("http://127.0.0.1:5002/AddClass?course_id=" . $url_params);
    $result = json_decode($result, true);
    if ($result == "SUCCESS"){
        echo "Successfully added Section";
    }
    else{
        echo "Failed to add Section";
    }
}

function add_course() {
    $url_params = urlencode($_POST['course_name']) . "&course_code=" . urlencode($_POST['course_code']) . "&course_credits=" . $_POST['course_credits'] . "&course_description=" . urlencode($_POST['course_description']);
    $result = file_get_contents("http://127.0.0.1:5002/AddCourse?course_name=" . $url_params);
    $result = json_decode($result, true);
    if ($result == "SUCCESS"){
        echo "Successfully added Course";
    }
    else{
        echo "Failed to add Course";
    }
}
function delete_course() {

    $url_params = $_POST['course_id'];
    $result = file_get_contents("http://127.0.0.1:5002/DeleteCourse?course_id=" . $url_params);
    $result = json_decode($result, true);
    if ($result == "SUCCESS"){
        echo "Successfully deleted class";
    }
    else{
        echo "Could not delete class: " . $_POST['course_id'];
    }
}

function delete_class() {

    $url_params = $_POST['class_id'];
    $result = file_get_contents("http://127.0.0.1:5002/DeleteClass?class_id=" . $url_params);
    $result = json_decode($result, true);
    if ($result == "SUCCESS"){
        echo "Successfully deleted class";
    }
    else{
        echo "Could not delete class: " . $_POST['class_id'];
    }
}

function get_classes() {
    $course_list = file_get_contents("http://127.0.0.1:5002/GetClasses");
    echo $course_list;
}

function get_courses() {
    $course_list = file_get_contents("http://127.0.0.1:5002/GetCourses");
    echo $course_list;
}


function get_prof_requests() {
    $list = file_get_contents("http://127.0.0.1:5002/GetProfessorRequests");
    echo $list;
}

function prof_approval() {
    if ($_POST['decision'] == "Approve"){
        $result = file_get_contents("http://127.0.0.1:5002/ApproveProfRequest?user_id=" . $_POST['user_id']);
        $result = json_decode($result, true);
        if($result == "SUCCESS"){
            echo "Professor status approval successful";
        }
        else{
            echo "Could not approve professor status";
        }

    }
    else{
        $result = file_get_contents("http://127.0.0.1:5002/DeleteProfRequest?user_id=" . $_POST['user_id']);
        $result = json_decode($result, true);
        if($result == "SUCCESS"){
            echo "Professor request deleted successfully";
        }
        else{
            echo "Could not delete request";
        }
    }
}

function get_student_classes_by_semester() {
    if(!isset($_POST['semester_id'])){
        echo [];
        return;
    }

    $list = file_get_contents("http://127.0.0.1:5002/GetStudentsClassesForSemester?user_id=" . $_POST['user_id'] . "&semester_id=" . $_POST['semester_id']);
    echo $list;
}

function get_student_favs() {
    $list = file_get_contents("http://127.0.0.1:5002/GetFavoritedClasses?user_id=" . $_POST['user_id']);
    echo $list;
}

function make_admin() {
    $result = file_get_contents("http://127.0.0.1:5002/MakeAdmin?user_id=" . $_POST['user_id']);
    $result = json_decode($result, true);
    if($result == "SUCCESS"){
        echo "Successfully granted admin privileges";
    }
    else{
        echo "Could not grant admin privileges";
    }
}


function reset_privileges() {
    $result = file_get_contents("http://127.0.0.1:5002/ResetPrivileges?user_id=" . $_POST['user_id']);
    $result = json_decode($result, true);
    if($result == "SUCCESS"){
        echo "Successfully reset privileges";
    }
    else{
        echo "Could not reset privileges, user still holds status";
    }
}