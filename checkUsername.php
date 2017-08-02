<?php 

include_once("../../config/db_config.php");
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if(checkUsername($mysqli,$_POST['username'])===false)
{
   echo "Yes";
}
else
   echo "No";
?>
