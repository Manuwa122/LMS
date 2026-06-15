<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

include "db.php";

if(!isset($_GET['class_id']) || !isset($_GET['teacher_id'])){
   header("Location: courses.php");
   exit();
}

$class_id = (int) $_GET['class_id'];
$teacher_id = (int) $_GET['teacher_id'];

$user_id = null;

if(isset($_SESSION['userId'])){
   $user_id = (int) $_SESSION['userId'];
}elseif(isset($_SESSION['student_id'])){
   $user_id = (int) $_SESSION['student_id'];
}

if(!$user_id){
   header("Location: index.php");
   exit();
}

/* Teacher */
$teacher_q = mysqli_query($conn, "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1");

if(!$teacher_q || mysqli_num_rows($teacher_q) == 0){
   header("Location: courses.php");
   exit();
}

$teacher = mysqli_fetch_assoc($teacher_q);

/* Current class only */
$current_class_q = mysqli_query($conn, "
   SELECT * FROM teacher_classes 
   WHERE id='$class_id' 
   AND teacher_id='$teacher_id'
   LIMIT 1
");

if(!$current_class_q || mysqli_num_rows($current_class_q) == 0){
   header("Location: teacher_course_page.php?id=$teacher_id");
   exit();
}

$current_class = mysqli_fetch_assoc($current_class_q);

/* Check approved access for THIS class only */
$access_q = mysqli_query($conn, "
   SELECT * FROM access_requests 
   WHERE student_id='$user_id' 
   AND teacher_id='$teacher_id'
   AND class_id='$class_id'
   AND status='approved'
   LIMIT 1
");

if(!$access_q || mysqli_num_rows($access_q) == 0){
   header("Location: teacher_course_page.php?id=$teacher_id");
   exit();
}

$access = mysqli_fetch_assoc($access_q);

if(!empty($access['expires_at']) && strtotime($access['expires_at']) < time()){
   header("Location: teacher_course_page.php?id=$teacher_id");
   exit();
}

/* Load lessons ONLY for this class block */
$lessons = mysqli_query($conn, "
   SELECT * FROM class_lessons
   WHERE teacher_id='$teacher_id'
   AND class_id='$class_id'
   ORDER BY id ASC
");

function getLessonTitle($lesson){
   if(isset($lesson['title']) && $lesson['title'] != ""){
      return $lesson['title'];
   }

   if(isset($lesson['lesson_title']) && $lesson['lesson_title'] != ""){
      return $lesson['lesson_title'];
   }

   if(isset($lesson['name']) && $lesson['name'] != ""){
      return $lesson['name'];
   }

   return "Lesson";
}

function getLessonType($lesson){
   if(isset($lesson['type']) && $lesson['type'] != ""){
      return strtolower($lesson['type']);
   }

   if(isset($lesson['lesson_type']) && $lesson['lesson_type'] != ""){
      return strtolower($lesson['lesson_type']);
   }

   if(isset($lesson['file_type']) && $lesson['file_type'] != ""){
      return strtolower($lesson['file_type']);
   }

   $url = getLessonUrl($lesson);
   $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));

   if($ext == "pdf"){
      return "pdf";
   }

   return "video";
}

function getLessonUrl($lesson){
   $possible_columns = [
      'link_url',
      'url',
      'video_url',
      'file_url',
      'file_path',
      'lesson_url',
      'content_url'
   ];

   foreach($possible_columns as $col){
      if(isset($lesson[$col]) && $lesson[$col] != ""){
         return $lesson[$col];
      }
   }

   return "#";
}

function isVideo($type, $url){
   $type = strtolower($type);

   if($type == "video"){
      return true;
   }

   if(strpos($url, "youtube.com") !== false || strpos($url, "youtu.be") !== false){
      return true;
   }

   $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
   return in_array($ext, ["mp4", "webm", "ogg"]);
}

function getEmbedUrl($url){
   if(strpos($url, "youtube.com/watch?v=") !== false){
      $parts = parse_url($url);
      parse_str($parts['query'], $query);

      if(isset($query['v'])){
         return "https://www.youtube.com/embed/" . $query['v'];
      }
   }

   if(strpos($url, "youtu.be/") !== false){
      $id = basename(parse_url($url, PHP_URL_PATH));
      return "https://www.youtube.com/embed/" . $id;
   }

   return $url;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($current_class['title']); ?></title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <style>
      *{
         margin:0;
         padding:0;
         box-sizing:border-box;
         font-family:"Segoe UI", Arial, sans-serif;
         text-decoration:none;
      }

      body{
         background:#edf2f7;
         color:#111827;
      }

      .lesson-page{
         min-height:100vh;
         display:grid;
         grid-template-columns:39rem 1fr;
      }

      .left-panel{
         background:#fff;
         height:100vh;
         overflow-y:auto;
         padding:1.5rem;
         border-right:1px solid #d1d5db;
      }

      .back-btn{
         display:block;
         background:#4f46e5;
         color:#fff;
         padding:1rem;
         border-radius:.45rem;
         text-align:center;
         font-size:1rem;
         margin-bottom:1.5rem;
         font-weight:600;
      }

      .back-btn:hover{
         background:#4338ca;
      }

      .class-block{
         padding:1.2rem .5rem;
      }

      .class-title{
         font-size:1.35rem;
         font-weight:800;
         color:#111827;
         margin-bottom:1rem;
         line-height:1.4;
      }

      .expire-line{
         display:flex;
         align-items:center;
         gap:.7rem;
         font-size:1rem;
         color:#111827;
         margin-bottom:1rem;
      }

      .expire-line i{
         color:#334155;
      }

      .expire-line strong{
         margin-left:.3rem;
      }

      .tree{
         margin-left:.75rem;
         padding-left:1.2rem;
         border-left:2px solid #c9cdd3;
      }

      .lesson-link{
         position:relative;
         display:flex;
         align-items:center;
         gap:.75rem;
         padding:.75rem 0;
         color:#2563eb;
         font-size:1.05rem;
         cursor:pointer;
         border:none;
         background:transparent;
         width:100%;
         text-align:left;
      }

      .lesson-link::before{
         content:"";
         position:absolute;
         left:-1.2rem;
         top:50%;
         width:1rem;
         height:2px;
         background:#c9cdd3;
      }

      .lesson-link:hover{
         color:#4f46e5;
      }

      .icon-pdf{
         color:#f97316;
      }

      .icon-video{
         color:#3b82f6;
      }

      .empty-lesson{
         position:relative;
         display:flex;
         align-items:center;
         gap:.75rem;
         padding:.75rem 0;
         color:#9ca3af;
         font-size:1.05rem;
      }

      .empty-lesson::before{
         content:"";
         position:absolute;
         left:-1.2rem;
         top:50%;
         width:1rem;
         height:2px;
         background:#c9cdd3;
      }

      .right-panel{
         min-height:100vh;
         background:#edf2f7;
         display:flex;
         flex-direction:column;
      }

      .preview-header{
         height:12rem;
         display:flex;
         align-items:center;
         justify-content:center;
         flex-direction:column;
         gap:.7rem;
         text-align:center;
      }

      .preview-header i{
         font-size:4rem;
         color:#4f46e5;
      }

      .preview-header p{
         color:#4b5563;
         font-size:1.15rem;
      }

      .preview-box{
         flex:1;
         display:flex;
         align-items:center;
         justify-content:center;
         padding:2rem;
      }

      .empty-preview{
         text-align:center;
         color:#6b7280;
      }

      .empty-preview i{
         font-size:4rem;
         margin-bottom:1rem;
         color:#6b7280;
      }

      .empty-preview p{
         font-size:1.15rem;
      }

      iframe{
         width:100%;
         height:calc(100vh - 15rem);
         border:none;
         border-radius:.6rem;
         background:#fff;
         box-shadow:0 1rem 2rem rgba(0,0,0,.08);
      }

      video{
         width:100%;
         max-height:calc(100vh - 15rem);
         border-radius:.6rem;
         background:#000;
         box-shadow:0 1rem 2rem rgba(0,0,0,.08);
      }

      .open-new{
         display:inline-block;
         margin-top:1rem;
         background:#4f46e5;
         color:#fff;
         padding:.8rem 1.5rem;
         border-radius:.4rem;
         font-weight:600;
      }

      .lesson-active{
         color:#4f46e5;
         font-weight:700;
      }

      @media(max-width:950px){
         .lesson-page{
            grid-template-columns:1fr;
         }

         .left-panel{
            height:auto;
            max-height:none;
            border-right:none;
            border-bottom:1px solid #d1d5db;
         }

         iframe{
            height:70vh;
         }
      }
   </style>
</head>
<body>

<div class="lesson-page">

   <div class="left-panel">

      <a href="teacher_course_page.php?id=<?php echo $teacher_id; ?>" class="back-btn">
         <i class="fa-solid fa-arrow-left"></i> Back To Classes
      </a>

      <div class="class-block">

         <h2 class="class-title">
            <?php echo htmlspecialchars($current_class['title']); ?>
         </h2>

         <div class="expire-line">
            <i class="fa-regular fa-clock"></i>
            <span>
               Expires On:
               <strong>
                  <?php
                  if(!empty($access['expires_at'])){
                     echo date("M jS Y, h:i A", strtotime($access['expires_at']));
                  }else{
                     echo "No expiry date";
                  }
                  ?>
               </strong>
            </span>
         </div>

         <div class="tree">

            <?php if($lessons && mysqli_num_rows($lessons) > 0){ ?>

               <?php while($lesson = mysqli_fetch_assoc($lessons)){ ?>

                  <?php
                     $lesson_title = getLessonTitle($lesson);
                     $lesson_type = getLessonType($lesson);
                     $lesson_url = getLessonUrl($lesson);
                     $embed_url = getEmbedUrl($lesson_url);
                     $is_video = isVideo($lesson_type, $lesson_url);
                  ?>

                  <button 
                     type="button"
                     class="lesson-link"
                     onclick="loadLesson('<?php echo htmlspecialchars($embed_url, ENT_QUOTES); ?>', '<?php echo $is_video ? 'video' : 'pdf'; ?>', this)"
                  >
                     <?php if($is_video){ ?>
                        <i class="fa-solid fa-video icon-video"></i>
                     <?php }else{ ?>
                        <i class="fa-solid fa-file-pdf icon-pdf"></i>
                     <?php } ?>

                     <span><?php echo htmlspecialchars($lesson_title); ?></span>
                  </button>

               <?php } ?>

            <?php }else{ ?>

               <div class="empty-lesson">
                  <i class="fa-solid fa-circle-info"></i>
                  <span>No lessons added yet</span>
               </div>

            <?php } ?>

         </div>

      </div>

   </div>

   <div class="right-panel">

      <div class="preview-header">
         <i class="fa-solid fa-graduation-cap"></i>
         <p>Select the lesson you want to access.</p>
      </div>

      <div class="preview-box" id="previewBox">
         <div class="empty-preview">
            <i class="fa-solid fa-book-open"></i>
            <p>No lesson selected yet.</p>
         </div>
      </div>

   </div>

</div>

<script>
function loadLesson(url, type, btn){
   const previewBox = document.getElementById('previewBox');

   document.querySelectorAll('.lesson-link').forEach(item => {
      item.classList.remove('lesson-active');
   });

   btn.classList.add('lesson-active');

   if(type === 'video'){
      if(url.includes('youtube.com/embed')){
         previewBox.innerHTML = `
            <iframe src="${url}" allowfullscreen></iframe>
         `;
      }else{
         previewBox.innerHTML = `
            <video controls autoplay>
               <source src="${url}">
               Your browser does not support the video tag.
            </video>
         `;
      }
   }else{
      previewBox.innerHTML = `
         <div style="width:100%;">
            <iframe src="${url}"></iframe>
            <a href="${url}" target="_blank" class="open-new">
               Open PDF / File In New Tab
            </a>
         </div>
      `;
   }
}
</script>

</body>
</html>