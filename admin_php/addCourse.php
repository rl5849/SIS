<div class="small-12 medium-6 large-4 columns">
    <!-- Start new form -->
    <form class="callout text-center" method="post">
        <input type="hidden" name="action" value="add_course">
        <h4>Add Course</h4>
        <div class="floated-label-wrapper">
            <label for="course_name">Course Name</label>
            <input type="text" id="course_name" name="course_name" placeholder="Course Name">
        </div>
        <div class="floated-label-wrapper">
            <label for="course_code">Course Code</label>
            <input type="text" id="course_code" name="course_code" placeholder="Course Code">
        </div>
        <div class="floated-label-wrapper">
            <label for="course_credits">Course Credits</label>
            <input type="number" name="course_credits" id="credits" min="1" max="7" placeholder="Course Credits">
        </div>
        <div class="floated-label-wrapper">
            <label for="course_description">Course Description</label>
            <input type="text" id="course_description" name="course_description" placeholder="Course Description">
        </div>
        <input class="button expanded" type="submit" value="Create Course">
    </form>
    <!-- End new form -->
</div>