<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendOTPEmail($toEmail, $username, $otp){
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // Gmail address
        $mail->Username   = 'bcmanuwa7@gmail.com';

        // Gmail App Password - exposed එක වෙනුවට අලුත් App Password එකක් දාන්න
        $mail->Password   = 'mpgcdozgbbvyoisd';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('bcmanuwa7@gmail.com', 'E Academy');
        $mail->addAddress($toEmail, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your E Academy Email';

        $safeUsername = htmlspecialchars($username);

        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Email Verification</title>
        </head>
        <body style="margin:0; padding:0; background:#f4f4ff; font-family:Arial, sans-serif;">

            <div style="width:100%; padding:40px 0; background:#f4f4ff;">
                <div style="
                    max-width:600px;
                    margin:auto;
                    background:#ffffff;
                    border-radius:22px;
                    overflow:hidden;
                    box-shadow:0 18px 45px rgba(0,0,0,0.13);
                    border:1px solid #eee;
                ">

                    <div style="
                        background:linear-gradient(135deg,#3b147d,#7438ff);
                        padding:38px 25px;
                        text-align:center;
                        color:#ffffff;
                    ">
                        <div style="
                            width:72px;
                            height:72px;
                            line-height:72px;
                            border-radius:50%;
                            background:rgba(255,255,255,0.18);
                            margin:0 auto 18px;
                            font-size:36px;
                        ">
                            &#9993;
                        </div>

                        <h1 style="
                            margin:0;
                            font-size:31px;
                            letter-spacing:1px;
                            font-weight:800;
                        ">
                            E Academy
                        </h1>

                        <p style="
                            margin:10px 0 0;
                            font-size:16px;
                            opacity:.9;
                        ">
                            Account Verification
                        </p>
                    </div>

                    <div style="padding:38px 32px; text-align:center;">
                        <h2 style="
                            color:#240046;
                            margin:0 0 15px;
                            font-size:27px;
                        ">
                            Verify Your Email Address
                        </h2>

                        <p style="
                            color:#555;
                            font-size:16px;
                            line-height:1.7;
                            margin:0;
                        ">
                            Hello <b>'.$safeUsername.'</b>,<br>
                            Welcome to <b>E Academy</b>!<br>
                            Please use the verification code below to activate your account.
                        </p>

                        <div style="
                            margin:32px auto;
                            background:#f0e8ff;
                            color:#4b0082;
                            font-size:36px;
                            font-weight:800;
                            letter-spacing:9px;
                            padding:20px 28px;
                            border-radius:16px;
                            display:inline-block;
                            border:2px dashed #7b2cff;
                            box-shadow:0 10px 30px rgba(123,76,255,0.18);
                        ">
                            '.$otp.'
                        </div>

                        <p style="
                            color:#777;
                            font-size:14px;
                            line-height:1.7;
                            margin:0;
                        ">
                            This verification code is valid for <b>10 minutes</b> only.<br>
                            If you did not create an account, please ignore this email.
                        </p>
                    </div>

                    <div style="
                        background:#fafafa;
                        padding:18px;
                        text-align:center;
                        color:#999;
                        font-size:13px;
                        border-top:1px solid #eee;
                    ">
                        © '.date("Y").' E Academy. All rights reserved.
                    </div>

                </div>
            </div>

        </body>
        </html>
        ';

        $mail->AltBody = "Hello $username,\n\nYour E Academy verification code is: $otp\n\nThis code will expire in 10 minutes.";

        $mail->send();
        return true;

    }catch(Exception $e){
        return false;
    }
}
?>