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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


function send_password_reset($username, $email_id, $token){
   $mail = new PHPMailer(true);
   $mail->isSMTP();
   $mail->SMTPAuth = true;

   $mail->Host = "smtp.gmail.com";
   $mail->Username = "jaysel1234567@gmail.com";
   $mail->Password = "";

   $mail->SMTPSecure = "tls";
   $mail->Port = 587;

   $mail->setFrom("BookDoc", $username);
   $mail->addAddress($email_id);

   $mail->isHTML(true);
   $mail->Subject = "Reset Password Notification";

   $email_template = "
   <h2>Hi</h2>
   <h3>You are receiving this email because we received a password reset request for your account.</h3>
   <br><br>
   <a href='http://localhost/BookDoc/resetPassword.php?token=$token&email=$email_id'>Click to Reset Password</a>
   ";

   $mail->Body = $email_template;
   $mail->send();
}


if(isset($_POST['Send'])){
	$email = mysqli_real_escape_string($con, $_POST['email_id']);
   $token = md5(rand());

	$sql = "SELECT * FROM users WHERE email_id = '$email'";
	$result = mysqli_query($con, $sql);
	$count = mysqli_num_rows($result);
	
   if(count > 0){
      $row = mysqli_fetch_array($result);
      $email_id = $row['email_id'];

      $update_token = "UPDATE users SET password = '$token' WHERE email_id = '$email_id';";
      $update_token_sql = mysqli_query($con, $update_token);

      if($update_token_sql){
         send_password_reset($username, $email_id, $token);
         $error = "We e-mailed you a password reset link.";
         echo '<script type="text/javascript">
            window.onload = function () { alert("We e-mailed you a password reset link."); }
            </script>'; 
         header("Location: login.php");
      }
      else{
         $error = "Something went wrong.";
         echo '<script type="text/javascript">
            window.onload = function () { alert("Something went wrong."); }
            </script>'; 
         header("Location: forgotPassword.php");
      }
   }
   else{
      $error = "No Email Found.";
      echo '<script type="text/javascript">
         window.onload = function () { alert("No Email Found."); }
         </script>'; 
      header("Location: forgotPassword.php");
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
      <title>Login Form</title>
   </head>
   
   <body>
      <header>
         <a href="#" class="logo">Welcome to <strong><span>BookDoc.</span></strong></a>
      </header>

      <div class="wrapper" style="height: 40%; margin-top: 30px;">
         <div class="title-text">
            <div class="title login">
               Forgot Password
            </div>
         </div>
         <div class="form-container">
            <div class="form-inner">
               <form action="forgotPassword.php" method="post" class="forgot">
                  <div class="field">
                     <p>Email ID:
                        <input type="text" name="email_id" required>
                     </p>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="SEND" name="Send">
                  </div>
                  <div class="submit-link">
                     Remember your password? <a href="login.php">Login now</a>
                  </div>
               </form>

            </div>
         </div>
      </div>
   </body>
</html>