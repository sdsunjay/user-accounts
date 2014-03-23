
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Protected Page</title>
    </head>
    <body>
<?php
include_once("../../config/db_config.php");
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.



function user_role($mysqli,$username)
{

   $chk_name= $mysqli->prepare("SELECT id FROM login WHERE username = ?");
   $chk_name->bind_param('s',$username);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($uid);
      $output=$chk_name->fetch();
      
      //must remove last character, I have no idea why?
     mysqli_stmt_close($chk_name);
   }

   $chk_name= $mysqli->prepare("SELECT role_id FROM user_roles WHERE user_id = ?");
   $chk_name->bind_param('i',$uid);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($role_id);
      $output=$chk_name->fetch();
      
      //must remove last character, I have no idea why?
     mysqli_stmt_close($chk_name);
   }
   $chk_name= $mysqli->prepare("SELECT role FROM roles WHERE id = ?");
   $chk_name->bind_param('i',$role_id);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($role);
      $output=$chk_name->fetch();
      
      //must remove last character, I have no idea why?
     mysqli_stmt_close($chk_name);
   }
   if(strcmp($role,"admin") == 0)
   {
      return true;
   }
   else
   {
      return false;
   }

}
function showInfo($mysqli)
{

   $chk_name= $mysqli->prepare("SELECT username FROM login");
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($name);
      while($chk_name->fetch()){
             echo $name . '<br />';
      }
      $chk_name->free_result();
      mysqli_stmt_close($chk_name);
   }
}
//if(session_id() == '' || !isset($_SESSION)) {
   // session isn't started
//   session_start();            // Start the PHP session 
//   session_regenerate_id();    // regenerated the session, delete the old one. 
//}
if (isset($_SESSION['user'])) {
   //if ($_SESSION['user_is_logged_in'] == true) {
   echo "Welcome " .$_SESSION['user_name']."<br>";
  
   $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
   /* check connection */
   if (mysqli_connect_errno()) 
   {
      printf("Connect failed: %s\n", mysqli_connect_error());
      echo "Connect: failed";
      exit();
   }
   if(user_role($mysqli,$_SESSION['user_name']))
   {
      //echo "You are an administrator <br>";i
      echo "<h3> Users </h3>";
      showInfo($mysqli);
      //show all tables

   }
   else
   {
      //echo "You are <b> not </b> an administrator <br>";

   }
   echo "You are securely logged in. <br>"; 
   echo "If you are done, please <a href='logout.php'>Log Out</a>.";
   
}
else
{

   echo "You are not logged in. <br>";
   echo "If you have an account, please <a href='index.php'>sign in</a>. <br>";
   echo "If you don't have a login, please <a href='register.php'>Register</a>.";
}
//sec_session_start();
?>
<!DOCTYPE html>
</body>
</html>
