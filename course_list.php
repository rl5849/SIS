<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS - Course List</title>
    <link rel="stylesheet" href="css/app.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>
    <!-- Load Nav Bar -->
    <div id="nav-placeholder"></div>

    <script>
    $(function(){
      $("#nav-placeholder").load("nav.php");
    });
    </script>
    <!-- End load Nave Bar -->
    
    <div class="grid-container">
	  
      <div class="grid-x grid-padding-x">
        
        <ul class="menu" style="margin-top:2%;">
          <li><input type="search" placeholder="Enter a course ID, Instructor, Time, etc. (ex. SWEN-344)" style="width:200%"></li>
          <li><button type="button" class="button" style="margin-left:267%;">Search</button></li>
        </ul>
        
        <table class="hover" style="margin-top:2%;">
          <tr>
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
        </table>
      </div>
    </div>

    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/what-input/dist/what-input.js"></script>
    <script src="bower_components/foundation-sites/dist/js/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
