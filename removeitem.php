<!-- ***************************************************************************** -->
<!-- PHP "self" code GET request for remove andable or disable product item        -->
<!-- ***************************************************************************** -->
<!-- Vrsion: 1.0        Date: 18.10.2020 by CDesigner.eu                           -->
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
	<title> Bazaar score - publish/unpublish/remove item script </title>
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
          <a class="navbar-brand" href="admin.php"> --> Bazaar admin page</a>
          <a class="navbar-brand" href="index.php"> --> return to main shop page</a>
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

      <br> <!-- logo on the center of the page -->
        <img id="calcimage" src="./images/admin.png" alt="admin image" width="150" height="150">
      <br>

       
            
      <?php // code for GET info about what to remove and submit removing approval

      /* structure of generated link on admin.php page for further reference
       echo '<td colspan="1"><a id="DEL" href="removeitem.php?item_id='.$row['item_id'] . '&amp;name_od_item='
                         . $row['name_of_item'] . '&amp;price_eur='. $row['price_eur'] .
                         '&amp;published='. $row['published'] . '&amp;screenshot1='. $row['screenshot1'] .
                         '&amp;screenshot2='. $row['screenshot2'] . '&amp;screenshot3='. $row['screenshot3'] . '"> >>Publish/UnPub./Remove  </a></td></tr>';
      */

        if(isset($_GET['item_id']) && isset($_GET['name_of_item']) && isset($_GET['price_eur']) && isset($_GET['published']) && isset($_GET['screenshot1'])){
            // take a data from GET link generated by adminscript
            $item_id = htmlspecialchars($_GET['item_id']);
            $name_of_item = htmlspecialchars($_GET['name_of_item']);
            $price_eur = htmlspecialchars($_GET['price_eur']);
            $published = htmlspecialchars($_GET['published']);
            $screenshot1 = htmlspecialchars($_GET['screenshot1']);
            $screenshot2 = htmlspecialchars($_GET['screenshot2']);
            $screenshot3 = htmlspecialchars($_GET['screenshot3']);
           

        } else if (isset($_POST['item_id']) && isset($_POST['name_of_item']) && isset($_POST['price_eur']) && isset($_POST['published']) && isset($_POST['screenshot1'])) { //grab score from POST - different behavior for removal
            
            $item_id = htmlspecialchars($_POST['item_id']);
            $name_of_item = htmlspecialchars($_POST['name_of_item']);
            $price_eur = htmlspecialchars($_POST['price_eur']);
            $published = htmlspecialchars($_POST['published']);
            $screenshot1 = htmlspecialchars($_POST['screenshot1']);
            $screenshot2 = htmlspecialchars($_POST['screenshot2']);
            $screenshot3 = htmlspecialchars($_POST['screenshot3']);
           

        }  else  { //error info message
            echo '<p class="alert alert-danger"> Please specify any category for removal. </p>';

        };

        if(isset($_POST['submit'])){
             
            if($_POST['confirm'] == 'Yes' ){ // delete appropriate score post with imagescreenshot
              //read all data from $_POST array
              $item_id = htmlspecialchars($_POST['item_id']);
              $name_of_item = htmlspecialchars($_POST['name_of_item']);
              $price_eur = htmlspecialchars($_POST['price_eur']);
              $published = htmlspecialchars($_POST['published']);
              $screenshot1 = htmlspecialchars($_POST['screenshot1']);
              $screenshot2 = htmlspecialchars($_POST['screenshot2']);
              $screenshot3 = htmlspecialchars($_POST['screenshot3']);

              $operation = htmlspecialchars($_POST['operation']);



             


              // conect to the database
              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

              //create sql query along selected operation
              switch ($operation) {
                case "publish":
                    $sql = "UPDATE bazaar_item SET published = '1' WHERE item_id = $item_id LIMIT 1";
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    // confirm executed command
                    echo '<p> The item <strong>' . $name_of_item . '</strong> with id <strong>' . $item_id . '</strong> was succesfully published. </p>';
                    break;
                case "unpublish":
                    $sql = "UPDATE bazaar_item SET published = '0' WHERE item_id = $item_id LIMIT 1";
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    // confirm executed command
                    echo '<p> The item <strong>' . $name_of_item . '</strong> with id <strong>' . $item_id . '</strong> was succesfully unpublished. </p>';
                    break;
                case "delete":
                    $sql = "DELETE FROM bazaar_item WHERE item_id = $item_id LIMIT 1";
                    // execute SQL
                    mysqli_query($dbc, $sql);
                    // confirm executed command
                    echo '<p> The item <strong>' . $name_of_item . '</strong> with id <strong>' . $item_id . '</strong> was succesfully deleted from listening on bazaar. </p>';
                    @unlink(IMAGE_PATH . $screenshot1); //delete image file
                    @unlink(IMAGE_PATH . $screenshot2);
                    @unlink(IMAGE_PATH . $screenshot3);
                    break;
            }
              
             

              // close database connection
              mysqli_close($dbc);

             

           
            } else {
                echo  '<p class="alert alert-danger" > The selected operation cannot be performed. </p>'; 
            }
        } else if (isset($item_id) && isset($price_eur) && isset($name_of_item) && isset($published) && isset($screenshot1) ) {
            echo '<h5>Are you sure perform selected operation with bazaar item? </h5>'; 
            // show short describtion of score for deletion
            $image_location = IMAGE_PATH.$screenshot1;
            echo '<p> <strong> Item_id: </strong> ' . $item_id .  '<br> <strong> Name: </strong>' . $name_of_item .
                  
                 
                           
                 '</p>'; 
        echo " <img src=\"$image_location\" alt=\" score image \"  height=\"150\"> ";
              
            //generating removing confirmation form      
            

            echo '<form method="POST" action="removeitem.php">';   //not self but direct this script removecategory.php - we dont want include any GET data tahat previously send
            echo '<h4> Please select your operation </h4>';

            echo '<input list="operation" name="operation" placeholder="select" >';
            echo '<datalist id="operation">';
            echo '<option value="publish">';
            echo '<option value="unpublish">';
            echo '<option value="delete">';
            echo '</datalist>';
         

            echo '<br><br>';

            
            echo '<input type="radio" name="confirm" value="Yes" /> Yes   '; 
            echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br><br>';  
            
            echo '<input type="hidden" name="item_id" value="'.$item_id.'"  />'; 
            echo '<input type="hidden" name="price_eur" value="'.$price_eur.'"  />';
            echo '<input type="hidden" name="name_of_item" value="'.$name_of_item.'" />'; 
            echo '<input type="hidden" name="published" value="'.$published.'" />'; 
            echo '<input type="hidden" name="screenshot1" value="'.$screenshot1.'" />'; 
            echo '<input type="hidden" name="screenshot2" value="'.$screenshot2.'" />'; 
            echo '<input type="hidden" name="screenshot3" value="'.$screenshot3.'" />'; 
            echo '<input type="submit" class="btn btn-danger" value="submit" name="submit" />'; 
            echo '</form>'; 


                
        };
        echo '<br><br>';
        echo  '<p> <a href = "admin.php"> &lt;&lt Back to admin  page. </a></p>';

?>
	  

	  
		
		</div>

          
		
		
    <?php  // footer include code
      require_once('footer.php'); // including footer
      generate_footer(580); // function from footer.php for seting width, you can use 580 and 1060px width
    ?>  
		
      
</body>
</html>