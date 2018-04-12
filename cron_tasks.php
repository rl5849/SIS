<?php
/**
 * Created by PhpStorm.
 * User: robertliedka
 * Date: 4/12/18
 * Time: 10:07 AM
 */
date_default_timezone_set('America/New_York');

try {
    //Call the API
    $result = @file_get_contents("http://127.0.0.1:5002/EnrollFromWaitlist");
    $result = json_decode($result, true);
} catch (Exception $e) {
    $result = $e->getMessage();
}

//Get the execution date attach to log entry
$entry = date("Y-m-d H:i:s") . " -- " . $result . "\n";


//Open the log file and write the execution result
$log = fopen("cron.log", "a") or die("Unable to open file!");
fwrite($log, $entry);
fclose($log);

