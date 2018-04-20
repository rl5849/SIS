<?php
require('fpdf181/html_table.php');

$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);

$classes = file_get_contents("http://127.0.0.1:5002/GetAccessRequests");
$classes = json_decode($classes, true);
$classes = $classes['requests'];

$current_semester = file_get_contents("http://127.0.0.1:5002/GetCurrentSemester");
$current_semester = json_decode($current_semester, true)["current_semester"];

$pdf->Cell(0, 20, "Student Access Requests for Current Semester", 0, 0, 'C');


$html = "<br><br><br><table border=\"1\"><tr><td>Class</td><td width='70px'>Code</td><td width='60px'>Section</td><td>time</td><td>Student</td><td>Request</td></tr>";

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

    $html = $html . "<tr><td>" . substr($class['class_name'], 0, 20) . "</td><td width='70px'>" . $class['course_code'] . "</td><td width='60px'>" . $class['section'] . "</td><td>" . $class['time'] . "</td><td>" . $class['user_name'] . "</td><td>" . $request . "</td></tr>";

}
$html = $html . "</table></div>";


$pdf->WriteHTML($html);
$pdf->Output();
?>