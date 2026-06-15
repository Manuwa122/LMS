<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = $_SESSION['teacher_id'];

$videos = mysqli_query($conn, "SELECT * FROM videos WHERE teacher_id='$teacher_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>Teacher Videos</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
 
   <link rel="stylesheet" href="style3.css">


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

/* page background */
body{
   min-height:100vh;
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.25), transparent 35%),
      radial-gradient(circle at bottom right, rgba(36,0,70,.18), transparent 35%),
      linear-gradient(135deg, #f8f7ff 0%, #eef2ff 50%, #f4f4f5 100%) !important;
   color:var(--text);
}

/* header */
.header{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border-bottom:1px solid rgba(255,255,255,.9) !important;
   box-shadow:0 15px 35px rgba(31,41,55,.08);
}

.header .flex .logo{
   color:var(--deep) !important;
   font-weight:800;
   letter-spacing:1px;
}

.header .flex .search-form{
   background:rgba(255,255,255,.88) !important;
   border:1px solid rgba(108,56,255,.12);
   border-radius:18px !important;
   overflow:hidden;
   box-shadow:0 12px 30px rgba(108,56,255,.08);
}

.header .flex .search-form input{
   background:transparent !important;
   color:var(--text) !important;
}

.header .flex .search-form button{
   color:var(--deep) !important;
}

.header .flex .icons div{
   background:rgba(255,255,255,.85) !important;
   color:var(--deep) !important;
   border:1px solid rgba(108,56,255,.12);
   border-radius:14px !important;
   transition:.3s ease;
}

.header .flex .icons div:hover{
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   color:#fff !important;
   transform:translateY(-3px);
   box-shadow:0 12px 25px rgba(108,56,255,.25);
}

/* sidebar */
.side-bar{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(20px);
   border-right:1px solid rgba(255,255,255,.9) !important;
   box-shadow:20px 0 45px rgba(31,41,55,.08);
}

.side-bar .navbar a{
   margin:.7rem 1.2rem;
   border-radius:18px;
   transition:.3s ease;
}

.side-bar .navbar a i{
   color:var(--purple) !important;
}

.side-bar .navbar a span{
   color:var(--text) !important;
   font-weight:500;
}

.side-bar .navbar a:hover{
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   transform:translateX(6px);
   box-shadow:0 12px 28px rgba(108,56,255,.22);
}

.side-bar .navbar a:hover i,
.side-bar .navbar a:hover span{
   color:#fff !important;
}

/* main section */
.videos,
.courses,
.playlist-videos{
   padding:3rem 2rem !important;
}

.heading{
   color:var(--deep) !important;
   font-weight:800 !important;
   font-size:3rem !important;
   border-bottom:1px solid rgba(108,56,255,.18) !important;
   position:relative;
   padding-bottom:1.5rem !important;
   margin-bottom:2.5rem !important;
}

.heading::after{
   content:'';
   position:absolute;
   left:0;
   bottom:-2px;
   width:90px;
   height:4px;
   border-radius:20px;
   background:linear-gradient(135deg, var(--purple), var(--deep));
}

/* video card grid */
.box-container{
   display:grid !important;
   grid-template-columns:repeat(auto-fit, minmax(32rem, 1fr)) !important;
   gap:2.2rem !important;
}

/* video cards */
.box-container .box{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9) !important;
   border-radius:28px !important;
   box-shadow:var(--shadow);
   overflow:hidden;
   position:relative;
   transition:.32s ease;
   padding:2.2rem !important;
}

.box-container .box::before{
   content:'';
   position:absolute;
   width:150px;
   height:150px;
   border-radius:50%;
   background:rgba(108,56,255,.10);
   right:-50px;
   top:-50px;
   z-index:0;
}

.box-container .box > *{
   position:relative;
   z-index:2;
}

.box-container .box:hover{
   transform:translateY(-8px);
   box-shadow:0 30px 70px rgba(108,56,255,.16);
}

/* video thumbnail */
.box-container .box video,
.box-container .box .thumb video,
.box-container .box .thumb img{
   width:100% !important;
   height:220px !important;
   object-fit:cover !important;
   border-radius:22px !important;
   box-shadow:0 16px 35px rgba(31,41,55,.14);
   background:#000;
}

.box-container .box .thumb{
   border-radius:22px !important;
   overflow:hidden;
   margin-bottom:1.5rem;
}

/* text */
.box-container .box h3,
.box-container .box .title{
   color:var(--deep) !important;
   font-size:2rem !important;
   font-weight:800 !important;
   margin:1.3rem 0 .8rem !important;
}

.box-container .box p{
   color:var(--muted) !important;
   font-size:1.5rem !important;
   line-height:1.8;
}

.box-container .box p span{
   color:var(--purple) !important;
   font-weight:700;
}

/* empty message */
.empty{
   grid-column:1 / -1;
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border:1px dashed rgba(108,56,255,.45) !important;
   border-radius:24px !important;
   padding:2.5rem !important;
   color:var(--purple) !important;
   font-size:1.7rem !important;
   font-weight:800;
   text-align:center;
   box-shadow:var(--shadow);
}

/* buttons */
.btn,
.inline-btn,
.option-btn,
.inline-option-btn{
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   color:#fff !important;
   border-radius:16px !important;
   font-weight:700 !important;
   border:none !important;
   box-shadow:0 14px 28px rgba(108,56,255,.25);
   transition:.3s ease !important;
}

.btn:hover,
.inline-btn:hover,
.option-btn:hover,
.inline-option-btn:hover{
   transform:translateY(-4px);
   box-shadow:0 18px 38px rgba(108,56,255,.35);
}

.option-btn{
   background:linear-gradient(135deg, #ef4444, #dc2626) !important;
}

/* footer */
.footer{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border-top:1px solid rgba(255,255,255,.9) !important;
   color:var(--text) !important;
   box-shadow:0 -15px 35px rgba(31,41,55,.08);
}

.footer span{
   color:var(--purple) !important;
   font-weight:700;
}

/* dark mode */
body.dark{
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.20), transparent 35%),
      radial-gradient(circle at bottom right, rgba(14,165,233,.14), transparent 35%),
      linear-gradient(135deg, #070b16 0%, #111827 45%, #1e1b4b 100%) !important;
   color:#e5e7eb !important;
}

body.dark .header,
body.dark .side-bar,
body.dark .box-container .box,
body.dark .empty,
body.dark .footer{
   background:rgba(17,24,39,.75) !important;
   border:1px solid rgba(255,255,255,.08) !important;
   box-shadow:0 25px 60px rgba(0,0,0,.30) !important;
}

body.dark .heading,
body.dark .header .flex .logo,
body.dark .box-container .box h3,
body.dark .box-container .box .title,
body.dark .side-bar .navbar a span{
   color:#fff !important;
}

body.dark .box-container .box p,
body.dark .header .flex .search-form input,
body.dark .side-bar .navbar a span{
   color:#cbd5e1 !important;
}

body.dark .header .flex .search-form{
   background:rgba(255,255,255,.08) !important;
   border:1px solid rgba(255,255,255,.08) !important;
}

body.dark .header .flex .search-form input::placeholder{
   color:#94a3b8 !important;
}

/* fix fontawesome icons */
.fa,
.fas,
.fa-solid{
   font-family:"Font Awesome 6 Free" !important;
   font-weight:900 !important;
}

.far,
.fa-regular{
   font-family:"Font Awesome 6 Free" !important;
   font-weight:400 !important;
}

.fab,
.fa-brands{
   font-family:"Font Awesome 6 Brands" !important;
   font-weight:400 !important;
}

/* mobile */
@media(max-width:768px){
   .heading{
      font-size:2.4rem !important;
   }

   .box-container{
      grid-template-columns:1fr !important;
   }

   .box-container .box video,
   .box-container .box .thumb video,
   .box-container .box .thumb img{
      height:190px !important;
   }
}
</style>





</head>
<body>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">Educa.</a>

      <form action="" method="post" class="search-form">
         <input type="text" placeholder="search videos..." name="search_box" maxlength="100">
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

   <nav class="navbar">
      <a href="teacher_dashboard.php"><i class="fas fa-home"></i><span>dashboard</span></a>
      <a href="upload_video.php"><i class="fas fa-video"></i><span>upload video</span></a>
      <a href="teacher_videos.php"><i class="fas fa-play"></i><span>my videos</span></a>
      <a href="teacher_logout.php"><i class="fas fa-sign-out-alt"></i><span>logout</span></a>
   </nav>

</div>

<section class="courses">

   <h1 class="heading">my uploaded videos</h1>

   <div class="box-container">

      <?php
      if($videos && mysqli_num_rows($videos) > 0){

         while($row = mysqli_fetch_assoc($videos)){
      ?>

      <div class="box">

         <video width="100%" height="250" controls>
            <source src="<?php echo htmlspecialchars($row['video_file']); ?>" type="video/mp4">
            Your browser does not support video.
         </video>

         <h3 class="title" style="margin-top:1rem;">
            <?php echo htmlspecialchars($row['title']); ?>
         </h3>

         <p style="font-size:1.5rem; color:var(--light-color); padding-top:.5rem;">
            <?php echo htmlspecialchars($row['description']); ?>
         </p>

         <p style="font-size:1.3rem; color:var(--main-color); padding-top:1rem;">
            Uploaded : <?php echo $row['uploaded_at']; ?>
         </p>

         <a href="edit_video.php?id=<?php echo $row['id']; ?>" class="inline-btn">
            edit
         </a>

         <a href="delete_video.php?id=<?php echo $row['id']; ?>" 
            class="inline-btn"
            onclick="return confirm('Delete this video?');">
            delete
         </a>

      </div>

      <?php
         }

      }else{

         echo '<p class="empty">No videos uploaded yet!</p>';

      }
      ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>