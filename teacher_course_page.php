<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

include "db.php";

if(!isset($_GET['id'])){
   header("Location: courses.php");
   exit();
}

$teacher_id = (int) $_GET['id'];
$main_id = isset($_GET['main_id']) ? (int) $_GET['main_id'] : 0;
$sub_id = isset($_GET['sub_id']) ? (int) $_GET['sub_id'] : 0;

$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1");

if(!$teacher_q || mysqli_num_rows($teacher_q) == 0){
   header("Location: courses.php");
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);

/* Student login session support */
$user_id = 0;

if(isset($_SESSION['userId'])){
   $user_id = (int) $_SESSION['userId'];
}elseif(isset($_SESSION['student_id'])){
   $user_id = (int) $_SESSION['student_id'];
}

/* Safe table helper */
function tableExists($conn, $table){
   $table = mysqli_real_escape_string($conn, $table);
   $q = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
   return ($q && mysqli_num_rows($q) > 0);
}

function safeImage($path, $fallback = "images/default-category.jpg"){
   if(isset($path) && trim($path) !== ""){
      return htmlspecialchars($path);
   }
   return htmlspecialchars($fallback);
}

function getClassField($row, $new_key, $old_key = null, $default = ""){
   if(isset($row[$new_key]) && $row[$new_key] !== null && $row[$new_key] !== ""){
      return $row[$new_key];
   }
   if($old_key !== null && isset($row[$old_key]) && $row[$old_key] !== null && $row[$old_key] !== ""){
      return $row[$old_key];
   }
   return $default;
}

/* Helper function - get current student's request for each class */
function getClassAccessRequest($conn, $user_id, $teacher_id, $class_id){
   if($user_id <= 0 || !tableExists($conn, "access_requests")){
      return null;
   }

   $user_id = (int) $user_id;
   $teacher_id = (int) $teacher_id;
   $class_id = (int) $class_id;

   $request_q = mysqli_query($conn, "
      SELECT * FROM access_requests
      WHERE student_id='$user_id'
      AND teacher_id='$teacher_id'
      AND class_id='$class_id'
      LIMIT 1
   ");

   if($request_q && mysqli_num_rows($request_q) > 0){
      return mysqli_fetch_assoc($request_q);
   }

   return null;
}

/* Load selected main category */
$selected_main = null;
if($main_id > 0 && tableExists($conn, "class_main_categories")){
   $selected_main_q = mysqli_query($conn, "
      SELECT * FROM class_main_categories
      WHERE id='$main_id' AND teacher_id='$teacher_id'
      LIMIT 1
   ");

   if($selected_main_q && mysqli_num_rows($selected_main_q) > 0){
      $selected_main = mysqli_fetch_assoc($selected_main_q);
   }else{
      header("Location: teacher_course_page.php?id=$teacher_id");
      exit();
   }
}

/* Load selected sub category */
$selected_sub = null;
if($sub_id > 0 && tableExists($conn, "class_sub_categories")){
   $selected_sub_q = mysqli_query($conn, "
      SELECT s.*, m.title AS main_title, m.image AS main_image
      FROM class_sub_categories s
      JOIN class_main_categories m ON s.main_category_id = m.id
      WHERE s.id='$sub_id' AND s.teacher_id='$teacher_id'
      LIMIT 1
   ");

   if($selected_sub_q && mysqli_num_rows($selected_sub_q) > 0){
      $selected_sub = mysqli_fetch_assoc($selected_sub_q);
      $main_id = (int) $selected_sub['main_category_id'];
   }else{
      header("Location: teacher_course_page.php?id=$teacher_id");
      exit();
   }
}

/* Main categories */
$main_categories = false;
if(tableExists($conn, "class_main_categories")){
   $main_categories = mysqli_query($conn, "
      SELECT m.*,
      (SELECT COUNT(*) FROM class_sub_categories s WHERE s.main_category_id=m.id AND s.teacher_id=m.teacher_id) AS sub_count
      FROM class_main_categories m
      WHERE m.teacher_id='$teacher_id'
      ORDER BY m.id DESC
   ");
}

/* Sub categories for selected main */
$sub_categories = false;
if($main_id > 0 && tableExists($conn, "class_sub_categories")){
   $sub_categories = mysqli_query($conn, "
      SELECT s.*,
      (SELECT COUNT(*) FROM teacher_classes tc WHERE tc.category_id=s.id AND tc.teacher_id=s.teacher_id) AS class_count
      FROM class_sub_categories s
      WHERE s.teacher_id='$teacher_id'
      AND s.main_category_id='$main_id'
      ORDER BY s.id DESC
   ");
}

/* Classes for selected sub category */
$classes = false;
if($sub_id > 0 && tableExists($conn, "teacher_classes")){
   $classes = mysqli_query($conn, "
      SELECT tc.*, s.title AS sub_category, m.title AS main_category, m.image AS main_image
      FROM teacher_classes tc
      LEFT JOIN class_sub_categories s ON tc.category_id=s.id
      LEFT JOIN class_main_categories m ON s.main_category_id=m.id
      WHERE tc.teacher_id='$teacher_id'
      AND tc.category_id='$sub_id'
      ORDER BY tc.id DESC
   ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($teacher['name']); ?> Classes</title>

   <link rel="stylesheet" href="style3.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <style>
      :root{
         --purple:#6d35ff;
         --purple-dark:#26006b;
         --ink:#201135;
         --muted:#6f6880;
         --line:rgba(109,53,255,.16);
         --glass:rgba(255,255,255,.82);
      }

      *{ box-sizing:border-box; }

      body{
         margin:0;
         padding-left:0 !important;
         min-height:100vh;
         background:
            radial-gradient(circle at 8% 8%, rgba(134,93,255,.18), transparent 32rem),
            radial-gradient(circle at 92% 2%, rgba(109,53,255,.16), transparent 34rem),
            linear-gradient(135deg,#f5f0ff 0%,#ffffff 52%,#f6f1ff 100%);
         color:var(--ink);
      }

      .student-class-page{
         width:100%;
         max-width:none;
         margin:0;
         padding:2.2rem 2rem 5rem;
      }

      .teacher-hero{
         min-height:13.5rem;
         background:
            radial-gradient(circle at 82% 0%, rgba(255,255,255,.18), transparent 22rem),
            linear-gradient(135deg,#26006b 0%,#5c22f0 55%,#9a6cff 100%);
         border-radius:2.8rem;
         padding:2.8rem 3.2rem;
         color:#fff;
         display:flex;
         align-items:center;
         justify-content:space-between;
         gap:2rem;
         margin-bottom:2.2rem;
         box-shadow:0 2.4rem 5.5rem rgba(80,35,170,.22);
         position:relative;
         overflow:hidden;
      }

      .teacher-hero::before,
      .teacher-hero::after{
         content:"";
         position:absolute;
         border-radius:50%;
         background:rgba(255,255,255,.13);
         pointer-events:none;
      }

      .teacher-hero::before{
         width:28rem;
         height:28rem;
         right:-7rem;
         top:-8rem;
      }

      .teacher-hero::after{
         width:12rem;
         height:12rem;
         left:45%;
         bottom:-7rem;
         background:rgba(255,255,255,.08);
      }

      .teacher-profile-box{
         display:flex;
         align-items:center;
         gap:2rem;
         position:relative;
         z-index:2;
      }

      .teacher-profile-box img{
         width:9.5rem;
         height:9.5rem;
         border-radius:2rem;
         object-fit:cover;
         border:.45rem solid rgba(255,255,255,.72);
         background:#fff;
         box-shadow:0 1rem 2.5rem rgba(0,0,0,.16);
      }

      .teacher-profile-box h1{
         font-size:clamp(2.8rem,3vw,4.2rem);
         color:#fff;
         line-height:1.05;
         letter-spacing:.02em;
         margin:0 0 .8rem;
      }

      .teacher-profile-box p{
         display:inline-flex;
         align-items:center;
         gap:.8rem;
         font-size:1.7rem;
         color:rgba(255,255,255,.86);
         margin:0;
      }

      .teacher-profile-box p::before{
         content:"";
         width:.8rem;
         height:.8rem;
         border-radius:50%;
         background:#fff;
         opacity:.8;
      }

      .back-btn{
         position:relative;
         z-index:2;
         display:inline-flex;
         align-items:center;
         justify-content:center;
         gap:.8rem;
         background:rgba(255,255,255,.92);
         color:#3f179d;
         padding:1.35rem 2.4rem;
         border-radius:1.2rem;
         font-size:1.55rem;
         font-weight:900;
         text-decoration:none;
         box-shadow:0 1rem 2.5rem rgba(0,0,0,.10);
         transition:.22s ease;
      }

      .back-btn:hover{ transform:translateY(-.2rem); }

      .breadcrumb-box{
         display:flex;
         gap:1rem;
         flex-wrap:wrap;
         margin-bottom:2.4rem;
         align-items:center;
      }

      .breadcrumb-box a,
      .breadcrumb-box span{
         font-size:1.45rem;
         background:var(--glass);
         backdrop-filter:blur(16px);
         border:.1rem solid var(--line);
         color:#5520c7;
         padding:1rem 1.55rem;
         border-radius:999px;
         text-decoration:none;
         font-weight:900;
         box-shadow:0 .8rem 2rem rgba(85,32,199,.06);
      }

      .breadcrumb-box span{ color:#736b86; }

      .section-title{
         font-size:clamp(2.4rem,2.6vw,3.6rem);
         color:var(--ink);
         margin:0 0 2.2rem;
         display:flex;
         align-items:center;
         gap:1rem;
         letter-spacing:.04em;
      }

      .section-title i{
         color:#3d116d;
      }

      .grid-box{
         display:grid;
         grid-template-columns:repeat(auto-fit,minmax(28rem,1fr));
         gap:2.2rem;
         align-items:stretch;
      }

      .main-card,
      .sub-card,
      .class-card{
         background:rgba(255,255,255,.9);
         border:.1rem solid var(--line);
         border-radius:2.4rem;
         overflow:hidden;
         box-shadow:0 1.8rem 4.5rem rgba(76,29,149,.10);
         transition:.25s ease;
         position:relative;
      }

      .main-card:hover,
      .sub-card:hover,
      .class-card:hover{
         transform:translateY(-.6rem);
         box-shadow:0 2.4rem 5rem rgba(76,29,149,.15);
      }

      .main-card{
         display:flex;
         flex-direction:column;
         min-height:auto;
      }

      .main-card::after{
         content:"";
         position:absolute;
         right:-6rem;
         bottom:-6rem;
         width:16rem;
         height:16rem;
         border-radius:50%;
         background:rgba(109,53,255,.08);
         pointer-events:none;
      }

      .main-img{
         height:19rem;
         min-height:19rem;
         background:#eee;
         position:relative;
      }

      .main-img::after{
         content:"";
         position:absolute;
         inset:0;
         background:linear-gradient(0deg, rgba(32,17,53,.20), transparent 58%);
      }

      .main-img img{
         width:100%;
         height:100%;
         object-fit:cover;
         display:block;
      }

      .card-content{
         padding:2rem;
         display:flex;
         flex-direction:column;
         justify-content:center;
         position:relative;
         z-index:2;
      }

      .card-content h3{
         font-size:clamp(2.4rem,2.4vw,3.6rem);
         color:var(--ink);
         margin:0 0 1rem;
         letter-spacing:.02em;
      }

      .card-content p{
         font-size:1.62rem;
         color:var(--muted);
         line-height:1.7;
         margin:0 0 2rem;
      }

      .open-btn,
      .request-btn,
      .pending-btn,
      .rejected-btn,
      .login-btn{
         display:inline-flex;
         align-items:center;
         justify-content:center;
         gap:.9rem;
         width:100%;
         text-align:center;
         border:none;
         border-radius:1.25rem;
         padding:1.35rem 1.5rem;
         font-size:1.58rem;
         color:#fff;
         font-weight:900;
         cursor:pointer;
         text-decoration:none;
         box-shadow:0 1rem 2.4rem rgba(109,53,255,.18);
         transition:.2s ease;
      }

      .open-btn:hover,
      .request-btn:hover,
      .login-btn:hover{ transform:translateY(-.2rem); }

      .open-btn{ background:linear-gradient(135deg,#6224ef,#9b5cff); }
      .request-btn{ background:linear-gradient(135deg,#2563eb,#0ea5e9); }
      .pending-btn{ background:linear-gradient(135deg,#f59e0b,#f97316); cursor:default; }
      .rejected-btn{ background:linear-gradient(135deg,#ef4444,#dc2626); cursor:default; }
      .login-btn{ background:linear-gradient(135deg,#16a34a,#22c55e); }

      .sub-card{
         padding:2.6rem;
      }

      .sub-card::before{
         content:"";
         position:absolute;
         inset:0 0 auto 0;
         height:.55rem;
         background:linear-gradient(90deg,#6224ef,#9b5cff,#14b8ff);
      }

      .sub-icon{
         width:6.6rem;
         height:6.6rem;
         display:flex;
         align-items:center;
         justify-content:center;
         border-radius:1.7rem;
         background:linear-gradient(135deg,#f2ebff,#ffffff);
         color:#6b28f5;
         font-size:3rem;
         margin-bottom:1.7rem;
         box-shadow:inset 0 0 0 .1rem rgba(109,53,255,.12);
      }

      .sub-card .card-content{
         padding:0;
      }

      .sub-card .card-content h3{
         font-size:2.5rem;
      }

      .class-card{
         display:grid;
         grid-template-columns:minmax(26rem,34rem) 1fr;
      }

      .class-thumb{
         min-height:100%;
         background:#eee;
      }

      .class-thumb img{
         width:100%;
         height:100%;
         min-height:26rem;
         object-fit:cover;
         display:block;
      }

      .class-info{
         padding:2.6rem;
      }

      .class-info h3{
         font-size:2.7rem;
         color:var(--ink);
         margin:0;
      }

      .class-info p{
         font-size:1.55rem;
         color:var(--muted);
         line-height:1.7;
      }

      .meta-row{
         display:flex;
         gap:.9rem;
         flex-wrap:wrap;
         margin:1.4rem 0;
      }

      .meta{
         background:#f4efff;
         color:#5420bd;
         padding:.75rem 1.15rem;
         border-radius:999px;
         font-size:1.32rem;
         font-weight:900;
         border:.1rem solid rgba(109,53,255,.12);
      }

      .request-form{
         background:#faf7ff;
         border:.1rem solid var(--line);
         border-radius:1.4rem;
         padding:1.6rem;
         margin-top:1.6rem;
      }

      .request-form label{
         display:block;
         color:var(--ink);
         font-size:1.45rem;
         margin-bottom:.9rem;
         font-weight:900;
      }

      .request-form input[type="file"]{
         width:100%;
         background:#fff;
         border:.1rem solid #e1d8ff;
         border-radius:1.1rem;
         padding:1.2rem;
         margin-bottom:1rem;
         font-size:1.4rem;
      }

      .empty{
         grid-column:1/-1;
         background:rgba(255,255,255,.9);
         border:.15rem dashed rgba(109,53,255,.45);
         border-radius:2rem;
         padding:4rem 2.5rem;
         color:#5b21b6;
         font-size:1.8rem;
         font-weight:900;
         text-align:center;
         box-shadow:0 1.4rem 3.5rem rgba(76,29,149,.08);
      }



      .content-layout{
         display:grid;
         grid-template-columns:34rem minmax(0,1fr);
         gap:2.4rem;
         align-items:start;
         width:100%;
      }

      .teacher-side-card{
         position:sticky;
         top:2rem;
         background:rgba(255,255,255,.92);
         border:.1rem solid var(--line);
         border-radius:2.4rem;
         padding:2rem;
         box-shadow:0 1.8rem 4.5rem rgba(76,29,149,.10);
         overflow:hidden;
      }

      .teacher-side-card::before{
         content:"";
         position:absolute;
         inset:0 0 auto 0;
         height:9rem;
         background:linear-gradient(135deg,#26006b,#7c3cff);
      }

      .side-profile{
         position:relative;
         z-index:2;
         text-align:center;
         padding-top:2.4rem;
      }

      .side-profile img{
         width:10.5rem;
         height:10.5rem;
         border-radius:2rem;
         object-fit:cover;
         border:.45rem solid #fff;
         background:#fff;
         box-shadow:0 1rem 2.5rem rgba(0,0,0,.14);
         margin-bottom:1.2rem;
      }

      .side-profile h2{
         font-size:2.25rem;
         color:var(--ink);
         margin:.5rem 0 .5rem;
         line-height:1.2;
      }

      .side-profile p{
         color:#5b21b6;
         font-size:1.48rem;
         font-weight:900;
         margin:0;
      }

      .teacher-detail-list{
         position:relative;
         z-index:2;
         display:grid;
         gap:1rem;
         margin-top:2rem;
      }

      .teacher-detail-item{
         display:flex;
         align-items:flex-start;
         gap:1rem;
         background:#f8f4ff;
         border:.1rem solid rgba(109,53,255,.12);
         border-radius:1.4rem;
         padding:1.15rem;
      }

      .teacher-detail-item i{
         width:3.8rem;
         height:3.8rem;
         display:flex;
         align-items:center;
         justify-content:center;
         flex:0 0 3.8rem;
         border-radius:1.1rem;
         background:#fff;
         color:#6d35ff;
         font-size:1.55rem;
      }

      .teacher-detail-item span{
         display:block;
         color:#7a728a;
         font-size:1.18rem;
         font-weight:900;
         text-transform:uppercase;
         letter-spacing:.06em;
      }

      .teacher-detail-item strong{
         display:block;
         color:var(--ink);
         font-size:1.45rem;
         line-height:1.35;
         margin-top:.25rem;
         word-break:break-word;
      }

      .side-action{
         position:relative;
         z-index:2;
         display:flex;
         align-items:center;
         justify-content:center;
         gap:.8rem;
         width:100%;
         margin-top:1.5rem;
         background:linear-gradient(135deg,#6224ef,#9b5cff);
         color:#fff;
         padding:1.25rem;
         border-radius:1.25rem;
         font-size:1.45rem;
         font-weight:900;
         text-decoration:none;
         box-shadow:0 1rem 2.4rem rgba(109,53,255,.18);
      }

      .main-content-area{
         min-width:0;
      }

      @media(max-width:900px){
         .student-class-page{ width:100%; padding:1.5rem 1.2rem 4rem; }
         .content-layout{ grid-template-columns:1fr; gap:1.8rem; }
         .teacher-side-card{ position:relative; top:auto; }
         .teacher-hero{ flex-direction:column; align-items:flex-start; padding:2.2rem; border-radius:2rem; }
         .teacher-profile-box{ align-items:flex-start; }
         .teacher-profile-box img{ width:7.5rem; height:7.5rem; border-radius:1.5rem; }
         .back-btn{ width:100%; }
         .grid-box{ grid-template-columns:1fr; }
         .main-card,
         .class-card{ grid-template-columns:1fr; }
         .main-img{ min-height:19rem; }
         .main-img::after{ background:linear-gradient(0deg, rgba(32,17,53,.18), transparent 55%); }
         .card-content{ padding:2.2rem; }
      }
   </style>
</head>
<body>

<div class="student-class-page">

   <div class="content-layout">

      <aside class="teacher-side-card">
         <div class="side-profile">
            <img src="<?php echo safeImage($teacher['profile_image'], 'images/pic-1.jpg'); ?>" alt="">
            <h2><?php echo htmlspecialchars($teacher['name']); ?></h2>
            <p><?php echo htmlspecialchars($teacher['subject']); ?></p>
         </div>

         <div class="teacher-detail-list">
            <div class="teacher-detail-item">
               <i class="fa-solid fa-chalkboard-user"></i>
               <div>
                  <span>Teacher Name</span>
                  <strong><?php echo htmlspecialchars($teacher['name']); ?></strong>
               </div>
            </div>

            <div class="teacher-detail-item">
               <i class="fa-solid fa-book-open"></i>
               <div>
                  <span>Subject</span>
                  <strong><?php echo htmlspecialchars($teacher['subject']); ?></strong>
               </div>
            </div>

            <?php if(isset($teacher['email']) && trim($teacher['email']) != ''){ ?>
            <div class="teacher-detail-item">
               <i class="fa-solid fa-envelope"></i>
               <div>
                  <span>Email</span>
                  <strong><?php echo htmlspecialchars($teacher['email']); ?></strong>
               </div>
            </div>
            <?php } ?>

            <?php if(isset($teacher['phone']) && trim($teacher['phone']) != ''){ ?>
            <div class="teacher-detail-item">
               <i class="fa-solid fa-phone"></i>
               <div>
                  <span>Phone</span>
                  <strong><?php echo htmlspecialchars($teacher['phone']); ?></strong>
               </div>
            </div>
            <?php } ?>

            <?php if(isset($teacher['bio']) && trim($teacher['bio']) != ''){ ?>
            <div class="teacher-detail-item">
               <i class="fa-solid fa-circle-info"></i>
               <div>
                  <span>About</span>
                  <strong><?php echo nl2br(htmlspecialchars($teacher['bio'])); ?></strong>
               </div>
            </div>
            <?php } ?>
         </div>

         <a href="courses.php" class="side-action">
            <i class="fa-solid fa-arrow-left"></i> Back To Courses
         </a>
      </aside>

      <main class="main-content-area">

         <div class="teacher-hero">
            <div class="teacher-profile-box">
               <img src="<?php echo safeImage($teacher['profile_image'], 'images/pic-1.jpg'); ?>" alt="">
               <div>
                  <h1><?php echo htmlspecialchars($teacher['name']); ?></h1>
                  <p><?php echo htmlspecialchars($teacher['subject']); ?></p>
               </div>
            </div>

            <a href="courses.php" class="back-btn">
               <i class="fa-solid fa-arrow-left"></i> Back To Courses
            </a>
         </div>

   <div class="breadcrumb-box">
      <a href="teacher_course_page.php?id=<?php echo $teacher_id; ?>">
         <i class="fa-solid fa-layer-group"></i> All Classes
      </a>

      <?php if($selected_main){ ?>
         <span><?php echo htmlspecialchars($selected_main['title']); ?></span>
      <?php } ?>

      <?php if($selected_sub){ ?>
         <span><?php echo htmlspecialchars($selected_sub['title']); ?></span>
      <?php } ?>
   </div>

   <?php if(isset($_GET['request']) && $_GET['request'] == "sent"){ ?>
      <div class="alert-success-box">
         <i class="fa-solid fa-circle-check"></i> Payment receipt uploaded successfully. Wait for teacher approval.
      </div>
   <?php } ?>

   <?php if(isset($_GET['request']) && $_GET['request'] == "already_sent"){ ?>
      <div class="alert-info-box">
         <i class="fa-solid fa-circle-info"></i> You already sent an access request for this class.
      </div>
   <?php } ?>

   <?php if(isset($_GET['error'])){ ?>
      <div class="alert-error-box">
         <i class="fa-solid fa-triangle-exclamation"></i> Something went wrong. Please upload a valid receipt and try again.
      </div>
   <?php } ?>

   <?php if($sub_id > 0){ ?>

      <h2 class="section-title">
         <i class="fa-solid fa-video"></i>
         <?php echo htmlspecialchars($selected_sub['main_title']); ?> / <?php echo htmlspecialchars($selected_sub['title']); ?> Classes
      </h2>

      <div class="grid-box" style="grid-template-columns:1fr;">

         <?php if($classes && mysqli_num_rows($classes) > 0){ ?>

            <?php while($class = mysqli_fetch_assoc($classes)){ 
               $class_id = (int) $class['id'];
               $class_title = getClassField($class, 'title', 'class_title', 'Untitled Class');
               $class_year = getClassField($class, 'year', 'class_year', '');
               $class_month = getClassField($class, 'month', 'month_name', '');
               $thumbnail = getClassField($class, 'thumbnail', null, 'images/default-category.jpg');
               $description = getClassField($class, 'description');
               $fee = getClassField($class, 'fee');
               $payment_scheme = getClassField($class, 'payment_scheme');
               $class_date = getClassField($class, 'class_date');
               $request = getClassAccessRequest($conn, $user_id, $teacher_id, $class_id);
            ?>

               <div class="class-card">
                  <div class="class-thumb">
                     <img src="<?php echo safeImage($thumbnail); ?>" alt="">
                  </div>

                  <div class="class-info">
                     <h3><?php echo htmlspecialchars($class_title); ?></h3>

                     <div class="meta-row">
                        <?php if($class_year != ''){ ?>
                           <span class="meta"><i class="fa-solid fa-calendar"></i> <?php echo htmlspecialchars($class_year); ?></span>
                        <?php } ?>

                        <?php if($class_month != ''){ ?>
                           <span class="meta"><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($class_month); ?></span>
                        <?php } ?>

                        <?php if($class_date != ''){ ?>
                           <span class="meta"><i class="fa-solid fa-calendar-days"></i> <?php echo htmlspecialchars($class_date); ?></span>
                        <?php } ?>

                        <?php if($fee != ''){ ?>
                           <span class="meta"><i class="fa-solid fa-money-bill"></i> LKR <?php echo htmlspecialchars($fee); ?></span>
                        <?php } ?>

                        <?php if($payment_scheme != ''){ ?>
                           <span class="meta"><i class="fa-regular fa-credit-card"></i> <?php echo htmlspecialchars($payment_scheme); ?></span>
                        <?php } ?>
                     </div>

                     <?php if($description != ''){ ?>
                        <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
                     <?php } ?>

                     <?php if($user_id <= 0){ ?>

                        <a href="student_login.php?teacher_id=<?php echo $teacher_id; ?>" class="login-btn">
                           <i class="fa-solid fa-lock"></i> Login To Request Access
                        </a>

                     <?php }elseif($request == null){ ?>

                        <form action="upload_access_request.php" method="post" enctype="multipart/form-data" class="request-form">
                           <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
                           <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                           <input type="hidden" name="redirect_url" value="teacher_course_page.php?id=<?php echo $teacher_id; ?>&sub_id=<?php echo $sub_id; ?>">

                           <label>Upload payment receipt to request access</label>
                           <input type="file" name="receipt" accept="image/*,.pdf" required>

                           <button type="submit" name="request_access" class="request-btn">
                              <i class="fa-solid fa-paper-plane"></i> Request Access
                           </button>
                        </form>

                     <?php }elseif(isset($request['status']) && $request['status'] == 'approved'){ ?>

                        <a href="view_class.php?class_id=<?php echo $class_id; ?>&teacher_id=<?php echo $teacher_id; ?>" class="open-btn">
                           <i class="fa-solid fa-play"></i> View Class
                        </a>

                     <?php }elseif(isset($request['status']) && $request['status'] == 'rejected'){ ?>

                        <button class="rejected-btn" type="button">
                           <i class="fa-solid fa-xmark"></i> Request Rejected
                        </button>

                     <?php }else{ ?>

                        <button class="pending-btn" type="button">
                           <i class="fa-solid fa-clock"></i> Waiting For Teacher Approval
                        </button>

                     <?php } ?>
                  </div>
               </div>

            <?php } ?>

         <?php }else{ ?>
            <p class="empty">No class Classes added to this yet.</p>
         <?php } ?>

      </div>

   <?php }elseif($main_id > 0){ ?>

      <h2 class="section-title">
         <i class="fa-solid fa-folder-open"></i>
         <?php echo htmlspecialchars($selected_main['title']); ?> Classes
      </h2>

      <div class="grid-box">

         <?php if($sub_categories && mysqli_num_rows($sub_categories) > 0){ ?>

            <?php while($sub = mysqli_fetch_assoc($sub_categories)){ ?>

               <div class="sub-card">
                  <div class="sub-icon">
                     <i class="fa-solid fa-folder"></i>
                  </div>

                  <div class="card-content" style="padding:0;">
                     <h3><?php echo htmlspecialchars($sub['title']); ?></h3>
                     <p><?php echo (int)$sub['class_count']; ?> classes available</p>

                     <a href="teacher_course_page.php?id=<?php echo $teacher_id; ?>&sub_id=<?php echo $sub['id']; ?>" class="open-btn">
                        Open Classes <i class="fa-solid fa-arrow-right"></i>
                     </a>
                  </div>
               </div>

            <?php } ?>

         <?php }else{ ?>
            <p class="empty">No Classes added yet.</p>
         <?php } ?>

      </div>

   <?php }else{ ?>

      <h2 class="section-title">
         <i class="fa-solid fa-layer-group"></i>
         Select Class
      </h2>

      <div class="grid-box">

         <?php if($main_categories && mysqli_num_rows($main_categories) > 0){ ?>

            <?php while($main = mysqli_fetch_assoc($main_categories)){ ?>

               <div class="main-card">
                  <div class="main-img">
                     <img src="<?php echo safeImage($main['image']); ?>" alt="">
                  </div>

                  <div class="card-content">
                     <h3><?php echo htmlspecialchars($main['title']); ?></h3>
                     <p><?php echo (int)$main['sub_count']; ?> Classes available</p>

                     <a href="teacher_course_page.php?id=<?php echo $teacher_id; ?>&main_id=<?php echo $main['id']; ?>" class="open-btn">
                        View Sub Categories <i class="fa-solid fa-arrow-right"></i>
                     </a>
                  </div>
               </div>

            <?php } ?>

         <?php }else{ ?>
            <p class="empty">No Classes added yet. Teacher should add Classes first.</p>
         <?php } ?>

      </div>

   <?php } ?>

      </main>

   </div>

</div>

</body>
</html>
