<?php
include "db.php";

if(isset($_SESSION['teacher_id'])){
   unset($_SESSION['teacher_id']);
}

session_unset();

session_destroy();

header("Location: teacher_login.php");
exit();
?>