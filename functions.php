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
function checkbrute($user_id,$mysql) {

   // All login attempts are counted from the past 1 hour. 
   $prep = "SELECT time FROM login_attempts WHERE user_id = ? AND time > DATE_SUB(CURDATE(), INTERVAL 1 HOUR)";


   //$prep = "SELECT time FROM login_attempts WHERE user_id = ?";

   $stmt = $mysql->prepare($prep);

   if ($stmt) {
      $stmt->bind_param('i', $user_id);
      if($stmt->execute())
      {
         $stmt->store_result();

         // If there have been more than 3 failed logins 
         if ($stmt->num_rows > 3) {
            //the below line causes the page not to work, not sure why..
            $stmt->close();
            return true;
         } else {
            $stmt->close();
            return false;
         }
      }
      else
      {
         return false;
      }
   }
   return false;
}

function login_check($mysqli) {
   // Check if all session variables are set 
   if (isset($_SESSION['user_name'], $_SESSION['user_is_logged_in'])) {

      $username = $_SESSION['user_name'];

      // Get the user-agent string of the user.
      $user_browser = $_SERVER['HTTP_USER_AGENT'];

      if(checkUsernameExists($username,$mysqli)){
         return true;
      } else {
         // Not logged in 
         return false;
      }
   }
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
function logout()
{
   $_SESSION = array();
   session_destroy();
   $user_is_logged_in = false;
   $feedback = "You were just logged out.";
}
/**
 * Validates the user's registration input
 * @return bool Success status of user's registration data validation
 */

function checkRegistrationData($username,$pass,$pass1)
{
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
      && (strlen($pass) < 72)
      && ( strlen($pass1) < 72)
   ) {
      // only this case return true, only this case is valid
      return true;
   } 
   else if (empty($username)) {
      $feedback = "Empty Username";
   } else if (empty($pass) || empty($pass1)) {
      $feedback = "Empty Password";
   } else if(strcmp($pass,$pass1) == 0) {
      $feedback = "Password and password confirmation are not the same";
   } else if (strlen($pass) < 8) {
      $feedback = "Password has a minimum length of 8 characters";
   } else if (strlen($username) > 64 || strlen($username) < 3) {
      $feedback = "Username cannot be shorter than 3 or longer than 64 characters";
   }
   else if(!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) { 
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
      $feedback = "Password cannot be longer than 71 characters";
   }
   else {
      $feedback = "An unknown error occurred.";
   }
   $_SESSION['Error']=$feedback;
   // default return
   return false;
}


function checkEmail($mysqli,$email)
{

   $query = "SELECT id FROM users WHERE email = ? LIMIT 1";
   $stmt = $mysqli->prepare($query);

   if ($stmt) {
      $stmt->bind_param('s', $email);
      if ($stmt->execute())
      {
         $stmt->store_result();

         if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $feedback = "A user with this email already exists!"; 
            $_SESSION['Error']=$feedback;
            /* close statement */
            mysqli_stmt_close($stmt);
            return false;
         }
         /* close statement */
         mysqli_stmt_close($stmt);
         return true;
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
   return true;
}

function getUserID($mysqli, $username){

   $query = "SELECT id FROM users WHERE username = ?";

   $stmt = $mysqli->prepare($query);
   $stmt->bind_param('s',$username);
   // Execute the prepared query.
   if ($stmt->execute()) {
      //bind result variables
      $stmt->bind_result($user_id);
      $output = $stmt->fetch();
      if($output)
      {

         $stmt->close();
         return $user_id;
      }
      $stmt->close();
   }
   else
   {
      $feedback = "A problem occurred with this email"; 
      $_SESSION['Error']=$feedback;
      return 0;
   }
}

function checkUsernameExists($mysqli, $username)
{

   $chk_name = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
   $chk_name->bind_param('s',$username);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      // bind result variables 
      $chk_name->store_result();

      if ($chk_name->num_rows == 1) {
         /* close statement */
         mysqli_stmt_close($chk_name);
         return true;
      }
      //this should in theory never happen..
      else if ($chk_name->num_rows >= 1) {
         $feedback = $username." exists multiple times in the database. WHAT?!"; 
         $_SESSION['Error']=$feedback;
         /* close statement */
         mysqli_stmt_close($chk_name);
      }

      else
      {
         /* close statement */
         mysqli_stmt_close($chk_name);
      }

   }
   else
   {

      $_SESSION['Error']='Could not execute query';
   }
   return false;
}
function checkUsername($mysqli,$username)
{

   $prep_stmt = "SELECT id FROM users WHERE username = ? LIMIT 1";
   $stmt = $mysqli->prepare($prep_stmt);

   if ($stmt) {
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows == 1) {
         // A user with this email address already exists
         $feedback = "A user with this username already exists!"; 
         $_SESSION['Error']=$feedback;
         /* close statement */
         mysqli_stmt_close($stmt);
         return false;
      }
      /* close statement */
      mysqli_stmt_close($stmt);
      return true;
   }
   else
   {
      $feedback = "A problem occurred with username"; 
      $_SESSION['Error']=$feedback;
   }
   return false;
}

function registerUserFromCli($username,$password,$password1)
{
   $config = HTMLPurifier_Config::createDefault();
   $purifier = new HTMLPurifier($config);
   $username = $purifier->purify($username);
   $email = "TestMe@gmail.com";
   if(checkRegistrationData($username,$password,$password1))
   {

      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
      // check connection 
      if (mysqli_connect_errno()) 
      {
         $feedback = "Connect failed";
         $_SESSION['Error'] = $feedback;
         return false;
      }

      // Username validity and password validity have NOT been checked client side.
      // This should be adequate as nobody gains any advantage from
      // breaking these rules.
      if(checkUsername($mysqli,$username))
      {
         //create a random salt
         //$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
         //$salt = substr($random_salt, 0, 128); 
         $temp_pass=password_hash($password, PASSWORD_BCRYPT,array("cost" => 9))."\n";
         // Insert the new user into the database 
         if ($insert_user = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")) {
            date_default_timezone_set('America/Los_Angeles');
            // $created_at =date("Y-m-d H:i:s");
            $insert_user->bind_param('sss', $username, $email, $temp_pass);
            // Execute the prepared query.
            if ($insert_user->execute()) {
               /* close statement */
               mysqli_stmt_close($insert_user);
               $_SESSION['user_name'] = $username;
               $_SESSION['user_is_logged_in'] = true; 
               //temp fix for now
               $_SESSION['user_id'] = 1;
                  /* close connection */ mysqli_close($mysqli); 
               //  $mysqli->close(); 
               return true;
            }
            else
            {
               $insert_user->close(); 
               $feedback = "Registration failure: INSERT";
                  /* close connection */ mysqli_close($mysqli); 
               //$mysqli->close(); 
               $_SESSION['Error'] = $feedback;
               return false;

            }
         }
         else {
            $insert_user->close(); 
            $feedback = "Database Error!";
            //$mysqli->close(); 
               /* close connection */ mysqli_close($mysqli); 
            $_SESSION['Error'] = $feedback;
            return false;
         }
      }
      else
      {
         $mysqli->close(); 
         //$_SESSION['Error'] = $feedback;
         return false;
      }
   }
   else
   {
      return false;
   }
}
function registerUser($name, $username,$email,$password,$password1)
{
   $config = HTMLPurifier_Config::createDefault();
   $purifier = new HTMLPurifier($config);
   $username = $purifier->purify($username);
   $name = $purifier->purify($name);

   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $feedback = "Invalid email format"; 
      $_SESSION['Error'] = $feedback;
      return false;
   }
   if(checkRegistrationData($username,$password,$password1))
   {

      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
      // check connection 
      if (mysqli_connect_errno()) 
      {
         $feedback = "Connect failed";
         $_SESSION['Error'] = $feedback;
      }

      // Username validity and password validity have NOT been checked client side.
      // This should be adequate as nobody gains any advantage from
      // breaking these rules.

      if(checkEmail($mysqli,$email))
      {
         if(checkUsername($mysqli,$username))
         {
            //create a random salt
            //$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            //$salt = substr($random_salt, 0, 128); 

            $temp_pass=password_hash($password, PASSWORD_BCRYPT,array("cost" => 9))."\n";
            // Insert the new user into the database 
            if ($insert_user = $mysqli->prepare("INSERT INTO users (name, username, email, password) VALUES (?,?,?,?)")) {
               date_default_timezone_set('America/Los_Angeles');
               $insert_user->bind_param('ssss', $name, $username, $email, $temp_pass);
               // Execute the prepared query.
               if ($insert_user->execute()) {
                  /* close statement */
                  mysqli_stmt_close($insert_user);
                  $_SESSION['user_name'] = $username;
                  $_SESSION['user_is_logged_in'] = true; 
                  //temp fix for now
                  $_SESSION['user_id'] = 1;
                  /* close connection */ mysqli_close($mysqli); 
                  //  $mysqli->close(); 
                  return true;
               }
               else
               {
                  $insert_user->close(); 
                  $feedback = "Registration failure: INSERT";
                  $mysqli->close(); 
                  $_SESSION['Error'] = $feedback;
                  return false;

               }
            }
            else {
               $insert_user->close(); 
               $feedback = "Database Error!";
               $mysqli->close(); 
               $_SESSION['Error'] = $feedback;
               return false;
            }
         }
         else
         {
            $mysqli->close(); 
            //$_SESSION['Error'] = $feedback;
            return false;
         }
      }
      else
      {
         $mysqli->close(); 
         //$_SESSION['Error'] = $feedback;
         return false;
      }
   }
   else
   {
      return false;
   }
}

function updatePassword($username,$old_password,$new_password,$new_password1)
{
   if(is_null($username))
   {
      $message = "Username is null";
   }
   elseif(is_null($old_password))
   {
      $message = "Old password is null";
   }
   elseif(is_null($new_password))
   {
      $message = "New password is null";
   }
   elseif(is_null($new_password1))
   {
      $message = "New password is null";
   }
   elseif(strcmp($new_password,$new_password1) == 0 )
   {
      if(strlen($new_password) < 72)
         if(strlen($new_password) >= 10)
         {
            $result = (preg_match('/[A-Z]+/', $new_password) && preg_match('/[a-z]+/', $new_password) && preg_match('/\d/', $new_password));
            if ($result)
            {
               $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
               // check connection 
               if (mysqli_connect_errno()) 
               {
                  $message = "Connect failed";
               }
               //is the username valid?
               else if(checkUsernameExists($username,$mysqli))
               {
                  //are they already logged in?
                  if(login_check($mysqli))
                  {
                     //are they who they say they are?
                     if (login($username,$old_password,$mysqli))
                     {
                        $temp_pass=password_hash($new_password, PASSWORD_BCRYPT,array("cost" => 9))."\n";
                        date_default_timezone_set('America/Los_Angeles');
                        $query_string="UPDATE users SET PASSWORD = ? where username = ?";
                        $stmt= $mysqli->prepare($query_string);
                        $stmt->bind_param('ss',$temp_pass,$username);
                        // Execute the prepared query.
                        if ($stmt->execute()) {
                           $stmt->close();
                           return true;
                        }
                        $stmt->close();
                     }
                     else
                     {
                        $message = "username and password combination is incorrect.";
                     }
                  }
                  else
                  {
                     $message = "You are not logged in";
                  }
               }
               else
               {
                  $message = $username . "does NOT exist.";
               }
            }
            else
            {
               $message = "Password does not contain atleast one lower case letter, one uppercase letter, and one number";
            }
         }
         else
         {
            $message = "Password length must be 10 or more characters.";

         }
   }
   else
   {
      $message = "Passwords do not match.";
   }
   $_SESSION['Error'] = $message;
   return false;
}
function getID($mysqli,$username)
{

   $chk_name= $mysqli->prepare("SELECT id FROM users WHERE username = ?");
   $chk_name->bind_param('s',$username);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      //bind result variables
      $chk_name->bind_result($uid);
      $output=$chk_name->fetch();
      if($output)
      {
         $chk_name->close();
         return $uid;
      }
      $chk_name->close();
   }
   else
   {
      $_SESSION['Error'] = "problem with checking username";
   }
   return 0;
}

function checkAnswer($mysqli, $user_answer, $question_id, $user_id){
	    $query = "select answer from user_answers where user_id = ? and question_id = ?"; 
	    $chk_name= $mysqli->prepare($query);
	    $chk_name->bind_param('ii',$user_id, $question_id);
	    // Execute the prepared query.
	    if ($chk_name->execute()) {
		//bind result variables
		$chk_name->bind_result($answer);
		$output = $chk_name->fetch();
		if($output){
		    if(strcmp($user_answer, $answer) == 0){
			return true;
		    }
		    else{
			return false;
		    }
		}
	    }
	    return false;
}

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
	    }
	    else
	    {
		$_SESSION['Error'] = 'Unable to find question for '.$username;
		return false;
	    }
	}
	$_SESSION['Error'] = 'Unable to find question for '.$username;
	return false;
    }
    return false;
}
function getQuestionID($mysqli,$user_id){
    if($user_id !== 0)
    {
	$chk_name= $mysqli->prepare("SELECT question_id FROM user_answers WHERE user_id = ?");
	$chk_name->bind_param('i',$user_id);
	// Execute the prepared query.
	if ($chk_name->execute()) {
	    //bind result variables
	    $chk_name->bind_result($question_id);
	    $output=$chk_name->fetch();
	    if($output)
	    {
		$chk_name->close();
		return $question_id;
	    }
	    $chk_name->close();
	}
	else
	{
	    $_SESSION['Error'] = "Error finding question ID.";
	}
    }
    else
    {
	$_SESSION['Error'] = "Unable to find user with that ID.";
    }   
    return 0;
}
function insertQuestionsAnswers($question1,$answer1,$username){

    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
    // check connection 
    if (mysqli_connect_errno()) 
    {
	$feedback = "Connect failed";
	$_SESSION['Error'] = $feedback;
	$mysqli->close();
	return false;
    }
    $id = getID($mysqli,$username);
    if($id>0)
    {
	//two questions and two answers
	if(insertQuestion($mysqli,$id,$question1,$answer1))
	{
	    //if(insertQuestion($mysqli,$id,$question2,$answer2))
	    // {
	    $mysqli->close();
	    return true;
	    /* }
	       else
	       {

	       $feedback = "Question 2 could not be added";
	       $_SESSION['Error'] = $feedback;
	       }*/
	}
	else
	{

	    $feedback = "Question 1 could not be added";
	    $_SESSION['Error'] = $feedback;
	}

    }
    else
    {

	$feedback = "Username could not be found";
	$_SESSION['Error'] = $feedback;
    }
    $mysqli->close();
    return false;
}
function insertQuestion($mysqli,$uid,$question,$answer) {
    //$config = HTMLPurifier_Config::createDefault();
    //$purifier = new HTMLPurifier($config);
    //purify answer 
    //$answer = $purifier->purify($answer);
    if(strlen($answer)>7 && strlen($answer) < 255)
    {

	$temp_pass=password_hash($answer, PASSWORD_BCRYPT,array("cost" => 9))."\n";
	if ($insert_question = $mysqli->prepare("INSERT INTO user_answers (user_id, question_id, answer) VALUES (?, ?,?)")) {
	    $insert_question->bind_param('iis', $uid, $question, $temp_pass);
	    // Execute the prepared query.
	    if ($insert_question->execute()) {
		$insert_question->close(); 
		return true;
	    }
	    else
	    {
		$feedback = "Registration failure: INSERT";
		$_SESSION['Error'] = $feedback;
		$insert_user->close();
		return false;
	    }
	}
	else {
	    $feedback = "Database Error";
	    $_SESSION['Error'] = $feedback;
	}
    }
    else
    {

	$feedback = "Security question answer is not long enough or is too long";
	$_SESSION['Error'] = $feedback;
    }
    return false;
}
/**
 * Used to autheticate user when logging in
 */

function login($username, $password,$mysqli) {


    $chk_name= $mysqli->prepare("SELECT id,password FROM users WHERE username = ?");
    $chk_name->bind_param('s',$username);
    // Execute the prepared query.
    if ($chk_name->execute()) {
	/* bind result variables */
	$chk_name->bind_result($uid,$hash);
	$output=$chk_name->fetch();

	//username does not exist in table
	if($output==null)
	{
	    $_SESSION['Error']="Username and password combination is incorrect.";
	    return false;
	}
	$chk_name->close();
	//must remove last character, I have no idea why?
	$hash=substr($hash, 0, -1);
	/* if ( strcmp($hash,'$2y$08$7ZTjDN3fDh4oiUzv2hJ/cOYXSiPCoBHFIDFb/uzSSIkDKuhY8y3ES') == 0)
	   {
	   echo "TRUE hash cmp";
	   }
	   else
	   {
	   echo 'hash: ';
	   echo strcmp($hash,'$2y$08$7ZTjDN3fDh4oiUzv2hJ/cOYXSiPCoBHFIDFb/uzSSIkDKuhY8y3ES');
	   }
	   if (strcmp($password,'xxxxxx') == 0)
	   {
	   echo "TRUE password cmp\n";
	   }
	   else
	   {
	   echo 'password: ';
	   echo strcmp($password,'xxxxx');
	   }
	   if (password_verify('xxxxx','$2y$08$7ZTjDN3fDh4oiUzv2hJ/cOYXSiPCoBHFIDFb/uzSSIkDKuhY8y3ES')) {
	   echo "true verify hash and password\n";
	   }*/
	//Have there been more than 3 failed login attempts?
	if(checkbrute($uid,$mysqli) == false){
	    $_SESSION['user_id']=$uid;
	    if (password_verify($password,$hash)) {
		/* Valid */
		return true;
	    }
	    else{
		if ($insert_st = $mysqli->prepare("INSERT INTO login_attempts(user_id) VALUES (?)")) {
		    $insert_st->bind_param('i', $uid);
		    // Execute the prepared query.
		    if ($insert_st->execute()) {
			$insert_st->close();
			return false;
		    }
		    else
		    {
			$message = "Login Attempts failure: INSERT";
			$_SESSION['Error']=$message;
			$insert_st->close();
			return false;
		    }


		}
		$_SESSION['Error']="Username and password combination is incorrect.";
		return false;
	    }
	}
	else{
	    $_SESSION['Error']="Account is temporarily locked.";
	    return false;
	} 

    }
    else{
	$_SESSION['Error']="Unable to execute query.";
	return false;
    }

}
?>
