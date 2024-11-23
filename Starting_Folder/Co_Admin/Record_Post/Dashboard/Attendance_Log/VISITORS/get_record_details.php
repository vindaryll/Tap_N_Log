<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid visitor ID.']);
    exit();
}

$visitorId = intval($_POST['id']);
$query = "SELECT first_name, last_name, phone_num, purpose, visitor_pass 
          FROM visitors 
          WHERE visitor_id = ? AND is_archived = FALSE";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $visitorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $record]);
} else {
    echo json_encode(['success' => false, 'message' => 'Visitor not found.']);
}

$stmt->close();
$conn->close();
?>
