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

// hide all error
error_reporting(0);

// check url
$url = $_SERVER['REQUEST_URI'];

// load session
session_start();

// Get ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Include browser detection utility
include_once('./include/browser_detection.php');

// Check if user is using Opera Mini - Opera Mini should not access client interface
// Always check browser type, regardless of page or login status
$browser_check = checkBrowserAccess('client');
if ($browser_check['redirect']) {
  performRedirect($browser_check['target'], $browser_check['message']);
}

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
if (!isset($_SESSION['theme']) || $_SESSION['theme'] == "") {
  // Pastikan $theme memiliki nilai default jika tidak ada di include/theme.php
  $theme = isset($theme) ? $theme : 'light';
  $themecolor = isset($themecolor) ? $themecolor : '#008BC9';
} else {
  $theme = $_SESSION['theme'];
  $themecolor = $_SESSION['themecolor'];
}

// Debug info
file_put_contents('./theme_debug.log', "Theme: " . $theme . "\nThemeColor: " . $themecolor . "\n", FILE_APPEND);

// load config
include_once('./include/headhtml.php');
include('./include/config.php');
include('./include/readcfg.php');

// load routeros api
include_once('./lib/routeros_api.class.php');
include_once('./lib/formatbytesbites.php');

// Get other URL parameters
$router = isset($_GET['router']) ? $_GET['router'] : '';
$session = isset($_GET['session']) ? $_GET['session'] : '';
$theme = isset($_GET['theme']) ? $_GET['theme'] : '';
$c = isset($_GET['c']) ? $_GET['c'] : '';
$q = isset($_GET['q']) ? $_GET['q'] : '';

if ($id == "login" || substr($url, -1) == "p") {

  if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // Debug information
    $debug_info = "User Input: " . $user . "<br>";
    $debug_info .= "Expected User: " . $useradm . "<br>";
    $debug_info .= "Encrypted Password in Config: " . $passadm . "<br>";
    $debug_info .= "Decrypted Password: " . decrypt($passadm) . "<br>";

    // Write to debug file
    file_put_contents('./debug.log', $debug_info, FILE_APPEND);

    if ($user == $useradm && $pass == decrypt($passadm)) {
      $_SESSION["mikhmon"] = $user;
      $_SESSION["user_type"] = "client"; // Set user type as client
      echo "<script>window.location='./client.php?id=sessions'</script>";
    } else {
      $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Alert!<br>Invalid username or password.</div>';
      $error .= '<div style="font-size:12px;text-align:left;padding:5px;">' . $debug_info . '</div>';
    }
  }

  include_once('./include/login.php');
} elseif (!isset($_SESSION["mikhmon"])) {
  echo "<script>window.location='./client.php?id=login'</script>";
} elseif (substr($url, -1) == "/" || substr($url, -4) == ".php") {
  echo "<script>window.location='./client.php?id=sessions'</script>";
} elseif ($id == "sessions") {
  $_SESSION["connect"] = "";
  include_once('./include/client_menu.php');
  include_once('./settings/client_sessions.php');
} elseif ($id == "connect"  && !empty($session)) {
  ini_set("max_execution_time", 5);
  include_once('./include/client_menu.php');
  $API = new RouterosAPI();
  $API->debug = false;
  if ($API->connect($iphost, $userhost, decrypt($passwdhost))) {
    $_SESSION["connect"] = "<b class='text-green'>Connected</b>";
    echo "<script>window.location='./?session=" . $session . "'</script>";
  } else {
    $_SESSION["connect"] = "<b class='text-red'>Not Connected</b>";
    $nl = '\n';
    if ($currency == in_array($currency, $cekindo['indo'])) {
      echo "<script>alert('Mikhmon not connected!" . $nl . "Silakan periksa kembali IP, User, Password dan port API harus enable." . $nl . "Jika menggunakan koneksi VPN, pastikan VPN tersebut terkoneksi.')</script>";
    } else {
      echo "<script>alert('Mikhmon not connected!" . $nl . "Please check the IP, User, Password and port API must be enabled.')</script>";
    }
    if ($c == "settings") {
      echo "<script>window.location='./client.php?id=settings&session=" . $session . "'</script>";
    } else {
      echo "<script>window.location='./client.php?id=sessions'</script>";
    }
  }
} elseif ($id == "remove-session" && $session != "") {
  include_once('./include/client_menu.php');
  $fc = file("./include/config.php");
  $f = fopen("./include/config.php", "w");
  $q = "'";
  $rem = '$data[' . $q . $session . $q . ']';
  foreach ($fc as $line) {
    if (!strstr($line, $rem))
      fputs($f, $line);
  }
  fclose($f);
  echo "<script>window.location='./client.php?id=sessions'</script>";
} elseif ($id == "about") {
  // Redirect to sessions page instead of showing about page
  echo "<script>window.location='./client.php?id=sessions'</script>";
} elseif ($id == "logout") {
  include_once('./include/client_menu.php');
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
    window.location='./client.php?id=login';
  </script>";
} elseif (empty($id)) {
  echo "<script>window.location='./client.php?id=sessions'</script>";
}
?>
<?php if ($id == "login" || substr($url, -1) == "p"): ?>
  <script src="js/jquery.min.js"></script>
  <script src="js/login.js"></script>
<?php else: ?>
  <script src="js/mikhmon-ui.pink.min.js"></script>
  <script src="js/mikhmon.js?t=<?= str_replace(" ", "_", date("Y-m-d H:i:s")); ?>"></script>
<?php endif; ?>
<?php include('./include/info.php'); ?>
</body>

</html>