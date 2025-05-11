<?php
/*
 * Browser Test Script for WIFI-DESA
 * This script helps test the browser detection functionality
 */

// Start session
session_start();

// Include browser detection utility
include_once('./include/browser_detection.php');

// Get user agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$is_opera_mini = isOperaMini();

// Determine which interface should be accessed
$should_access = $is_opera_mini ? 'Admin Interface (admin.php)' : 'Client Interface (client.php)';

// Determine which interface will be blocked
$will_be_blocked = $is_opera_mini ? 'Client Interface (client.php)' : 'Admin Interface (admin.php)';

// HTML output
?>
<!DOCTYPE html>
<html>

<head>
    <title>WIFI-DESA Browser Test</title>
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

        .admin {
            background-color: #ffe0e0;
            padding: 10px;
            border-radius: 5px;
        }

        .client {
            background-color: #e0ffe0;
            padding: 10px;
            border-radius: 5px;
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
        <h1>WIFI-DESA Browser Test</h1>

        <div class="info-box">
            <h2>Your Browser Information</h2>
            <p><strong>User Agent:</strong> <?php echo htmlspecialchars($user_agent); ?></p>
            <p><strong>Detected as Opera Mini:</strong> <?php echo $is_opera_mini ? 'Yes' : 'No'; ?></p>
            <p><strong>Should Access:</strong> <span class="<?php echo $is_opera_mini ? 'admin' : 'client'; ?>"><?php echo $should_access; ?></span></p>
            <p><strong>Will Be Blocked From:</strong> <span class="<?php echo $is_opera_mini ? 'client' : 'admin'; ?>"><?php echo $will_be_blocked; ?></span></p>
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