<?php
session_start();

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
    
    // Return the website link as a JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'website_link' => $_SESSION['website_link']
    ]);
} else {
    // Handle case when IP address is not found or invalid
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Unable to determine the local IP address.'
    ]);
}
