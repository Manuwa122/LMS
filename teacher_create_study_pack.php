<?php
session_start();
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = (int) $_SESSION['teacher_id'];
$message = "";
$error = "";

/* Check teacher session */
$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1");

if(!$teacher_q || mysqli_num_rows($teacher_q) == 0){
   die("Teacher session ID not found in teachers table. Please logout and login again.");
}

$teacher = mysqli_fetch_assoc($teacher_q);

/* Auto create study pack table */
mysqli_query($conn, "
   CREATE TABLE IF NOT EXISTS teacher_study_packs (
      id INT AUTO_INCREMENT PRIMARY KEY,
      teacher_id INT NOT NULL,
      pack_title VARCHAR(255) NOT NULL,
      pack_year INT NOT NULL,
      pack_month VARCHAR(50) NOT NULL,
      pack_date DATE DEFAULT NULL,
      pack_fee INT DEFAULT 0,
      thumbnail VARCHAR(255) DEFAULT '',
      description TEXT,
      file_path VARCHAR(255) DEFAULT '',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   )
");

if(mysqli_error($conn)){
   die("Table Create Error: " . mysqli_error($conn));
}

if(isset($_POST['create_pack'])){

   $pack_title = mysqli_real_escape_string($conn, $_POST['pack_title']);
   $pack_year = (int) $_POST['pack_year'];
   $pack_month = mysqli_real_escape_string($conn, $_POST['pack_month']);
   $pack_date = mysqli_real_escape_string($conn, $_POST['pack_date']);
   $pack_fee = (int) $_POST['pack_fee'];
   $description = mysqli_real_escape_string($conn, $_POST['description']);

   $thumbnail = "";
   $file_path = "";

   /* Upload thumbnail */
   if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0){

      $folder = "uploads/study_packs/thumbs/";

      if(!is_dir($folder)){
         mkdir($folder, 0777, true);
      }

      $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'webp'];

      if(in_array($ext, $allowed)){
         $thumbnail = $folder . time() . "_" . uniqid() . "." . $ext;

         if(!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail)){
            $thumbnail = "";
            $error = "Thumbnail upload failed!";
         }
      }else{
         $error = "Only JPG, JPEG, PNG, WEBP thumbnails allowed!";
      }
   }

   /* Upload study pack file */
   if($error == "" && isset($_FILES['pack_file']) && $_FILES['pack_file']['error'] == 0){

      $folder = "uploads/study_packs/files/";

      if(!is_dir($folder)){
         mkdir($folder, 0777, true);
      }

      $ext = strtolower(pathinfo($_FILES['pack_file']['name'], PATHINFO_EXTENSION));
      $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'zip'];

      if(in_array($ext, $allowed)){
         $file_path = $folder . time() . "_" . uniqid() . "." . $ext;

         if(!move_uploaded_file($_FILES['pack_file']['tmp_name'], $file_path)){
            $file_path = "";
            $error = "Study pack file upload failed!";
         }
      }else{
         $error = "Only PDF, JPG, PNG, WEBP or ZIP files allowed!";
      }
   }

   if($error == ""){

      $insert = mysqli_query($conn, "
         INSERT INTO teacher_study_packs
         (teacher_id, pack_title, pack_year, pack_month, pack_date, pack_fee, thumbnail, description, file_path)
         VALUES
         ('$teacher_id', '$pack_title', '$pack_year', '$pack_month', '$pack_date', '$pack_fee', '$thumbnail', '$description', '$file_path')
      ");

      if(!$insert){
         $error = "SQL Error: " . mysqli_error($conn);
      }else{
         $message = "Study pack created successfully for Teacher ID: " . $teacher_id;
      }
   }
}

$packs = mysqli_query($conn, "
   SELECT * FROM teacher_study_packs 
   WHERE teacher_id='$teacher_id' 
   ORDER BY pack_year DESC, pack_date DESC, id DESC
");

if(!$packs){
   die("Select Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Create Study Pack</title>
   <link rel="stylesheet" href="style3.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <style>
      body{
         background:linear-gradient(135deg,#f5f2ff,#ffffff);
      }

      .pack-page{
         padding:2rem;
      }

      .teacher-info-box{
         background:linear-gradient(135deg,#2b0066,#6f38ff);
         color:#fff;
         padding:2rem;
         border-radius:1.5rem;
         margin-bottom:2rem;
         box-shadow:0 1rem 3rem rgba(111,56,255,.20);
      }

      .teacher-info-box h2{
         font-size:2.5rem;
         margin-bottom:.8rem;
      }

      .teacher-info-box p{
         font-size:1.5rem;
         margin:.4rem 0;
      }

      .pack-card{
         background:#fff;
         border-radius:1.5rem;
         padding:2rem;
         box-shadow:0 1rem 3rem rgba(0,0,0,.08);
         border:.1rem solid #eadfff;
         margin-bottom:2rem;
      }

      .pack-card h1{
         font-size:2.7rem;
         color:#210044;
         margin-bottom:1.5rem;
         padding-bottom:1rem;
         border-bottom:.1rem solid #ddd;
      }

      .pack-card h1 span{
         border-bottom:.25rem solid #6f38ff;
         padding-bottom:1rem;
      }

      .form-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit, minmax(25rem, 1fr));
         gap:1.5rem;
      }

      .input-group p{
         font-size:1.5rem;
         color:#333;
         margin-bottom:.6rem;
      }

      .input-group input,
      .input-group select,
      .input-group textarea{
         width:100%;
         padding:1.2rem;
         border:.1rem solid #ddd;
         border-radius:.8rem;
         font-size:1.5rem;
         background:#fafafa;
      }

      .input-group textarea{
         min-height:12rem;
         resize:vertical;
      }

      .purple-btn{
         border:none;
         background:#6f38ff;
         color:#fff;
         padding:1.2rem 3rem;
         border-radius:.8rem;
         font-size:1.6rem;
         font-weight:700;
         cursor:pointer;
         margin-top:1.5rem;
         display:inline-block;
      }

      .purple-btn:hover{
         background:#5525df;
      }

      .black-btn{
         background:#222;
      }

      .msg{
         background:#e9fff3;
         color:#10854d;
         padding:1.2rem;
         border-radius:.8rem;
         font-size:1.6rem;
         margin-bottom:1.5rem;
      }

      .err{
         background:#ffe8e8;
         color:#c0392b;
         padding:1.2rem;
         border-radius:.8rem;
         font-size:1.6rem;
         margin-bottom:1.5rem;
      }

      .created-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit, minmax(28rem, 1fr));
         gap:1.5rem;
      }

      .created-card{
         border:.1rem solid #e5ddff;
         border-radius:1.2rem;
         overflow:hidden;
         background:#fff;
         box-shadow:0 .8rem 2rem rgba(111,56,255,.08);
      }

      .created-card img{
         width:100%;
         height:16rem;
         object-fit:cover;
         background:#eee7ff;
      }

      .created-card .info{
         padding:1.5rem;
      }

      .created-card h3{
         font-size:1.9rem;
         color:#210044;
         margin-bottom:.7rem;
      }

      .created-card p{
         font-size:1.4rem;
         color:#666;
         margin:.5rem 0;
      }

      @media(max-width:600px){
         .pack-page{
            padding:1rem;
         }
      }
   </style>
</head>
<body>

<section class="pack-page">

   <div class="teacher-info-box">
      <h2><?php echo htmlspecialchars($teacher['name']); ?></h2>
      <p><b>Teacher ID:</b> <?php echo $teacher_id; ?></p>
      <p><b>Subject:</b> <?php echo htmlspecialchars($teacher['subject']); ?></p>
      <p><b>Email:</b> <?php echo htmlspecialchars($teacher['email']); ?></p>
   </div>

   <div class="pack-card">
      <h1><span>Create Study Pack</span></h1>

      <?php if($message != ""){ ?>
         <p class="msg"><?php echo $message; ?></p>
      <?php } ?>

      <?php if($error != ""){ ?>
         <p class="err"><?php echo $error; ?></p>
      <?php } ?>

      <form method="post" enctype="multipart/form-data">

         <div class="form-grid">

            <div class="input-group">
               <p>Study Pack Title</p>
               <input type="text" name="pack_title" placeholder="2026 A/L April Study Pack" required>
            </div>

            <div class="input-group">
               <p>Year</p>
               <input type="number" name="pack_year" placeholder="2026" required>
            </div>

            <div class="input-group">
               <p>Relevant Month</p>
               <select name="pack_month" required>
                  <option value="">Select Month</option>
                  <option>January</option>
                  <option>February</option>
                  <option>March</option>
                  <option>April</option>
                  <option>May</option>
                  <option>June</option>
                  <option>July</option>
                  <option>August</option>
                  <option>September</option>
                  <option>October</option>
                  <option>November</option>
                  <option>December</option>
               </select>
            </div>

            <div class="input-group">
               <p>Pack Date</p>
               <input type="date" name="pack_date" required>
            </div>

            <div class="input-group">
               <p>Fee</p>
               <input type="number" name="pack_fee" placeholder="1500" required>
            </div>

            <div class="input-group">
               <p>Thumbnail Image</p>
               <input type="file" name="thumbnail" accept="image/*">
            </div>

            <div class="input-group">
               <p>Study Pack File</p>
               <input type="file" name="pack_file" accept=".pdf,.jpg,.jpeg,.png,.webp,.zip">
            </div>

         </div>

         <div class="input-group" style="margin-top:1.5rem;">
            <p>Description</p>
            <textarea name="description" placeholder="Study pack details..."></textarea>
         </div>

         <button type="submit" name="create_pack" class="purple-btn">
            Create Study Pack
         </button>

         <a href="teacher_dashboard.php" class="purple-btn black-btn">
            Back Dashboard
         </a>

      </form>
   </div>

   <div class="pack-card">
      <h1><span>My Study Packs</span></h1>

      <div class="created-grid">
         <?php if(mysqli_num_rows($packs) > 0){ ?>
            <?php while($row = mysqli_fetch_assoc($packs)){ ?>
               <div class="created-card">
                  <?php if($row['thumbnail'] != ""){ ?>
                     <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="">
                  <?php }else{ ?>
                     <img src="images/thumb-1.png" alt="">
                  <?php } ?>

                  <div class="info">
                     <h3><?php echo htmlspecialchars($row['pack_title']); ?></h3>
                     <p><b>Teacher ID:</b> <?php echo htmlspecialchars($row['teacher_id']); ?></p>
                     <p><b>Month:</b> <?php echo htmlspecialchars($row['pack_month']); ?></p>
                     <p><b>Date:</b> <?php echo htmlspecialchars($row['pack_date']); ?></p>
                     <p><b>Fee:</b> LKR <?php echo htmlspecialchars($row['pack_fee']); ?></p>

                     <?php if($row['file_path'] != ""){ ?>
                        <p><b>File:</b> Uploaded</p>
                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="purple-btn">
                           Open File
                        </a>
                     <?php }else{ ?>
                        <p><b>File:</b> Not uploaded</p>
                     <?php } ?>
                  </div>
               </div>
            <?php } ?>
         <?php }else{ ?>
            <p style="font-size:1.6rem;">No study packs created yet for Teacher ID <?php echo $teacher_id; ?>.</p>
         <?php } ?>
      </div>
   </div>

</section>

</body>
</html>