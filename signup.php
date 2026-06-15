<?php
  require "header.php";
?>

<main>
   <h1>Signup</h1>

   <?php
   if (isset($_GET['error'])){
      if ($_GET['error']=="emptyfields"){
         echo '<div class="alert alert-danger" role="alert">Fill in all fields !</div>';
      }else if ($_GET['error']=="invalidmailuid"){
         echo '<div class="alert alert-danger" role="alert">Invalid username and e-mail !</div>';
      }else if ($_GET['error']=="invaliduid"){
         echo '<div class="alert alert-danger" role="alert">Invalid username !</div>';
      }else if ($_GET['error']=="invalidmail"){
         echo '<div class="alert alert-danger" role="alert">Invalid e-mail !</div>';
      }else if ($_GET['error']=="passwordcheck"){
         echo '<div class="alert alert-danger" role="alert">Your passwords do not match !</div>';
      }else if ($_GET['error']=="usertaken"){
         echo '<div class="alert alert-danger" role="alert">Username is already taken !</div>';
      }else if ($_GET['error']=="invalidgender"){
         echo '<div class="alert alert-danger" role="alert">Please select valid gender !</div>';
      }
   }elseif (isset($_GET['signup'])) {
      if ($_GET['signup']=="success"){
         echo '<div class="alert alert-success" role="alert">Signup Successful !</div>';
      }
   }
   ?>

   <form action="includes/signup1.inc.php" method="post">
      <table>

         <tr>
            <th style="text-align: left;">Username</th>
            <td>
               <input type="text" name="uid" placeholder="Username" required
               value="<?php 
                  if (isset($_GET['uid'])) {
                     echo htmlspecialchars($_GET['uid']);
                  }
               ?>">
            </td>
         </tr>

         <tr>
            <th style="text-align: left;">E-mail</th>
            <td>
               <input type="email" name="mail" placeholder="E-mail" required
               value="<?php 
                  if (isset($_GET['mail'])) {
                     echo htmlspecialchars($_GET['mail']);
                  }
               ?>">
            </td>
         </tr>

         <tr>
            <th style="text-align: left;">Gender</th>
            <td>
               <select name="gender" required>
                  <option value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
               </select>
            </td>
         </tr>

         <tr>
            <th style="text-align: left;">Birthday</th>
            <td>
               <input type="date" name="birthday" required>
            </td>
         </tr>

         <tr>
            <th style="text-align: left;">ID Number</th>
            <td>
               <input type="text" name="id_number" placeholder="ID Number" required>
            </td>
         </tr>

         <tr>
            <th style="text-align: left;">Password</th>
            <td>
               <input type="password" name="pwd" placeholder="Password" required>
            </td>
         </tr>

         <tr>
            <th style="text-align: left;">Repeat Password</th>
            <td>
               <input type="password" name="pwd-repeat" placeholder="Repeat Password" required>
            </td>
         </tr>

      </table>

      <br>

      <button style="border-radius: 12px; padding:11px 16px; font-weight:bolder; margin-left:250px;" type="submit" name="signup-submit">
         Signup
      </button>
   </form>
</main>

<style>
main form{
   max-height: 450px;
   overflow-y: auto;
   padding-right: 10px;
}

main form table{
   width: 100%;
}

main form input,
main form select{
   width: 260px;
   padding: 10px 14px;
   margin: 6px 0;
   border-radius: 10px;
   border: 1px solid #ccc;
   outline: none;
   font-size: 15px;
}

main form select{
   cursor: pointer;
}

main form::-webkit-scrollbar{
   width: 6px;
}

main form::-webkit-scrollbar-thumb{
   background: #7b4cff;
   border-radius: 10px;
}



/* ===== SIGNUP PAGE STYLE ===== */

main{
   min-height: 100vh;
   display: flex;
   flex-direction: column;
   align-items: center;
   justify-content: center;
   background: linear-gradient(135deg, #12002f, #4b1d95, #7c3aed);
   padding: 40px 15px;
   font-family: Arial, sans-serif;
}

main h1{
   color: #fff;
   font-size: 42px;
   letter-spacing: 2px;
   margin-bottom: 25px;
   text-transform: uppercase;
}

main form{
   width: 520px;
   max-width: 100%;
   max-height: 560px;
   overflow-y: auto;
   background: rgba(255,255,255,0.15);
   backdrop-filter: blur(18px);
   -webkit-backdrop-filter: blur(18px);
   border: 1px solid rgba(255,255,255,0.25);
   border-radius: 25px;
   padding: 35px 35px 25px;
   box-shadow: 0 25px 60px rgba(0,0,0,0.35);
}

main form table{
   width: 100%;
   border-collapse: collapse;
}

main form tr{
   display: flex;
   flex-direction: column;
   margin-bottom: 14px;
}

main form th{
   color: #fff;
   font-size: 15px;
   font-weight: 600;
   margin-bottom: 6px;
   letter-spacing: .5px;
}

main form td{
   width: 100%;
}

main form input,
main form select{
   width: 100%;
   padding: 13px 16px;
   border-radius: 14px;
   border: 1px solid rgba(255,255,255,0.35);
   outline: none;
   background: rgba(255,255,255,0.92);
   color: #222;
   font-size: 15px;
   box-sizing: border-box;
}

main form input:focus,
main form select:focus{
   border-color: #fff;
   box-shadow: 0 0 0 4px rgba(255,255,255,0.18);
}

main form select{
   cursor: pointer;
}

main form button{
   width: 100%;
   margin-left: 0 !important;
   margin-top: 10px;
   padding: 14px 20px !important;
   border: none;
   border-radius: 30px !important;
   background: linear-gradient(135deg, #ffffff, #d8c8ff);
   color: #3b0764;
   font-size: 16px;
   font-weight: 800;
   letter-spacing: 2px;
   cursor: pointer;
   transition: .3s ease;
}

main form button:hover{
   transform: translateY(-3px);
   box-shadow: 0 12px 30px rgba(0,0,0,0.25);
}

/* alert messages */
.alert{
   width: 520px;
   max-width: 100%;
   padding: 14px 18px;
   border-radius: 14px;
   margin-bottom: 15px;
   font-size: 15px;
   font-weight: 600;
   text-align: center;
}

.alert-danger{
   background: rgba(255, 72, 72, 0.18);
   color: #fff;
   border: 1px solid rgba(255,255,255,0.25);
}

.alert-success{
   background: rgba(0, 255, 136, 0.18);
   color: #fff;
   border: 1px solid rgba(255,255,255,0.25);
}

/* scrollbar */
main form::-webkit-scrollbar{
   width: 7px;
}

main form::-webkit-scrollbar-track{
   background: transparent;
}

main form::-webkit-scrollbar-thumb{
   background: rgba(255,255,255,0.55);
   border-radius: 10px;
}

/* mobile responsive */
@media(max-width: 600px){
   main{
      padding: 25px 12px;
   }

   main h1{
      font-size: 32px;
   }

   main form{
      padding: 28px 22px 22px;
      max-height: 520px;
   }

   main form input,
   main form select{
      padding: 12px 14px;
      font-size: 14px;
   }
}









</style>

<?php
  require "footer.php";
?>