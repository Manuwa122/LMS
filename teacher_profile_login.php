<?php
include "db.php";

if(!isset($_GET['id'])){
   header("Location: teachers.php");
   exit();
}

$teacher_id = $_GET['id'];
$message = "";

$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id'");

if(mysqli_num_rows($teacher_q) == 0){
   echo "Teacher not found!";
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);

if(isset($_POST['login'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password'];

   if($email == $teacher['email'] && password_verify($password, $teacher['password'])){

      $_SESSION['teacher_id'] = $teacher['id'];

      header("Location: teacher_dashboard.php");
      exit();

   }else{
      $message = "Wrong email or password!";
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Teacher Profile Login</title>
   <link rel="icon" type="image/png" href="images/favicon.png">

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      html,
      body {
         width: 100%;
         height: 100%;
         margin: 0 !important;
         padding: 0 !important;
         overflow: hidden !important;
         background: #111827 !important;
      }

      body {
         display: block !important;
      }

      .teacher-login-page {
         position: fixed !important;
         top: 0 !important;
         left: 0 !important;
         width: 100% !important;
         height: 100vh !important;
         min-height: 100vh !important;
         margin: 0 !important;
         padding: 2rem !important;

         display: flex !important;
         align-items: center !important;
         justify-content: center !important;

         background:
            radial-gradient(circle at 15% 20%, rgba(255,255,255,.10), transparent 18%),
            radial-gradient(circle at 75% 70%, rgba(96,165,250,.25), transparent 23%),
            linear-gradient(135deg, #111827 0%, #1e1b4b 45%, #2563eb 100%) !important;

         overflow: hidden !important;
         z-index: 99999 !important;
      }

      .teacher-login-card {
         width: 100% !important;
         max-width: 48rem !important;
         background: rgba(255, 255, 255, .13) !important;
         border: 1px solid rgba(255, 255, 255, .22) !important;
         border-radius: 2.5rem !important;
         padding: 4rem 3.5rem !important;
         backdrop-filter: blur(2rem) !important;
         box-shadow: 0 2rem 6rem rgba(0,0,0,.35) !important;
         position: relative !important;
         z-index: 10 !important;
      }

      .teacher-login-bg span {
         position: absolute;
         width: 24rem;
         height: 24rem;
         border-radius: 50%;
         background: rgba(255,255,255,.08);
         filter: blur(.5rem);
         animation: teacherFloat 10s infinite ease-in-out;
      }

      .teacher-login-bg span:nth-child(1) {
         top: 8%;
         left: 8%;
      }

      .teacher-login-bg span:nth-child(2) {
         bottom: 10%;
         right: 10%;
         animation-delay: 2s;
      }

      .teacher-login-bg span:nth-child(3) {
         top: 55%;
         left: 55%;
         animation-delay: 4s;
      }

      @keyframes teacherFloat {
         0%, 100% {
            transform: translateY(0) scale(1);
         }
         50% {
            transform: translateY(-3rem) scale(1.08);
         }
      }

      .teacher-icon {
         width: 8.5rem;
         height: 8.5rem;
         margin: 0 auto 1.5rem;
         border-radius: 50%;
         background: linear-gradient(135deg, #38bdf8, #8b5cf6);
         display: flex;
         align-items: center;
         justify-content: center;
         color: #fff;
         font-size: 3.5rem;
         box-shadow: 0 1rem 3rem rgba(56,189,248,.35);
      }

      .teacher-login-card h3 {
         font-size: 3rem;
         color: #fff;
         text-align: center;
         margin-bottom: .7rem;
         letter-spacing: .1rem;
      }

      .login-subtitle {
         text-align: center;
         color: #dbeafe;
         font-size: 1.5rem;
         margin-bottom: 3rem;
      }

      .input-group {
         margin-bottom: 2rem;
      }

      .input-group label {
         display: block;
         color: #e5e7eb;
         font-size: 1.5rem;
         margin-bottom: .8rem;
      }

      .input-group label span {
         color: #fb7185;
      }

      .input-box {
         width: 100%;
         height: 5.5rem;
         background: rgba(255,255,255,.14);
         border: 1px solid rgba(255,255,255,.18);
         border-radius: 1.3rem;
         display: flex;
         align-items: center;
         padding: 0 1.5rem;
      }

      .input-box i {
         color: #38bdf8;
         font-size: 1.7rem;
         margin-right: 1.2rem;
      }

      .input-box input {
         width: 100%;
         height: 100%;
         background: transparent;
         border: none;
         outline: none;
         color: #fff;
         font-size: 1.6rem;
      }

      .input-box input::placeholder {
         color: #cbd5e1;
      }

      .teacher-login-btn {
         width: 100%;
         height: 5.5rem;
         border: none;
         border-radius: 1.3rem;
         background: linear-gradient(135deg, #38bdf8, #8b5cf6);
         color: #fff;
         font-size: 1.8rem;
         font-weight: 700;
         cursor: pointer;
      }

      .register-link {
         margin-top: 2rem;
         text-align: center;
         color: #dbeafe;
         font-size: 1.5rem;
      }

      .register-link a {
         color: #38bdf8;
         font-weight: 700;
         text-decoration: none;
      }

      @media (max-width: 600px) {
         .teacher-login-card {
            padding: 3rem 2rem !important;
         }

         .teacher-login-card h3 {
            font-size: 2.4rem;
         }
      }

      /* TEACHER LOGIN SMALL CENTER CARD FIX */

.teacher-login-page {
   width: 100vw !important;
   min-height: 100vh !important;
   padding: 2rem !important;
   display: flex !important;
   align-items: center !important;
   justify-content: center !important;
}

.teacher-login-card {
   width: 100% !important;
   max-width: 38rem !important;
   padding: 2.5rem 2.2rem !important;
   border-radius: 1.8rem !important;
   margin: auto !important;
}

.teacher-icon {
   width: 6rem !important;
   height: 6rem !important;
   font-size: 2.4rem !important;
   margin-bottom: 1rem !important;
}

.teacher-login-card h3 {
   font-size: 2.3rem !important;
   margin-bottom: .5rem !important;
}

.login-subtitle {
   font-size: 1.25rem !important;
   margin-bottom: 2rem !important;
}

.teacher-form .input-group,
.input-group {
   margin-bottom: 1.4rem !important;
}

.teacher-form label,
.input-group label {
   font-size: 1.25rem !important;
   margin-bottom: .5rem !important;
}

.teacher-form .input-box,
.input-box {
   height: 4.6rem !important;
   border-radius: 1rem !important;
   padding: 0 1.2rem !important;
}

.teacher-form .input-box input,
.input-box input {
   font-size: 1.3rem !important;
}

.teacher-form .input-box i,
.input-box i {
   font-size: 1.4rem !important;
}

.teacher-login-btn {
   height: 4.7rem !important;
   font-size: 1.5rem !important;
   border-radius: 1rem !important;
}

.register-link {
   font-size: 1.25rem !important;
   margin-top: 1.5rem !important;
}




   </style>








</head>

<body class="teacher-login-body">

<section class="teacher-login-page">

   <div class="teacher-login-bg">
      <span></span>
      <span></span>
      <span></span>
   </div>

   <div class="teacher-login-card">

      <div style="text-align:center; margin-bottom:1.5rem;">
         <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" 
              alt="Teacher"
              style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:4px solid rgba(255,255,255,.6); box-shadow:0 1rem 2rem rgba(0,0,0,.25);">
      </div>

      <h3>Teacher Login</h3>

      <p class="login-subtitle">
         Login as <strong><?php echo htmlspecialchars($teacher['name']); ?></strong><br>
         <?php echo htmlspecialchars($teacher['subject']); ?>
      </p>

      <?php
      if($message != ""){
         echo '<p style="color:#ffb4b4; font-size:1.5rem; text-align:center; margin-bottom:1rem;">'.$message.'</p>';
      }
      ?>

      <form action="" method="post" class="teacher-form">

         <div class="input-group">
            <label>Email <span>*</span></label>
            <div class="input-box">
               <i class="fas fa-envelope"></i>
               <input type="email" name="email" placeholder="Enter teacher email" required maxlength="50">
            </div>
         </div>

         <div class="input-group">
            <label>Password <span>*</span></label>
            <div class="input-box">
               <i class="fas fa-lock"></i>
               <input type="password" name="password" id="teacherPass" placeholder="Enter password" required maxlength="50">
               <i class="fas fa-eye toggle-pass" id="toggleTeacherPass"></i>
            </div>
         </div>

         <input type="submit" value="Login Profile" name="login" class="teacher-login-btn">

         <p class="register-link">
            don't have account?
            <a href="teacher_register.php">register now</a>
         </p>

         <p class="register-link" style="margin-top:1rem;">
            <a href="teachers.php">Back to Teachers</a>
         </p>

      </form>

   </div>

</section>

<script>
const teacherPass = document.getElementById('teacherPass');
const toggleTeacherPass = document.getElementById('toggleTeacherPass');

if (teacherPass && toggleTeacherPass) {
   toggleTeacherPass.addEventListener('click', function () {
      if (teacherPass.type === 'password') {
         teacherPass.type = 'text';
         toggleTeacherPass.classList.remove('fa-eye');
         toggleTeacherPass.classList.add('fa-eye-slash');
      } else {
         teacherPass.type = 'password';
         toggleTeacherPass.classList.remove('fa-eye-slash');
         toggleTeacherPass.classList.add('fa-eye');
      }
   });
}
</script>

</body>
</html>