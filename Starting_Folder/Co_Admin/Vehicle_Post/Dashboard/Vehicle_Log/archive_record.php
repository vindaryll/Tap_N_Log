<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $record_id = $_POST['record_id'] ?? null;
    $time_out = $_POST['time_out'] ?? 'NOT COMPLETED';

    // Retrieve session variables for guard and station
    $guard_id = $_SESSION['guard_id'] ?? null;
    $station_id = $_SESSION['station_id'] ?? null;

    if (!$record_id || !$guard_id || !$station_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit();
    }

    // Fetch additional vehicle details (plate_num, vehicle_pass, purpose)
    $vehicle_query = "SELECT first_name, date_att, time_in, last_name, plate_num, vehicle_pass, purpose FROM vehicles WHERE vehicle_id = ?";
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
    $vehicle_pass = $vehicle_details['vehicle_pass'] ?? 'None';
    $purpose = $vehicle_details['purpose'];

    $formatted_date = date("F j, Y", strtotime($date));
    $formatted_time_in = date("g:i A", strtotime($time_in));

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Archive the vehicle record
        $archive_query = "UPDATE vehicles SET is_archived = TRUE WHERE vehicle_id = ?";
        $stmt = $conn->prepare($archive_query);
        $stmt->bind_param("i", $record_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to archive the vehicle record.");
        }

        // Log the activity
        $details = "Archive Vehicle Log\n\nRecord Id: $record_id\nName: $first_name $last_name\nPlate Number: $plate_num\nDate: $formatted_date\nTime In: $formatted_time_in\nTime Out: $time_out\nVehicle Pass: $vehicle_pass\nPurpose: $purpose";

        $log_query = "
            INSERT INTO activity_log (section, details, category, station_id, guard_id) 
            VALUES ('VEHICLES', ?, 'ARCHIVE', ?, ?)";
        $stmt = $conn->prepare($log_query);
        $stmt->bind_param("sii", $details, $station_id, $guard_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to log the archive activity.");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Vehicle record archived successfully.']);
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
