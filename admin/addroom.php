<?php
include '../db.php';
session_start();

if(isset($_POST['addroom'])){
    $building = $_POST['building'];
    $room_no = strtoupper(trim($_POST['room_no'])); // Convert room number to uppercase and trim whitespace
    $bench_row = $_POST['bench_row'];
    $bench_column = $_POST['bench_column'];

    // Check if room number already exists (case-insensitive)
    $check_query = "SELECT * FROM room WHERE UPPER(room_no) = '$room_no' AND building = '$building'";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        $_SESSION['roomnot'] = "Error!! This room is already added.";
    } else {
        // Insert into room table
        $insert = "INSERT INTO room (building, room_no, bench_row, bench_column) VALUES ('$building', '$room_no', '$bench_column', '$bench_row')";
        $insert_query = mysqli_query($conn, $insert);

        if($insert_query){
            $_SESSION['room'] = "New room added successfully.";
        } else {
            $_SESSION['roomnot'] = "Error!! New room not added.";
        }
    }

    header("Location: add_room.php");
}
?>
