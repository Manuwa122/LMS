<?php
session_start();
include "db.php";

$message = "";

$email = "";

if(isset($_GET['email'])){
   $email = $_GET['email'];
}elseif(isset($_SESSION['teacher_verify_email'])){
   $email = $_SESSION['teacher_verify_email'];
}

if(isset($_POST['verify'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $otp = mysqli_real_escape_string($conn, $_POST['otp']);

   $result = mysqli_query($conn, "SELECT * FROM teachers WHERE email='$email' AND otp_code='$otp'");

   if(mysqli_num_rows($result) > 0){
      $teacher = mysqli_fetch_assoc($result);

      if(strtotime($teacher['otp_expires']) < time()){
         $message = "OTP expired! Please register again.";
      }else{
         mysqli_query($conn, "UPDATE teachers SET email_verified=1, otp_code=NULL, otp_expires=NULL WHERE email='$email'");

         unset($_SESSION['teacher_verify_email']);

         header("Location: teacher_login.php?verified=success");
         exit();
      }
   }else{
      $message = "Invalid OTP code!";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Teacher Email Verification</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
      }

      .verify-container{
         width:950px;
         min-height:520px;
         background:#fff;
         border-radius:10px;
         overflow:hidden;
         display:flex;
         box-shadow:0 20px 50px rgba(0,0,0,.35);
      }

      .verify-left{
         width:50%;
         background:linear-gradient(135deg,#251064,#5126bd);
         color:#fff;
         display:flex;
         flex-direction:column;
         align-items:center;
         justify-content:center;
         text-align:center;
         padding:50px;
      }

      .verify-left h1{
         font-size:44px;
         margin-bottom:20px;
      }

      .verify-left p{
         font-size:18px;
         line-height:1.6;
         color:rgba(255,255,255,.75);
      }

      .verify-right{
         width:50%;
         padding:60px 65px;
         display:flex;
         flex-direction:column;
         justify-content:center;
         align-items:center;
      }

      .verify-right h2{
         font-size:38px;
         color:#777;
         margin-bottom:15px;
      }

      .email-box{
         width:100%;
         background:#eef3fb;
         padding:16px;
         border-radius:4px;
         text-align:center;
         margin-bottom:20px;
         word-break:break-all;
      }

      .otp-input{
         width:100%;
         height:62px;
         border:none;
         outline:none;
         background:#eef3fb;
         color:#222;
         font-size:25px;
         text-align:center;
         letter-spacing:12px;
         border-radius:4px;
         margin-bottom:25px;
         font-weight:600;
      }

      .otp-input::placeholder{
         font-size:18px;
         letter-spacing:2px;
         font-weight:400;
      }

      .error-msg{
         width:100%;
         color:#e63946;
         background:#ffe9ec;
         padding:12px;
         border-radius:5px;
         text-align:center;
         margin-bottom:15px;
      }

      .verify-btn{
         width:65%;
         display:block;
         margin:0 auto;
         border:none;
         background:#7868e6;
         color:#fff;
         padding:16px 20px;
         border-radius:30px;
         font-weight:700;
         cursor:pointer;
      }

      @media(max-width:850px){
         .verify-container{
            width:90%;
            flex-direction:column;
         }

         .verify-left,
         .verify-right{
            width:100%;
            padding:40px 25px;
         }
      }
   </style>
</head>
<body>

<div class="verify-container">

   <div class="verify-left">
      <h1>Check Email!</h1>
      <p>We sent a 6 digit OTP code to your teacher email.</p>
   </div>

   <div class="verify-right">
      <h2>Verify Email</h2>

      <?php if($email != ""): ?>
         <div class="email-box"><?php echo htmlspecialchars($email); ?></div>
      <?php endif; ?>

      <?php
      if($message != ""){
         echo '<div class="error-msg">'.$message.'</div>';
      }
      ?>

      <form method="post" style="width:100%;">
         <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

         <input 
            type="text" 
            name="otp" 
            class="otp-input" 
            placeholder="Enter OTP" 
            maxlength="6" 
            required
            inputmode="numeric"
            pattern="[0-9]{6}"
         >

         <input type="submit" name="verify" value="VERIFY EMAIL" class="verify-btn">
      </form>
   </div>

</div>

</body>
</html>