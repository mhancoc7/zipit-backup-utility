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

// get database to backup
   $db = $_GET['db'];

   if ($auth_hash == $auth) {

    $cmd = "php zipit-zip-db-worker.php $auth_hash $progress_hash $db";
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


