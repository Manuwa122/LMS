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

if(mysqli_num_rows($video_q) == 0){
   echo "Video not found!";
   exit();
}

$video = mysqli_fetch_assoc($video_q);

$message = "";

if(isset($_POST['update'])){

   $title = mysqli_real_escape_string($conn, $_POST['title']);
   $description = mysqli_real_escape_string($conn, $_POST['description']);

   mysqli_query($conn, "UPDATE videos SET
   title='$title',
   description='$description'
   WHERE id='$id'");

   $message = "Video updated successfully!";

   $video = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM videos WHERE id='$id'"));
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Edit Video</title>
   <link rel="stylesheet" href="style3.css">
</head>
<body>

<section class="form-container">

   <form method="post">

      <h3>edit video</h3>

      <?php
      if($message != ""){
         echo '<p style="color:green; font-size:1.7rem;">'.$message.'</p>';
      }
      ?>

      <video width="100%" controls>
         <source src="<?php echo $video['video_file']; ?>">
      </video>

      <p>video title</p>
      <input type="text" name="title"
      value="<?php echo htmlspecialchars($video['title']); ?>"
      class="box" required>

      <p>description</p>
      <textarea name="description" class="box"><?php echo htmlspecialchars($video['description']); ?></textarea>

      <input type="submit" name="update" value="update video" class="btn">

      <a href="teacher_videos.php" class="option-btn">back</a>

   </form>

</section>

</body>
</html>