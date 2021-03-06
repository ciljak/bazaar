<!-- ***************************************************************************** -->
<!-- PHP "self" code GET request for user management                               -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 29.111.2020 by CDesigner.eu                           -->
<!-- ***************************************************************************** -->

<?php // leading part of page for simple header securing and basic variable setup
    require_once('appvars.php'); // including variables for database

    session_start(); // start the session - must be added on all pages for session variable accessing

	// solution using SESSIONS with COOKIES for longer (30days) login persistency
    
  if(!isset($_SESSION['users_id'])) { // if session is no more active
		if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
			$_SESSION['users_id'] = $_COOKIE['users_id'];
			$_SESSION['username'] = $_COOKIE['username'];
		}
	 }
   
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	
?>

<!-- ******************************************* -->
<!-- script for appropriate scode removal        -->
<!-- ******************************************* -->
<!-- obtain GET data from admin.php and trough   -->
<!-- POST submit remove data from database       -->
<!-- ******************************************* -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar user - update/delete/remove userscript </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
       
</head>
<body>
	<nav class="navbar t">
      <div class="container" id="header_container_580">
        <div class="navbar-header">   
          <?php
             require_once('headerlogo.php');
          ?> 
          <a class="navbar-brand" href="admin.php"><img id="next" src="./images/next_icon.png"> Bazaar admin page</a>
          <a class="navbar-brand" href="index.php"><img id="next" src="./images/next_icon.png">  Return to main shop page</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?> <!-- alert showing part -->
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
       
      <br> <!-- logo on the center of the page -->
      <h4>Please select what you will do.</h4>
      <br>
      <!-- remove after finalize page 
      <br> 
        <img id="calcimage" src="./images/workinprogress.png" alt="admin image" width="150" height="150">
      <br>
      -->

      <br> <!-- logo on the center of the page -->
        <img id="calcimage" src="./images/default_avatar.png" alt="admin image" width="150" height="150">
      <br>

       
            
      <?php // code for GET info about what to remove and submit removing approval

      /* structure of generated link on admin.php page for further reference
       echo '<td colspan="1"><a id="DEL" href="removeitem.php?item_id='.$row['item_id'] . '&amp;name_od_item='
                         . $row['name_of_item'] . '&amp;price_eur='. $row['price_eur'] .
                         '&amp;published='. $row['published'] . '&amp;screenshot1='. $row['screenshot1'] .
                         '&amp;screenshot2='. $row['screenshot2'] . '&amp;screenshot3='. $row['screenshot3'] . '"> >>Publish/UnPub./Remove  </a></td></tr>';
      */

        if(isset($_GET['users_id']) && isset($_GET['username']) && isset($_GET['user_role'])){
            // take a data from GET link generated by adminscript
            $users_id = htmlspecialchars($_GET['users_id']);
            $username = htmlspecialchars($_GET['username']);
            $user_role = htmlspecialchars($_GET['user_role']);

            // read other data relevant to user from database and assign them into a variable 
                  // conect to the database
                  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

                  // obtain all previous data about user
                    // create SELECT query for category names from database
                    $sql = "SELECT * FROM bazaar_user WHERE username = "."'$username'"." AND users_id="."'$users_id'" ;

                    // execute sql and populate data list with existing category in database
                    if($output = mysqli_query($dbc, $sql)){
                      if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
                        
                        while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                        
                                                  
                                                // need improvement $pass_word = $row['pass_word'];
                                                  $nickname= $row['nickname'];
                                                  $first_name = $row['first_name'];
                                                  $lastname_name = $row['lastname_name'];
                                                  $addresss = $row['addresss'];
                                                  $city = $row['city'];
                                                  $ZIPcode = $row['ZIPcode'];
                                                  $email = $row['email'];
                                                  $gdpr = $row['GDPR_accept']; // checkbox doesnot send post data, they must be checked for its set state !!!
                                                  $rules_accept = $row['rules_accept'];
                                              
                                                  $avatar = $row['avatar'];           // photo location of avatar
                                                  $profile_text = $row['profile_text'];
                            
                            
                        
                        }
                        
                        // Free result set
                        mysqli_free_result($output);
                      } else{
                        echo "There is no category in category table. Please wirite one."; // if no records in table
                      }
                    } else{
                      echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
                    }
           
           

        } else if (isset($_POST['users_id']) && isset($_POST['username']) && isset($_POST['user_role']) ) { //grab score from POST - different behavior for removal
            
            $users_id = htmlspecialchars($_POST['users_id']);
            $username = htmlspecialchars($_POST['username']);
            $user_role = htmlspecialchars($_POST['user_role']);
            
           

        }  else  { //error info message
            echo '<p class="alert alert-danger"> Please specify any category for removal. </p>';

        };

        if(isset($_POST['submit'])){
             
            if($_POST['confirm'] == 'Yes' ){ // delete appropriate score post with imagescreenshot
              //read all data from $_POST array
              $users_id = htmlspecialchars($_POST['users_id']);
              $username = htmlspecialchars($_POST['username']);
              $user_role = htmlspecialchars($_POST['user_role']);

              $operation = htmlspecialchars($_POST['operation']);



             
              // conect to the database
              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

             

              //create sql query along selected operation
              switch ($operation) {
                case "make_admin":
                    $sql = "UPDATE bazaar_user SET user_role = 'admin' WHERE users_id = $users_id LIMIT 1";
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    // confirm executed command
                    echo '<p> User <strong>' . $username . '</strong> with id <strong>' . $users_id . '</strong> was succesfully promoted as page <b>admin<b>. </p>';
                    break;
                case "make_user":
                  $sql = "UPDATE bazaar_user SET user_role = 'user' WHERE users_id = $users_id LIMIT 1";
                  // execute SQL
                  mysqli_query($dbc, $sql);
                  // confirm executed command
                  echo '<p> User <strong>' . $username . '</strong> with id <strong>' . $users_id . '</strong> was succesfully promoted as page <b>user<b>. </p>';
                  break;     
  /* to do */  case "change_user_data": // --> make solution for update other users data - link profile page for another user

                    // next part of code redirect to manageuserdata.php page for administration of appropriate users data by admin
                    
                    ?>
                       <script type = "text/javascript">
                          
                              function Redirect() {
                                window.location = "./manageuserdata.php?users_id=<?php echo $users_id ?>&username=<?php echo $username ?>"; // send users_id of user to edit must send to manageuserdata.php to be able edit appropriate user and not user extracted from session informatin
                              }            
                              document.write("You will be redirected to manageuserdata.php page.");
                              setTimeout('Redirect()', 0);
                          
                      </script>

                    <?php
                    

                    break;
                case "delete_user":
                    $sql = "DELETE FROM bazaar_user WHERE users_id = $users_id LIMIT 1";
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    // confirm executed command
                    echo '<p> The user <strong>' . $username . '</strong> with id <strong>' . $users_id . '</strong> was succesfully <b>deleted<b> from list of users. </p>';
                    @unlink(IMAGE_PATH . $avatar); //delete image file
                 
                    break;
            }
              
             

              // close database connection
              mysqli_close($dbc);

             

           
            } else {
                echo  '<p class="alert alert-danger" > The selected operation cannot be performed. </p>'; 
            }
        } else if (isset($users_id) && isset($username) && isset($user_role)  ) {
         
            echo '<h5>Are you sure perform selected operation with bazaar user? </h5>'; 
            // show short describtion of score for deletion
            $image_location = IMAGE_PATH.$avatar;
            echo '<p> <strong> User ID: </strong> ' . $users_id .  '<br> <strong> Name: </strong>' . $username .
                  
                 
                           
                 '</p>'; 
            echo " <center><img src=\"$image_location\" alt=\" score image \"  height=\"150\"> </center>";
            echo '<br>';
              
            //generating removing confirmation form      
            

            echo '<form method="POST" action="manageuser.php">';   //not self but direct this script removecategory.php - we dont want include any GET data tahat previously send
            echo '<h4> Please select your operation </h4>';

            echo '<center><input list="operation" name="operation" placeholder="click to select option" ><center>';
            echo '<datalist id="operation">';
            echo '<option value="make_admin">';
            echo '<option value="make_user">';
            echo '<option value="change_user_data">';
            echo '<option value="delete_user">';
            echo '</datalist>';
         

            echo '<br><br>';

            
            echo '<center><input type="radio" name="confirm" value="Yes" /> Yes   '; 
            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <center><br><br>';  
            
            // template for hiden value submitting echo '<input type="hidden" name="item_id" value="'.$item_id.'"  />';
            echo '<input type="hidden" name="users_id" value="'.$users_id.'"  />'; 
            echo '<input type="hidden" name="username" value="'.$username.'"  />'; 
            echo '<input type="hidden" name="user_role" value="'.$user_role.'"  />'; 
          
            echo '<center><input type="submit" class="btn btn-danger" value="submit" name="submit" /></center>'; 
            echo '</form>'; 


                
        };
        echo '<br><br>';
        echo  '<p> <a href = "admin.php"><img id="next" src="./images/previous_icon.png"> Back to admin  page. </a></p>';

?>
	  

	  
		
		</div>

          
		
		
    <?php  // footer include code
      require_once('footer.php'); // including footer
      generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
    ?>  
		
      
</body>
</html>