<?php
include_once("../../config/db_config.php");
//include_once("../config/password.php");
/**
 *  * This code will benchmark your server to determine how high of a cost you can
 *   * afford. You want to set the highest cost that you can without slowing down
 *    * you server too much. 10 is a good baseline, and more is good if your servers
 *     * are fast enough.
 *      */
/*$timeTarget = 0.1; 

$cost = 9;
do {
       $cost++;
           $start = microtime(true);
           password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
               $end = microtime(true);
} while (($end - $start) < $timeTarget);

echo "Appropriate Cost Found: " . $cost . "\n";
*/
/**
 *  * In this case, we want to increase the default cost for BCRYPT to 12.
 *   * Note that we also switched to BCRYPT, which will always be 60 characters.
 *    */
//$options = ['cost' => 10,];
//$password = "test";
// $hash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));
echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT,array("cost" => 8))."\n";
?>
