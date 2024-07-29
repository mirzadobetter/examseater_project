<?php

$conn = mysqli_connect("localhost:3306","root","","examseater");
if($conn){
	// echo "Successfully";
}
else{
    die("no conn" . mysqli_connect_error());
}

?>