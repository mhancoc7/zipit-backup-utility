<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// specify namespace
   namespace OpenCloud;

// include password protection
    require("zipit-login.php"); 

// require zipit configuration
    require('zipit-config.php');

// define zipit log file
    $zipitlog = "../../../logs/zipit.log";
    $logsize = filesize($zipitlog);

// create zipit log file if it doesn't exist
if(!file_exists("$zipitlog")) 
{ 
   $fp = fopen("$zipitlog","w");  
   fwrite($fp,"----Zipit Logs----\n\n");  
   fclose($fp); 

}

if ($logsize > 52428800) {
shell_exec("mv ../../../logs/zipit.log ../../../logs/zipit_old.log");
}

// clean up local file backups if files are older than 24 hours (86400 seconds)
$dir = "./zipit-backups/files/";
 
 
    if ($handle = opendir($dir)) {
    while (( $file = readdir($handle)) !== false ) {
        if ( $file == '.' || $file == '..' || is_dir($dir.'/'.$file) ) {
            continue;
        }
 
        if ((time() - filemtime($dir.'/'.$file)) > 86400) {
            shell_exec("rm -rf ./zipit-backups/files/$file");
        }
    }
    closedir($handle);

}

// clean up local database backups if files are older than 24 hours (86400 seconds)
$dir = "./zipit-backups/databases/";
 
 
    if ($handle = opendir($dir)) {
    while (( $file = readdir($handle)) !== false ) {
        if ( $file == '.' || $file == '..' || is_dir($dir.'/'.$file) ) {
            continue;
        }
 
        if ((time() - filemtime($dir.'/'.$file)) > 86400) {
            shell_exec("rm -rf ./zipit-backups/databases/$file");
        }
    }
    closedir($handle);

}

// generate hash to create progress file
$progress_hash_files_continuous = substr(hash("sha512",rand()),0,12); // Reduces the size to 12 chars
$progress_hash_files_daily = substr(hash("sha512",rand()),0,12); // Reduces the size to 12 chars
$progress_hash_databases_continuous = substr(hash("sha512",rand()),0,12); // Reduces the size to 12 chars
$progress_hash_databases_daily = substr(hash("sha512",rand()),0,12); // Reduces the size to 12 chars

// get installed installed version
$installed_version = "zipit-version.php";
$fh = fopen($installed_version, 'r');
$display_version = fread($fh, 5);
fclose($fh);

// check for new version

// Check for newer versions of script
function file_get_data($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$latest_version = file_get_data('https://raw.github.com/jeremehancock/zipit-backup-utility/master/zipit-version.php');
$latest_version = preg_replace( "/\r|\n/", "", $latest_version );

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="google" value="notranslate">

<link rel="stylesheet" href="css/colorbox.css" />
<link rel="stylesheet" href="css/zipit/jquery-ui.css" />
<link href="css/style.css" rel="stylesheet" type="text/css">
		<script src="js/jquery.js"></script>
  <script src="js/jquery-ui.js"></script>

		<script src="js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".backup-files").colorbox({iframe:true, width:"400px", height:"130px", closeButton:false, escKey:false, overlayClose:false, scrolling:false, top: "25%" });
			});
			$(document).ready(function(){
				$(".backup-database").colorbox({iframe:true, width:"400px", height:"130px", closeButton:false, escKey:false, overlayClose:false, scrolling:false, top: "25%" });
			});

                        $(document).ready(function(){
				$(".add-db").colorbox({iframe:true, width:"400px", height:"500px", closeButton:true, escKey:true, overlayClose:true, scrolling:false, top: "15%" });
			});
                        $(document).ready(function(){
				$(".edit-db").colorbox({iframe:true, width:"400px", height:"500px", closeButton:true, escKey:true, overlayClose:true, scrolling:false, top: "15%" });
			});
		</script>

<script>
  $(function() {
    $( document ).tooltip({
      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        }
      }
    });
  });
  </script>
</head>
<body>
<a href="https://github.com/jeremehancock/zipit" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub"></a>
<div id="wrapper">
  <h1>Zipit Backup Utility <div class="version_info" id="version_info">v<?php echo $display_version; ?></h1>
<div id="logout">
<a href="index.php?logout=1" title="Logout"><img src="images/logout.png" /></a>
</div>
  <div id="tabContainer">
    <div id="tabs">
      <ul onclick="refreshLogs();refreshDb();refreshFiles();updateDbMenu();updateDbMenuSchedule();">
        <li id="tabHeader_1">Home</li>
        <li id="tabHeader_2">Files</li>
        <li id="tabHeader_3">Databases</li>
        <li id="tabHeader_4">Schedule</li>
        <li id="tabHeader_5">Logs</li>
        <li id="tabHeader_6">Settings</li>
        <li id="tabHeader_7">Troubleshooting</li>
      </ul>
    </div>
    <div id="tabscontent">

<div class="tabpage" id="tabpage_1">
        <p><br/>The Zipit Backup Utility is designed for use with Rackspace Cloud Sites&reg;.<br/><br/>Zipit is an unofficial tool built by a Racker to assist Cloud Sites&reg; customers. <br/><br/>Zipit is not an "official" Rackspace&reg; tool. <br/><br/><h3>Additional info:</h3><ul><li><a href="http://www.rackspace.com/knowledge_center/article/zipit-backup-utility" target="_blank">Knowledge Center Article <img src="images/open_in_new_window.png" /></a></li><li><a href="https://community.rackspace.com/products/f/26/t/445" target="_blank">Community Forums <img src="images/open_in_new_window.png" /></a></li><li><a href="https://github.com/jeremehancock/zipit" target="_blank">Github Page <img src="images/open_in_new_window.png" /></a></li></ul></p>

<p><?php if ($display_version < $latest_version) {echo "<br/>There is a new version of Zipit available! <a href='zipit-updater.php'>Click here to update</a>";} ?></p>
      </div>
      <div class="tabpage" id="tabpage_2" style="display: none;">
        <h2>Available File Backups</h2>
<center><iframe src="zipit-view-files.php" class="files_frame" frameborder="0" scrolling="auto" name="files-list"></iframe><br/><br/></center>
<?php 
echo "<center>You can manage your backups via the <a href='https://mycloud.rackspace.com/a/$username/files#object-store%2CcloudFiles%2CORD/zipit-backups-files-$url/' target='_blank'>Cloud Files control panel <img src='images/open_in_new_window.png' /></a>";	
echo "<br/>";
echo "</center></em><br/>";
?>


        <p><center><a class='backup-files' href="zipit-zip-files.php"><button type="button" class="css3button" style="margin-right:15px;">Backup Now</button></a><button type="button" class="css3button" id="refresh-files" onclick="refreshFiles();">Refresh List</button></center></p>
    <script type="text/javascript">

       function refreshFiles() {
    var ifr = document.getElementsByName('files-list')[0];
    ifr.src = ifr.src;
}
    </script>
      </div>
      <div class="tabpage" id="tabpage_3" style="display: none;">
        <h2>Available Database Backups</h2>

<center><iframe src="zipit-view-db.php" class="dbs_frame" frameborder="0" scrolling="auto" name="db-list"></iframe><br/><br/></center>
<?php 
echo "<center>You can manage your backups via the <a href='https://mycloud.rackspace.com/a/$username/files#object-store%2CcloudFiles%2CORD/zipit-backups-databases-$url/' target='_blank'>Cloud Files control panel <img src='images/open_in_new_window.png' /></a>";	
echo "<br/>";
echo "</center></em><br/>";
?>

<script type = "text/javascript">

function showBackupButton() {

var val = document.db_form.db_select.value; 

if (val == "Select Database") {
document.getElementById("backup-database").style.display="none"; 
document.getElementById("edit-db").style.display="none";
}
else {
document.getElementById("backup-database").style.display="";  
document.getElementById("edit-db").style.display="";
}


}

function display_Schedule() {

var val = document.db_form_schedule.db_select_schedule.value; 

if (val == "Select Database") {
document.getElementById("databases_continuous").value="Select Database From Dropdown"; 
document.getElementById("databases_daily").value="Select Database From Dropdown"; 
}
else {
document.getElementById("databases_continuous").value="web/content/zipit/zipit-zip-db-worker.php <?php echo $auth_hash.' '.$progress_hash_databases_continuous;?>"+val+" "+val+ " auto "; 
document.getElementById("databases_daily").value="web/content/zipit/zipit-zip-db-worker.php <?php echo $auth_hash.' '.$progress_hash_databases_daily;?>"+val+" "+val+" auto daily"; 
}


}


function update(objS)
{
document.links["backup-database"].href = "zipit-zip-db.php?db=" + objS.options[objS.selectedIndex].value;
document.links["edit-db"].href = "zipit-add-db.php?db=" + objS.options[objS.selectedIndex].value;
}

$(document).ready(function(){
           $("#db_menu").load("zipit-db-menu.php");
           $("#db_menu_schedule").load("zipit-db-menu-schedule.php");
});


function updateDbMenu()
{
        $('#db_menu').load('zipit-db-menu.php');
        document.getElementById("backup-database").style.display="none"; 
        document.getElementById("edit-db").style.display="none";
}

function updateDbMenuSchedule()
{
        $("#db_menu_schedule").load("zipit-db-menu-schedule.php");
        document.getElementById("databases_continuous").value="Select Database From Dropdown"; 
        document.getElementById("databases_daily").value="Select Database From Dropdown"; 
}

function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}

</script>

<div id="db_menu" class="db_menu"><!-- database menu loads here --></div>

        <p><center><a id="backup-database" class="backup-database" style="display:none;padding-right:15px;" href=""><button type="button" class="css3button">Backup Now</button></a><a id="edit-db" class='edit-db' style="display:none;padding-right:15px;" href=""><button type="button" class="css3button">Edit Credentials</button></a><a class='add-db' style="padding-right:15px;" href="zipit-add-db.php"><button type="button" class="css3button" onclick="updateDbMenu();">Add Credentials</button></a><button type="button" class="css3button" id="refresh-db" onclick="refreshDb();">Refresh List</button></center></p>
<script type="text/javascript">
       function refreshDb() {
    var ifr = document.getElementsByName('db-list')[0];
    ifr.src = ifr.src;
}
    </script>
      </div>
      <div class="tabpage" id="tabpage_4" style="display: none;">
        <h2>Schedule</h2>
You can easily automate Zipit via a Scheduled Task (cronjob) via the Cloud Sites Control Panel. <br/><br/>

Below you will find the "Commands" to use for the Scheduled Task (cronjob).<br/><br/> Be sure to set the "Command Language" to php!  <br/><br/>For more info on setting up a Scheduled Task (cronjob) in Cloud Sites click <a href="http://www.rackspace.com/knowledge_center/article/how-do-i-schedule-a-cron-job-for-cloud-sites" target="_blank">here <img src='images/open_in_new_window.png' /></a>.<br/><br/>
<div id="div1" class="alldivs"> <p><h4>Files Options:</h4><br/>Continuous:<img src="images/hint.png" style="width:13px" title="Use this command to create a new backup each time the Scheduled Task (cronjob) runs without any rotation." /><br/><input class="files_continuous" name="files_continuous" type="text" id="files_continuous" value="web/content/zipit-zip-files-worker.php <?php echo $auth_hash.' '.$progress_hash_files_continuous;?> auto" readonly onClick="SelectAll('files_continuous');"><br/><br/>
Daily Rotation:<img src="images/hint.png" style="width:13px" title="Use this command to create a backup for each day of the week and rotate weekly. For this to function properly you must setup the Scheduled Task (cronjob) to run once per day. Keep in mind that when the rotation occurs the previous backup for that day will be overwritten and cannot be recovered!" /><br/><input class="files_daily" name="files_daily" type="text" id="files_daily" value="web/content/zipit-zip-files-worker.php <?php echo $auth_hash.' '.$progress_hash_files_daily;?> auto daily" readonly onClick="SelectAll('files_daily');"></div>

<div id="div2" class="alldivs"> <p><div id="db_menu_schedule" class="db_menu_schedule"><!-- database menu loads here --></div><h4>Database Options:</h4><br/>Continuous:<img src="images/hint.png" style="width:13px" title="Use this command to create a new backup each time the Scheduled Task (cronjob) runs without any rotation." /><br/><input class="databases_continuous" name="databases_continuous" type="text" id="databases_continuous" value="Select Database From Dropdown" readonly onClick="SelectAll('databases_continuous');"><br/><br/>
Daily Rotation:<img src="images/hint.png" style="width:13px" title="Use this command to create a backup for each day of the week and rotate weekly. For this to function properly you must setup the Scheduled Task (cronjob) to run once per day. Keep in mind that when the rotation occurs the previous backup for that day will be overwritten and cannot be recovered!" /><br/><input class="databases_daily" name="databases_daily" type="text" id="databases_daily" value="Select Database From Dropdown" readonly onClick="SelectAll('databases_daily');"></div>
</p>
      </div>
      <div class="tabpage" id="tabpage_5" style="display: none;">
        <h2>Logs</h2>
        <center><iframe src="zipit-logs.php" class="logs_frame" frameborder="0" scrolling="auto" name="logs-list"></iframe><br/><br/></center>
<p><center><button type="button" class="css3button" id="refresh-logs" onclick="refreshLogs();">Refresh Logs</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="css3button" id="clear" onclick="return confirmClearLogs();">Clear Logs</button></center></p>
    <script type="text/javascript">

    function confirmClearLogs() {
        if (confirm('Are you sure you want to clear your log?\n\nThis can\'t be undone!')) {
            //Make ajax call
            $.ajax({
                url: "zipit-clear-logs.php",
                type: "POST",
                data: {id : 5},
                dataType: "html", 
                success: function() {
                    refreshLogs();
                }
            });

        }
    }

       function refreshLogs() {
    var ifr = document.getElementsByName('logs-list')[0];
    ifr.src = ifr.src;
}

</script>

      </div>
      <div class="tabpage" id="tabpage_6" style="display: none;">
        <h2>Settings</h2>
        <center><iframe src="zipit-settings.php" class="settings_frame" frameborder="0" scrolling="no" name="settings"></iframe><br/><br/></center>
      </div>
<div class="tabpage" id="tabpage_7" style="display: none;">
        <h2>Troubleshooting</h2>

 <script>
  $(function() {
    $( "#accordion" ).accordion({
      collapsible: true,
      heightStyle: "content",
active: false
    });
  });
  </script>
<div id="accordion">
  <h3>Zip Failed!</h3>
  <div>
<div class="cause_fix">Cause:</div>
    <p>This error generally indicates that your site/database is larger than 4gb once zipped.</p><br/>
<div class="cause_fix">Solution:</div>
<p>You will need to reduce the size of your site/database to fix this issue.</p>
  </div>
  <h3>Backup Failed Integrity Check!</h3>
  <div>
    <div class="cause_fix">Cause:</div>
    <p>This error indicates that the backup was moved to Cloud Files®,however, the integrity check on the backup failed. This generally indicates that the backup was corrupted during transfer. This can be caused by various factors.</p><br/>
<div class="cause_fix">Solution:</div>
<p>Be sure that you have a good internet connection and try again.</p>
  </div>
  <h3>Authorization Failed!</h3>
  <div>
    <div class="cause_fix">Cause:</div>
    <p>This error indicates that the "Auth Hash" that was used to run Zipit did not match the value within the Zipit configuration. This error is commonly seen if the Scheduled Task option is setup with the wrong "Auth Hash". </p><br/>
<div class="cause_fix">Solution:</div>
<p>Be sure that you use the exact command for the Scheduled Task that is found on the Schedule tab. You can set the "Auth Hash" on the Settings tab.</p>
  </div>
  <h3>Can't Write to Log!</h3>
  <div>
       <div class="cause_fix">Cause:</div>
    <p>This error indicates that Zipit was unable to write to the zipit.log file in your logs folder. This is generally caused by having Zipit installed outside of the site's content folder. Another common cause is if Zipit was copied from one site to another using a secondary FTP user. </p><br/>
<div class="cause_fix">Solution:</div>
<p>Zipit is designed to be installed with the Zipit Installer and not moved manually. Be sure to use the Zipit Installer for the intial installation of Zipit and the Zipit Updater to update Zipit to any future version. You can see if there is a new version of Zipit available on the Home tab.</p>
  </div>
</div>
      </div>
    </div>
<div class="dev_by" id="dev_by">Developed by: <a href="http://www.cloudsitesrock.com/" target="_blank">Jereme Hancock</a></div>
  </div>
</div>

<script src="js/tabs.js"></script>
</body>
</html>
