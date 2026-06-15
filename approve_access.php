<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

if(!isset($_GET['id'])){
   header("Location: teacher_dashboard.php");
   exit();
}

$id = (int) $_GET['id'];
$teacher_id = (int) $_SESSION['teacher_id'];

$approved_at = date("Y-m-d H:i:s");
$expires_at = date("Y-m-t 23:59:59");

mysqli_query($conn, "
   UPDATE access_requests 
   SET status='approved',
       approved_at='$approved_at',
       expires_at='$expires_at'
   WHERE id='$id' 
   AND teacher_id='$teacher_id'
");

header("Location: teacher_payment_requests.php");
exit();
?>