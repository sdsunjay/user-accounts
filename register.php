
<?php

global $feedback;
include_once("../../config/db_config.php");
include_once("functions.php");

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password1'],$_POST['question1'],$_POST['answer1'])) {
   // Sanitize and validate the data passed in
   $name = $_POST['name'];
   $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
   $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   $password = $_POST['password'];
   $password1 = $_POST['password1'];
   $answer1 = filter_input(INPUT_POST, 'answer1', FILTER_SANITIZE_STRING);
   $question1 = $_POST['question1'];

   if(registerUser($name, $username,$email,$password,$password1)) {
       if(insertQuestionsAnswers($question1, $answer1, $_SESSION['user_id'])) {
	   // Login success 
	   $_SESSION['user_name'] = $username;
	   $_SESSION['user_is_logged_in'] = true;         
	   sec_session_start();

	   $arr = array ('response'=>'yes', 'msg' => 'success');
	   echo json_encode($arr);
	   return true;
       }
   } 
   $arr = array ('response'=>'no', 'msg' => $_SESSION['Error']);
   echo json_encode($arr);
   return false;
}

?>
