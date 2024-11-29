<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['record_guard_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $rfid = $conn->real_escape_string($_POST['rfid'] ?? '');
    $date_today = $conn->real_escape_string($_POST['client_date'] ?? '');

    if (empty($rfid) || empty($date_today)) {
        echo json_encode(['success' => false, 'message' => 'Invalid RFID or date provided.']);
        exit();
    }

    try {
        // Check if profile exists for the RFID
        $query = "SELECT * FROM ojt_profile WHERE ojt_rfid = ? AND status = 'ACTIVE'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $rfid);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();

        if (!$profile) {
            echo json_encode(['success' => false, 'message' => 'No profile is registered for this RFID number.']);
            $stmt->close();
            $conn->close();
            exit();
        }

        // Check if attendance exists for today
        $query = "
            SELECT * FROM ojt_attendance 
            WHERE ojt_id = ? AND date_att = ? AND is_archived = FALSE";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $profile['ojt_id'], $date_today);
        $stmt->execute();
        $result = $stmt->get_result();
        $attendance = $result->fetch_assoc();

        if (!$attendance) {
            echo json_encode(['success' => false, 'message' => 'This profile has no attendance logged to time-out.']);
            $stmt->close();
            $conn->close();
            exit();
        }

        if ($attendance['time_out'] !== null) {
            echo json_encode(['success' => false, 'message' => 'This profile has already completed attendance for today.']);
            $stmt->close();
            $conn->close();
            exit();
        }

        // Prepare profile data for the modal
        $imageDirectory = '/tapnlog/Image/OJT/';
        $response = [
            'success' => true,
            'profile' => [
                'attendance_id' => $attendance['ojt_attendance_id'],
                'id' => $profile['ojt_id'],
                'image' => $imageDirectory . $profile['ojt_img'],
                'rfid' => $profile['ojt_rfid'],
                'name' => $profile['first_name'] . ' ' . $profile['last_name'],
                'time_in' => $attendance['time_in'] // Fetch time_in from attendance
            ]
        ];
        echo json_encode($response);

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        $conn->close();
    }
}
