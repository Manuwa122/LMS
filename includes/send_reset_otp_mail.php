<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";

function sendResetOTPEmail($email, $name, $otp){

   $mail = new PHPMailer(true);

   try{

      $mail->isSMTP();
      $mail->Host = "smtp.gmail.com";
      $mail->SMTPAuth = true;

      // ඔයාගේ Gmail එක දාන්න
      $mail->Username = "bcmanuwa7@gmail.com";

      // Gmail password නෙවෙයි, App Password එක දාන්න
      $mail->Password = "hujokyooczwinguo";

      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom("yourgmail@gmail.com", "E-Academy");
      $mail->addAddress($email, $name);

      $mail->isHTML(true);
      $mail->Subject = "Reset Your E-Academy Password";

      $mail->Body = '
      <!DOCTYPE html>
      <html>
      <head>
         <meta charset="UTF-8">
      </head>
      <body style="margin:0; padding:0; background:#f4f4ff; font-family:Arial, sans-serif;">

         <div style="width:100%; padding:40px 0; background:#f4f4ff;">
            <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 15px 40px rgba(0,0,0,0.12);">

               <div style="background:linear-gradient(135deg,#4b0082,#7b2cff); padding:35px 25px; text-align:center; color:#ffffff;">
                  <h1 style="margin:0; font-size:30px;">E-Academy</h1>
                  <p style="margin:10px 0 0; font-size:16px;">Password Reset Verification</p>
               </div>

               <div style="padding:35px 30px; text-align:center;">
                  <h2 style="color:#240046; margin-top:0;">Reset Your Password</h2>

                  <p style="color:#555; font-size:16px; line-height:1.7;">
                     Hello <b>'.htmlspecialchars($name).'</b>,<br>
                     Use the verification code below to reset your password.
                  </p>

                  <div style="margin:30px auto; background:#f0e8ff; color:#4b0082; font-size:34px; font-weight:bold; letter-spacing:8px; padding:18px 25px; border-radius:15px; display:inline-block; border:2px dashed #7b2cff;">
                     '.$otp.'
                  </div>

                  <p style="color:#777; font-size:14px; line-height:1.6;">
                     This code is valid for 10 minutes only.<br>
                     If you did not request this, please ignore this email.
                  </p>
               </div>

               <div style="background:#fafafa; padding:18px; text-align:center; color:#999; font-size:13px;">
                  © '.date("Y").' E-Academy. All rights reserved.
               </div>

            </div>
         </div>

      </body>
      </html>
      ';

      $mail->send();
      return true;

   }catch(Exception $e){
      return false;
   }
}

?>