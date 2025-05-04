<?php
// Original encryption/decryption functions
function encrypt($string, $key=128) {
    $result = '';
    for($i=0, $k= strlen($string); $i<$k; $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)+ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
}

function decrypt($string, $key=128) {
    $result = '';
    $string = base64_decode($string);
    for($i=0, $k=strlen($string); $i< $k ; $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)-ord($keychar));
        $result .= $char;
    }
    return $result;
}

// Create a modified version of admin.php login check
function checkAdminLogin($user, $pass) {
    // Hard-coded credentials for admin
    $admin_user = "amnasiac";
    $admin_pass = "0163968146";
    
    // Check if credentials match
    if ($user === $admin_user && $pass === $admin_pass) {
        return true;
    }
    
    return false;
}

// Create a modified version of client.php login check
function checkClientLogin($user, $pass) {
    // Hard-coded credentials for client
    $client_user = "wifidesa";
    $client_pass = "wifidesa";
    
    // Check if credentials match
    if ($user === $client_user && $pass === $client_pass) {
        return true;
    }
    
    return false;
}

// Test the functions
echo "Testing admin login with correct credentials: ";
echo checkAdminLogin("amnasiac", "0163968146") ? "Success" : "Failed";
echo "\n";

echo "Testing admin login with incorrect credentials: ";
echo checkAdminLogin("amnasiac", "wrongpass") ? "Success" : "Failed";
echo "\n";

echo "Testing client login with correct credentials: ";
echo checkClientLogin("wifidesa", "wifidesa") ? "Success" : "Failed";
echo "\n";

echo "Testing client login with incorrect credentials: ";
echo checkClientLogin("wifidesa", "wrongpass") ? "Success" : "Failed";
echo "\n";
?>
