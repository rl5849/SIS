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
        case "RequestSpecialAccess":
            RequestSpecialAccess();
            break;

        case "RequestProfStatus":
            RequestProfStatus();
            break;
        case "submitGrade":
            submitGrade();
            break;
    }
}


function enroll() {
    #check prereqs
    $prerequisites = file_get_contents("http://127.0.0.1:5002/GetPrereqs?course_id=" . $_POST["course_id"]);
    $prerequisites = json_decode($prerequisites, true);
    $prerequisites = $prerequisites['prereqs'];

    foreach ($prerequisites as $prereq){
        $meetsPrereq = file_get_contents("http://127.0.0.1:5002/CheckPrereq?prereq_id=" . $prereq['prereq_id'] . "&student_id=" . $_POST['user_id']);
        $meetsPrereq = json_decode($meetsPrereq, true);
        $meetsPrereq = $meetsPrereq["meets_prereq"];
        if ($meetsPrereq == False){
            echo "Some prerequisites for this course have not been met. Failed to Enroll in class";
            return;
        }
    }

    $enroll = file_get_contents("http://127.0.0.1:5002/EnrollStudent?class_id=" . $_POST['class_id'] . "&user_id=" . $_POST['user_id']);
    $enroll = json_decode($enroll, true);
    if ($enroll == "SUCCESS"){
        echo "Successfully Enrolled in class";
    }
    else if ($enroll == "WAITLISTED"){
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
        echo "Successfully dropped class";
    }
    else{
        echo "Failed to drop class";
    }

    //call enroll from waitlist to move up other students
    file_get_contents("http://127.0.0.1:5002/EnrollFromWaitlist");
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
        echo "Successfully Unfavorited class";
    }
    else{
        echo "Failed to Unfavorite class";
    }
}
function RequestSpecialAccess() {
    //url encode post data and attach
    $requests = "";
    if(isset($_POST['hearing'])){
        unset($_POST['hearing']);
        $requests .= "hearing,";
    }
    if(isset($_POST['test_time'])){
        unset($_POST['test_time']);
        $requests .= "test_time,";
    }
    if(isset($_POST['note_taking'])){
        unset($_POST['note_taking']);
        $requests .= "note_taking,";
    }
    if(isset($_POST['none'])){
        unset($_POST['none']);
        $requests .= "none";
    }

    $result = file_get_contents("http://127.0.0.1:5002/RequestSpecialAccess?class_id=" . $_POST['class_id'] . "&user_id=" .  $_POST['user_id'] . "&requests=" . $requests);
    $result = json_decode($result, true);
    if ($result == "SUCCESS"){
        echo "Successfully Requested Special Access";
    }
    else{
        echo "Failed to Request Access";
    }
}

function RequestProfStatus(){
    $results = file_get_contents("http://127.0.0.1:5002/RequestProfessorApproval?user_id=".$_POST["user_id"]);
    $results = json_decode($results);

    if ($results == "SUCCESS"){
        echo "A request has been made to give you professor status within the system.";
    }
    else{
        echo "Failed to Request Access";
    }
}

function submitGrade(){
	$results = file_get_contents("http://127.0.0.1:5002/setGrade?student_id=" .$_POST["student_id"] . "&class_id=" . $_POST["class_id"] . "&grade=" . $_POST["grade"]);
	$results = json_decode($results, true);
	
	if ($results == "SUCCESS"){
        echo "Successfully submitted grade!";
    }
    else{
        echo "Failed to Submit Grade";
    }
}
