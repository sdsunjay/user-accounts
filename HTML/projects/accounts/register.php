<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Form</title>
        <link rel="stylesheet" href="style.css" />
         <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body>
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->

        <h1>Register</h1>
<h3>
<?php

include_once("../../config/db_config.php");
include_once("functions.php");

sec_session_start(); // Our custom secure way of starting a PHP session.

if( isset($_SESSION['Error']) )
{
   echo $_SESSION['Error'];
   unset($_SESSION['Error']);

}
?>
</h3>
<ul>
            <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Username must be at least 3 characters long</li>
            <li>Passwords must be at least 6 characters long</li>
            <li>Passwords must contain
                <ul>
                    <li>At least one upper case letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
            <li>Your password and confirmation must match exactly</li>
        </ul>
            <form method="POST" action="register.php">
               Username <br><input type="text" name="username" size="64"><br>
               Email <br><input type="text" name="email" size="64"><br>
               Password <br><input type="password" name="password" size="64"><br>
               Confirm Password <br><input type="password" name="password1" size="64"><br>
               <!--<input id="button" type="submit" name="submit" value="Register"> -->
<input type="button" 
                   value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.password1);" />             
</form>
        <p>Return to the <a href="index.php">login page</a>.</p>
<?php
$message="";
if (isset($_POST['username'], $_POST['email'], $_POST['password'],$_POST['password1'])) {
   // Sanitize and validate the data passed in
   $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
   $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   $email = filter_var($email, FILTER_VALIDATE_EMAIL);
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // Not a valid email
      $message = "The email address you entered in not valid";
      $_SESSION['Error'] = $message;
      // echo "<script type='text/javascript'>alert('$message');</script>";
   }

   $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
   $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
   registerUser($username,$email,$password,$password1);
}
?>
    </body>
</html>
