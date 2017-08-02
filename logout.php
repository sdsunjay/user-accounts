<?php
include_once 'functions.php';
sec_session_start();
 
// Unset all session values 
$_SESSION = array();
 
// get session parameters 
$params = session_get_cookie_params();
 
//this does not work.. not sure why
// Delete the actual cookie. 
/*
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httpsonly"]);
 */
// Destroy session 
session_destroy();
header('Location: ./index.html');
?>
