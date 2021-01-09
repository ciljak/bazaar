<!-- ******************************************************************* -->
<!-- PHP "self" code GET request for remove from cart                    -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 2.11.2020 by CDesigner.eu                  -->
<!-- ******************************************************************* -->

<?php // leading part of page for simple header securing and basic variable setup
    require_once('appvars.php'); // including variables for database
    session_start(); // start the session - must be added on all pages for session variable accessing

	// solution using SESSIONS with COOKIES for longer (30days) login persistency
    
  if(!isset($_SESSION['users_id'])) { // if session is no more active
		if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
			$_SESSION['users_id'] = $_COOKIE['users_id'];
            $_SESSION['username'] = $_COOKIE['username'];
            $_SESSION['user_role'] = $_COOKIE['user_role']; // added for role
		}
	 }
   
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	
?>

<!-- ******************************************* -->
<!-- script for removing item from cart          -->
<!-- ******************************************* -->
<!-- obtain GET data from cart.php and trough    -->
<!-- POST submit remove goods from cart by       -->
<!-- seting cart_number filed to 0 - notasigned  -->
<!-- to any user                                 -->
<!-- ******************************************* -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar remove from cart - remove script </title>
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
          <a class="navbar-brand" href="cart.php"><img id="next" src="./images/cart.png"> Return to your shopping cart</a>
          <a class="navbar-brand" href="index.php"><img id="next" src="./images/next_icon.png"> return to main shop page</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?> <!-- alert showing part -->
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
       
      <br> <!-- logo on the center of the page -->
      <h4>Confirmation of removal item from cart.</h4>
      <br>

      <br> <!-- logo on the center of the page -->
        <img id="calcimage" src="./images/delicon.png" alt="del image" width="150" height="150">
      <br>

       
            
      <?php // code for GET info about what to remove and submit removing approval

        if(isset($_GET['cart_number']) && isset($_GET['item_id']) && isset($_GET['name_of_item']) ){
            // take a data from GET link generated by adminscript
            $cart_number = htmlspecialchars($_GET['cart_number']);
            $item_id = htmlspecialchars($_GET['item_id']);
            $name_of_item = htmlspecialchars($_GET['name_of_item']);
           
           

        } else if (isset($_POST['cart_number']) && isset($_POST['item_id']) && isset($_POST['name_of_item'])) { //grab score from POST - different behavior for removal
            $cart_number = htmlspecialchars($_POST['cart_number']);
            $item_id = htmlspecialchars($_POST['item_id']);
            $name_of_item = htmlspecialchars($_POST['name_of_item']);
          
        }  else  { //error info message
            echo '<p class="alert alert-danger"> Please specify any cart item for removal. </p>';

        };

        if(isset($_POST['submit'])){
             
            if($_POST['confirm'] == 'Yes' ){ // delete appropriate score post with imagescreenshot
              //delete the screenshotimage from the 
              $cart_number = htmlspecialchars($_POST['cart_number']);
              $item_id  = htmlspecialchars($_POST['item_id']);
              $name_of_item  = htmlspecialchars($_POST['name_of_item']);
             

             


              // conect to the database
              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

              //Delete score data from the database
              $sql = "UPDATE bazaar_item SET cart_number = '0' WHERE item_id = $item_id LIMIT 1";
              // execute SQL
              mysqli_query($dbc, $sql);

              // close database connection
              mysqli_close($dbc);

              // confirm executed command
              echo '<p> The item ' . $name_of_item . ' with id<strong>' . $item_id . '</strong> was sucesfully removed from your cart and now is available in listening for sell
                    for another user. </p>';

           
            } else {
                echo  '<p class="alert alert-danger" > The selected item cannot be removed. </p>'; 
            }
        } else if (isset($cart_number) && isset($item_id) && isset($name_of_item) ) {
            echo '<h5>Are you sure to remove ' . $name_of_item . ' from your cart? Item will be set for sell listening and can be bought by another user.</h5>'; 
            // show short describtion of score for deletion
            echo '<p> <strong> item_id: </strong> ' . $item_id .  '<br> <strong> item name is: </strong>' . $name_of_item .
                 
                 '</p>'; 
              
            //generating removing confirmation form      
            

            echo '<form method="POST" action="removefromcart.php">';   //not self but direct this script removecategory.php - we dont want include any GET data tahat previously send

            echo '<center>';
            echo '<input type="radio" name="confirm" value="Yes" /> Yes   '; 
            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br><br>';  
            
            echo '<input type="hidden" name="cart_number" value="'.$cart_number.'"  />'; 
            echo '<input type="hidden"  name="item_id" value="'.$item_id.'"  />';
            echo '<input type="hidden" name="name_of_item" value="'.$name_of_item.'" />'; 
            echo '<input type="submit" class="btn btn-danger" value="submit" name="submit" />'; 
            echo '</center>'; 
            echo '</form>'; 


                
        };
        echo '<br><br>';
        echo  '<p> <a href = "cart.php"><img id="next" src="./images/previous_icon.png"> Back to your cart. </a></p>';

?>
	  

	  
		
		</div>

          
		
		
    <?php  // footer include code
      require_once('footer.php'); // including footer
      generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
    ?>  
		
      
</body>
</html>