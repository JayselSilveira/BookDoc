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

if(isset($_POST['Reset'])){
   $email_id = $_POST['email_id'];
   $current_password = $_POST['current_password'];
   $curr_password_encrypted = md5($current_password);
   $new_password = $_POST['new_password'];
   $confirm_password = $_POST['confirm_password'];
   
   $sql1 = "SELECT * FROM users WHERE email_id = '$email_id' AND password = '$curr_password_encrypted';";
   $result1 = mysqli_query($con, $sql1);
   $row1 = mysqli_fetch_array($result1,  MYSQLI_ASSOC);
   $count1 = mysqli_num_rows($result1);
      
   if($count1 == 1) {
   if($new_password == $confirm_password){
        $password = md5($new_password);
        $sql = "UPDATE users SET password = '$password' WHERE email_id = '$email_id';";
    
        if($con->query($sql) == true){
            echo '<script type="text/javascript">
            window.onload = function () { alert("New Password set successfully."); }
            </script>'; 
            header("location: login.php");
        } else {
            echo "ERROR: $sql <br> $con->error";
        }
   }
   else{
        $error = "New Password and Confirm Password values don't match.";
        echo '<script type="text/javascript">
            window.onload = function () { alert("New Password and Confirm Password values do not match."); }
            </script>'; 
      //   header("location: resetPassword.php");
   }
   }else{
      $error = "Your Email-ID and Current Password combination is invalid.";
      echo '<script type="text/javascript">
          window.onload = function () { alert("Your Email-ID and Current Password combination is invalid."); }
          </script>'; 
      // header("location: resetPassword.php");
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
      <title>Reset Password Form</title>
   </head>
   
   <body>
      <header>
         <a href="#" class="logo">Welcome to <strong><span>BookDoc.</span></strong></a>
      </header>

      <div class="wrapper" style="height:60%;">
         <div class="title-text">
            <div class="title signup">
               Reset Password
            </div>
         </div>
         <div class="form-container">
            <div class="form-inner">
               <form action="resetPassword.php" method="post" class="signup" enctype="multipart/form-data">
                  <div class="field">
                     <p>Email-ID:
                        <input type="text" name="email_id" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Current Password:
                        <input type="password" name="current_password" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>New Password:
                        <input type="password" name="new_password" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Confirm Password:
                        <input type="password" name="confirm_password" required>
                     </p>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="RESET" name="Reset">
                  </div>
                  <div class="submit-link">
                     Don't want to reset? <a href="login.php">Login now</a>
                  </div>
               </form>
            </div>
         </div>
      </div>      
   </body>
</html>