<?php
session_start();
include "db.php";
require "includes/send_otp_mail.php";

$message = "";

if(isset($_POST['register'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $subject = mysqli_real_escape_string($conn, $_POST['subject']);
   $phone = mysqli_real_escape_string($conn, $_POST['phone']);
   $bio = mysqli_real_escape_string($conn, $_POST['bio']);
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm_password'];

   if($password !== $confirm_password){
      $message = "Passwords do not match!";
   }else{

      $check = mysqli_query($conn, "SELECT * FROM teachers WHERE email='$email'");

      if(mysqli_num_rows($check) > 0){
         $message = "Teacher already registered!";
      }else{

         $hashed_password = password_hash($password, PASSWORD_DEFAULT);
         $otp = rand(100000, 999999);
         $otp_expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

         $profile_image = "images/pic-1.jpg";

         $insert = mysqli_query($conn, "INSERT INTO teachers
         (name, email, subject, phone, bio, profile_image, password, email_verified, otp_code, otp_expires)
         VALUES
         ('$name', '$email', '$subject', '$phone', '$bio', '$profile_image', '$hashed_password', 0, '$otp', '$otp_expires')");

         if($insert){
            if(sendOTPEmail($email, $name, $otp)){
               $_SESSION['teacher_verify_email'] = $email;
               header("Location: verify_teacher_email.php?email=$email");
               exit();
            }else{
               $message = "OTP email send failed!";
            }
         }else{
            $message = "Teacher registration failed!";
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Teacher Register</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="images/favicon.png">

   <style>
      *{box-sizing:border-box;}

      body{
         margin:0;
         min-height:100vh;
         font-family:'Segoe UI', Arial, sans-serif;
         background:
            linear-gradient(rgba(17,24,39,.78), rgba(17,24,39,.78)),
            url('images/hero_1.jpg');
         background-size:cover;
         background-position:center;
         display:flex;
         align-items:center;
         justify-content:center;
         padding:40px 0;
      }

      .register-container{
         width:1000px;
         background:#fff;
         border-radius:10px;
         overflow:hidden;
         display:flex;
         box-shadow:0 20px 50px rgba(0,0,0,.35);
      }

      .register-left{
         width:45%;
         background:linear-gradient(135deg,#251064,#5126bd);
         color:#fff;
         display:flex;
         flex-direction:column;
         align-items:center;
         justify-content:center;
         text-align:center;
         padding:50px;
      }

      .register-left h1{
         font-size:44px;
         margin-bottom:20px;
      }

      .register-left p{
         font-size:18px;
         line-height:1.6;
         color:rgba(255,255,255,.75);
         margin-bottom:35px;
      }

      .register-left a{
         text-decoration:none;
         color:#fff;
         border:1px solid #fff;
         border-radius:30px;
         padding:14px 45px;
         font-weight:700;
         transition:.3s;
      }

      .register-left a:hover{
         background:#fff;
         color:#4320a0;
      }

      .register-right{
         width:55%;
         padding:45px 60px;
      }

      .register-right h2{
         text-align:center;
         font-size:38px;
         color:#777;
         margin-bottom:25px;
      }

      .box{
         width:100%;
         height:55px;
         border:none;
         outline:none;
         background:#eef3fb;
         color:#222;
         font-size:16px;
         padding:0 18px;
         margin-bottom:15px;
         border-radius:4px;
      }

      textarea.box{
         height:90px;
         resize:none;
         padding-top:15px;
      }

      .error-msg{
         color:#e63946;
         background:#ffe9ec;
         padding:12px;
         border-radius:5px;
         text-align:center;
         margin-bottom:15px;
         font-size:15px;
      }

      .register-btn{
         width:60%;
         display:block;
         margin:10px auto 0;
         border:none;
         outline:none;
         background:#7868e6;
         color:#fff;
         padding:16px 20px;
         border-radius:30px;
         font-size:15px;
         font-weight:700;
         cursor:pointer;
         transition:.3s;
      }

      .register-btn:hover{
         background:#5b4bd6;
         transform:translateY(-2px);
      }

      .login-link{
         text-align:center;
         margin-top:20px;
         color:#777;
         font-size:15px;
      }

      .login-link a{
         color:#5126bd;
         text-decoration:none;
         font-weight:700;
      }

      @media(max-width:850px){
         .register-container{
            width:90%;
            flex-direction:column;
         }

         .register-left,
         .register-right{
            width:100%;
            padding:40px 25px;
         }
      }
   </style>
</head>
<body>

<div class="register-container">

   <div class="register-left">
      <h1>Hello Teacher!</h1>
      <p>Create your teacher account and verify your email with OTP.</p>
      <a href="teacher_login.php">SIGN IN</a>
   </div>

   <div class="register-right">
      <h2>Teacher Register</h2>

      <?php
      if($message != ""){
         echo '<div class="error-msg">'.$message.'</div>';
      }
      ?>

      <form method="post">
         <input type="text" name="name" class="box" placeholder="Teacher Name" required>
         <input type="email" name="email" class="box" placeholder="Teacher Email" required>
         <input type="text" name="subject" class="box" placeholder="Subject" required>
         <input type="text" name="phone" class="box" placeholder="Phone Number">
         <textarea name="bio" class="box" placeholder="Write about teacher"></textarea>
         <input type="password" name="password" class="box" placeholder="Password" required>
         <input type="password" name="confirm_password" class="box" placeholder="Repeat Password" required>

         <input type="submit" name="register" value="REGISTER" class="register-btn">
      </form>

      <div class="login-link">
         Already have an account? <a href="teacher_login.php">Login now</a>
      </div>
   </div>

</div>

</body>
</html>