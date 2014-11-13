<?php
include("../passwords/passwords.php");
define("UPLOAD_DIR", "/tmp/");
session_set_cookie_params(0);
session_start();
/*check to make sure we are logged in*/
check_logged();
/*Logout user*/
if(isset($_GET['logout']))
{
   session_destroy();
   header("location:login.php");
}


$allowed_types = array(
      /* images extensions */
      'jpeg', 'bmp', 'png', 'gif', 'tiff', 'jpg',
      /* audio extensions */
      'mp3', 'wav', 'midi', 'aac', 'ogg', 'wma', 'm4a', 'mid', 'orb', 'aif',
      /* movie extensions */                              
      'mov', 'flv', 'mpeg', 'mpg', 'mp4', 'avi', 'wmv', 'qt',
      /* document extensions */                               
      'txt', 'pdf', 'ppt', 'pps', 'xls', 'doc', 'xlsx', 'pptx', 'ppsx', 'docx'
      );


$black_list= array(
      /*HTML may contain cookie-stealing JavaScript and web bugs*/
      'text/html', 'text/javascript', 'text/x-javascript',  'application/x-shellscript',
      /*PHP scripts may execute arbitrary code on the server*/
      'application/x-php', 'text/x-php', 'text/x-php',
      /*Other types that may be interpreted by some servers*/
      'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh',
      'text/x-c++', 'text/x-c',
      /*Windows metafile, client-side vulnerability on some systems*/
      'application/x-msmetafile',
      /*A ZIP file may be a valid Java archive containing an applet which exploits the
	same-origin policy to steal cookies*/      
      'application/zip',
      );




function reArrayFiles(&$file_post) {

   $file_ary = array();
   $file_count = count($file_post['name']);
   $file_keys = array_keys($file_post);

   for ($i=0; $i<$file_count; $i++) {
      foreach ($file_keys as $key) {
	 $file_ary[$i][$key] = $file_post[$key][$i];
      }
   }

   return $file_ary;
}


$file_ary = reArrayFiles($_FILES['myFile']);

foreach ($file_ary['name'] as $k => $file) {

   if (!empty($k)) {
      //$myFile = $_FILES["file"];
      if ($file["error"] !== UPLOAD_ERR_OK) {
	 echo "<p>An error occurred.</p>";
      }
   }
   else
   {
      /*ensure a safe filename*/
      $name = preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
      //get the extension
      $ext=strtolower(pathinfo($name, PATHINFO_EXTENSION));
      //echo "Extention: " . $ext . "<br>";

      if(!strlen($ext) || (!$allow_all_types && !in_array($ext,$allowed_types))) {
	 echo "<p>Error: File extension is not one of the allowed types.</p>";
      }
      $finfo = new finfo(FILEINFO_MIME, MIME_MAGIC_PATH);
      //$finfo = new finfo(FILEINFO_MIME, "/usr/share/misc/magic");
      if ($finfo) {
	 $mime = $finfo->file($name);
      }
      else {
	 $mime=$_FILES["file"]["type"];
      }

      $mime = explode(" ", $mime);
      $mime = $mime[0];
      echo $mine;

      if (substr($mime, -1, 1) == ";") {
	 $mime = trim(substr($mime, 0, -1));
      }
      if(in_array($mime, $black_list) == true)
      {
	 echo "<p>Error: File extension is not allowed.</p>";
      }

      else
      {
	 // don't overwrite an existing file
	 $i = 0;
	 $parts = pathinfo($name);
	 while (file_exists(UPLOAD_DIR . $name)) {
	    $i++;
	    $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
	    if($i>2)
	    {
	       echo "<p>Error: Too many files of the same name.</p>";
	    }
	 }



	 //if (($ext == "jpg") && ($_FILES["myFile"]["type"] == "image/jpeg") &&
	 if(($_FILES["file"]["size"] < 350000)) {
	    $success = move_uploaded_file($myFile["tmp_name"],UPLOAD_DIR . $name); 
	 } else {
	    echo "Error: Only .jpg images under 30Kb are accepted for upload";
	 } 
	 //echo "Stored in: " . $_FILES["myFile"]["tmp_name"]; 
	 // preserve file from temporary directory
	 if (!$success) {
	    echo "<p>Error: A problem occurred during upload of your file.</p>";
	 }
	 else
	 {
	    print 'File Name: ' . $file['name'];
	    print 'File Type: ' . $file['type'];
	    print 'File Size: ' . $file['size'];


	    echo "<p>Successfully uploaded your file.</p>";
	    //echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	    //echo "Type: " . $_FILES["file"]["type"] . "<br>";
	    //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";

	 }

	 // set proper permissions on the new file
	 chmod(UPLOAD_DIR . $name, 0644);

	 if(isset($_GET['logout']))
	 {
	    session_destroy();
	    header("location:login.php");
	 }
      }
   }
}
?>

<html>
<head>
<title>File Upload Form</title>
</head>
<body>
<!--<p>This form allows you to upload a file to the server.</p>-->


<form id="form1" name="form1" method="get" action="getfile1.php">
<p>
<input type="submit" name="logout" id="logout" value="Logout" />
</p>
</form>
</form>
</body>
</html>

