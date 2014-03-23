<!DOCTYPE HTML>
<html>
   <head>
      <title>Sign-In</title>
    <!--  <link rel="stylesheet" type="text/css" href="style.css">-->
   </head>
   <body id="body-color">
      <div id="Sign-In">
         <fieldset style="width:30%"><legend>LOG-IN HERE</legend>
            <form method="POST" action="check.php">
               Username <br><input type="text" name="username" size="40"><br>
               Password <br><input type="password" name="password" size="40"><br>
               <input id="button" type="submit" name="submit" value="Log-In">
            </form>
         </fieldset>
      </div>
      <p>If you don't have a login, please <a href="register.php">Register</a>.</p>
      <p>If you are done, please <a href="logout.php">Log Out</a>.</p>
<?php 
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
if( isset($_SESSION['Error']) )
{
   echo $_SESSION['Error'];
   unset($_SESSION['Error']);

}
?>
   </body>
</html>

