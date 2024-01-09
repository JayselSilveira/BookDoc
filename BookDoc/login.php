<?php

// Set connection variables
$server = "localhost";
$username = "root";
$password = "";
$dbname = "bookdoc";

// Create a database connection
$con = mysqli_connect($server, $username, $password, $dbname);

// Check for connection success
if(!$con){
    die("Connection to this database failed due to" . mysqli_connect_error());
}
      
session_start();
   
if(isset($_POST['Login'])) {
// email_id and password sent from form
   $email_id = $_POST['email_id'];
   $password = $_POST['password'];

   $email_id = stripcslashes($email_id);
   $password = stripcslashes($password);
   $email_id = mysqli_real_escape_string($con, $email_id);
   $password = mysqli_real_escape_string($con, $password); 
   $password = md5($password);
      
   $sql = "SELECT * FROM users WHERE email_id = '$email_id' and password = '$password'";
   $result = mysqli_query($con, $sql);
   $row = mysqli_fetch_array($result,  MYSQLI_ASSOC);
     
   $count = mysqli_num_rows($result);
      
   // If result matched $email_id and $password, table row must be 1 row
   if($count == 1) {
      //$_SESSION['email_id'] = $row['email_id'];
      //$id = $row['id'];
      $username = $row['username'];
      $user = $row['user'];

      
      if($user == 1){
         if($row['view'] == 'list'){
            header("location: adminDashboardPatients.php");
         }
         if($row['view'] == 'grid'){
            header("location: adminDashboardPatientsG.php");
         }
      }
      if($user == 2){
         header("location: patientHomepage.php");
      }
      if($user == 3){
         header("location: doctorHomepage.php");
      }

      $_SESSION['user'] = $user;
      $_SESSION['username'] = $username;
      $_SESSION['email_id'] = $email_id;
      //$_SESSION['active'] = true;
      // header("location: home.php");
   } else {
      $error = "Your Email-ID and Password combination is invalid.";
      echo '<script type="text/javascript">
         window.onload = function () { alert("Your Email-ID and Password combination is invalid."); }
         </script>';        
   }
}

// Close the database connection
$con->close();
?>


<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="loginRegister.css">
      <script type="text/javascript" src="jquery.min.js"></script>
      <title>Login Form</title>
   </head>
   
   <body>
      <header>
         <a href="#" class="logo">Welcome to <strong><span>BookDoc.</span></strong></a>
      </header>

      <div class="wrapper" style="height: 50%; margin-top: 30px;">
         <div class="title-text">
            <div class="title login">
               Login
            </div>
         </div>
         <div class="form-container">
            <div class="form-inner">
               <form action="" method="post" class="login">
                  <div class="field">
                     <p>Email ID:
                        <input type="text" name="email_id" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Password:
                        <input type="password" name="password" required>
                     </p>
                  </div>
                  <div class="pass-link">
                     <a href="resetPassword.php";">Reset password?</a>
                  </div>
                  <!-- <script type="text/javascript">
                     function RunningPHP() {
                        $.get("forgotPassword.php");
                        return false;
                     }
                  </script> -->
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="LOGIN" name="Login">
                  </div>
                  <div class="submit-link">
                     Don't have an account? <a href="register.php">Signup now</a>
                  </div>
               </form>

            </div>
         </div>
      </div>
   </body>
</html>