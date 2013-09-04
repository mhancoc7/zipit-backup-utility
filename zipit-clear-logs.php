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

// define zipit log file
$zipitlog = "../../../logs/zipit.log";

// overwrite log file
$fp = fopen("$zipitlog","w");  
fwrite($fp,"----Zipit Logs----\n\n");  
fclose($fp); 

?>
