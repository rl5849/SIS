<?php
/**
 * Created by PhpStorm.
 * User: robertliedka
 * Date: 4/5/18
 * Time: 2:59 PM
 */


if (isset($_POST["action"])){
    switch ($_POST["action"]){
        case "enroll":
            if ($_POST["enroll"] == 0){
                unenroll();

            }
            else{
                enroll();
            }
            unset($_POST["enroll"]);
            break;
        case "favorite":
            if ($_POST["favorite"] == 0){
                favorite();

            }
            else{
                unfavorite();
            }
            unset($_POST["favorite"]);
            break;
    }
}


function enroll() {
    $enroll = file_get_contents("http://127.0.0.1:5002/EnrollStudent?class_id=" . $_POST['class_id'] . "&user_id=" . $_POST['user_id']);
    $enroll = json_decode($enroll, true);
    if ($enroll == "SUCCESS"){
        echo "<script>showMessage(\"success\", \"Successfully Enrolled in class\");</script>";
    }
    else if ($enroll = "WAITLISTED"){
        echo "Class is full. Successfully added to waitlist";
    }
    else{
        echo "Failed to Enroll in class";
    }
}


function unenroll() {
    $unenroll = file_get_contents("http://127.0.0.1:5002/DropStudent?class_id=" . $_POST['class_id'] . "&user_id=" . $_POST['user_id']);
    $unenroll = json_decode($unenroll, true);
    if ($unenroll == "SUCCESS"){
        echo "success\", \"Successfully Dropped class";
    }
    else{
        echo "Failed to Drop class";
    }
}

function favorite() {
    $favorite = file_get_contents("http://127.0.0.1:5002/FavoriteClass?class_id=" . $_POST['class_id'] . "&user_id=" .  $_POST['user_id']);
    $favorite = json_decode($favorite, true);
    if ($favorite == "SUCCESS"){
        echo "Successfully Favorited class";
    }
    else{
        echo "Failed to Favorite class";
    }
}


function unfavorite() {
    $unfavorite = file_get_contents("http://127.0.0.1:5002/UnfavoriteClass?class_id=" . $_POST['class_id'] . "&user_id=" .  $_POST['user_id']);
    $unfavorite = json_decode($unfavorite, true);
    if ($unfavorite == "SUCCESS"){
        echo "Successfully Dropped class";
    }
    else{
        echo "Failed to Drop class";
    }
}