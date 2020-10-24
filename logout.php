<!-- ******************************************************************* -->
<!-- PHP "self" code handling logout procedure into the bazaar app       -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 24.10-24.10.2020 by CDesigner.eu           -->
<!-- ******************************************************************* -->

<?php
 require_once('appvars.php'); // including variables for database
    // logout user by deleting cookie 
    
    if(isset($_COOKIE['user_id'])) {
        setcookie('user_id','',time() - 3600);
        setcookie('username','',time() - 3600);
        echo "deleted cookies";
               
    };

    // redirect to homepage in logout state
    $home_url = 'http://'. $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    header('Location:'. $home_url);

 ?>