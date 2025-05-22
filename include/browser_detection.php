<?php
/*
 * Browser Detection Utility for WIFI-DESA
 * This file handles browser detection and access control
 * - Admin interface: Only accessible via Opera Mini
 * - Client interface: Accessible via all browsers except Opera Mini
 */

// Function to detect if the current browser is Opera Mini
function isOperaMini()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Log the user agent for debugging
    $log_file = './opera_detection.log';
    $log_data = date('Y-m-d H:i:s') . " - User Agent: " . $user_agent . "\n";
    file_put_contents($log_file, $log_data, FILE_APPEND);

    // Check for Opera Mini in various ways
    if (strpos($user_agent, 'Opera Mini') !== false) {
        return true;
    }

    if (strpos($user_agent, 'OPR') !== false && strpos($user_agent, 'Mobile') !== false) {
        return true;
    }

    if (strpos($user_agent, 'Opera') !== false && strpos($user_agent, 'Mobile') !== false) {
        return true;
    }

    // Check for Opera headers
    if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE'])) {
        return true;
    }

    if (isset($_SERVER['HTTP_X_OPERAMINI_FEATURES'])) {
        return true;
    }

    return false;
}

// Function to check if user should be redirected based on browser and requested page
function checkBrowserAccess($current_page)
{
    $is_opera_mini = isOperaMini();

    // Skip browser detection for connect operations (critical fix)
    if (isset($_GET['id']) && $_GET['id'] == 'connect') {
        return [
            'redirect' => false
        ];
    }

    // Log the decision for debugging
    $log_file = './access_decisions.log';
    $log_data = date('Y-m-d H:i:s') . " - Page: " . $current_page . " - Is Opera Mini: " . ($is_opera_mini ? 'Yes' : 'No') . "\n";
    file_put_contents($log_file, $log_data, FILE_APPEND);

    // Special case: If it's any Opera browser (not just Opera Mini), allow access to admin.php
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ($current_page === 'admin') {
        if (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
            // Log this special case
            $special_log = './special_case.log';
            $special_data = date('Y-m-d H:i:s') . " - ALLOWING Opera browser to access admin: " . $user_agent . "\n";
            file_put_contents($special_log, $special_data, FILE_APPEND);

            // Allow Opera browsers to access admin.php
            return [
                'redirect' => false
            ];
        }
    }

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

    // For client pages: No Opera browsers can access (including Opera Mini and regular Opera)
    if ($current_page === 'client') {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        // If it's any Opera browser, redirect to admin
        if (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
            // Log this redirection
            $special_log = './client_redirect.log';
            $special_data = date('Y-m-d H:i:s') . " - REDIRECTING Opera browser from client to admin: " . $user_agent . "\n";
            file_put_contents($special_log, $special_data, FILE_APPEND);

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

// Function to perform the actual redirect with a message
function performRedirect($target, $message = null)
{
    if ($message && !empty($message)) {
        // Store message in session to display after redirect
        session_start();
        $_SESSION['browser_redirect_message'] = $message;
    }

    header("Location: $target");
    exit;
}

// Function to display browser redirect message if exists
function displayBrowserRedirectMessage()
{
    if (isset($_SESSION['browser_redirect_message'])) {
        $message = $_SESSION['browser_redirect_message'];
        unset($_SESSION['browser_redirect_message']); // Clear the message

        return '<div style="width: 100%; padding:5px 0px 5px 0px; margin-bottom: 10px; border-radius:5px;" class="bg-warning text-center">
            <i class="fa fa-exclamation-triangle"></i> ' . $message . '
        </div>';
    }

    return '';
}
