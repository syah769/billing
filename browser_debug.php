<?php
/*
 * Browser Debug Script for WIFI-DESA
 * This script helps debug the browser detection functionality
 */

// Start session
session_start();

// Include browser detection utility
include_once('./include/browser_detection.php');

// Get user agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$is_opera_mini = isOperaMini();

// Log browser information
$log_file = './browser_debug.log';
$log_data = date('Y-m-d H:i:s') . " - User Agent: " . $user_agent . " - Is Opera Mini: " . ($is_opera_mini ? 'Yes' : 'No') . "\n";
file_put_contents($log_file, $log_data, FILE_APPEND);

// HTML output
?>
<!DOCTYPE html>
<html>
<head>
    <title>WIFI-DESA Browser Debug</title>
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
        pre {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
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
        <h1>WIFI-DESA Browser Debug</h1>
        
        <div class="info-box">
            <h2>Your Browser Information</h2>
            <p><strong>User Agent:</strong> <?php echo htmlspecialchars($user_agent); ?></p>
            <p><strong>Detected as Opera Mini:</strong> <?php echo $is_opera_mini ? 'Yes' : 'No'; ?></p>
            <p><strong>Detection Function:</strong> <code>strpos($user_agent, 'Opera Mini') !== false</code></p>
        </div>
        
        <div class="info-box">
            <h2>Browser Detection Logic</h2>
            <pre>
function isOperaMini() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    return (strpos($user_agent, 'Opera Mini') !== false);
}

function checkBrowserAccess($current_page) {
    $is_opera_mini = isOperaMini();

    // For admin pages: only Opera Mini can access
    if ($current_page === 'admin') {
        // If NOT Opera Mini, redirect to client
        if (!$is_opera_mini) {
            return [
                'redirect' => true,
                'target' => 'client.php',
                'message' => '' // No message for admin redirect
            ];
        }
    }
    
    // For client pages: Opera Mini cannot access
    if ($current_page === 'client') {
        // If IS Opera Mini, redirect to admin
        if ($is_opera_mini) {
            return [
                'redirect' => true,
                'target' => 'admin.php',
                'message' => '' // No message for client redirect
            ];
        }
    }

    // No redirection needed
    return [
        'redirect' => false
    ];
}
            </pre>
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
