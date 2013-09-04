<?php 
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// include password protection
require_once("zipit-login.php"); 

// define zipit log file
$zipitlog = "../../../logs/zipit.log";
$logsize = filesize($zipitlog);

// create zipit log file if it doesn't exist
if (!file_exists("$zipitlog")) { 
   $fp = fopen("$zipitlog","w");  
   fwrite($fp,"----Zipit Logs----\n\n");  
   fclose($fp); 
}

if ($logsize > 52428800) {
   shell_exec("mv ../../../logs/zipit.log ../../../logs/zipit_old.log");
}

?>

<link href="css/style.css" rel="stylesheet" type="text/css">
<div class="logs">

<?php
// include logs class
require_once("lib/class.displaylogs.php"); 

$lfDispl = new displayLogfile; 

// Path/Name of Logfile 
$filename = $zipitlog; 

?> 

<pre style="font-size:12px;"> 

<?php

$lfDispl->setRowsToRead(10000);    // Read 100 rows 
$lfDispl->setAlign("bottom");       // Last row on top 
$lfDispl->setFilepath($filename); // from this logfile 
$lfDispl->setLineBreak(150);  // Break the row after 150 chars 
$lfDispl->returnFormated();   // Output  

?> 

</pre> 
</div>


