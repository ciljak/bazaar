<!-- ******************************************************************* -->
<!-- PHP "self" code GET request for remove and POST delete data         -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 17.10.2020 by CDesigner.eu                 -->
<!-- ******************************************************************* -->

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
	<title> Bazaar score - remove script </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar ">
      <div class="container" id="header_container_580">
        <div class="navbar-header">
          <?php
             require_once('headerlogo.php');
          ?>    
          <a class="navbar-brand" href="managecategory.php"><img id="next" src="./images/category_icon.png"> Bazaar category manager - part for Bazaar category management</a>
          <a class="navbar-brand" href="index.php"><img id="next" src="./images/next_icon.png"> return to main shop page</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?> <!-- alert showing part -->
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
       
      <br> <!-- logo on the center of the page -->
      <h4>Confirmation of deletion selected category removal.</h4>
      <br>

      <br> <!-- logo on the center of the page -->
        <img id="calcimage" src="./images/delicon.png" alt="Calc image" width="150" height="150">
      <br>

       
            
      <?php // code for GET info about what to remove and submit removing approval

        if(isset($_GET['subcategory_id']) && isset($_GET['category'])  ){
            // take a data from GET link generated by adminscript
            $subcategory_id = htmlspecialchars($_GET['subcategory_id']);
            $category = htmlspecialchars($_GET['category']);
            $subcategory = htmlspecialchars($_GET['subcategory']);
           

        } else if (isset($_POST['subcategory_id']) && isset($_POST['category']) && isset($_POST['subcategory'])) { //grab score from POST - different behavior for removal
            $subcategory_id = htmlspecialchars($_POST['subcategory_id']);
            $category = htmlspecialchars($_POST['category']);
            $subcategory = htmlspecialchars($_POST['subcategory']);

        }  else  { //error info message
            echo '<p class="alert alert-danger"> Please specify any category for removal. </p>';

        };

        if(isset($_POST['submit'])){
             
            if($_POST['confirm'] == 'Yes' ){ // delete appropriate score post with imagescreenshot
              //delete the screenshotimage from the 
              $subcategory_id = htmlspecialchars($_POST['subcategory_id']);
              $category  = htmlspecialchars($_POST['category ']);
              $subcategory = htmlspecialchars($_POST['subcategory']);
              

             


              // conect to the database
              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

              //Delete score data from the database
              $sql = "DELETE FROM bazaar_category WHERE subcategory_id = $subcategory_id LIMIT 1";
              // execute SQL
              mysqli_query($dbc, $sql);

              // close database connection
              mysqli_close($dbc);

              // confirm executed command
              echo '<p> The category <strong>' . $category . '</strong> with id <strong>' . $subcategory_id . '</strong> was succesfully removed. </p>';

           
            } else {
                echo  '<p class="alert alert-danger" > The selected category was not removed. </p>'; 
            }
        } else if (isset($subcategory_id) && isset($category)  ) {
            echo '<h5>Are you sure to delete the next category item from bazaar? </h5>'; 
            // show short describtion of score for deletion
            echo '<p> <strong> subcategory_id: </strong> ' . $subcategory_id .  '<br> <strong> Category: </strong>' . $category .
                 '<br> <strong> Subcategory: </strong>' . $subcategory .  
                 '</p>'; 
              
            //generating removing confirmation form      
            

            echo '<form method="POST" action="removecategory.php">';   //not self but direct this script removecategory.php - we dont want include any GET data tahat previously send
            echo '<center>';
            echo '<input type="radio" name="confirm" value="Yes" /> Yes   '; 
            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br><br>';  
            
            echo '<input type="hidden" name="subcategory_id" value="'.$subcategory_id.'"  />'; 
            echo '<input type="hidden"  name="category" value="'.$category.'"  />';
            echo '<input type="hidden" name="subcategory" value="'.$subcategory.'" />'; 
            echo '<input type="submit" class="btn btn-danger" value="submit" name="submit" />'; 
            echo '</center>'; 
            echo '</form>'; 


                
        };
        echo '<br><br>';
        echo  '<p> <a href = "managecategory.php"><img id="next" src="./images/previous_icon.png"> Back to category management page. </a></p>';

?>
	  

	  
		
		</div>

          
		
		
    <?php  // footer include code
      require_once('footer.php'); // including footer
      generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
    ?>  
		
      
</body>
</html>