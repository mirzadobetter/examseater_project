<?php 
session_start();

// Include the database connection file (adjust the path as necessary)
include '../db.php';

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Clear the examstudents table
$clearTableQuery = "TRUNCATE TABLE examstudents";
if (!mysqli_query($conn, $clearTableQuery)) {
    die("Error clearing table: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student</title>
    <link rel="stylesheet" href="common.css"> <!-- Adjust the path if necessary -->
    <?php include '../link.php'; ?> <!-- Adjust the path if necessary -->
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4>DASHBOARD</h4>   
            </div>
            <ul class="list-unstyled components">
                <li><a href="add_class.php"><img src="https://img.icons8.com/ios-filled/26/ffffff/google-classroom.png"/> Classes</a></li>
                <li><a href="add_student.php" class="active_link"><img src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png"/> Students</a></li>
                <li><a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a></li>
                <li><a href="add_exams.php"><img src="https://img.icons8.com/?size=30&id=AvrdORLC1sLM&format=png&color=FFFFFF"/> Exams</a></li>
                <li><a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a></li>
                <li><a href="attendance.php"><img src="https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF"/> Attendance</a></li>
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
                    <span class="page-name"> View Students</span>
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


                
                <div class="table-responsive border">
                    <table class="table table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Class</th>
                                <th>RollNo.</th>
                            </tr>   
                        </thead>
                        <tbody>
                            <?php
                            $selectclass = "SELECT * FROM students, class WHERE students.class_id = class.class_id ORDER BY students.year, students.dept, students.division, students.rollno";
                            $selectclassquery = mysqli_query($conn, $selectclass);

                            if ($selectclassquery) {
                                while ($row = mysqli_fetch_assoc($selectclassquery)) {
                                    // Displaying the data
                                    echo "<tr>
                                            <td>".$row['name']."</td>
                                            <td>".$row['year']." ".$row['dept']." ".$row['division']."</td>
                                            <td>".$row['rollno']."</td>
                                          </tr>";

                                    // Inserting the data into examstudents table
                                    $insertQuery = "INSERT INTO examstudents (student_id, class_id, rollno, name, year, dept, division, student_code)
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($insertQuery);
                                    $stmt->bind_param("iississs", 
                                        $row['student_id'], 
                                        $row['class_id'], 
                                        $row['rollno'], 
                                        $row['name'], 
                                        $row['year'], 
                                        $row['dept'], 
                                        $row['division'], 
                                        $row['student_code']
                                    );

                                    if (!$stmt->execute()) {
                                        echo "Error: " . $stmt->error;
                                    }
                                    $stmt->close();
                                }
                            } else {
                                echo "Error: " . mysqli_error($conn);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?> <!-- Ensure footer.php exists in the admin directory -->
</body>
</html>
<?php $conn->close(); ?>
