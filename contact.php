<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: index.php?error=notloggedin");
    exit();
}

$username = $_SESSION['userUid'];
$email = $_SESSION['userEmail'];
$userId = $_SESSION['userId'];
?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style3.css">
     <link rel="icon" type="image/png" href="images/favicon.png">


   <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root{
   --purple:#6c38ff;
   --purple2:#8b5cf6;
   --deep:#240046;
   --light:#f5f3ff;
   --white:#ffffff;
   --text:#1f2937;
   --muted:#6b7280;
   --shadow:0 25px 60px rgba(31,41,55,.13);
}

*{
   font-family:'Poppins', sans-serif !important;
}

body{
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.22), transparent 35%),
      radial-gradient(circle at bottom right, rgba(36,0,70,.15), transparent 35%),
      linear-gradient(135deg, #f8f7ff 0%, #eef2ff 50%, #f4f4f5 100%) !important;
   color:var(--text);
}

/* Header */
.header{
   background:rgba(255,255,255,.75) !important;
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
   background:rgba(255,255,255,.85) !important;
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

/* Sidebar */
.side-bar{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(20px);
   border-right:1px solid rgba(255,255,255,.9) !important;
   box-shadow:20px 0 45px rgba(31,41,55,.08);
}

.side-bar .profile{
   padding-top:2rem;
}

.side-bar .profile .image{
   width:10rem;
   height:10rem;
   border-radius:30px !important;
   border:5px solid #fff;
   box-shadow:0 18px 35px rgba(108,56,255,.25);
}

.side-bar .profile .name{
   color:var(--deep) !important;
   font-weight:800;
}

.side-bar .profile .role{
   color:var(--muted) !important;
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

/* Common headings */
.heading{
   color:var(--deep) !important;
   font-weight:800 !important;
   font-size:3rem !important;
   border-bottom:1px solid rgba(108,56,255,.18) !important;
   position:relative;
   padding-bottom:1.5rem !important;
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

/* Home quick option cards */
.home-grid .box-container,
.courses .box-container{
   gap:2rem !important;
}

.home-grid .box-container .box,
.courses .box-container .box{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9) !important;
   border-radius:28px !important;
   box-shadow:var(--shadow);
   overflow:hidden;
   position:relative;
   transition:.32s ease;
}

.home-grid .box-container .box::before,
.courses .box-container .box::before{
   content:'';
   position:absolute;
   width:150px;
   height:150px;
   border-radius:50%;
   background:rgba(108,56,255,.10);
   right:-50px;
   top:-50px;
}

.home-grid .box-container .box:hover,
.courses .box-container .box:hover{
   transform:translateY(-8px);
   box-shadow:0 30px 70px rgba(108,56,255,.16);
}

.home-grid .box-container .box .title,
.courses .box-container .box .title{
   color:var(--deep) !important;
   font-weight:800 !important;
   position:relative;
   z-index:2;
}

.home-grid .box-container .box p,
.courses .box-container .box p{
   color:var(--muted) !important;
   line-height:1.8;
   position:relative;
   z-index:2;
}

.home-grid .box-container .box p span{
   color:var(--purple) !important;
   font-weight:800;
}

/* Category/topic chips */
.home-grid .box-container .box .flex a{
   background:#f5f3ff !important;
   border:1px solid rgba(108,56,255,.12);
   border-radius:14px !important;
   color:var(--muted) !important;
   transition:.3s ease;
}

.home-grid .box-container .box .flex a i{
   color:var(--deep) !important;
}

.home-grid .box-container .box .flex a:hover{
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   transform:translateY(-3px);
   box-shadow:0 12px 25px rgba(108,56,255,.22);
}

.home-grid .box-container .box .flex a:hover span,
.home-grid .box-container .box .flex a:hover i{
   color:#fff !important;
}

/* Buttons */
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
   position:relative;
   overflow:hidden;
}

.btn:hover,
.inline-btn:hover,
.option-btn:hover,
.inline-option-btn:hover{
   transform:translateY(-4px);
   box-shadow:0 18px 38px rgba(108,56,255,.35);
}

/* Course cards */
.courses .box-container .box .tutor{
   position:relative;
   z-index:2;
}

.courses .box-container .box .tutor img{
   border-radius:18px !important;
   border:4px solid #fff;
   box-shadow:0 12px 25px rgba(108,56,255,.20);
}

.courses .box-container .box .tutor h3{
   color:var(--deep) !important;
   font-weight:800 !important;
}

.courses .box-container .box .tutor span{
   color:var(--muted) !important;
}

.courses .box-container .box .thumb{
   border-radius:22px !important;
   overflow:hidden;
   box-shadow:0 16px 35px rgba(31,41,55,.12);
   position:relative;
   z-index:2;
}

.courses .box-container .box .thumb img{
   transition:.35s ease;
}

.courses .box-container .box:hover .thumb img{
   transform:scale(1.06);
}

.courses .box-container .box .thumb span{
   background:rgba(36,0,70,.75) !important;
   backdrop-filter:blur(10px);
   border-radius:999px !important;
   color:#fff !important;
   font-weight:700;
}

/* Profile dropdown */
.header .flex .profile{
   background:rgba(255,255,255,.88) !important;
   backdrop-filter:blur(18px);
   border:1px solid rgba(108,56,255,.12);
   border-radius:24px !important;
   box-shadow:0 20px 50px rgba(31,41,55,.16);
}

.header .flex .profile .image{
   border-radius:22px !important;
   border:4px solid #fff;
   box-shadow:0 12px 25px rgba(108,56,255,.20);
}

.header .flex .profile .name{
   color:var(--deep) !important;
   font-weight:800;
}

.header .flex .profile .role{
   color:var(--muted) !important;
}

/* Footer */
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

/* Mobile */
@media(max-width:768px){
   .heading{
      font-size:2.4rem !important;
   }

   .home-grid .box-container,
   .courses .box-container{
      grid-template-columns:1fr !important;
   }

   .header .flex .search-form{
      border-radius:16px !important;
   }
}

/* fix font awesome icons showing as boxes */
.fa,
.fas,
.fa-solid{
   font-family: "Font Awesome 6 Free" !important;
   font-weight: 900 !important;
}

.far,
.fa-regular{
   font-family: "Font Awesome 6 Free" !important;
   font-weight: 400 !important;
}

.fab,
.fa-brands{
   font-family: "Font Awesome 6 Brands" !important;
   font-weight: 400 !important;
}

/* for old template icomoon icons */
[class^="icon-"],
[class*=" icon-"]{
   font-family: "icomoon" !important;
}

/* DARK MODE GLASSY THEME */
body.dark{
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.20), transparent 35%),
      radial-gradient(circle at bottom right, rgba(14,165,233,.14), transparent 35%),
      linear-gradient(135deg, #070b16 0%, #111827 45%, #1e1b4b 100%) !important;
   color:#e5e7eb !important;
}

body.dark .header{
   background:rgba(15,23,42,.78) !important;
   border-bottom:1px solid rgba(255,255,255,.08) !important;
   box-shadow:0 15px 35px rgba(0,0,0,.25) !important;
}

body.dark .header .flex .logo,
body.dark .heading,
body.dark .home-grid .box-container .box .title,
body.dark .courses .box-container .box .title,
body.dark .side-bar .profile .name{
   color:#fff !important;
}

body.dark .header .flex .search-form,
body.dark .side-bar,
body.dark .home-grid .box-container .box,
body.dark .courses .box-container .box,
body.dark .header .flex .profile{
   background:rgba(17,24,39,.72) !important;
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.08) !important;
   box-shadow:0 25px 60px rgba(0,0,0,.30) !important;
}

body.dark .header .flex .search-form input,
body.dark .home-grid .box-container .box p,
body.dark .courses .box-container .box p,
body.dark .side-bar .navbar a span,
body.dark .side-bar .profile .role,
body.dark .courses .box-container .box .tutor span{
   color:#cbd5e1 !important;
}

body.dark .header .flex .search-form input::placeholder{
   color:#94a3b8 !important;
}

body.dark .home-grid .box-container .box .flex a{
   background:rgba(255,255,255,.08) !important;
   border:1px solid rgba(255,255,255,.08) !important;
}

body.dark .home-grid .box-container .box .flex a span,
body.dark .home-grid .box-container .box .flex a i{
   color:#e5e7eb !important;
}

body.dark .footer{
   background:rgba(15,23,42,.80) !important;
   color:#e5e7eb !important;
   border-top:1px solid rgba(255,255,255,.08) !important;
}


</style>





</head>
<body>

<header class="header">
   
   <section class="flex">

      <a href="home.php" class="logo">Educa.</a>

      <form action="search.php" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <img src="images/pic-1.jpg" class="image" alt="">
         <h3 class="name"><?php echo htmlspecialchars($username); ?></h3>
         <p class="role"><?php echo htmlspecialchars($email); ?></p>
         <a href="profile.php" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images/pic-1.jpg" class="image" alt="">
      <h3 class="name"><?php echo htmlspecialchars($username); ?></h3>
      <p class="role"><?php echo htmlspecialchars($email); ?></p>
      <a href="profile.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>about</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>
   </nav>

</div>

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="post">
         <h3>get in touch</h3>
         <input type="text" placeholder="enter your name" name="name" required maxlength="50" class="box">
         <input type="email" placeholder="enter your email" name="email" required maxlength="50" class="box">
         <input type="number" placeholder="enter your number" name="number" required maxlength="50" class="box">
         <textarea name="msg" class="box" placeholder="enter your message" required maxlength="1000" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>phone number</h3>
         <a href="tel:1234567890">+94-740-876-205</a>
         <a href="tel:1112223333">+94-740_876_205</a>
      </div>
      
      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>email address</h3>
         <a href="mailto:bcmanuwa7@gmail.com">bcmanuwa7@gmail.com</a>
         <a href="mailto:bcmanuwa7@gmail.com">bcmanuwa7@gmail.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>office address</h3>
         <a href="#">B 2/8 Egalla,Beligala, Srianka</a>
      </div>

   </div>

</section>














<footer class="footer">

   &copy; copyright @ 2026 by <span>Manuka Chamath</span> | all rights reserved!

</footer>

<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>