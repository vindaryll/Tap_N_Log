<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

$profile_id = $_GET['profile_id'] ?? '';

$sql = "SELECT * FROM profile_registration WHERE profile_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

echo json_encode($profile);
