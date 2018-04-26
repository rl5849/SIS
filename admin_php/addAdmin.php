<div class="small-12 medium-6 large-4 columns">
    <form class="callout text-center" method="post">
        <h4>Add a new Admin</h4>
        <div class="floated-label-wrapper">
            <label for="User">User</label>
            <input type="text" id="user_filter" name="user_filter" placeholder="User">
        </div>
        <i>Start typing users name to see list</i>
    </form>

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

            echo "<tr name='user_listing' hidden> 
                    <td>" . $user['username'] . "</td> 
                    <td>" . $user['name'] . "</td> 
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

    <script>
        $('.text-center').on('click', function () {
            $(this).attr('disabled','disabled');
        })
    </script>


    <!-- End new form -->
</div>