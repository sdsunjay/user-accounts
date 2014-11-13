<?php session_start(); 
include("/var/www/passwords/passwords.php");

if ($_POST['submitted']=='log') { 

   if($_POST['captcha'] == $_SESSION['digit'])
   {
      if(empty($_POST["username"]))
      {
	 echo 'Username is empty!';
      }
      if(empty($_POST["password"]))
      {
	 echo 'Password is empty!';
      }

      $username = trim($_POST["username"]);
      $password = trim($_POST["password"]);


      // check if submitted  username and password exist in $USERS array 
      //checking passwords.php for validity
      if ($USERS[$username]==$password) {
	 $_SESSION["logged"]=$_POST["username"]; 
	 header("Location: upload.php");
      }
      else
      {
	 echo "The username or password does not match";
      }
   } else {
      echo "Sorry cannot submit as you've failed to provide correct captcha! Try again...";
   }
}

?>



<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta https-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Panel</title>
</head>

<body>
<form id='login' action='login.php' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='log'/>

<label for='username' >Username:</label>
<input type='text' name='username' id='username'  maxlength="20" />

<label for='password' >Password:</label>
<input type='password' name='password' id='password' maxlength="20" />
<form method="POST" action="form-handler" onsubmit="return checkForm(this);">

<script type="text/javascript">

function checkForm(form)
{


/*   if(!form.captcha.value.match(/^\d{5}$/)) {
      alert('Please enter the CAPTCHA digits in the box provided');
      form.captcha.focus();
      return false;
   }
*/


   return true;
}

</script>

<!--
<p><img src="/captcha.php" width="120" height="30" border="1" alt="CAPTCHA"></p>
<p><input type="text" size="6" maxlength="5" name="captcha" value=""><br>
<small>copy the digits from the image into this box</small></p>
-->
</form>


<input type='submit' name='Submit' value='Login'  />

</fieldset>
</form>
</body>
</html>
