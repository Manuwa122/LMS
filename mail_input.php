<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot Password</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <style>
      *{
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         font-family: 'Poppins', Arial, sans-serif;
      }

      body{
         min-height: 100vh;
         background:
            linear-gradient(rgba(10, 10, 30, .65), rgba(10, 10, 30, .65)),
            url('images/hero_1.jpg');
         background-size: cover;
         background-position: center;
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 20px;
      }

      .forgot-wrapper{
         width: 980px;
         max-width: 100%;
         min-height: 540px;
         display: grid;
         grid-template-columns: 1fr 1fr;
         background: rgba(255,255,255,.92);
         border-radius: 32px;
         overflow: hidden;
         box-shadow: 0 30px 90px rgba(0,0,0,.35);
         border: 1px solid rgba(255,255,255,.35);
      }

      .forgot-left{
         background: linear-gradient(135deg, #3b147d, #7438ff);
         color: #fff;
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         text-align: center;
         padding: 50px;
         position: relative;
         overflow: hidden;
      }

      .forgot-left::before{
         content: '';
         position: absolute;
         width: 260px;
         height: 260px;
         border-radius: 50%;
         background: rgba(255,255,255,.08);
         bottom: -90px;
         left: -90px;
      }

      .forgot-left::after{
         content: '';
         position: absolute;
         width: 180px;
         height: 180px;
         border-radius: 50%;
         background: rgba(255,255,255,.1);
         top: -60px;
         right: -60px;
      }

      .forgot-left i{
         font-size: 70px;
         margin-bottom: 25px;
         position: relative;
         z-index: 1;
      }

      .forgot-left h1{
         font-size: 42px;
         letter-spacing: 2px;
         margin-bottom: 20px;
         position: relative;
         z-index: 1;
      }

      .forgot-left p{
         font-size: 17px;
         line-height: 1.7;
         opacity: .9;
         max-width: 380px;
         position: relative;
         z-index: 1;
      }

      .forgot-right{
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 55px;
         background: rgba(255,255,255,.95);
      }

      .forgot-box{
         width: 100%;
         max-width: 420px;
         text-align: center;
      }

      .forgot-box h2{
         font-size: 42px;
         color: #240046;
         margin-bottom: 12px;
         letter-spacing: 1px;
      }

      .forgot-box .small-text{
         color: #777;
         font-size: 15px;
         line-height: 1.6;
         margin-bottom: 28px;
      }

      .input-group{
         position: relative;
         margin-bottom: 18px;
      }

      .input-group i{
         position: absolute;
         top: 50%;
         left: 18px;
         transform: translateY(-50%);
         color: #7b4cff;
         font-size: 16px;
      }

      .input-group input{
         width: 100%;
         height: 58px;
         border-radius: 16px;
         border: 1px solid #d8d5f3;
         background: #f4f2ff;
         outline: none;
         padding: 0 18px 0 50px;
         font-size: 16px;
         color: #222;
      }

      .input-group input:focus{
         border-color: #7b4cff;
         box-shadow: 0 0 0 4px rgba(123,76,255,.12);
      }

      .send-btn{
         width: 60%;
         height: 56px;
         border: none;
         border-radius: 30px;
         background: linear-gradient(135deg, #5b2df5, #8b4dff);
         color: #fff;
         font-size: 15px;
         font-weight: 800;
         letter-spacing: 2px;
         cursor: pointer;
         margin-top: 10px;
         box-shadow: 0 15px 35px rgba(91,45,245,.35);
         transition: .3s ease;
      }

      .send-btn:hover{
         transform: translateY(-3px);
         box-shadow: 0 20px 45px rgba(91,45,245,.45);
      }

      .back-login{
         display: inline-block;
         margin-top: 24px;
         color: #6b4cff;
         text-decoration: none;
         font-size: 15px;
         font-weight: 600;
      }

      .back-login:hover{
         text-decoration: underline;
      }

      .error,
      .success{
         padding: 12px 15px;
         border-radius: 12px;
         margin-bottom: 18px;
         font-size: 14px;
         font-weight: 600;
      }

      .error{
         background: rgba(255,0,0,.09);
         color: #d00000;
         border: 1px solid rgba(255,0,0,.18);
      }

      .success{
         background: rgba(0,160,80,.09);
         color: #008040;
         border: 1px solid rgba(0,160,80,.18);
      }

      @media(max-width: 850px){
         .forgot-wrapper{
            grid-template-columns: 1fr;
         }

         .forgot-left{
            padding: 40px 25px;
            min-height: 260px;
         }

         .forgot-left h1{
            font-size: 34px;
         }

         .forgot-right{
            padding: 40px 25px;
         }

         .forgot-box h2{
            font-size: 34px;
         }

         .send-btn{
            width: 100%;
         }
      }
   </style>
</head>
<body>

   <div class="forgot-wrapper">

      <div class="forgot-left">
         <i class="fas fa-lock"></i>
         <h1>Forgot Password?</h1>
         <p>
            Don't worry! Enter your registered email address and we will send you a verification code to reset your password.
         </p>
      </div>

      <div class="forgot-right">
         <div class="forgot-box">

            <h2>Reset Password</h2>
            <p class="small-text">
               Enter your E-mail address to receive your verification code.
            </p>

            <?php
            if(isset($_GET['error'])){
               if($_GET['error'] == "empty"){
                  echo '<div class="error">Please enter your email address.</div>';
               }elseif($_GET['error'] == "notfound"){
                  echo '<div class="error">This email is not registered.</div>';
               }elseif($_GET['error'] == "emailnotsent"){
                  echo '<div class="error">Verification email sending failed.</div>';
               }
            }

            if(isset($_GET['success'])){
               echo '<div class="success">Verification code sent successfully!</div>';
            }
            ?>

            <form action="includes/send_reset_otp.php" method="post">
               <div class="input-group">
                  <i class="fas fa-envelope"></i>
                  <input type="email" name="email" placeholder="Enter your E-mail" required>
               </div>

               <button type="submit" name="send_otp" class="send-btn">SEND CODE</button>
            </form>

            <a href="index.php" class="back-login">
               <i class="fas fa-arrow-left"></i> Back to Login
            </a>

         </div>
      </div>

   </div>

</body>
</html>