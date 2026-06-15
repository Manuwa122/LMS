<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

if(!isset($_GET['class_id']) || !isset($_GET['teacher_id'])){
   header("Location: teacher_manage_classes.php");
   exit();
}

$class_id = (int) $_GET['class_id'];
$teacher_id = (int) $_GET['teacher_id'];
$session_teacher_id = (int) $_SESSION['teacher_id'];

if($teacher_id != $session_teacher_id){
   header("Location: teacher_manage_classes.php");
   exit();
}

$message = "";
$error = "";

/* Get selected class */
$class_q = mysqli_query($conn, "
   SELECT * FROM teacher_classes
   WHERE id='$class_id'
   AND teacher_id='$teacher_id'
   LIMIT 1
");

if(!$class_q || mysqli_num_rows($class_q) == 0){
   header("Location: teacher_manage_classes.php");
   exit();
}

$class = mysqli_fetch_assoc($class_q);


/* Add lesson */
if(isset($_POST['add_lesson'])){

   $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, trim($_POST['title'])) : "";
   $type = isset($_POST['type']) ? mysqli_real_escape_string($conn, trim($_POST['type'])) : "";
   $link_url = "";

   if($type == ""){
      $error = "Please select lesson type!";
   }

   if($error == ""){

      if($title == ""){
         if($type == "video"){
            $title = "Video";
         }elseif($type == "pdf"){
            $title = "PDF";
         }else{
            $title = "Lesson";
         }
      }

      /* Upload file support */
      if(isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == 0){

         $upload_dir = "uploads/lessons/";

         if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0777, true);
         }

         $original_name = basename($_FILES['lesson_file']['name']);
         $file_name = time() . "_" . uniqid() . "_" . $original_name;
         $target_file = $upload_dir . $file_name;

         $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

         $video_allowed = ["mp4", "webm", "ogg"];
         $pdf_allowed = ["pdf"];

         if($type == "video"){

            if(in_array($file_ext, $video_allowed)){
               if(move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)){
                  $link_url = $target_file;
               }else{
                  $error = "Video upload failed!";
               }
            }else{
               $error = "For video, only MP4, WEBM, OGG files are allowed!";
            }

         }elseif($type == "pdf"){

            if(in_array($file_ext, $pdf_allowed)){
               if(move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)){
                  $link_url = $target_file;
               }else{
                  $error = "PDF upload failed!";
               }
            }else{
               $error = "For PDF, only PDF files are allowed!";
            }

         }

      }else{

         /* Link support */
         if(isset($_POST['link_url']) && trim($_POST['link_url']) != ""){
            $link_url = mysqli_real_escape_string($conn, trim($_POST['link_url']));
         }

      }

      if($error == ""){
         if($link_url == ""){
            $error = "Please upload a file or paste a link!";
         }else{

            mysqli_query($conn, "
               INSERT INTO class_lessons(teacher_id, class_id, title, type, link_url)
               VALUES('$teacher_id', '$class_id', '$title', '$type', '$link_url')
            ");

            header("Location: teacher_add_lessons.php?class_id=$class_id&teacher_id=$teacher_id&added=success");
            exit();
         }
      }
   }
}


/* Delete lesson */
if(isset($_GET['delete_lesson'])){

   $lesson_id = (int) $_GET['delete_lesson'];

   mysqli_query($conn, "
      DELETE FROM class_lessons
      WHERE id='$lesson_id'
      AND class_id='$class_id'
      AND teacher_id='$teacher_id'
   ");

   header("Location: teacher_add_lessons.php?class_id=$class_id&teacher_id=$teacher_id&deleted=success");
   exit();
}


/* Load lessons only for this class block */
$lessons = mysqli_query($conn, "
   SELECT * FROM class_lessons
   WHERE class_id='$class_id'
   AND teacher_id='$teacher_id'
   ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Add Lessons</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="images/favicon.png">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
}

:root{
   --purple:#6d35ff;
   --purple2:#8b5cf6;
   --dark:#251047;
   --text:#2f2f3a;
   --muted:#6b7280;
   --white:#fff;
   --danger:#ef4444;
   --green:#16a34a;
   --blue:#2563eb;
   --shadow:0 20px 55px rgba(76,29,149,.13);
}

body{
   min-height:100vh;
   background:
      radial-gradient(circle at top left, rgba(109,53,255,.18), transparent 32%),
      radial-gradient(circle at bottom right, rgba(139,92,246,.20), transparent 35%),
      linear-gradient(135deg,#fbf9ff,#f2efff,#ffffff);
   color:var(--text);
   padding:32px;
}

.wrap{
   max-width:1150px;
   margin:0 auto;
}

.top-bar{
   background:linear-gradient(135deg,#2b0068,#6d35ff,#986cff);
   border-radius:28px;
   padding:28px;
   display:flex;
   align-items:center;
   justify-content:space-between;
   gap:20px;
   box-shadow:0 25px 60px rgba(109,53,255,.25);
   margin-bottom:28px;
   position:relative;
   overflow:hidden;
}

.top-bar::after{
   content:'';
   position:absolute;
   right:-70px;
   top:-80px;
   width:260px;
   height:260px;
   border-radius:50%;
   background:rgba(255,255,255,.15);
}

.title-box{
   position:relative;
   z-index:2;
}

.title-box h1{
   font-size:34px;
   color:#fff;
   font-weight:800;
}

.title-box p{
   color:#efe7ff;
   font-size:16px;
   margin-top:6px;
}

.top-actions{
   position:relative;
   z-index:2;
   display:flex;
   gap:12px;
   flex-wrap:wrap;
}

.top-btn{
   background:#fff;
   color:#3b1191;
   padding:14px 22px;
   border-radius:14px;
   text-decoration:none;
   font-weight:800;
   display:inline-flex;
   align-items:center;
   gap:9px;
   transition:.25s;
}

.top-btn:hover{
   transform:translateY(-3px);
   box-shadow:0 12px 30px rgba(255,255,255,.25);
}

.card{
   background:rgba(255,255,255,.92);
   backdrop-filter:blur(16px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:26px;
   padding:28px;
   box-shadow:var(--shadow);
   margin-bottom:28px;
}

.card-title{
   font-size:28px;
   color:var(--dark);
   font-weight:800;
   margin-bottom:24px;
   padding-bottom:14px;
   border-bottom:1px solid rgba(109,53,255,.16);
   display:flex;
   align-items:center;
   gap:10px;
}

.card-title::after{
   content:'';
   flex:1;
   height:2px;
   background:linear-gradient(90deg,rgba(109,53,255,.5),transparent);
}

.notice{
   padding:15px 18px;
   border-radius:16px;
   margin-bottom:20px;
   font-weight:700;
   font-size:15px;
}

.success{
   background:#dcfce7;
   color:#15803d;
   border:1px solid #86efac;
}

.error{
   background:#fee2e2;
   color:#b91c1c;
   border:1px solid #fecaca;
}

.form-grid{
   display:grid;
   grid-template-columns:1fr 240px;
   gap:18px;
}

.form-group{
   display:flex;
   flex-direction:column;
   gap:8px;
}

.form-group label{
   font-weight:700;
   color:var(--dark);
   font-size:15px;
}

.input,
select{
   width:100%;
   border:1px solid rgba(109,53,255,.18);
   outline:none;
   background:#fff;
   border-radius:14px;
   padding:14px 15px;
   font-size:15px;
   color:var(--text);
}

.full{
   grid-column:1 / -1;
}

.btn{
   border:none;
   outline:none;
   background:linear-gradient(135deg,var(--purple),var(--purple2));
   color:#fff;
   padding:14px 24px;
   border-radius:14px;
   font-size:15px;
   font-weight:800;
   cursor:pointer;
   display:inline-flex;
   align-items:center;
   justify-content:center;
   gap:9px;
   transition:.25s;
   text-decoration:none;
   box-shadow:0 15px 30px rgba(109,53,255,.25);
}

.btn:hover{
   transform:translateY(-3px);
}

.btn-row{
   margin-top:20px;
}

.help-box{
   background:#f8f5ff;
   border:1px solid rgba(109,53,255,.15);
   border-radius:18px;
   padding:16px;
   margin-top:18px;
   color:#5b21b6;
   font-size:14px;
   line-height:1.7;
}

.lesson-list{
   display:grid;
   gap:15px;
}

.lesson-item{
   background:#fff;
   border:1px solid rgba(109,53,255,.15);
   border-radius:20px;
   padding:18px;
   display:grid;
   grid-template-columns:auto 1fr auto;
   gap:18px;
   align-items:center;
   box-shadow:0 12px 30px rgba(76,29,149,.07);
}

.lesson-icon{
   width:55px;
   height:55px;
   border-radius:17px;
   display:flex;
   align-items:center;
   justify-content:center;
   color:#fff;
   font-size:22px;
}

.video-icon{
   background:linear-gradient(135deg,#2563eb,#0ea5e9);
}

.pdf-icon{
   background:linear-gradient(135deg,#f97316,#ef4444);
}

.lesson-info h3{
   font-size:20px;
   color:var(--dark);
   margin-bottom:5px;
}

.lesson-info p{
   color:var(--muted);
   font-size:14px;
   word-break:break-all;
}

.lesson-type{
   display:inline-block;
   margin-top:8px;
   background:#f5f0ff;
   color:#5b21b6;
   padding:6px 12px;
   border-radius:999px;
   font-size:13px;
   font-weight:800;
   text-transform:capitalize;
}

.action-col{
   display:flex;
   gap:10px;
   flex-wrap:wrap;
}

.small-btn{
   text-decoration:none;
   border:none;
   border-radius:13px;
   padding:12px 15px;
   font-size:14px;
   font-weight:800;
   color:#fff;
   display:flex;
   align-items:center;
   justify-content:center;
   gap:8px;
   transition:.25s;
}

.open-btn{
   background:linear-gradient(135deg,#16a34a,#22c55e);
}

.delete-btn{
   background:linear-gradient(135deg,#ef4444,#dc2626);
}

.small-btn:hover{
   transform:translateY(-2px);
}

.empty{
   background:#faf7ff;
   border:1px dashed rgba(109,53,255,.5);
   color:var(--purple);
   border-radius:18px;
   padding:25px;
   font-size:16px;
   font-weight:800;
   text-align:center;
}

@media(max-width:750px){
   body{
      padding:16px;
   }

   .top-bar{
      flex-direction:column;
      align-items:flex-start;
   }

   .form-grid{
      grid-template-columns:1fr;
   }

   .lesson-item{
      grid-template-columns:1fr;
   }
}
   </style>
</head>
<body>

<div class="wrap">

   <div class="top-bar">
      <div class="title-box">
         <h1>Add Lessons</h1>
         <p>Class: <?php echo htmlspecialchars($class['title']); ?></p>
      </div>

      <div class="top-actions">
         <a href="teacher_manage_classes.php" class="top-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Back To Manage Classes
         </a>

         <a href="teacher_dashboard.php" class="top-btn">
            <i class="fa-solid fa-table-columns"></i>
            Dashboard
         </a>
      </div>
   </div>

   <?php if($error != ""){ ?>
      <div class="notice error"><?php echo $error; ?></div>
   <?php } ?>

   <?php if(isset($_GET['added'])){ ?>
      <div class="notice success">Lesson added successfully!</div>
   <?php } ?>

   <?php if(isset($_GET['deleted'])){ ?>
      <div class="notice success">Lesson deleted successfully!</div>
   <?php } ?>

   <div class="card">
      <h2 class="card-title">
         <i class="fa-solid fa-plus"></i>
         Add Video / PDF Link
      </h2>

      <form method="post" enctype="multipart/form-data">

         <div class="form-grid">

            <div class="form-group">
               <label>Lesson Title</label>
               <input type="text" name="title" class="input" placeholder="Example: Video">
            </div>

            <div class="form-group">
               <label>Lesson Type</label>
               <select name="type" required>
                  <option value="">Select Type</option>
                  <option value="video">Video</option>
                  <option value="pdf">PDF</option>
               </select>
            </div>

            <div class="form-group full">
               <label>Upload Video / PDF File</label>
               <input type="file" name="lesson_file" class="input" accept="video/mp4,video/webm,video/ogg,application/pdf">
            </div>

            <div class="form-group full">
               <label>Or Paste Video / PDF Link URL</label>
               <input type="url" name="link_url" class="input" placeholder="Paste YouTube, Google Drive, PDF, video link here">
            </div>

         </div>

         <div class="btn-row">
            <button type="submit" name="add_lesson" class="btn">
               <i class="fa-solid fa-cloud-arrow-up"></i>
               Add Lesson
            </button>
         </div>

      </form>

      <div class="help-box">
         <b>Important:</b> මේ page එකෙන් add කරන lesson/video/pdf මේ selected class block එකට විතරයි save වෙනවා.
      </div>
   </div>

   <div class="card">
      <h2 class="card-title">
         <i class="fa-solid fa-folder-open"></i>
         Added Lessons
      </h2>

      <div class="lesson-list">

         <?php if($lessons && mysqli_num_rows($lessons) > 0){ ?>

            <?php while($lesson = mysqli_fetch_assoc($lessons)){ ?>

               <div class="lesson-item">

                  <?php if(strtolower($lesson['type']) == "pdf"){ ?>
                     <div class="lesson-icon pdf-icon">
                        <i class="fa-solid fa-file-pdf"></i>
                     </div>
                  <?php }else{ ?>
                     <div class="lesson-icon video-icon">
                        <i class="fa-solid fa-video"></i>
                     </div>
                  <?php } ?>

                  <div class="lesson-info">
                     <h3><?php echo htmlspecialchars($lesson['title']); ?></h3>
                     <p><?php echo htmlspecialchars($lesson['link_url']); ?></p>
                     <span class="lesson-type"><?php echo htmlspecialchars($lesson['type']); ?></span>
                  </div>

                  <div class="action-col">
                     <a href="<?php echo htmlspecialchars($lesson['link_url']); ?>" target="_blank" class="small-btn open-btn">
                        <i class="fa-solid fa-up-right-from-square"></i>
                        Open
                     </a>

                     <a href="teacher_add_lessons.php?class_id=<?php echo $class_id; ?>&teacher_id=<?php echo $teacher_id; ?>&delete_lesson=<?php echo $lesson['id']; ?>"
                        onclick="return confirm('Delete this lesson?');"
                        class="small-btn delete-btn">
                        <i class="fa-solid fa-trash"></i>
                        Delete
                     </a>
                  </div>

               </div>

            <?php } ?>

         <?php }else{ ?>

            <p class="empty">No lessons added yet for this class block.</p>

         <?php } ?>

      </div>
   </div>

</div>

</body>
</html>