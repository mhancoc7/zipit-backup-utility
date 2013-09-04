<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// specify namespace
namespace OpenCloud;

echo "<link href='css/style.css' rel='stylesheet' type='text/css'><div class='logs'>";

// include password protection
require_once("zipit-login.php"); 

// require zipit configuration
require('zipit-config.php');

// define zipit log file
$zipitlog = "../../../logs/zipit.log";
$logsize = filesize($zipitlog);

// require Cloud Files API
require_once('./api/lib/php-opencloud.php');

echo "<center><em>";
echo "<br />";

// authenticate to Cloud Files
try {
// my credentials
   define('AUTHURL', 'https://identity.api.rackspacecloud.com/v2.0/');
   $mysecret = array('username' => $username,'apiKey' => $key);

// establish our credentials
   $connection = new Rackspace(AUTHURL, $mysecret);
// now, connect to the ObjectStore service
   $ostore = $connection->ObjectStore('cloudFiles', "$datacenter");
}
catch (HttpUnauthorizedError $e) {
   echo "Cloud Files API connection could not be established.<br/><br/>Be sure to check your API credentials on the Settings tab.";
   die();
}

// create container if it doesn't already exist
$cont = $ostore->Container();
$cont->Create(array('name'=>"zipit-backups-files-$url"));

$list = $cont->ObjectList();

while($o = $list->Next())
   echo $o->name ."<br/>";
   echo "</div>";

?>
