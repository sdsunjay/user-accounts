
<!DOCTYPE html>
<html lang="en">
<head>

<!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <title>User Accounts: Protected Page</title>

<!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<link rel="icon" href="https://sunjaydhama.com/images/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="https://sunjaydhama.com/images/favicon.ico" type="image/x-icon">

<!-- Meta Content
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->

            <meta charset="utf-8">
            <meta name="keywords" content="Sunjay, Dhama, Accounts, Security, PHP, Javascript, Terminal, HTML, CSS">
            <meta name="description" content="User Accounts: Protected Page">
            <meta name="author" content="Sunjay Dhama">
            <meta https-equiv="content-type" content="text/html; charset=utf-8" />

<!-- SEO -->
<link rel="canonical" href="https://sunjaydhama.com/" />

<!-- Facebook -->
<meta property="og:image" content="https://sunjaydhama.com/images/selfie.jpg"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="User Accounts: Protected Page"/>
<meta property="og:url" content="https://sunjaydhama.com/"/>
<meta property="og:site_name" content="sunjaydhama.com"/>
<meta property="og:description" content="I'm a Software Engineer and am passionate about security, currently based in Oakland, California."/>
<meta property=”og:locale” content=”en_US” />

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:creator" content="@sdsunjay">
<meta name="twitter:title" content="User Accounts: Protected Page">
<meta name="twitter:description" content="I'm a Software Engineer and am passionate about security, currently based in Oakland, California."/>
<meta name="twitter:image:src" content="https://sunjaydhama.com/images/selfie.jpg">

<!-- Google+ -->
<meta itemprop="name" content="Sunjay Dhama">
<meta itemprop="description" content="I'm a Software Engineer and am passionate about security, currently based in Oakland, California.">
<meta itemprop="image" content="https://sunjaydhama.com/images/selfie.jpg">

<!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Inconsolata' rel='stylesheet' type='text/css'>

<!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<link rel="stylesheet" href="../../css/normalize.css">
<link rel="stylesheet" href="../../css/skeleton.css">
<!--
<link rel="stylesheet" type="text/css" href="css/style.css">
-->

<link rel="stylesheet" type="text/css" href="../../css/login_style.css">
</head>
<body>


<!-- Nav
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<nav>
   <ul>
      <li><a href="../../about.html">ABOUT</a></li>
      <li><a href="../../projects.html">PROJECTS</a></li>
      <li>
      <a href="../../index.html">
         <img id="navlogo" src="../../images/logo.png" alt="navagation-logo"></a></li>
      <li><a href="../../resume.html">R&Eacute;SUM&Eacute;</a></li>
      <li><a href="../../contact.html">CONTACT</a></li>
   </ul>
</nav>
<!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<div class="shell">
   <div class="contact">
      <div class="content-contactform">




<?php
include_once("../../config/db_config.php");
include_once("../accounts/functions.php");
function user_role($mysqli, $username)
{

   $chk_name= $mysqli->prepare("SELECT id FROM users WHERE username = ?");
   $chk_name->bind_param('s',$username);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($uid);
      $output=$chk_name->fetch();

      //must remove last character, I have no idea why?
      mysqli_stmt_close($chk_name);
   }

   $chk_name= $mysqli->prepare("SELECT role_id FROM user_roles WHERE user_id = ?");
   $chk_name->bind_param('i',$uid);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($role_id);
      $output=$chk_name->fetch();

      mysqli_stmt_close($chk_name);
   }
   $chk_name= $mysqli->prepare("SELECT role FROM roles WHERE id = ?");
   $chk_name->bind_param('i',$role_id);
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($role);
      $output=$chk_name->fetch();

      //must remove last character, I have no idea why?
      mysqli_stmt_close($chk_name);
   }
   if(strcmp($role,"admin") == 0)
   {
      return True;
   }
   else
   {
      return False;
   }

}
function showInfo($mysqli)
{

   $chk_name= $mysqli->prepare("SELECT username FROM users");
   // Execute the prepared query.
   if ($chk_name->execute()) {
      /* bind result variables */
      $chk_name->bind_result($name);
      while($chk_name->fetch()){
         echo $name . '<br />';
      }
      $chk_name->free_result();
      mysqli_stmt_close($chk_name);
   }
}
//if(session_id() == '' || !isset($_SESSION)) {
// session isn't started
//   session_start();            // Start the PHP session 
//   session_regenerate_id();    // regenerated the session, delete the old one. 
//}


sec_session_start($_COOKIE["username"]);

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
/* check connection */
if (mysqli_connect_errno()) 
{
   printf("Connect failed: %s\n", mysqli_connect_error());
   echo "Connect: failed";
   exit();
}
/*check to make sure we are logged in*/
if(login_check($mysqli))
{
   echo "Welcome " .$_COOKIE["username"]."<br>";
?>
                 <a href="#" id="try-1" class="try sprited">Change Your Password</a>
            <div id="sign_up">
               <h3>Update Password</h3>
               <p>
               Passwords must be at least 8 characters long
               <br>
               Passwords must contain:
               <br>
               At least one upper case letter (A..Z)
               <br>
               At least one lower case letter (a..z)
               <br>
               At least one number (0..9)</p>
               <div id="sign_up_form">
                  <label><input type="password" id="old_password" name="old_password" size="64" placeholder="Old Password" class="sprited" autocomplete="off"/></label>
                  <label><input type="password" id="new_password" name="new_password" size="64" placeholder="New Password" class="sprited" autocomplete="off"/></label>
                  <label><input type="password" id="new_password1" name="new_password1" size="64" placeholder="Confirm Password" class="sprited" autocomplete="off"/></label>
                  <div id="actions">
                     <!--<a class="close form_button sprited" id="cancel" href="#">Cancel</a>-->
                     <input id="submit" type="submit" name="submit" value="Update" onclick="window.location='./protected_page.php';" />
               </div>
               </div>
   </div>
<br>
<!-- IN PROGRESS -->
                <a href="#" id="upload_button" class="upload_class sprited">Upload a Picture</a>
<br>          
  <div id="upload">
               <h3>Upload a Picture</h3>
               <p>Only .JPG images less than 1MB </p>
                  <form action="upload/upload_file.php" method="post"
                     enctype="multipart/form-data">
                     <label for="file">Filename:</label>
                     <input type="file" name="file" id="file"><br>
                     <input type="submit" name="submit" value="Submit">
                  </form>
                  <!--<div id="actions">
                     <a class="close form_button sprited" id="cancel" href="#">Cancel</a>
                  </div>
                  -->
         </div>
   </div>
<?php


   if(user_role($mysqli, $_SESSION['username']))
   {
      //echo "<h3><a href='../airport/admin.php'>Airport Admin</a></h3>";
      //echo "You are an administrator <br>";i
      echo "<h3> Users </h3>";

      showInfo($mysqli);

      //show all tables
   }
   else
   {
      echo "You are <b> not </b> an administrator <br>";

   }
   echo "You are securely logged in. <br>"; 
   echo "If you are done, please <a href='logout.php'>Log Out</a>.";

}
else
{

   echo "You are not logged in. <br>";
   echo "If you have an account, please <a href='index.html'>sign in</a>. <br>";
   echo "If you don't have a login, please <a href='register.html'>Register</a>. <br>";
}
?>
<br>
      </div>
   </div>
</div>
<!-- Scripts
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- Google Analytics -->
<script type="text/javascript" src="../../js/googleAnalytics.js"></script>
<!-- Lightbox Script -->
<script type="text/javascript" src="../../js/jquery.lightbox_me.js"></script>
<!-- Upload Picture Script -->
<!-- currently residing on this page -->
<!-- TODO move to its own file -->
<!--
<script language="javascript" type="text/JavaScript" src="js/upload.js"></script>  
-->
<script language="javascript" type="text/JavaScript" src="js/forms.js"></script>
<!-- Change Password Script -->
<script language="javascript" type="text/JavaScript" src="js/change_password.js"></script>
<!-- Lightbox Script for Login -->
<script type="text/javascript" charset="utf-8">
$(function() {
      function launch() {
      $('#sign_up').lightbox_me({centered: True, onLoad: function() { $('#sign_up').find('input:first').focus()}});
      }
      $('#try-1').click(function(e) {
         $("#sign_up").lightbox_me({centered: True, preventScroll: True, onLoad: function() {
            $("#sign_up").find("input:first").focus();
            }});
         e.preventDefault();
         });
      $('table tr:nth-child(even)').addClass('stripe');
      });
</script>
<!-- Lightbox Script for Upload -->
<script type="text/javascript" charset="utf-8">
$(function() {
   function launch() {
      $('#upload').lightbox_me({centered: True, onLoad: function() { $('#upload').find('input:first').focus()}});
            }
            $('#upload_button').click(function(e) {
               $("#upload").lightbox_me({centered: True, preventScroll: True, onLoad: function() {
                  $("#upload").find("input:first").focus();
                                }});
                e.preventDefault();
            });
            $('table tr:nth-child(even)').addClass('stripe');
        });
</script>
</body>
</html>
