<?php
/*
 * Opera Test Script for WIFI-DESA
 * This script helps test Opera browser detection
 */

// Start session
session_start();

// Get user agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Check for Opera in various ways
$is_opera = false;
$is_opera_mini = false;
$is_opera_mobile = false;

// Check for Opera Mini
if (strpos($user_agent, 'Opera Mini') !== false) {
    $is_opera_mini = true;
}

// Check for Opera Mobile
if (strpos($user_agent, 'Opera Mobi') !== false) {
    $is_opera_mobile = true;
}

// Check for Opera in general
if (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
    $is_opera = true;
}

// Check for Opera headers
$has_opera_headers = false;
if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE']) || isset($_SERVER['HTTP_X_OPERAMINI_FEATURES'])) {
    $has_opera_headers = true;
}

// Log the results
$log_file = './opera_test.log';
$log_data = date('Y-m-d H:i:s') . " - User Agent: " . $user_agent . 
    " - Is Opera: " . ($is_opera ? 'Yes' : 'No') . 
    " - Is Opera Mini: " . ($is_opera_mini ? 'Yes' : 'No') . 
    " - Is Opera Mobile: " . ($is_opera_mobile ? 'Yes' : 'No') . 
    " - Has Opera Headers: " . ($has_opera_headers ? 'Yes' : 'No') . "\n";
file_put_contents($log_file, $log_data, FILE_APPEND);

// HTML output
?>
<!DOCTYPE html>
<html>
<head>
    <title>WIFI-DESA Opera Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .info-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .result {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
        }
        .yes {
            background-color: #d4edda;
            color: #155724;
        }
        .no {
            background-color: #f8d7da;
            color: #721c24;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WIFI-DESA Opera Test</h1>
        
        <div class="info-box">
            <h2>Your Browser Information</h2>
            <p><strong>User Agent:</strong> <?php echo htmlspecialchars($user_agent); ?></p>
            <p><strong>Is Opera:</strong> <span class="result <?php echo $is_opera ? 'yes' : 'no'; ?>"><?php echo $is_opera ? 'Yes' : 'No'; ?></span></p>
            <p><strong>Is Opera Mini:</strong> <span class="result <?php echo $is_opera_mini ? 'yes' : 'no'; ?>"><?php echo $is_opera_mini ? 'Yes' : 'No'; ?></span></p>
            <p><strong>Is Opera Mobile:</strong> <span class="result <?php echo $is_opera_mobile ? 'yes' : 'no'; ?>"><?php echo $is_opera_mobile ? 'Yes' : 'No'; ?></span></p>
            <p><strong>Has Opera Headers:</strong> <span class="result <?php echo $has_opera_headers ? 'yes' : 'no'; ?>"><?php echo $has_opera_headers ? 'Yes' : 'No'; ?></span></p>
        </div>
        
        <div class="info-box">
            <h2>HTTP Headers</h2>
            <p>These are all the HTTP headers sent by your browser:</p>
            <pre><?php 
                foreach ($_SERVER as $key => $value) {
                    if (substr($key, 0, 5) === 'HTTP_') {
                        echo htmlspecialchars($key . ': ' . $value) . "\n";
                    }
                }
            ?></pre>
        </div>
        
        <div>
            <h2>Access Links</h2>
            <p>Click the links below to test the redirection:</p>
            <a href="admin.php" class="button">Go to Admin Interface</a>
            <a href="client.php" class="button">Go to Client Interface</a>
        </div>
    </div>
</body>
</html>
