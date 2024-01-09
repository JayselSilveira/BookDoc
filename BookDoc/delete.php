<?php
  $toSearch = false;
  $toSort = false;

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

//   if(isset($_POST['delete'])){
    $temp = $_GET['id'];
    // echo '<script type="text/javascript"> ';  
    // echo ' function openulr(newurl) {';  
    // echo '  if (confirm("Are you sure you want to delete this user?")) {';
      $sql3 = "DELETE FROM users WHERE id = '$temp';";
      //$result1 = mysqli_query($con, $sql1);
      if(($con->query($sql3) == true)) {
        //echo '<script type ="text/JavaScript">';
        //echo 'alert("Post deleted successfully!")';
        //echo '</script>';
        header("location: adminDashboard.php");
      } else {
        echo "ERROR: $sql3 <br> $con->error";
      }
      echo 'alert("Successfully deleted!")';  
      // echo '    document.location = newurl;';  
    // echo '  }';  
    // echo '}';  
    // echo '</script>';   
//   }

  // Close the database connection
  $con->close();
?>

<?php
  session_start();
  if(!isset($_SESSION['email_id'])){
    header("location: login.php");
  }
?>