<!-- ******************************************************************* -->
<!-- PHP  code generating verification captcha image                     -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 8. - 9.11.2020 by CDesigner.eu             -->
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
     
 ?>    