<?php
  $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
  //$readfile = fread($myfile,filesize("LinkedIn/config.ini"));
  
  foreach($myfile as $line) {
    echo $line;
  }
  
  fclose($myfile);
?>