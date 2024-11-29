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

if (!isset($_POST['profile_id']) || !isset($_POST['rfid']) || !isset($_POST['name']) || !isset($_POST['method']) || !isset($_POST['date']) || !isset($_POST['time'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input parameters']);
    exit();
}

// Escape and sanitize inputs
$profile_id = $conn->real_escape_string($_POST['profile_id']);
$rfid = $conn->real_escape_string($_POST['rfid'] ?? 'None'); // Default to "None" if null
$name = $conn->real_escape_string($_POST['name']);
$method = $conn->real_escape_string($_POST['method']);
$date = $conn->real_escape_string(date('Y-m-d', strtotime($_POST['date']))); // Convert to YYYY-MM-DD
$time = $conn->real_escape_string(date('H:i:s', strtotime($_POST['time']))); // Convert to HH:MM:SS

// Use session variables for guard and station
$guard_id = $_SESSION['guard_id'] ?? null;
$station_id = $_SESSION['station_id'] ?? null;

if (!$guard_id || !$station_id) {
    echo json_encode(['success' => false, 'message' => 'Missing guard or station information']);
    exit();
}

try {
    $conn->begin_transaction();

    // Insert into employees_attendance
    $stmt = $conn->prepare("INSERT INTO employees_attendance (employee_id, date_att, time_in) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $profile_id, $date, $time);
    $stmt->execute();
    $attendance_id = $stmt->insert_id; // Get the inserted record ID

    // Prepare activity log details with line breaks
    $formattedDate = date('F j, Y', strtotime($date)); 
    $formattedTime = date('g:i a', strtotime($time));
    $logDetails = "Insert Employee Attendance\n\n" .
                  "TIME IN\n\n" .
                  "Record Id: $attendance_id\n" .
                  "Profile Id: $profile_id\n" .
                  "Name: $name\n" .
                  "Date: $formattedDate\n" .
                  "Time in: $formattedTime\n" .
                  "Method: $method\n" .
                  "RFID: $rfid\n";

    // Insert into activity_log
    $stmt = $conn->prepare("INSERT INTO activity_log (section, details, category, station_id, guard_id) VALUES (?, ?, ?, ?, ?)");
    $section = 'EMPLOYEES';
    $category = 'INSERT';
    $stmt->bind_param('sssii', $section, $logDetails, $category, $station_id, $guard_id);
    $stmt->execute();

    $conn->commit();

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Time-in confirmed successfully.']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
