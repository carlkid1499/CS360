<?php
/* This is the signup php file. The goal if for a new/existing patient
to sign up for an account username and password.
*/

# Start the session
ob_start();
session_start();
# Declare Global Vars for file
$msg = "";
// Create connection for log in
$conn = new mysqli("localhost", "myhealth2", "CIOjh^J8h^?b", "myhealth2");
// Check if connection is valid
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  $msg = "Connection failed: to DB";
}

?>

<!------------- HTML ------------->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to My Health Portal</title>
  <link href="css/welcome.css" rel='stylesheet'>
  <link href="css/blue_theme.css" rel='stylesheet'>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <script src="js/effects.js"></script>

  <style>
    @-webkit-keyframes animatezoom {
      from {-webkit-transform: scale(0)} 
      to {-webkit-transform: scale(1)}
      }
    
    @keyframes animatezoom {
      from {transform: scale(0)} 
      to {transform: scale(1)}
      }

    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
      span.psw {
          display: block;
          float: none;
      }
      .cancelbtn {
          width: 100%;
      }
    }
  </style>
</head>

<body>

<!--top bar -->
<div class="w3-bar w3-theme-d5">
<!--Home Button-->
  <form class="form-options" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                                                    ?>" method="post">
      <button class="w3-bar-item w3-button" name="home" type="submit">Home
        <!-- If the logout button is pushed -->
        <?php if(isset($_POST['home']))
        {
          header('Location: index.php');
        } 
        ?>
        </button>
  </form>
</div>

<div class="header w3-theme-d2">
  <h1><b>My Health Patient Portal</b></h1>
</div>

<div class="content background container" style="height: 400px">
      <p>
        <br><br><br><br>
      </p>

  <div class="center">
    <button class="w3-button w3-xlarge w3-round w3-black w3-ripple" 
    onclick="document.getElementById('id01').style.display='block'" style="width:auto;"
      id="btnlogin" type="submit" name="login">Login
          <!-- If the login button is pushed -->
                
    </button>
    <button class="w3-button w3-xlarge w3-round w3-black w3-ripple" 
    onclick="document.getElementById('id02').style.display='block'" style="width:auto;"
      id="btnsignup" type="submit" name="signup">Create Account
    </button>
  </div>
      <p>
        <br><br><br><br>
      </p>
</div>

<div class="footer w3-theme-d2 center">
  <p>Created by Carlos, Kiran, and Keller</p>
</div>


<!--modal called to display login form-->
<div id="id01" class="modal">
  
  <form class="modal-content animate" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
      <img src="assets/user_icon.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">        
      <?php
      $msg = '';

      if (
        isset($_POST['login']) && !empty($_POST['username'])
        && !empty($_POST['password'])
      ) {

        // Create connection for log in
        $conn = new mysqli("localhost", "myhealth2", "CIOjh^J8h^?b", "myhealth2");
        // Check if connection is valid
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
          $msg = "Connection failed: to DB";
        }

        // Get the information from the forms
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query the users database for the information
        $results = $conn ->query("SELECT * FROM Users WHERE UserName like '%$username%' AND UserPassword like '%$password%' ");

        // If we get a match grant access
        if($results->num_rows >0)
        {
          // Set the Session values to access later
          $_SESSION['valid'] = true;
          $_SESSION['timeout'] = time();
          $_SESSION['username'] = $username;
          $_SESSION['userid'] = NULL;
          $_SESSION['employeetype'] = NULL;
          $_SESSION['pid'] = NULL;

          // Get the Query Results
          while ($row = $results->fetch_assoc()) {
            $_SESSION['userid'] = $row["UserID"];
            $_SESSION['employeetype'] = $row["EmployeeType"];
            $_SESSION['pid'] = $row["PID"];
          }

          switch($_SESSION['employeetype']){
            case "0":
              header('Location: php/patient_portal.php');
            break;

            case "1":
              //we have a doctor
              header('Location: php/healthcare_worker_portal.php');
            break;

            case "2":
              //we have a pharmacist
              header('Location: php/pharmacy_portal.php');
            break;

            default:
              echo "Error processing employee type";
          };
        }
      }
      ?>

      <!-- form for username imput-->
      <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                                                    ?>" method="post">
            <h4 class="form-signin-heading"><?php echo $msg; ?></h4>
            <input type="text" class="form-control" name="username" placeholder="username" required autofocus></br>
            <input type="password" class="form-control" name="password" placeholder="password" required>
            <button type="submit" name="login" class="loginbtn">Login</button>
        </form>

        <!-- form for password imput-->
        <form class="form-options" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                                                    ?>" method="post">
        </form>

        <div class="container">
          <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
        </div>
    </div>
  </form>
</div>


<!--modal called to display signup form-->
<div id="id02" class="modal">
  
  <form class="modal-content animate" method="post">
  <div class="imgcontainer">
    <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
    <div class="center">
      <h3>Create Account</h3>
    </div>
  </div>

  <div class="container">
  <section class="signup_area" id="signup_area">
    <form class="form-signup" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                                                  ?>" method="post">
      <div class = "container">
        <select name="select_user_type">
          <option name="user_type" value="null">Account Type:</option>
          <option name="user_type" value="0">Patient</option>
          <option name="user_type" value="1">Doctor/Nurse</option>
          <option name="user_type" value="2">Pharmacist</option>
        </select>
      </div>
      <input type="text" class="form-signup" name="pid" placeholder="Patient ID" required></br></br>
      <input type="text" class="form-signup" name="name_first" placeholder="First Name" required></br></br>
      <input type="text" class="form-signup" name="name_last" placeholder="Last Name" required></br></br>
      <input type="text" class="form-signup" name="username" placeholder="username" required></br></br>
      <input type="password" class="form-control" name="password" placeholder="password" required>
      <input type="password" class="form-control" name="retype_password" placeholder="retype password" required>
      <button class="loginbtn" type="submit" name="submit">submit
        <!-- If the submit button is pushed, we need to process the data. If successfull we need to redirect to the patient portal -->
        <?php 
          
          if (isset($_POST['submit'])) {
            if(trim($_POST['select_user_type']) == "null"){
              $msg="Please select an account type";
            }
            else{
              // Process the form information
              // Now we check if the password files match. Strip whitespace first
              if (trim($_POST['password']) == trim($_POST['retype_password'])) {
                // passwords match yay, lets process !
                $pid = trim($_POST['pid']);
                $name_first = trim($_POST['name_first']);
                $name_last = trim($_POST['name_last']);
                $password = trim($_POST['password']);
                $username = trim($_POST['username']);
                $account_type = trim($_POST['select_user_type']);

                // We retrieve and update data based on the PID, first lets check if the PID is valid
                // Query the PatientInfo database for the information
                $results = $conn->query("SELECT PID FROM PatientInfo WHERE PID='$pid' AND name_first='$name_first' AND name_last='$name_last'");

                // Check if results is NULL, if it is the PID doesn't exist
                if ($results == NULL) {
                  $msg = "Not a valid Patient ID, please contact tech support!";
                } 
                else {
                  // Create a random UserID. Max is unsigned int for mysql
                  $userid = rand(1, 4294967295);
                  // Check if it already exists in the table/DB.
                  $results = $conn->query("SELECT UserID FROM Users WHERE UserID='$userid'");
                  if ($results != NULL) {
                    // We need to insert into the Users table with the info provied
                    $results = $conn->query("INSERT INTO Users (UserID, PID, Username, UserPassword, EmployeeType) VALUES ('$userid','$pid', '$username', '$password', $account_type)");
                  if($results != NULL)
                    {
                      // If we get here we inserted into users successfully
                      header('Location: index.php');
                    }
                    else{
                      $msg = "error creating account please try again or contact support";
                    }
                  } 
                  else {
                    $msg = "We ran into an error please try again or contact support!";
                  }
                }
              }
              else {
              $msg = "password field did not match! Please try again!";
              }
            }  
          }
        ?>
      </button>
    </form>
  </section>
    <div class="container">
        <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </div>
  </form>
</div>

</div>

<!--JS to close modal window on click outside of window-->
<script>
  // Get the modal
  var modals = document.getElementsByClassName('modal');

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    for(i=0; i<modals.length;i++)
      if (event.target == modals[i]) {
          modals[i].style.display = "none";
      }
  }
</script>
<br>
  <?php echo "$msg" ?>
  <?php $conn->close(); ?>

</body>
</html>
