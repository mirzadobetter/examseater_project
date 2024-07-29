<?php
session_start();
?>
<html>
<head>
    <title>download report</title>
    <link rel="stylesheet" href="common.css">
    <?php include'../link.php' ?>
    <style type="text/css">
    </style>
    </head>
<body>
<div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4>DASHBOARD</h4>   
            </div>
            <ul class="list-unstyled components">
                    <li>
                        <a href="add_class.php"><img src="https://img.icons8.com/ios-filled/26/ffffff/google-classroom.png"/> Classes</a>
                    </li>
                    <li>
                        <a href="add_student.php"><img src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png"/> Students</a>
                    </li>
                    <li>
                        <a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a>
                    </li>
                    <li>
                        <a href="add_exams.php"><img src="https://img.icons8.com/?size=30&id=AvrdORLC1sLM&format=png&color=FFFFFF"/> Exams</a>
                    </li>
                    <li>
                        <a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a>
                    </li>
                    <li>
                         <a href="attendance.php"><img src="https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF"/>Attendance</a>
                   </li>
                   <li>
                         <a href="report.php" class="active_link"><img src="https://img.icons8.com/?size=30&id=frlIxSuEDkbi&format=png&color=FFFFFF"/>Report</a>
                   </li>
                </ul>
            </nav>
<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-info">
                <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
            </button><span class="page-name"> Download report</span>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
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
    
    <?php
                    if (isset($_POST['download'])) {
                        // Retrieve form inputs
                        $selected_date = $_POST['date'];

                        $sql = "SELECT a.student_code, a.dept, a.year, a.teacher_name, a.status, a.room_no, a.building, a.subject_code, a.subject_name, a.exam_date, a.exam_time, es.division, es.rollno, es.name
                        FROM attendance a
                        JOIN examstudents es ON a.student_code = es.student_code
                        WHERE exam_date = '$selected_date' " ;

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Display table header
                        echo "<h2>Attendance Records</h2>";
                        echo "<table><thead><tr><th>Exam Date</th><th>Exam Time</th><th>Student_code</th><th>Roll No</th><th>Name</th><th>year</th><th>Dept</th><th>div</th><th>Subject Code</th><th>Subject Name</th><th>status</th><th>Room no</th><th>building</th><th>teacher_name</th></tr></thead><tbody>";

                        // Display data rows
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr ><td>" . $row["exam_date"] . "</td><td>" . $row["exam_time"] . "</td><td>" . $row["student_code"] . "</td><td>" . $row["rollno"] . "</td><td>" . $row["name"] . "</td><td>" . $row["year"] . "</td><td>" . $row["dept"] . "</td><td>" . $row["division"] . "</td><td>" . $row["subject_code"] . "</td><td>" . $row["subject_name"] . "</td><td>" . $row["status"] . "</td><td>" . $row["room_no"] . "</td><td>" . $row["building"] . "</td><td>" . $row["teacher_name"] . "</td></tr>";
                        }

                        // Close table
                        echo "</tbody></table>";
                    } else {
                        echo "No results found.";
                    }
                    }
    ?> 
</div> 
</div>
    <?php include 'footer.php' ?>
</body>
</html>
