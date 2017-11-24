<?php

//$allowedExts = array("gif", "jpeg", "jpg", "png");
$allowedExts = array(
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
if($_FILES["file"]["name"])
{
   $tempExplode= explode(".", $_FILES["file"]["name"]);
   $extension = end($tempExplode);
   if ((($_FILES["file"]["type"] == "image/gif")
      || ($_FILES["file"]["type"] == "image/jpeg")
      || ($_FILES["file"]["type"] == "image/jpg")
      || ($_FILES["file"]["type"] == "image/pjpeg")
      || ($_FILES["file"]["type"] == "image/x-png")
      || ($_FILES["file"]["type"] == "image/png"))
      && ($_FILES["file"]["size"] < 2000000000)
      && in_array($extension, $allowedExts))
   {
      if ($_FILES["file"]["error"] > 0)
      {
         echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
      }
      else
      {
         echo "Upload: " . $_FILES["file"]["name"] . "<br>";
         echo "Type: " . $_FILES["file"]["type"] . "<br>";
         echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
         echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

         if (file_exists("/tmp/uploads/" . $_FILES["file"]["name"]))
         {
            echo $_FILES["file"]["name"] . " already exists. ";
         }
         else
         {
            move_uploaded_file($_FILES["file"]["tmp_name"],
               "/usr/share/nginx/html/projects/accounts/images/user_images/" . $_FILES["file"]["name"]);
            echo "Stored in: " . $_FILES["file"]["name"];
         }
         header("Location: https://sunjaydhama.com/projects/accounts/protected_page.php");
         die();
      }
   }
   else
   {
      echo "Invalid file type";
   }
}
else
{
   echo "No file specified";
}
?>
