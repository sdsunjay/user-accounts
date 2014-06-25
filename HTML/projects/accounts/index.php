<!DOCTYPE HTML>
<html>
   <head>
      <title>Sign-In</title>
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
   </script>
<script language="javascript" type="text/JavaScript" src="js/login.js"></script>
      <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body id="body-color">
  <div id="Sign-In">
         <fieldset style="width:30%"><legend>LOG-IN HERE</legend>
            <!--<form method="POST" action="check.php">-->
               Username <br><input type="text" id="username"  name="username" size="40"><br>
               Password <br><input type="password" id="password" name="password" size="40"><br>
               <input id="submit" type="submit" name="submit" value="Log-In">
           <!-- </form>-->
         </fieldset>
      </div>
      <p>If you don't have a login, please <a href="register.html">Register</a>.</p>
      <p>If you are done, please <a href="logout.php">Log Out</a>.</p>
      <p><a href="forgot.php">Forgot your password</a>.</p>
<?php 
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
if( isset($_SESSION['Error']) )
{
   echo $_SESSION['Error'];
   unset($_SESSION['Error']);

}
?>
   </body>
</html>

