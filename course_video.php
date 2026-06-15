<?php
include "db.php";

if(!isset($_GET['id'])){
   header("Location: courses.php");
   exit();
}

$video_id = $_GET['id'];

$course_q = mysqli_query($conn, "SELECT videos.*, teachers.name AS teacher_name, teachers.subject, teachers.profile_image, teachers.id AS teacher_id
FROM videos
JOIN teachers ON videos.teacher_id = teachers.id
WHERE videos.id='$video_id'");

if(mysqli_num_rows($course_q) == 0){
   echo "Course not found!";
   exit();
}

$course = mysqli_fetch_assoc($course_q);
$teacher_id = $course['teacher_id'];

$access_status = "";

if(isset($_SESSION['student_id'])){
   $student_id = $_SESSION['student_id'];

   $access_q = mysqli_query($conn, "SELECT * FROM access_requests 
   WHERE student_id='$student_id' 
   AND teacher_id='$teacher_id' 
   AND status='approved'");

   if(mysqli_num_rows($access_q) > 0){
      $access_status = "approved";
   }
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Course Video</title>
   <link rel="stylesheet" href="style3.css">
  
   <link rel="stylesheet" href="style3.css">
     <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>

<section class="watch-video">

   <div class="video-container">

      <div class="tutor">
         <img src="<?php echo htmlspecialchars($course['profile_image']); ?>" alt="">
         <div>
            <h3><?php echo htmlspecialchars($course['teacher_name']); ?></h3>
            <span><?php echo htmlspecialchars($course['subject']); ?></span>
         </div>
      </div>

      <h3 class="title"><?php echo htmlspecialchars($course['title']); ?></h3>
      <p><?php echo htmlspecialchars($course['description']); ?></p>

      <?php if(!isset($_SESSION['student_id'])){ ?>

         <p class="empty">Please login as student to request access.</p>
         <a href="student_login.php" class="inline-btn">student login</a>

      <?php }elseif($access_status == "approved"){ ?>

         <video width="100%" controls>
            <source src="<?php echo htmlspecialchars($course['video_file']); ?>" type="video/mp4">
         </video>

      <?php }else{ ?>

         <p class="empty">You need teacher access to watch this video.</p>
         <a href="request_access.php?teacher_id=<?php echo $teacher_id; ?>" class="inline-btn">
            request access
         </a>

      <?php } ?>

   </div>

</section>

</body>
</html>