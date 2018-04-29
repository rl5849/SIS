<div class="small-12 medium-6 large-4 columns">
    <div>
        <form class="callout text-center" method="post">
            <h4>Add Prereq to a course</h4>
            <div class="floated-label-wrapper">
                <label for="class_new_class">Class</label>
                <input type="hidden" name="action" value="add_prereq">
                <select name="course_id" id="class_new_class" required>
                    <option>Select Course</option>

                    <?php
                    $course_list = file_get_contents("http://127.0.0.1:5002/GetCourses");
                    $course_list = json_decode($course_list, true);

                    foreach ($course_list["courses"] as $course){
                        ?>
                        <option value="<?php echo $course["course_id"]; ?>"><?php echo $course["course_name"]; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="floated-label-wrapper">
                <label for="year_level">Year Level</label>
                <select name="year" id="year">
                    <option value="none">Select year requirement</option>
                    <option value="none">N/A</option>
                    <option value="1">1st year</option>
                    <option value="2">2nd year</option>
                    <option value="3">3rd year</option>
                    <option value="4">4th year</option>
                    <option value="5">5th year</option>
                    <option value="6">Graduate</option>
                </select>
            </div>
            <div class="floated-label-wrapper">
                <label for="major">Major</label>
                <select name="major" id="major" required>
                    <option>Select Major</option>

                    <?php
                    $majors = file_get_contents("http://127.0.0.1:5002/GetMajors");
                    $majors = json_decode($majors, true)["majors"];

                    foreach ($majors as $major){
                        ?>
                        <option value="<?php echo $major["major_id"]; ?>"><?php echo $major["major_name"]; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <input type="submit" class="button expanded rit-orange" value="Create Section">
        </form>
        <!-- End new form -->
    </div>
</div>
