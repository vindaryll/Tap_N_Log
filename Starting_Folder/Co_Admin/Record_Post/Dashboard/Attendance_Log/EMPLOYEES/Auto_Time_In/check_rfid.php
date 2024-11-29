<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['record_guard_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Ensure RFID and client-side date are provided
if (!isset($_POST['rfid']) || !isset($_POST['client_date'])) {
    echo json_encode(['success' => false, 'message' => 'Required data not provided']);
    exit();
}

$rfid = $conn->real_escape_string($_POST['rfid']);
$date_today = $conn->real_escape_string($_POST['client_date']);

try {
    // Check for active employee profile with RFID
    $stmt = $conn->prepare("SELECT * FROM employees_profile WHERE employee_rfid = ? AND status = 'ACTIVE'");
    $stmt->bind_param('s', $rfid);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();

    if (!$profile) {
        echo json_encode(['success' => false, 'message' => 'No profile is registered for this RFID number.']);
        exit();
    }

    // Check if attendance exists for today
    $stmt = $conn->prepare("SELECT * FROM employees_attendance WHERE employee_id = ? AND date_att = ? AND is_archived = FALSE");
    $stmt->bind_param('is', $profile['employee_id'], $date_today);
    $stmt->execute();
    $attendance = $stmt->get_result()->fetch_assoc();
    $imageDirectory = '/tapnlog/Image/EMPLOYEES/';
    

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
                'id' => $profile['employee_id'],
                'image' => $imageDirectory . $profile['employee_img'],
                'rfid' => $profile['employee_rfid'],
                'name' => $profile['first_name'] . ' ' . $profile['last_name'],
            ],
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
