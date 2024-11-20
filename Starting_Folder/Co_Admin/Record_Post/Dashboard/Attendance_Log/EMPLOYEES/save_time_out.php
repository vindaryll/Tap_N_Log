<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $attendance_id = $conn->real_escape_string($_POST['attendance_id'] ?? '');
    $profile_id = $conn->real_escape_string($_POST['profile_id'] ?? '');
    $time_out = $conn->real_escape_string($_POST['time_out'] ?? '');
    $rfid = $conn->real_escape_string($_POST['rfid'] ?? '');
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $method = $conn->real_escape_string($_POST['method'] ?? 'MANUAL');

    if (empty($attendance_id) || empty($time_out) || empty($profile_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit();
    }

    $conn->begin_transaction();

    try {
        // Update the time_out in employees_attendance
        $query = "UPDATE employees_attendance SET time_out = ? WHERE employee_attendance_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $time_out, $attendance_id);
        $stmt->execute();

        if ($stmt->affected_rows <= 0) {
            throw new Exception("Failed to update time-out record.");
        }

        // Format date and time
        $formatted_date = date('F j, Y'); // Format as "November 20, 2024"
        $formatted_time_out = date('g:i A', strtotime($time_out)); // Format as "11:30 PM"

        // Log the activity in activity_log
        $details = "Insert Employee Attendance\n\nTIME OUT\n\nRecord Id: $attendance_id\nProfile Id: $profile_id\nName: $name\nDate: $formatted_date\nTime out: $formatted_time_out\nMethod: $method\nRFID: $rfid";

        $query = "
            INSERT INTO activity_log (section, details, category, guard_id, station_id) 
            VALUES ('EMPLOYEES', ?, 'UPDATE', ?, ?)";
        $stmt = $conn->prepare($query);

        // Example station_id and guard_id from session or default values
        $guard_id = $_SESSION['record_guard_id'] ?? 1; // Replace with actual guard ID from session
        $station_id = $_SESSION['station_id'] ?? 1;   // Replace with actual station ID from session

        $stmt->bind_param("sii", $details, $guard_id, $station_id);
        $stmt->execute();

        if ($stmt->affected_rows <= 0) {
            throw new Exception("Failed to insert activity log.");
        }

        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Time-out updated and activity log inserted successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
}
