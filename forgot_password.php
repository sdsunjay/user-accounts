<?php 

include_once("../../config/db_config.php");
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if(checkUsernameExists($mysqli, $_POST['username']))
{
   $questionForUser = getQuestion($mysqli, $_POST['username']);
   $arr = array ('response' => 'yes', 'question'=>$questionForUser);
   echo json_encode($arr);
}
else
{
   $arr = array ('response' => 'no', 'msg' => $_SESSION['Error']);
   echo json_encode($arr);
}
?>
