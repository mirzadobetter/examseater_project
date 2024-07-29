<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance</title>
    <link rel="stylesheet" href="common.css">
    <?php include '../link.php'; ?>
    <style type="text/css">
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .active_link {
            background-color: #17a2b8;
            color: white;
        }
        .main-content {
            padding: 20px;
        }
        .absent-row {
            color: red;
        }
    </style>
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
                <li><a href="attendance.php" class="active_link"><img src="https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF"/> Attendance</a></li>
                <li><a href="report.php"><img src="https://img.icons8.com/?size=30&id=frlIxSuEDkbi&format=png&color=FFFFFF"/>Report</a></li>
                <li><a href="upload.php"><img src="https://img.icons8.com/?size=25&id=11400&format=png&color=FFFFFF"/>Upload</a></li>
            </ul>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                    </button>
                    <span class="page-name"> View Attendance & Malpractice</span>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <img src="https://img.icons8.com/ios-filled/20/ffffff/menu--v3.png"/>
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
                <form action="attendance.php" method="post">
                    <div class="form-group">
                        <label for="ex_date">Select Date:</label>
                        <input type="date" id="ex_date" name="ex_date" class="form-control">
                    </div>
                    <div class="form-group">
                    <label for="year">Select Year</label>
                        <select id="year" name="year" class="form-control">
                            <option value="">--select--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="dept">Select Department</label>
                        <select id="dept" name="dept" class="form-control">
                            <option value="">--select--</option>
                            <option value="CSE">CSE</option>
                            <option value="IT">IT</option>
                            <option value="ME">MECH</option>
                            <option value="CE">CIVIL</option>
                            <option value="EEE">EEE</option>
                            <option value="ECE">ECE</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="div">Select Division</label>
                        <select id="div" name="div" class="form-control">
                            <option value="">--select--</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="showattendance">View</button>
                    </div>
                </form>
                <?php
                // Check if form is submitted
                if (isset($_POST['showattendance'])) {
                    // Retrieve form inputs
                    $selected_date = $_POST['ex_date'];
                    $selected_year = $_POST['year'];
                    $selected_dept = $_POST['dept'];
                    $selected_division = $_POST['div'];

                    // Construct SQL query for attendance
                    $sql = "SELECT a.student_code, a.dept, a.year, a.teacher_name, a.status, a.room_no, a.building, a.subject_code, a.subject_name, a.exam_date, a.exam_time, es.division, es.rollno, es.name
                            FROM attendance a
                            JOIN examstudents es ON a.student_code = es.student_code
                            WHERE 1"; // Start with a dummy condition

                    // Add conditions based on user inputs
                    if (!empty($selected_date)) {
                        $sql .= " AND a.exam_date = '" . $selected_date . "'";
                    }
                    if (!empty($selected_year)) {
                        $sql .= " AND a.year = '" . $selected_year . "'";
                    }
                    if (!empty($selected_dept)) {
                        $sql .= " AND a.dept = '" . $selected_dept . "'";
                    }
                    if (!empty($selected_division)) {
                        $sql .= " AND es.division = '" . $selected_division . "'";
                    }

                    // Execute SQL query
                    $result = $conn->query($sql);

                    // Process Results
                    if ($result->num_rows > 0) {
                        // Display table header
                        echo "<h2>Attendance Records</h2>";
                        echo "<table><thead><tr><th>Status</th><th>Roll No</th><th>Name</th><th>Subject Code</th><th>Subject Name</th><th>Exam Date</th><th>Exam Time</th></tr></thead><tbody>";

                        // Display data rows
                        while ($row = $result->fetch_assoc()) {
                            $row_class = $row["status"] == 'ABSENT' ? 'class="absent-row"' : '';
                            echo "<tr $row_class><td>" . $row["status"] . "</td><td>" . $row["rollno"] . "</td><td>" . $row["name"] . "</td><td>" . $row["subject_code"] . "</td><td>" . $row["subject_name"] . "</td><td>" . $row["exam_date"] . "</td><td>" . $row["exam_time"] . "</td></tr>";
                        }

                        // Close table
                        echo "</tbody></table>";
                    } else {
                        echo "No results found.";
                    }

                    // Construct SQL query for malpractice
                    $sql_malpractice = "SELECT e.rollno, m.name, e.date, e.reason 
                    FROM malpractice e, examstudents m 
                    WHERE e.rollno = m.rollno 
                    AND e.dept = m.dept 
                    AND e.year = m.year 
                    AND e.division = m.division"; // Start with a base condition

// Add conditions based on user inputs
if (!empty($selected_date)) {
    $sql_malpractice .= " AND e.date = '" . $selected_date . "'";
}
if (!empty($selected_year)) {
    $sql_malpractice .= " AND e.year = '" . $selected_year . "'";
}
if (!empty($selected_dept)) {
    $sql_malpractice .= " AND e.dept = '" . $selected_dept . "'";
}
if (!empty($selected_division)) {
    $sql_malpractice .= " AND e.division = '" . $selected_division . "'";
}

// Execute SQL query
$res = $conn->query($sql_malpractice);

// Process Results
if ($res->num_rows > 0) {
    // Display table header
    echo "<h2>Malpractice Reports</h2>";
    echo "<table><thead><tr><th>Roll No</th><th>Name</th><th>Reason</th><th>Exam Date</th></tr></thead><tbody>";

    // Display data rows
    while ($row = $res->fetch_assoc()) {
        echo "<tr><td>" . $row["rollno"] . "</td><td>" . $row["name"] . "</td><td>" . $row["reason"] . "</td><td>" . $row["date"] . "</td></tr>";
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
    </div>
    <?php include 'footer.php'; ?> 
</body>
</html>
<?php $conn->close(); ?>
