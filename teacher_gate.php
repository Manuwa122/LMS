<?php
session_start();

$error = "";

/*
   මෙතන ඔයාට teacher login open කරන්න දෙන්න ඕන secret code එක දාන්න.
   Example: MANUKA2026
*/
$secret_code = "197839";

if(isset($_POST['verify_code'])){
   $entered_code = trim($_POST['access_code']);

   if($entered_code === $secret_code){
      $_SESSION['teacher_access'] = true;
      header("Location: teacher_login.php");
      exit();
   }else{
      $error = "Invalid access code!";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Teacher Access</title>
     <link rel="icon" type="image/png" href="images/favicon.png">

   <style>
      *{
         margin:0;
         padding:0;
         box-sizing:border-box;
         font-family:Arial, sans-serif;
      }

      body{
         min-height:100vh;
         display:flex;
         align-items:center;
         justify-content:center;
         background:
            linear-gradient(rgba(10,10,30,.72), rgba(10,10,30,.72)),
            url('images/hero_1.jpg');
         background-size:cover;
         background-position:center;
         padding:20px;
      }

      .access-box{
         width:950px;
         max-width:100%;
         min-height:540px;
         display:grid;
         grid-template-columns:1fr 1fr;
         background:rgba(255,255,255,.95);
         border-radius:30px;
         overflow:hidden;
         box-shadow:0 30px 90px rgba(0,0,0,.35);
      }

      .left{
         background:linear-gradient(135deg,#3b147d,#7438ff);
         color:#fff;
         display:flex;
         flex-direction:column;
         align-items:center;
         justify-content:center;
         text-align:center;
         padding:50px;
      }

      .left h1{
         font-size:42px;
         letter-spacing:2px;
         margin-bottom:20px;
      }

      .left p{
         font-size:17px;
         line-height:1.7;
         opacity:.9;
         max-width:380px;
      }

      .right{
         display:flex;
         align-items:center;
         justify-content:center;
         padding:55px;
      }

      .form-box{
         width:100%;
         max-width:420px;
         text-align:center;
      }

      .form-box h2{
         font-size:40px;
         color:#240046;
         margin-bottom:12px;
      }

      .form-box p{
         color:#666;
         font-size:15px;
         line-height:1.6;
         margin-bottom:25px;
      }

      .whatsapp-btn{
         display:block;
         width:100%;
         padding:16px;
         border-radius:30px;
         background:#25D366;
         color:#fff;
         text-decoration:none;
         font-weight:800;
         margin-bottom:20px;
         letter-spacing:1px;
      }

      input{
         width:100%;
         height:56px;
         border-radius:15px;
         border:1px solid #d8d5f3;
         background:#f4f2ff;
         outline:none;
         padding:0 18px;
         font-size:16px;
         margin-bottom:18px;
         text-align:center;
         letter-spacing:2px;
      }

      button{
         width:65%;
         height:55px;
         border:none;
         border-radius:30px;
         background:linear-gradient(135deg,#5b2df5,#8b4dff);
         color:#fff;
         font-weight:800;
         letter-spacing:2px;
         cursor:pointer;
         box-shadow:0 15px 35px rgba(91,45,245,.35);
      }

      .error{
         background:#ffe5e5;
         color:red;
         padding:12px;
         border-radius:12px;
         margin-bottom:15px;
         font-size:14px;
         font-weight:600;
      }

      .back{
         display:inline-block;
         margin-top:25px;
         color:#6b4cff;
         text-decoration:none;
         font-weight:600;
      }

      @media(max-width:850px){
         .access-box{
            grid-template-columns:1fr;
         }

         .left{
            padding:40px 25px;
         }

         .right{
            padding:40px 25px;
         }

         .left h1,
         .form-box h2{
            font-size:32px;
         }

         button{
            width:100%;
         }
      }
   </style>
</head>
<body>

<div class="access-box">

   <div class="left">
      <h1>Teacher Access</h1>
      <p>
         Teacher login is protected. Send a WhatsApp message to admin and enter the access code to continue.
      </p>
   </div>

   <div class="right">
      <div class="form-box">
         <h2>Enter Code</h2>
         <p>Click WhatsApp button and request your teacher access code.</p>

         <?php
         if($error != ""){
            echo '<div class="error">'.$error.'</div>';
         }
         ?>

         <!-- මෙතන ඔයාගේ WhatsApp number එක දාන්න -->
        <a 
          class="whatsapp-btn" 
            href="https://wa.me/94740876205?text=Hello%20Admin,%20I%20would%20like%20to%20register%20as%20a%20teacher.%20Please%20send%20me%20the%20teacher%20access%20code."
            target="_blank">
            SEND WHATSAPP MESSAGE
        </a>

         <form method="post">
            <input type="text" name="access_code" placeholder="Enter Access Code" required>
            <button type="submit" name="verify_code">OPEN LOGIN</button>
         </form>

         <a href="index.php" class="back">← Back to Home</a>
      </div>
   </div>

</div>

</body>
</html>