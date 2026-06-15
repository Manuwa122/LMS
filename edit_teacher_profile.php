<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = $_SESSION['teacher_id'];

$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id'");

if(mysqli_num_rows($teacher_q) == 0){
   echo "Teacher not found!";
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);

$message = "";

if(isset($_POST['update'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $subject = mysqli_real_escape_string($conn, $_POST['subject']);
   $phone = mysqli_real_escape_string($conn, $_POST['phone']);
   $bio = mysqli_real_escape_string($conn, $_POST['bio']);

   $image = $_FILES['profile']['name'];
   $tmp_name = $_FILES['profile']['tmp_name'];

   if(!empty($image)){

      $new_image = "uploads/" . time() . "_" . $image;

      move_uploaded_file($tmp_name, $new_image);

      if(file_exists($teacher['profile_image'])){
         unlink($teacher['profile_image']);
      }

      mysqli_query($conn, "UPDATE teachers SET
      name='$name',
      subject='$subject',
      phone='$phone',
      bio='$bio',
      profile_image='$new_image'
      WHERE id='$teacher_id'");

   }else{

      mysqli_query($conn, "UPDATE teachers SET
      name='$name',
      subject='$subject',
      phone='$phone',
      bio='$bio'
      WHERE id='$teacher_id'");

   }

   $message = "Profile updated successfully!";

   $teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id'");
   $teacher = mysqli_fetch_assoc($teacher_q);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>Edit Teacher Profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <link rel="stylesheet" href="style3.css">

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
   --text:#1f2937;
   --muted:#6b7280;
   --white:#fff;
   --shadow:0 25px 60px rgba(31,41,55,.14);
}

body{
   min-height:100vh;
   background:
      radial-gradient(circle at top left, rgba(108,56,255,.25), transparent 35%),
      radial-gradient(circle at bottom right, rgba(36,0,70,.18), transparent 35%),
      linear-gradient(135deg, #f8f7ff 0%, #eef2ff 50%, #f4f4f5 100%);
   color:var(--text);
   padding:35px;
}

.edit-profile-page{
   min-height:calc(100vh - 70px);
   display:flex;
   align-items:center;
   justify-content:center;
}

.edit-card{
   width:1100px;
   background:rgba(255,255,255,.78);
   backdrop-filter:blur(18px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:32px;
   overflow:hidden;
   display:grid;
   grid-template-columns:38% 62%;
   box-shadow:var(--shadow);
   position:relative;
}

.edit-card::before{
   content:'';
   position:absolute;
   width:230px;
   height:230px;
   border-radius:50%;
   background:rgba(108,56,255,.12);
   right:-70px;
   top:-70px;
}

.edit-left{
   background:
      radial-gradient(circle at top left, rgba(255,255,255,.18), transparent 35%),
      linear-gradient(135deg, var(--deep), var(--purple));
   color:#fff;
   padding:60px 45px;
   display:flex;
   flex-direction:column;
   align-items:center;
   justify-content:center;
   text-align:center;
   position:relative;
   overflow:hidden;
}

.edit-left::after{
   content:'Profile';
   position:absolute;
   bottom:25px;
   left:20px;
   font-size:72px;
   font-weight:800;
   color:rgba(255,255,255,.06);
   letter-spacing:3px;
}

.edit-left img{
   width:145px;
   height:145px;
   border-radius:35px;
   object-fit:cover;
   border:6px solid rgba(255,255,255,.9);
   box-shadow:0 20px 45px rgba(0,0,0,.25);
   margin-bottom:25px;
   position:relative;
   z-index:2;
}

.edit-left h2{
   font-size:30px;
   font-weight:800;
   margin-bottom:8px;
   position:relative;
   z-index:2;
}

.edit-left p{
   background:rgba(255,255,255,.16);
   padding:9px 22px;
   border-radius:999px;
   font-size:15px;
   font-weight:700;
   color:rgba(255,255,255,.9);
   margin-bottom:35px;
   position:relative;
   z-index:2;
}

.back-btn{
   text-decoration:none;
   color:#fff;
   border:1px solid rgba(255,255,255,.85);
   border-radius:999px;
   padding:14px 38px;
   font-weight:700;
   transition:.3s ease;
   position:relative;
   z-index:2;
}

.back-btn:hover{
   background:#fff;
   color:var(--deep);
   transform:translateY(-3px);
}

.edit-right{
   padding:55px 65px;
   position:relative;
   z-index:2;
}

.edit-right h1{
   font-size:36px;
   font-weight:800;
   color:var(--deep);
   margin-bottom:30px;
   display:flex;
   align-items:center;
   gap:12px;
}

.edit-right h1::before{
   content:'';
   width:12px;
   height:38px;
   border-radius:20px;
   background:linear-gradient(180deg, var(--purple), var(--deep));
   box-shadow:0 10px 25px rgba(108,56,255,.35);
}

.message{
   background:#ecfdf5;
   color:#059669;
   border:1px solid #bbf7d0;
   padding:14px 18px;
   border-radius:16px;
   margin-bottom:20px;
   font-size:15px;
   font-weight:700;
   text-align:center;
}

.input-group{
   margin-bottom:19px;
}

.input-group label{
   display:block;
   color:var(--muted);
   font-size:15px;
   font-weight:600;
   margin-bottom:8px;
}

.input-group input,
.input-group textarea{
   width:100%;
   border:none;
   outline:none;
   background:#f3f4ff;
   border:1px solid rgba(108,56,255,.12);
   color:var(--text);
   font-size:16px;
   border-radius:17px;
   padding:17px 20px;
   transition:.25s ease;
}

.input-group input{
   height:58px;
}

.input-group textarea{
   height:120px;
   resize:none;
}

.input-group input:focus,
.input-group textarea:focus{
   background:#fff;
   border-color:rgba(108,56,255,.55);
   box-shadow:0 0 0 5px rgba(108,56,255,.10);
}

.input-group input[type="file"]{
   padding:15px;
   cursor:pointer;
}

.update-btn{
   width:220px;
   height:56px;
   border:none;
   outline:none;
   border-radius:18px;
   background:linear-gradient(135deg, var(--purple), var(--purple2));
   color:#fff;
   font-size:16px;
   font-weight:800;
   cursor:pointer;
   box-shadow:0 16px 32px rgba(108,56,255,.28);
   transition:.3s ease;
   position:relative;
   overflow:hidden;
}

.update-btn:hover{
   transform:translateY(-4px);
   box-shadow:0 20px 40px rgba(108,56,255,.4);
}

@media(max-width:900px){
   body{
      padding:18px;
   }

   .edit-card{
      width:100%;
      grid-template-columns:1fr;
   }

   .edit-left{
      padding:45px 25px;
   }

   .edit-right{
      padding:40px 25px;
   }

   .edit-right h1{
      font-size:30px;
   }

   .update-btn{
      width:100%;
   }
}
</style>




</head>
<body>

<section class="edit-profile-page">

   <div class="edit-card">

      <div class="edit-left">
         <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" alt="Teacher Profile">
         <h2><?php echo htmlspecialchars($teacher['name']); ?></h2>
         <p><?php echo htmlspecialchars($teacher['subject']); ?></p>

         <a href="teacher_dashboard.php" class="back-btn">Back Dashboard</a>
      </div>

      <div class="edit-right">

         <h1>Edit Teacher Profile</h1>

         <?php
         if(isset($message) && $message != ""){
            echo '<div class="message">'.$message.'</div>';
         }
         ?>

         <form method="post" enctype="multipart/form-data">

            <div class="input-group">
               <label>Teacher Name</label>
               <input 
                  type="text" 
                  name="name" 
                  value="<?php echo htmlspecialchars($teacher['name']); ?>" 
                  placeholder="Enter teacher name"
                  required
               >
            </div>

            <div class="input-group">
               <label>Subject</label>
               <input 
                  type="text" 
                  name="subject" 
                  value="<?php echo htmlspecialchars($teacher['subject']); ?>" 
                  placeholder="Enter subject"
                  required
               >
            </div>

            <div class="input-group">
               <label>Phone Number</label>
               <input 
                  type="text" 
                  name="phone" 
                  value="<?php echo htmlspecialchars($teacher['phone']); ?>" 
                  placeholder="Enter phone number"
               >
            </div>

            <div class="input-group">
               <label>Teacher Bio</label>
               <textarea 
                  name="bio" 
                  placeholder="Write about teacher"><?php echo htmlspecialchars($teacher['bio']); ?></textarea>
            </div>

            <div class="input-group">
               <label>Change Profile Image</label>
               <input type="file" name="profile_image" accept="image/*">
            </div>

            <input type="submit" name="update" value="Update Profile" class="update-btn">

         </form>

      </div>

   </div>

</section>

</body>
</html>