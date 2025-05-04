<?php
// Include the encryption/decryption functions
include_once('./lib/routeros_api.class.php');

// Get the encrypted password from config.php
include('./include/config.php');
include('./include/readcfg.php');

echo "Admin User: " . $admin_user . "\n";
echo "Admin Pass (encrypted): " . $admin_pass . "\n";
echo "Admin Pass (decrypted): " . decrypt($admin_pass) . "\n\n";

echo "Client User: " . $client_user . "\n";
echo "Client Pass (encrypted): " . $client_pass . "\n";
echo "Client Pass (decrypted): " . decrypt($client_pass) . "\n\n";

// Test encryption/decryption with a known value
$test_password = "wifidesa";
$encrypted = encrypt($test_password);
$decrypted = decrypt($encrypted);

echo "Test Password: " . $test_password . "\n";
echo "Test Encrypted: " . $encrypted . "\n";
echo "Test Decrypted: " . $decrypted . "\n\n";

// Test with the actual encrypted password from config
$original_encrypted_admin = "aGJoa2pocGJmbg==";
$original_decrypted_admin = decrypt($original_encrypted_admin);
echo "Original Admin Encrypted: " . $original_encrypted_admin . "\n";
echo "Original Admin Decrypted: " . $original_decrypted_admin . "\n\n";

$original_encrypted_client = "r5qYoZWXq5I=";
$original_decrypted_client = decrypt($original_encrypted_client);
echo "Original Client Encrypted: " . $original_encrypted_client . "\n";
echo "Original Client Decrypted: " . $original_decrypted_client . "\n";
?>
