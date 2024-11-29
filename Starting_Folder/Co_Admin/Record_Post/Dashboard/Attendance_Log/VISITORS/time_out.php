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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $record_id = $conn->real_escape_string($_POST['record_id'] ?? '');
    $date = $conn->real_escape_string($_POST['date'] ?? '');
    $time_out = $conn->real_escape_string($_POST['time_out'] ?? '');

    if (empty($record_id) || empty($time_out)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit();
    }

    $conn->begin_transaction();

    try {
        // Fetch visitor's attendance details
        $fetchQuery = "SELECT first_name, last_name, phone_num, purpose, visitor_pass FROM visitors WHERE visitor_id = ? AND is_archived = FALSE";
        $fetchStmt = $conn->prepare($fetchQuery);
        $fetchStmt->bind_param("i", $record_id);
        $fetchStmt->execute();
        $result = $fetchStmt->get_result();
        $record = $result->fetch_assoc();

        if (!$record) {
            throw new Exception("Visitor record not found or already archived.");
        }

        $first_name = $record['first_name'];
        $last_name = $record['last_name'];
        $phone_num = $record['phone_num'];
        $purpose = $record['purpose'];
        $pass = !empty($record['visitor_pass']) ? $record['visitor_pass'] : "None";

        // Update the time_out in the visitors table
        $updateQuery = "UPDATE visitors SET time_out = ? WHERE visitor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $time_out, $record_id);
        $updateStmt->execute();

        if ($updateStmt->affected_rows <= 0) {
            throw new Exception("Failed to update time-out.");
        }

        // Format the data for activity log
        $formatted_date = date("F j, Y", strtotime($date));
        $formatted_time_out = date("g:i A", strtotime($time_out));
        $full_name = "$first_name $last_name";

        // Prepare log details
        $log_details = "Insert Visitor Log\n\nTIME OUT\n\nVisitor ID: $record_id\nName: $full_name\nPhone Number: $phone_num\nDate: $formatted_date\nTime-Out: $formatted_time_out\nVisitor Pass: $pass\nPurpose: $purpose";

        // Insert into activity_log
        $logQuery = "INSERT INTO activity_log (section, details, category, station_id, guard_id) VALUES ('VISITORS', ?, 'INSERT', ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $station_id = $_SESSION['station_id'] ?? null;
        $guard_id = $_SESSION['guard_id'] ?? null;
        $logStmt->bind_param("sii", $log_details, $station_id, $guard_id);
        $logStmt->execute();

        if ($logStmt->affected_rows <= 0) {
            throw new Exception("Failed to insert activity log.");
        }

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Time-out confirmed successfully.']);
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

    // Close connections
    $fetchStmt->close();
    $updateStmt->close();
    $logStmt->close();
    $conn->close();
}
?>
