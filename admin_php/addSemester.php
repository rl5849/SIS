<div class="small-12 medium-6 large-4 columns">
    <!-- Start new form -->
    <form class="callout text-center" method="post">
        <input type="hidden" name="action" value="add_semester">
        <h4>Add a New Semester</h4>
        <div class="floated-label-wrapper">
            <label for="semester_code">Semester Code</label>
            <input type="text" id="semester_code" name="semester_code" placeholder="Semester Code" required>
        </div>
        <input class="button expanded" type="submit" value="Create Semester">
    </form>
    <!-- End new form -->

    <!-- Start new form -->
    <form class="callout text-center" method="post">
        <input type="hidden" name="action" value="add_semester">
        <h4>Add a new Admin</h4>
        <div class="floated-label-wrapper">
            <label for="User">User</label>
            <input type="text" id="user" name="user" placeholder="User">
        </div>
        <input class="button expanded" type="submit" value="Add Admin">
    </form>
    <!-- End new form -->
    
    <table>
    <?php
    $users = file_get_contents("http://127.0.0.1:5002/GetUsers");
    $users = json_decode($users, true);
    $users = $users['users'];

    foreach ($users as $user){
        if ($user['user_status'] == '2'){
            continue;
        }
        switch ($user['user_status']){
            case '0':
                $status = "Student";
                break;
            case '1':
                $status = "Professor";
                break;
            case '2':
                $status = "Admin";
                break;
        }

        echo "<tr> 
                <td>" . $user['name'] . "</td> 
                <td>" . $status . "</td> 
                <td> 
                    <form class='text-center' method='post'>
                        <input type='hidden' name='action' value='make_admin'> 
                        <input type='hidden' name='user_id' value='" . $user['user_id'] . "'> 
                        <input class='button expanded rit-orange' type='submit' value='Make Admin'>
                    </form> </td>
              </tr>";
    }?>
    </table>
</div>