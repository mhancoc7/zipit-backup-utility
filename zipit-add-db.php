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

// set Wordpress config path
$wordpress = $path.'/wp-config.php';

// set Joomla config path
$joomla = $path.'/configuration.php';

// set Drupal config path
$drupal = $path.'/sites/default/settings.php';

// get database to backup
if (isset( $_GET['db']) && !empty( $_GET['db'])) {
   $db = $_GET['db'];
   $db_file = $db."-config.php";
   require("./dbs/$db_file");
   $button_text = "Update";
   $alert_text = "Updated";
   $display_delete = "";
}

elseif (file_exists($wordpress)) {
   include($wordpress);
      if (file_exists("./dbs/".DB_NAME."-config.php")) {
         $wordpress_installed = "false";
         $button_text = "Add";
         $alert_text = "Added";
         $display_delete = "display:none";
      }
      else {
         $wordpress_installed = "true";
         $button_text = "Add";
         $alert_text = "Added";
         $display_delete = "display:none";
      }
}

elseif (file_exists($joomla) && strpos(file_get_contents($joomla), 'Joomla')) {
   include($joomla);
   $jconfig = new JConfig();
      if (file_exists("./dbs/".$jconfig->db."-config.php")) {
         $joomla_installed = "false";
         $button_text = "Add";
         $alert_text = "Added";
         $display_delete = "display:none";
      }
      else {
         $joomla_installed = "true";
         $button_text = "Add";
         $alert_text = "Added";
         $display_delete = "display:none";
         $db_name = $jconfig->db;
         $db_user = $jconfig->user;
         $db_pass = $jconfig->password;
         $db_host = $jconfig->host;
      }
}

elseif (file_exists($drupal)) {
   include($drupal);
      if (file_exists("./dbs/".$databases['default']['default']['database']."-config.php")) {
         $drupal_installed = "false";
         $button_text = "Add";
         $alert_text = "Added";
         $display_delete = "display:none";
      }
     else {
        $drupal_installed = "true";
        $button_text = "Add";
        $alert_text = "Added";
        $display_delete = "display:none";
        $db_name = $databases['default']['default']['database'];
        $db_user = $databases['default']['default']['username'];
        $db_pass = $databases['default']['default']['password'];
        $db_host = $databases['default']['default']['host'];
    }
}

else {
   $button_text = "Add";
   $alert_text = "Added";
   $display_delete = "display:none";
}

if (isset($_POST["Submit"])) {
// check database connection and database existence
   $link = mysqli_connect($_POST["db_host"],$_POST["db_user"],$_POST["db_pass"]);
   $db_selected = mysqli_select_db($link, $_POST["db_name"]);
   if (!$db_selected) {
      echo '<script>';
      echo 'alert("Database Connection Failed!\n\nCheck credentials and try again.")';
      echo '</script>';
   }
   else {
      $string = '<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

// Database connection string
$db_name = "'. $_POST["db_name"]. '";
$db_user = "'. $_POST["db_user"]. '";
$db_pass = "'. $_POST["db_pass"]. '";
$db_host = "'. $_POST["db_host"]. '";

?>';

   $fp = fopen("./dbs/".$_POST['db_name']."-config.php", "w");
   fwrite($fp, $string);
   fclose($fp);

   echo '<script>';
   echo 'alert("Database Connection Successfully '.$alert_text.'!\n\nYou may need to refresh the Database Menu.")';
   echo '</script>';

      if ($db_name != $_POST["db_name"]) {
         shell_exec("rm ./dbs/$db_file");
      }

   echo '<script>';
   echo 'window.location="zipit-add-db.php?db='.$_POST["db_name"].'"';
   echo '</script>';
   }

}

echo '<script>';
echo 'parent.updateDbMenu();';
echo '</script>';

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="js/jquery.js"></script>
<link rel="stylesheet" href="css/zipit/jquery-ui.css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

<script>
   function removeSpaces(string) {
      return string.split(' ').join('');
   }
</script>

</head>
<body>

<div class="wrapper">

<div style="text-align:center">
<form action="" method="post" name="install" id="install">
<?php if ($wordpress_installed == "true") {echo "<center><font color=red><br/>Wordpress Install Detected!</font></center>";} elseif ($joomla_installed == "true") {echo "<center><font color=red><br/>Joomla Install Detected!</font></center>";} elseif ($drupal_installed == "true") {echo "<center><font color=red><br/>Drupal Install Detected!</font></center>";}?><br/>
<em>Enter your database credentials</em>
<br/><br />
<p>
    Database Name:<br />
    <input name="db_name" type="text" id="db_name" value="<?php if ($wordpress_installed == 'true') {echo DB_NAME;} else {echo $db_name;}?>" onblur="this.value=removeSpaces(this.value);" required>
</p>
<br />
<p>
     Database User:<br />
    <input name="db_user" type="text" id="db_user" value="<?php if ($wordpress_installed == 'true') {echo DB_USER;} else {echo $db_user;}?>" onblur="this.value=removeSpaces(this.value);" required>
</p>
<br />

<p>
    Database Password:<br />
    <input name="db_pass" type="password" id="db_pass" value="<?php if ($wordpress_installed == 'true') {echo DB_PASSWORD;} else {echo $db_pass;}?>" onblur="this.value=removeSpaces(this.value);" required>
</p>
<br />
<p>
    Database Host:<br />
    <input name="db_host" type="text" id="db_host" value="<?php if ($wordpress_installed == 'true') {echo DB_HOST;} else {echo $db_host;}?>" onblur="this.value=removeSpaces(this.value);" required>
</p>

<br />
<p>

   <button type="submit" name="Submit" value="Update" class="css3button"><?php echo $button_text;?></button><button type="button" class="css3button" id="delete-db" class="delete-db" style="margin-left:15px;<?php echo $display_delete;?>" onclick="return confirmDbDelete();">Delete</button>
</p>

</form>
<script>
   function confirmDbDelete() {
      if (confirm('Are you sure you want to delete this database connection?\n\nThis can\'t be undone!')) {
         $.ajax({
            url: "zipit-delete-db.php?db=<?php echo $db; ?>",
            type: "POST",
            data: {id : 5},
            dataType: "html",
            success: function() {
               window.location="zipit-add-db.php";
               parent.updateDbMenu();
            }
         });
      }
   }
</script>
</div>

<script>
   $(function(){
      parent.$.colorbox.resize({
         innerWidth:$('body').width(),
         innerHeight:$('body').height()
      });
   });
</script>
</body>
</html>
