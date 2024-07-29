<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="common.css">
    <?php include '../link.php'; ?>
</head>
<body>
<?php
    session_start();
?>
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
                <li><a href="attendance.php"><img src="https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF"/> Attendance</a></li>
                <li><a href="report.php"><img src="https://img.icons8.com/?size=30&id=frlIxSuEDkbi&format=png&color=FFFFFF"/>Report</a></li>
                <li><a href="upload.php" class="active_link"><img src="https://img.icons8.com/?size=25&id=11400&format=png&color=FFFFFF"/>Upload</a></li>
            </ul>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                    </button><span class="page-name"> Manage Students</span>
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
                <h2>Upload</h2>
                <form action="upload.php" method="post" enctype="multipart/form-data">
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
                        <label for="file">Upload CSV File</label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="showattendance">Upload</button>
                    </div>
                </form>

                <?php
if (isset($_POST['showattendance'])) {
    $year = $_POST['year'];
    $dept = $_POST['dept'];
    $div = $_POST['div'];

    include '../db.php'; // Include your database connection file

    // Check if class already exists in the students table
    $checkClassQuery = "SELECT COUNT(*) as count FROM students WHERE year = '$year' AND dept = '$dept' AND division = '$div'";
    $result = mysqli_query($conn, $checkClassQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo "<div class='alert alert-danger'>This class is already added.</div>";
    } else {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('csv');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Truncate tempstudents table before inserting new data
                $idget = "SELECT c_id FROM allclass WHERE year='$year' AND dept='$dept' AND division='$div'";
    $idgot_result = mysqli_query($conn, $idget);
    if ($idgot_result && mysqli_num_rows($idgot_result) > 0) {
        $idgot_row = mysqli_fetch_assoc($idgot_result);
        $class_id = $idgot_row['c_id'];}
                $truncateQuery = "TRUNCATE TABLE tempstudents";
                mysqli_query($conn, $truncateQuery);

                $handle = fopen($fileTmpPath, 'r');
                $isHeader = true;

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($isHeader) {
                        $isHeader = false;
                        continue;
                    }

                    
                    $rollno = $data[0];
                    $name = $data[1];

                    $sql = "INSERT INTO tempstudents (class_id, rollno, name, year, dept, division) VALUES ('$class_id', '$rollno', '$name', '$year', '$dept', '$div')";
                    mysqli_query($conn, $sql);
                }
                fclose($handle);

                // Insert data from tempstudents into students table
                $insertIntoStudentsQuery = "INSERT INTO students (class_id, rollno, name, year, dept, division)
                                            SELECT class_id, rollno, name, year, dept, division FROM tempstudents";
                mysqli_query($conn, $insertIntoStudentsQuery);

                echo "<div class='alert alert-success'>File successfully uploaded and data inserted into the database.</div>";
            } else {
                echo "<div class='alert alert-danger'>Upload failed. Allowed file types: .csv</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error uploading file.</div>";
        }
    }
}
?>


            </div>
            <div class="main-content">
                <h2>Delete</h2>
                <form action="upload.php" method="post">
                    <div class="form-group">
                        <label for="del_year">Select Year</label>
                        <select id="del_year" name="del_year" class="form-control">
                            <option value="">--select--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="del_dept">Select Department</label>
                        <select id="del_dept" name="del_dept" class="form-control">
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
                        <label for="del_div">Select Division</label>
                        <select id="del_div" name="del_div" class="form-control">
                            <option value="">--select--</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="deleteclass">Delete</button>
                    </div>
                </form>

                <?php
                if (isset($_POST['deleteclass'])) {
                    $del_year = $_POST['del_year'];
                    $del_dept = $_POST['del_dept'];
                    $del_div = $_POST['del_div'];

                    include '../db.php'; // Include your database connection file

                    // Delete the entries from the students table
                    $deleteQuery = "DELETE FROM students WHERE year = '$del_year' AND dept = '$del_dept' AND division = '$del_div'";
                    if (mysqli_query($conn, $deleteQuery)) {
                        echo "<div class='alert alert-success'>Entries successfully deleted.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error deleting entries.</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>
