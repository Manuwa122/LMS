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

mysqli_query($conn, "
   UPDATE access_requests 
   SET status='rejected',
       approved_at=NULL,
       expires_at=NULL
   WHERE id='$id'
   AND teacher_id='$teacher_id'
");

header("Location: teacher_payment_requests.php");
exit();
?>