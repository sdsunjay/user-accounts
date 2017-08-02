
<head>
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta https-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Secure Login: Protected Page</title>
<link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../gui/style.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script type="text/javascript" src="../../gui/jquery.formerize-0.1.js"></script>
<script language="javascript" type="text/JavaScript" src="../accounts/js/login.js"></script>
      <link rel="stylesheet" type="text/css" href="style.css">
	<script src="../../js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
        $(function() {
            function launch() {
                 $('#sign_up').lightbox_me({centered: true, onLoad: function() { $('#sign_up').find('input:first').focus()}});
            }
            $('#try-1').click(function(e) {
                $("#sign_up").lightbox_me({centered: true, preventScroll: true, onLoad: function() {
					$("#sign_up").find("input:first").focus();
				}});
                e.preventDefault();
            });
            $('table tr:nth-child(even)').addClass('stripe');
        });
    </script>

</head>
<body>
<div id="wrapper">
<div id="header">
  <div id="logo">
    <h1><strong>Sunjay's Homepage</strong></h1>
  </div>
  <div id="search">
    <form action="" method="post">
      <div>
        <input class="form-text" name="search" size="44" maxlength="100" title="Search my website" />
      </div>
    </form>
  </div>
 	<div id="menu">
                        <ul>
                             <li class="https://sunjaydhama.com"><a href="../../gui/index.html">Home</a></li>
                             <li><a href="../../gui/about.html">About</a></li>
                             <li><a href="../../gui/images.html">Images</a></li>
                             <li><a href="../../blog">Blog</a></li>
                             <li><a href="../../gui/projects.html">Projects</a></li>
                             <li><a href="../../gui/contact.html">Contact Me</a></li>
                        </ul>
			<br class="clearfix" />
		</div>
	</div>
	<div id="page">
		<div id="sidebar">
			<h3>Sidebar</h3>
			<ul class="list">
                         <li class="first"><a href="https://sunjaydhama.com">Terminal</a></li>
                         <li><a href="#" onClick="window.open('https://github.com/sdsunjay?tab=repositories', 'external');">Github</a></li>
                      
      <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/sdsunjay" data-widget-id="459207762094735360">Tweets by @sdsunjay</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^https:/.test(d.location)?'https':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
  </ul>
</div>
<div id="content">
<div id="post1">
<!--<button name = "change" type="button"  text="Change Password">Change Password</button>-->
<?php
include_once("../../config/db_config.php");
include("../accounts/functions.php");
sec_session_start(); // Our custom secure way of starting a PHP session.
function showInfo($mysqli)
{

   $chk_name= $mysqli->prepare("SELECT username FROM login");
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

   echo "Welcome " .$_SESSION['user_name']."<br>";
?>

                    <a href="#" id="try-1" class="try sprited">Change Your Password</a>
            <div id="sign_up">
               <h3>Upload File</h3>
               <div id="sign_up_form">
                  <form action="upload_file.php" method="post"
                     enctype="multipart/form-data">
                     <label for="file">Filename:</label>
                     <input type="file" name="file" id="file"><br>
                     <input type="submit" name="submit" value="Submit">
                  </form>
                  <div id="actions">
                     <a class="close form_button sprited" id="cancel" href="#">Cancel</a>
                     <a href="#">Update</a>
                  </div>
               </div>
            </div>
<?php   
if(user_role($mysqli,$_SESSION['user_name']))
   {
      echo "<h4><a href='./account.html'>Update Password</a></h4>";
   }
   else
   {
      //echo "You are <b> not </b> an administrator <br>";

   }
   echo "You are securely logged in. <br>"; 
   echo "If you are done, please <a href='logout.php'>Log Out</a>.";
   
}
else
{

   echo "You are not logged in. <br>";
   echo "If you have an account, please <a href='index.php'>sign in</a>. <br>";
   echo "If you don't have a login, please <a href='register.php'>Register</a>.";
}
//sec_session_start();
?>


</div>
</div>
</div>
</div>
                  <div id="footer"> Copyright (c) 2014 <a href="https://www.sunjaydhama.com/">Sunjay Dhama</a>. All rights reserved. Template by <a href="https://www.freecsstemplates.org/">CSS Templates</a><br>
<a class='facebook' href='#' onclick="window.open('https://www.linkedin.com/in/sdsunjay','external');" >
                        <img alt='' src='../../gui/images/li.png' width="30" height="30" /></a>
                     <a class='twitter' href='#' onclick="window.open('https://www.twitter.com/sdsunjay','external');" >
                        <br>
                        <img alt='' src='../../gui/images/twitt.jpg' width="30" height="30"/></a>
                     <br>
                     <a href="https://bitcoin.org" target="_NEW">BitCoin</a>: <b><a href="bitcoin:1Shn9NDCuHeAeDDaHtCb9RFMV1kQr6uZx">1Shn9NDCuHeAeDDaHtCb9RFMV1kQr6uZx</a></b><br />
                  </div>
               </body>
            </html>
