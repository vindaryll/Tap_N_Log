<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid vehicle ID.']);
    exit();
}

$vehicleId = intval($_POST['id']);
$query = "SELECT vehicle_id, first_name, last_name, plate_num, purpose, vehicle_pass 
          FROM vehicles 
          WHERE vehicle_id = ? AND is_archived = FALSE";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vehicleId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $record]);
} else {
    echo json_encode(['success' => false, 'message' => 'Vehicle not found.']);
}

$stmt->close();
$conn->close();
?>
