<?php
function logSystemActivity($conn, $activity, $status = 'SUCCESS', $additional_info = '') {
    // Determine user type
    $user_type = 'UNKNOWN';
    $username = null;
    
    if (isset($_SESSION['admin_logged'])) {
        $user_type = 'MAIN_ADMIN';
        $username = $_SESSION['username'] ?? null;
    } elseif (isset($_SESSION['record_guard_logged'])) {
        $user_type = 'RECORD_POST_ADMIN';
        $username = $_SESSION['username'] ?? null;
    } elseif (isset($_SESSION['vehicle_guard_logged'])) {
        $user_type = 'VEHICLE_POST_ADMIN';
        $username = $_SESSION['username'] ?? null;
    }

    // Get IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // Get WiFi information
    $wifi_name = '';
    $wifi_mac = '';
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // For Windows
        exec('netsh wlan show interfaces', $output);
        foreach ($output as $line) {
            if (strpos($line, 'SSID') !== false && strpos($line, 'BSSID') === false) {
                $wifi_name = trim(substr($line, strpos($line, ':') + 1));
            }
            if (strpos($line, 'BSSID') !== false) {
                $wifi_mac = trim(substr($line, strpos($line, ':') + 1));
            }
        }
    } else {
        // For Linux/Unix
        exec('iwconfig 2>/dev/null', $output);
        foreach ($output as $line) {
            if (strpos($line, 'ESSID') !== false) {
                preg_match('/ESSID:"([^"]*)"/', $line, $matches);
                $wifi_name = $matches[1] ?? '';
            }
            if (strpos($line, 'Access Point') !== false) {
                preg_match('/Access Point: ([0-9A-Fa-f:]{17})/', $line, $matches);
                $wifi_mac = $matches[1] ?? '';
            }
        }
    }

    // Get current page URL
    $page_accessed = $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? 'Unknown Page';

    try {
        $stmt = $conn->prepare("INSERT INTO system_activity_log (user_type, ip_address, wifi_name, wifi_mac, activity, username, page_accessed, status, additional_info) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssssss", 
            $user_type,
            $ip_address,
            $wifi_name,
            $wifi_mac,
            $activity,
            $username,
            $page_accessed,
            $status,
            $additional_info
        );

        $stmt->execute();
        return true;
    } catch (Exception $e) {
        // Silently fail - we don't want logging errors to break the application
        return false;
    }
}

// Example usage:
/*
require_once 'dbcon.php';
require_once 'system_log_helper.php';

// Log a successful login
logSystemActivity($conn, 
    "User login", 
    "SUCCESS", 
    "Login from " . $_SERVER['HTTP_USER_AGENT']
);

// Log a failed login attempt
logSystemActivity($conn, 
    "Failed login attempt", 
    "FAILED", 
    "Invalid credentials for username: " . $attempted_username
);

// Log an unauthorized access attempt
logSystemActivity($conn, 
    "Unauthorized access attempt", 
    "UNAUTHORIZED", 
    "Attempted to access restricted page"
);
*/
?>

