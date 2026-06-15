<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = $_SESSION['teacher_id'];

$id = $_GET['id'];

$video_q = mysqli_query($conn, "SELECT * FROM videos
WHERE id='$id' AND teacher_id='$teacher_id'");

if(mysqli_num_rows($video_q) > 0){

   $video = mysqli_fetch_assoc($video_q);

   if(file_exists($video['video_file'])){
      unlink($video['video_file']);
   }

   mysqli_query($conn, "DELETE FROM videos WHERE id='$id'");
}

header("Location: teacher_videos.php");
exit();
?>