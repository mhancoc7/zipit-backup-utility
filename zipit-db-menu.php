<?php 
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// include password protection
    require("zipit-login.php"); 

$db_dir = "./dbs"; 
$show = array( '.php'); 

$select = "<center><form name = \"db_form\"><select name=\"db_select\" onchange = \"showBackupButton();update(this);\"><option value='Select Database to Backup'>Select Database to Backup</option>\n"; 

$dh = @opendir( $db_dir ); 
while( false !== ( $file = readdir( $dh ) ) ){ 
    $ext=substr($file,-4,4); 
        if(in_array( $ext, $show )){       
            $file = str_replace("-config.php", "", $file);   
               if ($file != "index.php") {
                  $select .= "<option value='$file'>$file</option>\n"; 
               }
         } 
}   

$select .= "</select><a href='#' class='update_db_menu' id='update_db_menu' onclick='updateDbMenu();' style='margin-left:10px;position:relative;top:5px;' title='Refresh Database Menu'><img src='images/refresh.png'/></a></form></center><br/>"; 
closedir( $dh ); 


   

if($dh = opendir($db_dir)){           

	while(($file = readdir($dh)) !== false){

		if($file != "." && $file != ".." && $file != "index.php"){
			$file_list[] = $file;  
		}


	}

	closedir($dh);      
}

if(isset($file_list)){
echo "$select"; 
} else {
    echo "<center>Use the \"Add Credentials\" button below to add your database connection information.</center><br/>";

}

?>
