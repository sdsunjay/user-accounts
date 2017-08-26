<?php

include_once("../../config/db_config.php");
include_once 'functions.php';


function verify_answer($answer, $action){
    if ($action == 'checkAnswer') {
        if (checkAnswer($mysqli, $answer, $_SESSION['question_id'], $_SESSION['user_id'])) {
            $arr = array('response' => 'yes');
            echo json_encode($arr);
        } else {
            $arr = array('response' => 'no', 'msg' => $_SESSION['Error']);
            echo json_encode($arr);
        }
    }
}

function verify_username($username, $action){
    if ($action == 'checkUsername') {
        if (checkUsernameExists($mysqli, $username)) {
            $questionForUser = getQuestion($mysqli, $username);
            $arr = array('response' => 'yes', 'question'=>$questionForUser);
            echo json_encode($arr);
        } else {
            $arr = array('response' => 'no', 'msg' => $_SESSION['Error']);
            echo json_encode($arr);
        }
    }
}

function reset_password($password, $password1, $action){
    if ($action == 'checkPassword') {
        if (validatePassword($mysqli, $password, $password1)) {
            if (helpUpdatePassword($mysqli, $_SESSION['user_id'], $password)) {
                $arr = array('response' => 'yes');
                echo json_encode($arr);
            } else {
                $arr = array('response' => 'no', 'msg' => $_SESSION['Error']);
                echo json_encode($arr);
            }
        } else {
            $arr = array('response' => 'no', 'msg' => $_SESSION['Error']);
            echo json_encode($arr);
        }
    }
}

sec_session_start(); // Our custom secure way of starting a PHP session.
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if (isset($_POST['submit'], $_POST['username'], $_POST['action'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    verify_username($username, $action);
} elseif (isset($_POST['submit'], $_POST['answer'], $_POST['action'])) {
    $answer = filter_input(INPUT_POST, 'answer', FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    verify_answer($answer, $action);
} elseif (isset($_POST['submit'], $_POST['password'], $_POST['password1'], $_POST['action'])) {    
    $password = $_POST['password'];
    $password1 = $_POST['password1'];
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    reset_password($password, $password1, $action); 
}
if (isset($_SESSION['Error'])) {
    echo $_SESSION['Error'];
    unset($_SESSION['Error']);
}
