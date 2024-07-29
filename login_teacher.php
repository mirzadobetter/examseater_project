<?php
session_start();
include "db.php";

if (isset($_POST['submit'])) {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['room_no'])) {
        $email = $_POST['email'];
        $email = mysqli_real_escape_string($conn, $email);
        $email = htmlentities($email);
        $password = $_POST['password'];
        $password = mysqli_real_escape_string($conn, $password);
        $password = htmlentities($password);
        $room_no = $_POST['room_no'];
        $room_no = mysqli_real_escape_string($conn, strtoupper($room_no));
        $room_no = htmlentities($room_no);

        $select_admin = "SELECT teacher_id, email, password FROM teacher WHERE email='$email' AND password='$password'";
        $select_admin_query = mysqli_query($conn, $select_admin);

        if (mysqli_num_rows($select_admin_query) > 0) {
            $admin_data = mysqli_fetch_assoc($select_admin_query);
            $check_room = "SELECT room_no, building FROM allot WHERE room_no='$room_no'";
            $check_room_query = mysqli_query($conn, $check_room);

            if (mysqli_num_rows($check_room_query) > 0) {
                $room_data = mysqli_fetch_assoc($check_room_query);
                $_SESSION['adminlogin'] = "admin";
                $_SESSION['teacher_id'] = $admin_data['teacher_id'];
                $_SESSION['room_no'] = $room_data['room_no'];
                $_SESSION['building'] = $room_data['building'];
                header('Location: teacher/dashboard.php');
                exit();
            } else {
                $_SESSION['loginmsg'] = "Room number does not exist in the allot table.";
                header('Location: login_teacher.php');
                exit();
            }
        } else {
            $_SESSION['loginmsg'] = "Incorrect Credentials";
            header('Location: login_teacher.php');
            exit();
        }
    } else {
        $_SESSION['loginmsg'] = "Please fill in all fields";
        header('Location: login_teacher.php');
        exit();
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
            height: 400px;
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

        .name, .pass {
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
            margin-bottom: 27px;
            margin-left: 46px;
            text-align: center;
            margin-bottom: 27px;
            font-family: 'Ubuntu', sans-serif;
        }

        .un:focus, .pass:focus {
            border: 2px solid rgba(0, 0, 0, 0.18) !important;
        }

        .submit {
            cursor: pointer;
            border-radius: 5em;
            color: #fff;
            background: linear-gradient(to right, #9C27B0, #E040FB);
            border: 0;
            padding-left: 40px;
            padding-right: 40px;
            padding-bottom: 10px;
            padding-top: 10px;
            font-family: 'Ubuntu', sans-serif;
            margin-left: 35%;
            font-size: 13px;
            box-shadow: 0px 7px 20px 0px #C0C0C0;
        }

        .forgot {
            text-decoration: none;
            color: #E1BEE7;
            font-size: 12px;
            margin-left: 46px;
        }

        .forgot:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="main">
        <p class="sign" align="center">Teacher Log in</p>
        <form class="form1" method="post" action="login_teacher.php">
            <input class="name" type="text" align="center" placeholder="Email" name="email">
            <input class="pass" type="password" align="center" placeholder="Password" name="password">
            <input class="pass" type="text" align="center" placeholder="Room Number" name="room_no">
            <button type="submit" class="submit" align="center" name="submit">Log in</button>
            <?php
            if (isset($_SESSION['loginmsg'])) {
                echo '<p style="color:red;text-align:center;">' . $_SESSION['loginmsg'] . '</p>';
                unset($_SESSION['loginmsg']);
            }
            ?>
        </form>
    </div>
</body>
</html>
