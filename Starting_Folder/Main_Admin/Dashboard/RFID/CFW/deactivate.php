<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileId = intval(sanitizeInput($_POST['cfw_id']));
    $type = sanitizeInput($_POST['type']); // "returned", "lost", or "no_rfid"
    $rfid = isset($_POST['rfid']) ? sanitizeInput($_POST['rfid']) : null;
    $adminId = $_SESSION['admin_id'];

    try {
        $conn->begin_transaction();

        // Fetch staff's details
        $query = $conn->prepare("SELECT first_name, last_name, cfw_rfid, status FROM cfw_profile WHERE cfw_id = ?");
        $query->bind_param("i", $profileId);
        $query->execute();
        $result = $query->get_result();
        $profile = $result->fetch_assoc();

        if (!$profile) {
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
            exit();
        }

        if ($profile['status'] === 'INACTIVE') {
            echo json_encode(['success' => false, 'message' => 'Profile is already inactive.']);
            exit();
        }

        $logDetails = "Deactivate Cash for Work Staff Profile\n\nId: $profileId\nName: {$profile['first_name']} {$profile['last_name']}\n";

        // Log based on type
        if ($type === 'returned') {
            $logDetails .= "Returned RFID: $rfid";
        } elseif ($type === 'lost') {
            $logDetails .= "Lost RFID: {$profile['cfw_rfid']}";
        } else {
            $logDetails .= "No RFID";
        }

        // Deactivate the staff and set RFID to NULL
        $updateQuery = $conn->prepare("UPDATE cfw_profile SET status = 'INACTIVE', cfw_rfid = NULL WHERE cfw_id = ?");
        $updateQuery->bind_param("i", $profileId);
        $updateQuery->execute();

        // Log the activity
        $logQuery = $conn->prepare("INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('RFID', ?, 'DEACTIVATE', ?)");
        $logQuery->bind_param("si", $logDetails, $adminId);
        $logQuery->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Profile deactivated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
