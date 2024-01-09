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

  $sql3 = "SELECT * FROM location;";
  $result3 = $con->query($sql3);

  $sql = "SELECT * FROM users;";
  $result = $con->query($sql);

if(isset($_POST['update'])){

    $username = $_POST['username'];
    $birth_date = $_POST['birth_date'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $email_id = $_POST['email_id'];
    // $password = $_POST['password'];

    $image = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];    
    $folder = "uploadedImages/".$image;

    $temp1 = $_GET['id'];

    $sql2 = "SELECT * FROM users;";
    $result2 = $con->query($sql2);
    while($rows2 = mysqli_fetch_array($result2)){
        if($rows2['user_id']==$temp1){
            $img = $rows2['image'];
        }
    }

    if(isset($_FILES['image'])&& !empty( $_FILES["image"]["name"] )){
        $sql1 = "UPDATE users SET username = '$username', birth_date = '$birth_date', location = '$location', contact = '$contact', image = '$image', email_id = '$email_id' WHERE user_id = '$temp1';";
    } else {
        $sql1 = "UPDATE users SET username = '$username', birth_date = '$birth_date', location = '$location', contact = '$contact', email_id = '$email_id' WHERE user_id = '$temp1';";
    }
    
    if($con->query($sql1) == true){
        // header("location: adminDashboard.php");
     } else {
        echo "ERROR: $sql1 <br> $con->error";
     }
    //alert("Successfully updated!");

    // Now let's move the uploaded image into the folder: image
    if (move_uploaded_file($tempname, $folder))  {
        $msg = "Image uploaded successfully";
    }else{
        $msg = "Failed to upload image";
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
    <link rel="stylesheet" href="editPatient.css">
    <title>Edit Page</title>
</head>

<body background="images/medical.jpg">
    <header>
        <a href="#" class="logo"><span>BookDoc.</span></a>
    </header>
    <div class="card" style="height: 100%; margin-top:10%;">
        <div><h2>Edit Page</h2>
        </div>
        <hr style="margin-bottom:10px;">
        <?php   // LOOP TILL END OF DATA 
            //while($rows=$result->fetch_assoc() &&
            $temp = $_GET['id'];
            while($rows = mysqli_fetch_array($result)){
               if($rows['user_id'] == $temp){
        ?>
        <div class="form"></div>
        <form action="edit.php?id=<?php echo $rows['user_id']?>" method="post" class="edit" enctype="multipart/form-data">
            <div class="field">
                <p style="font-size: 20px;">Username: 
                    <input type="text" value="<?php echo $rows['username'];?>" name="username" required>
                </p>
            </div>
            <div class="field">
                <p style="font-size: 20px;">Date of Birth: 
                    <input type="date" value="<?php echo $rows['birth_date'];?>" name="birth_date" style="height: 30px; width: 50%; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px; border-radius: 5px;" required>
                </p>
            </div>
            <div class="field">
                <label for="location"><p style="font-size: 20px;">Location:</label>
                <select id="location" name="location" size="1" style="height: 30px; width: 50%; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                        <option value="" disabled>Select a Taluka</option>                            
                        <?php
                        while ($row3 = mysqli_fetch_array($result3,  MYSQLI_ASSOC)) 
                        {
                            if($rows['location'] == $row3['taluka'])
                            {
                                echo '<option value=" '.$row3['taluka'].' " selected> '.$row3['taluka'].' </option>';
                            }else{
                                echo '<option value=" '.$row3['taluka'].' "> '.$row3['taluka'].' </option>';
                            }
                        }
                        ?>
                    </select><br><br>
                </p>
            </div>
            <div class="field">
                <p style="font-size: 20px;">Image: 
                    <input type="file" name="image" id="image">
                    <p style="font-size: 20px;">Current Image: <img type="image" src="uploadedImages/<?php echo $rows['image'];?>" alt="Uploaded Image" name="currentImage" width=100 height=80></p>
                </p>
            </div>
            <div class="field">
                <p style="font-size: 20px;">Contact Number: 
                    <input type="text" value="<?php echo $rows['contact'];?>" name="contact" required>
                </p>
            </div>
            <div class="field">
                <p style="font-size: 20px;">Email ID: 
                    <input type="text" value="<?php echo $rows['email_id'];?>" name="email_id" required>
                </p>
            </div>
            <?php
               if($_SESSION['user'] == 'patient'){
            ?>
            <div class="field">
                <p style="font-size: 20px;">Password: 
                    <input type="password" value="<?php echo $rows['password'];?>" name="password" required>
                </p>
            </div>
            <?php
                }
            ?>
            <div class="btn">
                <input type="submit" value="Save Changes" name="update">
            </div>
        </form>
    </div>
    <?php
            }
        }
    ?>
</body>

</html>