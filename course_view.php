<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Course List</title>
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
			<ul class="menu">
			  <li><input type="search" placeholder="Class Search"></li>
			  <li><button type="button" class="button">Search</button></li>
			</ul>
			<li>
				<a href="account.php">Welcome, Big boyyy</a>
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
    
    <?php
				$course_id = 1;
				$course_info = file_get_contents("http://127.0.0.1:5002/GetCourseInfo?course_id=".$course_id);
				$course_info = json_decode($course_info, true);
      ?>

	</div>
    <div class="grid-container">
	  
      <div class="grid-x grid-padding-x" style="padding-top:2%;">
        
        <div class="large-4 medium-4 small-4 cell">
          <ul class="profile-list">
            <li><?php echo ($course_info["course_info"][0]["course_name"])?></li>
            <li>SWEN-344</li>
          </ul>
        </div>
        <div class="large-6 medium-6 small-5 cell">
          <ul class="profile-list">
            <li>GOL-1520</li>
            <li>10:10 am - 11:05am</li>
            <li>Krutzy Boyy</li>
          </ul>
        </div>
        <div class="large-2 medium-2 small-3 cell">
          <ul class="profile-list">
            <li>          
              <p><input type="submit" class="button expanded rit-orange" value="Favorite"></input></p>
              <p><input type="submit" class="button expanded rit-orange" value="Enroll/Drop"></input></p>
            </li>
          </ul>
        </div>
        
      </div>
      
      <div class="grid-x grid-padding-x" style="padding-top:2%;">
        
        <div class="large-4 medium-4 small-4 cell">
          <div class="card">
            <div class="card-divider">
              Info
            </div>
            <div class="card-section">
              <ul class="profile-list">
                <li>3 Credit Hours</li>
                <li>Enrolled: 40/40</li>
                <li>Wait List: 3/10</li>
                <li>...</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="large-4 medium-4 small-4 cell">
          <div class="card">
            <div class="card-divider">
              Description
            </div>
            <div class="card-section">
              This class is very good, yes!
            </div>
          </div>
        </div>
        <div class="large-4 medium-4 small-4 cell">
          <div class="card">
            <div class="card-divider">
              Prerequisites
            </div>
            <div class="card-section">
              <ul class="profile-list">
                <li>3rd Year status</li>
                <li>Major: Software Engineering</li>
                <li>Must be pretty cool</li>
                <li>...</li>
              </ul>
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
