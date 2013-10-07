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

// get profile for backup
$profile = $_GET['profile'];

// generate hash to create progress file
$progress_hash = substr(hash("sha512",rand()),0,12); // Reduces the size to 12 chars

// get name of progress file. This will keep on demand backups from colliding with auto backups
$progress_file = $progress_hash. "-progress.php";

// update progress file
file_put_contents($progress_file,'<br/><center>Initializing...<br/><img src="images/progress.gif"/></center>');

?>

<script src="js/jquery.js"></script>

<script type="text/javascript">
   function checkForData ( ) {
      $.post('<?php echo $progress_file; ?>',false,function(data){
         if(data.length){
// Display the current progress
            document.getElementById('progress').innerHTML = data;
         } 
         else {
// No need to show anything if there isn't anything happening
         }  
      });
   }
   
// Start the timer when the page is done loading:
   $(function(){
// First Check 
      checkForData();
// Start Timer
      var refreshIntervalId = setInterval('checkForData()',1000); // 1 Second Intervals
         $.post('zipit-zip-files-process.php?auth=<?php echo $auth_hash; ?>&progress=<?php echo $progress_hash; ?>&profile=<?php echo $profile; ?>', function(data) {
            $('.result').text(data);
            clearInterval(refreshIntervalId);
         });
   });
</script>

<link href="css/style.css" rel="stylesheet" type="text/css">
<span id="progress"></span>
