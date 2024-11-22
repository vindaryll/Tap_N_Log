<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Ensure RFID and client-side date are provided
if (!isset($_POST['rfid']) || !isset($_POST['client_date'])) {
    echo json_encode(['success' => false, 'message' => 'Required data not provided']);
    exit();
}

$rfid = $conn->real_escape_string($_POST['rfid']);
$date_today = $conn->real_escape_string($_POST['client_date']);

try {
    // Check for active cfw profile with RFID
    $stmt = $conn->prepare("SELECT * FROM cfw_profile WHERE cfw_rfid = ? AND status = 'ACTIVE'");
    $stmt->bind_param('s', $rfid);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();

    if (!$profile) {
        echo json_encode(['success' => false, 'message' => 'No profile is registered for this RFID number.']);
        exit();
    }

    // Check if attendance exists for today
    $stmt = $conn->prepare("SELECT * FROM cfw_attendance WHERE cfw_id = ? AND date_att = ? AND is_archived = FALSE");
    $stmt->bind_param('is', $profile['cfw_id'], $date_today);
    $stmt->execute();
    $attendance = $stmt->get_result()->fetch_assoc();
    $imageDirectory = '/tapnlog/Image/CFW/';
    

    if ($attendance) {
        if (is_null($attendance['time_out'])) {
            echo json_encode(['success' => false, 'message' => 'This profile has already recorded a time-in for today.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'This profile has already completed attendance for today.']);
        }
    } else {
        echo json_encode([
            'success' => true,
            'profile' => [
                'id' => $profile['cfw_id'],
                'image' => $imageDirectory . $profile['cfw_img'],
                'rfid' => $profile['cfw_rfid'],
                'name' => $profile['first_name'] . ' ' . $profile['last_name'],
            ],
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
