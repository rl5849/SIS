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
        //case "add_semester":
        //    add_semester();
        case "delete_class":
            delete_class();
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


