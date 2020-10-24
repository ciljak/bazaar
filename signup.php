<!-- ******************************************************************* -->
<!-- PHP "self" code handling sign up for membership on the bazaar app   -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 24.10-24.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 require_once('appvars.php'); // including variables for database
   
 // two variables for message and styling of the mesage with bootstrap
 $msg = '';
 $msgClass = '';
 $u_name = '';
 $usr_passwd = '';
/* Attempt MySQL server connection.  */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

if(isset($_POST['submit'])) { 
    // obtaining submitted data from POST
    $u_name = htmlspecialchars($_POST['u_name']);
    $u_pass_1 = htmlspecialchars($_POST['u_pass_1']);
    $u_pass_2 = htmlspecialchars($_POST['u_pass_2']);
    $email = htmlspecialchars($_POST['email']);

    if(!empty($u_name) && !empty($email) && !empty($u_pass_1) && !empty($u_pass_2) && ($u_pass_1 = $u_pass_2)) {
     // make sure that username is available and is not registered for someone else
     $sql = "SELECT * FROM bazaar_user WHERE username = "."'$u_name'" ;
     $data = mysqli_query($dbc, $sql);   
 
       if(mysqli_num_rows($data) == 0) {
           // username is unique and have not been used by any previous user
           $usr_passwd_sha1 =  sha1($u_pass_2);
           $sql = "INSERT INTO bazaar_user (username, pass_word, write_date, email, nickname) 
                   VALUES ('$u_name', '$usr_passwd_sha1' , now(), '$email','$u_name')"; // by default nickname and username are the same, next user can change

           if(mysqli_query($dbc, $sql)){
            $msg = ' Your new account has been created successfully. 
            You are now ready to <a href="login.php">log in</a>';
            $msgClass = 'alert-success';
           } else{
               echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
           }
           //success confirmation for registered user
         
           

            // Free result set
			mysqli_free_result($data);
            // Close connection
            
            //exit(); if used blank page will be displayed without any other redirecting

       } else { // an account already exists for this username, so display an error message
           
            $msg = ' An account for submitted username already exsts. Please use different username ...';
            $msgClass = 'alert-danger';
       } 
    } else {
     
            $msg = ' Your must enter all of the required data, including contact e-mail address.';
            $msgClass = 'alert-danger';
    }
      
}   
    // Close connection 
    mysqli_close($dbc);    


?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar signup page  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Bazaar - Signup for submitting/ buying your items</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
        
    <?php if($msg != ''): ?>
        <br> 
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
         
    	
     	
        
        <br> 
        <img id="calcimage" src="./images/login.png" alt="bazaar image" width="150" height="150">
        <br>

        <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <div id="login">
                <legend> Please register for Bazaar membership <legend>
                    <label>Username:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['u_name']) ? $u_name : ''; ?>'" name="u_name" class="form-control" value="<?php echo isset($_POST['u_name']) ? $u_name : 'Login name'; ?>">

                    <label>e-mail:</label>
                    <input type="text" onfocus="this.value='<?php echo isset($_POST['email']) ? $email : ''; ?>'" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $email : '@'; ?>">


                    <label>Password:</label>
                    <input type="password" onfocus="this.value='<?php echo isset($_POST['u_pass_1']) ? '' : ''; ?>'" name="u_pass_1" class="form-control" value="<?php echo isset($_POST['u_pass_1']) ? '' : ''; ?>">

                    <label>Password:</label>
                    <input type="password" onfocus="this.value='<?php echo isset($_POST['u_pass_2']) ? '' : ''; ?>'" name="u_pass_2" class="form-control" value="<?php echo isset($_POST['u_pass_2']) ? '' : ''; ?>">
            </div>
           <input id="loginsubmitt" type="submit" name="submit" class="btn btn-info" value="Sign In"> 
           <br>

        </form>

       


      </div>

          
		
		
<div class="footer"> 
   <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
 </div>
 

</body>
</html>