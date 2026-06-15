<?php
include "db.php";

if(!isset($_SESSION['teacher_id'])){
   header("Location: teacher_login.php");
   exit();
}

$teacher_id = (int) $_SESSION['teacher_id'];
$message = "";
$error = "";

/* Get teacher */
$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1");

if(!$teacher_q || mysqli_num_rows($teacher_q) == 0){
   echo "Teacher not found!";
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);


/* Add Main Category with Image */
if(isset($_POST['add_main_category'])){

   $main_title = mysqli_real_escape_string($conn, trim($_POST['main_title']));
   $main_image = "images/default-category.jpg";

   if($main_title == ""){
      $error = "Please enter main category name!";
   }else{

      $check_main = mysqli_query($conn, "
         SELECT * FROM class_main_categories
         WHERE teacher_id='$teacher_id'
         AND title='$main_title'
         LIMIT 1
      ");

      if($check_main && mysqli_num_rows($check_main) > 0){
         $error = "This main category already exists!";
      }else{

         if(isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0){

            $upload_dir = "uploads/category_images/";

            if(!is_dir($upload_dir)){
               mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . "_" . uniqid() . "_" . basename($_FILES['main_image']['name']);
            $target_file = $upload_dir . $file_name;

            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed = ["jpg", "jpeg", "png", "webp"];

            if(in_array($file_type, $allowed)){
               if(move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)){
                  $main_image = $target_file;
               }
            }else{
               $error = "Only JPG, JPEG, PNG, WEBP images allowed!";
            }
         }

         if($error == ""){
            mysqli_query($conn, "
               INSERT INTO class_main_categories(teacher_id, title, image)
               VALUES('$teacher_id', '$main_title', '$main_image')
            ");

            $message = "Main category added successfully!";
         }
      }
   }
}


/* Add Sub Category */
if(isset($_POST['add_sub_category'])){

   /*
      Now teacher can select an existing main category OR type a new one.
      If typed main category does not exist, it will be created automatically.
   */
   $main_category_title = mysqli_real_escape_string($conn, trim($_POST['main_category_title']));
   $sub_title = mysqli_real_escape_string($conn, trim($_POST['sub_title']));
   $main_category_id = 0;

   if($main_category_title == "" || $sub_title == ""){
      $error = "Please select/type main category and enter sub category!";
   }else{

      $check_main = mysqli_query($conn, "
         SELECT * FROM class_main_categories
         WHERE teacher_id='$teacher_id'
         AND title='$main_category_title'
         LIMIT 1
      ");

      if($check_main && mysqli_num_rows($check_main) > 0){
         $main_row = mysqli_fetch_assoc($check_main);
         $main_category_id = (int) $main_row['id'];
      }else{
         mysqli_query($conn, "
            INSERT INTO class_main_categories(teacher_id, title, image)
            VALUES('$teacher_id', '$main_category_title', 'images/default-category.jpg')
         ");

         $main_category_id = mysqli_insert_id($conn);
      }

      if($main_category_id == 0){
         $error = "Main category could not be created!";
      }else{
         $check_sub = mysqli_query($conn, "
            SELECT * FROM class_sub_categories
            WHERE teacher_id='$teacher_id'
            AND main_category_id='$main_category_id'
            AND title='$sub_title'
            LIMIT 1
         ");

         if($check_sub && mysqli_num_rows($check_sub) > 0){
            $error = "This sub category already exists!";
         }else{
            mysqli_query($conn, "
               INSERT INTO class_sub_categories(teacher_id, main_category_id, title)
               VALUES('$teacher_id', '$main_category_id', '$sub_title')
            ");

            $message = "Sub category added successfully!";
         }
      }
   }
}


/* Create class block */
if(isset($_POST['create_class'])){

   $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0; // this is sub category id
   $title = mysqli_real_escape_string($conn, $_POST['title']);
   $year = mysqli_real_escape_string($conn, $_POST['year']);
   $month = mysqli_real_escape_string($conn, $_POST['month']);
   $class_date = mysqli_real_escape_string($conn, $_POST['class_date']);
   $fee = mysqli_real_escape_string($conn, $_POST['fee']);
   $payment_scheme = mysqli_real_escape_string($conn, $_POST['payment_scheme']);
   $description = mysqli_real_escape_string($conn, $_POST['description']);

   $thumbnail = "images/default-class.jpg";

   if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0){

      $upload_dir = "uploads/classes/";

      if(!is_dir($upload_dir)){
         mkdir($upload_dir, 0777, true);
      }

      $file_name = time() . "_" . uniqid() . "_" . basename($_FILES['thumbnail']['name']);
      $target_file = $upload_dir . $file_name;

      $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      $allowed = ["jpg", "jpeg", "png", "webp"];

      if(in_array($file_type, $allowed)){
         if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)){
            $thumbnail = $target_file;
         }
      }else{
         $error = "Only JPG, JPEG, PNG, WEBP images allowed!";
      }
   }

   if($error == ""){
      if($category_id == 0 || $title == "" || $year == "" || $month == ""){
         $error = "Please fill required fields!";
      }else{

         mysqli_query($conn, "
            INSERT INTO teacher_classes
            (teacher_id, category_id, title, year, month, class_date, fee, payment_scheme, thumbnail, description)
            VALUES
            ('$teacher_id', '$category_id', '$title', '$year', '$month', '$class_date', '$fee', '$payment_scheme', '$thumbnail', '$description')
         ");

         header("Location: teacher_manage_classes.php?created=success");
         exit();
      }
   }
}


/* Delete class */
if(isset($_GET['delete_class'])){

   $class_id = (int) $_GET['delete_class'];

   mysqli_query($conn, "
      DELETE FROM teacher_classes
      WHERE id='$class_id'
      AND teacher_id='$teacher_id'
   ");

   mysqli_query($conn, "
      DELETE FROM class_lessons
      WHERE class_id='$class_id'
      AND teacher_id='$teacher_id'
   ");

   header("Location: teacher_manage_classes.php?deleted=success");
   exit();
}


/* Delete Sub Category */
if(isset($_GET['delete_sub_category'])){

   $sub_id = (int) $_GET['delete_sub_category'];

   mysqli_query($conn, "
      DELETE FROM class_sub_categories
      WHERE id='$sub_id'
      AND teacher_id='$teacher_id'
   ");

   mysqli_query($conn, "
      UPDATE teacher_classes
      SET category_id=NULL
      WHERE category_id='$sub_id'
      AND teacher_id='$teacher_id'
   ");

   header("Location: teacher_manage_classes.php?sub_deleted=success");
   exit();
}


/* Delete Main Category */
if(isset($_GET['delete_main_category'])){

   $main_id = (int) $_GET['delete_main_category'];

   $sub_ids_q = mysqli_query($conn, "
      SELECT id FROM class_sub_categories
      WHERE main_category_id='$main_id'
      AND teacher_id='$teacher_id'
   ");

   if($sub_ids_q){
      while($s = mysqli_fetch_assoc($sub_ids_q)){
         $sid = (int)$s['id'];
         mysqli_query($conn, "
            UPDATE teacher_classes
            SET category_id=NULL
            WHERE category_id='$sid'
            AND teacher_id='$teacher_id'
         ");
      }
   }

   mysqli_query($conn, "
      DELETE FROM class_sub_categories
      WHERE main_category_id='$main_id'
      AND teacher_id='$teacher_id'
   ");

   mysqli_query($conn, "
      DELETE FROM class_main_categories
      WHERE id='$main_id'
      AND teacher_id='$teacher_id'
   ");

   header("Location: teacher_manage_classes.php?main_deleted=success");
   exit();
}


/* Load main categories */
$main_categories = mysqli_query($conn, "
   SELECT * FROM class_main_categories
   WHERE teacher_id='$teacher_id'
   ORDER BY id DESC
");

$main_categories_for_select = mysqli_query($conn, "
   SELECT * FROM class_main_categories
   WHERE teacher_id='$teacher_id'
   ORDER BY title ASC
");

/* Load sub categories for class select */
$sub_categories_for_select = mysqli_query($conn, "
   SELECT s.*, m.title AS main_title
   FROM class_sub_categories s
   INNER JOIN class_main_categories m ON s.main_category_id = m.id
   WHERE s.teacher_id='$teacher_id'
   ORDER BY m.title ASC, s.title ASC
");

/* Load classes */
$classes = mysqli_query($conn, "
   SELECT teacher_classes.*, 
          s.title AS sub_category,
          m.title AS main_category,
          m.image AS main_image
   FROM teacher_classes
   LEFT JOIN class_sub_categories s ON teacher_classes.category_id = s.id
   LEFT JOIN class_main_categories m ON s.main_category_id = m.id
   WHERE teacher_classes.teacher_id='$teacher_id'
   ORDER BY teacher_classes.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Manage Classes</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <link rel="icon" type="image/png" href="images/favicon.png">

   <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
}

:root{
   --purple:#6d35ff;
   --purple2:#8b5cf6;
   --dark:#251047;
   --text:#2f2f3a;
   --muted:#6b7280;
   --white:#fff;
   --danger:#ef4444;
   --green:#16a34a;
   --blue:#2563eb;
   --shadow:0 20px 55px rgba(76,29,149,.13);
}

body{
   min-height:100vh;
   background:
      radial-gradient(circle at top left, rgba(109,53,255,.18), transparent 32%),
      radial-gradient(circle at bottom right, rgba(139,92,246,.20), transparent 35%),
      linear-gradient(135deg,#fbf9ff,#f2efff,#ffffff);
   color:var(--text);
   padding:32px;
}

.manage-wrap{
   max-width:1250px;
   margin:0 auto;
}

.top-bar{
   background:linear-gradient(135deg,#2b0068,#6d35ff,#986cff);
   border-radius:28px;
   padding:28px;
   display:flex;
   align-items:center;
   justify-content:space-between;
   gap:20px;
   box-shadow:0 25px 60px rgba(109,53,255,.25);
   margin-bottom:28px;
   position:relative;
   overflow:hidden;
}

.top-bar::after{
   content:'';
   position:absolute;
   right:-70px;
   top:-80px;
   width:260px;
   height:260px;
   border-radius:50%;
   background:rgba(255,255,255,.15);
}

.teacher-mini{
   display:flex;
   align-items:center;
   gap:18px;
   position:relative;
   z-index:2;
}

.teacher-mini img{
   width:72px;
   height:72px;
   border-radius:22px;
   object-fit:cover;
   border:4px solid rgba(255,255,255,.85);
}

.teacher-mini h1{
   font-size:30px;
   color:#fff;
   font-weight:800;
}

.teacher-mini p{
   color:#efe7ff;
   font-size:15px;
   margin-top:3px;
}

.back-dashboard{
   position:relative;
   z-index:2;
   background:#fff;
   color:#3b1191;
   padding:14px 24px;
   border-radius:14px;
   text-decoration:none;
   font-weight:800;
   display:inline-flex;
   align-items:center;
   gap:9px;
   transition:.25s;
}

.back-dashboard:hover{
   transform:translateY(-3px);
   box-shadow:0 12px 30px rgba(255,255,255,.25);
}

.notice{
   padding:15px 18px;
   border-radius:16px;
   margin-bottom:20px;
   font-weight:700;
   font-size:15px;
}

.success{
   background:#dcfce7;
   color:#15803d;
   border:1px solid #86efac;
}

.error{
   background:#fee2e2;
   color:#b91c1c;
   border:1px solid #fecaca;
}

.card{
   background:rgba(255,255,255,.88);
   backdrop-filter:blur(16px);
   border:1px solid rgba(255,255,255,.9);
   border-radius:26px;
   padding:28px;
   box-shadow:var(--shadow);
   margin-bottom:28px;
}

.card-title{
   font-size:28px;
   color:var(--dark);
   font-weight:800;
   margin-bottom:24px;
   padding-bottom:14px;
   border-bottom:1px solid rgba(109,53,255,.16);
   display:flex;
   align-items:center;
   gap:10px;
}

.card-title::after{
   content:'';
   flex:1;
   height:2px;
   background:linear-gradient(90deg,rgba(109,53,255,.5),transparent);
}

.form-grid{
   display:grid;
   grid-template-columns:repeat(4, 1fr);
   gap:18px;
}

.form-grid.two{
   grid-template-columns:2fr 2fr 1fr;
}

.form-group{
   display:flex;
   flex-direction:column;
   gap:8px;
}

.form-group label{
   font-weight:700;
   color:var(--dark);
   font-size:15px;
}

.input,
select,
textarea{
   width:100%;
   border:1px solid rgba(109,53,255,.18);
   outline:none;
   background:#fff;
   border-radius:14px;
   padding:14px 15px;
   font-size:15px;
   color:var(--text);
}

textarea{
   min-height:120px;
   resize:vertical;
}

.full{
   grid-column:1 / -1;
}

.btn{
   border:none;
   outline:none;
   background:linear-gradient(135deg,var(--purple),var(--purple2));
   color:#fff;
   padding:14px 24px;
   border-radius:14px;
   font-size:15px;
   font-weight:800;
   cursor:pointer;
   display:inline-flex;
   align-items:center;
   justify-content:center;
   gap:9px;
   transition:.25s;
   text-decoration:none;
   box-shadow:0 15px 30px rgba(109,53,255,.25);
}

.btn:hover{
   transform:translateY(-3px);
}

.btn-row{
   margin-top:20px;
   display:flex;
   gap:14px;
   flex-wrap:wrap;
}

.category-list{
   display:grid;
   grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
   gap:18px;
   margin-top:24px;
}

.cat-card{
   background:#fff;
   border:1px solid rgba(109,53,255,.14);
   border-radius:20px;
   padding:16px;
   box-shadow:0 12px 30px rgba(76,29,149,.06);
   overflow:hidden;
}

.cat-img{
   width:100%;
   height:145px;
   object-fit:cover;
   border-radius:16px;
   margin-bottom:15px;
   background:#f2f2f2;
}

.cat-card h3{
   color:var(--dark);
   font-size:21px;
   margin-bottom:12px;
}

.sub-list{
   display:flex;
   flex-direction:column;
   gap:9px;
   margin-bottom:14px;
}

.sub-item{
   background:#f5f0ff;
   color:#5b21b6;
   padding:10px 12px;
   border-radius:12px;
   font-size:14px;
   font-weight:700;
   display:flex;
   align-items:center;
   justify-content:space-between;
   gap:10px;
}

.sub-item a{
   color:#dc2626;
   text-decoration:none;
   font-size:13px;
}

.class-list{
   display:grid;
   gap:18px;
}

.class-item{
   background:#fff;
   border:1px solid rgba(109,53,255,.16);
   border-radius:22px;
   padding:18px;
   display:grid;
   grid-template-columns:170px 1fr auto;
   gap:20px;
   align-items:center;
   box-shadow:0 15px 35px rgba(76,29,149,.07);
   transition:.25s;
}

.class-item:hover{
   transform:translateY(-3px);
   box-shadow:0 22px 42px rgba(76,29,149,.12);
}

.class-img{
   width:170px;
   height:115px;
   border-radius:18px;
   object-fit:cover;
   border:1px solid #eee;
}

.class-info h3{
   font-size:24px;
   color:var(--dark);
   margin-bottom:10px;
}

.meta-grid{
   display:flex;
   flex-wrap:wrap;
   gap:9px;
   margin-bottom:10px;
}

.meta{
   background:#f5f0ff;
   color:#5b21b6;
   padding:7px 12px;
   border-radius:999px;
   font-size:13px;
   font-weight:700;
}

.class-info p{
   color:var(--muted);
   font-size:14px;
   line-height:1.6;
}

.action-col{
   display:flex;
   flex-direction:column;
   gap:10px;
   min-width:150px;
}

.small-btn{
   text-decoration:none;
   border:none;
   border-radius:13px;
   padding:12px 15px;
   font-size:14px;
   font-weight:800;
   color:#fff;
   display:flex;
   align-items:center;
   justify-content:center;
   gap:8px;
   transition:.25s;
}

.small-btn:hover{
   transform:translateY(-2px);
}

.lesson-btn{
   background:linear-gradient(135deg,#2563eb,#0ea5e9);
}

.view-btn{
   background:linear-gradient(135deg,#16a34a,#22c55e);
}

.delete-btn{
   background:linear-gradient(135deg,#ef4444,#dc2626);
}

.empty{
   background:#faf7ff;
   border:1px dashed rgba(109,53,255,.5);
   color:var(--purple);
   border-radius:18px;
   padding:25px;
   font-size:16px;
   font-weight:800;
   text-align:center;
}

@media(max-width:1050px){
   .form-grid,
   .form-grid.two{
      grid-template-columns:repeat(2, 1fr);
   }

   .class-item{
      grid-template-columns:140px 1fr;
   }

   .action-col{
      grid-column:1 / -1;
      flex-direction:row;
      flex-wrap:wrap;
   }
}

@media(max-width:700px){
   body{
      padding:16px;
   }

   .top-bar{
      flex-direction:column;
      align-items:flex-start;
   }

   .teacher-mini{
      flex-direction:column;
      align-items:flex-start;
   }

   .teacher-mini h1{
      font-size:25px;
   }

   .form-grid,
   .form-grid.two{
      grid-template-columns:1fr;
   }

   .class-item{
      grid-template-columns:1fr;
   }

   .class-img{
      width:100%;
      height:180px;
   }

   .action-col{
      flex-direction:column;
   }
}
   </style>
</head>
<body>

<div class="manage-wrap">

   <div class="top-bar">
      <div class="teacher-mini">
         <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" alt="">
         <div>
            <h1>Manage Classes</h1>
            <p>
               <?php echo htmlspecialchars($teacher['name']); ?> •
               <?php echo htmlspecialchars($teacher['subject']); ?>
            </p>
         </div>
      </div>

      <a href="teacher_dashboard.php" class="back-dashboard">
         <i class="fa-solid fa-arrow-left"></i>
         Back To Dashboard
      </a>
   </div>

   <?php if($message != ""){ ?>
      <div class="notice success"><?php echo $message; ?></div>
   <?php } ?>

   <?php if($error != ""){ ?>
      <div class="notice error"><?php echo $error; ?></div>
   <?php } ?>

   <?php if(isset($_GET['created'])){ ?>
      <div class="notice success">Class block created successfully!</div>
   <?php } ?>

   <?php if(isset($_GET['deleted'])){ ?>
      <div class="notice success">Class deleted successfully!</div>
   <?php } ?>

   <?php if(isset($_GET['main_deleted'])){ ?>
      <div class="notice success">Main category deleted successfully!</div>
   <?php } ?>

   <?php if(isset($_GET['sub_deleted'])){ ?>
      <div class="notice success">Sub category deleted successfully!</div>
   <?php } ?>


   <div class="card">
      <h2 class="card-title">
         <i class="fa-solid fa-layer-group"></i>
         Add Main Category
      </h2>

      <form method="post" enctype="multipart/form-data">
         <div class="form-grid two">

            <div class="form-group">
               <label>Main Category</label>
               <input type="text" name="main_title" class="input" list="mainCategoryOptions" placeholder="Select or type main category" required>
               <datalist id="mainCategoryOptions">
                  <option value="O/L">
                  <option value="A/L">
                  <option value="Grade 5 Scholarship Exam">
                  <option value="Grade 6 - 11">
                  <option value="University">
                  <option value="Professional Courses">
                  <option value="ICT">
                  <option value="Combined Maths">
                  <option value="Physics">
                  <option value="Chemistry">
               </datalist>
            </div>

            <div class="form-group">
               <label>Main Category Photo</label>
               <input type="file" name="main_image" class="input" accept="image/*">
            </div>

            <div class="form-group">
               <label>&nbsp;</label>
               <button type="submit" name="add_main_category" class="btn">
                  <i class="fa-solid fa-plus"></i>
                  Add Main
               </button>
            </div>

         </div>
      </form>
   </div>


   <div class="card">
      <h2 class="card-title">
         <i class="fa-solid fa-folder-plus"></i>
         Add Sub Category
      </h2>

      <form method="post">
         <div class="form-grid two">

            <div class="form-group">
               <label>Select / Type Main Category</label>
               <input type="text" name="main_category_title" class="input" list="existingMainCategories" placeholder="Select or type main category" required>
               <datalist id="existingMainCategories">
                  <?php if($main_categories_for_select && mysqli_num_rows($main_categories_for_select) > 0){ ?>
                     <?php while($main_select = mysqli_fetch_assoc($main_categories_for_select)){ ?>
                        <option value="<?php echo htmlspecialchars($main_select['title']); ?>">
                     <?php } ?>
                  <?php } ?>
               </datalist>
            </div>

            <div class="form-group">
               <label>Sub Category</label>
               <input type="text" name="sub_title" class="input" placeholder="Example: 2026, 2027, 2028" required>
            </div>

            <div class="form-group">
               <label>&nbsp;</label>
               <button type="submit" name="add_sub_category" class="btn">
                  <i class="fa-solid fa-plus"></i>
                  Add Sub
               </button>
            </div>

         </div>
      </form>

      <div class="category-list">

         <?php if($main_categories && mysqli_num_rows($main_categories) > 0){ ?>

            <?php while($main = mysqli_fetch_assoc($main_categories)){ ?>

               <div class="cat-card">

                  <img src="<?php echo htmlspecialchars($main['image']); ?>" class="cat-img" alt="">

                  <h3><?php echo htmlspecialchars($main['title']); ?></h3>

                  <div class="sub-list">
                     <?php
                     $main_id = (int)$main['id'];
                     $subs_q = mysqli_query($conn, "
                        SELECT * FROM class_sub_categories
                        WHERE main_category_id='$main_id'
                        AND teacher_id='$teacher_id'
                        ORDER BY id DESC
                     ");
                     ?>

                     <?php if($subs_q && mysqli_num_rows($subs_q) > 0){ ?>
                        <?php while($sub = mysqli_fetch_assoc($subs_q)){ ?>
                           <div class="sub-item">
                              <span><i class="fa-solid fa-folder"></i> <?php echo htmlspecialchars($sub['title']); ?></span>

                              <a href="teacher_manage_classes.php?delete_sub_category=<?php echo $sub['id']; ?>"
                                 onclick="return confirm('Delete this sub category?');">
                                 Delete
                              </a>
                           </div>
                        <?php } ?>
                     <?php }else{ ?>
                        <div class="sub-item">
                           <span>No sub categories yet</span>
                        </div>
                     <?php } ?>
                  </div>

                  <a href="teacher_manage_classes.php?delete_main_category=<?php echo $main['id']; ?>"
                     onclick="return confirm('Delete this main category and all sub categories?');"
                     class="small-btn delete-btn">
                     <i class="fa-solid fa-trash"></i>
                     Delete Main
                  </a>

               </div>

            <?php } ?>

         <?php }else{ ?>

            <p class="empty">No main categories added yet. Add A/L, O/L or any main category first.</p>

         <?php } ?>

      </div>
   </div>


   <div class="card">
      <h2 class="card-title">
         <i class="fa-solid fa-square-plus"></i>
         Create Class Block
      </h2>

      <form method="post" enctype="multipart/form-data">

         <div class="form-grid">

            <div class="form-group">
               <label>Class Sub Category</label>
               <select name="category_id" required>
                  <option value="">Select Sub Category</option>

                  <?php if($sub_categories_for_select && mysqli_num_rows($sub_categories_for_select) > 0){ ?>
                     <?php while($cat_select = mysqli_fetch_assoc($sub_categories_for_select)){ ?>
                        <option value="<?php echo $cat_select['id']; ?>">
                           <?php echo htmlspecialchars($cat_select['main_title']); ?> -
                           <?php echo htmlspecialchars($cat_select['title']); ?>
                        </option>
                     <?php } ?>
                  <?php } ?>

               </select>
            </div>

            <div class="form-group">
               <label>Class Title</label>
               <input type="text" name="title" class="input" placeholder="2026 A/L Full Paper Class" required>
            </div>

            <div class="form-group">
               <label>Class Year</label>
               <input type="text" name="year" class="input" placeholder="2026" required>
            </div>

            <div class="form-group">
               <label>Relevant Month</label>
               <select name="month" required>
                  <option value="">Select Month</option>
                  <option value="January">January</option>
                  <option value="February">February</option>
                  <option value="March">March</option>
                  <option value="April">April</option>
                  <option value="May">May</option>
                  <option value="June">June</option>
                  <option value="July">July</option>
                  <option value="August">August</option>
                  <option value="September">September</option>
                  <option value="October">October</option>
                  <option value="November">November</option>
                  <option value="December">December</option>
               </select>
            </div>

            <div class="form-group">
               <label>Class Date</label>
               <input type="date" name="class_date" class="input">
            </div>

            <div class="form-group">
               <label>Class Fee</label>
               <input type="text" name="fee" class="input" placeholder="4000">
            </div>

            <div class="form-group">
               <label>Payment Scheme</label>
               <select name="payment_scheme">
                  <option value="Monthly">Monthly</option>
                  <option value="One Time">One Time</option>
                  <option value="Free">Free</option>
               </select>
            </div>

            <div class="form-group">
               <label>Thumbnail Image</label>
               <input type="file" name="thumbnail" class="input" accept="image/*">
            </div>

            <div class="form-group full">
               <label>Description / Info</label>
               <textarea name="description" placeholder="Class details, WhatsApp link, Telegram link, bank details..."></textarea>
            </div>

         </div>

         <div class="btn-row">
            <button type="submit" name="create_class" class="btn">
               <i class="fa-solid fa-plus"></i>
               Create Class
            </button>

            <a href="teacher_dashboard.php" class="btn">
               <i class="fa-solid fa-arrow-left"></i>
               Back To Dashboard
            </a>
         </div>

      </form>
   </div>


   <div class="card">
      <h2 class="card-title">
         <i class="fa-solid fa-folder-open"></i>
         My Created Classes
      </h2>

      <div class="class-list">

         <?php if($classes && mysqli_num_rows($classes) > 0){ ?>

            <?php while($class = mysqli_fetch_assoc($classes)){ ?>

               <div class="class-item">

                  <img src="<?php echo htmlspecialchars($class['thumbnail']); ?>" class="class-img" alt="">

                  <div class="class-info">
                     <h3><?php echo htmlspecialchars($class['title']); ?></h3>

                     <div class="meta-grid">

                        <span class="meta">
                           <i class="fa-solid fa-layer-group"></i>
                           <?php echo htmlspecialchars($class['main_category'] ?? 'No Main'); ?> /
                           <?php echo htmlspecialchars($class['sub_category'] ?? 'No Sub'); ?>
                        </span>

                        <span class="meta">
                           <i class="fa-solid fa-calendar"></i>
                           Year: <?php echo htmlspecialchars($class['year']); ?>
                        </span>

                        <span class="meta">
                           <i class="fa-regular fa-calendar"></i>
                           Month: <?php echo htmlspecialchars($class['month']); ?>
                        </span>

                        <?php if(!empty($class['class_date'])){ ?>
                           <span class="meta">
                              <i class="fa-solid fa-calendar-days"></i>
                              Date: <?php echo htmlspecialchars($class['class_date']); ?>
                           </span>
                        <?php } ?>

                        <?php if(!empty($class['fee'])){ ?>
                           <span class="meta">
                              <i class="fa-solid fa-money-bill"></i>
                              LKR <?php echo htmlspecialchars($class['fee']); ?>
                           </span>
                        <?php } ?>

                        <span class="meta">
                           <i class="fa-regular fa-credit-card"></i>
                           <?php echo htmlspecialchars($class['payment_scheme']); ?>
                        </span>

                     </div>

                     <?php if(!empty($class['description'])){ ?>
                        <p><?php echo htmlspecialchars($class['description']); ?></p>
                     <?php } ?>
                  </div>

                  <div class="action-col">

                     <a href="teacher_add_lessons.php?class_id=<?php echo $class['id']; ?>&teacher_id=<?php echo $teacher_id; ?>" class="small-btn lesson-btn">
                        <i class="fa-solid fa-video"></i>
                        Add Lessons
                     </a>

                     <a href="view_class.php?class_id=<?php echo $class['id']; ?>&teacher_id=<?php echo $teacher_id; ?>" class="small-btn view-btn">
                        <i class="fa-solid fa-eye"></i>
                        View Class
                     </a>

                     <a href="teacher_manage_classes.php?delete_class=<?php echo $class['id']; ?>"
                        onclick="return confirm('Delete this class and its lessons?');"
                        class="small-btn delete-btn">
                        <i class="fa-solid fa-trash"></i>
                        Delete Class
                     </a>

                  </div>

               </div>

            <?php } ?>

         <?php }else{ ?>

            <p class="empty">No class blocks created yet.</p>

         <?php } ?>

      </div>
   </div>

</div>

</body>
</html>
