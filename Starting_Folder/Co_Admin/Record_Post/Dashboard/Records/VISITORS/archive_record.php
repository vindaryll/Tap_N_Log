<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $record_id = $_POST['record_id'] ?? null;

    // Retrieve session variables for guard and station
    $guard_id = $_SESSION['guard_id'] ?? null;
    $station_id = $_SESSION['station_id'] ?? null;

    if (!$record_id || !$guard_id || !$station_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit();
    }

    // Fetch additional visitor details (phone_num, visitor_pass, purpose)
    $visitor_query = "SELECT first_name, date_att, time_in, time_out, last_name, phone_num, visitor_pass, purpose FROM visitors WHERE visitor_id = ?";
    $stmt = $conn->prepare($visitor_query);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $visitor_details = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$visitor_details) {
        echo json_encode(['success' => false, 'message' => 'Visitor record not found.']);
        exit();
    }

    $first_name = $visitor_details['first_name'];
    $last_name = $visitor_details['last_name'];
    $phone_num = $visitor_details['phone_num'];
    $date = $visitor_details['date_att'];
    $time_in = $visitor_details['time_in'];
    $time_out = $visitor_details['time_out'];
    $visitor_pass = $visitor_details['visitor_pass'] ?? 'None';
    $purpose = $visitor_details['purpose'];

    $formatted_date = date("F j, Y", strtotime($date));
    $formatted_time_in = date("g:i A", strtotime($time_in));
    $formatted_time_out = !empty($time_out) ? date("g:i A", strtotime($time_out)) : "NOT COMPLETED";

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Archive the visitor record
        $archive_query = "UPDATE visitors SET is_archived = TRUE WHERE visitor_id = ?";
        $stmt = $conn->prepare($archive_query);
        $stmt->bind_param("i", $record_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to archive the visitor record.");
        }

        // Log the activity
        $details = "Archive Visitor Log\n\nRecord Id: $record_id\nName: $first_name $last_name\nPhone Number: $phone_num\nDate: $formatted_date\nTime In: $formatted_time_in\nTime Out: $formatted_time_out\nVisitor Pass: $visitor_pass\nPurpose: $purpose";

        $log_query = "
            INSERT INTO activity_log (section, details, category, station_id, guard_id) 
            VALUES ('VISITORS', ?, 'ARCHIVE', ?, ?)";
        $stmt = $conn->prepare($log_query);
        $stmt->bind_param("sii", $details, $station_id, $guard_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to log the archive activity.");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Visitor record archived successfully.']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>
