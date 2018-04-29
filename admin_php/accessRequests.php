<div class="small-12 medium-6 large-8 columns">
    <!-- Start new form -->
    <form class="callout text-center" method="post">
        <h4>Active Student Access Requests</h4>
        <table>
            <tr><th>Student</th><th>Class</th><th>Request</th></tr>
        <?php
        $classes = file_get_contents("http://127.0.0.1:5002/GetAccessRequests");
        $classes = json_decode($classes, true);
        $classes = $classes['requests'];

        foreach ($classes as $class) {
            switch($class['request']){
                case 'hearing':
                    $request = "Hearing";
                    break;
                case 'note_taking':
                    $request = "Note Taking";
                    break;
                default:
                    $request = "Test Time";
                    break;

            }
            echo "<tr><td>" . $class['user_name'] . "</td><td>" . $class['course_code'] . "</td><td>" . $request . "</td></tr>";
        }
            ?>



    </table>
        <a href="access_report.php" target="_blank">Download a Report</a>
    </div>
</div>