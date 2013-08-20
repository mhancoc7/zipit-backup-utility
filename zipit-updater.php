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

// generate hash to create progress file
$progress_hash = substr(hash("sha512",rand()),0,12); // Reduces the size to 12 chars

// get name of progress file. This will keep on demand backups from colliding with auto backups
$progress_file = $progress_hash. "-progress.php";

file_put_contents("../$progress_file",'<link href="css/iframe_style.css" rel="stylesheet" type="text/css"><br/><center>Initializing...<br/><img src="images/progress.gif"/></center>');

// grab the updater from Github
shell_exec('wget https://raw.github.com/jeremehancock/zipit-backup-utility-updater/master/zipit-update.php --no-check-certificate -O ../zipit-update.php');

shell_exec("php ../zipit-update.php $progress_hash >/dev/null 2>/dev/null &");

?>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
  function checkForData ( ) {
    jQuery.get('<?php echo "../".$progress_file; ?>',false,function(data){
      if(data.length){
        // Display the current progress
        document.getElementById('progress').innerHTML = data;
      }else{
        // No need to show anything if there isn't anything happening
      }
    });
  }
  // Start the timer when the page is done loading:
  $(function(){
    // First Check 
    checkForData();
    // Start Timer
    setInterval('checkForData()',1000); // 3 Second Intervals
  });
</script>
<span id="progress"></span>
