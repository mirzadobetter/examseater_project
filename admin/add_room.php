<html>
<head>
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="common.css">
    <?php include'../link.php' ?>
    <style type="text/css">
    </style>
    </head>
<body>
<?php
    session_start();
    if(isset($_POST['deleteroom'])){
        $room_no = $_POST['deleteroom'];
        $delete = "DELETE FROM room WHERE room_no = '$room_no'";
        $delete_query = mysqli_query($conn, $delete);
        if($delete_query){
            $_SESSION['delete'] = "Room deleted successfully";
        } else {
            $_SESSION['deletenot'] = "Error!! Room not deleted.";
        }
    }
?>
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
                        <a href="add_room.php" class="active_link"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a>
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
                   <li><a href="report.php"><img src="https://img.icons8.com/?size=30&id=frlIxSuEDkbi&format=png&color=FFFFFF"/>Report</a></li>
                   <li><a href="upload.php"><img src="https://img.icons8.com/?size=25&id=11400&format=png&color=FFFFFF"/>Upload</a></li>
                </ul>
            </nav>
<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-info">
                <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
            </button><span class="page-name"> Manage Rooms</span>
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
        if(isset($_SESSION['room'])){
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>".$_SESSION['room']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
            unset($_SESSION['room']);
        }
        if(isset($_SESSION['roomnot'])){
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>".$_SESSION['roomnot']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
            unset($_SESSION['roomnot']);
        }

        if(isset($_SESSION['delete'])){
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>".$_SESSION['delete']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
            unset($_SESSION['delete']);
        }
        if(isset($_SESSION['deletenot'])){
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>".$_SESSION['deletenot']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
            unset($_SESSION['deletenot']);
        }
        ?>
      
    <div class="table-responsive border">
            <table class="table table-hover text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Building</th>
                        <th>Room no.</th>
                        <th>Bench in rows</th>
                        <th>Bench in column</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="addroom.php" method="post">
                     <tr>
                        <th class="py-3 bg-light">
                            <select id="building" name="building" class="form-control">
                                <option value="">--select--</option>
                                <option value="New Block">New Block</option>
                                <option value="CS Block">CS Block</option>
                            </select>
                        </th>
                        <th class="py-3 bg-light">
                            <input type="text" id="room_no" name="room_no" class="form-control" placeholder="Room no.">
                        </th>
                        <th class="py-3 bg-light">
                            <select id="bench_row" name="bench_row" class="form-control">
                                <?php for ($i=1; $i<=10; $i++) echo "<option value='$i'>$i</option>"; ?>
                            </select>
                        </th>
                        <th class="py-3 bg-light">
                            <select id="bench_column" name="bench_column" class="form-control">
                                <?php for ($i=1; $i<=10; $i++) echo "<option value='$i'>$i</option>"; ?>
                            </select>
                        </th>
                        <th class="py-3 bg-light">
                            <button class="btn btn-primary" name="addroom">Add</button>
                        </th>
                    </tr>  
                </form>
                <?php
                $selectroom = "SELECT * FROM room ORDER BY building, room_no";
                $selectroomquery = mysqli_query($conn, $selectroom);
                if($selectroomquery){
                    while ($row = mysqli_fetch_assoc($selectroomquery)) {
                        echo "<tr>
                        <td>".$row['building']."</td>
                        <td>".$row['room_no']."</td>
                        <td>".$row['bench_column']."</td>
                        <td>".$row['bench_row']."</td>
                        <form method='post'>
                        <td>
                            <button class='btn btn-light px-1 py-0' type='submit' value='".$row['room_no']."' name='deleteroom'>
                                <img src='https://img.icons8.com/color/25/000000/delete-forever.png'/>
                            </button>
                        </td>
                        </form>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No rooms available.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    // Total Vacancy Calculation
    $totalVacancyQuery = "SELECT SUM(vacancy) AS total_vacancy FROM room";
    $totalVacancyResult = mysqli_query($conn, $totalVacancyQuery);
    $totalVacancy = 0;
    if ($totalVacancyResult) {
        $totalVacancyRow = mysqli_fetch_assoc($totalVacancyResult);
        $totalVacancy = $totalVacancyRow['total_vacancy'];
    }

    // Total Strength Calculation
    $totalStrengthQuery = "SELECT SUM(strength) AS total_strength FROM class";
    $totalStrengthResult = mysqli_query($conn, $totalStrengthQuery);
    $totalStrength = 0;
    if ($totalStrengthResult) {
        $totalStrengthRow = mysqli_fetch_assoc($totalStrengthResult);
        $totalStrength = $totalStrengthRow['total_strength'];
    }

    // Determine if Total Vacancy is less than Total Strength
    $vacancyLessThanStrength = ($totalVacancy < $totalStrength);
?>
<!-- Display Total Vacancy and Total Strength -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Vacancy</h5>
                <p class="card-text" <?php if ($vacancyLessThanStrength) echo 'style="color: red;"'; ?>><?php echo $totalVacancy; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Strength</h5>
                <p class="card-text"><?php echo $totalStrength; ?></p>
            </div>
        </div>
    </div>
</div>
    </div>
    </div>
</div>
<?php include'footer.php' ?>
<script type="text/javascript">
   
</script>
</body>
</html>
