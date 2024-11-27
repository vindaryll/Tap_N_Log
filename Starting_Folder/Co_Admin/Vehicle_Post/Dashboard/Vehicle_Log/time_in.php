<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Check if necessary POST data is available
if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['purpose'], $_POST['plate_number'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


// Extract data from POST
$firstName = $conn->real_escape_string(sanitizeInput($_POST['first_name']));
$lastName = $conn->real_escape_string(sanitizeInput($_POST['last_name']));
$plateNumber = $conn->real_escape_string(sanitizeInput($_POST['plate_number']));
$vehiclePass = isset($_POST['vehicle_pass']) && !empty($_POST['vehicle_pass']) 
    ? $conn->real_escape_string(sanitizeInput($_POST['vehicle_pass'])) 
    : null; // Assign NULL if empty
$purpose = $conn->real_escape_string(sanitizeInput($_POST['purpose']));
$date = $conn->real_escape_string(date('Y-m-d', strtotime($_POST['date'])));
$timeIn = $conn->real_escape_string(date('H:i:s', strtotime($_POST['time_in'])));

$formattedDate = $conn->real_escape_string(sanitizeInput($_POST['date_format']));
$formattedTime = date('g:i a', strtotime($timeIn));
$logVehiclePass = $vehiclePass ?? "None";

// Start transaction
$conn->begin_transaction();

try {
    // Insert vehicle data into vehicles table
    $stmt = $conn->prepare("INSERT INTO vehicles (first_name, last_name, plate_num, vehicle_pass, purpose, date_att, time_in) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstName, $lastName, $plateNumber, $vehiclePass, $purpose, $date, $timeIn);
    $stmt->execute();
    $vehicleId = $stmt->insert_id;
    $stmt->close();

    // Prepare activity log
    $details = "Insert Vehicle Log\n\nTIME IN\n\nRecord Id: $vehicleId\nName: $firstName $lastName\nPlate Number: $plateNumber\nDate: $formattedDate\nTime in: $formattedTime\nVehicle pass: $logVehiclePass\nPurpose: $purpose";
    $section = 'VEHICLES';
    $category = 'INSERT';
    $stationId = $_SESSION['station_id'] ?? 0;
    $guardId = $_SESSION['guard_id'] ?? 0;

    // Log activity
    $stmtLog = $conn->prepare("INSERT INTO activity_log (section, details, category, station_id, guard_id) VALUES (?, ?, ?, ?, ?)");
    $stmtLog->bind_param("sssii", $section, $details, $category, $stationId, $guardId);
    $stmtLog->execute();
    $stmtLog->close();

    // Commit transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Time-in confirmed successfully.']);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Transaction failed: " . $e->getMessage()); // Log the error for debugging
    echo json_encode(['success' => false, 'message' => 'Transaction failed. Please try again.']);
}

$conn->close();
?>