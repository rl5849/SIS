<div class="small-12 medium-6 large-4 columns">
    <div>
        <form class="callout text-center" method="post">
            <h4>Add a New Class Section</h4>
            <div class="floated-label-wrapper">
                <label for="class_new_class">Class</label>
                <input type="hidden" name="action" value="add_class">
                <select name="course_id" id="class_new_class">

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
                <label for="professor">Professor</label>
                <select name="professor_id" id="professor">

                    <?php
                    $prof_list = file_get_contents("http://127.0.0.1:5002/GetProfs");
                    $prof_list = json_decode($prof_list, true);

                    foreach ($prof_list["profs"] as $prof){
                        ?>
                        <option value="<?php echo $prof[1]; ?>"><?php echo $prof[0]; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="floated-label-wrapper">
                <label for="capacity_new_class">Capacity</label>
                <input type="number" name="capacity" id="capacity_new_class" min="1" max="300" placeholder="Capacity">
            </div>
            <div class="floated-label-wrapper">
                <label for="room_number_new_class">Room Number</label>
                <input type="number" name="room_number" id="room_number_new_class" min="1" max="1000" placeholder="Room Number">
            </div>
            <div class="floated-label-wrapper">
                <label for="time_new_class">Time</label>
                <input type="text" name="time" id="time_new_class" placeholder="Time (eg. 'TuTh 10:00-10:55')">
            </div>
            <input type="submit" class="button expanded rit-orange" value="Create Section">
        </form>
        <!-- End new form -->
    </div>
</div>
