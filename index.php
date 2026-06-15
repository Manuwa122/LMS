<!DOCTYPE html>
<html lang="en">
  <head>
    <title>E_Academy</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">


    <style>
   .teacher-nav-area{
      width: 38%;
      display: flex;
      justify-content: flex-end;
      align-items: center;
   }

   .teacher-top-buttons{
      display: flex !important;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
      white-space: nowrap;
   }

   .teacher-top-buttons li{
      display: inline-block;
      margin: 0 !important;
   }

   .teacher-top-buttons li a{
      padding: 0 !important;
   }

   .teacher-top-buttons li a span{
      display: inline-block;
      padding: 14px 24px !important;
      border-radius: 30px;
      font-weight: 700;
      font-size: 14px;
   }

   .teacher-login-btn a span{
      background: #2b1055 !important;
      color: #fff !important;
      transition: .3s ease;
   }

   .teacher-login-btn a span:hover{
      background: #7868e6 !important;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(120,104,230,.35);
   }



  
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root{
   --purple:#6c38ff;
   --purple2:#8b5cf6;
   --deep:#240046;
   --glass:rgba(255,255,255,.78);
   --white:#fff;
   --text:#1f2937;
   --muted:#6b7280;
   --shadow:0 30px 80px rgba(0,0,0,.25);
}

*{
   font-family:'Poppins', sans-serif !important;
}

body{
   background:#0f172a;
}

/* Header glass look */
.site-navbar{
   background:rgba(15,23,42,.35) !important;
   backdrop-filter:blur(18px);
   border-bottom:1px solid rgba(255,255,255,.08);
   transition:.3s ease;

}

.site-navbar.scrolled,
.js-sticky-header.is-sticky{
   background:rgba(255,255,255,.82) !important;
   backdrop-filter:blur(20px);
   box-shadow:0 18px 45px rgba(0,0,0,.12);
}

.site-logo a{
   color:#fff !important;
   font-weight:800 !important;
   letter-spacing:1px;
   font-size:25px !important;
}

.site-navbar.scrolled .site-logo a,
.js-sticky-header.is-sticky .site-logo a{
   color:var(--deep) !important;
}

.site-menu li a{
   color:#fff !important;
   font-weight:600 !important;
   font-size:16px !important;
   position:relative;
}

.site-navbar.scrolled .site-menu li a,
.js-sticky-header.is-sticky .site-menu li a{
   color:var(--deep) !important;
}

.site-menu li a::after{
   content:'';
   position:absolute;
   left:50%;
   bottom:0;
   width:0;
   height:3px;
   background:linear-gradient(135deg, var(--purple), var(--purple2));
   border-radius:20px;
   transform:translateX(-50%);
   transition:.3s ease;
}

.site-menu li a:hover::after{
   width:70%;
}

/* Contact + Teacher Login buttons */
.teacher-nav-area{
   width:40% !important;
   display:flex;
   justify-content:flex-end;
   align-items:center;
}

.teacher-top-buttons{
   display:flex !important;
   align-items:center;
   justify-content:flex-end;
   gap:14px;
   white-space:nowrap;
}

.teacher-top-buttons li{
   margin:0 !important;
}

.teacher-top-buttons li a{
   padding:0 !important;
}

.teacher-img{
   width: 12rem;
   height: 12rem;
   border-radius: 50%;
   object-fit: cover;
   object-position: center top;
   display: block;
   margin: 0 auto 2rem;
}




.teacher-top-buttons li a span,
.site-menu .cta a span{
   display:inline-flex !important;
   align-items:center;
   justify-content:center;
   padding:16px 28px !important;
   border-radius:999px !important;
   font-size:14px !important;
   font-weight:800 !important;
   letter-spacing:.3px;
   color:#fff !important;
   border:none !important;
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   box-shadow:0 16px 35px rgba(108,56,255,.32);
   transition:.3s ease;
}

.teacher-login-btn a span{
   background:linear-gradient(135deg, var(--deep), #3b0764) !important;
}

.teacher-top-buttons li a span:hover,
.site-menu .cta a span:hover{
   transform:translateY(-4px);
   box-shadow:0 22px 45px rgba(108,56,255,.45);
}

/* Hero background overlay */
.intro-section{
   min-height:100vh;
   background:#0f172a;
}

.slide-1{
   min-height:100vh !important;
   position:relative;
   background-size:cover !important;
   background-position:center !important;
}

.slide-1::before{
   content:'';
   position:absolute;
   inset:0;
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.32), transparent 35%),
      radial-gradient(circle at bottom right, rgba(36,0,70,.28), transparent 35%),
      linear-gradient(135deg, rgba(15,23,42,.82), rgba(15,23,42,.66));
   z-index:1;
}

.slide-1 .container{
   position:relative;
   z-index:2;
}

/* Login/signup main glass card */
.sign{
   width:100%;
   display:flex;
   align-items:center;
   justify-content:center;
   padding-top:60px;
}

.sign .container#container{
   background:rgba(255,255,255,.70) !important;
   backdrop-filter:blur(20px);
   border:1px solid rgba(255,255,255,.85);
   border-radius:32px !important;
   box-shadow:var(--shadow);
   overflow:hidden;
   min-height:560px;
   max-width:980px;
   width:100%;
   position:relative;
}

.sign .container#container::before{
   content:'';
   position:absolute;
   width:250px;
   height:250px;
   border-radius:50%;
   background:rgba(108,56,255,.13);
   top:-80px;
   right:-80px;
   z-index:5;
   pointer-events:none;
}

.sign .container#container::after{
   content:'';
   position:absolute;
   width:180px;
   height:180px;
   border-radius:50%;
   background:rgba(36,0,70,.11);
   bottom:-70px;
   left:-70px;
   z-index:5;
   pointer-events:none;
}

/* Form side */
.form-container{
   background:rgba(255,255,255,.72) !important;
}

.form-container form{
   background:transparent !important;
   padding:0 55px !important;
}

.form-container h1.title{
   font-size:42px !important;
   font-weight:800 !important;
   color:var(--deep) !important;
   letter-spacing:.5px;
   margin-bottom:14px !important;
}

.form-container input{
   width:100% !important;
   height:58px !important;
   border:none !important;
   outline:none !important;
   background:#f3f4ff !important;
   border:1px solid rgba(108,56,255,.12) !important;
   border-radius:17px !important;
   padding:0 20px !important;
   margin:10px 0 !important;
   color:var(--text) !important;
   font-size:16px !important;
   transition:.25s ease !important;
}

.form-container input:focus{
   background:#fff !important;
   border-color:rgba(108,56,255,.55) !important;
   box-shadow:0 0 0 5px rgba(108,56,255,.10) !important;
}

.form-container input::placeholder{
   color:#7b8190 !important;
}

.form-container a{
   color:var(--deep) !important;
   font-weight:600 !important;
   text-decoration:none !important;
   margin:12px 0 18px !important;
}

.form-container a:hover{
   color:var(--purple) !important;
}

.form-container button{
   min-width:180px !important;
   height:56px !important;
   border:none !important;
   border-radius:999px !important;
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   color:#fff !important;
   font-size:14px !important;
   font-weight:800 !important;
   letter-spacing:1px !important;
   box-shadow:0 16px 32px rgba(108,56,255,.30);
   transition:.3s ease !important;
   position:relative;
   overflow:hidden;
}

.form-container button:hover{
   transform:translateY(-4px);
   box-shadow:0 22px 45px rgba(108,56,255,.42);
}

/* Purple overlay side */
.overlay-container{
   border-radius:0 32px 32px 0 !important;
}

.overlay{
   background:
      radial-gradient(circle at top left, rgba(255,255,255,.18), transparent 35%),
      linear-gradient(135deg, var(--deep), var(--purple), var(--purple2)) !important;
}

.overlay-panel{
   padding:0 55px !important;
}

.overlay-panel h1{
   color:#fff !important;
   font-size:43px !important;
   font-weight:800 !important;
   letter-spacing:1px;
   margin-bottom:18px !important;
}

.overlay-panel p{
   color:rgba(255,255,255,.78) !important;
   font-size:16px !important;
   line-height:1.7 !important;
   font-weight:500 !important;
   margin-bottom:32px !important;
}

.overlay-panel .ghost{
   background:rgba(255,255,255,.08) !important;
   border:1px solid rgba(255,255,255,.85) !important;
   color:#fff !important;
   border-radius:999px !important;
   padding:15px 50px !important;
   font-weight:800 !important;
   transition:.3s ease !important;
}

.overlay-panel .ghost:hover{
   background:#fff !important;
   color:var(--deep) !important;
   transform:translateY(-4px);
}

/* Error/success messages */
.form-container div[style*="color:red"]{
   background:#fff1f2 !important;
   color:#e11d48 !important;
   padding:10px 14px !important;
   border-radius:14px !important;
   font-size:14px !important;
   font-weight:700 !important;
   margin-bottom:8px;
}

.form-container div[style*="color:green"]{
   background:#ecfdf5 !important;
   color:#059669 !important;
   padding:10px 14px !important;
   border-radius:14px !important;
   font-size:14px !important;
   font-weight:700 !important;
   margin-bottom:8px;
}

/* Sections below hero glass style */
.site-section{
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.10), transparent 30%),
      linear-gradient(135deg, #f8f7ff, #eef2ff) !important;
}

.courses-title{
   background:linear-gradient(135deg, var(--deep), var(--purple)) !important;
}

.section-title{
   color:var(--deep) !important;
   font-weight:800 !important;
   font-size:38px !important;
   position:relative;
}

.courses-title .section-title{
   color:#fff !important;
}

.course,
.teacher,
.why-choose-us-box,
#contact-section form{
   background:rgba(255,255,255,.78) !important;
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:28px !important;
   box-shadow:0 25px 60px rgba(31,41,55,.13);
   overflow:hidden;
   transition:.3s ease;
}

.course:hover,
.teacher:hover,
.why-choose-us-box:hover{
   transform:translateY(-8px);
   box-shadow:0 32px 75px rgba(108,56,255,.18);
}

.course-price,
.btn-primary{
   background:linear-gradient(135deg, var(--purple), var(--purple2)) !important;
   border:none !important;
   border-radius:999px !important;
   box-shadow:0 14px 28px rgba(108,56,255,.25);
}

.form-control{
   background:#f3f4ff !important;
   border:1px solid rgba(108,56,255,.12) !important;
   border-radius:17px !important;
   min-height:56px !important;
   padding:15px 18px !important;
}

.form-control:focus{
   background:#fff !important;
   border-color:rgba(108,56,255,.55) !important;
   box-shadow:0 0 0 5px rgba(108,56,255,.10) !important;
}

.footer-section{
   background:rgba(255,255,255,.82) !important;
   backdrop-filter:blur(18px);
}

/* Mobile responsive */
@media(max-width:991px){
   .teacher-nav-area{
      width:auto !important;
   }

   .sign{
      padding:90px 15px 40px;
   }

   .sign .container#container{
      min-height:auto;
      width:94%;
   }

   .form-container form{
      padding:0 25px !important;
   }

   .form-container h1.title{
      font-size:32px !important;
   }

   .overlay-panel h1{
      font-size:32px !important;
   }
}

@media(max-width:768px){
   .sign .container#container{
      border-radius:24px !important;
   }

   .site-logo a{
      font-size:22px !important;
   }

   .section-title{
      font-size:30px !important;
   }
}


/* make login/signup card smaller */
.sign .container#container{
   max-width: 850px !important;
   min-height: 455px !important;
   border-radius: 26px !important;
}

/* reduce form spacing */
.form-container form{
   padding: 0 45px !important;
}

.form-container h1.title{
   font-size: 34px !important;
   margin-bottom: 8px !important;
}

.form-container input{
   height: 48px !important;
   font-size: 14px !important;
   border-radius: 14px !important;
   margin: 8px 0 !important;
   padding: 0 18px !important;
}

.form-container button{
   min-width: 160px !important;
   height: 48px !important;
   font-size: 13px !important;
}

/* right purple panel smaller text */
.overlay-panel{
   padding: 0 45px !important;
}

.overlay-panel h1{
   font-size: 36px !important;
   margin-bottom: 14px !important;
}

.overlay-panel p{
   font-size: 15px !important;
   line-height: 1.6 !important;
   margin-bottom: 26px !important;
}

.overlay-panel .ghost{
   padding: 13px 44px !important;
   font-size: 13px !important;
}

/* center card better */
.sign{
   padding-top: 35px !important;
}

/* mobile */
@media(max-width:768px){
   .sign .container#container{
      max-width: 92% !important;
      min-height: 430px !important;
   }

   .form-container form{
      padding: 0 25px !important;
   }

   .form-container h1.title{
      font-size: 30px !important;
   }

   .overlay-panel h1{
      font-size: 30px !important;
   }
}



/* ===== TEACHER CARD IMAGE FORCE FIX ===== */

#teachers-section .teacher{
   background: #fff !important;
   border-radius: 30px !important;
   padding: 45px 35px 45px !important;
   min-height: 430px !important;
   overflow: hidden !important;
   position: relative !important;
   margin-top: 0 !important;
}

#teachers-section .teacher img{
   width: 120px !important;
   height: 120px !important;
   max-width: 120px !important;
   border-radius: 50% !important;
   object-fit: cover !important;
   object-position: center top !important;

   display: block !important;
   margin: 0 auto 35px auto !important;

   position: static !important;
   top: auto !important;
   left: auto !important;
   transform: none !important;
}

#teachers-section .teacher .py-2{
   padding-top: 0 !important;
}

#teachers-section .teacher h3{
   margin-top: 0 !important;
}



/* ===== SIGN IN / SIGN UP OVERLAY FIX ===== */

.sign .container{
   position: relative;
   width: 950px;
   max-width: 100%;
   min-height: 570px;
   overflow: hidden;
   border-radius: 30px;
   background: rgba(255,255,255,0.95);
}

.sign .form-container{
   position: absolute;
   top: 0;
   height: 100%;
   width: 50%;
   transition: all .6s ease-in-out;
}

.sign .sign-in-container{
   left: 0;
   z-index: 2;
}

.sign .sign-up-container{
   left: 0;
   opacity: 0;
   z-index: 1;
}

.sign .container.right-panel-active .sign-in-container{
   transform: translateX(100%);
   opacity: 0;
   z-index: 1;
}

.sign .container.right-panel-active .sign-up-container{
   transform: translateX(100%);
   opacity: 1;
   z-index: 5;
}

.sign form{
   background: transparent;
   display: flex;
   flex-direction: column;
   align-items: center;
   justify-content: center;
   padding: 0 50px;
   height: 100%;
   text-align: center;
}

.sign form input{
   width: 100%;
   padding: 14px 20px;
   margin: 8px 0;
   border-radius: 15px;
   border: 1px solid #d8d5f3;
   outline: none;
   font-size: 16px;
   background: #f4f2ff;
}

.sign form a{
   color: #777;
   font-size: 15px;
   margin: 8px 0 15px;
   text-decoration: none;
   position: relative;
   z-index: 20;
}

.sign form a:hover{
   color: #5b2df5;
   text-decoration: underline;
}

.sign form button{
   margin-top: 10px;
   border-radius: 25px;
   border: none;
   background: linear-gradient(135deg, #5b2df5, #8b4dff);
   color: #fff;
   font-size: 15px;
   font-weight: 700;
   padding: 14px 55px;
   letter-spacing: 2px;
   cursor: pointer;
}

.sign .overlay-container{
   position: absolute;
   top: 0;
   left: 50%;
   width: 50%;
   height: 100%;
   overflow: hidden;
   transition: transform .6s ease-in-out;
   z-index: 100;
}

.sign .container.right-panel-active .overlay-container{
   transform: translateX(-100%);
}

.sign .overlay{
   background: linear-gradient(135deg, #3b147d, #7438ff);
   color: #fff;
   position: relative;
   left: -100%;
   height: 100%;
   width: 200%;
   transform: translateX(0);
   transition: transform .6s ease-in-out;
}

.sign .container.right-panel-active .overlay{
   transform: translateX(50%);
}

.sign .overlay-panel{
   position: absolute;
   display: flex;
   align-items: center;
   justify-content: center;
   flex-direction: column;
   padding: 0 40px;
   text-align: center;
   top: 0;
   height: 100%;
   width: 50%;
}

.sign .overlay-left{
   transform: translateX(-20%);
}

.sign .overlay-right{
   right: 0;
   transform: translateX(0);
}

.sign .ghost{
   background: transparent !important;
   border: 1px solid #fff !important;
}


/* Sign up form scroll fix */
.sign .sign-up-container form{
   justify-content: flex-start !important;
   padding: 35px 50px 25px !important;
   overflow-y: auto !important;
   overflow-x: hidden !important;
}

.sign .sign-up-container form .title{
   margin-bottom: 12px !important;
}

.sign form input,
.sign form select{
   width: 100% !important;
   padding: 12px 18px !important;
   margin: 6px 0 !important;
   border-radius: 15px !important;
   border: 1px solid #d8d5f3 !important;
   outline: none !important;
   font-size: 15px !important;
   background: #f4f2ff !important;
   color: #333 !important;
}

.sign form select{
   cursor: pointer;
}

.sign .sign-up-container form::-webkit-scrollbar{
   width: 6px;
}

.sign .sign-up-container form::-webkit-scrollbar-thumb{
   background: #7b4cff;
   border-radius: 10px;
}

.sign .sign-up-container form::-webkit-scrollbar-track{
   background: transparent;
}


/* ===== SIGN UP BUTTON VISIBLE FIX ===== */

.sign .container{
   min-height: 650px !important;
}

.sign .sign-up-container form{
   height: 100% !important;
   max-height: 650px !important;
   overflow-y: auto !important;
   justify-content: flex-start !important;
   padding: 25px 55px 35px !important;
}

.sign .sign-up-container form input,
.sign .sign-up-container form select{
   padding: 10px 16px !important;
   margin: 5px 0 !important;
   height: 52px !important;
   font-size: 15px !important;
}

.sign .sign-up-container form .title{
   font-size: 38px !important;
   margin-bottom: 10px !important;
}

.sign .sign-up-container form button{
   width: 60% !important;
   min-height: 55px !important;
   margin: 14px auto 10px !important;
   display: block !important;
   flex-shrink: 0 !important;
   position: relative !important;
   bottom: auto !important;
}


.brand-logo{
   display: flex;
   align-items: center;
   gap: 10px;
   text-decoration: none;
}

.brand-logo img{
   width: 42px;
   height: 42px;
   object-fit: cover;
   border-radius: 50%;
}

.brand-logo span{
   color: #fff;
   font-size: 28px;
   font-weight: 900;
   letter-spacing: 2px;
}








</style>

  </head>
  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>


    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <div class="site-logo mr-auto w-25">
   <a href="index.php" class="brand-logo">
      <img src="images/logo-icon.png" alt="E Academy Logo">
      <span>E_Academy</span>
   </a>
</div>

          <div class="mx-auto text-center">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block  m-0 p-0">
                <li><a href="#home-section" class="nav-link">Home</a></li>
                <li><a href="#courses-section" class="nav-link">Courses</a></li>
                <li><a href="#programs-section" class="nav-link">Programs</a></li>
              </ul>
            </nav>
          </div>

          <div class="ml-auto teacher-nav-area">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu site-menu-dark js-clone-nav teacher-top-buttons d-none d-lg-flex m-0 p-0">
                  <li class="cta">
                 <a href="#contact-section" class="nav-link"><span>Contact Us</span></a>
                   </li>

                 <li class="cta teacher-login-btn">
                  <a href="teacher_gate.php" class="nav-link"><span>Teacher Login</span></a>
                 </li>
              </ul>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
          </div>
        </div>
      </div>

    </header>

    <div class="intro-section" id="home-section">

      <div class="slide-1" style="background-image: url('images/hero_1.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="sign">
                <!--sign-->
                <div class="container <?php echo (isset($_GET['errorp']) || isset($_GET['signup']))? 'right-panel-active':''; ?>" id="container">
                  <div class="form-container sign-up-container">
                    <form action="includes/signup1.inc.php" method="post" id="signup_form">
   <h1 class="title">Sign Up</h1>

   <?php
   if (isset($_GET['errorp'])){
      if ($_GET['errorp']=="emptyfields"){
         echo '<div style="color:red;">Fill in all fields !</div>';
      }else if ($_GET['errorp']=="invalidmailuid"){
         echo '<div style="color:red;">Invalid username and e-mail !</div>';
      }else if ($_GET['errorp']=="invaliduid"){
         echo '<div style="color:red;">Invalid username !</div>';
      }else if ($_GET['errorp']=="invalidmail"){
         echo '<div style="color:red;">Invalid e-mail !</div>';
      }else if ($_GET['errorp']=="passwordcheck"){
         echo '<div style="color:red;">Passwords don\'t match!</div>';
      }else if ($_GET['errorp']=="usertaken"){
         echo '<div style="color:red;">Username / Email / ID already taken!</div>';
      }
   }elseif (isset($_GET['signup'])) {
      if ($_GET['signup']=="success"){
         echo '<div style="color:green;">Signup Successful ! Feel free to login !</div>';
      }
   }
   ?>

   <input type="text" id="name_signup" name="uid" placeholder="Name" required>

   <input type="email" id="mail_signup" name="mail" placeholder="E-mail" required>

   <select name="gender" id="gender_signup" required>
      <option value="">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
   </select>

   <input type="date" id="birthday_signup" name="birthday" required>

   <input type="text" id="id_number_signup" name="id_number" placeholder="ID Number" required>

   <input type="password" id="pwd1_signup" name="pwd" placeholder="Password" required>

   <input type="password" id="pwd2_signup" name="pwd-repeat" placeholder="Repeat Password" required>

   <button type="submit">Sign Up</button>
</form>
                  </div>
                  <div class="form-container sign-in-container">
                    <form action="includes/login.inc.php" method="post" id="signin_form">
                      <h1 class="title">Sign in</h1>
                      <br>
                      <?php
                      if (isset($_GET['error'])){
                        if ($_GET['error']=="emptyfields"){
                          echo '<div style="color:red;">Fill in all fields !</div>';
                        }else if ($_GET['error']=="wrongpwd"){
                          echo '<div style="color:red;">Wrong password!</div>';
                        }else if ($_GET['error']=="nomatch"){
                          echo '<div style="color:red;">There\'s no match for your email !</div>';

                      }elseif (isset($_GET['login']) ) {
                        if ($_GET['signup']=="success")
                          echo '<div style="color:green;">Sign in Successful !</div>';
                      }
                  }?>
                      <div id="error_signin_mail"></div>
                      <input type="email" id="mailsignin" placeholder="Email" name="mailuid" placeholder="Username/E-mail"
                        value="<?php if (isset($_GET['error'])){
                                        if ($_GET['error']=="wrongpwd" || $_GET['error']=="emptyfields"){
                                              echo  isset($_GET['mail'])?$_GET['mail']:'';
                                        }else {
                                              echo '';
                                             }
                    }  ?>" />
                      <div id="error_signin_pwd"></div>
                      <input type="password" id="pwdsignin" name="pwd" placeholder="Password" />
                      <a href="mail_input.php">Forgot your password?</a>
                      <button>Sign In</button>
                    </form>
                  </div>
                  <div class="overlay-container">
                    <div class="overlay">
                    <div id="overlay-left" class="overlay-panel overlay-left">
                          <h1>Welcome Back!</h1>
                          <p>To keep connected with us please login with your personal info</p>
                          <button class="ghost" id="signIn">Sign In</button>
                        </div>
                        <div class="overlay-panel overlay-right">
                          <h1>Hello, Learner!</h1>
                          <p>Enter your personal details and start journey with us</p>
                          <button class="ghost" id="signUp">Sign Up</button>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
            <!--end-->
          </div>
          </div>
          </div>
          </div>
        </div>
      </div>
    </div>


    <div class="site-section courses-title" id="courses-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="">
            <h2 class="section-title">Courses</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section courses-entry-wrap"  data-aos="fade-up" data-aos-delay="100">
      <div class="container">
        <div class="row">

          <div class="owl-carousel col-12 nonloop-block-14">

            <div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/physics.png" alt="Physics" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>12 Lessons / 8 week</div>
    <h3><a href="#">Physics</a></h3>
    <p>Explore motion, forces, waves, electricity and modern physics.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 2,450 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 7</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/maths.png" alt="Mathematics" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>15 Lessons / 10 week</div>
    <h3><a href="#">Mathematics</a></h3>
    <p>Master algebra, calculus, trigonometry and problem solving.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 3,120 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 9</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/arts.png" alt="Arts" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>8 Lessons / 6 week</div>
    <h3><a href="#">Arts</a></h3>
    <p>Learn creativity, visual arts, culture and design thinking.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 1,850 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 4</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/politics.png" alt="Politics" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>10 Lessons / 7 week</div>
    <h3><a href="#">Politics</a></h3>
    <p>Understand governments, political systems and global affairs.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 1,630 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 5</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/science.png" alt="Science" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>14 Lessons / 9 week</div>
    <h3><a href="#">Science</a></h3>
    <p>Discover experiments, scientific methods and real world concepts.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 2,980 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 8</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/data-science.png" alt="Data Science" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>16 Lessons / 12 week</div>
    <h3><a href="#">Data Science</a></h3>
    <p>Learn data analysis, visualization, statistics and AI basics.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 3,540 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 11</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/computer-science.png" alt="Computer Science" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>18 Lessons / 12 week</div>
    <h3><a href="#">Computer Science</a></h3>
    <p>Study programming, algorithms, databases and computer systems.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 4,120 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 14</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/biology.png" alt="Biology" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>13 Lessons / 9 week</div>
    <h3><a href="#">Biology</a></h3>
    <p>Learn about cells, genetics, human body and living organisms.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 2,760 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 6</div>
  </div>
</div>

<div class="course bg-white h-100 align-self-stretch">
  <figure class="m-0">
    <img src="images/engineering-technology.png" alt="Engineering Technology" class="img-fluid">
  </figure>
  <div class="course-inner-text py-4 px-4">
    <span class="course-price">$0</span>
    <div class="meta"><span class="icon-clock-o"></span>15 Lessons / 10 week</div>
    <h3><a href="#">Engineering Technology</a></h3>
    <p>Build knowledge in mechanics, electronics and modern technology.</p>
  </div>
  <div class="d-flex border-top stats">
    <div class="py-3 px-4"><span class="icon-users"></span> 2,340 students</div>
    <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> 10</div>
  </div>
</div>
          </div>



        </div>
        <div class="row justify-content-center">
          <div class="col-7 text-center">
            <button class="customPrevBtn btn btn-primary m-1">Prev</button>
            <button class="customNextBtn btn btn-primary m-1">Next</button>
          </div>
        </div>
      </div>
    </div>


    <div class="site-section" id="programs-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 text-center"  data-aos="fade-up" data-aos-delay="">
            <h2 class="section-title">Our Programs</h2>
            <p>We aim to make studying SIMPLE, EASY and ACCESSIBLE to EVERYONE thus we collected the BEST COURSES in the world in one place.</p>
          </div>
        </div>
        <div class="row mb-5 align-items-center">
          <div class="col-lg-7 mb-5" data-aos="fade-up" data-aos-delay="100">
            <img src="images/undraw_youtube_tutorial.svg" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-4 ml-auto" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-black mb-4">We Are Excellent In Education</h2>
            <p class="mb-4">Education is an art and we are the artists.</p>

            <div class="d-flex align-items-center custom-icon-wrap mb-3">
              <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
              <div><h3 class="m-0">Learn Anytime, Anywhere</h3></div>
            </div>

            <div class="d-flex align-items-center custom-icon-wrap">
              <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
              <div><h3 class="m-0">Video Lessons & Study Packs</h3></div>
            </div>

          </div>
        </div>

        <div class="row mb-5 align-items-center">
          <div class="col-lg-7 mb-5 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
            <img src="images/undraw_teaching.svg" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-4 mr-auto order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-black mb-4">Strive for Excellent</h2>
            <p class="mb-4">our goal is your success.</p>

            <div class="d-flex align-items-center custom-icon-wrap mb-3">
              <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
              <div><h3 class="m-0">Learn Anytime, Anywhere</h3></div>
            </div>

            <div class="d-flex align-items-center custom-icon-wrap">
              <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
              <div><h3 class="m-0">Video Lessons & Study Packs</h3></div>
            </div>

          </div>
        </div>

        <div class="row mb-5 align-items-center">
          <div class="col-lg-7 mb-5" data-aos="fade-up" data-aos-delay="100">
            <img src="images/undraw_teacher.svg" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-4 ml-auto" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-black mb-4">Education is life</h2>
            <p class="mb-4"> Access your teacher's monthly classes, video lessons, and study packs from one simple online platform..</p>

            <div class="d-flex align-items-center custom-icon-wrap mb-3">
              <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
              <div><h3 class="m-0">Learn Anytime, Anywhere</h3></div>
            </div>

            <div class="d-flex align-items-center custom-icon-wrap">
              <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
              <div><h3 class="m-0">Video Lessons & Study Packs</h3></div>
            </div>

          </div>
        </div>

      </div>
    </div>


    <div class="site-section bg-image overlay" style="background-image: url('images/hero_1.jpg');">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-8 text-center testimony">
            <img src="images/devl.png" alt="Image" class="img-fluid w-25 mb-4 rounded-circle">
            <h3 class="mb-4">Manuka Chamath</h3>
            <blockquote>
              <p>&ldquo; E-Academy helped to you learn from trusted teachers, access monthly class videos, 
                and study anytime from home. It makes online learning simple, organized, and effective. &rdquo;</p>
            </blockquote>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section pb-0">

      <div class="future-blobs">
        <div class="blob_2">
          <img src="images/blob_2.svg" alt="Image">
        </div>
        <div class="blob_1">
          <img src="images/blob_1.svg" alt="Image">
        </div>
      </div>
      <div class="container">
        <div class="row mb-5 justify-content-center" data-aos="fade-up" data-aos-delay="">
          <div class="col-lg-7 text-center">
            <h2 class="section-title">Why Choose Us</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 ml-auto align-self-start"  data-aos="fade-up" data-aos-delay="100">

            <div class="p-4 rounded bg-white why-choose-us-box">

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                <div><h3 class="m-0">Monthly Class Access</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                <div><h3 class="m-0">Teacher Approved Lessons</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                <div><h3 class="m-0">Secure Payment Receipt System</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                <div><h3 class="m-0">Learn Anytime, Anywhere</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                <div><h3 class="m-0">Study Packs for Every Subject</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                <div><h3 class="m-0">Best Teachers</h3></div>
              </div>

            </div>


          </div>
          <div class="col-lg-7 align-self-end"  data-aos="fade-left" data-aos-delay="200">
            <img src="images/person_transparent.png" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <div class="site-section bg-light" id="contact-section">
      <div class="container">

        <div class="row justify-content-center">
          <div class="col-md-7">

            <h2 class="section-title mb-3">Message Us</h2>
            <p class="mb-5">We are more than happy to receive your suggestions.</p>
            <!-- Beginning of the php for the contact form -->
            <?php
            // Message Vars
            $msg = '';
            $msgClass = '';

            // Check For Submit
            if(filter_has_var(INPUT_POST, 'submit')){
              // Get Form Data
              $name = htmlspecialchars($_POST['name']);
              $email = htmlspecialchars($_POST['email']);
              $message = htmlspecialchars($_POST['message']);
              $subject = htmlspecialchars($_POST['subject']);

              // Check Required Fields
              if(!empty($email) && !empty($name) && !empty($message) && !empty($subject)){
                // Passed
                // Check Email
                if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                  // Failed
                  $msg = 'Please use a valid email';
                  $msgClass = 'alert-danger';
                } else {
                  // Passed
                  $toEmail = 'support@lacademy.com';
                  $body = $subject.'<h4>Name</h4><p>'.$name.'</p>
                    <h4>Email</h4><p>'.$email.'</p>
                    <h4>Message</h4><p>'.$message.'</p>';

                  // Email Headers
                  $headers = "MIME-Version: 1.0" ."\r\n";
                  $headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";

                  // Additional Headers
                  $headers .= "From: " .$name. "<".$email.">". "\r\n";

                  if(mail($toEmail, $subject, $body, $headers)){
                    // Email Sent
                    $msg = 'Your email has been sent';
                    $msgClass = 'alert-success';
                  } else {
                    // Failed
                    $msg = 'Your email was not sent';
                    $msgClass = 'alert-danger';
                  }
                }
              } else {
                // Failed
                $msg = 'Please fill in all fields';
                $msgClass = 'alert-danger';
              }
            }
             ?>
             <?php if($msg != ''): ?>
                 <div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
               <?php endif; ?>
            <!-- End of the php for the contact form -->
            <form method="post" action="index.php#contact-section" data-aos="fade" id="contact_form">
              <div class="form-group row">
                <div class="col-md-12">
                  <div id="error_contact_fullname"></div>
                  <input type="text" name="name" id="contact_fullname" class="form-control" placeholder="Full name" value="<?php echo isset($_POST['name']) ? $name : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <div id="error_contact_subject"></div>
                  <input type="text" id="contact_subject" name="subject" class="form-control" placeholder="Subject" >
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <div id="error_contact_email"></div>
                  <input type="email" id="contact_email" name="email"  class="form-control" placeholder="Email" value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <div id="error_contact_message"></div>
                  <textarea class="form-control" id="contact_message" name="message" cols="30" rows="10" placeholder="Write your message here."><?php echo isset($_POST['message']) ? $message : ''; ?></textarea>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <input type="submit" name="submit" class="btn btn-primary py-3 px-5 btn-block btn-pill" value="Send Message">
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>


    <footer class="footer-section bg-white">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h3>About LAcademy</h3>
            <p>An E-Learning platform rich of resources, We make learning easy and simple for Everyone.</p>
          </div>

          <div class="col-md-3 ml-auto">
            <h3>Links</h3>
            <ul class="list-unstyled footer-links">
              <li><a href="#home-section" class="nav-link">Home</a></li>
              <li><a href="#courses-section" class="nav-link">Courses</a></li>
              <li><a href="#programs-section" class="nav-link">Programs</a></li>
            </ul>
          </div>

          <div class="col-md-4">
            <h3>Subscribe</h3>
            <p>Keep yourself up to date and receive all kind of news about LAcademy.</p>
            <form action="https://mailchi.mp/064deb47eeaa/lacdemy" target="_blank" class="footer-subscribe">
              <div class="d-flex mb-5">

                <input type="submit" class="btn btn-primary rounded-0" value="Subscribe">
              </div>
            </form>
          </div>

        </div>

        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
            <p>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
      </p>
            </div>
          </div>

        </div>
      </div>
    </footer>



  </div> <!-- .site-wrap -->

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>


  <script src="js/main.js"></script>

  </body>
</html>
