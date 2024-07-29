<?php
session_start();
include "db.php";

if(isset($_POST['submit'])){
    $year = $_POST['year'];
    $dept = $_POST['dept'];
    $division = $_POST['division'];
    $rollno = $_POST['rollno'];

    // Query to check if the roll number exists in the students table
    $query = "SELECT * FROM students WHERE year='$year' AND dept='$dept' AND division='$division' AND rollno='$rollno'";
    $result = $conn->query($query);

    if($result->num_rows > 0) {
        // Student exists, fetch student data and store in session
        $student = $result->fetch_assoc();
        $_SESSION['student'] = $student; // Store student data in session
        header('Location: students/dashboard.php');
        exit; // Ensure script stops execution after redirect
    } else {
        // Student does not exist, set error message
        $_SESSION['loginmsg'] = "Wrong Roll No.";
        header('Location: login_student.php');
        exit; // Ensure script stops execution after redirect
    }
}
?>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Log in</title>
    <style>
        body {
            background-color: #F3EBF6;
            font-family: 'Ubuntu', sans-serif;
        }

        .main {
            background-color: #FFFFFF;
            width: 400px;
            height: 500px;
            margin: 5em auto;
            border-radius: 1.5em;
            box-shadow: 0px 11px 35px 2px rgba(0, 0, 0, 0.14);
        }

        .sign {
            padding-top: 50px;
            color: #8C55AA;
            font-weight: bold;
            font-size: 23px;
        }

        .name {
            width: 76%;
            color: rgb(38, 50, 56);
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            background: rgba(136, 126, 126, 0.04);
            padding: 10px 20px;
            border: none;
            outline: none;
            box-sizing: border-box;
            border: 2px solid rgba(0, 0, 0, 0.02);
            border-radius: 20px;
            margin-left: 46px;
            text-align: center;
            margin-bottom: 27px;
        }

        form.form1 {
            padding-top: 5px;
        }

        .pass {
            width: 76%;
            color: rgb(38, 50, 56);
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            background: rgba(136, 126, 126, 0.04);
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            outline: none;
            box-sizing: border-box;
            border: 2px solid rgba(0, 0, 0, 0.02);
            margin-bottom: 50px;
            margin-left: 46px;
            text-align: center;
            margin-bottom: 27px;
        }


        .name:focus,
        .pass:focus {
            border: 2px solid rgba(0, 0, 0, 0.18) !important;

        }

        .submit {
            cursor: pointer;
            border-radius: 5em;
            color: #fff;
            background: linear-gradient(to right, #9C27B0, #E040FB);
            border: 0;
            padding: 10px 40px;
            margin-top: 10px;
            margin-left: 35%;
            font-size: 13px;
            box-shadow: 0 0 20px 1px rgba(0, 0, 0, 0.04);
            text-shadow: 0px 0px 3px rgba(117, 117, 117, 0.12);
            color: #fff;
        }
        h1{
            text-align: center;
            color: #9C27B0;
            padding-top: 30px;
        }
        .login-div{
            height: 30px;
        }
        .loginmsg{
            text-align: center;
            font-family: Georgia, serif;
            color: red;
        }
        .role-msg{
            font-family: Georgia, serif;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <h1>Exam Hall Seating Arrangement</h1>
    <div class="main">
        <p class="sign" align="center">STUDENT LOGIN</p>
        <div class="login-div">
            <p class="loginmsg">
                <?php
                if(isset($_SESSION['loginmsg'])){
                    echo $_SESSION['loginmsg'];
                    unset($_SESSION['loginmsg']);
                }
                ?>
            </p>
        </div>
        <form class="form1" method="post">
            <select id="year" name="year" class="name">
                <option value="">YEAR</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <select id="dept" name="dept" class="name">
                <option value="">DEPARTMENT</option>
                <option value="CSE">CSE</option>
                <option value="IT">IT</option>
                <option value="ME">MECH</option>
                <option value="CE">CIVIL</option>
                <option value="EEE">EEE</option>
                <option value="ECE">ECE</option>
            </select>
            <select id="division" name="division" class="name">
                <option value="A">DIVISION</option>
                <option value="A">A</option>
                <option value="B">B</option>
            </select>
            <input class="name" name="rollno" type="text" align="center" placeholder="Enter Roll Number">
            <button class="submit" name="submit" type="submit" align="center">LOGIN</button>
        </form>
    </div>
</body>
</html>
