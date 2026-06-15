<?php
include "db.php";

if(!isset($_GET['main_id'])){
   header("Location: courses.php");
   exit();
}

$main_id = (int)$_GET['main_id'];

$main_q = mysqli_query($conn, "
   SELECT m.*, t.name AS teacher_name, t.subject, t.profile_image
   FROM class_main_categories m
   JOIN teachers t ON m.teacher_id = t.id
   WHERE m.id='$main_id'
   LIMIT 1
");

if(mysqli_num_rows($main_q) == 0){
   echo "Category not found!";
   exit();
}

$main = mysqli_fetch_assoc($main_q);

$subs = mysqli_query($conn, "
   SELECT * FROM class_sub_categories
   WHERE main_category_id='$main_id'
   ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title><?php echo htmlspecialchars($main['title']); ?></title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="style3.css">
     <link rel="icon" type="image/png" href="images/favicon.png">

   <style>
      body{
         background:linear-gradient(135deg,#f4efff,#ffffff);
      }

      .sub-wrap{
         max-width:1100px;
         margin:4rem auto;
         padding:2rem;
      }

      .main-banner{
         background:linear-gradient(135deg,#3b0a83,#7c3cff);
         border-radius:2rem;
         overflow:hidden;
         color:#fff;
         box-shadow:0 1rem 3rem rgba(80,30,160,.25);
         margin-bottom:3rem;
      }

      .main-banner img{
         width:100%;
         height:25rem;
         object-fit:cover;
         display:block;
      }

      .banner-content{
         padding:2.5rem;
      }

      .banner-content h1{
         font-size:3rem;
         margin-bottom:.8rem;
      }

      .banner-content p{
         font-size:1.6rem;
         opacity:.9;
      }

      .back-link{
         display:inline-block;
         margin-bottom:2rem;
         color:#5d25d9;
         background:#fff;
         padding:1rem 2rem;
         border-radius:1rem;
         font-size:1.6rem;
         font-weight:700;
      }

      .sub-grid{
         display:grid;
         grid-template-columns:repeat(auto-fit,minmax(25rem,1fr));
         gap:2rem;
      }

      .sub-box{
         background:#fff;
         border:.1rem solid #eadfff;
         padding:2.5rem;
         border-radius:1.6rem;
         box-shadow:0 .8rem 2rem rgba(0,0,0,.07);
         transition:.3s ease;
      }

      .sub-box:hover{
         transform:translateY(-.5rem);
      }

      .sub-box i{
         font-size:3rem;
         color:#7c3cff;
         margin-bottom:1.2rem;
      }

      .sub-box h3{
         font-size:2.3rem;
         color:#261044;
         margin-bottom:1rem;
      }

      .sub-box p{
         font-size:1.5rem;
         color:#777;
         margin-bottom:1.5rem;
      }

      .view-btn{
         display:block;
         text-align:center;
         background:linear-gradient(135deg,#6b28f5,#9b5cff);
         color:#fff;
         padding:1.2rem;
         border-radius:1rem;
         font-size:1.6rem;
         font-weight:700;
      }

      .empty{
         background:#fff;
         padding:2rem;
         border-radius:1rem;
         font-size:1.7rem;
         color:#777;
      }
   </style>
</head>
<body>

<div class="sub-wrap">

   <a href="teacher_course_page.php?id=<?php echo $main['teacher_id']; ?>" class="back-link">
      <i class="fas fa-arrow-left"></i> Back To Main Categories
   </a>

   <div class="main-banner">
      <?php if($main['image'] != ""){ ?>
         <img src="<?php echo htmlspecialchars($main['image']); ?>" alt="">
      <?php }else{ ?>
         <img src="images/default-category.jpg" alt="">
      <?php } ?>

      <div class="banner-content">
         <h1><?php echo htmlspecialchars($main['title']); ?></h1>
         <p>
            <?php echo htmlspecialchars($main['teacher_name']); ?> · 
            <?php echo htmlspecialchars($main['subject']); ?>
         </p>
      </div>
   </div>

   <div class="sub-grid">

      <?php
      if(mysqli_num_rows($subs) > 0){
         while($sub = mysqli_fetch_assoc($subs)){
      ?>

      <div class="sub-box">
         <i class="fas fa-folder-open"></i>
         <h3><?php echo htmlspecialchars($sub['title']); ?></h3>
         <p>Open this sub category to view lessons or videos.</p>

         <a href="student_lessons.php?sub_id=<?php echo $sub['id']; ?>" class="view-btn">
            Open <i class="fas fa-arrow-right"></i>
         </a>
      </div>

      <?php
         }
      }else{
         echo '<p class="empty">No sub categories added yet.</p>';
      }
      ?>

   </div>

</div>

</body>
</html>