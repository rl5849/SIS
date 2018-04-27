<div class="small-12 medium-6 large-4 columns">
    <form class="callout text-center" method="post">
        <h4>Reset User Privileges</h4>
        <div class="floated-label-wrapper">
            <label for="User">User</label>
            <input type="text" id="user_filter_2" name="user_filter_2" placeholder="User">
        </div>
        <i>Start typing users name to see list</i>
    </form>

    <table>
        <?php
        $users = file_get_contents("http://127.0.0.1:5002/GetUsers");
        $users = json_decode($users, true);
        $users = $users['users'];

        foreach ($users as $user){
//            if ($user['user_status'] == '0'){
//                continue;
//            }
            $status = "";
            switch ($user['user_status']){
                case '1':
                    $status = "Professor";
                    break;
                case '2':
                    $status = "Admin";
                    break;
                default:
                    continue;

            }

            echo "<tr name='user_listing_2' hidden> 
                    <td>" . $user['username'] . "</td> 
                    <td>" . $user['name'] . "</td> 
                    <td>" . $status . "</td> 
                    <td> 
                        <form class='text-center' method='post'>
                            <input type='hidden' name='action' value='reset_privileges'> 
                            <input type='hidden' name='user_id' value='" . $user['user_id'] . "'> 
                            <input class='button expanded rit-orange' type='submit' value='Reset Privileges'>
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