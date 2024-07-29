<?php
session_start();
?>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="common.css">
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
        width: 200px; /* Set button width */
        margin: 0 auto; /* Center horizontally */
        background-color: green;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        margin-bottom: 20px;
    }
    </style>
    <?php include '../link.php' ?>
    <script>
        function runScript() {
            fetch('http://127.0.0.1:5000/run-script', { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Display the "done" message
                    document.getElementById('message').textContent = data.message;
                })
                .catch(error => {
                    // Handle any errors
                    document.getElementById('message').textContent ;
                });
        }
    </script>
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
                    <a href="dashboard.php" class="active_link"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a>
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
                    </button>
                    <span class="page-name"> Allotment</span>
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
            <div>
                
                <button class="allocate-button" onclick="runScript()">Allocate</button>
                <h2>Select Building and Room</h2>
                <div id="message"></div>
                <div class="table-responsive border">
                    <table class="table table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Building</th>
                                <th>Room</th>
                            </tr>
                        <form method="post" action="">
                            <tr>
                                <th class="py-3 bg-light">
                                    <select id="building" name="building" onchange="this.form.submit()">
                                        <option value="">Select Building</option>
                                        <?php
                                        $buildings = $conn->query("SELECT DISTINCT building FROM room");
                                        while ($building = $buildings->fetch_assoc()) {
                                            echo "<option value='" . $building['building'] . "'" . (isset($_POST['building']) && $_POST['building'] == $building['building'] ? " selected" : "") . ">" . $building['building'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </th>

                                <th class="py-3 bg-light">
                                    <select id="room_no" name="room_no" onchange="this.form.submit()">
                                        <option value="">Select Room</option>
                                        <?php
                                        if (isset($_POST['building'])) {
                                            $rooms = $conn->query("SELECT room_no FROM room WHERE building='" . $_POST['building'] . "'");
                                            while ($room = $rooms->fetch_assoc()) {
                                                echo "<option value='" . $room['room_no'] . "'" . (isset($_POST['room_no']) && $_POST['room_no'] == $room['room_no'] ? " selected" : "") . ">" . $room['room_no'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </th>
                            </tr>
                        </form>

                        <?php
                        if (isset($_POST['room_no']) && isset($_POST['building'])) {
                            $room_no = $_POST['room_no'];
                            $building = $_POST['building'];

                            // Fetch the room configuration
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

                                echo "<table border='1' class='seating-table'>";
                                echo "<h3>Seating arrangement for Room: $room_no</h3>";
                                for ($i = 0; $i < $rows; $i++) {
                                    echo "<tr>";
                                    for ($j = 0; $j < $cols; $j++) {
                                        if (isset($seating[$i][$j])) {
                                            echo "<td>" . $seating[$i][$j] . "</td>";
                                        } else {
                                            echo "<td></td>";
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
                    </table>
                </div>
            </div>
        </div>
    <?php include 'footer.php' ?>
</body>
</html>
