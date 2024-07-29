<?php include '../db.php' ?>
<?php
session_start();


if (isset($_POST['download'])) {
    // Retrieve form inputs
    $selected_date = $_POST['date'];

    // Database connection
     // Ensure this file sets up $conn

    $sql = "SELECT a.exam_date, a.exam_time, a.student_code, es.rollno, es.name, a.year, a.dept, es.division, a.subject_code, a.subject_name, a.status, a.room_no, a.building, a.teacher_name
            FROM attendance a
            JOIN examstudents es ON a.student_code = es.student_code
            WHERE a.exam_date = '$selected_date'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Define the filename with the current date
        $filename = "attendance_records_" . date('Ymd') . ".csv";

        // Set headers to force download of the file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write the column headers
        fputcsv($output, ['Exam Date', 'Exam Time', 'Student Code', 'Roll No', 'Name', 'Year', 'Dept', 'Division', 'Subject Code', 'Subject Name', 'Status', 'Room No', 'Building', 'Teacher Name']);

        // Write the data rows
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row["exam_date"],
                $row["exam_time"],
                $row["student_code"],
                $row["rollno"],
                $row["name"],
                $row["year"],
                $row["dept"],
                $row["division"],
                $row["subject_code"],
                $row["subject_name"],
                $row["status"],
                $row["room_no"],
                $row["building"],
                $row["teacher_name"]
            ]);
        }

        // Close output stream
        fclose($output);
        exit();
    } else {
        echo "No results found.";
    }

    // Close connection
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<link rel="stylesheet" href="common.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Download Report</title>
    <link rel="stylesheet" href="common.css">
    <?php include '../link.php'; ?>
</head>
<body>
<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4>DASHBOARD</h4>
        </div>
        <ul class="list-unstyled components">
            <li><a href="add_class.php"><img src="https://img.icons8.com/ios-filled/26/ffffff/google-classroom.png"/> Classes</a></li>
            <li><a href="add_student.php"><img src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png"/> Students</a></li>
            <li><a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a></li>
            <li><a href="add_exams.php"><img src="https://img.icons8.com/?size=30&id=AvrdORLC1sLM&format=png&color=FFFFFF"/> Exams</a></li>
            <li><a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a></li>
            <li><a href="attendance.php"><img src="https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF"/>Attendance</a></li>
            <li><a href="report.php" class="active_link"><img src="https://img.icons8.com/?size=30&id=frlIxSuEDkbi&format=png&color=FFFFFF"/>Report</a></li>
            <li><a href="upload.php"><img src="https://img.icons8.com/?size=25&id=11400&format=png&color=FFFFFF"/>Upload</a></li>
        </ul>
    </nav>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info"><img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/></button>
                <span class="page-name"> Download report</span>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-content">
            <form action="report.php" method="post">
                <div class="form-group">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="download">Download</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
