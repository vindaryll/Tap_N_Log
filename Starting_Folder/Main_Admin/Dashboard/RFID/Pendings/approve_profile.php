<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

$profile_id = intval($_POST['profile_id']);
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$profile_type = trim($_POST['type_of_profile']);
$profile_img = trim($_POST['profile_img']);
$rfid = trim($_POST['rfid']); // RFID field

$table_mapping = [
    'CFW' => ['table' => 'cfw_profile', 'folder' => '/TAPNLOG/Image/CFW/'],
    'OJT' => ['table' => 'ojt_profile', 'folder' => '/TAPNLOG/Image/OJT/'],
    'EMPLOYEE' => ['table' => 'employees_profile', 'folder' => '/TAPNLOG/Image/EMPLOYEES/'],
];

if (!isset($table_mapping[$profile_type])) {
    echo json_encode(['success' => false, 'message' => 'Invalid profile type.']);
    exit();
}

$destinationFolder = $_SERVER['DOCUMENT_ROOT'] . $table_mapping[$profile_type]['folder'];
$destinationPath = $destinationFolder . $profile_img;

// Move the image
$sourcePath = $_SERVER['DOCUMENT_ROOT'] . "/TAPNLOG/Image/Pending/" . $profile_img;
if (file_exists($sourcePath) && rename($sourcePath, $destinationPath)) {

    // Prepare the insert query
    $rfid_value = $rfid ? $rfid : NULL;
    
    $sql = "INSERT INTO {$table_mapping[$profile_type]['table']} (first_name, last_name, {$profile_type}_img, {$profile_type}_rfid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $first_name, $last_name, $profile_img, $rfid_value);

    if ($stmt->execute()) {
        // Delete from profile_registration
        $deleteSql = "DELETE FROM profile_registration WHERE profile_id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param('i', $profile_id);
        $deleteStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Profile approved successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save profile to database.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move image.']);
}
?>

