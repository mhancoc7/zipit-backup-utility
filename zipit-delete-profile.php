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

// get profile name
$profile = $_GET['profile'];
$profile_file = $profile."-profile.php";

// delete profile config file
if (isset($_GET['profile']) && !empty($_GET['profile']) && $profile_file != 'Full-Backup-Default-profile.php') {
   shell_exec("rm ./excludes/$profile_file");
}

?>
