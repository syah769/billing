<?php
// Function to decrypt
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

// Test with the encrypted password from config.php
$encrypted_password = "MTIzNA==";
$decrypted_password = decrypt($encrypted_password);
echo "Encrypted: " . $encrypted_password . "\n";
echo "Decrypted: " . $decrypted_password . "\n";

// Test with the original encrypted password
$original_encrypted = "r5qYoZWXq5I=";
$original_decrypted = decrypt($original_encrypted);
echo "Original Encrypted: " . $original_encrypted . "\n";
echo "Original Decrypted: " . $original_decrypted . "\n";
?>
