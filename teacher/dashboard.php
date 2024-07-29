<?php
session_start();
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['adminlogin'])) {
    header('Location: ../login_teacher.php');
    exit();
}

if (!isset($_SESSION['teacher_id'])) {
    $_SESSION['loginmsg'] = "Session expired. Please log in again.";
    header('Location: ../login_teacher.php');
    exit();
}
if (isset($_POST['logout'])) {
    unset($_SESSION['student']); // Unset the student session data
    header('Location: ../login_teacher.php'); // Redirect to login page after logout
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$room_no = $_SESSION['room_no'];

// Fetch the building information based on the room number
$building_query = "SELECT building FROM allot WHERE room_no='$room_no'";
$building_result = mysqli_query($conn, $building_query);
$building_data = mysqli_fetch_assoc($building_result);

if (!$building_data) {
    $_SESSION['loginmsg'] = "Error fetching building information.";
    header('Location: ../login_teacher.php');
    exit();
}

$building = $building_data['building'];

// Fetch the teacher's name
$teacher_query = "SELECT name FROM teacher WHERE teacher_id='$teacher_id'";
$teacher_result = mysqli_query($conn, $teacher_query);
$teacher = mysqli_fetch_assoc($teacher_result);

if (!$teacher) {
    $_SESSION['loginmsg'] = "Error fetching teacher information.";
    header('Location: ../login_teacher.php');
    exit();
}

$teacher_name = $teacher['name'];

// Handle attendance form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance'])) {
    $attendance_data = $_POST['attendance'];
    foreach ($attendance_data as $student_code => $status) {
        // Fetch student details
        $student_query = "SELECT * FROM examstudents WHERE student_code='$student_code'";
        $student_result = mysqli_query($conn, $student_query);
        $student = mysqli_fetch_assoc($student_result);

        if ($student) {
            $dept = $student['dept'];
            $year = $student['year'];
            $exam_date = $_POST['exam_date'];
            $exam_time = $_POST['exam_time'];

            // Fetch exam details based on student department, year, and exam date
            $exam_query = "SELECT * FROM exam WHERE dept='$dept' AND year='$year' AND exam_date='$exam_date' and exam_time = '$exam_time' ";
            $exam_result = mysqli_query($conn, $exam_query);
            $exam = mysqli_fetch_assoc($exam_result);

            if ($exam) {
                $subject_code = $exam['course_code'];
                $subject_name = $exam['subject'];
                $exam_time = $exam['exam_time'];

                $attendance_query = "INSERT INTO attendance (student_code, dept, year, teacher_name, status, room_no, building, subject_code, subject_name, exam_date, exam_time) VALUES ('$student_code', '$dept', '$year', '$teacher_name', '$status', '$room_no', '$building', '$subject_code', '$subject_name', '$exam_date', '$exam_time')";
                mysqli_query($conn, $attendance_query);
            }
        }
    }
    $_SESSION['msg'] = "Attendance recorded successfully.";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle malpractice report form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_malpractice'])) {
    $mal_date = $_POST['mal_date'];
    $mal_year = $_POST['mal_year'];
    $mal_dept = $_POST['mal_dept'];
    $mal_div = $_POST['mal_div'];
    $mal_rollno = $_POST['mal_rollno'];
    $reason = $_POST['reason'];

    $mal_query = "INSERT INTO malpractice (rollno, year, dept, division, date, teacher_name, reason) VALUES ('$mal_rollno', '$mal_year', '$mal_dept', '$mal_div', '$mal_date', '$teacher_name', '$reason')";
    if (mysqli_query($conn, $mal_query)) {
        $_SESSION['msg'] = "Malpractice reported successfully.";
    } else {
        $_SESSION['msg'] = "Error reporting malpractice: " . mysqli_error($conn);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch exam details based on selected date
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch_exam'])) {
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];

    $exam_query = "SELECT * FROM exam WHERE exam_date='$exam_date' and exam_time = '$exam_time' ";
    $exam_result = mysqli_query($conn, $exam_query);

    if (!$exam_result) {
        $_SESSION['msg'] = "Error fetching exam details.";
    } else {
        $exam_details = mysqli_fetch_assoc($exam_result);
        if ($exam_details) {
            $subject_code = $exam_details['course_code'];
            $subject_name = $exam_details['subject'];
            $exam_time = $exam_details['exam_time'];

            $_SESSION['msg'] = "Exam details fetched successfully.";
        } else {
            $_SESSION['msg'] = "No exam details found for the selected date.";
        }
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../admin/common.css">
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        table td {
            padding: 15px;
            border: 3px solid black;
        }
        /* Add borders to create gaps after every two columns */
        table tr td:nth-child(2n) {
            position: relative;
        }
        table tr td:nth-child(2n)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 0px;
            border-right: 3px solid black;
            border-right: 10px solid white;
            border-left: 3px solid black;
        }
        .allocate-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            text-align: center;
        }
        .student-code {
            color: green;
            font-weight: bold;
        }
        .seating-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .seating-table td {
            padding: 10px;
            text-align: center;
        }
        .seating-table td.empty {
            background-color: #eee;
        }
        .highlight {
            background-color: green;
            color: white;
        }
        h1, h2, h3 {
            text-align: center;
            color: #333;
        }
        form {
            width: 90%;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin: 10px 0 5px 0;
        }
        input[type="date"],
        select,
        textarea,
        button {
            padding: 10px;
            margin: 5px 0;
            width: 80%;
            max-width: 400px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            width: auto;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            color: #d9534f;
        }
    </style>
</head>
<body>
<div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav navbar-nav ml-auto">
                    <form method="post" class="form-inline my-2 my-lg-0">
                        <input type="submit" class="btn btn-link nav-link" name="logout" value="Logout">
                    </form>
                </ul>
            </div>
        </nav>
        <div class="main-content d-lg-flex justify-content-around">
            <div class="student-info">
    <h1>Welcome, <?php echo htmlspecialchars($teacher_name); ?></h1>
    <h2>Room: <?php echo htmlspecialchars($room_no); ?></h2>
    <h2>Building: <?php echo htmlspecialchars($building); ?></h2>

    <!-- Form to select exam details -->
    <form method="POST">
        <label for="exam_date">Select Exam Date:</label>
        <input type="date" id="exam_date" name="exam_date" required>
        <div class="form-group">
    <label for="exam_time">Select Time:</label>
    <select id="exam_time" name="exam_time" class="form-control">
        <option value="FN">Forenoon (FN)</option>
        <option value="AN">Afternoon (AN)</option>
    </select>
</div>


        <button type="submit" name="fetch_exam">Fetch Exam Details</button>
    </form>

    <?php
    if (isset($_SESSION['msg'])) {
        echo "<p>" . $_SESSION['msg'] . "</p>";
        unset($_SESSION['msg']);
    }
    ?>


    <!-- Attendance Form -->
    <?php
    // Display seating arrangement and take attendance if exam details are fetched
    if (isset($subject_code) && isset($subject_name) && isset($exam_time)) {
        $room_config_query = "SELECT bench_row, bench_column FROM room WHERE room_no='$room_no' AND building='$building'";
        $room_config_result = mysqli_query($conn, $room_config_query);
        $room_config = mysqli_fetch_assoc($room_config_result);

        if ($room_config) {
            $rows = $room_config['bench_row'];
            $cols = $room_config['bench_column'] * 2;

            // Fetch the seating arrangement
            $allotments_query = "SELECT row_number, column_number, student_code FROM allot WHERE room_no='$room_no' AND building='$building'";
            $allotments_result = mysqli_query($conn, $allotments_query);
            $seating = array();

            while ($allotment = mysqli_fetch_assoc($allotments_result)) {
                $seating[$allotment['row_number']][$allotment['column_number']] = $allotment['student_code'];
            }

            echo "<form method='POST'>";
            echo "<table border='1' class='seating-table'>";
            echo "<h3>Seating arrangement for Room: $room_no</h3>";
            for ($i = 0; $i < $rows; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $cols; $j++) {
                    if (isset($seating[$i][$j])) {
                        echo "<td>";
                        echo htmlspecialchars($seating[$i][$j]);
                        echo "<br>";
                        echo "<select name='attendance[" . htmlspecialchars($seating[$i][$j]) . "]'>";
                        echo "<option value='PRESENT'>PRESENT</option>";
                        echo "<option value='ABSENT'>ABSENT</option>";
                        echo "</select>";
                        echo "</td>";
                    } else {
                        echo "<td class='empty'></td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";

            echo "<input type='hidden' name='exam_date' value='$exam_date'>";
            echo "<input type='hidden' name='exam_time' value='$exam_time'>";
            echo "<button type='submit' class='allocate-button'>Submit Attendance</button>";
            echo "</form>";
        } else {
            echo "<p>No configuration found for the selected room.</p>";
        }
    }
    ?>

    <!-- Report Malpractice Form -->
    <h2>Report Malpractice</h2>
    <form method="POST">
        <label for="mal_date">Select Date:</label>
        <input type="date" id="mal_date" name="mal_date" required>

        <label for="mal_year">Year:</label>
        <select id="mal_year" name="mal_year" required>
            <option value="">--select--</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <label for="mal_dept">Department:</label>
        <select id="mal_dept" name="mal_dept" required>
            <option value="">--select--</option>
            <option value="CSE">CSE</option>
            <option value="IT">IT</option>
            <option value="ME">MECH</option>
            <option value="CE">CIVIL</option>
            <option value="EEE">EEE</option>
            <option value="ECE">ECE</option>
        </select>

        <label for="mal_div">Division:</label>
        <select id="mal_div" name="mal_div" required>
            <option value="">--select--</option>
            <option value="A">A</option>
            <option value="B">B</option>
        </select>

        <label for="mal_rollno">Roll No:</label>
        <input type="text" id="mal_rollno" name="mal_rollno" required>

        <label for="reason">Reason for Report:</label>
        <textarea id="reason" name="reason" required></textarea>

        <button type="submit" name="report_malpractice">Report</button>
    </form>
</body>
</html>
