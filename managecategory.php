<!-- ******************************************************************* -->
<!-- PHP "self" code handling adding adding category by admin of page    -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 11.10-XX.X.2020 by CDesigner.eu            -->
<!-- ******************************************************************* -->

<?php
    require_once('appvars.php'); // including variables for database
	// two variables for message and styling of the mesage with bootstrap
	$msg = '';
	$msgClass = '';

	// default values of auxiliary variables
	$category = "";
	$subcategory = "";
	
	$is_result = false; //before hitting submit button no result is available
	


	
        
    // If category with subcategory data was submitted
	if(filter_has_var(INPUT_POST,'subcategorysubmit')){
		// Data obtained from $_postmessage are assigned to local variables
        $subcategory = htmlspecialchars($_POST['subcategory']);
        $category = htmlspecialchars($_POST['category']);

        //echo "$category in subcategory debug ";
        //cho "$subcategory in subcategory debug ";
        
		
		
		

		// Controll if all required fields was written
		if(!empty($category && $subcategory) ){
			
            // add category into a databse - there will by fields without subcategory but they will by omited for showing

			// make database connection
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

			// Check connection
				if($dbc === false){
					die("ERROR: Could not connect to database. " . mysqli_connect_error());
				}
			
			
			   

			   // create INSERT query
			   $sql = "INSERT INTO bazaar_category (category, subcategory) 
						VALUES ('$category','$subcategory')";



				if(mysqli_query($dbc, $sql)){
					
					$msg = 'New category' . $category . 'sucesfully added into a bazaar_category table.';
					$msgClass = 'alert-success';

					// clear entry fileds after sucessfull deleting from database
					$category= "";
                   
                    $is_result = false; //before hitting submit button no result is available
				} else{
					
					$msg = "ERROR: Could not able to execute $sql. " . mysqli_error($dbc);
					$msgClass = 'alert-danger';
				}

			// end connection
				mysqli_close($dbc);

			}
			
		} else {
			// Failed - if not all fields are fullfiled
			$msg = 'Please fill in all * marked contactform fields';
			$msgClass = 'alert-danger'; // bootstrap format for allert message with red color
		};    

		
  
	

	
		
?>

<!-- **************************************** -->
<!-- HTML code containing Form for submitting -->
<!-- **************************************** -->
<!DOCTYPE html>
<html>
<head>
	<title> bazaar - category management  </title>
	<link rel="stylesheet" href="./css/bootstrap.min.css"> <!-- bootstrap mini.css file -->
	<link rel="stylesheet" href="./css/style.css"> <!-- my local.css file -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	
</head>
<body>
	<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">    
          <a class="navbar-brand" href="index.php">Bazaar - management of selling category</a>
        </div>
      </div>
    </nav>
    <div class="container" id="formcontainer">	
		
    	
	  <?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>	
        
        <br> 
        <img id="calcimage" src="./images/addicon.png" alt="Calc image" width="150" height="150">
        <br>

     
          
          <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="form-group">
		      <label>* Set name for new subcategory:</label>
		      <input type="text" onfocus="this.value='<?php echo isset($_POST['subcategory']) ? $subcategory : ''; ?>'" name="subcategory" class="form-control" value="<?php echo isset($_POST['subcategory']) ? $subcategory : 'Please provide name of new subcategory'; ?>">
              <br> 
              <label>* Select main category for nesting created subcategory:</label>
		      <input list="category" name="category" >
                <datalist id="category">
					<?php // here read data from mysql bazaar_category and display existing category whre subcategory will be nested
					 	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

						    // Check connection
							 if($dbc === false){
								 die("ERROR: Could not connect to database. " . mysqli_connect_error());
							 };
						 
						 
							
			 
							// create SELECT query for category names from database
							$sql = "SELECT DISTINCT category FROM bazaar_category ORDER BY category ASC";

							// execute sql and populate data list with existing category in database
							if($output = mysqli_query($dbc, $sql)){
								if(mysqli_num_rows($output) > 0){  // if any record obtained from SELECT query
									
									while($row = mysqli_fetch_array($output)){ //next rows outputed in while loop
									
											echo "<option value=" . $row['category'] . ">";
											
											
									
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
              <br> 
			  
              <button type="submit" name="subcategorysubmit" class="btn btn-warning"> Create new subcategory </button>
			  <input type="reset" class="btn btn-info" value="Reset">			  
	      </div>
          <hr> 
          </form> 
         	 
		  

		  
		  
		  
		  
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


    
            
// read all rows (data) from guestbook table in "test" database
$sql = "SELECT * FROM bazaar_category ORDER BY category ASC, subcategory ASC";  // read in reverse order of score - highest score first
/*************************************************************************/
/*  Output in Table - solution 1 - for debuging data from database       */
/*************************************************************************/
// if data properly selected from guestbook database tabele

echo "<h4>List of active categories and subcategories</h4>";
echo "<br>";
echo ' <button class="btn btn-secondary btn-lg " onclick="location.href=\'admin.php\'" type="button">  admin page -> </button>';

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
	  

	  
		
		</div>

          
		
		
	   <div class="footer"> 
          <a class="navbar-brand" href="https://cdesigner.eu"> Visit us on CDesigner.eu </a>
		</div>
		
      
</body>
</html>