<?php
/*
 * Browser Detection Utility for WIFI-DESA
 * This file handles browser detection and access control
 * - Admin interface: Only accessible via Opera Mini
 * - Client interface: Accessible via all browsers except Opera Mini
 */

// Function to detect if the current browser is Opera Mini
function isOperaMini() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    return (strpos($user_agent, 'Opera Mini') !== false);
}

// Function to check if user should be redirected based on browser and requested page
function checkBrowserAccess($current_page) {
    $is_opera_mini = isOperaMini();
    
    // If on admin page but not using Opera Mini, redirect to client
    if ($current_page === 'admin' && !$is_opera_mini) {
        return [
            'redirect' => true,
            'target' => 'client.php',
            'message' => 'Admin interface is only accessible via Opera Mini browser.'
        ];
    }
    
    // If on client page but using Opera Mini, redirect to admin
    if ($current_page === 'client' && $is_opera_mini) {
        return [
            'redirect' => true,
            'target' => 'admin.php',
            'message' => 'Client interface is not accessible via Opera Mini browser.'
        ];
    }
    
    // No redirection needed
    return [
        'redirect' => false
    ];
}

// Function to perform the actual redirect with a message
function performRedirect($target, $message = null) {
    if ($message) {
        // Store message in session to display after redirect
        session_start();
        $_SESSION['browser_redirect_message'] = $message;
    }
    
    header("Location: $target");
    exit;
}

// Function to display browser redirect message if exists
function displayBrowserRedirectMessage() {
    if (isset($_SESSION['browser_redirect_message'])) {
        $message = $_SESSION['browser_redirect_message'];
        unset($_SESSION['browser_redirect_message']); // Clear the message
        
        return '<div style="width: 100%; padding:5px 0px 5px 0px; margin-bottom: 10px; border-radius:5px;" class="bg-warning text-center">
            <i class="fa fa-exclamation-triangle"></i> ' . $message . '
        </div>';
    }
    
    return '';
}
?>
