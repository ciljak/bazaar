<!-- ******************************************************************* -->
<!-- PHP "self" code handling login into the bazaar app                  -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 11.10-24.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 require_once('appvars.php'); // including variables for database
   
 // two variables for message and styling of the mesage with bootstrap
 $msg = '';
 $msgClass = '';
 $usr_username = '';
 $usr_passwd = '';

//get info that user is loged in, if not try it looking at cookies
if(!isset($_COOKIE['user_id'])) {
    if(isset($_POST['submit'])) {
        /* Attempt MySQL server connection.  */
             $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);
             
                // accessing user entered login data
             $usr_username = htmlspecialchars($_POST['u_name']);    
             $usr_passwd = htmlspecialchars($_POST['u_pass']);
             

             if(!empty($usr_username) && !empty($usr_passwd)) {
              // try lookup user database
              $usr_passwd_SHA = sha1($usr_passwd);
              $sql = "SELECT users_id, username FROM bazaar_user WHERE username = "."'$usr_username'". " AND pass_word = "."'$usr_passwd_SHA'" ;
              // debug output echo  $usr_username; 
              // echo  $usr_passwd;
              //echo $usr_passwd_SHA;
              $data = mysqli_query($dbc, $sql);   
              
              if(mysqli_num_rows($data) == 1) {
                  // login is ok, set user  ID and username cookies and redirect to the homepage
                  $row = mysqli_fetch_array($data);
                  setcookie('user_id', $row['user_id']);
                  setcookie('username', $row['username']);
                  $home_url = 'http://'. $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                  header('Location:'. $home_url);

                  // Free result set
				  mysqli_free_result($data);
                  // Close connection
                  mysqli_close($dbc);

              } else  {
                  // urename/ password are incorrect - error meesage is displayed
                  $msg = "Incorrect username or password. Login denied!  ";
				  $msgClass = 'alert-danger';
   
            }     

              
            } else {
                // username/ password were not entered - display error message
                $msg = "Sorry, you must eneter username and password to log in. ";
				$msgClass = 'alert-danger';
   
            }     
    }  

} 

?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar login page  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Bazaar - Login page</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
      <?php 
            if(empty($_COOKIE['user_id'])) { 
                echo ' <br> ';
    		echo  '<p class="alert alert-danger">' . $msg . '</p>';
       ?>	
        
        <br> 
        <img id="calcimage" src="./images/login.png" alt="bazaar image" width="150" height="150">
        <br>

        <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <div id="login">
                <legend> Log In <legend>
                <label>Username:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['u_name']) ? '' : ''; ?>'" name="u_name" class="form-control" value="<?php echo isset($_POST['u_name']) ? 'Please reenter' : 'Login name'; ?>">

                    <label>Password:</label>
                    <input type="password" onfocus="this.value='<?php echo isset($_POST['u_pass']) ? '' : ''; ?>'" name="u_pass" class="form-control" value="<?php echo isset($_POST['u_pass']) ? 'Please reenter' : 'Login name'; ?>">
            </div>
           <input id="loginsubmitt" type="submit" name="submit" class="btn btn-info" value="Log In"> 
           <br>

        </form>

        <?php }  else { 
                 // successfull login
                  echo '<p class="alert alert-success"> You are loged in as ' . $_COOKIE['username']. '</p>';
              } 
        ?>	


      </div>

          
		
		
<div class="footer"> 
   <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
 </div>
 

</body>
</html>