<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $record_id = $_POST['record_id'] ?? null;

    // Retrieve session variables for main admin
    $admin_id = $_SESSION['admin_id'] ?? null;

    if (!$record_id || !$admin_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit();
    }

    // Fetch additional vehicle details (plate_num, vehicle_pass, purpose)
    $vehicle_query = "SELECT first_name, date_att, time_in, time_out, last_name, plate_num, vehicle_pass, purpose FROM vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($vehicle_query);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $vehicle_details = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$vehicle_details) {
        echo json_encode(['success' => false, 'message' => 'Vehicle record not found.']);
        exit();
    }

    $first_name = $vehicle_details['first_name'];
    $last_name = $vehicle_details['last_name'];
    $plate_num = $vehicle_details['plate_num'];
    $date = $vehicle_details['date_att'];
    $time_in = $vehicle_details['time_in'];
    $time_out = $vehicle_details['time_out'];
    $vehicle_pass = $vehicle_details['vehicle_pass'] ?? 'None';
    $purpose = $vehicle_details['purpose'];

    $formatted_date = date("F j, Y", strtotime($date));
    $formatted_time_in = date("g:i A", strtotime($time_in));
    $formatted_time_out = !empty($time_out) ? date("g:i A", strtotime($time_out)) : "NOT COMPLETED";

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Archive the vehicle record
        $archive_query = "UPDATE vehicles SET is_archived = FALSE WHERE vehicle_id = ?";
        $stmt = $conn->prepare($archive_query);
        $stmt->bind_param("i", $record_id);
        $stmt->execute();

        if (!$stmt) {
            error_log("Database error: " . $conn->error); // Log the error
            echo json_encode(['success' => false, 'message' => 'Failed to prepare query.']);
            exit();
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to restore the vehicle record.");
        }

        // Log the activity
        $details = "Restore Vehicle Log\n\nRecord Id: $record_id\nName: $first_name $last_name\nPlate Number: $plate_num\nDate: $formatted_date\nTime In: $formatted_time_in\nTime Out: $formatted_time_out\nVehicle Pass: $vehicle_pass\nPurpose: $purpose";

        $log_query = "
            INSERT INTO admin_activity_log (section, details, category, admin_id) 
            VALUES ('RECORD', ?, 'RESTORE', ?)";
        $stmt = $conn->prepare($log_query);
        $stmt->bind_param("si", $details, $admin_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to log the archive activity.");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Vehicle record restored successfully.']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => htmlspecialchars($e->getMessage())]);
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>
