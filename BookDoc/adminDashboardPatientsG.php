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

  $sql9 = "SELECT * FROM users where user = 1;";
  $result9 = $con->query($sql9);
  $row9 = mysqli_fetch_array($result9,  MYSQLI_ASSOC);

  $sql8 = "SELECT * FROM usertype;";
  $result8 = mysqli_query($con, $sql8);
  $row8 = mysqli_fetch_array($result8,  MYSQLI_ASSOC);
  if($row8['user'] == 'admin'){
     $user1 = $row8['id'];
  }
  if($row8['user'] == 'patient'){
    $user2 = $row8['id'];
 }
 if($row8['user'] == 'doctor'){
    $user3 = $row8['id'];
}

  $sql = "SELECT * FROM users where user = 1;";
  $result = $con->query($sql);
  // $row = mysqli_fetch_array($result,  MYSQLI_ASSOC);
  // $view = $row['view'];

  $sql1 = "SELECT * FROM users where user = 2;";
  $result1 = $con->query($sql1);

  $sql2 = "SELECT * FROM users where user = 2;";
  $result2 = $con->query($sql2);

  $sql6 = "SELECT * FROM users where user = 3;";
  $result6 = $con->query($sql6);

  $sql8 = "SELECT * FROM users where user = 3;";
  $result8 = $con->query($sql8);

  if(isset($_POST['delete'])){
    $temp = $_GET['id'];
    // echo '<script type="text/javascript"> ';  
    // echo ' function openulr(newurl) {';  
    // echo '  if (confirm("Are you sure you want to delete this user?")) {';
      $sql3 = "DELETE FROM users WHERE user_id = '$temp';";
      $sql13 = "DELETE FROM appointments WHERE patient_id = '$temp';";
      //$result1 = mysqli_query($con, $sql1);
      if(($con->query($sql3) == true) and ($con->query($sql13) == true)) {
        echo '<script type ="text/JavaScript">';
        echo 'alert("User deleted successfully!")';
        echo '</script>';
        header("location: adminDashboardPatientsG.php");
      } else {
        echo "ERROR: $sql3 <br> $con->error";
        echo "ERROR: $sql13 <br> $con->error";
      }
      echo 'alert("Successfully deleted!")';  
      // echo '    document.location = newurl;';  
    // echo '  }';  
    // echo '}';  
    // echo '</script>';   
  }

  if(isset($_POST['search'])){
      $search = $_POST['searchText'];
      $sql4 = "SELECT * FROM users WHERE username like '%$search%' and user = 2;";
      $sql12  = "SELECT TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS Age FROM users WHERE username like '%$search%' and user = 2;";
      $result12 = mysqli_query($con, $sql12);
      $result4 = mysqli_query($con, $sql4);
      $count4 = mysqli_num_rows($result4);
      
      // If result matched $email_id and $password, table row must be 1 row
      if($count4 != 0) {
        $toSearch = true;
      } else {
        $error = "No user with the entered username exists!";
        echo '<script type="text/javascript">
        window.onload = function () { alert("No user with the entered username exists!"); }
        </script>';        
      }
  }

  if(isset($_POST['sort'])){
    $toSort = true;
    $sort = $_POST['sortText'];
    if($sort === 'Name'){
      $sql5 = "SELECT * FROM users WHERE user = 2 ORDER BY username;";
      $sql11 = "SELECT TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS Age FROM users WHERE user = 2 ORDER BY username;";
    }
    if($sort === 'Location'){
      $sql5 = "SELECT * FROM users WHERE user = 2 ORDER BY location;";
      $sql11 = "SELECT TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS Age FROM users WHERE user = 2 ORDER BY location;";
    }
    if($sort === 'Age'){
      $sql5 = "SELECT * FROM users WHERE user = 2 ORDER BY birth_date DESC;";
      $sql11 = "SELECT TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS Age FROM users WHERE user = 2 ORDER BY birth_date DESC;";
    }
    $result5 = mysqli_query($con, $sql5);
    $result11 = mysqli_query($con, $sql11);
  }

  if(isset($_POST['changeView'])){
    $sql6 = "SELECT * FROM users where user = 1;";
    $result6 = mysqli_query($con, $sql6);
    $row6 = mysqli_fetch_array($result6,  MYSQLI_ASSOC);
    if($row6['view'] === 'list'){
      $sql7 = "UPDATE users SET view = 'grid' WHERE user = 1;";
      $result7 = mysqli_query($con, $sql7);
      $row7 = mysqli_fetch_array($result7,  MYSQLI_ASSOC);
      header("location: adminDashboardPatientsG.php");
    }
    if($row6['view'] === 'grid'){
      $sql7 = "UPDATE users SET view = 'list' WHERE user = 1;";
      $result7 = mysqli_query($con, $sql7);
      $row7 = mysqli_fetch_array($result7,  MYSQLI_ASSOC);
      header("location: adminDashboardPatients.php");
    }
  }

    $sql10 = "SELECT TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS Age FROM users WHERE user = 2;";
    $result10 = mysqli_query($con, $sql10);

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
      <meta charset="utf-8">
      <link rel="stylesheet" href="adminDashboard.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <title>Admin Dashboard</title>
   </head>

   <body>
      <header>
         <a href="#" class="logo"><span>BookDoc.</span></a>
         <form method="post"><a>
          <button type="submit" class="btn" id="logout" name="logout" value="Logout"
          style="width: 100%; font-weight: 600; color: #33a396; background: #fff; padding: 2px 20px;">
            <span class="hoverText">Logout</span>
            <i class="fa fa-sign-out" style='font-size:24px'></i>
          </button>
        </a></form>
      </header>
      
      <div class="sidebar" style="width:18%; margin-top:5%;">

                <?php   // LOOP TILL END OF DATA 
                //while($rows=$result->fetch_assoc() && 
                    while($rows = mysqli_fetch_array($result)){
                        if($rows['email_id']==$_SESSION['email_id']){
                ?>
            <div class="profile">
                <h3><?php echo $rows['email_id']?></h3>
            </div> 
            <?php
                        }
                    }
            ?>
            <button name="patients" id="patients" style="background-color:#f51637;" class="tablinks">Patients</button>
            <button name="doctors" id="doctors" style="background-color:#f51637;" class="tablinks" onclick="displayDoctors()">Doctors</button>
            <script>
              function displayPatients() {
                // var x = document.getElementById("patient");
                // var y = document.getElementById("doctor");
                // x.style.display = "block";
                // y.style.display = "none";
                // displayP = true;
                location.href = "http://localhost/BookDoc/adminDashboardPatients.php";
              }
              function displayDoctors() {
                <?php if($row9['view'] == 'list'){ ?>
                    location.href = "http://localhost/BookDoc/adminDashboardDoctors.php";
                <?php } 
                if($row9['view'] == 'grid'){ ?>
                   location.href = "http://localhost/BookDoc/adminDashboardDoctorsG.php";
                <?php } ?>
              }
              </script>
      </div>

      <div id="patient" class="tabcontent" style="margin-left:18%; margin-top:6%;">
        <section class="patients">

          <div class="title">
            <h2 class="title-text">The <span>PATIENTS'</span> details</h2>
          </div>

            <div class="filter">

                <form action="adminDashboardPatientsG.php" method="post" enctype="multipart/form-data">
                  <div class="field">
                    <input type="search" name="searchText" placeholder="Search.." id="searchText"
                    style="height: 30px; width: 50%; outline: none; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                    <button style="background-color:#90E4C1; width:12%; margin-left: 5px;" name="search" class="btn">    
                      <span class="hoverText">Search by Username</span>
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </form>
              
              <form action="adminDashboardPatientsG.php" method="post" enctype="multipart/form-data">
                  <div class="field">
                    <select id="sort" name="sortText" size="1" 
                    style="height: 30px; width: 70%; outline: none; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                        <option value="Name">Name</option>
                        <option value="Location">Location</option>
                        <option value="Age">Age</option>
                    </select>
                    <button style="background-color:#90E4C1; width:23%; margin-left:5px;" name="sort" class="btn">
                      <span class="hoverText">Sort By</span>
                      <i class="fa fa-sort"></i>
                    </button>
                    <!-- <input type="submit" value="SIGNUP" name="Signup"> -->
                  </div>
              </form>

              <form action="adminDashboardPatientsG.php" method="post" enctype="multipart/form-data">
                <button style="background-color:#90E4C1; width:400%; margin-left:130%;" name="changeView" class="btn">
                  <span class="hoverText">Change View</span>
                  <i class="fa fa-exchange"></i>
                </button>
              </form>

              <!-- <script>
              function changeView() {
                // var x = document.getElementById("list");
                // var y = document.getElementById("grid");
                // if (x.style.display === "none") {
                //   x.style.display = "block";
                //   y.style.display = "none";
                // } else {
                //   x.style.display = "none";
                //   y.style.display = "block"; 
                // }
                location.href = "http://localhost/BookDoc/adminDashboardPatientsG.php";
              }
              </script> -->

              <!-- <li><a href="http://localhost/BookDoc/addDoctors.php">Add a New Doctor +</a></li> -->
              <!-- <a href="http://localhost/BookDoc/addPatients.php">Add a New Patient +</a> -->
              
              <button style="background-color:#90E4C1; margin-left:20%;" class="btn" onclick="addNewPatient()">
                <span class="hoverText">Add A New Patient</span>
                <i class="fa fa-user-plus"></i>
              </button>
              <script>
                function addNewPatient() {
                  window.location.href="addNewPatient.php";
                }
              </script>

            </div>

        <div id="grid">
            <?php 
                if($toSearch){
                    while($rows4 = mysqli_fetch_array($result4) and $rows12 = mysqli_fetch_array($result12)){
                        if($rows4['user']==2){
            ?>

          <div class="card" style="display:inline-block; color:teal; background-color:#c2fcf6; font-size:15px; margin-top:2%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:25%; height:37%; text-align:center;">
            <table>
              <tr>
                <td width="30%;"></td>
                <td><img src="uploadedImages/<?php echo $rows4['image'];?>" width="140" height="140"></td>

                <td rowspan="2">              
                  <form action="viewPatient.php?id=<?php echo $rows4['user_id']?>" id="view" method="post">
                    <a href="viewPatient.php?id=<?php echo $rows4['user_id'];?>">
                      <input type="submit" name="view" value="&#x1F4C5;" id="<?php echo $rows4['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#6ffc8e;">
                    </a>
                  </form>
                  <form action="edit.php?id=<?php echo $rows4['user_id']?>" id="edit" method="post">
                    <a href="edit.php?id=<?php echo $rows4['user_id'];?>">
                      <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows4['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                    </a>
                  </form>
                  <form action="adminDashboardPatientsG.php?id=<?php echo $rows4['user_id'];?>" id="delete" method="post">
                    <a href="adminDashboardPatientsG.php?id=<?php echo $rows4['user_id'];?>">
                      <input type="submit" name="delete" value="&#10006;" id="<?php echo $rows4['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                    </a>  
                  </form>
                </td>
              </tr>
            </table>
            <div class="container">
              Username: <?php echo $rows4['username'];?><br>
              Age: <?php echo $rows12['Age'];?><br>
              Location: <?php echo $rows4['location'];?><br>
              Contact: <?php echo $rows4['contact'];?><br>
              Email-ID: <?php echo $rows4['email_id'];?><br>
            </div>
          </div>

          <?php
                }
              }
            } else if($toSort){
                while($rows5 = mysqli_fetch_array($result5) and $rows11 = mysqli_fetch_array($result11)){
                    if($rows5['user']==2){
          ?>

        <div class="card" style="display:inline-block; color:teal; background-color:#c2fcf6; font-size:15px; margin-top:2%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:25%; height:37%; text-align:center;">
            <table>
              <tr>
                <td width="30%;"></td>
                <td><img src="uploadedImages/<?php echo $rows5['image'];?>" width="140" height="140"></td>

                <td rowspan="2">              
                  <form action="viewPatient.php?id=<?php echo $rows5['user_id']?>" id="view" method="post">
                    <a href="viewPatient.php?id=<?php echo $rows5['user_id'];?>">
                      <input type="submit" name="view" value="&#x1F4C5;" id="<?php echo $rows5['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#6ffc8e;">
                    </a>
                  </form>
                  <form action="edit.php?id=<?php echo $rows5['user_id']?>" id="edit" method="post">
                    <a href="edit.php?id=<?php echo $rows5['user_id'];?>">
                      <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows5['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                    </a>
                  </form>
                  <form action="adminDashboardPatientsG.php?id=<?php echo $rows5['user_id'];?>" id="delete" method="post">
                    <a href="adminDashboardPatientsG.php?id=<?php echo $rows5['user_id'];?>">
                      <input type="submit" name="delete" value="&#10006;" id="<?php echo $rows5['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                    </a>  
                  </form>
                </td>
              </tr>
            </table>
            <div class="container">
              Username: <?php echo $rows5['username'];?><br>
              Age: <?php echo $rows11['Age'];?><br>
              Location: <?php echo $rows5['location'];?><br>
              Contact: <?php echo $rows5['contact'];?><br>
              Email-ID: <?php echo $rows5['email_id'];?><br>
            </div>
        </div>

          <?php 
                    }
                }
            } else{
                while($rows2 = mysqli_fetch_array($result2) and $rows10 = mysqli_fetch_array($result10)){
                    if($rows2['user']==2){
          ?>

        <div class="card" style="display:inline-block; color:teal; background-color:#c2fcf6; font-size:15px; margin-top:2%; margin-bottom:2%; margin-left:3%; margin-right:5%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s; width:25%; height:37%; text-align:center;">
            <table>
              <tr>
                <td width="30%;"></td>
                <td><img src="uploadedImages/<?php echo $rows2['image'];?>" width="140" height="140"></td>

                <td rowspan="2">              
                  <form action="viewPatient.php?id=<?php echo $rows2['user_id']?>" id="view" method="post">
                    <a href="viewPatient.php?id=<?php echo $rows2['user_id'];?>">
                      <input type="submit" name="view" value="&#x1F4C5;" id="<?php echo $rows2['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#6ffc8e;">
                    </a>
                  </form>
                  <form action="edit.php?id=<?php echo $rows2['user_id']?>" id="edit" method="post">
                    <a href="edit.php?id=<?php echo $rows2['user_id'];?>">
                      <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows2['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                    </a>
                  </form>
                  <form action="adminDashboardPatientsG.php?id=<?php echo $rows2['user_id'];?>" id="delete" method="post">
                    <a href="adminDashboardPatientsG.php?id=<?php echo $rows2['user_id'];?>">
                      <input type="submit" name="delete" value="&#10006;" id="<?php echo $rows2['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                    </a>  
                  </form>
                </td>
              </tr>
            </table>
            <div class="container">
              Username: <?php echo $rows2['username'];?><br>
              Age: <?php echo $rows10['Age'];?><br>
              Location: <?php echo $rows2['location'];?><br>
              Contact: <?php echo $rows2['contact'];?><br>
              Email-ID: <?php echo $rows2['email_id'];?><br>
            </div>
        </div>

          <?php 
                    }
                }
            }
          ?>

    </div>

        </section>
      </div>

   </body>
</html>