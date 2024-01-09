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

$sql = "SELECT * FROM users where user = 3;";
$result = $con->query($sql);

$sql1 = "SELECT * FROM appointments;";
$result1 = $con->query($sql1);

session_start();
$email = $_SESSION['email_id'];

$sql3 = "SELECT * FROM appointment_time AS atime
INNER JOIN appointments AS a ON a.time = atime.time 
INNER JOIN users AS u ON a.doctor_id = u.user_id
WHERE u.username = 'James Sequeira';";

// $sql2 = "SELECT * FROM appointments where doctor_email_id = '$doctor_email_id' ORDER BY status DESC;";
$result3 = mysqli_query($con, $sql3);
$count3 = mysqli_num_rows($result3);

if($count3 > 0){
   echo '<script type="text/javascript">
         window.onload = function () { alert("Lets book an Appointment!!"); }
         </script>'; 
}

if(isset($_POST['Book'])){
   $sd = split ("\-", $_POST['doctor']); 
   $specialization = $sd[0];
   $doctor_name = $sd[1];
   $doctor_name = substr($doctor_name, 4);
   $doctor_name = ltrim($doctor_name," ");
   $doctor_name = rtrim($doctor_name," ");

   $sql5 = "SELECT * FROM users WHERE username = '$doctor_name' and specialization = '$specialization' and user = 3;";
   $result5 = $con->query($sql5);
   $rows5 = mysqli_fetch_array($result5,  MYSQLI_ASSOC);
   $doctor_id = $rows5['user_id'];

   // $specialization = substr($sd, 0, -1);
   // $doctor_name = substr($sd, 5);
   $symptoms = $_POST['symptoms'];
   $date = date('Y-m-d', strtotime($_POST['date']));
   $time = $_POST['time'];

   
   $patient_name = $_SESSION['username'];
   $patient_email_id = $_SESSION['email_id'];

   $sql6 = "SELECT * FROM users WHERE email_id = '$patient_email_id' and user = 'patient';";
   $result6 = $con->query($sql6);
   $rows6 = mysqli_fetch_array($result6,  MYSQLI_ASSOC);
   $count = mysqli_num_rows($result5);
      
   if($count == 1) {
      $patient_id = $rows6['user_id'];
   }
   

   // $sql2 = "SELECT * FROM users WHERE email_id = '$email_id';";
   // $result2 = mysqli_query($con, $sql2);
   // $row2 = mysqli_fetch_array($result2,  MYSQLI_ASSOC);
     
   // $count2 = mysqli_num_rows($result2);
		
   // if($count2 == 1) {
   //    $error = "User with the entered email-id has already been registered.";
   //    echo '<script type="text/javascript">
   //       window.onload = function () { alert("User with the entered email-id has already been registered."); }
   //       </script>';  
   // } 
   // else {
   //    $image = $_FILES["image"]["name"];
   //    $tempname = $_FILES["image"]["tmp_name"];    
   //    $folder = "uploadedImages/".$image;
      
   //    $sql = "INSERT INTO users(user, username, birth_date, location, image, contact,  email_id, password) VALUES('patient', '$user_name', '$birth_date', '$location', '$image', '$contact', '$email_id', '$password');";
      
   //    // Execute the query
   //    if($con->query($sql) == true){
   //       // Flag for successful insertion
   //       $insert = true;
   //    } else {
   //       echo "ERROR: $sql <br> $con->error";
   //    }

   //    // Now let's move the uploaded image into the folder: image
   //    if (move_uploaded_file($tempname, $folder))  {
   //       $msg = "Image uploaded successfully";
   //    }else{
   //       $msg = "Failed to upload image";
   //    }
   // }

   $sql2 = "INSERT INTO appointments(patient_id, doctor_id, symptoms, date, time, status) VALUES('$patient_id', '$doctor_id', '$symptoms', '$date', '$time', 'upcoming');";
   // VALUES('a', 'a', 'a', 'a', 'a', 'a', 'a');";
      
      // Execute the query
      if($con->query($sql2) == true){
         // Flag for successful insertion
         $insert = true;
         header("location: patientHomepage.php");
      } else {
         echo "ERROR: $sql2 <br> $con->error";
      }
}


// Close the database connection
$con->close();
?>

<?php
  if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location: login.php");
  }
?>

<?php
  if(!isset($_SESSION['email_id'])){
    header("location: login.php");
  }
?>


<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="addNewPatient.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <title>Registration Form</title>
   </head>
   
   <body>
      <header>
         <a href="#" class="logo"><span>BookDoc.</span></a>
         <form method="post"><a>
          <button type="submit" class="btn" id="logout" name="logout" value="Logout"
          style="width: 150%; height:40px; font-weight: 600; color: #33a396; background: #fff; padding: 2px 20px;">
            <span class="hoverText">Logout</span>
            <i class="fa fa-sign-out" style='font-size:24px'></i>
          </button>
        </a></form>
      </header>

      <div class="wrapper" style="height: 60%;">
         <div class="title-text">
            <div class="title signup" style="font-size:34px;">
               Book Appointment
            </div>
         </div>
         <div class="form-container">
            <div class="form-inner">
               <form action="bookAppointment.php" name="book" method="post" class="signup" enctype="multipart/form-data">
                  <div class="field">
                    <label for="doctor">Doctor:</label>
                    <select id="doctor" name="doctor" size="1" onchange="selectDoctor()" style="height: 30px; width: 100%; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                        <option value="" disabled selected>Select a Doctor</option>    
                        
                        <?php
                        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)) 
                        {
                           echo '<option value=" '.$row['specialization']." - Dr. ".$row["username"].' "> '.$row['specialization']." - Dr. ".$row["username"].' </option>';
                        }
                        ?>    
                        <!-- <option value="Panjim">Dentist - Dr. James Sequeira</option>
                        <option value="Margao">Margao</option>
                        <option value="Mapusa">Mapusa</option>
                        <option value="Vasco">Vasco</option> -->
                    </select><br><br>
                  </div>
                  <script type="text/javascript">
                     function selectDoctor(){
                        var doctor = document.forms["book"]["doctor"].value;
                        var d = doctor.substring(doctor.indexOf('-') + 5);
                        alert(d);
                     }
                  </script>

                  <div class="field">
                     <p>Problem:
                        <input type="text" name="symptoms" required>
                     </p>
                  </div>

                  <div class="field">
                     <p>Date:
                        <input type="date" name="date" id="date" min="<?php echo (new DateTime('tomorrow'))->format('Y-m-d'); ?>" required>
                     </p>
                  </div><br>
                  <script>
                     // onfocus="date()"
                     function date() {
                        var today = new Date();
                        document.getElementById("date").min = today.getDate() + 1;
                     }
                  </script>

                     <label for="time">Time:</label>
                     <select id="time" name="time" size="1" style="height: 30px; width: 100%; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                        <option value="" disabled selected>Select a Time</option>   
                        <?php
                        $appointments = array("9:00am", "10:00am", "11:00am", "12:00pm", "2:00pm", "3:00pm", "4:00pm", "5:00pm", "6:00pm");
                     
                        while ($row3 = mysqli_fetch_array($result3,  MYSQLI_ASSOC)) {
                           $doc = "<script>document.write(d)</script>";
                           if($row3['u.username'] === 'James Sequeira' and in_array($row3['a.time'], $appointments))
                           {
                              $key = array_search($row3['a.time'], $appointments);
                              unset($appointments[$key]);
                              // $appointments = array_diff($appointments, $row3['a.time']);   
                           }
                        }
                        
                        for ($i = 0; $i < count($appointments); $i++) {   
                           print($arr[$i] . " ");   
                           echo '<option value="'.$appointments[$i].'"> '.$appointments[$i].' </option>';
                        } 
                        ?>
                     </select><br><br>

                  <!-- <div class="field">
                     <p>Time:
                        <input type="time" name="time" id="time" onfocus="time()" required>
                     </p>
                  </div> -->

                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="BOOK" name="Book">
                  </div>
               </form>
            </div>
         </div>
      </div>      
   </body>
</html>