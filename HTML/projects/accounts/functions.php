<?php

//sec_session_start();
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
function checkbrute($user_id,$mysql) {

    // Get timestamp of current time 
    $now = time();
    // All login attempts are counted from the past 1 hour. 
    $valid_attempts = $now - (60);
    $prep = "SELECT time FROM login_attempts WHERE user_id = ? AND time > ?";
    //$prep = "SELECT time FROM login_attempts WHERE user_id = ?";

    $timez = $mysql->prepare($prep);

         if ($timez) {
            $timez->bind_param('ii', $user_id, $valid_attempts);
            if($timez->execute())
            {
               $timez->store_result();

               // If there have been more than 4 failed logins 
               if ($timez->num_rows > 4) {
                  timez->close();
                  return true;
               } else {
                  timez->close();
                  //mysqli_stmt_close($timez);
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
   if (isset($_SESSION['user_id'], 
      $_SESSION['username'], 
      $_SESSION['login_string'])) {

         $user_id = $_SESSION['user_id'];
         $login_string = $_SESSION['login_string'];
         $username = $_SESSION['username'];

         // Get the user-agent string of the user.
         $user_browser = $_SERVER['HTTP_USER_AGENT'];

         if ($stmt = $mysqli->prepare("SELECT password FROM login WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
               // If the user exists get variables from result.
               $stmt->bind_result($password);
               $stmt->fetch();
               $login_check = $login_string;
               //not sure about this
               //$login_check = hash('sha512', $password . $user_browser);

               if ($login_check == $login_string) {
                  // Logged In!!!! 
                  return true;
               } else {
                  // Not logged in 
                  return false;
               }
            } else {
               // Not logged in 
               return false;
            }
         } else {
            // Not logged in 
            return false;
         }
      } else {
         // Not logged in 
         return false;
      }
}


function sec_session_start() {
   $session_name = 'sec_session_id';   // Set a custom session name
   $secure = false;
   //$secure = SECURE;
   // This stops JavaScript being able to access the session id.
   $httponly = true;
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
      $httponly);
   // Sets the session name to the one set above.
   session_name($session_name);
   session_start();            // Start the PHP session 
   session_regenerate_id();    // regenerated the session, delete the old one. 
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
function checkRegistrationData($username,$email,$pass,$pass1 )
{
   global $feedback;
   // validating the input
   if (!empty($username)
      && strlen($username) <= 64
         && strlen($username) >= 2
         && preg_match('/^[a-z\d]{2,64}$/i', $username)
            && !empty($email)
               && strlen($email) <= 64
               && filter_var($email, FILTER_VALIDATE_EMAIL)
                  && !empty($pass)
                     && !empty($pass1)
                        && ($pass === $pass1)
                           ) {
                              // only this case return true, only this case is valid
                              return true;
                           } elseif (empty($username)) {
                              $feedback = "Empty Username";
                           } elseif (empty($pass) || empty($pass1)) {
                              $feedback = "Empty Password";
                           } elseif ($pass !== $pass1) {
                              $feedback = "Password and password repeat are not the same";
                           } elseif (strlen($pass) < 6) {
                              $feedback = "Password has a minimum length of 6 characters";
                           } elseif (strlen($username) > 64 || strlen($username) < 2) {
                              $feedback = "Username cannot be shorter than 2 or longer than 64 characters";
                           } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $username)) {
                              $feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
                           } elseif (empty($email)) {
                              $feedback = "Email cannot be empty";
                           } elseif (strlen($email) > 64) {
                              $feedback = "Email cannot be longer than 64 characters";
                           } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                              $feedback = "Your email address is not in a valid email format";
                           } else {
                              $feedback = "An unknown error occurred.";
                           }

   // default return
   return false;
}
function checkEmail($mysqli,$email)
{

   $prep_stmt = "SELECT id FROM login WHERE email = ? LIMIT 1";
   $stmt = $mysqli->prepare($prep_stmt);

   if ($stmt) {
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows == 1) {
         // A user with this email address already exists
         $message = "A user with this email already exists!"; 
         $_SESSION['Error'] = $message;
         $stmt->close();
         return false;
      }
         $stmt->close();
      return true;
   }
         $stmt->close();
   return false;
}
function checkUsername($mysqli,$username)
{

   $prep_stmt = "SELECT id FROM login WHERE username = ? LIMIT 1";
   $stmt = $mysqli->prepare($prep_stmt);

   if ($stmt) {
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows == 1) {
         // A user with this email address already exists
         $message = "A user with this username already exists!"; 
         $_SESSION['Error'] = $message;
         $stmt->close();
         return false;
      }
         $stmt->close();
      return true;
   }
         $stmt->close();
   return false;
}
function registerUser($username,$email,$password,$password1)
{

   if(checkRegistrationData($username,$email,$password,$password1))
   {

      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
      /* check connection */
      if (mysqli_connect_errno()) 
      {
         $message = "Connect failed";
         $_SESSION['Error'] = $message;
      }

      // Username validity and password validity have NOT been checked client side.
      // This should be adequate as nobody gains any advantage from
      // breaking these rules.

      if(checkEmail($mysqli,$email))
      {
         if(checkUsername($mysqli,$username))
         {
            $temp_pass=password_hash($password, PASSWORD_BCRYPT,array("cost" => 8))."\n";
            // Insert the new user into the database 
            if ($insert_user = $mysqli->prepare("INSERT INTO login (username, email, password) VALUES (?, ?, ?)")) {
               $insert_user->bind_param('sss', $username, $email, $temp_pass);
               // Execute the prepared query.
               if ($insert_user->execute()) {
                 insert_user->close(); 
                  $_SESSION['user_name'] = $name;
                  $_SESSION['user']=1;
                  $_SESSION['user_is_logged_in'] = true; 
                 mysqli->close(); 
                  return true;
               }
               else
               {
                 insert_user->close(); 
                  $message = "Registration failure: INSERT";
                  $_SESSION['Error'] = $message;
                 mysqli->close(); 
                  return false;

               }
            }
            else {
                 insert_user->close(); 
               $message = "Database error";
               $_SESSION['Error'] = $message;
                 mysqli->close(); 
                  return false;
            }
         }
         else
         {
                 mysqli->close(); 
                  return false;
         }
      }
      else
      {
                 mysqli->close(); 
                  return false;
      }
   }
   else
   {
      $_SESSION['Error'] = $feedback;
                 mysqli->close(); 
                  return false;
   }
}
function getID($mysqli,$username)
   {

      $chk_name= $mysqli->prepare("SELECT id FROM login WHERE username = ?");
      $chk_name->bind_param('s',$username);
      // Execute the prepared query.
      if ($chk_name->execute()) {
         /* bind result variables */
         $chk_name->bind_result($uid);
         $output=$chk_name->fetch();
         if($output)
         {
            chk_name->close();
            return $output;
         }
            chk_name->close();
      }
      return 0;
   }
function insertQuestionsAnswers($question1,$question2,$answer1,$answer2,$username)
{

   $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
   /* check connection */
   if (mysqli_connect_errno()) 
   {
      $message = "Connect failed";
      $_SESSION['Error'] = $message;
      mysqli->close();
      return FALSE;
   }
   $id=getID($mysqli,$username);
   if($id>0)
   {
      //two questions and two answers
      if(insertQuestion($mysqli,$id,$question1,$answer1))
      {
         if(insertQuestion($mysqli,$id,$question2,$answer2))
         {
            mysqli->close();
            return TRUE;
         }
         else
         {

            $message = "question 2 could not be added";
            $_SESSION['Error'] = $message;
         }
      }
      else
      {

         $message = "question 1 could not be added";
         $_SESSION['Error'] = $message;
      }

   }
   else
   {

      $message = "Username could not be found";
      $_SESSION['Error'] = $message;
   }
   mysqli->close();
   return FALSE;
}
function insertQuestion($mysqli,$uid,$question,$answer)
{

   if ($insert_user = $mysqli->prepare("INSERT INTO questionsanswers (id,questionID,answerID) VALUES (?, ?,?)")) {
      $insert_user->bind_param('iis', $uid, $question, $answer);
      // Execute the prepared query.
      if (! $insert_user->execute()) {
         $message = "Registration failure: INSERT";
         $_SESSION['Error'] = $message;
         insert_user->close();
      }
      else
      {
         insert_user->close();
         return true; 
      }
   }
   else {
      $message = "Database error";
      $_SESSION['Error'] = $message;
   }
   return false;
}
?>
