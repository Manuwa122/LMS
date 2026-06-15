<?php
include "db.php";

if(!isset($_POST['request_access'])){
   header("Location: courses.php");
   exit();
}

$teacher_id = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;
$class_id   = isset($_POST['class_id']) ? (int)$_POST['class_id'] : 0;

$redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : "teacher_course_page.php?id=".$teacher_id;

/* Student session check */
$student_id = 0;

if(isset($_SESSION['student_id'])){
   $student_id = (int)$_SESSION['student_id'];
}elseif(isset($_SESSION['userId'])){
   $student_id = (int)$_SESSION['userId'];
}

if($student_id <= 0){
   header("Location: student_login.php?teacher_id=".$teacher_id);
   exit();
}

if($teacher_id <= 0 || $class_id <= 0){
   header("Location: ".$redirect_url."&error=invalid_request");
   exit();
}

/* Check duplicate request */
$check = mysqli_query($conn, "
   SELECT * FROM access_requests
   WHERE student_id='$student_id'
   AND teacher_id='$teacher_id'
   AND class_id='$class_id'
   LIMIT 1
");

if(mysqli_num_rows($check) > 0){
   header("Location: ".$redirect_url."&request=already_sent");
   exit();
}

/* Receipt upload */
$receipt_path = "";

if(isset($_FILES['receipt']) && $_FILES['receipt']['error'] == 0){

   $folder = "uploads/receipts/";

   if(!is_dir($folder)){
      mkdir($folder, 0777, true);
   }

   $file_name = $_FILES['receipt']['name'];
   $tmp_name  = $_FILES['receipt']['tmp_name'];
   $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

   $allowed = ['jpg','jpeg','png','webp','pdf'];

   if(!in_array($ext, $allowed)){
      header("Location: ".$redirect_url."&error=invalid_file");
      exit();
   }

   $new_name = time() . "_" . uniqid() . "." . $ext;
   $receipt_path = $folder . $new_name;

   if(!move_uploaded_file($tmp_name, $receipt_path)){
      header("Location: ".$redirect_url."&error=upload_failed");
      exit();
   }

}else{
   header("Location: ".$redirect_url."&error=no_receipt");
   exit();
}

/* Insert request */
$insert = mysqli_query($conn, "
   INSERT INTO access_requests(student_id, teacher_id, class_id, receipt, status, created_at)
   VALUES('$student_id', '$teacher_id', '$class_id', '$receipt_path', 'pending', NOW())
");

if($insert){
   header("Location: ".$redirect_url."&request=sent");
   exit();
}else{
   header("Location: ".$redirect_url."&error=db_failed");
   exit();
}
?>