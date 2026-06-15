<?php

if (
    !(empty($_POST['uid']) || trim($_POST['uid']) == '') &&
    !(empty($_POST['mail']) || trim($_POST['mail']) == '') &&
    !(empty($_POST['pwd']) || trim($_POST['pwd']) == '') &&
    !(empty($_POST['pwd-repeat']) || trim($_POST['pwd-repeat']) == '')
) {

    $uid = trim($_POST['uid']);
    $mail = trim($_POST['mail']);
    $pwd = $_POST['pwd'];
    $pwdRepeat = $_POST['pwd-repeat'];

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9 ]*$/", $uid)) {
        header("Location: ../index.php?errorp=invalidmailuid");
        exit();

    } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.php?errorp=invalidmail&uid=".$uid);
        exit();

    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $uid)) {
        header("Location: ../index.php?errorp=invaliduid&mail=".$mail);
        exit();

    } else if ($pwd !== $pwdRepeat) {
        header("Location: ../index.php?errorp=passwordcheck&mail=".$mail."&uid=".$uid);
        exit();
    }

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

    // Check username or email already exists
    $req = $db->prepare('SELECT * FROM users WHERE uidUsers = :u OR emailUsers = :e');
    $req->execute(array(
        'u' => $uid,
        'e' => $mail
    ));

    if ($req->fetch()) {
        header("Location: ../index.php?errorp=usertaken&mail=".$mail);
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    $otp = rand(100000, 999999);
    $otpExpires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $insert = $db->prepare('
        INSERT INTO users 
        (uidUsers, emailUsers, pwdUsers, email_verified, otp_code, otp_expires) 
        VALUES 
        (:u, :e, :p, 0, :otp, :expires)
    ');

    $insert->execute(array(
        'u' => $uid,
        'e' => $mail,
        'p' => $hashedPwd,
        'otp' => $otp,
        'expires' => $otpExpires
    ));

    $userId = $db->lastInsertId();

    // If your imgupload table exists, keep this
    try {
        $img = $db->prepare('INSERT INTO imgupload(userid, status) VALUES(:u, :s)');
        $img->execute(array(
            'u' => $userId,
            's' => 1
        ));
    } catch (Exception $e) {
        // Ignore if imgupload table not exists
    }

    require "send_otp_mail.php";

    if (sendOTPEmail($mail, $uid, $otp)) {
        session_start();
        $_SESSION['verify_email'] = $mail;

        header("Location: ../verify_email.php?email=".$mail);
        exit();
    } else {
        header("Location: ../index.php?errorp=emailnotsent");
        exit();
    }

} else {

    if ((empty($_POST['uid']) || trim($_POST['uid']) == '') && (empty($_POST['mail']) || trim($_POST['mail']) == '')) {
        header("Location: ../index.php?errorp=emptyfields");
    } elseif (!(empty($_POST['uid']) || trim($_POST['uid']) == '') && (empty($_POST['mail']) || trim($_POST['mail']) == '')) {
        header("Location: ../index.php?errorp=emptyfields&uid=".$_POST['uid']);
    } elseif ((empty($_POST['uid']) || trim($_POST['uid']) == '') && !(empty($_POST['mail']) || trim($_POST['mail']) == '')) {
        header("Location: ../index.php?errorp=emptyfields&mail=".$_POST['mail']);
    } else {
        header("Location: ../index.php?errorp=emptyfields&mail=".$_POST['mail']."&uid=".$_POST['uid']);
    }

    exit();
}