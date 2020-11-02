<!-- ******************************************************************* -->
<!-- PHP "self" code handling homepage of bazaar                         -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 17. - 18.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
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
	$name_of_item = "";
	$price_eur = "";
	$subcategory_id = "";
	$users_id = "";
	$item_add_date = "";
	$subcategory_id = "";
	$published = false;
	$screenshot1 = "";
	$screenshot2 = "";
	$screenshot3 = "";
    $item_description = '';
	$is_result = false; //before hitting submit button no result is available
	


	// Control if data was submitted
	if(filter_has_var(INPUT_POST, 'submit')) {
		// Data obtained from $_postmessage are assigned to local variables
	
		//echo 'users_id'; echo $users_id;
		
		$category_subcategory = htmlspecialchars($_POST['category_subcategory']); // must be converted to subcategory_id (*)
			// separate category and subcategory with strtok() function 
			$words = explode('-', $category_subcategory);
			$category=$words[0];
			//echo $category;
			//echo '<br>';
			$subcategory=$words[1];
			//echo $subcategory;
		
		
		

		// (*) -- conversion of category and subcategory into category%id
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

					// Check connection
					if($dbc === false){
						die("ERROR: Could not connect to database. " . mysqli_connect_error());
					};
				
				    
					

					// create SELECT query for category names from database
					$sql = "SELECT subcategory_id FROM bazaar_category WHERE category = "."'$category'". " AND subcategory = "."'$subcategory'" ;

					// execute sql and populate data list with existing category in database
					if($output = mysqli_query($dbc, $sql)){
						if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
							while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
								
								$subcategory_id	= $row['subcategory_id'] ;
								$is_result = true; // result can be shown
									
							}
							
							
							// Free result set
							mysqli_free_result($output);
						} else {
							echo "There is no souch category-subcategory in category table. Please correct your error."; // if no records in table
						}
					} else{
						echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
					}


					// Close connection
					mysqli_close($dbc);


		

		

	};	
  
	

	// if reset button clicked
	if(filter_has_var(INPUT_POST, 'reset')){
		$msg = '';
		$msgClass = ''; // bootstrap format for allert message with red color
		$name_of_item = "";
		$price_eur = "";
		$subcategory_id = "";
		$users_id = "";
		$item_add_date = "";
		$subcategory_id = "";
		$published = false;
		$screenshot1 = "";
		$screenshot2 = "";
		$screenshot3 = "";
		$item_description = '';
		$is_result = false;
		
	};
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> Bazaar app by CDesigner.eu  </title>
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
    <div class="container" id="container_1060">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/bazaar.png" alt="bazaar image" width="150" height="150">
        <br>

      <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
	      <div class="form-group">
		  <label>* Select category-subcategory of product for salle:</label>
		  <input list="category_subcategory" name="category_subcategory" placeholder="please select">
                <datalist id="category_subcategory"> <!-- must be converted in subcategory_id in script - marked with (*) -->
					<?php // here read data from mysql bazaar_category and display existing category whre subcategory will be nested
					 	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

						    // Check connection
							 if($dbc === false){
								 die("ERROR: Could not connect to database. " . mysqli_connect_error());
							 };
						 
						 
							
			 
							// create SELECT query for category names from database
							$sql = "SELECT DISTINCT category, subcategory FROM bazaar_category ORDER BY category ASC, subcategory ASC";

							// execute sql and populate data list with existing category in database
							if($output = mysqli_query($dbc, $sql)){
								if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
									
									while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
									
											echo "<option value=" . $row['category'] ."-".$row['subcategory'] . ">";
											
											
									
									}
									
									// Free result set
									mysqli_free_result($output);
								} else{
									echo "There is no category in category table. Please wirite one."; // if no records in table
								}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
							}

			 
			                // Close connection
                            mysqli_close($dbc);
                    ?>
                                     
                </datalist>
				<p> If no proper category-subcategory exist, please contact admin of the pages for creation them for you. </p>
              
			  

				<!-- users_id from session obtaining - for debuging and testing is set as hidden -->
				<input type="hidden" name="users_id" value="1">





			  
	      </div>
	      
         

			  
            
           

          <br><br>
		 
          
		  

		  

		  <!-- div class="form-group">
	      	<label>Your message for Guestbook:</label-->  <!-- textera for input large text -->
	      	<!-- textarea id="postmessage" name="postmessage" class="form-control" rows="6" cols="50"><?php echo isset($_POST['postmessage']) ? $postmessage : 'Your text goes here ...'; ?></textarea>
	      </div-->
	 
		  <button type="submit" name="submit" class="btn btn-warning"> Show me interesting things! </button>
		  <button type="submit" name="reset" class="btn btn-info"> Reset form </button>
		  
		 
		  
          <br><br>
		  	 
		
	  </form>
	  <?php // code showing all subscribers in form of a table at end of the page
	  
	  // show only if result is awaylable after category and subcategory of interesting items selected

			// Controll if all required fields was written
			if($is_result ) { 
				$is_result = false;
				if(!empty($category) && !empty($subcategory) ) { // these item identifiers are mandatory and can not be empty
					// If check passed - show only interesting items from sell listening
					/* Attempt MySQL server connection. Assuming you are running MySQL
							server with default setting (user 'root' with no password) */
							$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

							// Check connection
							if($dbc === false){
								die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
							}


								
										
							// read all rows (data) from guestbook table in "test" database
							$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND subcategory_id = "."'$subcategory_id'"." AND cart_number="."'0'"." ORDER BY item_id DESC";  // read in reverse order of score - highest score first
							                                       // must be piblished then set 1                        and is not added to somebody cart - cart_number is default 0                                     
							/*************************************************************************/
							/*  Output in Table - solution 1 - for debuging data from database       */
							/*************************************************************************/
							// if data properly selected from guestbook database tabele

							echo "<h4>List of items in selected category: $category / $subcategory </h4>";
							echo "<br>";
							//echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'unsubscribe.php\'" type="button">  Unsubscribe by e-mail -> </button>';

							echo "<br>"; echo "<br>";

								if($output = mysqli_query($dbc, $sql)){
									if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
										// create table output
										echo "<table>"; //head of table
											echo "<tr>";
												//echo "<th>id</th>";
												echo "<th>Name</th>";
												echo "<th>Price</th>";
												echo "<th>Category</th>";
												echo "<th>Screenshot1</th>";
												echo "<th>More info</th>";
												
												
											echo "</tr>";
										while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
											echo " <div class=\"mailinglist\"> " ;
											echo "<tr>";
												//echo "<td>" . $row['item_id'] . "</td>";
												echo "<td class=\"item_name\">" . $row['name_of_item'] . "</td>";
												echo "<td class=\"price\">" . $row['price_eur'] . " € </td>";
												echo "<td>" . $category."/".$subcategory."</td>";
												
													$image_location = IMAGE_PATH.$row['screenshot1'];
												echo "<td id=\"gray_under_picture\"> <img src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"250\"> </td>"; 
												echo '<td colspan="1"><a id="DEL" href="item.php?item_id='.$row['item_id']. '"> >> Visit item page  </a></td></tr>'; //construction of GETable link
											echo "</tr>";
											echo " </div> " ;
										}
										echo "</table>";
										// Free result set
										mysqli_free_result($output);
									} else{
										echo "There is no item for sell. Please add one."; // if no records in table
									}
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
								}



							// Close connection
							mysqli_close($dbc);
					
					
					};
			};						

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

// Check connection
if($dbc === false){
    die("ERROR: Could not connect to database - stage of article listing. " . mysqli_connect_error());
}

               
					
    
            
// read all rows (data) from guestbook table in "test" database
$sql = "SELECT * FROM bazaar_item WHERE published="."'1'"." AND cart_number="."'0'"." ORDER BY item_id DESC LIMIT 5";  // read in reverse order of score - highest score first
/*************************************************************************/
/*  Output in Table - solution 1 - for debuging data from database       */
/*************************************************************************/
// if data properly selected from guestbook database tabele
echo "<br><br>";
echo "<h4>Latest added items for you! </h4>";
echo "<br>";
//echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'unsubscribe.php\'" type="button">  Unsubscribe by e-mail -> </button>';

echo "<br>"; echo "<br>";

if($output = mysqli_query($dbc, $sql)){
	if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
		// create table output
		echo "<table>"; //head of table
			echo "<tr>";
				//echo "<th>id</th>";
				echo "<th>Name</th>";
				echo "<th>Price</th>";
				echo "<th>Category</th>";
				echo "<th>Screenshot1</th>";
				echo "<th>More info</th>";
				
				
			echo "</tr>";
		while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
			echo " <div class=\"mailinglist\"> " ;
			echo "<tr>";
				//echo "<td>" . $row['item_id'] . "</td>";
				echo "<td class=\"item_name\">" . $row['name_of_item'] . "</td>";
				echo "<td class=\"price\">" . $row['price_eur'] . " € </td>";

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
				echo "<td id=\"gray_under_picture\"> <img  src=\"$image_location\" alt=\" screenshot of product primary \"  height=\"250\"> </td>"; 
				echo '<td colspan="1"><a id="DEL" href="item.php?item_id='.$row['item_id']. '"> >> Visit item page  </a></td></tr>'; //construction of GETable link
			echo "</tr>";
			echo " </div> " ;
		}
		echo "</table>";
		// Free result set
		mysqli_free_result($output);
	} else{
		echo "There is no item for sell. Please add one."; // if no records in table
	}
} else{
	echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbc); // if database query problem
}


// Close connection
mysqli_close($dbc);
?>
	  

	  
		
		</div>

          
		
		
	   <div class="footer"> 
          <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
		</div>
		
      
</body>
</html>