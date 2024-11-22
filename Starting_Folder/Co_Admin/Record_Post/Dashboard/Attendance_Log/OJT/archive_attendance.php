<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $attendance_id = $_POST['attendance_id'] ?? null;
    $profile_id = $_POST['profile_id'] ?? null;
    $name = $_POST['name'] ?? null;
    $date = $_POST['date'] ?? null;
    $time_in = $_POST['time_in'] ?? null;
    $time_out = $_POST['time_out'] ?? 'NOT COMPLETED';

    // Guard ID and Station ID from session
    $guard_id = $_SESSION['guard_id'] ?? null;
    $station_id = $_SESSION['station_id'] ?? null;

    if (!$attendance_id || !$profile_id || !$guard_id || !$station_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Archive the record
        $archive_query = "UPDATE ojt_attendance SET is_archived = TRUE WHERE ojt_attendance_id = ?";
        $stmt = $conn->prepare($archive_query);
        $stmt->bind_param("i", $attendance_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to archive the record.");
        }

        // Log the activity
        $details = "Archive On the Job Trainee Attendance\n\nRecord Id: $attendance_id \nProfile Id: $profile_id \nName: $name\nDate: $date\nTime in: $time_in\nTime out: $time_out";
        $log_query = "
            INSERT INTO activity_log (section, details, category, station_id, guard_id) 
            VALUES ('OJT', ?, 'ARCHIVE', ?, ?)";
        $stmt = $conn->prepare($log_query);
        $stmt->bind_param("sii", $details, $station_id, $guard_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to log the activity.");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Attendance archived successfully.']);
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>
