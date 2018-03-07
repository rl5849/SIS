&1F914;

<?php
  echo "Config contents";
  $myfile = fopen("LinkedIn/config.ini", "r") or die("Unable to open file!");
  $readfile = fread($myfile,filesize("LinkedIn/config.ini"));
  
  echo $readfile;
  
  foreach($myfile as $line) {
    echo $line;
  }
  
  fclose($myfile);
?>