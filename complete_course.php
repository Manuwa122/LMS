<?php
include "db.php";

if (!isset($_SESSION['userId'])) {
   header("Location: index.php?error=notloggedin");
   exit();
}

if (!isset($_GET['id'])) {
   header("Location: courses.php");
   exit();
}

$userId = $_SESSION['userId'];
$courseId = (int) $_GET['id'];

/* Check if progress already exists */
$check = mysqli_prepare($conn, "SELECT id FROM user_progress WHERE user_id = ? AND course_id = ?");
mysqli_stmt_bind_param($check, "ii", $userId, $courseId);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

/* If not exists, insert completed progress */
if (mysqli_stmt_num_rows($check) == 0) {

   $insert = mysqli_prepare($conn, "INSERT INTO user_progress (user_id, course_id, completed, progress) VALUES (?, ?, 1, 100)");
   mysqli_stmt_bind_param($insert, "ii", $userId, $courseId);
   mysqli_stmt_execute($insert);

} else {

   $update = mysqli_prepare($conn, "UPDATE user_progress SET completed = 1, progress = 100 WHERE user_id = ? AND course_id = ?");
   mysqli_stmt_bind_param($update, "ii", $userId, $courseId);
   mysqli_stmt_execute($update);

}

header("Location: profile.php");
exit();
?>