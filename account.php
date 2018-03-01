<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - My Account</title>
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
	<div class="top-bar">
	  <div class="top-bar-left">
		<ul class="dropdown menu" data-dropdown-menu>
		  <li class="menu-text">SIS++</li>
		  <li><a href="course_list.php">Course List</a></li>
		  <li>
			<a href="#">Admin</a>
			<ul class="menu vertical">
			  <li><a href="#">Admin Action 1</a></li>
			  <li><a href="#">Admin Action 2</a></li>
			  <li><a href="#">Admin Action 3</a></li>
			</ul>
		  </li>
		</ul>
	  </div>
	  <div class="top-bar-right">
		<ul class="dropdown menu" data-dropdown-menu>
      <!--<form action="course_search.php" method="get">-->
        <ul class="menu">
          <li><input type="search" placeholder="Class Search"></li>
          <li><button type="submit" class="button" formaction="course_search.php">Search</button></li> <!-- Search functionality here-->
        </ul>
      <!--<form>-->
			<li>
				<a href="account.php">Welcome, Big boyyy</a> <!-- Name here -->
			</li>
			<li>
				<a href="#"></a>
				<ul class="menu vertical">
				  <li><a href="account.php">Profile</a></li>
				  <li><a href="account.php">Classes</a></li>
				  <li><a href="https://www.linkedin.com/">LinkedIn</a></li>
				  <li><a href="login.php">Logout</a></li> <!-- we need to make sure this actually logs them out-->
				</ul>
			</li>
		</ul>
	  </div>
	</div>
  <div class="grid-container">
  
    <div class="grid-x grid-padding-x" style="padding-top:2%;">
  
      <div class="large-4 medium-4 small-4 cell">
        <img src="https://i0.wp.com/radaronline.com/wp-content/uploads/2017/05/betty-white-secret-suitor-split-pp.jpg?fit=640%2C420&ssl=1" alt="my_profile_image">
      </div>
      <div class="large-4 medium-4 small-4 cell">
        <ul class="profile-list">
			<?php
				$student_id = 1;
				$student_info = file_get_contents("localhost:5002/GetUser?student_id=".$student_id);
				$student_info = json_decode($student_info, True)["student_info"];
				echo "<script>alert('".$student_info."');</script>"
			?>
		  <li><?php echo $student_info->student_name?></li>
		  <li><?php echo $student_info["date_of_birth"]?></li>
		  <li><?php echo $student_info["graduation_year"]?></li>
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
                    //get all the data for the classes the user is in
                    // prepare and bind
                    //$stmt = $conn->prepare("SELECT * FROM class where class_id = (SELECT class_id FROM student_to_class WHERE student_id = ?)");
                    //$stmt->bind_param("s", $student_id);
                    //$stmt->execute();
                  ?>
                <th>Course</th>
                <th>Section</th>
                <th>Time</th>
                <th>Instructor</th>
                <th>Room</th>
              </tr>
              
              <tr>
                <td><a href="course_view.php">SWEN-344</a></td>
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
