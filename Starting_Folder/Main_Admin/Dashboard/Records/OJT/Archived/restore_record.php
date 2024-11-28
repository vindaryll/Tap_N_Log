<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get POST data
    $attendance_id = $_POST['attendance_id'] ?? null;

    // Admin ID from session
    $admin_id = $_SESSION['admin_id'] ?? null;

    if (!$attendance_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Retrieve the attendance record and related profile data
        $sql = "
            SELECT ea.ojt_attendance_id, ea.ojt_id, ea.date_att, ea.time_in, ea.time_out, 
                   ep.first_name, ep.last_name
            FROM ojt_attendance ea
            JOIN ojt_profile ep ON ea.ojt_id = ep.ojt_id
            WHERE ea.ojt_attendance_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $attendance_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if record is found
        if ($result->num_rows === 0) {
            throw new Exception("Attendance record not found.");
        }

        // Fetch the record
        $row = $result->fetch_assoc();
        
        // Use variables for profile data and attendance details
        $attendance_id = $row['ojt_attendance_id'];
        $profile_id = $row['ojt_id']; // Now using profile_id
        $name = $row['first_name'] . ' ' . $row['last_name']; // Combining first and last name
        $date = $row['date_att'];
        $time_in = $row['time_in'];
        $time_out = $row['time_out'] ?? 'NOT COMPLETED';

        $formattedDate = date("F j, Y", strtotime($row['date_att']));
        $formattedTimeIn = date("g:i A", strtotime($row['time_in']));
        $formattedTimeOut = !empty($row['time_out']) ? date("g:i A", strtotime($row['time_out'])) : "NOT COMPLETED";

        // Restore the record (set is_archived to FALSE)
        $archive_query = "UPDATE ojt_attendance SET is_archived = FALSE WHERE ojt_attendance_id = ?";
        $stmt = $conn->prepare($archive_query);
        $stmt->bind_param("i", $attendance_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to archive the record.");
        }

        // Log the activity
        $details = "Restore On the Job Trainee Attendance\n\nRecord Id: $attendance_id \nProfile Id: $profile_id \nName: $name\nDate: $formattedDate\nTime in: $formattedTimeIn\nTime out: $formattedTimeOut";
        $log_query = "
            INSERT INTO admin_activity_log (section, details, category, admin_id) 
            VALUES ('RECORD', ?, 'RESTORE', ?)";
        $stmt = $conn->prepare($log_query);
        $stmt->bind_param("si", $details, $admin_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to log the activity.");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Attendance restored successfully.']);
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
