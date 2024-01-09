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

  if(isset($_POST['delete'])){
    $temp = $_GET['id'];
    echo '<script type="text/javascript"> ';  
    // echo ' function openulr(newurl) {';  
    // echo '  if (confirm("Are you sure you want to delete this user?")) {';
      $sql3 = "DELETE FROM users WHERE user_id = '$temp';";
      $sql13 = "DELETE FROM appointments WHERE patient_id = '$temp';";
      //$result1 = mysqli_query($con, $sql1);
      if(($con->query($sql3) == true) and ($con->query($sql13) == true)) {
        echo '<script type ="text/JavaScript">';
        echo 'alert("User deleted successfully!")';
        echo '</script>';
        header("location: sample.php");
      } else {
        echo "ERROR: $sql3 <br> $con->error";
        echo "ERROR: $sql13 <br> $con->error";
      }
      echo 'alert("Successfully deleted!")';  
      // echo '    document.location = newurl;';  
    // echo '  }';  
    // echo '}';  
    echo '</script>';   
  }

  if(isset($_POST['search'])){
      $search = $_POST['searchText'];
      $sql4 = "SELECT * FROM users WHERE username like '%$search%' and user = 2;";
      $result4 = mysqli_query($con, $sql4);
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
      header("location: sample.php");      
    }
  }

    $sql10 = "SELECT TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS Age FROM users WHERE user = 2;";
    $result10 = mysqli_query($con, $sql10);

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

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="adminDashboard.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <title>Admin Dashboard</title>

      <script>
                  getPagination('#table-id');
					//getPagination('.table-class');
					//getPagination('table');

		  /*					PAGINATION 
		  - on change max rows select options fade out all rows gt option value mx = 5
		  - append pagination list as per numbers of rows / max rows option (20row/5= 4pages )
		  - each pagination li on click -> fade out all tr gt max rows * li num and (5*pagenum 2 = 10 rows)
		  - fade out all tr lt max rows * li num - max rows ((5*pagenum 2 = 10) - 5)
		  - fade in all tr between (maxRows*PageNum) and (maxRows*pageNum)- MaxRows 
		  */
		 

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');						// reset pagination

     lastPage = 1;
      $('.pagination')
        .find('li')
        .slice(1, -1)
        .remove();
      var trnum = 0; // reset tr counter
      var maxRows = parseInt($(this).val()); // get Max Rows from select option

      if (maxRows == 5000) {
        $('.pagination').hide();
      } else {
        $('.pagination').show();
      }

      var totalRows = $(table + ' tbody tr').length; // numbers of rows
      $(table + ' tr:gt(0)').each(function() {
        // each TR in  table and not the header
        trnum++; // Start Counter
        if (trnum > maxRows) {
          // if tr number gt maxRows

          $(this).hide(); // fade it out
        }
        if (trnum <= maxRows) {
          $(this).show();
        } // else fade in Important in case if it ..
      }); //  was fade out to fade it in
      if (totalRows > maxRows) {
        // if tr total rows gt max rows option
        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
        //	numbers of pages
        for (var i = 1; i <= pagenum; ) {
          // for each page append pagination li
          $('.pagination #prev')
            .before(
              '<li data-page="' +
                i +
                '">\
								  <span>' +
                i++ +
                '<span class="sr-only">(current)</span></span>\
								</li>'
            )
            .show();
        } // end for i
      } // end if row count > max rows
      $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
      $('.pagination li').on('click', function(evt) {
        // on click each page
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var pageNum = $(this).attr('data-page'); // get it's number

        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

        if (pageNum == 'prev') {
          if (lastPage == 1) {
            return;
          }
          pageNum = --lastPage;
        }
        if (pageNum == 'next') {
          if (lastPage == $('.pagination li').length - 2) {
            return;
          }
          pageNum = ++lastPage;
        }

        lastPage = pageNum;
        var trIndex = 0; // reset tr counter
        $('.pagination li').removeClass('active'); // remove active class from all li
        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
        // $(this).addClass('active');					// add active class to the clicked
	  	limitPagging();
        $(table + ' tr:gt(0)').each(function() {
          // each tr in table not the header
          trIndex++; // tr index counter
          // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
          if (
            trIndex > maxRows * pageNum ||
            trIndex <= maxRows * pageNum - maxRows
          ) {
            $(this).hide();
          } else {
            $(this).show();
          } //else fade in
        }); // end of for each tr in table
      }); // end of on click pagination list
	  limitPagging();
    })
    .val(5)
    .change();

  // end of on select change

  // END OF PAGINATION
}

function limitPagging(){
	// alert($('.pagination li').length)

	if($('.pagination li').length > 7 ){
			if( $('.pagination li.active').attr('data-page') <= 3 ){
			$('.pagination li:gt(5)').hide();
			$('.pagination li:lt(5)').show();
			$('.pagination [data-page="next"]').show();
		}if ($('.pagination li.active').attr('data-page') > 3){
			$('.pagination li:gt(0)').hide();
			$('.pagination [data-page="next"]').show();
			for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
				$('.pagination [data-page="'+i+'"]').show();

			}

		}
	}
}

$(function() {
  // Just to append id number for each row
  $('table tr:eq(0)').prepend('<th> ID </th>');

  var id = 0;

  $('table tr:gt(0)').each(function() {
    id++;
    $(this).prepend('<td>' + id + '</td>');
  });
});

      </script>
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
                location.href = "http://localhost/BookDoc/sample.php";
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
              <!-- <li><a href="http://localhost/BookDoc/start.php">Homepage</a></li> -->


                <form action="sample.php" method="post" enctype="multipart/form-data">
                  <div class="field">
                    <input type="search" name="searchText" placeholder="Search.." id="searchText"
                    style="height: 30px; width: 50%; outline: none; padding-left: 15px; border: 1px solid lightgrey; border-bottom-width: 2px; font-size: 17px;" required>
                    <button style="background-color:#90E4C1; width:12%; margin-left: 5px;" name="search" class="btn">    
                      <span class="hoverText">Search by Username</span>
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </form>



              <!-- <div id="myDropdown" class="dropdown-content" style="display: none; position: absolute; background-color: #f6f6f6; min-width: 230px; overflow: auto; border: 1px solid #ddd; z-index: 1;">
                <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                  <a href="#about" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">About</a>
                            <a href="#base" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">Base</a>
                            <a href="#blog" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">Blog</a>
                            <a href="#contact" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">Contact</a>
                            <a href="#custom" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">Custom</a>
                            <a href="#support" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">Support</a>
                            <a href="#tools" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">Tools</a>
              </div> -->
              
              <form action="sample.php" method="post" enctype="multipart/form-data">
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

              <form action="sample.php" method="post" enctype="multipart/form-data">
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
  
        <div id="list">
          <?php 
            if($toSearch){
          ?>

          <table style="border:5px solid #33a396; width:100%; margin-top:1%;">
          <tr style="outline:3px solid #33a396; color:teal; font-size:25px;" class="displayTitle">
            <th >Image</th>
            <th>Username</th>
            <th>Age</th>
            <th>Location</th>
            <th>Contact Number</th>
            <th>Email-ID</th>
            <th> </th>
          </tr>

          <!-- PHP CODE TO FETCH DATA FROM ROWS-->
          <?php   // LOOP TILL END OF DATA 
            while($rows4 = mysqli_fetch_array($result4) and $rows12 = mysqli_fetch_array($result12)){
              if($rows4['user']==2){
          ?>

          <tr style="outline:3px solid #33a396; text-align:center; color:teal;" class="displayData">
                <!--FETCHING DATA FROM EACH 
                    ROW OF EVERY COLUMN-->
            <td><img src="uploadedImages/<?php echo $rows4['image'];?>" width="70" height="70"></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows4['username'];?></td>

            <!-- <td style="text-align:center; font-size:20px;"><?php echo $rows4['birth_date'];?></td> -->

            <td style="text-align:center; font-size:20px;"><?php echo $rows12['Age'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows4['location'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows4['contact'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows4['email_id'];?></td>
            <td>
              <form action="viewPatient.php?id=<?php echo $rows4['user_id']?>" id="view" method="post">
                <a href="viewPatient.php?id=<?php echo $rows4['user_id']?>">
                <input type="submit" name="view" value="&#x1F4C5;" id="<?php echo $rows4['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#6ffc8e;">
                </a>
              </form>
            </td>
            <td>
              <form action="edit.php?id=<?php echo $rows4['user_id']?>" id="edit" method="post">
                <a href="edit.php?id=<?php echo $rows4['user_id']?>">
                <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows4['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                </a>
              </form>
            </td>
            <td>
            <form action="sample.php?id=<?php echo $rows4['user_id']?>" id="delete" method="post">
                <a href="sample.php?id=<?php echo $rows4['user_id']?>">
                <input type="submit" name="delete" value="&#10006;" id="<?php echo $rows4['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                </a>  
              </form>
            </td>
          </tr>
          
          <?php
                }
              }
            
          ?>
          </table>

          <?php
            } else if($toSort){
          ?>

          <table style="border:5px solid #33a396; width:100%; margin-top:1%;">
          <tr style="outline:3px solid #33a396; color:teal; font-size:25px;" class="displayTitle">
            <th >Image</th>
            <th>Username</th>
            <th>Age</th>
            <th>Location</th>
            <th>Contact Number</th>
            <th>Email-ID</th>
            <th> </th>
          </tr>

          <!-- PHP CODE TO FETCH DATA FROM ROWS-->
          <?php   // LOOP TILL END OF DATA 
            while($rows5 = mysqli_fetch_array($result5) and $rows11 = mysqli_fetch_array($result11)){
              if($rows5['user']==2){
          ?>

          <tr style="outline:3px solid #33a396; text-align:center; color:teal;" class="displayData">
                <!--FETCHING DATA FROM EACH 
                    ROW OF EVERY COLUMN-->
            <td><img src="uploadedImages/<?php echo $rows5['image'];?>" width="70" height="70"></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows5['username'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows11['Age'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows5['location'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows5['contact'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows5['email_id'];?></td>
            <td>
              <form action="viewPatient.php?id=<?php echo $rows5['user_id']?>" id="view" method="post">
                <a href="viewPatient.php?id=<?php echo $rows5['user_id']?>">
                <input type="submit" name="view" value="&#x1F4C5;" id="<?php echo $rows5['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#6ffc8e;">
                </a>
              </form>
            </td>
            <td>
              <form action="edit.php?id=<?php echo $rows5['user_id']?>" id="edit" method="post">
                <a href="edit.php?id=<?php echo $rows5['user_id']?>">
                <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows5['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                </a>
              </form>
            </td>
            <td>
            <form action="sample.php?id=<?php echo $rows5['user_id']?>" id="delete" method="post">
                <a href="sample.php?id=<?php echo $rows5['user_id']?>">
                <input type="submit" name="delete" value="&#10006;" id="<?php echo $rows5['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                </a>  
              </form>
            </td>
          </tr>
          
          <?php
                }
              }
            
          ?>
          </table>

          <?php 
            } else{
          ?>
          <h2>Select Number Of Rows</h2>
				<div class="form-group"> 	<!--		Show Numbers Of Rows 		-->
			 		<select class  ="form-control" name="state" id="maxRows">
						 <option value="5000">Show ALL Rows</option>
						 <option value="5">5</option>
						 <option value="10">10</option>
						 <option value="15">15</option>
						 <option value="20">20</option>
						 <option value="50">50</option>
						 <option value="70">70</option>
						 <option value="100">100</option>
						</select>
			 		
			  	</div>

          <table id="table-id" style="border:5px solid #33a396; width:100%; margin-top:1%;">
          <tr style="outline:3px solid #33a396; color:teal; font-size:25px;" class="displayTitle">
            <th >Image</th>
            <th>Username</th>
            <th>Age</th>
            <th>Location</th>
            <th>Contact Number</th>
            <th>Email-ID</th>
            <th> </th>
          </tr>

          <!-- PHP CODE TO FETCH DATA FROM ROWS-->
          <?php   // LOOP TILL END OF DATA 
            //while($rows=$result->fetch_assoc() && 
            while($rows1 = mysqli_fetch_array($result1) and $rows10 = mysqli_fetch_array($result10)){
              if($rows1['user']==2){
          ?>

              <!-- <script>
                var dt1 = <?php echo $rows1['birth_date'];?>;
                var birth_date = new Date(dt1);
                var birth_year = birth_date.getFullYear();
                var birth_month = birth_date.getMonth();
                var calc_year = curr_year - birth_year;
                var calc_month = curr_month - birth_month;
                var age = (calc_year && "." && calc_month).toString();
                age = parseFloat;
              </script> -->

          <tr style="outline:3px solid #33a396; text-align:center; color:teal;" class="displayData">
                <!--FETCHING DATA FROM EACH 
                    ROW OF EVERY COLUMN-->
            <td><img src="uploadedImages/<?php echo $rows1['image'];?>" width="70" height="70"></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows1['username'];?></td>
            <!-- <td style="text-align:center; font-size:20px;"><?php echo $rows1['birth_date'];?></td> -->
            
            <td style="text-align:center; font-size:20px;"><?php echo $rows10['Age'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows1['location'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows1['contact'];?></td>
            <td style="text-align:center; font-size:20px;"><?php echo $rows1['email_id'];?></td>
            <td>
              <form action="viewPatient.php?id=<?php echo $rows1['user_id']?>" id="view" method="post">
                <a href="viewPatient.php?id=<?php echo $rows1['user_id']?>">
                  <!-- <button type="submit" form="view" style="font-size:24px; background-color:#6ffc8e;"><i class="fa fa-calendar"></i></button> -->
                  <!-- <i class="fa fa-calendar" style="font-size:24px; padding: 5px 5px 5px 5px; width:60%; border: black solid; background-color:#6ffc8e; color:#000;"></i> -->
                  <!-- <input type="submit" form="edit" style="font-size:24px; background-color:#6ffc8e;"> -->
                  <input type="submit" name="view" value="&#x1F4C5;" id="<?php echo $rows1['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#6ffc8e;">
                </a>
              </form>
            </td>
            <td>
              <form action="edit.php?id=<?php echo $rows1['user_id']?>" id="edit" method="post">
                <a href="edit.php?id=<?php echo $rows1['user_id']?>">
                  <!-- <button type="submit" form="edit" style="font-size:24px; background-color:#f5fc12;"><i class="fa fa-edit"></i></button> -->
                  <!-- <i class="fa fa-edit" style="font-size:24px; padding: 5px 5px 5px 5px; width:60%; border: black solid; background-color:#f5fc12; color:#000;"></i> -->
                  <!-- <input type="submit" form="edit" style="font-size:24px; background-color:#f5fc12;"> -->
                  <input type="submit" name="edit" value="&#9998;" id="<?php echo $rows1['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f5fc12;">
                </a>
              </form>
            </td>
            <td>
            <form action="sample.php?id=<?php echo $rows1['user_id']?>" id="delete" method="post">
                <a href="sample.php?id=<?php echo $rows1['user_id']?>">
                  <!-- <button type="submit" form="delete" name="delete" id="<?php echo $rows1['user_id'];?>" style="font-size:24px; background-color:#f51637;"><i class="fa fa-close"></i></button> -->
                  <!-- <i class="fa fa-close" style="font-size:24px; padding: 5px 5px 5px 5px; width:60%; border: black solid; background-color:#f51637; color:#000;"></i> -->
                  <input type="submit" name="delete" value="&#10006;" id="<?php echo $rows1['user_id'];?>" style="font-size:24px; width:50px; border: black solid; background-color:#f51637;">
                </a>  
              </form>
            </td>
          </tr>
          
          <?php
                }
              }
            
          ?>
          </table>

          

          <?php 
            }
          ?>
          <div class='pagination-container' >
				<nav>
				  <ul class="pagination">
            
            <li data-page="prev" >
								     <span> < <span class="sr-only">(current)</span></span>
								    </li>
				   <!--	Here the JS Function Will Add the Rows -->
        <li data-page="next" id="prev">
								       <span> > <span class="sr-only">(current)</span></span>
								    </li>
				  </ul>
				</nav>
			</div>

        </div>

        </section>
      </div>

   </body>
</html>