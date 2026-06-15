<?php
session_start();

if(!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])){
   header("Location: mail_input.php");
   exit();
}

$message = "";

if(isset($_POST['reset'])){

   $newPwd = $_POST['new_password'];
   $repeatPwd = $_POST['repeat_password'];
   $email = $_SESSION['reset_email'];

   if(empty($newPwd) || empty($repeatPwd)){
      $message = "Please fill all fields!";
   }elseif($newPwd !== $repeatPwd){
      $message = "Passwords do not match!";
   }else{

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

      $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);

      $update = $db->prepare("
         UPDATE users 
         SET pwdUsers = :pwd, reset_otp = NULL, reset_otp_expires = NULL 
         WHERE emailUsers = :email
      ");

      $update->execute(array(
         'pwd' => $hashedPwd,
         'email' => $email
      ));

      unset($_SESSION['reset_email']);
      unset($_SESSION['otp_verified']);

      header("Location: index.php?password=changed");
      exit();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Reset Password</title>

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
         margin-bottom:25px;
      }

      input{
         width:100%;
         padding:15px;
         border:1px solid #ddd;
         border-radius:12px;
         margin-bottom:15px;
         font-size:16px;
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
   </style>
</head>
<body>

<div class="box">
   <h1>Reset Password</h1>

   <?php
   if($message != ""){
      echo '<p class="error">'.$message.'</p>';
   }
   ?>

   <form method="post">
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="repeat_password" placeholder="Repeat Password" required>
      <button type="submit" name="reset">RESET PASSWORD</button>
   </form>
</div>

</body>
</html>