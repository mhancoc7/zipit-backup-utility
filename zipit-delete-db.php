<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// include password protection
require_once("zipit-login.php"); 

// require zipit configuration
require('zipit-config.php');

// get database name
$db = $_GET['db'];
$db_file = $db."-config.php";

// delete database config file
if (isset($_GET['db']) && !empty($_GET['db'])) {
   shell_exec("rm ./dbs/$db_file");
}

?>
