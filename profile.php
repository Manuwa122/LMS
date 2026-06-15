<?php
session_start();
include "db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: index.php?error=notloggedin");
    exit();
}

$userId = $_SESSION['userId'];
$username = $_SESSION['userUid'];
$email = $_SESSION['userEmail'];

$firstLetter = strtoupper(substr($username, 0, 1));

$totalCourses = 0;
$completedCourses = 0;
$progressPercent = 0;

try {
    $db = new PDO(
        'mysql:host=localhost;dbname=loginsystemtut;charset=utf8',
        'root',
        '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    // Total courses joined by this user
    $stmt = $db->prepare("SELECT COUNT(*) FROM user_progress WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $totalCourses = $stmt->fetchColumn();

    // Completed courses
    $stmt = $db->prepare("SELECT COUNT(*) FROM user_progress WHERE user_id = :user_id AND completed = 1");
    $stmt->execute(['user_id' => $userId]);
    $completedCourses = $stmt->fetchColumn();

    // Average progress
    $stmt = $db->prepare("SELECT AVG(progress) FROM user_progress WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $avgProgress = $stmt->fetchColumn();

    if ($avgProgress !== null) {
        $progressPercent = round($avgProgress);
    }

} catch (Exception $e) {
    $totalCourses = 0;
    $completedCourses = 0;
    $progressPercent = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title><?php echo htmlspecialchars($username); ?> | Profile</title>

   <link rel="stylesheet" href="profile.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="background-animation">
   <span></span>
   <span></span>
   <span></span>
   <span></span>
</div>

<header class="top-header">
   <a href="loggedin.php" class="logo">
      <i class="fas fa-graduation-cap"></i>
      Educa.
   </a>

   <nav>
      <a href="loggedin.php">Dashboard</a>
      <a href="courses.php">Courses</a>
      <a href="includes/logout.inc.php" class="logout-btn">Logout</a>
   </nav>
</header>

<section class="profile-section">

   <div class="profile-card">

      <div class="profile-cover"></div>

      <div class="avatar-box">
         <div class="avatar">
            <?php echo htmlspecialchars($firstLetter); ?>
         </div>
      </div>

      <div class="profile-info">
         <h1><?php echo htmlspecialchars($username); ?></h1>
         <p class="email">
            <i class="fas fa-envelope"></i>
            <?php echo htmlspecialchars($email); ?>
         </p>

         <span class="badge">
            <i class="fas fa-user-check"></i>
            Verified Student
         </span>
      </div>

      <div class="profile-stats">
         <div>
            <h3>12</h3>
            <p>Courses</p>
         </div>
         <div>
            <h3>08</h3>
            <p>Completed</p>
         </div>
         <div>
            <h3>95%</h3>
            <p>Progress</p>
         </div>
      </div>

      <div class="profile-details">
         <h2>Account Details</h2>

         <div class="detail-box">
            <i class="fas fa-id-card"></i>
            <div>
               <span>User ID</span>
               <p><?php echo htmlspecialchars($userId); ?></p>
            </div>
         </div>

         <div class="detail-box">
            <i class="fas fa-user"></i>
            <div>
               <span>Username</span>
               <p><?php echo htmlspecialchars($username); ?></p>
            </div>
         </div>

         <div class="detail-box">
            <i class="fas fa-envelope-open"></i>
            <div>
               <span>Email Address</span>
               <p><?php echo htmlspecialchars($email); ?></p>
            </div>
         </div>
      </div>

      <div class="action-buttons">
         <a href="loggedin.php" class="btn primary">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
         </a>

         <a href="includes/logout.inc.php" class="btn danger">
            <i class="fas fa-right-from-bracket"></i>
            Logout
         </a>
      </div>

   </div>

</section>

<script src="profile.js"></script>

</body>
</html>