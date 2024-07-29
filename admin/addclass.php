<?php
include '../db.php';
session_start();

if(isset($_POST['addclass'])){
    $year = $_POST['year'];
    $dept = $_POST['dept'];
    $div = $_POST['div'];

    // Query to get the c_id and strength from allclass table
    $query = "SELECT c_id, strength FROM allclass WHERE year = '$year' AND dept = '$dept' AND division = '$div'";
    $result = mysqli_query($conn, $query);
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $c_id = $row['c_id'];
        $strength = $row['strength'];

        // Insert into class table using the retrieved c_id, year, dept, division, and strength
        $insert = "INSERT INTO class (class_id, year, dept, division, strength) VALUES ('$c_id', '$year', '$dept', '$div', '$strength')";
        $insert_query = mysqli_query($conn, $insert);

        if($insert_query){
            $_SESSION['class'] = "New class added successfully.";
        } else {
            $_SESSION['classnot'] = "Error!! New class not added.";
        }
    } else {
        $_SESSION['classnot'] = "Error!! Class not found in all classes.";
    }

    header("Location: add_class.php");
}
?>
