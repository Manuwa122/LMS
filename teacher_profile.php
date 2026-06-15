<?php
session_start();
include "db.php";

if(!isset($_GET['id'])){
   header("Location: courses.php");
   exit();
}

$teacher_id = mysqli_real_escape_string($conn, $_GET['id']);

$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1");

if(mysqli_num_rows($teacher_q) == 0){
   header("Location: courses.php");
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);

$classes = mysqli_query($conn, "
   SELECT * FROM teacher_classes 
   WHERE teacher_id='$teacher_id'
   ORDER BY class_year ASC, month_date ASC, id ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($teacher['name']); ?> Profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="style3.css">
     <link rel="icon" type="image/png" href="images/favicon.png">

   <style>
      body{
         background:#f4f2ff;
      }

      .teacher-profile-page{
         padding:2rem;
      }

      .profile-hero{
         background:linear-gradient(135deg,#2d0066,#6f38ff,#9d68ff);
         border-radius:2rem;
         padding:3rem;
         color:#fff;
         position:relative;
         overflow:hidden;
         box-shadow:0 2rem 5rem rgba(85,50,200,.25);
         margin-bottom:2.5rem;
      }

      .profile-hero::before{
         content:"";
         position:absolute;
         width:28rem;
         height:28rem;
         background:rgba(255,255,255,.13);
         border-radius:50%;
         right:-7rem;
         top:-7rem;
      }

      .profile-hero::after{
         content:"";
         position:absolute;
         width:18rem;
         height:18rem;
         background:rgba(255,255,255,.10);
         border-radius:50%;
         left:45%;
         bottom:-8rem;
      }

      .teacher-main{
         position:relative;
         z-index:2;
         display:flex;
         align-items:center;
         gap:2rem;
         flex-wrap:wrap;
      }

      .teacher-main img{
         width:12rem;
         height:12rem;
         object-fit:cover;
         border-radius:50%;
         border:.45rem solid rgba(255,255,255,.8);
         background:#fff;
      }

      .teacher-main h1{
         font-size:3.5rem;
         margin-bottom:.5rem;
         color:#fff;
      }

      .teacher-main p{
         font-size:1.7rem;
         color:#eee;
         margin:.4rem 0;
      }

      .teacher-info-grid{
         display:grid;
         grid-template-columns:1fr 36rem;
         gap:2rem;
         align-items:start;
      }

      .content-card{
         background:#fff;
         border-radius:1.5rem;
         padding:2rem;
         box-shadow:0 1rem 3rem rgba(0,0,0,.06);
         border:.1rem solid #ece8ff;
      }

      .section-heading{
         font-size:2.3rem;
         color:#210044;
         margin-bottom:1.5rem;
         padding-bottom:1rem;
         border-bottom:.1rem solid #ddd;
      }

      .section-heading span{
         border-bottom:.25rem solid #6f38ff;
         padding-bottom:1rem;
      }

      .month-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit, minmax(24rem, 1fr));
         gap:1.5rem;
      }

      .month-card{
         background:linear-gradient(180deg,#ffffff,#faf8ff);
         border:.1rem solid #e4dcff;
         border-radius:1.3rem;
         padding:1.8rem;
         transition:.25s ease;
         position:relative;
         overflow:hidden;
      }

      .month-card:hover{
         transform:translateY(-.4rem);
         box-shadow:0 1rem 3rem rgba(111,56,255,.15);
      }

      .month-card::before{
         content:"";
         position:absolute;
         width:9rem;
         height:9rem;
         border-radius:50%;
         background:#eee8ff;
         right:-3rem;
         top:-3rem;
      }

      .month-card h3{
         position:relative;
         font-size:2.1rem;
         color:#210044;
         margin-bottom:1rem;
      }

      .month-card p{
         position:relative;
         font-size:1.5rem;
         color:#666;
         margin-bottom:1.2rem;
      }

      .month-card i{
         color:#6f38ff;
         margin-right:.6rem;
      }

      .access-btn{
         position:relative;
         display:inline-block;
         border:none;
         background:#6f38ff;
         color:#fff;
         padding:1rem 2.4rem;
         border-radius:.8rem;
         font-size:1.5rem;
         font-weight:700;
         cursor:pointer;
         box-shadow:0 .8rem 2rem rgba(111,56,255,.25);
      }

      .access-btn:hover{
         background:#5626df;
         color:#fff;
      }

      .pending-btn{
         background:#777 !important;
         cursor:not-allowed;
         box-shadow:none;
      }

      .reject-btn{
         background:#e74c3c !important;
      }

      .approved-btn{
         background:#18b66b !important;
      }

      .side-card{
         background:#fff;
         border-radius:1.5rem;
         padding:2rem;
         box-shadow:0 1rem 3rem rgba(0,0,0,.06);
         border:.1rem solid #ece8ff;
         position:sticky;
         top:9rem;
      }

      .side-card img{
         width:100%;
         height:20rem;
         object-fit:cover;
         border-radius:1.2rem;
         background:#f1edff;
         margin-bottom:1.5rem;
      }

      .side-card h3{
         font-size:2rem;
         color:#210044;
         margin-bottom:1rem;
      }

      .side-card p{
         font-size:1.5rem;
         color:#666;
         line-height:1.7;
         margin-bottom:.8rem;
      }

      .bio-box{
         margin-top:2rem;
      }

      .bio-box p{
         font-size:1.6rem;
         color:#555;
         line-height:1.8;
      }

      .empty{
         font-size:1.7rem;
         color:#777;
         padding:1.5rem;
      }

      @media(max-width:991px){
         .teacher-info-grid{
            grid-template-columns:1fr;
         }

         .side-card{
            position:relative;
            top:0;
         }
      }

      @media(max-width:600px){
         .teacher-profile-page{
            padding:1rem;
         }

         .profile-hero{
            padding:2rem;
         }

         .teacher-main{
            text-align:center;
            justify-content:center;
         }

         .teacher-main h1{
            font-size:2.6rem;
         }

         .teacher-main img{
            width:10rem;
            height:10rem;
         }
      }
   </style>
</head>
<body>

<header class="header">
   <section class="flex">
      <a href="home.php" class="logo">Educa.</a>

      <form action="courses.php" method="post" class="search-form">
         <input type="text" name="search_box" placeholder="search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
   </section>
</header>

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images/pic-1.jpg" class="image" alt="">
      <h3 class="name">Student</h3>
      <p class="role">student</p>
      <a href="profile.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>about</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>
   </nav>
</div>

<section class="teacher-profile-page">

   <div class="profile-hero">
      <div class="teacher-main">
         <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" alt="">
         <div>
            <h1><?php echo htmlspecialchars($teacher['name']); ?></h1>
            <p><i class="fas fa-book"></i> <?php echo htmlspecialchars($teacher['subject']); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($teacher['email']); ?></p>
            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($teacher['phone']); ?></p>
         </div>
      </div>
   </div>

   <div class="teacher-info-grid">

      <div>
         <div class="content-card">
            <h2 class="section-heading"><span>Available Class Months</span></h2>

            <div class="month-grid">

               <?php
               if(mysqli_num_rows($classes) > 0){
                  while($class = mysqli_fetch_assoc($classes)){

                     $class_id = $class['id'];
                     $access_status = "none";

                     if(isset($_SESSION['student_id'])){
                        $student_id = $_SESSION['student_id'];

                        $check_payment = mysqli_query($conn, "
                           SELECT * FROM class_payments 
                           WHERE student_id='$student_id' 
                           AND class_id='$class_id'
                           LIMIT 1
                        ");

                        if(mysqli_num_rows($check_payment) > 0){
                           $payment = mysqli_fetch_assoc($check_payment);
                           $access_status = $payment['status'];
                        }
                     }
               ?>

                  <div class="month-card">
                     <h3><?php echo htmlspecialchars($class['class_month']); ?></h3>

                     <p>
                        <i class="fa-regular fa-calendar-days"></i>
                        <?php
                        if(!empty($class['month_date'])){
                           echo date("j-M-Y", strtotime($class['month_date']));
                        }else{
                           echo "Date not added";
                        }
                        ?>
                     </p>

                     <p>
                        <i class="fa-solid fa-money-bill"></i>
                        Fee: LKR <?php echo htmlspecialchars($class['fee']); ?>
                     </p>

                     <p>
                        <i class="fa-regular fa-credit-card"></i>
                        <?php echo htmlspecialchars($class['payment_scheme']); ?>
                     </p>

                     <?php if(!isset($_SESSION['student_id'])){ ?>

                        <a href="student_login.php?teacher_id=<?php echo $teacher_id; ?>" class="access-btn">
                           Login To Access
                        </a>

                     <?php }elseif($access_status == "approved"){ ?>

                        <a href="playlist.php?class_id=<?php echo $class_id; ?>" class="access-btn approved-btn">
                           View Class
                        </a>

                     <?php }elseif($access_status == "pending"){ ?>

                        <button class="access-btn pending-btn" disabled>
                           Pending Approval
                        </button>

                     <?php }elseif($access_status == "rejected"){ ?>

                        <a href="upload_receipt.php?class_id=<?php echo $class_id; ?>&teacher_id=<?php echo $teacher_id; ?>" class="access-btn reject-btn">
                           Upload Again
                        </a>

                     <?php }else{ ?>

                        <a href="upload_receipt.php?class_id=<?php echo $class_id; ?>&teacher_id=<?php echo $teacher_id; ?>" class="access-btn">
                           Upload Receipt
                        </a>

                     <?php } ?>

                  </div>

               <?php
                  }
               }else{
                  echo '<p class="empty">No class months added for this teacher yet.</p>';
               }
               ?>

            </div>
         </div>

         <div class="content-card bio-box">
            <h2 class="section-heading"><span>About Teacher</span></h2>
            <p><?php echo nl2br(htmlspecialchars($teacher['bio'])); ?></p>
         </div>
      </div>

      <div class="side-card">
         <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" alt="">

         <h3><?php echo htmlspecialchars($teacher['name']); ?></h3>
         <p><b>Subject:</b> <?php echo htmlspecialchars($teacher['subject']); ?></p>
         <p><b>Email:</b> <?php echo htmlspecialchars($teacher['email']); ?></p>
         <p><b>Phone:</b> <?php echo htmlspecialchars($teacher['phone']); ?></p>

         <a href="courses.php" class="access-btn" style="margin-top:1rem;">
            Back To Courses
         </a>
      </div>

   </div>

</section>

<footer class="footer">
   &copy; copyright @ 2026 by <span>Manuka Chamath</span> | all rights reserved!
</footer>

<script src="js/script.js"></script>

</body>
</html>