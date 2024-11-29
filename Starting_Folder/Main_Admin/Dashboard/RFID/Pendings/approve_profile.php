<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $profile_id = intval(sanitizeInput($_POST['profile_id']));
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $profile_type = sanitizeInput($_POST['type_of_profile']);
    $profile_img = sanitizeInput($_POST['profile_img']);
    $rfid = sanitizeInput($_POST['rfid']) ?: null; // Allow NULL for RFID

    // Admin ID for logging
    $admin_id = $_SESSION['admin_id'];

    // Define profile type mappings
    $table_mapping = [
        'CFW' => ['table' => 'cfw_profile', 'id_field' => 'cfw_id', 'img_field' => 'cfw_img', 'rfid_field' => 'cfw_rfid', 'folder' => '/TAPNLOG/Image/CFW/'],
        'OJT' => ['table' => 'ojt_profile', 'id_field' => 'ojt_id', 'img_field' => 'ojt_img', 'rfid_field' => 'ojt_rfid', 'folder' => '/TAPNLOG/Image/OJT/'],
        'EMPLOYEE' => ['table' => 'employees_profile', 'id_field' => 'employee_id', 'img_field' => 'employee_img', 'rfid_field' => 'employee_rfid', 'folder' => '/TAPNLOG/Image/EMPLOYEES/'],
    ];

    if (!isset($table_mapping[$profile_type])) {
        $response['message'] = 'Invalid profile type.';
        echo json_encode($response);
        exit();
    }

    // Source and destination paths
    $sourcePath = $_SERVER['DOCUMENT_ROOT'] . "/TAPNLOG/Image/Pending/" . $profile_img;
    $destinationFolder = $_SERVER['DOCUMENT_ROOT'] . $table_mapping[$profile_type]['folder'];
    $destinationPath = $destinationFolder . $profile_img;

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Move image file
        if (!file_exists($sourcePath) || !rename($sourcePath, $destinationPath)) {
            throw new Exception('Failed to move image.');
        }

        // Insert into the corresponding profile table
        $sql = "INSERT INTO {$table_mapping[$profile_type]['table']} (first_name, last_name, {$table_mapping[$profile_type]['img_field']}, {$table_mapping[$profile_type]['rfid_field']}) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $first_name, $last_name, $profile_img, $rfid);

        if (!$stmt->execute()) {
            throw new Exception('Failed to save profile to database.');
        }

        // Fetch the newly inserted record
        $inserted_id = $stmt->insert_id;
        $select_sql = "SELECT * FROM {$table_mapping[$profile_type]['table']} WHERE {$table_mapping[$profile_type]['id_field']} = ?";
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->bind_param('i', $inserted_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception('Failed to fetch the newly approved profile.');
        }
        $profile_data = $result->fetch_assoc();

        // Delete from `profile_registration`
        $deleteSql = "DELETE FROM profile_registration WHERE profile_id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param('i', $profile_id);
        if (!$deleteStmt->execute()) {
            throw new Exception('Failed to delete profile registration.');
        }

        // Prepare activity log details
        $logDetails = "Approve Profile:\n\nID: {$profile_data[$table_mapping[$profile_type]['id_field']]}\nName: {$profile_data['first_name']} {$profile_data['last_name']}\nType of Profile: $profile_type\nRFID: " . ($profile_data[$table_mapping[$profile_type]['rfid_field']] ?: 'None');
        $logQuery = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES ('RFID', ?, 'INSERT', ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param('si', $logDetails, $admin_id);
        if (!$logStmt->execute()) {
            throw new Exception('Failed to log activity.');
        }

        // Commit transaction
        $conn->commit();

        $response['success'] = true;
        $response['message'] = 'Profile approved successfully!';
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);

?>
