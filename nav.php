<div class="top-bar">
	<?php
    error_reporting(0);
        session_start();
		$user_id = $_SESSION['user_id'];
		$user_info = file_get_contents("http://127.0.0.1:5002/GetStudentInfo?student_id=" . $_SESSION["user_id"]);
		$user_info = json_decode($user_info, true);
	?>
  <div class="top-bar-left">
	<ul class="dropdown menu" data-dropdown-menu>
	  <li><a href="account.php"  class="site-title">SIS++</a></li>
	  <li><a href="course_list.php">Class List</a></li>
	  <li>
          <?php
          if ($_SESSION["is_admin"] == "true"){
          ?>
            <a href="javascript:void(0)">Admin</a>
            <ul class="menu vertical">
              <li><a href="admin.php?view=system">System</a></li>
              <li><a href="admin.php?view=courses">Courses</a></li>
              <li><a href="admin.php?view=classes">Classes</a></li>
              <li><a href="admin.php?view=users">Users</a></li>
            </ul>
          <?php }?>
	  </li>
	</ul>
  </div>
  <div class="top-bar-right">
	<ul class="dropdown menu" data-dropdown-menu>
  <form action="course_search.php" method="get">
	<ul class="menu">
	  <li><input type="search" name="search_parameters" placeholder="Class Search"></li>
	  <li><button type="submit" class="button" formaction="course_list.php">Search</button></li>
	</ul>
  </form>

        <?php
        if ($_SESSION["user_id"] != null) {
        ?>
		<li>
			<a href="account.php">Welcome,
                <?php
                    $name = $user_info["student_info"][0]["student_name"];
                    if ($name == "") {
                        echo "Unknown User";
                    } else {
                        echo $name;
                    }
                ?>
            </a>
		</li>
		<li>
			<a href="#"><i class="fi-list"></i></a>
			<ul class="menu vertical">
			  <li><a href="account.php">My Profile</a></li>
              <li><a href="account.php?editprofile=true">Edit Profile</a></li>
			  <li><a href="account.php">My Classes</a></li>
			  <li><a href="https://www.linkedin.com/">LinkedIn</a></li>
			  <li><a href="logout.php">Logout</a></li>
			</ul>
		</li>
        <?php }  else  {?>
        <li>
            <a href="login.php">Log In</a>
        </li>
        <?php }?>
	</ul>
  </div>
</div>