<?php
// Start session
session_start();

// Include database connection
require_once $_SESSION['directory'] .'\Database\dbcon.php';

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Function to sanitize user inputs
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if form data has been sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $guard_name = sanitizeInput($_POST['guard_name']);
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $station_id = intval(sanitizeInput($_POST['station']));
    
    // Validate that inputs are not empty
    if (empty($guard_name) || empty($username) || empty($email) || empty($password) || empty($station_id)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
        exit();
    }

    // Start transaction
    try {
        $conn->begin_transaction();

        // Insert new guard into the guards table
        $stmt = $conn->prepare("INSERT INTO guards (guard_name, station_id) VALUES (?, ?)");
        $stmt->bind_param("si", $guard_name, $station_id);

        if (!$stmt->execute()) {
            throw new Exception("Error adding guard.");
        }

        // Get the last inserted guard ID
        $guardId = $stmt->insert_id;

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into the guard_accounts table
        $stmtAccount = $conn->prepare("INSERT INTO guard_accounts (username, email, password, guard_id) VALUES (?, ?, ?, ?)");
        $stmtAccount->bind_param("sssi", $username, $email, $hashedPassword, $guardId);

        if (!$stmtAccount->execute()) {
            throw new Exception("Error adding guard account.");
        }

        // Retrieve the station name for logging
        $stmtStation = $conn->prepare("SELECT station_name FROM stations WHERE station_id = ?");
        $stmtStation->bind_param("i", $station_id);
        $stmtStation->execute();
        $stmtStation->bind_result($station_name);
        $stmtStation->fetch();
        $stmtStation->close();

        if (!$station_name) {
            throw new Exception("Station not found.");
        }

        // Insert into activity log
        $logSql = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ? , ?)";
        $logStmt = $conn->prepare($logSql);

        $section = 'CO-ADMIN';
        $details = "Insert Co-admin Account\n\nID: $guardId\nStation: $station_name\nName: $guard_name\nUsername: $username";
        $category = 'INSERT';
        $adminId = $_SESSION['admin_id'];

        $logStmt->bind_param("sssi", $section, $details, $category, $adminId);
        if (!$logStmt->execute()) {
            throw new Exception("Error logging activity.");
        }

        // Commit transaction
        $conn->commit();

        // Close statements
        $stmt->close();
        $stmtAccount->close();
        $logStmt->close();

        // Success response
        echo json_encode(['success' => true, 'message' => 'Account created successfully.']);
    } catch (Exception $e) {
        // Rollback transaction if something failed
        $conn->rollback();

        // Close the database connection
        $conn->close();

        // Show error message
        echo json_encode(['success' => false, 'message' => 'Error: ' . addslashes($e->getMessage())]);
        exit();
    }
}
else{
    echo "<script>alert('Invalid request! Please try again.'); window.location.href='main_active.php';</script>";
    exit();
}
?>
