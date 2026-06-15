<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendWelcomeEmail($toEmail, $username){
    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = 'bcmanuwa7@gmail.com';

        // මෙතන අලුත් Gmail App Password එක දාන්න
        $mail->Password   = 'mpgcdozgbbvyoisd';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('bcmanuwa7@gmail.com', 'E Academy');
        $mail->addAddress($toEmail, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to E Academy';

        $safeUsername = htmlspecialchars($username);

        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Welcome to E Academy</title>
        </head>
        <body style="margin:0; padding:0; background:#f4f4ff; font-family:Arial, sans-serif;">

            <div style="width:100%; padding:40px 0; background:#f4f4ff;">
                <div style="
                    max-width:620px;
                    margin:auto;
                    background:#ffffff;
                    border-radius:24px;
                    overflow:hidden;
                    box-shadow:0 18px 45px rgba(0,0,0,0.13);
                    border:1px solid #eee;
                ">

                    <div style="
                        background:linear-gradient(135deg,#3b147d,#7438ff);
                        padding:42px 25px;
                        text-align:center;
                        color:#ffffff;
                    ">
                        <div style="
                            width:76px;
                            height:76px;
                            line-height:76px;
                            border-radius:50%;
                            background:rgba(255,255,255,0.18);
                            margin:0 auto 18px;
                            font-size:38px;
                        ">
                            &#127881;
                        </div>

                        <h1 style="
                            margin:0;
                            font-size:32px;
                            letter-spacing:1px;
                            font-weight:800;
                        ">
                            Welcome to E Academy
                        </h1>

                        <p style="
                            margin:10px 0 0;
                            font-size:16px;
                            opacity:.9;
                        ">
                            Your account has been successfully created
                        </p>
                    </div>

                    <div style="padding:40px 34px; text-align:center;">
                        <h2 style="
                            color:#240046;
                            margin:0 0 16px;
                            font-size:27px;
                        ">
                            Hello '.$safeUsername.'!
                        </h2>

                        <p style="
                            color:#555;
                            font-size:16px;
                            line-height:1.8;
                            margin:0;
                        ">
                            Congratulations! Your <b>E Academy</b> account is now active.<br>
                            You can now explore courses, learn new skills, and continue your learning journey with us.
                        </p>

                        <div style="
                            margin:32px auto;
                            background:#f0e8ff;
                            color:#4b0082;
                            font-size:18px;
                            font-weight:700;
                            padding:18px 28px;
                            border-radius:16px;
                            display:inline-block;
                            border:1px solid #d8c8ff;
                        ">
                            Start learning. Grow your future.
                        </div>

                        <p style="
                            color:#777;
                            font-size:14px;
                            line-height:1.7;
                            margin:0;
                        ">
                            Thank you for joining E Academy.<br>
                            We are excited to have you with us.
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

        $mail->AltBody = "Hello $username,\n\nWelcome to E Academy! Your account has been successfully created.";

        $mail->send();
        return true;

    }catch(Exception $e){
        return false;
    }
}
?>