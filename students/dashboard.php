<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student'])) {
    header('Location: ../login_student.php'); // Redirect to login page if not logged in
    exit;
}

// Logout logic
if (isset($_POST['logout'])) {
    unset($_SESSION['student']); // Unset the student session data
    header('Location: ../login_student.php'); // Redirect to login page after logout
    exit;
}

// Retrieve student information from session
$student = $_SESSION['student'];
$student_name = $student['name'];
$student_year = $student['year'];
$student_dept = $student['dept'];
$student_division = $student['division'];

// Database connection parameters (update with your actual database credentials)
include '../link.php';

// Query to fetch student_id and student_code
$sql = "SELECT student_id, student_code FROM students WHERE name = '{$student_name}' AND year = '{$student_year}' AND dept = '{$student_dept}' AND division = '{$student_division}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $student_id = $row["student_id"];
        $student_code = $row["student_code"];
    }
} else {
    echo "Student not found";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../admin/common.css">
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px 5;
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
            width: 20px;
            border-right: 3px solid black;
            border-right: 20px solid white;
            border-left: 3px solid black;
        }
        .allocate-button {
            display: block;
            width: 200px;
            margin: 0 auto;
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
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
    </style>
    <?php include '../link.php'; ?>
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
                <h2>Welcome, <?php echo $student_name; ?></h2>
                <p>Year: <?php echo $student_year; ?></p>
                <p>Department: <?php echo $student_dept; ?></p>
                <p>Division: <?php echo $student_division; ?></p>
                <p class="student-code">Student Code: <?php echo $student_code; ?></p>
            </div>
            <?php
            $room_no = null;
            $building = null;

            // Query to fetch room_no and building from allot table
            $sql = "SELECT room_no, building FROM allot WHERE student_code = '{$student_code}'";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Fetch room_no and building
                $row = $result->fetch_assoc();
                $room_no = $row['room_no'];
                $building = $row['building'];
            } else {
                echo "<p>Room and building information not found for the student.</p>";
            }

            // Fetch the room configuration
            if ($room_no && $building) {
                $room_config = $conn->query("SELECT bench_row, bench_column FROM room WHERE room_no='$room_no' AND building='$building'")->fetch_assoc();

                if ($room_config) {
                    $rows = $room_config['bench_row'];
                    $cols = $room_config['bench_column'] * 2;

                    // Fetch the seating arrangement
                    $allotments = $conn->query("SELECT row_number, column_number, student_code FROM allot WHERE room_no='$room_no' AND building='$building'");
                    $seating = array();

                    while ($allotment = $allotments->fetch_assoc()) {
                        $seating[$allotment['row_number']][$allotment['column_number']] = $allotment['student_code'];
                    }

                    echo "<table class='seating-table'>";
                    echo "<caption>Seating arrangement for Room: $room_no</caption>";
                    for ($i = 0; $i < $rows; $i++) {
                        echo "<tr>";
                        for ($j = 0; $j < $cols; $j++) {
                            if (isset($seating[$i][$j])) {
                                $class = ($seating[$i][$j] === $student_code) ? 'highlight' : '';
                                echo "<td class='$class'>" . $seating[$i][$j] . "</td>";
                            } else {
                                echo "<td class='empty'></td>";
                            }
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No configuration found for the selected room.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
