<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// require zipit configuration
   require('zipit-config.php');

// get Auth Hash to protect this file from being run unintentially
   $auth = $_GET['auth'];

// get name of progress file. This will keep on demand backuups from colliding with auto backups
   $progress_hash = $_GET['progress'];

   if ($auth_hash == $auth) {

    $cmd = "php ../zipit-update.php $progress_hash";
    $pipe = popen($cmd, 'r');

    if (empty($pipe)) {
    throw new Exception("Unable to open pipe for command '$cmd'");
    }

    stream_set_blocking($pipe, false);

    while (!feof($pipe)) {
    fread($pipe, 10240);
    sleep(1);

    flush();
    }


    pclose($pipe);

}

else {

   die();
   }

?>


