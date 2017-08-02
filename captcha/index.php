<html>
  <body>
    <form action="" method="post">
   <?php

require_once('../functions.php');
require_once('../../../config/db_config.php');
   $salt=$_GET['ID'];
   if(!is_null($salt))
   {
      sec_session_start(); // Our custom secure way of starting a PHP session.
      $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
      $question=getQuestion($mysqli,$salt);
      if(is_null($question))
      {
         echo $_SESSION['Error'];
      }
      else
      {
         echo $question;
?>
   <!-- get secret question based on code --> 
   <input type="text" placeholder="Secret Answer"/>
<?php

require_once('recaptchalib.php');

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "6LdOjuESAAAAAMk_QzrUDHx5Rdx-dnhYPfWR17cR";
$privatekey = "6LdOjuESAAAAAJhPc2LALffIvb99BavqoKz0SZIa";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                echo "You got it!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }
}
echo recaptcha_get_html($publickey, $error,true);
?>
    <br/>
   
    <input type="submit" value="submit" />
    </form>
  </body>
<?php 
   
      }//closes the $question is null else

      }//closes the ID not null if
   else
   {
      $newURL="https://www.sunjaydhama.com/projects/accounts";
      header('Location: '.$newURL);
   }
?>
</html>
