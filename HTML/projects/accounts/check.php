<?php
include_once("../../config/db_config.php");
include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

//from login username field
// from login password field

//session_start();            // Start the PHP session 
//   session_regenerate_id();    // regenerated the session, delete the old one. 
//if(session_id() == '' || !isset($_SESSION)) {
// session isn't started
//}
$_SESSION['user_is_logged_in'] = false;         
//there seems to be a problem with the line below
if(isset($_POST['submit'],$_POST['username'], $_POST['password'])) {
   $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
   $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

   if(is_null($name))
   {
      $message = "Name is null";
      $_SESSION['Error']=$message;
   }
   else if(is_null($password))
   {
      $message = "Password is null";
      $_SESSION['Error']=$message;
   }
   else
   {
      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
      $name = preg_replace("/[^a-zA-Z0-9_\-]+/","",$name);
      $name= $mysqli->escape_string($name);

      //purifier broken
      //using html purifier
      //$name = $purifier->purify($name);

      /* check connection */
      if (mysqli_connect_errno()) 
      {
         $message = "Connect: failed";
         $_SESSION['Error']=$message;
         echo "No";
      }
      
      else if(login($name,$password,$mysqli))
      {
         // Login success 
         $_SESSION['user_name'] = $name;
         $_SESSION['user_is_logged_in'] = true;         
        // $_SESSION['user']=1;
         $session_name = 'sec_session_id';   // Set a custom session name
         
         // Sets the session name to the one set above.
         session_name($session_name);
         $mysqli->close();
         echo "Yes";
      }
      else
      {

         // Login fail 
         $mysqli->close();
         $_SESSION['user_is_logged_in'] = false;         
         echo "No";
      }
   }
}
else
{
      $message = "Enter username and password";
      $_SESSION['Error']=$message;
      echo "No";
   //echo "Enter username and password";
}
?>
