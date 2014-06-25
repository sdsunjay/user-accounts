<?php
include_once("../../config/db_config.php");
include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.

//from login username field
// from login password field
function login($username, $password,$mysqli) {

   
   $chk_name= $mysqli->prepare("SELECT id,password FROM login WHERE username = ?");
   $chk_name->bind_param('s',$username);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($uid,$hash);
      $output=$chk_name->fetch();

      //username does not exist in table
       if($output==null)
      {
         $_SESSION['Error']="Username and password combination is incorrect.";
         return false;
         //echo "<script type='text/javascript'>alert('$message');</script>";
      }
     $chk_name->close();
      //must remove last character, I have no idea why?
     $hash=substr($hash, 0, -1);
    /* if ( strcmp($hash,'$2y$08$7ZTjDN3fDh4oiUzv2hJ/cOYXSiPCoBHFIDFb/uzSSIkDKuhY8y3ES') == 0)
      {
         echo "TRUE hash cmp";
      }
      else
      {
      echo 'hash: ';
      echo strcmp($hash,'$2y$08$7ZTjDN3fDh4oiUzv2hJ/cOYXSiPCoBHFIDFb/uzSSIkDKuhY8y3ES');
      }
      if (strcmp($password,'SD972eff*') == 0)
      {
         echo "TRUE password cmp\n";
      }
      else
      {
         echo 'password: ';
         echo strcmp($password,'SD972eff*');
      }
      if (password_verify('SD972eff*','$2y$08$7ZTjDN3fDh4oiUzv2hJ/cOYXSiPCoBHFIDFb/uzSSIkDKuhY8y3ES')) {
        echo "true verify hash and password\n";
      }*/
      if(checkbrute($uid,$mysqli) == false)
      {
         //there have not been more than 5 failed login attempts
         if (password_verify($password,$hash)) {
            /* Valid */
            return true;
         }
         else
         {
            // Invalid 
            $now = time();
            if ($insert_st = $mysqli->prepare("INSERT INTO login_attempts(user_id, time) VALUES (?, ?)")) {
               $insert_st->bind_param('is', $uid, $now);
               // Execute the prepared query.
               if ($insert_st->execute()) {
                  $insert_st->close();
                  return false;
               }
               else
               {
                  $message = "Registration failure: INSERT";
                  $_SESSION['Error']=$message;
                  $insert_st->close();
                  return false;
               }


            }
            $_SESSION['Error']="Username and password combination is incorrect.";
            return false;
         }
      }
      else
      {
         $_SESSION['Error']="Account is temporarily locked.";
         return false;
      } 

   }
   else
   {
      $_SESSION['Error']="Unable to execute query.";
      return false;
   }
   //$message = "password verify returned false"; 
   // echo "<script type='text/javascript'>alert('$message');</script>";


}

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
      echo "name is null in sign in";
      printf("Name is null\n\n");
   }
   else if(is_null($password))
   {
      echo "pass is null in sign in";
      printf("pass is null\n\n");
   }
   else
   {
      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
      //this needs to be fixed..use the class..I will merge with it..eventually.
      $name = preg_replace("/[^a-zA-Z0-9_\-]+/","",$name);
      $name= $mysqli->escape_string($name);

      /* check connection */
      if (mysqli_connect_errno()) 
      {
         printf("Connect failed: %s\n", mysqli_connect_error());
         echo "Connect: failed";
         exit();
      }
      if(login($name,$password,$mysqli))
      {
         // Login success 
         $_SESSION['user_name'] = $name;
         $_SESSION['user_is_logged_in'] = true;         
         $_SESSION['user']=1;
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
         echo "No";
      }
   }
}
else
{
   echo "Enter username and password";
}
?>
