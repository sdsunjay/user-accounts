
<?php

global $feedback;
include_once("../../config/db_config.php");
include_once("functions.php");

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password1'],$_POST['answer1'],$_POST['answer2'])) {
   // Sanitize and validate the data passed in
   $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
   $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   $email = filter_var($email, FILTER_VALIDATE_EMAIL);
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // Not a valid email
      $message = "The email address you entered in not valid";
      $_SESSION['Error'] = $message;
      echo "No";
   }
   else
   {
   $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
   $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
   $answer1 = filter_input(INPUT_POST, 'answer1', FILTER_SANITIZE_STRING);
   $answer2 = filter_input(INPUT_POST, 'answer2', FILTER_SANITIZE_STRING);
   $question1 = $_POST['question1'];
   $question2 = $_POST['question2'];
   
   if(registerUser($username,$email,$password,$password1))
   {
      if(insertQuestionsAnswers($question1,$question2,$answer1,$answer2,$username))
      {
         // Login success 
         $_SESSION['user_name'] = $username;
         $_SESSION['user_is_logged_in'] = true;         
        // $_SESSION['user']=1;
         $session_name = 'sec_session_id';   // Set a custom session name
         
         // Sets the session name to the one set above.
         session_name($session_name);
         echo "Yes";
      }
      else
      {
         echo "No";
      }
   }
   else
   {
      echo "No";
   }
   }

}
?>
