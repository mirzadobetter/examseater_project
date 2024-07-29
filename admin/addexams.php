<?php
include '../db.php';
session_start();

if(isset($_POST['addexam'])){
    $dept = $_POST['dept'];
    $year = $_POST['year'];
    $course_code = $_POST['course_code'];
    $subject = $_POST['subject'];
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];

    // Query to check if the class exists
    $query = "SELECT year, dept FROM class WHERE year = '$year' AND dept = '$dept'";
    $result = mysqli_query($conn, $query);
    if($result && mysqli_num_rows($result) > 0) {
        // Insert into exam table
        $insert = "INSERT INTO exam (dept, year, course_code, subject, exam_date, exam_time) 
                   VALUES ('$dept', '$year', '$course_code', '$subject', '$exam_date', '$exam_time')";
        $insert_query = mysqli_query($conn, $insert);

        if($insert_query){
            $_SESSION['exam'] = "New Exam added successfully.";
        } else {
            $_SESSION['examnot'] = "Error!! New Exam not added.";
        }
    } else {
        $_SESSION['examnot'] = "Error!! Class not found in all classes.";
    }

    header("Location: add_exams.php");
}

if(isset($_POST['deleteexam'])){
    $exam_id = $_POST['deleteexam'];

    // Delete exam entry
    $delete_query = "DELETE FROM exam WHERE exam_id = '$exam_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if($delete_result){
        $_SESSION['delete'] = "Exam deleted successfully.";
    } else {
        $_SESSION['deletenot'] = "Error!! Exam not deleted.";
    }

    header("Location: add_exams.php");
}
?>
