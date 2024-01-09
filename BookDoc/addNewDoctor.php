<?php
$insert = false;

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

$sql3 = "SELECT * FROM location;";
$result3 = $con->query($sql3);

if(isset($_POST['Signup'])){
   $first_name = trim($_POST['first_name']);
   $last_name = trim($_POST['last_name']);
   $user_name = $first_name." ".$last_name;
   $specialization = $_POST['specialization'];
   $birth_date = date('Y-m-d', strtotime($_POST['birth_date']));
   $location = $_POST['location'];
   $contact = trim($_POST['contact']);
   $email_id = trim($_POST['email_id']);
   $password = trim($_POST['password']);
   $password = md5($password);

   if(!empty($first_name) and !empty($last_name)){
      if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format.";
      echo '<script type="text/javascript">
         window.onload = function () { alert("Invalid email format."); }
         </script>';
   } 
   else
   {

   $sql4 = "SELECT * FROM usertype;";
   $result4 = mysqli_query($con, $sql4);
   $row4 = mysqli_fetch_array($result4,  MYSQLI_ASSOC);
   if(row4['user'] == 'doctor'){
      $user = row4['id'];
   }

   $sql2 = "SELECT * FROM users WHERE email_id = '$email_id';";
   $result2 = mysqli_query($con, $sql2);
   $row2 = mysqli_fetch_array($result2,  MYSQLI_ASSOC);
     
   $count2 = mysqli_num_rows($result2);
		
   if($count2 == 1) {
      $error = "User with the entered email-id has already been registered.";
      echo '<script type="text/javascript">
         window.onload = function () { alert("User with the entered email-id has already been registered."); }
         </script>';  
   } 
   else {
      $image = $_FILES["image"]["name"];
      $tempname = $_FILES["image"]["tmp_name"];    
      $folder = "uploadedImages/".$image;
      
      $sql = "INSERT INTO users(user, username, specialization, birth_date, location, image, contact,  email_id, password) VALUES(3, '$user_name', '$specialization', '$birth_date', '$location', '$image', '$contact', '$email_id', '$password');";
      
      // Execute the query
      if($con->query($sql) == true){
         // Flag for successful insertion
         $insert = true;
         header("location: adminDashboard.php");
      } else {
         echo "ERROR: $sql <br> $con->error";
      }

      // Now let's move the uploaded image into the folder: image
      if (move_uploaded_file($tempname, $folder))  {
         $msg = "Image uploaded successfully";
      }else{
         $msg = "Failed to upload image";
      }
   }
      
}
}
else{
   $spaceErr = "Blank spaces in username.";
   echo '<script type="text/javascript">
      window.onload = function () { alert("Blank spaces in username."); }
      </script>';
}
}

// Close the database connection
$con->close();
?>

<?php
  session_start();
  if(!isset($_SESSION['email_id'])){
    header("location: login.php");
  }
?>


<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="loginRegister.css">
      <title>Registration Form</title>
   </head>
   
   <body>
      <header>
         <a href="#" class="logo">Welcome to <strong><span>BookDoc.</span></strong></a>
      </header>

      <div class="wrapper" style="height: 100%; margin-top: 200px">
         <div class="title-text">
            <div class="title signup">
               Doctor Signup
            </div>
         </div>
         <div class="form-container">
            <div class="form-inner">
               <form action="#" method="post" class="signup" enctype="multipart/form-data">
                  <div class="field">
                     <p>First Name:
                        <input type="text" name="first_name" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Last Name:
                        <input type="text" name="last_name" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Specialization:
                        <input type="text" name="specialization" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Date of Birth:
                        <input type="date" name="birth_date" required>
                     </p>
                  </div>
                  <div class="field">
                    <label for="location">Location:</label>
                    <select id="location" name="location" size="1" style="height: 30px; width: 100%; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                        <option value="" disabled selected>Select a Taluka</option>
                        
                        <?php
                        while ($row3 = mysqli_fetch_array($result3,  MYSQLI_ASSOC)) 
                        {
                           echo '<option value=" '.$row3['taluka'].' "> '.$row3['taluka'].' </option>';
                        }
                        ?>
                    </select><br><br>
                  </div>
                  <div class="field">
                     <p>Image: 
                        <input type="file" name="image" id="image" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Contact Number:
                       <input type="number" name="contact" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Email ID:
                       <input type="text" name="email_id" required>
                     </p>
                  </div>
                  <div class="field">
                     <p>Password: 
                        <input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,15}" title="Must contain at least one number, one uppercase and lowercase letter and one special symbol, and at least 8 to 15 characters" required>
                     </p>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="SIGNUP" name="Signup">
                  </div>
               </form>
            </div>
         </div>
      </div>      
   </body>
</html>