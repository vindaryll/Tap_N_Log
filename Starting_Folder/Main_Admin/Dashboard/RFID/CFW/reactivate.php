<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileId = intval(sanitizeInput($_POST['cfw_id']));
    $rfid = (isset($_POST['rfid']) && !empty($_POST['rfid']) && $_POST['rfid'] !== '') ? sanitizeInput($_POST['rfid']) : null;
    $adminId = $_SESSION['admin_id']; // Assuming this is set for the logged-in admin

    if (empty($profileId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid staff ID.']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Fetch current staff details
        $query = $conn->prepare("SELECT first_name, last_name, status FROM cfw_profile WHERE cfw_id = ?");
        $query->bind_param("i", $profileId);
        $query->execute();
        $result = $query->get_result();
        $profile = $result->fetch_assoc();

        if (!$profile) {
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
            exit();
        }

        if ($profile['status'] === 'ACTIVE') {
            echo json_encode(['success' => false, 'message' => 'Profile is already active.']);
            exit();
        }

        // Update the status to ACTIVE and assign RFID if provided
        $updateQuery = $conn->prepare("UPDATE cfw_profile SET status = 'ACTIVE', cfw_rfid = ? WHERE cfw_id = ?");
        $updateQuery->bind_param("si", $rfid, $profileId);
        $updateQuery->execute();

        // Log the reactivation
        $fullName = $profile['first_name'] . ' ' . $profile['last_name'];
        $rfidText = $rfid ? $rfid : 'None';
        $logDetails = "Reactivate Cash for Work Staff Profile\n\nId: $profileId\nName: $fullName\nRFID: $rfidText";

        $logQuery = $conn->prepare("INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('RFID', ?, 'REACTIVATE', ?)");
        $logQuery->bind_param("si", $logDetails, $adminId);
        $logQuery->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Profile reactivated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
