<div class="small-12 medium-6 large-4 columns">
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
            echo "<tr><td>" . $class['user_name'] . "</td><td>" . $class['class_name'] . "</td><td>" . $class['request'] . "</td></tr>";
        }
            ?>



    </table>

    </div>
</div>