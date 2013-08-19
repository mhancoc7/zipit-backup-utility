<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################

error_reporting(E_ERROR | E_PARSE);

// require zipit configuration
require('zipit-config.php');

$LOGIN_INFORMATION = array(
  $zipituser => $password
);

define('USE_USERNAME', true);

define('LOGOUT_URL', 'index.php');

define('TIMEOUT_MINUTES', 0);

define('TIMEOUT_CHECK_ACTIVITY', true);

$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

function showLoginPasswordProtect($error_msg) {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/colorbox.css" />
		<script src="js/jquery.js"></script>
		<script src="js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".iframe").colorbox({iframe:true, width:"400px", height:"130px", closeButton:false, escKey:false, overlayClose:false, scrolling:false});
			});
		</script>
</head>
<body>
<div id="wrapper">
  <h1>Zipit Backup Utility</h1>
  <div id="tabContainer">
   
    <div id="tabscontent"><br/>
  <form method="post" style="text-align:center;">
    <font color="red"><?php echo $error_msg; ?></font><br />
<?php if (USE_USERNAME) echo 'Username: <input type="input" name="access_login" style="font-size:18px;" autofocus/>&nbsp;&nbsp;Password: &nbsp;'; ?><input type="password" name="access_password" style="font-size:18px;" />&nbsp;&nbsp;&nbsp;<button type="submit" class="css3button">Submit</button>
  </form><br />

</div>
</div>


</body>
</html>

<?php

  die();
}
}

if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) {
    showLoginPasswordProtect("Incorrect Login!<br>");
  }
  else {

    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
    
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);


  }

}

else {

  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }

  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;

      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");

  }
}

?>
