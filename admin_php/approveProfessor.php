<div class="small-12 medium-6 large-4 columns">
    <!-- Start new form -->
    <form class="callout text-center" method="post">
        <input type="hidden" name="action" value="add_semester">
        <h4>Approve Professor Status</h4>
        <div class="floated-label-wrapper">
            <table class="hover">
                <tbody id="profs">
                <!--                              Javascript builds table here-->
                </tbody>
            </table>
        </div>

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
        <i>Start typing users name to see list</i>
<!--        <input class="button expanded" type="submit" value="Add Admin" disabled>-->
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
            $status = "";
            switch ($user['user_status']){
                case '1':
                    $status = "Professor";
                    break;
                default:
                    $status = "Student";
                    break;
            }

            echo "<tr> 
                    <td>" . $user['name'] . "</td> 
                    <td>" . $user['username'] . "</td> 
                    <td>" . $status . "</td> 
                    <td> 
                        <form class='text-center' method='post'>
                            <input type='hidden' name='action' value='make_admin'> 
                            <input type='hidden' name='user_id' value='" . $user['user_id'] . "'> 
                            <input class='button expanded rit-orange' type='submit' value='Make Admin'>
                        </form> 
                    </td>
                  </tr>";
        }?>
    </table>

</div>