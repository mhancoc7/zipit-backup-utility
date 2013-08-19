<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// include password protection
    require("zipit-login.php"); 

// require zipit configuration
    require('zipit-config.php');

// Grab remote version of Zipit for update feature
$ch = curl_init("https://raw.github.com/jeremehancock/zipit-updater/master/zipit-update.php");
$fp = fopen("../zipit-update.php", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);

shell_exec("php ../zipit-update.php");

?>

<center>Updating please wait...<br/>
<img src="images/progress.gif"/></center>
<br/>
