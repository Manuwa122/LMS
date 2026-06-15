<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$message = "";

$teacher_id = $_SESSION['teacher_id'];

if(isset($_POST['upload'])){

   $title = mysqli_real_escape_string($conn, $_POST['title']);
   $description = mysqli_real_escape_string($conn, $_POST['description']);

   $video_name = $_FILES['video']['name'];
   $video_tmp = $_FILES['video']['tmp_name'];

   $video_folder = "videos/" . time() . "_" . $video_name;

   if(move_uploaded_file($video_tmp, $video_folder)){

      $insert = mysqli_query($conn, "INSERT INTO videos
      (teacher_id,title,description,video_file)
      VALUES
      ('$teacher_id','$title','$description','$video_folder')");

      if($insert){
         $message = "Video uploaded successfully!";
      }else{
         $message = "Database insert failed!";
      }

   }else{
      $message = "Video upload failed!";
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>Upload Video</title>
     <link rel="icon" type="image/png" href="images/favicon.png">

 
   <link rel="stylesheet" href="style3.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

*{
   font-family:'Poppins', sans-serif !important;
   box-sizing:border-box;
}

:root{
   --purple:#6c38ff;
   --purple2:#8b5cf6;
   --deep:#240046;
   --text:#1f2937;
   --muted:#6b7280;
   --white:#fff;
   --shadow:0 25px 60px rgba(31,41,55,.14);
}

body{
   min-height:100vh;
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.25), transparent 35%),
      radial-gradient(circle at bottom right, rgba(36,0,70,.18), transparent 35%),
      linear-gradient(135deg, #f8f7ff 0%, #eef2ff 50%, #f4f4f5 100%) !important;
   color:var(--text);
}

/* main form section */
.form-container{
   min-height:calc(100vh - 80px);
   display:flex;
   align-items:center;
   justify-content:center;
   padding:4rem 2rem;
}

/* upload card */
.form-container form{
   width:100%;
   max-width:650px;
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:32px !important;
   padding:3.5rem !important;
   box-shadow:var(--shadow);
   position:relative;
   overflow:hidden;
}

/* glass circles */
.form-container form::before{
   content:'';
   position:absolute;
   width:190px;
   height:190px;
   border-radius:50%;
   background:rgba(108,56,255,.13);
   right:-60px;
   top:-60px;
   z-index:0;
}

.form-container form::after{
   content:'Video';
   position:absolute;
   right:25px;
   bottom:10px;
   font-size:75px;
   font-weight:800;
   color:rgba(108,56,255,.06);
   letter-spacing:3px;
   z-index:0;
}

.form-container form > *{
   position:relative;
   z-index:2;
}

/* title */
.form-container form h3{
   font-size:3rem !important;
   color:var(--deep) !important;
   font-weight:800 !important;
   text-align:center;
   margin-bottom:2.5rem !important;
   letter-spacing:.5px;
}

.form-container form h3::after{
   content:'';
   display:block;
   width:80px;
   height:4px;
   background:linear-gradient(135deg, var(--purple), var(--deep));
   border-radius:20px;
   margin:1rem auto 0;
}

/* labels */
.form-container form p{
   color:var(--muted) !important;
   font-size:1.6rem !important;
   font-weight:600;
   margin-bottom:.8rem;
   margin-top:1.4rem;
}

.form-container form p span{
   color:#ef4444 !important;
}

/* inputs */
.form-container form .box,
.form-container form input[type="text"],
.form-container form input[type="file"],
.form-container form textarea{
   width:100%;
   border:none !important;
   outline:none !important;
   background:#f3f4ff !important;
   border:1px solid rgba(108,56,255,.12) !important;
   color:var(--text) !important;
   font-size:1.6rem !important;
   border-radius:18px !important;
   padding:1.6rem 1.8rem !important;
   transition:.25s ease;
}

.form-container form input[type="text"],
.form-container form input[type="file"]{
   min-height:5.8rem;
}

.form-container form textarea{
   min-height:11rem;
   resize:none;
}

.form-container form .box:focus,
.form-container form input:focus,
.form-container form textarea:focus{
   background:#fff !important;
   border-color:rgba(108,56,255,.55) !important;
   box-shadow:0 0 0 5px rgba(108,56,255,.10) !important;
}

/* file input */
.form-container form input[type="file"]{
   cursor:pointer;
   padding-top:1.4rem !important;
}

.form-container form input[type="file"]::file-selector-button{
   border:none;
   background:linear-gradient(135deg, var(--purple), var(--purple2));
   color:#fff;
   padding:.9rem 1.6rem;
   border-radius:12px;
   font-weight:700;
   margin-right:1.2rem;
   cursor:pointer;
}

/* buttons */
.form-container form .btn,
.form-container form .inline-btn,
.form-container form .option-btn,
.form-container form input[type="submit"],
.form-container form a{
   width:100%;
   display:flex;
   align-items:center;
   justify-content:center;
   min-height:5.4rem;
   border:none !important;
   border-radius:18px !important;
   text-decoration:none !important;
   font-size:1.7rem !important;
   font-weight:800 !important;
   margin-top:1.4rem;
   cursor:pointer;
   transition:.3s ease !important;
}

/* upload button */
.form-container form input[type="submit"],
.form-container form .btn{
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   color:#fff !important;
   box-shadow:0 16px 32px rgba(108,56,255,.28);
}

/* back dashboard button */
.form-container form a,
.form-container form .option-btn{
   background:linear-gradient(135deg, #f59e0b, #f97316) !important;
   color:#fff !important;
   box-shadow:0 16px 32px rgba(249,115,22,.22);
}

.form-container form input[type="submit"]:hover,
.form-container form .btn:hover,
.form-container form a:hover,
.form-container form .option-btn:hover{
   transform:translateY(-4px);
   box-shadow:0 22px 45px rgba(108,56,255,.35);
}

/* message */
.message,
.form-container form .message{
   background:#ecfdf5;
   color:#059669;
   border:1px solid #bbf7d0;
   padding:1.4rem 1.8rem;
   border-radius:16px;
   font-size:1.5rem;
   font-weight:700;
   text-align:center;
   margin-bottom:1.5rem;
}

/* dark mode support */
body.dark{
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.20), transparent 35%),
      radial-gradient(circle at bottom right, rgba(14,165,233,.14), transparent 35%),
      linear-gradient(135deg, #070b16 0%, #111827 45%, #1e1b4b 100%) !important;
}

body.dark .form-container form{
   background:rgba(17,24,39,.75) !important;
   border:1px solid rgba(255,255,255,.08);
}

body.dark .form-container form h3{
   color:#fff !important;
}

body.dark .form-container form p{
   color:#cbd5e1 !important;
}

body.dark .form-container form .box,
body.dark .form-container form input,
body.dark .form-container form textarea{
   background:rgba(255,255,255,.08) !important;
   color:#fff !important;
   border:1px solid rgba(255,255,255,.08) !important;
}

/* mobile */
@media(max-width:768px){
   .form-container{
      padding:2rem 1.5rem;
   }

   .form-container form{
      padding:2.5rem 2rem !important;
      border-radius:24px !important;
   }

   .form-container form h3{
      font-size:2.5rem !important;
   }
}
</style>



</head>
<body>

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data">

      <h3>upload video lesson</h3>

      <?php
      if($message != ""){
         echo '<p style="color:red; font-size:1.7rem;">'.$message.'</p>';
      }
      ?>

      <p>video title <span>*</span></p>
      <input type="text" name="title" class="box" required>

      <p>video description</p>
      <textarea name="description" class="box"></textarea>

      <p>select video <span>*</span></p>
      <input type="file" name="video" accept="video/*" class="box" required>

      <input type="submit" name="upload" value="upload video" class="btn">

      <a href="teacher_dashboard.php" class="option-btn">back dashboard</a>

   </form>

</section>

</body>
</html>