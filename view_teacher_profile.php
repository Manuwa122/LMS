<?php
include "db.php";

if(!isset($_SESSION['student_id'])){
   header("Location: student_login.php");
   exit();
}

$student_id = $_SESSION['student_id'];
$teacher_id = $_GET['id'];

$teacher = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id'"));

$access = mysqli_query($conn, "SELECT * FROM access_requests 
WHERE student_id='$student_id' AND teacher_id='$teacher_id'");

$status = "";

if(mysqli_num_rows($access) > 0){
   $access_data = mysqli_fetch_assoc($access);
   $status = $access_data['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Teacher Profile</title>
   <link rel="stylesheet" href="style3.css">
</head>
<body>

<section class="teacher-profile">

   <h1 class="heading">teacher profile</h1>

   <div class="details">
      <div class="tutor">
         <img src="<?php echo $teacher['profile_image']; ?>" alt="">
         <h3><?php echo $teacher['name']; ?></h3>
         <span><?php echo $teacher['subject']; ?></span>
      </div>

      <div class="flex">
         <p>email : <span><?php echo $teacher['email']; ?></span></p>
         <p>phone : <span><?php echo $teacher['phone']; ?></span></p>
      </div>

      <p><?php echo $teacher['bio']; ?></p>

      <?php if($status == ""){ ?>
         <a href="request_access.php?teacher_id=<?php echo $teacher_id; ?>" class="inline-btn">request access</a>
      <?php }elseif($status == "pending"){ ?>
         <p class="empty">Your request is pending. Wait for teacher approval.</p>
      <?php }elseif($status == "rejected"){ ?>
         <p class="empty">Your request was rejected.</p>
      <?php } ?>
   </div>

</section>

<?php if($status == "approved"){ ?>

<section class="courses">

   <h1 class="heading">video lessons</h1>

   <div class="box-container">

      <?php
      $videos = mysqli_query($conn, "SELECT * FROM videos WHERE teacher_id='$teacher_id' ORDER BY id DESC");

      if(mysqli_num_rows($videos) > 0){
         while($video = mysqli_fetch_assoc($videos)){
      ?>

      <div class="box">
         <video width="100%" controls>
            <source src="<?php echo $video['video_file']; ?>">
         </video>

         <h3 class="title"><?php echo $video['title']; ?></h3>
         <p><?php echo $video['description']; ?></p>
      </div>

      <?php
         }
      }else{
         echo '<p class="empty">No videos uploaded yet!</p>';
      }
      ?>

   </div>

</section>

<?php } ?>

</body>
</html>