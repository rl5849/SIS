<?php
  echo "Config contents";
  $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
  $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
  
  $arr = explode("\n", $readfile);
  $client_id = explode("=", $arr[0])[1];
  $client_secret = explode("=", $arr[0])[1];

  echo $client_id;
  echo $client_secret;
  
  fclose($myfile);
?>