<?php
$existsAppointment = false;

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
$doctor_email_id = $_SESSION['email_id'];

$sql6 = "SELECT * FROM users WHERE email_id = '$doctor_email_id';";
$result6 = $con->query($sql6);
$rows6 = mysqli_fetch_array($result6,  MYSQLI_ASSOC);
$doctor_id = $rows6['user_id'];

$sql2 = "SELECT * FROM appointments AS a 
LEFT JOIN users AS u ON a.patient_id = u.user_id 
WHERE doctor_id = '$doctor_id' 
ORDER BY status DESC;";

// $sql2 = "SELECT * FROM appointments where doctor_email_id = '$doctor_email_id' ORDER BY status DESC;";
$result2 = mysqli_query($con, $sql2);
$count = mysqli_num_rows($result2);
      
   if($count > 0) {
      $existsAppointment = true;
      // header("location: home.php");
   } else {
      $existsAppointment = false;
      $error = "You have not booked any appointments till date.";
      // echo '<script type="text/javascript">
      //    window.onload = function () { alert("You have not booked any appointments till date."); }
      //    </script>';        
   }
    

// if(isset($_POST['completed'])){
    $temp = $_GET['id'];

    $sql3 = "SELECT * FROM users;";
    $result3 = $con->query($sql3);
    while($rows3 = mysqli_fetch_array($result3)){
        if($rows3['user_id']==$temp){
          $sql1 = "UPDATE appointments SET status = 'completed' WHERE user_id = '$temp';";
          if($con->query($sql1) == true) {
              echo '<script type ="text/JavaScript">';
              echo 'alert("Appointment done successfully!")';
              echo '</script>';
              header("location: doctorHomepage.php");
          } else {
              echo "ERROR: $sql1 <br> $con->error";
          }
          // echo 'alert("Successfully updated!")';
        }
    }  
// }

$sql = "SELECT * FROM users;";
$result = $con->query($sql);

// Close the database connection
$con->close();
?>

<?php
  if(!isset($_SESSION['email_id'])){
    header("location: login.php");
  }
?>

<?php
  if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location: login.php");
  }
?>