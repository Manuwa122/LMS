<?php
session_start();

if(!isset($_SESSION['reset_email'])){
   header("Location: mail_input.php");
   exit();
}

$message = "";

if(isset($_POST['verify'])){

   $otp = trim($_POST['otp']);
   $email = $_SESSION['reset_email'];

   try{
      $db = new PDO(
         'mysql:host=localhost;dbname=loginsystemtut;charset=utf8',
         'root',
         '',
         array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
   }catch(Exception $err){
      die("Connection Failed : ".$err->getMessage());
   }

   $check = $db->prepare("
      SELECT * FROM users 
      WHERE emailUsers = :email
      LIMIT 1
   ");

   $check->execute(array(
      'email' => $email
   ));

   $user = $check->fetch(PDO::FETCH_ASSOC);

   if(!$user){
      $message = "Email session not found. Please request code again.";
   }else{

      $dbOtp = trim($user['reset_otp']);
      $dbExpire = $user['reset_otp_expires'];

      if($dbOtp === ""){
         $message = "No verification code found. Please request a new code.";

      }elseif($dbOtp !== $otp){
         $message = "Invalid verification code!";

      }elseif(strtotime($dbExpire) < time()){
         $message = "Verification code expired! Please request a new code.";

      }else{
         $_SESSION['otp_verified'] = true;

         header("Location: reset_password.php");
         exit();
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Verify Code</title>

   <style>
      body{
         margin:0;
         min-height:100vh;
         display:flex;
         align-items:center;
         justify-content:center;
         background:#f4f4ff;
         font-family:Arial, sans-serif;
      }

      .box{
         width:430px;
         max-width:90%;
         background:#fff;
         padding:40px;
         border-radius:25px;
         text-align:center;
         box-shadow:0 20px 60px rgba(0,0,0,.12);
      }

      h1{
         color:#240046;
         margin-bottom:10px;
         letter-spacing:2px;
      }

      p{
         color:#666;
      }

      input{
         width:100%;
         padding:15px;
         border:1px solid #ddd;
         border-radius:12px;
         margin:20px 0;
         font-size:18px;
         text-align:center;
         letter-spacing:5px;
         box-sizing:border-box;
      }

      button{
         width:100%;
         padding:15px;
         border:none;
         border-radius:30px;
         background:linear-gradient(135deg,#4b0082,#7b2cff);
         color:#fff;
         font-weight:800;
         cursor:pointer;
      }

      .error{
         color:red;
         font-size:15px;
      }

      .email{
         font-size:14px;
         color:#777;
         margin-top:5px;
      }
   </style>
</head>
<body>

<div class="box">
   <h1>Verify Code</h1>
   <p>Enter the 6-digit code sent to your email.</p>
   <p class="email"><?php echo htmlspecialchars($_SESSION['reset_email']); ?></p>

   <?php
   if($message != ""){
      echo '<p class="error">'.$message.'</p>';
   }
   ?>

   <form method="post">
      <input type="text" name="otp" placeholder="000000" maxlength="6" required>
      <button type="submit" name="verify">VERIFY</button>
   </form>
</div>

</body>
</html>