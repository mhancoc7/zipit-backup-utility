<?php 
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// include password protection
    require("zipit-login.php"); 

$db_dir = "./dbs"; //change this if the script is in a different dir that the files you want 
$show = array( '.php'); //Type of files to show 

$select = "<center><form name = \"db_form\"><select name=\"db_select\" onchange = \"showBackupButton();update(this);\"><option value='Select Database'>Select Database</option>\n"; 

$dh = @opendir( $db_dir ); 
while( false !== ( $file = readdir( $dh ) ) ){ 
    $ext=substr($file,-4,4); 
        if(in_array( $ext, $show )){       
            $file = str_replace("-config.php", "", $file);   
            $select .= "<option value='$file'>$file</option>\n"; 
    } 
}   

$select .= "</select><a href='#' class='update_db_menu' id='update_db_menu' onclick='updateDbMenu();' style='margin-left:10px;position:relative;top:5px;' title='Refresh Database Menu'><img src='images/refresh.png'/></a></form></center><br/>"; 
closedir( $dh ); 

echo "$select"; 
?>
