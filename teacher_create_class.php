<?php
session_start();
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = $_SESSION['teacher_id'];
$message = "";

if(isset($_POST['create_class'])){

   $class_title = mysqli_real_escape_string($conn, $_POST['class_title']);
   $class_year = mysqli_real_escape_string($conn, $_POST['class_year']);
   $class_month = mysqli_real_escape_string($conn, $_POST['class_month']);
   $class_date = mysqli_real_escape_string($conn, $_POST['class_date']);
   $class_fee = mysqli_real_escape_string($conn, $_POST['class_fee']);
   $payment_scheme = mysqli_real_escape_string($conn, $_POST['payment_scheme']);
   $description = mysqli_real_escape_string($conn, $_POST['description']);

   $thumbnail = "";

   if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0){

      $folder = "uploads/classes/";

      if(!is_dir($folder)){
         mkdir($folder, 0777, true);
      }

      $original_name = basename($_FILES['thumbnail']['name']);
      $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'webp'];

      if(in_array($ext, $allowed)){
         $file_name = time() . "_" . uniqid() . "." . $ext;
         $target_file = $folder . $file_name;

         if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)){
            $thumbnail = $target_file;
         }
      }
   }

   mysqli_query($conn, "
      INSERT INTO teacher_classes
      (teacher_id, class_title, class_year, class_month, class_date, class_fee, payment_scheme, thumbnail, description)
      VALUES
      ('$teacher_id', '$class_title', '$class_year', '$class_month', '$class_date', '$class_fee', '$payment_scheme', '$thumbnail', '$description')
   ");

   $message = "Class block created successfully!";
}

$my_classes = mysqli_query($conn, "
   SELECT * FROM teacher_classes 
   WHERE teacher_id='$teacher_id' 
   ORDER BY class_year DESC, class_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Create Class Block</title>

   <link rel="stylesheet" href="style3.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <style>
      body{
         background:#f5f2ff;
      }

      .create-class-page{
         padding:2rem;
      }

      .class-form-card,
      .class-list-card{
         background:#fff;
         border-radius:1.5rem;
         padding:2rem;
         box-shadow:0 1rem 3rem rgba(0,0,0,.08);
         margin-bottom:2rem;
         border:.1rem solid #eadfff;
      }

      .class-form-card h1,
      .class-list-card h1{
         font-size:2.7rem;
         color:#210044;
         margin-bottom:1.5rem;
         border-bottom:.1rem solid #ddd;
         padding-bottom:1rem;
      }

      .class-form-card h1 span,
      .class-list-card h1 span{
         border-bottom:.25rem solid #6f38ff;
         padding-bottom:1rem;
      }

      .form-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit, minmax(25rem, 1fr));
         gap:1.5rem;
      }

      .input-group p{
         font-size:1.5rem;
         color:#333;
         margin-bottom:.6rem;
      }

      .input-group input,
      .input-group select,
      .input-group textarea{
         width:100%;
         padding:1.2rem;
         border:.1rem solid #ddd;
         border-radius:.8rem;
         font-size:1.5rem;
         background:#fafafa;
      }

      .input-group textarea{
         min-height:12rem;
         resize:vertical;
      }

      .purple-btn{
         border:none;
         background:#6f38ff;
         color:#fff;
         padding:1.2rem 3rem;
         border-radius:.8rem;
         font-size:1.6rem;
         font-weight:700;
         cursor:pointer;
         margin-top:1.5rem;
      }

      .purple-btn:hover{
         background:#5524df;
      }

      .msg{
         background:#e9fff3;
         color:#10854d;
         padding:1.2rem;
         border-radius:.8rem;
         font-size:1.6rem;
         margin-bottom:1.5rem;
      }

      .created-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit, minmax(28rem, 1fr));
         gap:1.5rem;
      }

      .created-card{
         border:.1rem solid #e5ddff;
         border-radius:1.2rem;
         overflow:hidden;
         background:#fff;
      }

      .created-card img{
         width:100%;
         height:16rem;
         object-fit:cover;
         background:#eee7ff;
      }

      .created-card .info{
         padding:1.5rem;
      }

      .created-card h3{
         font-size:1.9rem;
         color:#210044;
         margin-bottom:.8rem;
      }

      .created-card p{
         font-size:1.4rem;
         color:#666;
         margin:.5rem 0;
      }
   </style>
</head>
<body>

<section class="create-class-page">

   <div class="class-form-card">
      <h1><span>Create Class Block</span></h1>

      <?php if($message != ""){ ?>
         <p class="msg"><?php echo $message; ?></p>
      <?php } ?>

      <form method="post" enctype="multipart/form-data">

         <div class="form-grid">

            <div class="input-group">
               <p>Class Title</p>
               <input type="text" name="class_title" placeholder="2026 A/L Full Paper Class" required>
            </div>

            <div class="input-group">
               <p>Class Year</p>
               <input type="number" name="class_year" placeholder="2026" required>
            </div>

            <div class="input-group">
               <p>Relevant Month</p>
               <select name="class_month" required>
                  <option value="">Select Month</option>
                  <option>January</option>
                  <option>February</option>
                  <option>March</option>
                  <option>April</option>
                  <option>May</option>
                  <option>June</option>
                  <option>July</option>
                  <option>August</option>
                  <option>September</option>
                  <option>October</option>
                  <option>November</option>
                  <option>December</option>
               </select>
            </div>

            <div class="input-group">
               <p>Class Date</p>
               <input type="date" name="class_date" required>
            </div>

            <div class="input-group">
               <p>Class Fee</p>
               <input type="number" name="class_fee" placeholder="4000" required>
            </div>

            <div class="input-group">
               <p>Payment Scheme</p>
               <select name="payment_scheme" required>
                  <option value="Monthly">Monthly</option>
                  <option value="Full Payment">Full Payment</option>
                  <option value="Free">Free</option>
               </select>
            </div>

            <div class="input-group">
               <p>Thumbnail Image</p>
               <input type="file" name="thumbnail" accept="image/*">
            </div>

         </div>

         <div class="input-group" style="margin-top:1.5rem;">
            <p>Description / Info</p>
            <textarea name="description" placeholder="Class details, WhatsApp link, Telegram link, bank details..."></textarea>
         </div>

         <button type="submit" name="create_class" class="purple-btn">
            Create Class
         </button>

      </form>
   </div>

   <div class="class-list-card">
      <h1><span>My Created Classes</span></h1>

      <div class="created-grid">

         <?php if(mysqli_num_rows($my_classes) > 0){ ?>
            <?php while($row = mysqli_fetch_assoc($my_classes)){ ?>

               <div class="created-card">
                  <?php if($row['thumbnail'] != ""){ ?>
                     <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="">
                  <?php }else{ ?>
                     <img src="images/thumb-1.png" alt="">
                  <?php } ?>

                  <div class="info">
                     <h3><?php echo htmlspecialchars($row['class_title']); ?></h3>
                     <p><b>Month:</b> <?php echo htmlspecialchars($row['class_month']); ?></p>
                     <p><b>Date:</b> <?php echo htmlspecialchars($row['class_date']); ?></p>
                     <p><b>Fee:</b> LKR <?php echo htmlspecialchars($row['class_fee']); ?></p>
                     <p><b>Payment:</b> <?php echo htmlspecialchars($row['payment_scheme']); ?></p>
                  </div>
               </div>

            <?php } ?>
         <?php }else{ ?>
            <p style="font-size:1.6rem;">No classes created yet.</p>
         <?php } ?>

      </div>
   </div>

</section>

</body>
</html>