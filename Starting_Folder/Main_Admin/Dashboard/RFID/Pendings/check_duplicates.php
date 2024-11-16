<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Sanitize inputs
$first_name = sanitizeInput($_POST['first_name']);
$last_name = sanitizeInput($_POST['last_name']);
$type_of_profile = sanitizeInput($_POST['type_of_profile']);

// Map profile types to their respective tables and column names
$table_mapping = [
    'CFW' => [
        'table' => 'cfw_profile',
        'img_column' => 'cfw_img',
        'rfid_column' => 'cfw_rfid',
        'display_name' => 'Cash for Work Staff'
    ],
    'OJT' => [
        'table' => 'ojt_profile',
        'img_column' => 'ojt_img',
        'rfid_column' => 'ojt_rfid',
        'display_name' => 'On the Job Trainee'
    ],
    'EMPLOYEE' => [
        'table' => 'employees_profile',
        'img_column' => 'employee_img',
        'rfid_column' => 'employee_rfid',
        'display_name' => 'Employee'
    ],
];

// Validate the profile type
if (!isset($table_mapping[$type_of_profile])) {
    echo json_encode(['success' => false, 'message' => 'Invalid profile type.']);
    exit();
}

$table_info = $table_mapping[$type_of_profile];
$table = $table_info['table'];
$img_column = $table_info['img_column'];
$rfid_column = $table_info['rfid_column'];
$display_name = $table_info['display_name'];

// Construct the SQL query
$sql = "SELECT first_name, last_name, $img_column AS img, $rfid_column AS rfid, status,
        DATE_FORMAT(date_approved, '%M %d, %Y') AS formatted_date, ? AS type 
        FROM $table 
        WHERE first_name = ? AND last_name = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $display_name, $first_name, $last_name);
$stmt->execute();
$result = $stmt->get_result();

$duplicates = [];
while ($row = $result->fetch_assoc()) {
    $duplicates[] = $row;
}

// Return the duplicates as a JSON response
echo json_encode(['success' => true, 'duplicates' => $duplicates]);
?>
