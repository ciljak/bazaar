<!-- ******************************************************************* -->
<!-- PHP "self" code handling managenet of bazaar portal                 -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 18.10-XX.X.2020 by CDesigner.eu            -->
<!-- ******************************************************************* -->

<?php
    require_once('appvars.php'); // including variables for database
    // two variables for message and styling of the mesage with bootstrap
    session_start(); // start the session - must be added on all pages for session variable accessing

	// solution using SESSIONS with COOKIES for longer (30days) login persistency
    
    if(!isset($_SESSION['users_id'])) { // if session is no more active
		if(isset($_COOKIE['users_id']) && isset($_COOKIE['username'])) { // but cookie is set then renew session variables along them
			$_SESSION['users_id'] = $_COOKIE['users_id'];
            $_SESSION['username'] = $_COOKIE['username'];
            $_SESSION['user_role'] = $_COOKIE['user_role']; // added for role
		}
	 }
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$category = "";
	$subcategory = "";
	
	$is_result = false; //before hitting submit button no result is available
	
	
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> bazaar - page administration </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
        <?php // generate menu if user is loged in or not
		 // old solution with cookies if(isset($_COOKIE['username'])) { // loged in user
			if(isset($_SESSION['username'])) { // loged in user
				echo '<a class="navbar-brand" href="index.php">Bazaar - best items for a best prices!</a>';
				echo '<a class="navbar-brand" href="editprofile.php"> Edit profile </a>';
				echo '<a class="navbar-brand" href="logout.php"> Logout ' .$_SESSION['username'] .'</a>';
				if(isset($_SESSION['user_role'])=='admin') { // if oged user is admin role
				   echo '<a class="navbar-brand" href="admin.php"> Manage your page </a>';
               };
               require_once('sell_icon.php'); // graphic menu item for selling your items
               require_once('cart_icon.php'); // small cart icon in menu
			 } else { // visitor without login
			   echo '<a class="navbar-brand" href="login.php"> Log In </a>';
			   echo '<a class="navbar-brand" href="signup.php"> Sign Up for better membership! </a>';
   			   echo '<a class="navbar-brand" href="index.php">Bazaar - best items for a best prices!</a>';
			}
		?>	 
        </div>
      </div>
    </nav>
    <div class="container" id="container_1060">	<!-- wider container for admin page - width 1060px - styled in style.css-->

    <br> 
	  <h4> Aministration of Bazaar app </h4>

<!-- *************************************************** -->
<!-- HTML part available after succesfull login as admin -->
<!-- *************************************************** -->		
<?php if(isset($_SESSION['users_id']) && ($_SESSION['user_role']=='admin')) { //if user is loged with users_id then editprofile form is available?> 		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/admin.png" alt="adminimage" width="150" height="150">
        <br>

     
          
         
         	 
		  

		  
		  
		  
		  
          <br><br>
		  
		  
		  <?php   //part displaying info after succesfull added subscriber into a mailinglist
				 if ($is_result ) {
					

						echo "<br> <br>";
						echo " <table class=\"table table-success\"> ";
						echo " <tr>
                               <td><h5> <em> Category: </em> $category with subcategory $subcategory </h5> <h5> has been succesfully added to category list </h5> ";
                                  
						
						  
						echo "	   <td>   </tr> "; 
						echo " </table> ";
					
					//echo " <input type="text" id="result_field" name="result_field" value="$result"  >  <br>" ;
				} ; 
				 ?>
                 <br>
		
	  </form>
      <?php // code showing all subscribers in form of a table at end of the page

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
}
// ----------------------------------------------------------------------------------- SHOWING ADMIN TABLES ----------------------------------
/********************************************************************************************/
/* I. Showing items for publish/unpublish and delete table with option for removal          */
/********************************************************************************************/   
// querying bazaar_category for listed category items            
// read all rows (data) from guestbook table in "test" database
$sql = "SELECT * FROM bazaar_item ORDER BY item_add_date DESC";  // read in reverse order of score - highest score first

// processing table output form bazaar_category
echo "<h4>I. Manage list of items for sell </h4>";
echo "<br>";
// buttons for access to other pages
echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'sellitem.php\'" type="button">  Create new item page -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    echo "<th>item_id</th>";
                    echo "<th>name</th>";
                    echo "<th>published?</th>";
                    echo "<th>date</th>";

					echo "<th>price</th>";
					echo "<th>category id</th>";
                    echo "<th>photo 1</th>";
                    echo "<th>photo 2</th>";
                    echo "<th>photo 3</th>";
                    echo "<th colspan=\"2\">Manage</th>";
                    
                    
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['item_id'] . "</td>";
                    echo "<td>" . $row['name_of_item'] . "</td>";
                    if ($row['published']) { // show if published - set 1 or waiting set to 0
                        echo "<td> ok-Published </td>";
                    } else {
                        echo "<td> X-waiting </td>";
                    }
                    
                    echo "<td>" . $row['item_add_date'] . "</td>";

                    echo "<td>" . $row['price_eur'] . " € </td>";
                   // echo "<td>" . $row['subcategory_id'] . "</td>";
                                /* convert category_id in to category and subcategory */
                                            $subcategory_id = $row['subcategory_id'];
                                            $category_idsupl	= "" ;
                                            $subcategory_idsupl	= "" ;
                                            // (*) -- conversion of category and subcategory into category%id
                                                
                                                // create SELECT query for category and subcategory names from database
                                                $sql_supl = "SELECT category, subcategory FROM bazaar_category WHERE subcategory_id = "."'$subcategory_id'" ;
                                                /*$output_supl = mysqli_query($dbc, $sql_supl);
                                                $row_supl = mysqli_fetch_array($output_supl);
                                                $category_id	= $row_supl['category'] ;
                                                $subcategory_id	= $row_supl['subcategory'] ;
                                                echo "<td>" . $category_id."/".$subcategory_id."</td>";*/
                                                // execute sql and populate data list with existing category in database
                                                if($output_supl = mysqli_query($dbc, $sql_supl)){
                                                    if(mysqli_num_rows($output_supl) > 0){  // if any record obtained from SELECT query
                                                        while($row_supl = mysqli_fetch_array($output_supl)){ //next rows outputed in while loop
                                                            
                                                            $category_idsupl	= $row_supl['category'] ;
                                                            $subcategory_idsupl	= $row_supl['subcategory'] ;
                                                            
                                                                
                                                        }
                                                        
                                                        
                                                        // Free result set
                                                        mysqli_free_result($output_supl);
                                                    } else {
                                                        echo "There is no souch category-subcategory in category table. Please correct your error."; // if no records in table
                                                    }
                                                } else{
                                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
                                                }

                                echo "<td>" . $category_idsupl."/".$subcategory_idsupl."</td>";


                    $image_location = IMAGE_PATH.$row['screenshot1'];
                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"95\"> </td>"; 
                    $image_location = IMAGE_PATH.$row['screenshot2'];
                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product second \"  height=\"95\"> </td>"; 
                    $image_location = IMAGE_PATH.$row['screenshot3'];
                    echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product third \"  height=\"95\"> </td>"; 

					 // removal line with removing link line
                
					 
					// echo "<td  colspan=\"1\"> Manage entry: </td>"; // description on first line
						 echo '<td colspan="1"><a id="DEL" href="removeitem.php?item_id='.$row['item_id'] . '&amp;name_of_item='
                         . $row['name_of_item'] . '&amp;price_eur='. $row['price_eur'] .
                         '&amp;published='. $row['published'] . '&amp;screenshot1='. $row['screenshot1'] .
                         '&amp;screenshot2='. $row['screenshot2'] . '&amp;screenshot3='. $row['screenshot3'] . '"> >>Publish/UnPub./Remove  </a></td></tr>'; //construction of GETable link
						 // for removecategory.php input
					
                    
                echo "</tr>";
                echo " </div> " ;
            }
            echo "</table>";
            echo "<br>";
            echo "<hr>";
            // Free result set
            mysqli_free_result($output);
        } else{
            echo "There is no benchmark result in chart. Please wirite one."; // if no records in table
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
    }


/*************************************************************************/
/*  II. Showing category/ subcategory table with option for removal          */
/*************************************************************************/   
// querying bazaar_category for listed category items            
// read all rows (data) from guestbook table in "test" database
$sql = "SELECT * FROM bazaar_category ORDER BY category ASC, subcategory ASC";  // read in reverse order of score - highest score first

// processing table output form bazaar_category
echo "<h4>II. Manage List of active categories and subcategories on store</h4>";
echo "<br>";
echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'managecategory.php\'" type="button">  Create new category-subcategory -> </button>';

echo "<br>"; echo "<br>";

    if($output = mysqli_query($dbc, $sql)){
        if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
            // create table output
            echo "<table>"; //head of table
                echo "<tr>";
                    echo "<th>subcategory_id</th>";
                    echo "<th>category</th>";
					echo "<th>subcategory</th>";
					echo "<th></th>";
					echo "<th>delete category</th>";
                    
                    
                    
                    
                echo "</tr>";
            while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
                echo " <div class=\"mailinglist\"> " ;
                echo "<tr>";
                    echo "<td>" . $row['subcategory_id'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
					echo "<td>" . $row['subcategory'] . "</td>";
					 // removal line with removing link line
                
					 
					 echo "<td  colspan=\"1\"> Manage entry: </td>"; // description on first line
						 echo '<td colspan="1"><a id="DEL" href="removecategory.php?subcategory_id='.$row['subcategory_id'] . '&amp;category='
						 . $row['category'] . '&amp;subcategory='. $row['subcategory'] .'"> >> Remove  </a></td></tr>'; //construction of GETable link
						 // for removecategory.php input
					
                    
                echo "</tr>";
                echo " </div> " ;
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($output);
        } else{
            echo "There is no benchmark result in chart. Please wirite one."; // if no records in table
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
    }



// Close connection
mysqli_close($dbc);
?>

<!-- ***************************************** -->
<!-- HTML part displayed for unloged user      -->
<!-- ***************************************** --> 
<?php } else { // else if user is not loged then form will noot be diplayed?>  
    <?php if(isset($_SESSION['users_id']) && $_SESSION['user_role']!='admin') {     ?>  
        <br> 
        <img id="calcimage" src="./images/logininvit.png" alt="Log in invitation" width="150" height="150">
        <br>
        <h4>You must by loged with administrator role for admin. Please<a class="navbar-brand" href="logout.php"><h4><u>logout</u></h4></a>and the login as admin.</h4>
        <br>
    <?php } else {     ?>  
        <br> 
        <img id="calcimage" src="./images/logininvit.png" alt="Log in invitation" width="150" height="150">
        <br>
        <h4>For further page administration please log in <a class="navbar-brand" href="login.php"> here. </a></h4>
        <br>
    <?php }      ?>   

<?php } ?>  
	  

	  
		
		</div>

          
		
		
	   <div class="footer"> 
          <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
		</div>
		
      
</body>
</html>