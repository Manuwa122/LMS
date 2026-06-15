<?php
session_start();
include "db.php";

if(!isset($_SESSION['student_id'])){
   header("Location: student_login.php");
   exit();
}

if(!isset($_GET['class_id']) || !isset($_GET['teacher_id'])){
   header("Location: courses.php");
   exit();
}

$student_id = $_SESSION['student_id'];
$class_id = mysqli_real_escape_string($conn, $_GET['class_id']);
$teacher_id = mysqli_real_escape_string($conn, $_GET['teacher_id']);

$class_q = mysqli_query($conn, "SELECT * FROM teacher_classes WHERE id='$class_id' LIMIT 1");
$class = mysqli_fetch_assoc($class_q);

$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1");
$teacher = mysqli_fetch_assoc($teacher_q);

$message = "";

if(isset($_POST['upload'])){
   if(isset($_FILES['receipt']) && $_FILES['receipt']['error'] == 0){

      $folder = "uploads/receipts/";

      if(!is_dir($folder)){
         mkdir($folder, 0777, true);
      }

      $file_name = time() . "_" . basename($_FILES['receipt']['name']);
      $target_file = $folder . $file_name;

      $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

      if(in_array($file_type, $allowed)){
         if(move_uploaded_file($_FILES['receipt']['tmp_name'], $target_file)){

            $check = mysqli_query($conn, "
               SELECT * FROM class_payments 
               WHERE student_id='$student_id' 
               AND class_id='$class_id'
               LIMIT 1
            ");

            if(mysqli_num_rows($check) > 0){
               mysqli_query($conn, "
                  UPDATE class_payments 
                  SET receipt_image='$target_file', status='pending'
                  WHERE student_id='$student_id' 
                  AND class_id='$class_id'
               ");
            }else{
               mysqli_query($conn, "
                  INSERT INTO class_payments(student_id, teacher_id, class_id, receipt_image, status)
                  VALUES('$student_id', '$teacher_id', '$class_id', '$target_file', 'pending')
               ");
            }

            header("Location: teacher_profile.php?id=$teacher_id&uploaded=success");
            exit();

         }else{
            $message = "Receipt upload failed!";
         }
      }else{
         $message = "Only JPG, PNG, WEBP or PDF files allowed!";
      }

   }else{
      $message = "Please select your receipt!";
   }
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Upload Receipt</title>
   <link rel="stylesheet" href="style3.css">

   <style>
      body{
         background:linear-gradient(135deg,#f5f1ff,#ffffff);
      }

      .receipt-page{
         min-height:100vh;
         display:flex;
         align-items:center;
         justify-content:center;
         padding:2rem;
      }

      .receipt-card{
         width:100%;
         max-width:55rem;
         background:#fff;
         border-radius:2rem;
         padding:3rem;
         box-shadow:0 2rem 5rem rgba(111,56,255,.18);
         border:.1rem solid #eadfff;
      }

      .receipt-card h3{
         font-size:2.8rem;
         color:#210044;
         margin-bottom:1rem;
         text-align:center;
      }

      .receipt-card .class-info{
         background:#f6f1ff;
         padding:1.5rem;
         border-radius:1.2rem;
         margin:2rem 0;
      }

      .receipt-card .class-info p{
         font-size:1.5rem;
         color:#555;
         margin:.7rem 0;
      }

      .receipt-card label{
         display:block;
         font-size:1.6rem;
         color:#333;
         margin-bottom:.8rem;
      }

      .receipt-card input[type="file"]{
         width:100%;
         padding:1.3rem;
         background:#fafafa;
         border:.1rem solid #ddd;
         border-radius:.8rem;
         font-size:1.5rem;
         margin-bottom:1.5rem;
      }

      .upload-btn{
         width:100%;
         border:none;
         background:#6f38ff;
         color:#fff;
         padding:1.3rem;
         border-radius:.8rem;
         font-size:1.7rem;
         font-weight:700;
         cursor:pointer;
         margin-bottom:1rem;
      }

      .back-btn{
         display:block;
         text-align:center;
         background:#222;
         color:#fff;
         padding:1.2rem;
         border-radius:.8rem;
         font-size:1.6rem;
      }

      .msg{
         text-align:center;
         color:red;
         font-size:1.5rem;
         margin-bottom:1rem;
      }
   </style>
</head>
<body>

<section class="receipt-page">
   <form method="post" enctype="multipart/form-data" class="receipt-card">

      <h3>Upload Payment Receipt</h3>

      <?php if($message != ""){ ?>
         <p class="msg"><?php echo $message; ?></p>
      <?php } ?>

      <div class="class-info">
         <p><b>Teacher:</b> <?php echo htmlspecialchars($teacher['name']); ?></p>
         <p><b>Class:</b> <?php echo htmlspecialchars($class['class_title']); ?></p>
         <p><b>Month:</b> <?php echo htmlspecialchars($class['class_month']); ?></p>
         <p><b>Fee:</b> LKR <?php echo htmlspecialchars($class['fee']); ?></p>
      </div>

      <label>Choose receipt image or PDF</label>
      <input type="file" name="receipt" required>

      <button type="submit" name="upload" class="upload-btn">
         Upload Receipt
      </button>

      <a href="teacher_profile.php?id=<?php echo $teacher_id; ?>" class="back-btn">
         Back To Teacher Profile
      </a>

   </form>
</section>

</body>
</html>