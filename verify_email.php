<?php
session_start();

$message = "";

try {
    $db = new PDO(
        'mysql:host=localhost;dbname=loginsystemtut;charset=utf8',
        'root',
        '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (Exception $err) {
    die('Connection Failed :' . $err->getMessage());
}

$email = "";

if (isset($_GET['email'])) {
    $email = $_GET['email'];
} elseif (isset($_SESSION['verify_email'])) {
    $email = $_SESSION['verify_email'];
}

if (isset($_POST['verify'])) {
    $email = $_POST['email'];
    $otp = trim($_POST['otp']);

    $stmt = $db->prepare('SELECT * FROM users WHERE emailUsers = :e AND otp_code = :otp LIMIT 1');
    $stmt->execute(array(
        'e' => $email,
        'otp' => $otp
    ));

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (strtotime($user['otp_expires']) < time()) {
            $message = "OTP expired! Please sign up again.";
        } else {
            $update = $db->prepare('
                UPDATE users 
                SET email_verified = 1, otp_code = NULL, otp_expires = NULL 
                WHERE emailUsers = :e
            ');

            $update->execute(array(
                'e' => $email
            ));

            // Welcome mail send කරන තැන
            require "includes/send_welcome_mail.php";
            sendWelcomeEmail($email, $user['uidUsers']);

            unset($_SESSION['verify_email']);

            header("Location: index.php?signup=success");
            exit();
        }
    } else {
        $message = "Invalid OTP code!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        *{
            box-sizing: border-box;
        }

        body{
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            background:
                linear-gradient(rgba(17,24,39,.78), rgba(17,24,39,.78)),
                url('images/hero_1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-container{
            width: 950px;
            min-height: 520px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 20px 50px rgba(0,0,0,.35);
        }

        .verify-left{
            width: 50%;
            background: linear-gradient(135deg, #251064, #5126bd);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 50px;
        }

        .verify-left h1{
            font-size: 46px;
            margin: 0 0 20px;
            letter-spacing: 1px;
        }

        .verify-left p{
            font-size: 18px;
            line-height: 1.6;
            color: rgba(255,255,255,.75);
            margin-bottom: 35px;
        }

        .verify-left a{
            text-decoration: none;
            color: #fff;
            border: 1px solid #fff;
            border-radius: 30px;
            padding: 14px 55px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: .3s;
        }

        .verify-left a:hover{
            background: #fff;
            color: #4320a0;
        }

        .verify-right{
            width: 50%;
            padding: 60px 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .verify-right h2{
            font-size: 42px;
            color: #777;
            margin-bottom: 15px;
            letter-spacing: 2px;
        }

        .verify-text{
            color: #777;
            font-size: 16px;
            margin-bottom: 20px;
            text-align: center;
            line-height: 1.6;
        }

        .email-box{
            width: 100%;
            background: #eef3fb;
            padding: 16px;
            border-radius: 4px;
            color: #333;
            font-size: 15px;
            text-align: center;
            margin-bottom: 20px;
            word-break: break-all;
        }

        .error-msg{
            width: 100%;
            color: #e63946;
            background: #ffe9ec;
            padding: 12px;
            border-radius: 5px;
            text-align: center;
            font-size: 15px;
            margin-bottom: 15px;
        }

        form{
            width: 100%;
        }

        .otp-input{
            width: 100%;
            height: 62px;
            border: none;
            outline: none;
            background: #eef3fb;
            color: #222;
            font-size: 25px;
            text-align: center;
            letter-spacing: 12px;
            border-radius: 4px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .otp-input::placeholder{
            font-size: 18px;
            letter-spacing: 2px;
            font-weight: 400;
            color: #777;
        }

        .verify-btn{
            width: 60%;
            display: block;
            margin: 0 auto;
            border: none;
            outline: none;
            background: #7868e6;
            color: #fff;
            padding: 16px 20px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .3s ease;
        }

        .verify-btn:hover{
            background: #5b4bd6;
            transform: translateY(-2px);
        }

        .small-text{
            margin-top: 25px;
            color: #777;
            font-size: 15px;
            text-align: center;
        }

        @media(max-width: 850px){
            .verify-container{
                width: 90%;
                flex-direction: column;
            }

            .verify-left,
            .verify-right{
                width: 100%;
            }

            .verify-left{
                padding: 40px 25px;
            }

            .verify-right{
                padding: 40px 25px;
            }

            .verify-left h1{
                font-size: 34px;
            }

            .verify-right h2{
                font-size: 34px;
            }

            .verify-btn{
                width: 80%;
            }
        }
    </style>
</head>
<body>

<div class="verify-container">

    <div class="verify-left">
        <h1>Check Your Email!</h1>
        <p>
            We sent a 6 digit OTP code to your email.  
            Enter that code to activate your account.
        </p>
        <a href="index.php">BACK</a>
    </div>

    <div class="verify-right">
        <h2>Verify Email</h2>

        <p class="verify-text">Enter the OTP code sent to your email</p>

        <?php if($email != ""): ?>
            <div class="email-box">
                <?php echo htmlspecialchars($email); ?>
            </div>
        <?php endif; ?>

        <?php
        if ($message != "") {
            echo '<div class="error-msg">'.$message.'</div>';
        }
        ?>

        <form method="post">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <input 
                type="text" 
                name="otp" 
                class="otp-input"
                placeholder="Enter OTP"
                maxlength="6"
                required
                autocomplete="off"
                inputmode="numeric"
                pattern="[0-9]{6}"
            >

            <input type="submit" name="verify" value="VERIFY EMAIL" class="verify-btn">
        </form>

        <p class="small-text">
            After verification, you can login to your account.
        </p>
    </div>

</div>

</body>
</html>