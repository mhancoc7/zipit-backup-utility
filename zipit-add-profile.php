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

if (isset($_POST["Submit"])) {
   
if (file_exists("./excludes/".$_POST["profile-name"]."-profile.php")) {
   echo "<script>alert('A profile with that name already exists. Please choose another name.');parent.$.fn.colorbox.close();</script>";
   die;
}

if (isset($_POST['folder']) && !empty($_POST['folder'])) {
$folder_ex = implode("\*", $_POST["folder"]); 
$folder_ex = $folder_ex."\*";
$folder_ex = str_replace("../../../../", "./",$folder_ex);
$folder_ex = str_replace(" ", "\ ",$folder_ex);
$folder_ex = str_replace("\*", "\* ",$folder_ex);
}

if (isset($_POST['file']) && !empty($_POST['file'])) {
$file_ex = implode(",", $_POST["file"]); 
$file_ex = str_replace("../../../../", "./",$file_ex);
$file_ex = str_replace(" ", "\ ",$file_ex);
$file_ex = str_replace(",", " ",$file_ex);
}

if (isset($_POST['exclude-zip']) && !empty($_POST['exclude-zip'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-zip"]. "'";
}

if (isset($_POST['exclude-gz']) && !empty($_POST['exclude-gz'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-gz"]. "'";
}

if (isset($_POST['exclude-tar']) && !empty($_POST['exclude-tar'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-tar"]. "'";
}

if (isset($_POST['exclude-targz']) && !empty($_POST['exclude-targz'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-targz"]. "'";
}

if (isset($_POST['exclude-rar']) && !empty($_POST['exclude-rar'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-rar"]. "'";
}

if (isset($_POST['exclude-7z']) && !empty($_POST['exclude-7z'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-7z"]. "'";
}

if (isset($_POST['exclude-iso']) && !empty($_POST['exclude-iso'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-iso"]. "'";
}

if (isset($_POST['exclude-log']) && !empty($_POST['exclude-log'])) {
   $file_ex = $file_ex. " '" . $_POST["exclude-log"]. "'";
}

$string = '<?php
$file_excludes = "'.$file_ex.'";
$folder_excludes = "'.$folder_ex.'";
?>';

$fp = fopen("./excludes/".$_POST["profile-name"]."-profile.php", "w");
fwrite($fp, $string);
fclose($fp);

echo "<script>parent.updateProfileMenu();alert('Backup Profile Successfully Added!\\n\\nYou may need to refresh the Profile Menu.');parent.$.fn.colorbox.close();</script>";

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
   <script src="./js/jquery.js" type="text/javascript"></script>
   <script src="js/jquery-ui.js"></script>
   <script src="./js/jquery-easing.js" type="text/javascript"></script>
   <script src="./js/jqueryFileTree.js" type="text/javascript"></script>
   <link href="./css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
   <link rel="stylesheet" href="css/zipit/jquery-ui.css" />
   <link href="css/style.css" rel="stylesheet" type="text/css" />
		
<script type="text/javascript">
   $(document).ready( function() {
      $('#fileTree').fileTree({ root: '../../../../', script: 'lib/jqueryFileTree.php'  }, function(file) {
        openFile(file);
      });
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
      
<script>
   function removeSpaces(string) {
      return string.split(' ').join('');
   }
</script>

</head>
	
<body>
   
   <div class="wrap">
      <form action='' method='post' name='excludes' id='excludes'>
           Profile Name: <input name="profile-name" type="text" id="profile-name" onblur="this.value=removeSpaces(this.value);" required> <img src="images/hint.png" title="Enter a simple but descriptive name for this profile. Spaces and special characters are not allowed." /> <br/> <br/>
			<b>Select the folders/files to exclude:</b>
         <div id="fileTree" class="box"></div><br/>
         <b>Exclude all by file extension:</b><br/><br/>

               <input type='checkbox' name='exclude-zip' id='exclude-zip' value='*.zip' />.zip &nbsp;&nbsp;
               <input type='checkbox' name='exclude-gz' id='exclude-gz' value='*.gz' />.gz &nbsp;&nbsp;
               <input type='checkbox' name='exclude-tar' id='exclude-tar' value='*.tar' />.tar &nbsp;&nbsp;
               <input type='checkbox' name='exclude-targz' id='exclude-targz' value='*.tar.gz' />.tar.gz<br/><br/>
               <input type='checkbox' name='exclude-rar' id='exclude-rar' value='*.rar' />.rar &nbsp;&nbsp;
               <input type='checkbox' name='exclude-7z' id='exclude-7z' value='*.7z' />.7z &nbsp;&nbsp;
               <input type='checkbox' name='exclude-iso' id='exclude-iso' value='*.iso' />.iso &nbsp;&nbsp;
               <input type='checkbox' name='exclude-log' id='exclude-log' value='*.log' />.log<br/><br/><br/>

                  <center><button type='submit' name='Submit' value='Update Excludes' class='css3button'>Add Profile</button></center>
         </form>
		</div>
      
<script>
   $('input').bind('keypress', function (event) {
      var regex = new RegExp("^[a-zA-Z0-9-]+$");
      var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
         if (!regex.test(key) && key.charCodeAt(0) > 32) {
            event.preventDefault();
            return false;
         }
  });
</script>

</body>
	
</html>
