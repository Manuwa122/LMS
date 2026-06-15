<?php
session_start();

if(isset($_POST['send_otp'])){

   if(empty(trim($_POST['email']))){
      header("Location: ../mail_input.php?error=empty");
      exit();
   }

   $email = trim($_POST['email']);

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

   $check = $db->prepare("SELECT * FROM users WHERE emailUsers = :email");
   $check->execute(array(
      'email' => $email
   ));

   $user = $check->fetch(PDO::FETCH_ASSOC);

   if(!$user){
      header("Location: ../mail_input.php?error=notfound");
      exit();
   }

   $otp = rand(100000, 999999);
   $otpExpires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

   $update = $db->prepare("
      UPDATE users 
      SET reset_otp = :otp, reset_otp_expires = :expires 
      WHERE emailUsers = :email
   ");

   $update->execute(array(
      'otp' => $otp,
      'expires' => $otpExpires,
      'email' => $email
   ));

   require "send_reset_otp_mail.php";

   if(sendResetOTPEmail($email, $user['uidUsers'], $otp)){
      $_SESSION['reset_email'] = $email;
      header("Location: ../verify_reset_otp.php");
      exit();
   }else{
      header("Location: ../mail_input.php?error=emailnotsent");
      exit();
   }

}else{
   header("Location: ../mail_input.php");
   exit();
}
?>