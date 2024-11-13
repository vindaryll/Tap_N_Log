<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$type_of_profile = trim($_POST['type_of_profile']);

$table_mapping = [
    'CFW' => 'cfw_profile',
    'OJT' => 'ojt_profile',
    'EMPLOYEE' => 'employees_profile',
];

if (!isset($table_mapping[$type_of_profile])) {
    echo json_encode(['success' => false, 'message' => 'Invalid profile type.']);
    exit();
}

$table = $table_mapping[$type_of_profile];

$sql = "SELECT first_name, last_name, cfw_img AS img, status, 'CFW' AS type FROM $table WHERE first_name = ? AND last_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $first_name, $last_name);
$stmt->execute();
$result = $stmt->get_result();

$duplicates = [];
while ($row = $result->fetch_assoc()) {
    $duplicates[] = $row;
}

echo json_encode(['success' => true, 'duplicates' => $duplicates]);
