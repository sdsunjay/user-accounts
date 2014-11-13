<?php
include_once("../../config/db_config.php");
include_once("functions.php");

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['old_password'], $_POST['new_password'], $_POST['new_password1'], $_POST['submit']))
{

   if(updatePassword($_SESSION['user_name'],$_POST['old_password'], $_POST['new_password'], $_POST['new_password1']))
   {
      echo "Yes";
   }
   else
   {
      echo $_SESSION['Error'];
   }
}
else
{
   echo "No";
}

