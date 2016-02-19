
<?php

global $feedback;
include_once("../../config/db_config.php");
include_once("functions.php");

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password1'],$_POST['question1'],$_POST['answer1'])) {
   // Sanitize and validate the data passed in
   $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
   $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   $password = $_POST['password'];
   $password1 = $_POST['password1'];
   $answer1 = filter_input(INPUT_POST, 'answer1', FILTER_SANITIZE_STRING);
   $question1 = $_POST['question1'];

   if(registerUser($username,$email,$password,$password1))
   {
      if(insertQuestionsAnswers($question1,$answer1,$username))
      {
         // Login success 
         $_SESSION['user_name'] = $username;
         $_SESSION['user_is_logged_in'] = true;         
         // $_SESSION['user']=1;
         $session_name = 'sec_session_id';   // Set a custom session name

         // Sets the session name to the one set above.
         session_name($session_name);
         echo "yes";
      }
      else
      {
         //echo $feedback;
         echo $_SESSION['Error']; 
      }
   }
   else
   {
      echo $_SESSION['Error']; 
      //echo $feedback;
   }
}

?>
