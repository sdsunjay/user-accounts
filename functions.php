<?php
//sec_session_start();
/*
 * XSS Protective Measures
 */
require_once '/usr/share/nginx/html/security/htmlpurifier-4.9.3-standalone/HTMLPurifier.standalone.php';


function Redirect($url, $permanent = false){
   header('Location: ' . $url, true, $permanent ? 301 : 302);
   exit();
}

function esc_url($url) {

   if ('' == $url) {
      return $url;
   }

   $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

   $strip = array('%0d', '%0a', '%0D', '%0A');
   $url = (string) $url;

   $count = 1;
   while ($count) {
      $url = str_replace($strip, '', $url, $count);
   }

   $url = str_replace(';//', '://', $url);

   $url = htmlentities($url);

   $url = str_replace('&amp;', '&#038;', $url);
   $url = str_replace("'", '&#039;', $url);

   if ($url[0] !== '/') {
      // We're only interested in relative links from $_SERVER['PHP_SELF']
      return '';
   } else {
      return $url;
   }
}

//There is a problem with this function
function check_brute($user_id, $mysql) {

   // All login attempts are counted from the past 1 hour.
   $query = "SELECT id FROM login_attempts WHERE user_id = ? AND updated_at > DATE_SUB(CURDATE(), INTERVAL 1 HOUR)";

   $stmt = $mysql->prepare($query);

   if ($stmt) {
      $stmt->bind_param('i', $user_id);
      if($stmt->execute()){
         $stmt->store_result();
         // If there have been more than 3 failed logins
         if ($stmt->num_rows > 3) {
            //the below line causes the page not to work, not sure why..
            $stmt->close();
            return true;
	    $_SESSION['Error'] = 'There have been more than 3 failed login attempts. Please wait 1
		hour before trying again';
         } else {
            $stmt->close();
         }
      } else {
         return false;
      }
   }
   return false;
}

function login_check($mysqli) {
    // Check if all session variables are set
    if (isset($_SESSION['user_name'], $_SESSION['user_is_logged_in'])) {
	if ($_SESSION['user_is_logged_in']) {
	    $username = $_SESSION['user_name'];
	    // Get the user-agent string of the user.
	    $user_browser = $_SERVER['HTTP_USER_AGENT'];
	    if(checkUsernameExists($mysqli, $username)){
		return true;
	    }
	}
    }
    return false;
}

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    // $secure = false;
    $secure = true;
    // This stops JavaScript being able to access the session id.
    $httpsonly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
	header("Location: ./error.php?err=Could not initiate a safe session (ini_set)");
	exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
	    $cookieParams["path"],
	    $cookieParams["domain"],
	    $secure,
	    $httpsonly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    //session_regenerate_id();    // regenerated the session, delete the old one.
    //$_SESSION['user_is_logged_in'] = false;
}

function logout() {
    $_SESSION = array();
    session_destroy();
    $user_is_logged_in = false;
    $feedback = "You were just logged out.";
}

/**
 * Validates the user's registration input
 * @return bool Success status of user's registration data validation
 */

function checkRegistrationData($username, $pass, $pass1) {
    // validating the input
    if (!empty($username)
	    && (strlen($username) <= 64)
	    && (strlen($username) > 2)
	    && (preg_match('/^[a-zA-Z0-9]{3,}$/', $username))
	    //      && (!empty($email))
	    //        && (strlen($email) <= 64)
	    //          && filter_var($email, FILTER_VALIDATE_EMAIL)
	    && !empty($pass)
	    && !empty($pass1)
	    && (strcmp($pass,$pass1) == 0 )
	    && (strlen($pass) <= 64)
	    && ( strlen($pass1) <= 64)
       ) {
	        // only this case return true, only this case is valid
	         return true;
    }
    elseif (empty($username)) {
	$feedback = "Empty Username";
    } elseif (empty($pass) || empty($pass1)) {
	$feedback = "Empty Password";
    } elseif(strcmp($pass,$pass1) == 0) {
	$feedback = "Password and password confirmation are not the same";
    } elseif (strlen($pass) < 8) {
	$feedback = "Password has a minimum length of 8 characters";
    } elseif (strlen($username) > 64 || strlen($username) < 3) {
	$feedback = "Username cannot be shorter than 3 or longer than 64 characters";
    }
    elseif(!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
	$feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed";
    }
    /* elseif (empty($email)) {
       $feedback = "Email cannot be empty";
       } elseif (strlen($email) > 64) {
       $feedback = "Email cannot be longer than 64 characters";
       }

       elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $feedback = "Your email address is not in a valid email format";
       }
     */
    elseif (strlen($pass) > 64) {
	$feedback = "Password cannot be longer than 64 characters";
    } else {
	$feedback = "An unknown error occurred.";
    }
    $_SESSION['Error']=$feedback;
    // default return
    return false;
}


function checkEmailExists($mysqli, $email) {

    $query = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
	$stmt->bind_param('s', $email);
	if ($stmt->execute()) {
	    $stmt->store_result();
	    if ($stmt->num_rows >= 1) {
		// A user with this email address already exists
		$feedback = "A user with this email already exists!";
		$_SESSION['Error']=$feedback;
		/* close statement */
		mysqli_stmt_close($stmt);
		return true;
	    }
	    /* close statement */
	    mysqli_stmt_close($stmt);
	    return false;
	}
	$feedback = "Problem executing query";
	$_SESSION['Error']=$feedback;
	/* close statement */
	mysqli_stmt_close($stmt);
	return true;
    }
    $feedback = "A problem occurred with this email";
    $_SESSION['Error']=$feedback;
    /* close statement */
    mysqli_stmt_close($stmt);
    return false;
}
/*
 * Get the user ID from the Users table
 */

function getUserID($mysqli, $username){

    $user_id = 0;
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s',$username);
    // Execute the prepared query.
    if ($stmt->execute()) {
	//bind result variables
	$stmt->bind_result($user_id);
	$output = $stmt->fetch();
	// TO DO - should fetch come before or after $stmt->num_rows (line 226) 
	// TO DO - Sunjay figure out why this fails in forgot password feature
	if ($stmt->num_rows > 1) {
	    // A user with this username already exists
	    $msg = "A user with this username already exists!";
	    $_SESSION['Error']= $msg;
	    /* close statement */
	    $stmt->close();
	} elseif ($stmt->num_rows < 1) {
	    $feedback = "Could not find a user with this username";
	    $_SESSION['Error'] = $feedback;
	} else {
	    if($output){
		$stmt->close();
	    } else {
		$feedback = "Could not find a user with this username";
		$_SESSION['Error'] = $feedback;
	    }
	}
	$stmt->close();
    } else {
	$feedback = "A problem occurred with this username";
	$_SESSION['Error'] = $feedback;
    }
    return $user_id;
}
/**
 * Check if the username already exists in the database
 */
function checkUsernameExists($mysqli, $username) {
    $user_id = getUserID($mysqli, $username);
    if ($user_id != 0) {
	return true;
    } else {
	return false;
    }
}

function registerUserFromCli($username, $password, $password1) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $username = $purifier->purify($username);
    $email = "Test@gmail.com";
    if(checkRegistrationData($username, $password, $password1)) {
	$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	// check connection
	if (mysqli_connect_errno()) {
	    $feedback = "Connect failed";
	    $_SESSION['Error'] = $feedback;
	    return false;
	}

	// Username validity and password validity have NOT been checked client side.
	// This should be adequate as nobody gains any advantage from
	// breaking these rules.
	if(checkUsernameExists($mysqli, $username) == false) {
	    //create a random salt
	    //$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	    //$salt = substr($random_salt, 0, 128);
	    $temp_pass = password_hash($password, PASSWORD_BCRYPT,array("cost" => 9));
	    // Insert the new user into the database
	    if ($insert_user = $mysqli->prepare("INSERT INTO users (username, email, password,
		created_at) VALUES (?, ?, ?, ?)")) {
		date_default_timezone_set('America/Los_Angeles');
		$created_at = date("Y-m-d H:i:s");
		$insert_user->bind_param('ssss', $username, $email, $temp_pass, $created_at);
		// Execute the prepared query.
		if ($insert_user->execute()) {
		    /* close statement */
		    mysqli_stmt_close($insert_user);
		    $_SESSION['user_name'] = $username;
		    $_SESSION['user_is_logged_in'] = true;
		    $_SESSION['user_id'] = getUserID($mysqli, $username);
		    /* close connection */
		    mysqli_close($mysqli);
		    return true;
		} else {
		    $insert_user->close();
		    $feedback = "Registration failure: INSERT";
		    /* close connection */ mysqli_close($mysqli);
		    //$mysqli->close();
		    $_SESSION['Error'] = $feedback;
		    return false;
		}
	    } else {
		$insert_user->close();
		$feedback = "Registration failure: INSERT";
		//$mysqli->close();
		/* close connection */ mysqli_close($mysqli);
		$_SESSION['Error'] = $feedback;
		return false;
	    }
	} else {
	    $feedback = "Registration failure: Username already exists";
	    $mysqli->close();
	    //$_SESSION['Error'] = $feedback;
	    return false;
	}
    }
    return false;
}

function registerUser($name, $username, $email, $password, $password1) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $username = $purifier->purify($username);
    $name = $purifier->purify($name);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$feedback = "Invalid email format";
	$_SESSION['Error'] = $feedback;
    } elseif(checkRegistrationData($username, $password, $password1)) {
	$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	// check connection
	if (mysqli_connect_errno()) {
	    $feedback = "Connect failed";
	    $_SESSION['Error'] = $feedback;
	}
	// Username validity and password validity have been checked client side.
	// This should be adequate as nobody gains any advantage from
	// breaking these rules.
	if(checkEmailExists($mysqli, $email) == false ) {
	    if(checkUsernameExists($mysqli, $username) == false) {
		$temp_pass = password_hash($password, PASSWORD_BCRYPT,array("cost" => 9));
		// Insert the new user into the database
		$created_at = date("Y-m-d H:i:s");
		if ($insert_user = $mysqli->prepare("INSERT INTO users (name, username, email,
		    password, created_at) VALUES (?,?,?,?,?)")) {
		    date_default_timezone_set('America/Los_Angeles');
		    $insert_user->bind_param('sssss', $name, $username, $email, $temp_pass, $created_at);
		    // Execute the prepared query.
		    if ($insert_user->execute()) {
			/* close statement */
			mysqli_stmt_close($insert_user);
			$_SESSION['user_name'] = $username;
			$_SESSION['user_is_logged_in'] = true;
			$_SESSION['user_id'] = getUserID($username);
			/* close connection */ mysqli_close($mysqli);
			//  $mysqli->close();
			return true;
		    } else {
			$insert_user->close();
			$feedback = "Registration failure: INSERT";
			$_SESSION['Error'] = $feedback;
			$mysqli->close();
		    }
		} else {
		    $insert_user->close();
		    $feedback = "Database Error!";
		    $_SESSION['Error'] = $feedback;
		    $mysqli->close();
		}
	    } else {
		$mysqli->close();
	    }
	} else {
	    $mysqli->close();
	}
    }
    return false;
}
function helpUpdatePassword($mysqli, $user_id, $new_password){

    date_default_timezone_set('America/Los_Angeles');
    $temp_pass = password_hash($new_password, PASSWORD_BCRYPT,array("cost" => 9));
    $query_string ="UPDATE users SET PASSWORD = ? where user_id = ?";
    $stmt = $mysqli->prepare($query_string);
    $stmt->bind_param('si',$temp_pass, $user_id);
    // Execute the prepared query.
    if ($stmt->execute()) {
	$stmt->close();
	return true;
    } 
    $message = "Update Password failure: Update Password INSERT";
    $_SESSION['Error'] = $message;
    return false;
}

function validatePassword($new_password, $new_password1){

    if(strcmp($new_password, $new_password1) == 0 ) {
	if( (strlen($new_password) <= 64) && (strlen($new_password) >= 8)) {
	    $result = (preg_match('/[A-Z]+/', $new_password) && preg_match('/[a-z]+/', $new_password) && preg_match('/\d/', $new_password));
	    if ($result) {
		return true;
	    } else {
		$message = "Password does not contain atleast one lower case letter, one uppercase letter, and one number";
	    }
	} else {
	    $message = "Password length must be between 8 and 64 characters.";
	}
    } else {
	$message = "Passwords do not match.";
    }
    $_SESSION['Error'] = $message;
    return false;
}

/**
 * Update user's password
 */
function updatePassword($mysqli, $username, $old_password, $new_password, $new_password1) {

    if(login_check($mysqli)) {
	if(is_null($username)) {
	    $message = "Username is null";
	} elseif(is_null($old_password)) {
	    $message = "Old password is null";
	} elseif(is_null($new_password)) {
	    $message = "New password is null";
	} elseif(is_null($new_password1)) {
	    $message = "New password is null";
	} else {
	    $user_id = getUserID($mysqli, $username);
	    //Does the old password match
	    if (login($user_id, $old_password,$mysqli)) {
		if(validatePassword($new_password, $new_password1)){
		    return helpUpdatePassword($mysqli, $user_id, $new_password);
		}
	    } else {
		$message = "Username and old password combination is incorrect.";
	    }
	}
    } else {
	$message = "You are not logged in";
    }
    $_SESSION['Error'] = $message;
    return false;
}

/**
 * Check the user's supplied answer against what is in the database
 * @param $user_answer - the user's supplied answer
 */
function checkAnswer($mysqli, $user_answer, $question_id, $user_id) {
    $query = "select answer from user_answers where user_id = ? and question_id = ?";
    $chk_name= $mysqli->prepare($query);
    $chk_name->bind_param('ii',$user_id, $question_id);
    // Execute the prepared query.
    if ($chk_name->execute()) {
	//bind result variables
	$chk_name->bind_result($answer);
	$output = $chk_name->fetch();
	if($output) {
	    $temp_answer = password_hash($user_answer, PASSWORD_BCRYPT,array("cost" => 9));
	    if (password_verify($temp_answer, $answer)) {
		// TO DO - Sunjay, user should not be logged in, simply because they answered their secret
		// question
		//	$_SESSION['user_is_logged_in'] = true;
		return true;
	    } else {
		return false;
	    }
	}
    }
    return false;
}
/*
 * For a given user, get their security question
 */
function getQuestion($mysqli, $username){
    $user_id = getUserID($mysqli, $username);

    if($user_id != 0){
	$question_id = getQuestionID($mysqli,$user_id);
	if($question_id != 0){
	    $query = "select question from questions where id = ?";
	    $chk_name= $mysqli->prepare($query);
	    $chk_name->bind_param('i',$question_id);
	    // Execute the prepared query.
	    if ($chk_name->execute()) {
		//bind result variables
		$chk_name->bind_result($question);
		$output = $chk_name->fetch();
		if($output){
		    setcookie("username",$username);
		    $_SESSION['username'] = $username;
		    $_SESSION['question_id'] = $question_id;
		    $_SESSION['user_id'] = $user_id;
		    $chk_name->close();
		    return $question;
		}
		$_SESSION['Error'] = 'Unable to find question for '.$username;
		$chk_name->close();
	    } else {
		$_SESSION['Error'] = 'Unable to find question for '.$username;
		return false;
	    }
	}
	$_SESSION['Error'] = 'Unable to find question for '.$username;
	return false;
    }
    return false;
}

/**
 * Get the question ID for the user's security question
 */

function getQuestionID($mysqli,$user_id){
    if($user_id !== 0){
	$chk_name= $mysqli->prepare("SELECT question_id FROM user_answers WHERE user_id = ?");
	$chk_name->bind_param('i',$user_id);
	// Execute the prepared query.
	if ($chk_name->execute()) {
	    //bind result variables
	    $chk_name->bind_result($question_id);
	    $output=$chk_name->fetch();
	    if($output) {
		$chk_name->close();
		return $question_id;
	    }
	    $chk_name->close();
	} else {
	    $_SESSION['Error'] = "Error finding question ID.";
	}
    } else {
	$_SESSION['Error'] = "Unable to find user with that ID.";
    }
    return 0;
}

/**
 * Insert user's security question and answer into user_answers
 */
function insertQuestionsAnswers($mysqli, $question_id, $answer1, $username) {

    $user_id = getUserID($mysqli,$username);
    if($user_id > 0){
	//two questions and two answers
	if(insertQuestion($mysqli, $user_id, $question_id, $answer1)) {
	    $mysqli->close();
	    return true;
	} else {

	    $feedback = "Question 1 could not be added";
	    $_SESSION['Error'] = $feedback;
	}
    } else {
	$feedback = "Username could not be found";
	$_SESSION['Error'] = $feedback;
    }
    $mysqli->close();
    return false;
}

/**
 * Insert user's answer into user answers
 */
function insertQuestion($mysqli, $user_id, $question_id, $answer) {
    //$config = HTMLPurifier_Config::createDefault();
    //$purifier = new HTMLPurifier($config);
    //purify answer
    //$answer = $purifier->purify($answer);
    if(strlen($answer) > 5 && strlen($answer) < 64) {
	date_default_timezone_set('America/Los_Angeles');
	$created_at = date("Y-m-d H:i:s");
	$temp_pass = password_hash($answer, PASSWORD_BCRYPT,array("cost" => 9));
	if ($insert_question = $mysqli->prepare("INSERT INTO user_answers (user_id, question_id,
	    answer, created_at) VALUES (?, ?, ?, ?)")) {
	    $insert_question->bind_param('iiss', $user_id, $question_id, $temp_pass, $created_at);
	    // Execute the prepared query.
	    if ($insert_question->execute()) {
		$insert_question->close();
		return true;
	    } else {
		$feedback = "Registration failure: Security Question INSERT";
		$_SESSION['Error'] = $feedback;
		$insert_user->close();
	    }
	} else {
	    $feedback = "Database Error";
	    $_SESSION['Error'] = $feedback;
	}
    } else {
	$feedback = "Security question answer is not long enough or is too long";
	$_SESSION['Error'] = $feedback;
    }
    return false;
}

/**
 * Insert user's login attempt into failed login attempts
 * 3 failed attempts and we lock them out
 */
function insertIntoLoginAttempts($mysqli, $user_id){

    date_default_timezone_set('America/Los_Angeles');
    $created_at = date("Y-m-d H:i:s");
    $_SESSION['Error']="Username and password combination is incorrect.";
    if ($insert_st = $mysqli->prepare("INSERT INTO login_attempts(user_id, created_at) VALUES (?, ?)")) {
	$insert_st->bind_param('is', $user_id, $created_at);
	// Execute the prepared query.
	if ($insert_st->execute()) {
	    $insert_st->close();
	} else {
	    $message = "Login Attempts failure: INSERT";
	    $_SESSION['Error']=$message;
	    $insert_st->close();
	}
    } else {
	$message = "Login Attempts failure: INSERT";
	$_SESSION['Error']=$message;
	$insert_st->close();
    }

}

/**
 * Helper function for login
 */
function verifyPassword($mysqli, $user_id, $password){

    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    // Execute the prepared query.
    if ($stmt->execute()) {
	/* bind result variables */
	$stmt->bind_result($hash);
	$output = $stmt->fetch();
	// TO DO - Sunjay look more into when close should be called on the statement
	if($output) {
	    $stmt->close();
	    // TO DO - Sunjay, check if this is still needed
	    //must remove last character, I have no idea why?
	    $hash = substr($hash, 0, -1);
	    $temp_pass = password_hash($password, PASSWORD_BCRYPT,array("cost" => 9));
	    if (password_verify($temp_pass, $hash)) {
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_name'] = $username;
		$_SESSION['user_is_logged_in'] = true;
		/* Valid */
		return true;
	    } else {
		$_SESSION['Error']="Username and password combination is incorrect";
		insertIntoLoginAttempts($mysqli, $user_id);
	    }
	} else {
	    $_SESSION['Error']="Unable to fetch user's password from database.";
	}
    } else {
	$_SESSION['Error']="Unable to fetch user's password from database.";
    }
    return false;
}

/**
 * Used to autheticate user when logging in
 */
function login($user_id, $password, $mysqli) {
    if ($user_id != 0){
	//Have there been more than 3 failed login attempts?
	if(check_brute($user_id, $mysqli) == false){
	    return verifyPassword($mysqli, $user_id, $password);
	} else {
	    $_SESSION['Error']="Account is temporarily locked.";
	}
    }
    return false;
}
?>
