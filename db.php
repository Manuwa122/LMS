<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

$conn = mysqli_connect("localhost", "root", "", "loginsystemtut");

if(!$conn){
   die("Connection Failed");
}

/* monthly access reset */
if (file_exists("monthly_access_reset.php")) {
   include "monthly_access_reset.php";
}
?>