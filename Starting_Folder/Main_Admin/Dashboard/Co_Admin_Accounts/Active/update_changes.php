<?php

session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guard_id = intval(sanitizeInput($_POST['guard_id']));
    $guard_name = sanitizeInput($_POST['guard_name']);
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $station_id = intval(sanitizeInput($_POST['station']));
    $admin_id = $_SESSION['admin_id']; // Assume this is set when admin is logged in

    if (empty($guard_id) || empty($guard_name) || empty($username) || empty($email) || empty($station_id)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Fetch current data for guards table
        $query = $conn->prepare("SELECT guard_name, station_id FROM guards WHERE guard_id = ?");
        $query->bind_param("i", $guard_id);
        $query->execute();
        $result = $query->get_result();
        $currentGuardData = $result->fetch_assoc();

        // Fetch current data for guard_accounts table
        $query = $conn->prepare("SELECT username, email FROM guard_accounts WHERE guard_id = ?");
        $query->bind_param("i", $guard_id);
        $query->execute();
        $result = $query->get_result();
        $currentAccountData = $result->fetch_assoc();

        // Fetch the current station name using station_id
        $stationQuery = $conn->prepare("SELECT station_name FROM stations WHERE station_id = ?");
        $stationQuery->bind_param("i", $currentGuardData['station_id']);
        $stationQuery->execute();
        $stationResult = $stationQuery->get_result();
        $currentStationData = $stationResult->fetch_assoc();
        $currentStationName = $currentStationData['station_name'] ?? 'Unknown';

        $updatesGuards = [];
        $updatesAccounts = [];
        $logDetails = [];

        // Build log header
        $logHeader = "Update Co-admin Details\n\nID: $guard_id\nName: $guard_name\n\n";

        // Check and prepare updates for guards table
        if ($currentGuardData['guard_name'] !== $guard_name) {
            $updatesGuards[] = "guard_name = '$guard_name'";
            $logDetails[] = "Set Name\nFrom: {$currentGuardData['guard_name']}\nTo: $guard_name";
        }
        if ($currentGuardData['station_id'] != $station_id) {
            // Fetch new station name
            $newStationQuery = $conn->prepare("SELECT station_name FROM stations WHERE station_id = ?");
            $newStationQuery->bind_param("i", $station_id);
            $newStationQuery->execute();
            $newStationResult = $newStationQuery->get_result();
            $newStationData = $newStationResult->fetch_assoc();
            $newStationName = $newStationData['station_name'] ?? 'Unknown';

            $updatesGuards[] = "station_id = $station_id";
            $logDetails[] = "Set Station\nFrom: $currentStationName\nTo: $newStationName";
        }

        // Check and prepare updates for guard_accounts table
        if ($currentAccountData['username'] !== $username) {
            $updatesAccounts[] = "username = '$username'";
            $logDetails[] = "Set Username\nFrom: {$currentAccountData['username']}\nTo: $username";
        }
        if ($currentAccountData['email'] !== $email) {
            $updatesAccounts[] = "email = '$email'";
            $logDetails[] = "Set Email\nFrom: {$currentAccountData['email']}\nTo: $email";
        }

        // Check if there are any updates to be made
        if (empty($updatesGuards) && empty($updatesAccounts)) {
            echo json_encode(['success' => false, 'message' => "There's no update change."]);
            exit();
        }

        // Update guards table if there are changes
        if (!empty($updatesGuards)) {
            $updateGuardsSQL = "UPDATE guards SET " . implode(", ", $updatesGuards) . " WHERE guard_id = $guard_id";
            $conn->query($updateGuardsSQL);
        }

        // Update guard_accounts table if there are changes
        if (!empty($updatesAccounts)) {
            $updateAccountsSQL = "UPDATE guard_accounts SET " . implode(", ", $updatesAccounts) . " WHERE guard_id = $guard_id";
            $conn->query($updateAccountsSQL);
        }

        // Insert a single log entry into admin_activity_log
        if (!empty($logDetails)) {
            $logDetailsText = $logHeader . implode("\n\n", $logDetails);
            $logSQL = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ?, ?)";
            $logStmt = $conn->prepare($logSQL);
            $section = 'CO-ADMIN';
            $category = 'UPDATE';
            $logStmt->bind_param("sssi", $section, $logDetailsText, $category, $admin_id);
            $logStmt->execute();
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Details updated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request!']);
}
