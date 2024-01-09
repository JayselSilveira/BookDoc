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

$id = $_GET['id'];
$sql3 = "SELECT * FROM users WHERE user_id = $id;";
$result3 = $con->query($sql3);
$rows3 = mysqli_fetch_array($result3,  MYSQLI_ASSOC);
$patient_email_id = $rows3['email_id'];

$sql6 = "SELECT * FROM users WHERE email_id = '$patient_email_id';";
$result6 = $con->query($sql6);
$rows6 = mysqli_fetch_array($result6,  MYSQLI_ASSOC);
$patient_id = $rows6['user_id'];

$sql2 = "SELECT * FROM appointments AS a 
LEFT JOIN users AS u ON a.doctor_id = u.user_id 
WHERE patient_id = '$patient_id' 
ORDER BY status DESC, date, LENGTH(time);";

// $sql2 = "SELECT * FROM appointments WHERE patient_email_id = '$patient_email_id' ORDER BY status DESC;";
$result2 = mysqli_query($con, $sql2);
$count = mysqli_num_rows($result2);

// $sql4 = "SELECT * FROM appointments WHERE patient_email_id = '$patient_email_id';";
// $result4 = $con->query($sql4);
// $rows4 = mysqli_fetch_array($result4,  MYSQLI_ASSOC);
// $doctor_email_id = $rows4['doctor_email_id'];

// $sql5 = "SELECT * FROM users WHERE email_id = '$doctor_email_id';";
// $result5 = $con->query($sql5);
// $rows5 = mysqli_fetch_array($result5,  MYSQLI_ASSOC);
// $doctor_name = $rows5['username'];

      
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
    // else {
    //     header("location: login.php");
    // }

$sql = "SELECT * FROM users;";
$result = $con->query($sql);

// Close the database connection
$con->close();
?>

<?php
    session_start();
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


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="patientHomepage.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Home Page</title>
    </head>

    <body background="images/medical.jpg" style="background-size:cover">
        <header>
            <a href="#" class="logo"><span>BookDoc.</span></a>
            <form method="post"><a>
            <button type="submit" class="logoutbtn" id="logout" name="logout" value="Logout"
            style="width: 100%; font-weight: 600; color: #33a396; background: #fff; padding: 2px 20px; margin-top:2%;">
                <span class="hoverText">Logout</span>
                <i class="fa fa-sign-out" style='font-size:24px'></i>
            </button>
            </a></form>
        </header>

      <div id="patient" class="tabcontent" style="margin-left:4%; margin-top:3%;">
        <section class="appointments">

        <div id="grid" style="margin-left:18%; width:100%;">

          <?php   
              if($existsAppointment === true){
                while($rows2 = mysqli_fetch_array($result2,  MYSQLI_ASSOC)){
          ?>

            <?php 
              if($rows2['status'] === 'completed'){
            ?>
            <div class="card" style="display:inline-block; color:#000; background-color:#D3D3D3; font-size:15px; margin-top:7%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:25%; height:37%; text-align:center;">
            <div class="container" style="margin-bottom:5%; margin-top:7%;">
              <div style="font-size:25px;"><strong>Dr. <?php echo $rows2['username'];?></strong></div>
              <div style="font-size:22px; padding-right:2%; padding-left:2%;"><strong><?php echo$rows2['email_id'];?></strong></div>
              <div style="font-size:20px;">Specialization:<br> <?php echo $rows2['specialization'];?></div><br>
              <div style="font-size:20px;"><strong>Date: <?php echo $rows2['date'];?></strong></div>
              <div style="font-size:20px;"><strong>Time: <?php echo $rows2['time'];?></strong></div><br>
              <div style="font-size:20px;">Status: <?php echo $rows2['status'];?></div>
            </div>
          </div>
          
          <?php
              } if($rows2['status'] === 'upcoming') {
            ?>
            <div class="card" style="display:inline-block; color:#000; background-color:#ddffa6; font-size:15px; margin-top:7%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:25%; height:37%; text-align:center;">
            <div class="container" style="margin-bottom:5%; margin-top:7%;">
              <div style="font-size:25px;"><strong>Dr. <?php echo $rows2['username'];?></strong></div>
              <div style="font-size:22px; padding-left:2%;"><strong><?php echo$rows2['email_id'];?></strong></div>
              <div style="font-size:20px;">Specialization:<br> <?php echo $rows2['specialization'];?></div><br>
              <div style="font-size:20px;"><strong>Date: <?php echo $rows2['date'];?></strong></div>
              <div style="font-size:20px;"><strong>Time: <?php echo $rows2['time'];?></strong></div><br>
              <div style="font-size:20px;">Status: <?php echo $rows2['status'];?></div>
            </div>
          </div>

          <?php
            }
          ?>

          <?php
                }
            } else{ 
          ?>
                <div style="margin-left:10%;">
                  <h1 text-align="center">No_Appointments</h1>
                </div>
          <?php 
            }
            
          ?>

        </div>

        </section>
      </div>

    </body>
</html>