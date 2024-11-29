<?php
session_start();

// Include system log helper
require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

// Function to get the local machine's IP address
function getLocalIpAddress() {
    $localIp = 'IPv4 address not found'; // Default message

    // Check if the OS is Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $output = shell_exec('ipconfig');
        if ($output) {
            // Match IPv4 Address for Windows
            preg_match('/IPv4 Address[^:]*:\s*([\d\.]+)/', $output, $matches);
            $localIp = $matches[1] ?? $localIp;
        }
    } else {
        // For Linux/Mac, use ifconfig
        $output = shell_exec('ifconfig');
        if ($output) {
            // Match IPv4 Address, excluding loopback (127.x.x.x)
            preg_match('/inet\s([\d\.]+)\s.*?(?=.*broadcast)/', $output, $matches);
            $localIp = $matches[1] ?? $localIp;
        }
    }

    return $localIp;
}

// Get local IP address
$localIp = getLocalIpAddress();

// Validate the IP address
if (filter_var($localIp, FILTER_VALIDATE_IP)) {
    $_SESSION['ip_address'] = $localIp;
    $_SESSION['website_link'] = 'http://' . $localIp . '/TAPNLOG';
    
    // Log successful website link generation
    logSystemActivity(
        $conn,
        "Website link generation",
        "SUCCESS",
        "Generated website link: " . $_SESSION['website_link']
    );
    
    // Return the website link as a JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'website_link' => $_SESSION['website_link']
    ]);
} else {
    // Log failed website link generation
    logSystemActivity(
        $conn,
        "Website link generation",
        "FAILED",
        "Unable to determine local IP address"
    );
    
    // Handle case when IP address is not found or invalid
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Unable to determine the local IP address.'
    ]);
}
