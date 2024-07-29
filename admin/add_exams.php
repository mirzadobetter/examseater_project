<?php
include '../db.php';
session_start();

// Store the selected filter date in the session
if (isset($_POST['filter_date'])) {
    $_SESSION['filter_date'] = $_POST['filter_date'];
}

$filter_date = isset($_SESSION['filter_date']) ? $_SESSION['filter_date'] : '';

if (isset($_POST['addexam'])) {
    $dept = $_POST['dept'];
    $year = $_POST['year'];
    $course_code = $_POST['course_code'];
    $subject = $_POST['subject'];
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];

    // Query to check if the class exists
    $query = "SELECT year, dept FROM class WHERE year = '$year' AND dept = '$dept'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        // Insert into exam table
        $insert = "INSERT INTO exam (dept, year, course_code, subject, exam_date, exam_time) 
                   VALUES ('$dept', '$year', '$course_code', '$subject', '$exam_date', '$exam_time')";
        $insert_query = mysqli_query($conn, $insert);

        if ($insert_query) {
            $_SESSION['exam'] = "New Exam added successfully.";
        } else {
            $_SESSION['examnot'] = "Error!! New Exam not added.";
        }
    } else {
        $_SESSION['examnot'] = "Error!! Class not found in all classes.";
    }

    header("Location: add_exams.php");
    exit();
}

if (isset($_POST['deleteexam'])) {
    $exam_id = $_POST['deleteexam'];

    // Delete exam entry
    $delete_query = "DELETE FROM exam WHERE exam_id = '$exam_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        $_SESSION['delete'] = "Exam deleted successfully.";
    } else {
        $_SESSION['deletenot'] = "Error!! Exam not deleted.";
    }

    header("Location: add_exams.php");
    exit();
}
?>

<html>
<head>
    <title>Manage Exams</title>
    <link rel="stylesheet" href="common.css">
    <?php include '../link.php' ?>
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
                <a href="add_exams.php" class="active_link"><img src="https://img.icons8.com/?size=30&id=AvrdORLC1sLM&format=png&color=FFFFFF"/> Exams</a>
            </li>
            <li>
                <a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a>
            </li>
            <li>
                <a href="attendance.php"><img src="https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF"/> Attendance</a>
            </li>
            <li><a href="report.php"><img src="https://img.icons8.com/?size=30&id=frlIxSuEDkbi&format=png&color=FFFFFF"/>Report</a></li>
            <li><a href="upload.php"><img src="https://img.icons8.com/?size=25&id=11400&format=png&color=FFFFFF"/>Upload</a></li>
        </ul>
    </nav>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                </button><span class="page-name"> Manage Exams</span>
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
            <?php
            if (isset($_SESSION['exam'])) {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>" . $_SESSION['exam'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['exam']);
            }
            if (isset($_SESSION['examnot'])) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" . $_SESSION['examnot'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['examnot']);
            }

            if (isset($_SESSION['delete'])) {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>" . $_SESSION['delete'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['delete']);
            }
            if (isset($_SESSION['deletenot'])) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" . $_SESSION['deletenot'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['deletenot']);
            }
            ?>

            <form action="addexams.php" method="post">
                <div class="form-group">
                    <label for="exam_date">Select Date:</label>
                    <input type="date" id="exam_date" name="exam_date" class="form-control">
                </div>
                <div class="form-group">
    <label for="exam_time">Select Time:</label>
    <select id="exam_time" name="exam_time" class="form-control">
        <option value="FN">Forenoon (FN)</option>
        <option value="AN">Afternoon (AN)</option>
    </select>
</div>

                <div class="table-responsive border">
                    <table class="table table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Course Code</th>
                                <th>Subject</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="py-3 bg-light">
                                    <select id="dept" name="dept" class="form-control">
                                        <option value="">--select--</option>
                                        <?php
                                        $dept_query = "SELECT DISTINCT dept FROM class";
                                        $dept_result = mysqli_query($conn, $dept_query);
                                        while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                                            echo "<option value='" . $dept_row['dept'] . "'>" . $dept_row['dept'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </th>
                                <th class="py-3 bg-light">
                                    <select id="year" name="year" class="form-control">
                                        <option value="">--select--</option>
                                        <?php
                                        $year_query = "SELECT DISTINCT year FROM class";
                                        $year_result = mysqli_query($conn, $year_query);
                                        while ($year_row = mysqli_fetch_assoc($year_result)) {
                                            echo "<option value='" . $year_row['year'] . "'>" . $year_row['year'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </th>
                                <th class="py-3 bg-light">
                                    <input type="text" id="course_code" name="course_code" class="form-control" placeholder="Course Code">
                                </th>
                                <th class="py-3 bg-light">
                                    <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject">
                                </th>
                                <th class="py-3 bg-light">
                                    <button class="btn btn-primary" name="addexam">Add</button>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>

            <form method="post">
                <div class="form-group">
                    <label for="filter_date">Filter by Date:</label>
                    <input type="date" id="filter_date" name="filter_date" class="form-control" value="<?php echo $filter_date; ?>" onchange="this.form.submit()">
                </div>
            </form>

            <div class="table-responsive border">
                <table class="table table-hover text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Course Code</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($filter_date) {
                            $get_exams = "SELECT * FROM exam WHERE exam_date = '$filter_date'";
                        } else {
                            $get_exams = "SELECT * FROM exam";
                        }
                        $result = mysqli_query($conn, $get_exams);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['dept'] . "</td>";
                            echo "<td>" . $row['year'] . "</td>";
                            echo "<td>" . $row['course_code'] . "</td>";
                            echo "<td>" . $row['subject'] . "</td>";
                            echo "<td>" . $row['exam_date'] . "</td>";
                            echo "<td><form action='addexams.php' method='post'>
                                      <button type='submit' name='deleteexam' value='" . $row['exam_id'] . "' class='btn btn-danger'>Delete</button>
                                  </form></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
</script>
</body>
</html>
