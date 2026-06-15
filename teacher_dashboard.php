<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teachers.php");
   exit();
}

$teacher_id = $_SESSION['teacher_id'];

$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id'");

if(mysqli_num_rows($teacher_q) == 0){
   echo "Teacher not found!";
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);

/* Access requests from main users table */
$requests = mysqli_query($conn, "
   SELECT access_requests.*, users.uidUsers, users.emailUsers
   FROM access_requests
   JOIN users ON access_requests.student_id = users.idUsers
   WHERE access_requests.teacher_id = '$teacher_id'
   ORDER BY access_requests.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
   <title>Teacher Dashboard</title>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="style3.css">

   <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
}

:root{
   --purple:#6c38ff;
   --purple2:#8b5cf6;
   --deep:#240046;
   --light:#f5f3ff;
   --white:#ffffff;
   --text:#1f2937;
   --muted:#6b7280;
   --danger:#ef4444;
   --success:#22c55e;
   --warning:#f59e0b;
   --shadow:0 25px 60px rgba(31,41,55,.13);
}

body{
   min-height:100vh;
   color:var(--text);
   padding:35px;
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.25), transparent 35%),
      radial-gradient(circle at bottom right, rgba(36,0,70,.18), transparent 35%),
      linear-gradient(135deg, #f8f7ff 0%, #eef2ff 50%, #f4f4f5 100%);
}

.teacher-dashboard{
   max-width:1250px;
   margin:0 auto;
}

.dashboard-title{
   font-size:38px;
   font-weight:800;
   color:var(--deep);
   margin-bottom:28px;
   display:flex;
   align-items:center;
   gap:12px;
}

.dashboard-title::before{
   content:'';
   width:16px;
   height:42px;
   border-radius:20px;
   background:linear-gradient(180deg, var(--purple), var(--deep));
   box-shadow:0 10px 25px rgba(108,56,255,.4);
}

.dashboard-title::after{
   content:'';
   flex:1;
   height:2px;
   background:linear-gradient(90deg, rgba(108,56,255,.45), transparent);
}

.profile-card{
   position:relative;
   overflow:hidden;
   background:rgba(255,255,255,.78);
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:30px;
   padding:34px;
   box-shadow:var(--shadow);
   display:grid;
   grid-template-columns:120px 1fr;
   gap:28px;
   align-items:center;
   margin-bottom:32px;
}

.profile-card::before{
   content:'';
   position:absolute;
   inset:0;
   background:
      linear-gradient(135deg, rgba(108,56,255,.16), transparent 45%),
      radial-gradient(circle at top right, rgba(36,0,70,.18), transparent 35%);
   pointer-events:none;
}

.profile-card::after{
   content:'Teacher';
   position:absolute;
   right:35px;
   top:25px;
   font-size:82px;
   font-weight:800;
   color:rgba(108,56,255,.06);
   letter-spacing:3px;
}

.profile-card img{
   position:relative;
   z-index:2;
   width:115px;
   height:115px;
   border-radius:28px;
   object-fit:cover;
   border:5px solid #fff;
   box-shadow:0 18px 35px rgba(108,56,255,.28);
   background:#fff;
}

.teacher-info{
   position:relative;
   z-index:2;
}

.teacher-info h3{
   font-size:31px;
   font-weight:800;
   color:var(--deep);
   margin-bottom:8px;
}

.subject{
   display:inline-flex;
   align-items:center;
   background:linear-gradient(135deg, #ede9fe, #f5f3ff);
   color:var(--purple);
   padding:8px 20px;
   border-radius:999px;
   font-size:15px;
   font-weight:700;
   margin-bottom:18px;
}

.info-grid{
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(230px, 1fr));
   gap:15px;
   margin-top:16px;
}

.info-item{
   background:rgba(255,255,255,.85);
   border:1px solid rgba(108,56,255,.12);
   border-radius:18px;
   padding:15px 18px;
   color:var(--muted);
   font-size:15px;
   box-shadow:0 10px 28px rgba(108,56,255,.06);
}

.info-item span{
   display:block;
   margin-top:5px;
   color:var(--deep);
   font-weight:700;
   word-break:break-word;
}

.dashboard-actions{
   position:relative;
   z-index:2;
   grid-column:1 / -1;
   display:flex;
   flex-wrap:wrap;
   gap:14px;
   margin-top:12px;
}

.dashboard-actions a{
   display:inline-flex;
   align-items:center;
   justify-content:center;
   min-width:150px;
   min-height:52px;
   text-decoration:none;
   border-radius:16px;
   font-size:15px;
   font-weight:700;
   color:#fff;
   background:linear-gradient(135deg, var(--purple), var(--purple2));
   box-shadow:0 14px 28px rgba(108,56,255,.28);
   transition:.28s ease;
   position:relative;
   overflow:hidden;
}

.dashboard-actions a::before{
   content:'';
   position:absolute;
   top:0;
   left:-100%;
   width:100%;
   height:100%;
   background:linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent);
   transition:.45s ease;
}

.dashboard-actions a:hover::before{
   left:100%;
}

.dashboard-actions a:hover{
   transform:translateY(-4px);
   box-shadow:0 18px 35px rgba(108,56,255,.38);
}

.dashboard-actions a:nth-child(2){
   background:linear-gradient(135deg, #0ea5e9, #2563eb);
}

.dashboard-actions a:nth-child(3){
   background:linear-gradient(135deg, #10b981, #059669);
}

.dashboard-actions .logout{
   background:linear-gradient(135deg, #f43f5e, #dc2626);
}

.section-card{
   background:rgba(255,255,255,.78);
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:30px;
   padding:32px;
   box-shadow:var(--shadow);
   position:relative;
   overflow:hidden;
}

.section-card::before{
   content:'';
   position:absolute;
   width:180px;
   height:180px;
   background:rgba(108,56,255,.1);
   border-radius:50%;
   right:-60px;
   top:-60px;
}

.section-card h2{
   font-size:30px;
   font-weight:800;
   color:var(--deep);
   margin-bottom:25px;
   padding-bottom:16px;
   border-bottom:1px solid rgba(108,56,255,.18);
   position:relative;
}

.section-card h2::after{
   content:'';
   position:absolute;
   left:0;
   bottom:-2px;
   width:90px;
   height:4px;
   border-radius:20px;
   background:linear-gradient(135deg, var(--purple), var(--deep));
}

.requests-grid{
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));
   gap:20px;
   position:relative;
   z-index:2;
}

.request-card{
   background:#fff;
   border:1px solid rgba(108,56,255,.12);
   border-radius:24px;
   padding:24px;
   box-shadow:0 18px 38px rgba(108,56,255,.08);
   transition:.3s ease;
}

.request-card:hover{
   transform:translateY(-5px);
   box-shadow:0 25px 55px rgba(108,56,255,.15);
}

.request-user{
   display:flex;
   align-items:center;
   gap:15px;
   margin-bottom:18px;
}

.student-icon{
   width:54px;
   height:54px;
   border-radius:18px;
   background:linear-gradient(135deg, var(--purple), var(--purple2));
   color:#fff;
   display:flex;
   align-items:center;
   justify-content:center;
   font-size:22px;
   box-shadow:0 12px 25px rgba(108,56,255,.25);
}

.request-user h3{
   font-size:20px;
   color:var(--deep);
   font-weight:800;
}

.request-user span{
   color:var(--muted);
   font-size:14px;
   word-break:break-word;
}

.request-info{
   display:grid;
   gap:12px;
   margin-bottom:18px;
}

.request-info p{
   background:#f8f7ff;
   border:1px solid rgba(108,56,255,.1);
   border-radius:16px;
   padding:13px 15px;
   color:var(--muted);
   font-size:14px;
}

.request-info p span{
   display:block;
   color:var(--deep);
   font-weight:700;
   margin-top:4px;
}

.status-badge{
   display:inline-block !important;
   width:max-content;
   padding:6px 14px;
   border-radius:999px;
   color:#fff !important;
   font-size:13px;
   text-transform:capitalize;
}

.status-badge.pending{
   background:var(--warning);
}

.status-badge.unpaid{
   background:#f97316;
}

.status-badge.approved{
   background:var(--success);
}

.status-badge.rejected{
   background:var(--danger);
}

.receipt-box{
   background:#f8f7ff;
   border-radius:18px;
   padding:15px;
   margin-bottom:18px;
}

.receipt-box h4{
   color:var(--deep);
   font-size:16px;
   margin-bottom:12px;
}

.receipt-btn{
   display:inline-flex;
   text-decoration:none;
   background:linear-gradient(135deg, #0ea5e9, #2563eb);
   color:#fff;
   padding:11px 20px;
   border-radius:12px;
   font-size:14px;
   font-weight:700;
}

.receipt-img{
   width:100%;
   max-height:220px;
   object-fit:cover;
   border-radius:16px;
   border:4px solid #fff;
   box-shadow:0 12px 25px rgba(0,0,0,.1);
}

.no-receipt{
   background:#fff1f2;
   color:#e11d48;
   border-radius:16px;
   padding:14px;
   font-weight:700;
   margin-bottom:16px;
   text-align:center;
}

.request-btns{
   display:flex;
   gap:12px;
   flex-wrap:wrap;
}

.approve-btn,
.reject-btn{
   flex:1;
   display:inline-flex;
   align-items:center;
   justify-content:center;
   min-height:45px;
   border-radius:14px;
   text-decoration:none;
   color:#fff;
   font-size:14px;
   font-weight:800;
   transition:.25s ease;
}

.approve-btn{
   background:linear-gradient(135deg, #22c55e, #16a34a);
}

.reject-btn{
   background:linear-gradient(135deg, #ef4444, #dc2626);
}

.approve-btn:hover,
.reject-btn:hover{
   transform:translateY(-3px);
   filter:brightness(1.05);
}

.empty{
   grid-column:1 / -1;
   background:linear-gradient(135deg, #f5f3ff, #eef2ff);
   border:1px dashed rgba(108,56,255,.45);
   border-radius:20px;
   padding:25px;
   color:var(--purple);
   font-size:16px;
   font-weight:800;
   text-align:center;
}

@media(max-width:900px){
   body{
      padding:18px;
   }

   .dashboard-title{
      font-size:30px;
   }

   .profile-card{
      grid-template-columns:1fr;
      text-align:center;
      padding:28px 22px;
   }

   .profile-card img{
      margin:auto;
   }

   .profile-card::after{
      display:none;
   }

   .dashboard-actions{
      justify-content:center;
   }

   .dashboard-actions a{
      width:100%;
   }

   .requests-grid{
      grid-template-columns:1fr;
   }
}


.receipt-box{
   background:#f8f4ff;
   border:1px solid #e6d8ff;
   padding:1.2rem;
   border-radius:1rem;
   margin:1.5rem 0;
}

.receipt-title{
   font-size:1.5rem;
   font-weight:700;
   color:#351066;
   margin-bottom:1rem;
}

.receipt-img{
   width:100%;
   max-height:22rem;
   object-fit:cover;
   border-radius:1rem;
   border:1px solid #e5d8ff;
   cursor:pointer;
}

.view-receipt-btn{
   display:block;
   text-align:center;
   background:#6d28d9;
   color:#fff;
   padding:1rem;
   border-radius:.8rem;
   font-size:1.5rem;
   font-weight:700;
}

.no-receipt{
   background:#fff1f2;
   color:#e11d48;
   padding:1.2rem;
   border-radius:1rem;
   font-size:1.4rem;
   font-weight:700;
   text-align:center;
   margin:1.5rem 0;
}


</style>
</head>
<body>

<section class="teacher-dashboard">

   <h1 class="dashboard-title">Teacher Dashboard</h1>

   <div class="profile-card">

      <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" alt="Teacher Profile">

      <div class="teacher-info">
         <h3><?php echo htmlspecialchars($teacher['name']); ?></h3>
         <span class="subject"><?php echo htmlspecialchars($teacher['subject']); ?></span>

         <div class="info-grid">
            <div class="info-item">
               Email
               <span><?php echo htmlspecialchars($teacher['email']); ?></span>
            </div>

            <div class="info-item">
               Phone
               <span><?php echo htmlspecialchars($teacher['phone']); ?></span>
            </div>

            <div class="info-item">
               Bio
               <span><?php echo htmlspecialchars($teacher['bio']); ?></span>
            </div>
         </div>
      </div>

      <div class="dashboard-actions">
         <a href="edit_teacher_profile.php">Edit Profile</a>
         <a href="teacher_manage_classes.php" class="inline-btn">
         <i class="fas fa-folder-plus"></i> Manage Class Blocks
         </a>
         <a href="teacher_videos.php">My Videos</a>
         <a href="teacher_payment_requests.php" class="dashboard-action-btn payment-btn">
          <i class="fas fa-file-invoice-dollar"></i>
            Payment Requests
         </a>
         <a href="teacher_logout.php" class="logout">Logout</a>
         


      </div>

   </div>

   <div class="section-card">

      <h2>Access Requests</h2>

      <div class="requests-grid">

         <?php if($requests && mysqli_num_rows($requests) > 0){ ?>

            <?php while($row = mysqli_fetch_assoc($requests)){ ?>

               <div class="request-card">

                  <div class="request-user">
                     <div class="student-icon">
                        <i class="fas fa-user-graduate"></i>
                     </div>

                     <div>
                        <h3><?php echo htmlspecialchars($row['uidUsers']); ?></h3>
                        <span><?php echo htmlspecialchars($row['emailUsers']); ?></span>
                     </div>
                  </div>

                  <div class="request-info">
                     <p>
                        Status
                        <span class="status-badge <?php echo htmlspecialchars($row['status']); ?>">
                           <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                     </p>

                     <?php if(!empty($row['approved_at'])){ ?>
                        <p>
                           Approved At
                           <span><?php echo htmlspecialchars($row['approved_at']); ?></span>
                        </p>
                     <?php } ?>

                     <?php if(!empty($row['expires_at'])){ ?>
                        <p>
                           Expires At
                           <span><?php echo htmlspecialchars($row['expires_at']); ?></span>
                        </p>
                     <?php } ?>
                  </div>

                  <?php if(!empty($row['payment_receipt'])){ ?>

                     <div class="receipt-box">
                        <h4>Payment Receipt</h4>

                        <?php
                           $file_ext = strtolower(pathinfo($row['payment_receipt'], PATHINFO_EXTENSION));
                        ?>

                        <?php if($file_ext == "pdf"){ ?>

                           <a href="<?php echo htmlspecialchars($row['payment_receipt']); ?>" 
                              target="_blank" 
                              class="receipt-btn">
                              View Receipt PDF
                           </a>

                        <?php }else{ ?>

                           <a href="<?php echo htmlspecialchars($row['payment_receipt']); ?>" target="_blank">
                              <img src="<?php echo htmlspecialchars($row['payment_receipt']); ?>" 
                              alt="payment receipt"
                              class="receipt-img">
                           </a>

                        <?php } ?>

                     </div>

                  <?php }else{ ?>

                    <?php
$receipt = "";

if(isset($row['receipt']) && $row['receipt'] != ""){
   $receipt = $row['receipt'];
}elseif(isset($row['receipt_path']) && $row['receipt_path'] != ""){
   $receipt = $row['receipt_path'];
}elseif(isset($row['receipt_image']) && $row['receipt_image'] != ""){
   $receipt = $row['receipt_image'];
}
?>

<?php if($receipt != ""){ ?>

   <div class="receipt-box">
      <p class="receipt-title">
         <i class="fas fa-receipt"></i> Payment Receipt
      </p>

      <?php
      $ext = strtolower(pathinfo($receipt, PATHINFO_EXTENSION));
      ?>

      <?php if($ext == "pdf"){ ?>
         <a href="<?php echo htmlspecialchars($receipt); ?>" target="_blank" class="view-receipt-btn">
            View PDF Receipt
         </a>
      <?php }else{ ?>
         <a href="<?php echo htmlspecialchars($receipt); ?>" target="_blank">
            <img src="<?php echo htmlspecialchars($receipt); ?>" class="receipt-img" alt="Payment Receipt">
         </a>
      <?php } ?>
   </div>

<?php }else{ ?>

   <div class="no-receipt">
      No payment receipt uploaded.
   </div>

<?php } ?>

                  <?php } ?>

                  <?php if($row['status'] == "pending" || $row['status'] == "unpaid"){ ?>

                     <div class="request-btns">

                        <a href="approve_access.php?id=<?php echo $row['id']; ?>" class="approve-btn">
                           Approve
                        </a>

                        <a href="reject_access.php?id=<?php echo $row['id']; ?>" class="reject-btn">
                           Reject
                        </a>

                     </div>

                  <?php } ?>

               </div>

            <?php } ?>

         <?php }else{ ?>

            <p class="empty">No access requests yet!</p>

         <?php } ?>

      </div>

   </div>

</section>

</body>
</html>