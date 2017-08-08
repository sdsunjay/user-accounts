<?php 

include_once("../../config/db_config.php");
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if(isset($_POST['submit'], $_POST['username'], $_POST['action'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    if(checkUsernameExists($mysqli, $username)) {
	$questionForUser = getQuestion($mysqli, $username);
	$arr = array ('response' => 'yes', 'question'=>$questionForUser);
	echo json_encode($arr);
    }
    else {
	$arr = array ('response' => 'no', 'msg' => $_SESSION['Error']);
	echo json_encode($arr);
    }
} else if(isset($_POST['submit'], $_POST['answer'], $_POST['action'])) {
    $answer = filter_input(INPUT_POST, 'answer', FILTER_SANITIZE_STRING);
    if(checkAnswer($mysqli, $answer, $_SESSION['question_id'], $_SESSION['user_id'])){
	$arr = array ('response' => 'yes');
	echo json_encode($arr); 
    }
    else {
	$arr = array ('response' => 'no', 'msg' => $_SESSION['Error']);
	echo json_encode($arr);
    }

}
if( isset($_SESSION['Error']) )
{
   echo $_SESSION['Error'];
   unset($_SESSION['Error']);

}
    ?>
