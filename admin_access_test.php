<?php
/*
 * Admin Access Test Script for WIFI-DESA
 * This script helps test if Opera browsers can access admin.php
 */

// Start session
session_start();

// Get user agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Check for Opera in various ways
$is_opera = false;
$is_opera_mini = false;

// Check for Opera Mini
if (strpos($user_agent, 'Opera Mini') !== false) {
    $is_opera_mini = true;
}

// Check for Opera in general
if (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
    $is_opera = true;
}

// Log the access attempt
$log_file = './admin_access_test.log';
$log_data = date('Y-m-d H:i:s') . " - User Agent: " . $user_agent .
    " - Is Opera: " . ($is_opera ? 'Yes' : 'No') .
    " - Is Opera Mini: " . ($is_opera_mini ? 'Yes' : 'No') . "\n";
file_put_contents($log_file, $log_data, FILE_APPEND);

// HTML output
?>
<!DOCTYPE html>
<html>

<head>
    <title>WIFI-DESA Admin Access Test</title>
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
        <h1>WIFI-DESA Admin Access Test</h1>

        <div class="info-box">
            <h2>Your Browser Information</h2>
            <p><strong>User Agent:</strong> <?php echo htmlspecialchars($user_agent); ?></p>
            <p><strong>Is Opera:</strong> <span class="result <?php echo $is_opera ? 'yes' : 'no'; ?>"><?php echo $is_opera ? 'Yes' : 'No'; ?></span></p>
            <p><strong>Is Opera Mini:</strong> <span class="result <?php echo $is_opera_mini ? 'yes' : 'no'; ?>"><?php echo $is_opera_mini ? 'Yes' : 'No'; ?></span></p>
            <p><strong>Should be able to access admin.php:</strong> <span class="result <?php echo $is_opera ? 'yes' : 'no'; ?>"><?php echo $is_opera ? 'Yes' : 'No'; ?></span></p>
            <p><strong>Should be redirected from client.php:</strong> <span class="result <?php echo $is_opera ? 'yes' : 'no'; ?>"><?php echo $is_opera ? 'Yes' : 'No'; ?></span></p>
        </div>

        <div class="info-box">
            <h2>Access Test</h2>
            <p>This page is accessible to all browsers. The real test is whether you can access admin.php and client.php.</p>
            <p>If you're using an Opera browser:</p>
            <ul>
                <li>You <strong>should</strong> be able to access admin.php without being redirected</li>
                <li>You <strong>should NOT</strong> be able to access client.php (you'll be redirected to admin.php)</li>
            </ul>
            <p>If you're using a non-Opera browser:</p>
            <ul>
                <li>You <strong>should NOT</strong> be able to access admin.php (you'll be redirected to client.php)</li>
                <li>You <strong>should</strong> be able to access client.php without being redirected</li>
            </ul>
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