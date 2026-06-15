<?php
include "db.php";

if(!isset($_GET['id'])){
   header("Location: teachers.php");
   exit();
}

$teacher_id = $_GET['id'];

if(isset($_SESSION['teacher_id'])){

   if($_SESSION['teacher_id'] == $teacher_id){
      header("Location: teacher_dashboard.php");
      exit();
   }else{
      header("Location: view_teacher_profile.php?id=$teacher_id");
      exit();
   }

}else{
   header("Location: teacher_login.php?teacher_id=$teacher_id");
   exit();
}
?>