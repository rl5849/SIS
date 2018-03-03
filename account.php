<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - My Account</title>
    <link rel="stylesheet" href="css/app.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>
	<div w3-include-html="nav.php"></div>
  
  <!-- Load Nav Bar -->
    <div id="nav-placeholder"></div>

    <script>
    $(function(){
      $("#nav-placeholder").load("nav.php");
    });
    </script>
    <!-- End load Nave Bar -->
	
	<div class="grid-container">
  
    <div class="grid-x grid-padding-x" style="padding-top:2%;">
  
      <div class="large-4 medium-4 small-4 cell">
        <img src="https://i0.wp.com/radaronline.com/wp-content/uploads/2017/05/betty-white-secret-suitor-split-pp.jpg?fit=640%2C420&ssl=1" alt="my_profile_image">
      </div>
      <div class="large-4 medium-4 small-4 cell">
        <ul class="profile-list">
			<?php
				$student_id = 1;
				$student_info = file_get_contents("http://127.0.0.1:5002/GetUser?student_id=".$student_id);
				$student_info = json_decode($student_info, true);
			?>
		  <li><?php echo ($student_info["student_info"][0]["student_name"])?></li>
		  <li><?php echo ($student_info["student_info"][0]["date_of_birth"])?></li>
		  <li><?php echo ($student_info["student_info"][0]["graduation_year"])?></li>
        </ul>
      </div>
      <div class="large-2 medium-2 small-3 cell">
        <ul class="profile-list">
          <p><input type="submit" href="https://www.linkedin.com"class="button expanded rit-orange" value="LinkedIn"></input></p>
          <li>GPA</li>
        </ul>
      </div>
      
    </div>
  
    <div class="grid-x grid-padding-x" style="padding-top: 2%;">
      <div class="large-12 medium-12 small-12 columns">
      <ul class="horizontal tabs" data-tabs id="course-tabs">
        <li class="tabs-title favorited-classes-title"><a href="#panel1v">Favorited</a></li>
        <li class="tabs-title is-active"><a href="#panel1v" aria-selected="true">Current Semester</a></li>
        <li class="tabs-title"><a href="#panel2v">Fall 2017</a></li>
        <li class="tabs-title"><a href="#panel3v">Summer 2017</a></li>
        <li class="tabs-title"><a href="#panel4v">Spring 2017</a></li>
        <li class="tabs-title"><a href="#panel5v">Fall 2016</a></li>
      </ul>
      </div>
      <div class="large-12 medium-12 small-12 cell">
        <div class="tabs-content" data-tabs-content="course-tabs">
          <div class="tabs-panel is-active" id="panel1v">
            <table class="hover">
              <tr>
                <!--For loop for query here, delete everything else-->
                 <?php






                  ?>
                <th>Course</th>
                <th>Section</th>
                <th>Time</th>
                <th>Instructor</th>
                <th>Room</th>
              </tr>
              
              <tr>
                <td><a href="course_view.html">SWEN-344</a></td>
                <td>01</td>
                <td>10:10 am - 11:05 am</td>
                <td>Danny Boye</td>
                <td>GOL 1520</td>
              </tr>
              <tr>
                <td>SWEN-344</td>
                <td>01</td>
                <td>10:10 am - 11:05 am</td>
                <td>Danny Boye</td>
                <td>GOL 1520</td>
              </tr>
              <tr>
                <td>SWEN-344</td>
                <td>01</td>
                <td>10:10 am - 11:05 am</td>
                <td>Danny Boye</td>
                <td>GOL 1520</td>
              </tr>
              <tr>
                <td>SWEN-344</td>
                <td>01</td>
                <td>10:10 am - 11:05 am</td>
                <td>Danny Boye</td>
                <td>GOL 1520</td>
              </tr>
              <tr>
                <td>SWEN-344</td>
                <td>01</td>
                <td>10:10 am - 11:05 am</td>
                <td>Danny Boye</td>
                <td>GOL 1520</td>
              </tr>
              
            <table>
          </div>
          <div class="tabs-panel" id="panel2v">
          </div>
          <div class="tabs-panel" id="panel3v">
          </div>
          <div class="tabs-panel" id="panel4v">
          </div>
          <div class="tabs-panel" id="panel5v">
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="bower_components/jquery/dist/jquery.js"></script>
  <script src="bower_components/what-input/dist/what-input.js"></script>
  <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
  <script src="js/app.js"></script>
  </body>
</html>
