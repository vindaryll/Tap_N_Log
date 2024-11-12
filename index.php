<?php

session_start();

// Function to get the local machine's IP address (for the server running this code)
function getLocalIpAddress() {

    // Check if the OS is Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $output = shell_exec('ipconfig');
        preg_match('/IPv4 Address[^:]*:\s*([^\s]+)/', $output, $matches);
        return $matches[1] ?? 'IPv4 address not found';
    } 
    // For Linux or Mac, use ifconfig
    else {
        $output = shell_exec('ifconfig');
        preg_match('/inet\s(\d+\.\d+\.\d+\.\d+)/', $output, $matches);
        return $matches[1] ?? 'IPv4 address not found';
    }
}

// Function to get the client's IP address (if accessed via a server)
function getUserIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Return only IPv4 if available
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return $ip;
    } else {
        return 'No IPv4 address found';
    }
}


// Necessary global variables needed

$_SESSION['directory'] = __DIR__;
$_SESSION['ip_address'] = getLocalIpAddress();

// echo $_SESSION['directory']; = \
// echo $_SERVER['DOCUMENT_ROOT']; = /
// exit();


// For example
// echo 'Access our website using this link: http://' . $_SESSION['ip_address'] . '/TAPNLOG';




header("Location: /tapnlog/starting_folder/landing_page/index.php");
exit();

?>


