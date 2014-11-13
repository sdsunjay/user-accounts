<?php
session_set_cookie_params(0);
session_start();  
include("../passwords/passwords.php");
   /*check to make sure we are logged in*/
   check_logged();
?>

<html>
<head>
<title>File Upload Form</title>
</head>
<body>
<p>This form allows you to upload multiple files to the server.</p>


<form action="getfile1.php" method="post" enctype="multipart/form-data">
Type (or select) Filename: <input type="file" multiple="true" name="userfile[]">
        <input type="submit" value="Send files" />
	</form>


<!--<form action="getfile1.php" method="post" enctype="multipart/form-data"><br>
Type (or select) Filename: <input type="file" multiple="true" name="myFile[]">
<input type="hidden" name="MAX_FILE_SIZE" value="2500000" />-->
<input type="submit" value="Upload">
</form>
</body>
</html>



