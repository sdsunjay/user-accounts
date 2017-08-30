<?php
include_once("../../config/db_config.php");
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.
if(isset($_POST['submit'],$_POST['username'], $_POST['password'])) {
    $response = 'no';
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if(is_null($username)) {
	$msg = "Username is null";
	$_SESSION['Error']= $message;
    } elseif(is_null($password)) {
	$msg = "Password is null";
	$_SESSION['Error']= $msg;
    } else {
	if(validatePassword($password, $password)){
	    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	    //using html purifier
	    //$name = $purifier->purify($name);
	    /* check connection */
	    if (mysqli_connect_errno()) {
		$msg = "Connect: failed";
		$_SESSION['Error']= $msg;
	    } elseif(login_check($mysqli)) { //already logged in
		$response = 'yes';
		$msg = 'Success';
	    } else {
		$user_id = getUserID($mysqli, $username);
		if($user_id != 0){
		    if(login($user_id, $password, $mysqli)) {
			// Login success
			setcookie("username",$username);
			$_SESSION['user_is_logged_in'] = true;
			$session_name = 'sec_session_id';   // Set a custom session name
			// Sets the session name to the one set above.
			session_name($session_name);
			$response = 'yes';
			$msg = 'Success';
		    }
		} else {
		    $msg = $username.' does not exist';
		}
	    }
	    $mysqli->close();
	}
	$msg =  $_SESSION['Error'];
    }
    $arr = array ('response'=> $response, 'msg' => $msg);
    echo json_encode($arr);
} else {
    $msg = "Enter username and password";
    $arr = array ('response'=> 'no', 'msg' => $msg);
    echo json_encode($arr);
}
?>
