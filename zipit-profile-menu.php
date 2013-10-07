<?php 
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// include password protection
require_once("zipit-login.php");  

$db_dir = "./excludes"; 
$show = array('.php'); 

$select = "<center><form name = \"profile_form\"><select name=\"profile_select\" onchange = \"showFilesBackupButton();updateProfile(this);\"><option value='Select Profile for Backup'>Select Profile for Backup</option>\n"; 

$dh = @opendir($db_dir); 
   while(false !== ($file = readdir($dh))) { 
      $ext=substr($file,-4,4); 
      if (in_array($ext, $show)) {       
         $file = str_replace("-profile.php", "", $file);   
         if ($file != "index.php") {
            $select .= "<option value='$file'>$file</option>\n"; 
         }
      } 
   }   

$select .= "</select><a href='#' class='update_profile_menu' id='update_profile_menu' onclick='updateProfileMenu();' style='margin-left:10px;position:relative;top:5px;' title='Refresh Profile Menu'><img src='images/refresh.png'/></a></form></center><br/>"; 

closedir($dh); 

if ($dh = opendir($db_dir)) {           
   while(($file = readdir($dh)) !== false) {
      if($file != "." && $file != ".." && $file != "index.php") {
         $file_list[] = $file;  
      }
   }
closedir($dh);      
}


$file_count = count($file_list);

if ($file_count > 1) {
echo "$select"; 
}
else {
   echo "<center><font color='red'>Use the \"Add Profile\" button below to add more backup profiles.</font></center><br/>$select";
}

?>
