<?php
session_start();
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = (int) $_SESSION['teacher_id'];

function e($value){
   return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function getReceiptPath($row){
   $keys = ['payment_receipt', 'receipt', 'receipt_path', 'receipt_image'];
   foreach($keys as $key){
      if(isset($row[$key]) && trim($row[$key]) !== ""){
         return trim($row[$key]);
      }
   }
   return "";
}

if(isset($_GET['approve'])){
   $id = (int) $_GET['approve'];

   mysqli_query($conn, "
      UPDATE access_requests 
      SET status='approved',
          approved_at=NOW(),
          expires_at=DATE_ADD(NOW(), INTERVAL 30 DAY)
      WHERE id='$id' AND teacher_id='$teacher_id'
   ");

   header("Location: teacher_payment_requests.php?msg=approved");
   exit();
}

if(isset($_GET['reject'])){
   $id = (int) $_GET['reject'];

   mysqli_query($conn, "
      UPDATE access_requests 
      SET status='rejected',
          approved_at=NULL,
          expires_at=NULL
      WHERE id='$id' AND teacher_id='$teacher_id'
   ");

   header("Location: teacher_payment_requests.php?msg=rejected");
   exit();
}

$requests = mysqli_query($conn, "
   SELECT 
      ar.*, 
      u.uidUsers, 
      u.emailUsers,
      tc.title AS class_title,
      tc.class_year,
      tc.month_name AS relevant_month
   FROM access_requests ar
   LEFT JOIN users u ON ar.student_id = u.idUsers
   LEFT JOIN teacher_classes tc ON ar.class_id = tc.id
   WHERE ar.teacher_id='$teacher_id'
   ORDER BY ar.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment Requests</title>

   <link rel="stylesheet" href="style3.css">
     <link rel="icon" type="image/png" href="images/favicon.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <style>
      *{
         box-sizing:border-box;
      }

      html,
      body{
         margin:0 !important;
         padding:0 !important;
         width:100%;
         min-height:100vh;
         overflow-x:hidden;
         background:
            radial-gradient(circle at top left, rgba(124,58,237,.18), transparent 32%),
            linear-gradient(135deg,#f6f1ff,#ffffff);
      }

      body{
         font-family:'Poppins', Arial, sans-serif;
      }

      .payment-page{
         width:100%;
         max-width:1250px;
         margin:0 auto;
         padding:3rem 1.5rem;
      }

      .payment-shell{
         background:rgba(255,255,255,.92);
         border:1px solid rgba(124,58,237,.12);
         border-radius:2rem;
         box-shadow:0 1.5rem 4rem rgba(49,10,92,.10);
         padding:2.4rem;
      }

      .payment-header{
         display:flex;
         align-items:center;
         justify-content:space-between;
         gap:1.5rem;
         padding-bottom:1.8rem;
         border-bottom:1px solid #e9ddff;
         margin-bottom:2rem;
      }

      .payment-header h1{
         margin:0;
         color:#21083f;
         font-size:2.5rem;
         letter-spacing:.04rem;
         display:flex;
         align-items:center;
         gap:1rem;
      }

      .payment-header h1 i{
         color:#6d28d9;
      }

      .back-dashboard-btn{
         display:inline-flex;
         align-items:center;
         justify-content:center;
         gap:.75rem;
         background:linear-gradient(135deg,#6d28d9,#9b5cff);
         color:#fff !important;
         padding:1.05rem 2rem;
         border-radius:1rem;
         font-size:1.45rem;
         font-weight:800;
         text-decoration:none;
         box-shadow:0 .9rem 2.2rem rgba(109,40,217,.22);
         transition:.22s ease;
         white-space:nowrap;
      }

      .back-dashboard-btn:hover{
         transform:translateY(-2px);
      }

      .top-message{
         margin:0 0 1.5rem;
         padding:1.2rem 1.5rem;
         border-radius:1rem;
         font-size:1.45rem;
         font-weight:700;
      }

      .top-message.success{
         background:#dcfce7;
         color:#166534;
      }

      .top-message.reject{
         background:#fee2e2;
         color:#991b1b;
      }

      .requests-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit, minmax(330px, 1fr));
         gap:1.5rem;
      }

      .request-card{
         position:relative;
         overflow:hidden;
         background:#fff;
         border:1px solid #eadfff;
         border-radius:1.6rem;
         padding:1.8rem;
         box-shadow:0 .8rem 2.5rem rgba(31,8,68,.07);
         display:flex;
         flex-direction:column;
         gap:1.3rem;
      }

      .request-card::before{
         content:"";
         position:absolute;
         width:11rem;
         height:11rem;
         right:-4rem;
         top:-4rem;
         border-radius:50%;
         background:rgba(124,58,237,.08);
      }

      .request-top{
         position:relative;
         z-index:1;
         display:flex;
         align-items:flex-start;
         justify-content:space-between;
         gap:1rem;
      }

      .class-title{
         font-size:2rem;
         line-height:1.25;
         color:#21083f;
         margin:0;
      }

      .student-line{
         display:flex;
         align-items:center;
         gap:1rem;
         margin-top:1rem;
      }

      .student-icon{
         width:4.6rem;
         height:4.6rem;
         border-radius:1rem;
         background:linear-gradient(135deg,#6d28d9,#9b5cff);
         color:#fff;
         display:flex;
         align-items:center;
         justify-content:center;
         font-size:1.8rem;
         flex:0 0 auto;
      }

      .student-line h3{
         font-size:1.65rem;
         color:#21083f;
         margin:0 0 .2rem;
      }

      .student-line p{
         font-size:1.32rem;
         color:#6b6475;
         margin:0;
         word-break:break-word;
      }

      .info-box{
         background:#faf8ff;
         border:1px solid #eadfff;
         border-radius:1.2rem;
         padding:1.2rem;
      }

      .info-row{
         display:grid;
         grid-template-columns:9.5rem 1fr;
         gap:.8rem;
         color:#5d566b;
         font-size:1.42rem;
         line-height:1.8;
      }

      .info-row b{
         color:#34214d;
      }

      .status{
         display:inline-flex;
         align-items:center;
         justify-content:center;
         width:max-content;
         padding:.75rem 1.25rem;
         border-radius:5rem;
         font-size:1.35rem;
         font-weight:800;
      }

      .status.pending{
         background:#fff4d6;
         color:#9a6900;
      }

      .status.approved{
         background:#dcffe9;
         color:#0b7a3b;
      }

      .status.rejected{
         background:#ffe1e1;
         color:#b52424;
      }

      .receipt-preview{
         background:#f8f4ff;
         border:1px solid #eadfff;
         border-radius:1.2rem;
         padding:1.2rem;
      }

      .receipt-preview p{
         margin:0 0 .9rem;
         font-size:1.4rem;
         color:#34214d;
         font-weight:800;
      }

      .receipt-img{
         width:100%;
         height:15rem;
         object-fit:cover;
         border-radius:1rem;
         border:1px solid #e5d8ff;
         display:block;
      }

      .no-receipt{
         background:#fff1f2;
         color:#e11d48;
         padding:1.1rem;
         border-radius:1rem;
         font-size:1.35rem;
         font-weight:800;
         text-align:center;
      }

      .btn-area{
         display:grid;
         grid-template-columns:1fr 1fr;
         gap:1rem;
         margin-top:auto;
      }

      .btn-area a{
         display:flex;
         align-items:center;
         justify-content:center;
         gap:.7rem;
         padding:1rem 1.2rem;
         border-radius:1rem;
         color:#fff !important;
         font-size:1.42rem;
         font-weight:800;
         text-align:center;
         text-decoration:none;
         transition:.2s ease;
      }

      .btn-area a:hover{
         transform:translateY(-2px);
      }

      .view-btn{
         background:linear-gradient(135deg,#6d28d9,#8b5cf6);
      }

      .approve-btn{
         background:linear-gradient(135deg,#16a34a,#22c55e);
      }

      .reject-btn{
         background:linear-gradient(135deg,#dc2626,#ef4444);
      }

      .full-btn{
         grid-column:1 / -1;
      }

      .empty{
         background:#fff;
         border:1px solid #eadfff;
         border-radius:1.5rem;
         padding:2rem;
         font-size:1.7rem;
         color:#777;
         text-align:center;
      }

      @media(max-width:768px){
         .payment-shell{
            padding:1.5rem;
         }

         .payment-header{
            flex-direction:column;
            align-items:flex-start;
         }

         .back-dashboard-btn{
            width:100%;
         }

         .requests-grid{
            grid-template-columns:1fr;
         }

         .btn-area{
            grid-template-columns:1fr;
         }

         .full-btn{
            grid-column:auto;
         }

         .info-row{
            grid-template-columns:1fr;
            gap:.1rem;
         }
      }
   </style>
</head>
<body>

<section class="payment-page">
   <div class="payment-shell">

      <div class="payment-header">
         <h1>
            <i class="fas fa-file-invoice-dollar"></i>
            Payment Requests
         </h1>

         <a href="teacher_dashboard.php" class="back-dashboard-btn">
            <i class="fas fa-arrow-left"></i>
            Back To Dashboard
         </a>
      </div>

      <?php if(isset($_GET['msg']) && $_GET['msg'] == "approved"){ ?>
         <div class="top-message success">
            <i class="fas fa-check-circle"></i> Payment request approved successfully.
         </div>
      <?php } ?>

      <?php if(isset($_GET['msg']) && $_GET['msg'] == "rejected"){ ?>
         <div class="top-message reject">
            <i class="fas fa-times-circle"></i> Payment request rejected successfully.
         </div>
      <?php } ?>

      <?php if($requests && mysqli_num_rows($requests) > 0){ ?>

         <div class="requests-grid">

            <?php while($row = mysqli_fetch_assoc($requests)){ ?>

               <?php
                  $receipt = getReceiptPath($row);
                  $status = isset($row['status']) && $row['status'] != "" ? strtolower($row['status']) : "pending";
                  $student_name = $row['uidUsers'] ?? "Unknown";
                  $student_email = $row['emailUsers'] ?? "No email";
                  $requested_time = $row['requested_at'] ?? ($row['created_at'] ?? "-");
               ?>

               <div class="request-card">

                  <div class="request-top">
                     <div>
                        <h2 class="class-title">
                           <?php echo e($row['class_title'] ?? 'Class not found'); ?>
                        </h2>

                        <div class="student-line">
                           <div class="student-icon">
                              <i class="fas fa-user-graduate"></i>
                           </div>
                           <div>
                              <h3><?php echo e($student_name); ?></h3>
                              <p><?php echo e($student_email); ?></p>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="info-box">
                     <div class="info-row">
                        <b>Year:</b>
                        <span><?php echo e($row['class_year'] ?? '-'); ?></span>
                     </div>

                     <div class="info-row">
                        <b>Month:</b>
                        <span><?php echo e($row['relevant_month'] ?? '-'); ?></span>
                     </div>

                     <div class="info-row">
                        <b>Requested:</b>
                        <span><?php echo e($requested_time); ?></span>
                     </div>

                     <div class="info-row">
                        <b>Approved:</b>
                        <span><?php echo !empty($row['approved_at']) ? e($row['approved_at']) : 'Not approved yet'; ?></span>
                     </div>

                     <div class="info-row">
                        <b>Expires:</b>
                        <span><?php echo !empty($row['expires_at']) ? e($row['expires_at']) : 'Not set'; ?></span>
                     </div>
                  </div>

                  <span class="status <?php echo e($status); ?>">
                     <?php echo ucfirst(e($status)); ?>
                  </span>

                  <?php if($receipt != ""){ ?>
                     <div class="receipt-preview">
                        <p><i class="fas fa-receipt"></i> Payment Receipt</p>

                        <?php
                           $ext = strtolower(pathinfo($receipt, PATHINFO_EXTENSION));
                        ?>

                        <?php if(in_array($ext, ['jpg','jpeg','png','webp','gif'])){ ?>
                           <a href="<?php echo e($receipt); ?>" target="_blank">
                              <img src="<?php echo e($receipt); ?>" class="receipt-img" alt="Payment Receipt">
                           </a>
                        <?php }else{ ?>
                           <a href="<?php echo e($receipt); ?>" target="_blank" class="view-btn" style="display:flex;justify-content:center;align-items:center;gap:.7rem;padding:1rem;border-radius:1rem;color:#fff;text-decoration:none;font-size:1.4rem;font-weight:800;">
                              <i class="fas fa-file-pdf"></i> Open Receipt File
                           </a>
                        <?php } ?>
                     </div>
                  <?php }else{ ?>
                     <div class="no-receipt">
                        No payment receipt uploaded.
                     </div>
                  <?php } ?>

                  <div class="btn-area">
                     <?php if($receipt != ""){ ?>
                        <a href="<?php echo e($receipt); ?>" target="_blank" class="view-btn full-btn">
                           <i class="fas fa-receipt"></i> View Receipt
                        </a>
                     <?php } ?>

                     <?php if($status != 'approved'){ ?>
                        <a href="teacher_payment_requests.php?approve=<?php echo (int)$row['id']; ?>" class="approve-btn"
                           onclick="return confirm('Approve this payment request?');">
                           <i class="fas fa-check"></i> Approve
                        </a>
                     <?php } ?>

                     <?php if($status != 'rejected'){ ?>
                        <a href="teacher_payment_requests.php?reject=<?php echo (int)$row['id']; ?>" class="reject-btn"
                           onclick="return confirm('Reject this payment request?');">
                           <i class="fas fa-times"></i> Reject
                        </a>
                     <?php } ?>
                  </div>

               </div>

            <?php } ?>

         </div>

      <?php }else{ ?>

         <p class="empty">
            <i class="fas fa-inbox"></i> No payment requests yet.
         </p>

      <?php } ?>

   </div>
</section>

</body>
</html>
