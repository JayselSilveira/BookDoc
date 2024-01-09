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
$patient_email_id = $_SESSION['email_id'];

$sql6 = "SELECT * FROM users WHERE email_id = '$patient_email_id';";
$result6 = $con->query($sql6);
$rows6 = mysqli_fetch_array($result6,  MYSQLI_ASSOC);
$patient_id = $rows6['user_id'];

$sql2 = "SELECT * FROM appointments AS a 
LEFT JOIN users AS u ON a.doctor_id = u.user_id 
WHERE patient_id = '$patient_id' 
ORDER BY status DESC, date, LENGTH(time);";

$result2 = mysqli_query($con, $sql2);
$count = mysqli_num_rows($result2);

// $sql4 = "SELECT * FROM appointments WHERE patient_id = '$patient_id';";
// $result4 = $con->query($sql4);
// $rows4 = mysqli_fetch_array($result4,  MYSQLI_ASSOC);
// $doctor_id = $rows4['doctor_id'];

// $sql7 = "SELECT * FROM users WHERE user_id = '$doctor_id';";
// $result7 = $con->query($sql7);
// $rows7 = mysqli_fetch_array($result7,  MYSQLI_ASSOC);
// $doctor_email_id = $rows7['email_id'];

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

if(isset($_POST['delUser'])){
    $temp = $_GET['id'];
      $sql3 = "DELETE FROM users WHERE user_id = '$temp';";
      $sql8 = "DELETE FROM appointments WHERE patient_id = '$temp';";
      if(($con->query($sql3) == true) and ($con->query($sql8) == true)) {
        echo '<script type ="text/JavaScript">';
        echo 'alert("User deleted successfully!")';
        echo '</script>';
        header("location: login.php");
      } else {
        echo "ERROR: $sql3 <br> $con->error";
        echo "ERROR: $sql8 <br> $con->error";
      }
      echo 'alert("Successfully deleted!")';  
}
    

if(isset($_POST['delete'])){
    $temp = $_GET['id'];
    // $temp = stripcslashes($temp);
    // $temp = mysqli_real_escape_string($con, $temp);
    $sql1 = "DELETE FROM appointments WHERE appointment_id = '$temp';";
    if($con->query($sql1) == true) {
        echo '<script type ="text/JavaScript">';
        echo 'alert("User deleted successfully!")';
        echo '</script>';
        header("location: login.php");
    } else {
        echo "ERROR: $sql1 <br> $con->error";
    }
    echo 'alert("Successfully deleted!")'; 
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

    <body style="background-color:#d5ecf5;">
        <header>
            <a href="#" class="logo"><span>BookDoc.</span></a>

              <button style="width: 7%; font-size: 24px; font-weight: 600; color: #000; background: #90E4C1; padding: 2px 20px; margin-left: 60%;" class="logoutbtn" onclick="addNewAppointment()">
                <span class="hoverText" style="font-size: 13px;">Book an Appointment</span>
                <i class="fa fa-calendar-plus-o"></i>
              </button>
              <script>
                function addNewAppointment() {
                  window.location.href="bookAppointment.php";
                }
              </script>

            <form method="post"><a>
            <button type="submit" class="logoutbtn" id="logout" name="logout" value="Logout"
            style="width: 100%; font-weight: 600; color: #33a396; background: #fff; padding: 2px 20px; margin-top:2%;">
                <span class="hoverText">Logout</span>
                <i class="fa fa-sign-out" style='font-size:24px'></i>
            </button>
            </a></form>
        </header>

        <div class="sidebar" style="width:18%; margin-top:5%">
            <?php 
                while($rows = mysqli_fetch_array($result)){
                    if($rows['email_id']==$_SESSION['email_id']){
            ?>

            <div class="profile">
                <img src="uploadedImages/<?php echo $rows['image'];?>" alt="profile_picture">
                <h3 style="font-size:150%; margin-top:15%;"><?php echo $rows['username']?></h3>
                <h3 style="font-size:100%;"><?php echo $rows['email_id']?></h3>
                <!-- <h3><?php echo $rows['birth_date']?></h3>
                <h3><?php echo $rows['location']?></h3>
                <h3><?php echo $rows['contact']?></h3> -->
            </div>            

            <table style=" width:50%; margin-left:25%; margin-top:20%;">

            <tr style="text-align:center; color:teal;" class="displayData">

            <td>
              <form action="edit.php?id=<?php echo $rows['user_id']?>" id="edit" method="post">
                <a href="edit.php?id=<?php echo $rows['user_id']?>">
                    <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                </a>
              </form>
            </td>
            <td>
            <form action="patientHomepage.php?id=<?php echo $rows['user_id']?>" id="delUser" method="post">
                <a href="patientHomepage.php?id=<?php echo $rows['user_id']?>">
                    <input type="submit" name="delUser" value="&#10006;" id="<?php echo $rows['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                </a>  
              </form>
            </td>
          </tr>
            </table>

            <!-- <form action="edit.php?id=<?php echo $rows['id']?>" method="post"><a href="edit.php?id=<?php echo $rows['id']?>"><input type="submit" value="Edit" name="edit" class="btn" id="edit" style="background-color:#fabe28;"></a></form>

            <form action="login.php?id=<?php echo $rows['id']?>" method="post">
                <a href="login.php?id=<?php echo $rows['id']?>">
                    <input type="submit" value="Delete" name="delete" class="btn" id="delete" style="background-color:#f51637;">
                </a>
            </form> -->
            <?php
                        }
                    }
            ?>
        </div>

      <div style="width:100%; margin-left:18%; background-size: cover;">
      <div id="patient" class="tabcontent" style="margin-left:18%; margin-top:5%; width:75%;">
        <section class="appointments">

        <div id="grid"">

          <?php   
              if($existsAppointment === true){
                while($rows2 = mysqli_fetch_array($result2,  MYSQLI_ASSOC)){
          ?>

            <?php 
              if($rows2['status'] === 'completed'){
            ?>
            <div class="card" style="display:inline-block; color:#000; background-color:#D3D3D3; font-size:15px; margin-top:5%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:35%; height:37%; text-align:center;">
            <div class="container" style="margin-bottom:5%; margin-top:13%;">
              <div style="font-size:25px;"><strong>Dr. <?php echo $rows2['username'];?></strong></div>
              <div style="font-size:22px;"><strong><?php echo $rows2['email_id'];?></strong></div>
              <div style="font-size:20px;">Specialization:<br> <?php echo $rows2['specialization'];?></div><br>
              <div style="font-size:20px;"><strong>Date: <?php echo $rows2['date'];?></strong></div>
              <div style="font-size:20px;"><strong>Time: <?php echo $rows2['time'];?></strong></div><br>
              <div style="font-size:20px;">Status: <?php echo $rows2['status'];?></div>
            </div>
          </div>
          
          <?php
              } if($rows2['status'] === 'upcoming') {
            ?>
            <div class="card" style="display:inline-block; color:#000; background-color:#ddffa6; font-size:15px; margin-top:7%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:35%; height:37%; text-align:center;">
            <table>
              <tr>
                <!-- <td width="30%;"></td>
                <td><img src="uploadedImages/<?php echo $rows2['image'];?>" width="140" height="140"></td> -->

                <td rowspan="2">
                  <form action="adminDashboard.php?id=<?php echo $rows2['appointment_id'];?>" id="delete" method="post">
                    <a href="adminDashboard.php?id=<?php echo $rows2['appointment_id'];?>">
                      <input type="submit" class="btn" name="delete" value="Cancel" id="<?php echo $rows2['appointment_id'];?>" 
                      style="font-size:15px; width:100%; margin-top:10%; margin-left:235%; padding:2px 4px; height:30%; border: black solid; background-color:#f51637;">
                    </a>  
                  </form>
                </td>
              </tr>
            </table>
            
            <div class="container" style="margin-bottom:5%;">
              <div style="font-size:25px;"><strong>Dr. <?php echo $rows2['username'];?></strong></div>
              <div style="font-size:22px;"><strong><?php echo $rows2['email_id'];?></strong></div>
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
                <div style="margin-left:25%; margin-top:10%;">
                  <h1 text-align="center">No_Appointments</h1>
                </div>
          <?php 
              
            }
            
          ?>
                <!-- <div style="margin-left:20%; margin-top:10%;">
                  <h1 text-align="center">No Appointments</h1>
                </div> -->
        </div>

        </section>
      </div>
      </div>

    </body>
</html>