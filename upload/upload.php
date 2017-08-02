<?php
include_once("../../../config/db_config.php");
include("../../accounts/functions.php");
sec_session_start(); // Our custom secure way of starting a PHP session.
define("UPLOAD_DIR", "/tmp/");
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
/** 
 * pass by reference 
 *
 */
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
function any_uploaded($name) {
   foreach ($_FILES[$name]['error'] as $ferror) {
      if ($ferror != UPLOAD_ERR_NO_FILE) {
         return true;
      }
   }
   return false;
}

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
   echo "You are securely logged in. <br>"; 
   echo "If you are done, please <a href='logout.php'>Log Out</a>.";
}
else
{

   echo "You are not logged in. <br>";
   echo "If you have an account, please <a href='index.php'>sign in</a>. <br>";
   echo "If you don't have a login, please <a href='register.php'>Register</a>.";
}
if (isset($_POST['submit'])){
   if (any_uploaded('files')) {
      $file_ary = reArrayFiles($_FILES['files']);

/*   foreach ($file_ary as $file) {
      print 'File Name: ' . $file['name'];
      print 'File Type: ' . $file['type'];
      print 'File Size: ' . $file['size'];
   }
}
 */
      foreach ($file_ary as $file) {
         //ensure a safe filename
         $name = preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
         //get the extension
         $ext=strtolower(pathinfo($name, PATHINFO_EXTENSION));
         //echo "Extention: " . $ext . "<br>";

         if(!strlen($ext) || (!in_array($ext,$allowed_types)) ) {
            echo "<p>Error: File extension is not one of the allowed types.</p>";
         }
        // $finfo = new finfo(FILEINFO_MIME, MIME_MAGIC_PATH);
         //$finfo = new finfo(FILEINFO_MIME, "/usr/share/misc/magic");
         //if ($finfo) {
           // $mime = $finfo->file($name);
         //}
         //else {
            $mime=$file["type"];
         //}

         //$mime = explode(" ", $mime);
         //$mime = $mime[0];
         echo $mime;

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
                  break;
               }
            }

            //if (($ext == "jpg") && ($_FILES["myFile"]["type"] == "image/jpeg") &&
            if(($file["size"] < 3500000)) {
               $success = move_uploaded_file($file["tmp_name"],UPLOAD_DIR . $name); 
            } else {
               echo "Error: Only .jpg images under 30Kb are accepted for upload";
            } 
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
         }
      
      } //closes loop
   } //closes if
}//closes if
?>
