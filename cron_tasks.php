<?php
/**
 * Created by PhpStorm.
 * User: robertliedka
 * Date: 4/12/18
 * Time: 10:07 AM
 */
date_default_timezone_set('America/New_York');


//Call the API
$result = file_get_contents("http://127.0.0.1:5002/EnrollFromWaitlist");
$result = json_decode($result, true);

//Get the execution date
$date = new DateTime();
$entry = $date->getTimestamp() . "--" . $result;


//Open the log file and write the execution result
$myfile = fopen("cron.log", "w") or die("Unable to open file!");
fwrite($myfile, $entry);
fclose($myfile);

