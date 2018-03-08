<div class="top-bar">
	<?php
        session_start();
		$user_id = $_SESSION['user_id'];
		$user_info = file_get_contents("http://127.0.0.1:5002/GetStudentInfo?student_id=" . $_SESSION["user_id"]);
		$user_info = json_decode($user_info, true);
	?>
  <div class="top-bar-left">
	<ul class="dropdown menu" data-dropdown-menu>
	  <li><a href="account.php"  class="site-title">SIS++</a></li>
	  <li><a href="course_list.php">Course List</a></li>
	  <li>
          <?php
          $is_admin = file_get_contents("http://127.0.0.1:5002/CheckIfAdmin?id=".$_SESSION["user_id"]);
          $is_admin = json_decode($is_admin, true);
          $is_admin = $is_admin["is_admin"];

          if ($is_admin == "true"){
          ?>
            <a href="#">Admin</a>
            <ul class="menu vertical">
              <li><a href="admin.php">Modify Classes</a></li>
            </ul>
          <?php }?>
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
			<a href="account.php">Welcome, <?php echo ($user_info["student_info"][0]["student_name"])?></a> <!-- Name here -->
		</li>
		<li>
			<a href="#"><i class="fi-list"></i></a>
			<ul class="menu vertical">
			  <li><a href="account.php">Profile</a></li>
			  <li><a href="account.php">Classes</a></li>
			  <li><a href="https://www.linkedin.com/">LinkedIn</a></li>
			  <li><a href="logout.php">Logout</a></li>
			</ul>
		</li>
	</ul>
  </div>
</div>

<script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="js/app.js"></script>