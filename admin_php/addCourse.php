<div class="small-12 medium-6 large-4 columns">
    <!-- Start new form -->
    <form class="callout text-center" method="post">
        <input type="hidden" name="action" value="add_course">
        <h4>Add Course</h4>
        <div class="floated-label-wrapper">
            <label for="course_name">Course Name</label>
            <input type="text" id="course_name" name="course_name" placeholder="Course Name" required>
        </div>
<!--        <div class="floated-label-wrapper">-->
<!--            <label for="class_new_class">Prerequisites</label>-->
<!--            <select placeholder="Prerequisites" name="course_id" id="class_new_class">-->
<!--                <option value="none">No Prerequisites</option>-->
<!---->
<!--                --><?php
//                $course_list = file_get_contents("http://127.0.0.1:5002/GetCourses");
//                $course_list = json_decode($course_list, true);
//
//                foreach ($course_list["courses"] as $course){
//                    ?>
<!--                    <option value="--><?php //echo $course["course_id"]; ?><!--">--><?php //echo $course["course_name"]; ?><!--</option>-->
<!--                    --><?php
//                }
//                ?>
<!--            </select>-->
<!--        </div>-->
        <div class="floated-label-wrapper">
            <label for="course_code">Course Code</label>
            <input type="text" id="course_code" name="course_code" placeholder="Course Code" required>
        </div>
        <div class="floated-label-wrapper">
            <label for="course_credits">Course Credits</label>
            <input type="number" name="course_credits" id="credits" min="1" max="7" placeholder="Course Credits" required>
        </div>
        <div class="floated-label-wrapper">
            <label for="course_description">Course Description</label>
            <input type="text" id="course_description" name="course_description" placeholder="Course Description" required>
        </div>
        <input class="button expanded" type="submit" value="Create Course" required>
    </form>
    <!-- End new form -->
</div>